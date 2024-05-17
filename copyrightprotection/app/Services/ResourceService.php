<?php

namespace App\Services;


use App\Helpers\Sorter;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceService
{
    public $fileServise;

    public function __construct()
    {
        $this->fileServise = new PublicFilesService();
    }

    public function create($data)
    {
        if ($data->hasFile('featured_image')) {

            $featured_image = $this->fileServise->store($data->file('featured_image'), PublicFilesService::PUBLIC_RESOURCE_SOURCE);

            $data = $data->validated();

            if ($featured_image) {
                $data['featured_image_url'] = $featured_image;
            }
        }else {
            $data = $data->validated();
        }

        return Resource::create($data);
    }

    public function update($data, Resource $resource)
    {
        if ($data->hasFile('featured_image')) {
            if (!empty($resource->featured_image_url)){
                $this->fileServise->delete($resource->featured_image_url);
            }

            $featured_image = $this->fileServise->store($data->file('featured_image'), PublicFilesService::PUBLIC_RESOURCE_SOURCE);

            $data = $data->validated();

            if ($featured_image) {
                $data['featured_image_url'] = $featured_image;
            }
        }else{
            $data = $data->validated();
        }

        return $resource->update($data);
    }

    public function delete(Resource $resource)
    {
        if (!empty($resource->featured_image_url)){
            $this->fileServise->delete($resource->featured_image_url);
        }
        return $resource->delete();
    }

    public static function getResourceAndSorter($perPage = 10)
    {
        //param name => table column name
        $orderByColumns = [
            'sortByTitle' => 'title',
            'sortByStatus' => 'status',
            'sortByCreatedDate' => 'created_at',
        ];
        $sorter = new Sorter($orderByColumns);

        $resources = Resource::withTrashed()
            ->from('resource_posts')
            ->whereNull('deleted_at');

        $filterByTitle = request()->input('filterByTitle');
        $filterByStatus = request()->input('filterByStatus');

        $resources
            ->when($filterByTitle, function ($q) use ($filterByTitle) {
                return $q->where('title', $filterByTitle);
            })
            ->when($filterByStatus, function ($q) use ($filterByStatus) {
                return $q->where('status', $filterByStatus);
            });

        $resources->orderBy($sorter->getOrderByColumn(), $sorter->getOrderByDirection());


        if ($perPage === 'All') {
            $projects = $resources->get();
        } else {
            $projects = $resources->paginate($perPage)->withQueryString();
        }

        return [$projects, $sorter];
    }

}

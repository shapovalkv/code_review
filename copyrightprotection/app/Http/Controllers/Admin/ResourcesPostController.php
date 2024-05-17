<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateResourceRequest;
use App\Models\Resource;
use App\Services\ResourceService;

class ResourcesPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ResourceService $resourceService)
    {
        $perPage = request()->input('perPage', 10);
        list($resources, $sorter) = $resourceService->getResourceAndSorter($perPage);
        return view('admin.resources.index', compact('resources', 'sorter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.resources.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateResourceRequest $request, ResourceService $resourceService)
    {
        if ($resourceService->create($request)){
            return redirect(route('admin.resources.index'))->with('success', __('messages.resources_created'));
        } return redirect(route('admin.resources.index'))->with('error', __('messages.resources_created_fail'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', ['resource' => $resource]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateResourceRequest $request, Resource $resource, ResourceService $resourceService)
    {
        if ($resourceService->update($request, $resource)){
            return redirect(route('admin.resources.index'))->with('success', __('messages.resources_updated'));
        } return redirect(route('admin.resources.index'))->with('error', __('messages.resources_updated_fail'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource, ResourceService $resourceService)
    {
        $action = $resourceService->delete($resource);
        if ($action){
            return redirect(route('admin.resources.index'))->with('success', __('messages.resources_deleted'));
        } else {
            return redirect(route('admin.resources.index'))->with('success', __('messages.resources_deleted_fail'));
        }
    }
}

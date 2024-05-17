<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateResourceRequest;
use App\Models\Resource;
use App\Services\FilesService;
use App\Services\ResourceService;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.resources', ['resources' => Resource::where('status', '=', Resource::PUBLISH)->paginate(10, ['*'], 'resources')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        return view('pages.resources-single', ['resource' => $resource, 'resources' => Resource::where('status', '=', Resource::PUBLISH)->latest()->take(3)->get()]);
    }
}

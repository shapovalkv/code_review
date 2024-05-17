@php
    use App\Models\User;
@endphp
<x-app-layout>
    <div class="row gx-3">
        <div class="col-lg-12 pe-lg-2">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"> Edit {{$resource->title}}</h5>
                </div>
                <div class="card-body bg-light">

                    <form class="row g-3" action="{{ route('admin.resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-6">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control" name="title" id="title-name" type="text" value="{{old('title', $resource->title)}}">
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input name="role" type="hidden" value="{{$resource->title}}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-center" for="status">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="{{\App\Models\Resource::PUBLISH}}" {{ old('status', $resource->status) == \App\Models\Resource::PUBLISH ? 'selected' : '' }}>Publish</option>
                                <option value="{{\App\Models\Resource::DRAFT}}" {{ old('status', $resource->status) == \App\Models\Resource::DRAFT ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label text-center" for="status">Featured Image</label>
                            <label class="block mt-2">
                                <span class="sr-only">Choose image</span>
                                <input type="file" id="featured_image" name="featured_image" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-violet-50 file:text-violet-700
                                    hover:file:bg-violet-100
                                "/>
                            </label>
                            <div class="shrink-0 my-2">
                                <img id="featured_image_preview" class="h-64 w-128" src="{{ $resource->featured_image_url }}" alt="{{ $resource->featured_image_url }}" />
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                        </div>
                        <div class="log-lg-12">
                            <label class="form-label text-center" for="content">Content</label>
                            <textarea class="tinymce d-none" data-tinymce="data-tinymce" name="content" id="content">{{ old('content', $resource ?? null) }}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dark" type="submit">Save</button>
                        </div>
                    </form>
                    @if(auth()->user()->hasRole(User::ROLE_ADMIN))
                    <hr>
                    <div class="col-12 d-flex justify-content-start">
                        <form method="POST"
                              action="{{ route('admin.resources.destroy', ['resource' => $resource]) }}"
                              style="display:inline-block">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Are you sure want to delete?')"
                                    class="btn btn-danger"
                                    title="Delete user">
                                Delete Post
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="row gx-3">
        <div class="col-lg-12 pe-lg-2">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"> Create Resource Post</h5>
                </div>
                <div class="card-body bg-light">
                    <form class="row g-3" action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-6">
                            <label class="form-label" for="first-name">Title</label>
                            <input class="form-control" name="title" id="title" type="text" value="{{old('title')}}">
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-center" for="status">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="{{ \App\Models\Resource::PUBLISH }}" {{ old('status') == \App\Models\Resource::PUBLISH ? 'selected' : '' }}>Publish</option>
                                <option value="{{ \App\Models\Resource::DRAFT }}" {{ old('status') == \App\Models\Resource::DRAFT ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="featured_image" value="Featured Image" />
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
                            <x-input-error class="mt-2" :messages="$errors->get('featured_image')" />
                        </div>

                        <div class="col-lg-12">
                            <div class="log-lg-12">
                                <label class="form-label text-center" for="content">Content</label>
                                <textarea class="tinymce d-none" data-tinymce="data-tinymce" name="content" id="content">{{ old('content', $resource ?? null) }}</textarea>
                            </div>
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dark" type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    @include('components.orders-buttons')
    <div class="row g-0">
        <div class="col-lg-16">

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Social media</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.keywords.create') }}" method="post" class="row g-3">
                        @csrf
                        <label for="basic-url">Insert whitelisted keywords</label>
                        <div class="input-group mb-3">
                            <input class="form-control" name="content" id="first-name" type="text" />
                            <button class="btn btn-dark ms-2" type="submit">Add </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <label class="form-label" for="first-name">Or import your file here</label>
                    <form action="{{ route('user.keywords.import') }}" method="post" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-12 d-flex justify-content-start">
                            <input type="file"  name="import_keywords" class="form-control">
                            <button class="btn btn-dark ms-2" type="submit">Import</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive scrollbar">
                    <table class="table">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($whitelistedKeywords as $whitelistedKeyword)
                            <tr>
                                <td>{{ $whitelistedKeyword->content }}</td>
                                <td class="text-end">
                                    <div>
                                        <a href="{{ route('user.keywords.delete', ['whitelistedKeyword' => $whitelistedKeyword->id]) }}"
                                           class="btn btn-link p-0 ms-2" type="button"
                                           data-bs-toggle="tooltip" data-bs-placement="top"
                                           title="Delete"><span
                                                class="text-500 fas fa-trash-alt"></span></a></div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        {!! $whitelistedKeywords->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    @include('components.orders-buttons')
    <div class="row g-0">
        <div class="col-lg-16">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Social media</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.accounts.create') }}" method="post" class="row g-3">
                        @csrf
                        <div class="col-lg-6">
                            <label class="form-label" for="first-name">Insert whitelisted accounts here</label>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">www.example.com</span>
                            </div>
                            <input type="text" name="content" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            <button class="btn btn-dark ms-2" type="submit">Add</button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <label class="form-label" for="first-name">Or import your file here</label>
                    <form action="{{ route('user.accounts.import') }}" method="post" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-12 d-flex justify-content-start">
                            <input type="file" name="import_accounts" class="form-control">
                            <button class="btn btn-dark ms-2" type="submit" style="border: 1px solid #fff;">Import</button>
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
                        @foreach($whitelistedAccounts as $whitelistedAccount)
                            <tr>
                                <td>{{ $whitelistedAccount->content }}</td>
                                <td class="text-end">
                                    <div><a href="{{ route('user.accounts.delete', ['whitelistedAccount' => $whitelistedAccount->id]) }}" class="btn btn-link p-0 ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="text-500 fas fa-trash-alt"></span></a></div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="card-footer d-flex align-items-center justify-content-center">
                        {!! $whitelistedAccounts->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

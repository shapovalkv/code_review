<x-app-layout>
    @include('components.orders-buttons')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0" data-anchor="data-anchor">Multiple File Upload</h5>
                </div>
            </div>
        </div>

        <div class="card-body">
            <livewire:legal-document-livewire project="{{ Auth::user()->selectedProject->id }}"/>
        </div>
    </div>
</x-app-layout>

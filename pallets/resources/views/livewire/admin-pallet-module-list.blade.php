<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="row">
                    <label for="left_pallet_position_id">FilterBy Category: </label>
                    <select class="browser-default"  wire:model.live.throttle.500ms="selectedCategory">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class=" col-md-4">
                    <input wire:model.live.throttle.500ms="search" type="text" id="searchInput" class="form-control"
                           placeholder="Search by name, assembly_no">
                </div>
                <table class="highlight">
                    <thead>
                    <tr>
                        <th class="align-middle py-3">Name</th>
                        <th class="align-middle py-3">Category</th>
                        <th class="align-middle">Assembly number</th>
                        <th class="align-middle">Cost</th>
                    </tr>
                    </thead>
                    <tbody class="list" id="table-ticket-body">

                    @foreach($palletizer_modules as $key => $module)
                        <tr>
                            <td class="align-middle">
                                <h6 class="mb-0"><a href="{{ route('pallet.module.show', ['module' => $module->id]) }}">{{$module->name }}</a></h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$module->category->name }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$module->assembly_no }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$module->cost }}</h6>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $palletizer_modules->links() }}
            </div>
        </div>
    </div>
</div>

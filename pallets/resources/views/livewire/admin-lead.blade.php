<div class="py-12">
    <div class="max-w-12xl mx-auto sm:px-12 lg:px-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <table class="highlight">
                    <thead>
                    <tr>
                        <th class="align-middle py-3">Gripper</th>
                        <th class="align-middle py-3">Product infeed</th>
                        <th class="align-middle py-3">Replacement Product infeed</th>
                        <th class="align-middle">Left pallet position</th>
                        <th class="align-middle">Replacement Left pallet position</th>
                        <th class="align-middle">Right pallet position</th>
                        <th class="align-middle">Replacement Right pallet position</th>
                        <th class="align-middle">System pallet height</th>
                        <th class="align-middle">Product name</th>
                        <th class="align-middle">Product type</th>
                        <th class="align-middle">Product length</th>
                        <th class="align-middle">Product width</th>
                        <th class="align-middle">Product height</th>
                        <th class="align-middle">Product weight</th>
                        <th class="align-middle">Infeed rate</th>
                        <th class="align-middle">Pallet length</th>
                        <th class="align-middle">Pallet width</th>
                        <th class="align-middle">Pallet height</th>
                        <th class="align-middle">Robot</th>
                    </tr>
                    </thead>
                    <tbody class="list" id="table-ticket-body">

                    @foreach($configurations as $key => $configuration)
                        <tr>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->tool->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->infeedPosition->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->replacementInfeedPosition?->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->leftPosition->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->replacementLeftPosition?->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->rightPosition->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->replacementRightPosition?->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->system_pallet_height ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->product_name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->productType->name ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->product_length ?? 'Not filled' }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->product_width  ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->product_height ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->product_weight ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->product_infeed_rate ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->pallet_length ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->pallet_width ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->pallet_height ?? 'Not filled'}}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$configuration->robot->name ?? 'Not filled'}}</h6>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
{{--                {{ $configurations->links() }}--}}
            </div>
        </div>
    </div>
</div>

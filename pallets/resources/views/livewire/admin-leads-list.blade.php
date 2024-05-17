<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <table class="highlight">
                    <thead>
                    <tr>
                        <th class="align-middle py-3">First Name</th>
                        <th class="align-middle py-3">Last Name</th>
                        <th class="align-middle">Email</th>
                        <th class="align-middle">Phone</th>
                        <th class="align-middle">Company</th>
                    </tr>
                    </thead>
                    <tbody class="list" id="table-ticket-body">

                    @foreach($leads as $key => $lead)
                        <tr>
                            <td class="align-middle">
                                <h6 class="mb-0"><a href="{{ route('lead.show', ['lead' => $lead->id]) }}">{{$lead->first_name }}</a></h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$lead->last_name }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$lead->email }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$lead->phone }}</h6>
                            </td>
                            <td class="align-middle">
                                <h6 class="mb-0">{{$lead->company }}</h6>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $leads->links() }}
            </div>
        </div>
    </div>
</div>

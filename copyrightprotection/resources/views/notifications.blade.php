<x-app-layout>
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
        @include('components.orders-buttons')
    @endif
    <div class="row gx-3">
        <div class="col-xxl-12 col-xl-12">
            <div class="card mb-3">
                <div class="card-header border-bottom border-200 px-0">
                    <div class="d-lg-flex justify-content-between">
                        <div class="row flex-between-center gy-2 px-x1">
                            <div class="col-auto pe-0">
                                <h5 class="mb-0">Unread Notifications</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">

                    <div class="table-responsive scrollbar">
                        <table class="table table-sm mb-0 fs--1 table-view-tickets">
                            <thead class="text-800 bg-light">
                            <tr>
                                <th class="align-middle py-2">Project name</th>
                                @if(!auth()->user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                                    <th class="align-middle">Author</th>
                                @endif
                                <th class="align-middle">Content</th>
                                <th class="align-middle text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="list" id="table-ticket-body">
                            @foreach($unreadNotifications as $unreadNotification)
                                <tr>
                                    <td class="align-middle py-2"><a href="{{route('agent.user.project', $unreadNotification->data['project_id'])}}" >{{ $unreadNotification->data['project_name'] }}</a></td>
                                    @if(!auth()->user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                                        <td class="align-middle"><a href="{{route('agent.customer.view', $unreadNotification->data['user_id'])}}">{{ $unreadNotification->data['user_name'] }}</a></td>
                                    @endif
                                    <td class="align-middle">{{ $unreadNotification->data['content'] }}</td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('account.markNotification', ['id' => $unreadNotification->id]) }}" class="float-right mark-as-read" data-id="{{ $unreadNotification->id }}">
                                            Mark as read
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    {{ $unreadNotifications->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

        <div class="col-xxl-12 col-xl-12">
            <div class="card mb-3">
                <div class="card-header border-bottom border-200 px-0">
                    <div class="d-lg-flex justify-content-between">
                        <div class="row flex-between-center gy-2 px-x1">
                            <div class="col-auto pe-0">
                                <h5 class="mb-0">All Notifications</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive scrollbar">
                        <table class="table table-sm mb-0 fs--1 table-view-tickets">
                            <thead class="text-800 bg-light">
                            <tr>
                                <th class="align-middle py-2">Project name</th>
                                @if(!auth()->user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                                    <th class="align-middle">Author</th>
                                @endif
                                <th class="align-middle text-end">Content</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td class="align-middle py-2"><a href="{{route('agent.user.project', $notification->data['project_id'])}}" >{{ $notification->data['project_name'] }}</a></td>
                                    @if(!auth()->user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                                        <td class="align-middle"><a href="{{route('agent.customer.view', $notification->data['user_id'])}}">{{ $notification->data['user_name'] }}</a></td>
                                    @endif
                                    <td class="align-middle text-end">{{ $notification->data['content'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    {{ $notifications->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="row gx-3">
        <div class="col-xxl-12 col-xl-12">
            <div class="card">
                <div class="card-header border-bottom border-200 px-0">
                    <div class="d-flex justify-content-between">
                        <div class="row flex-between-center gy-2 px-x1">
                            <div class="col-auto pe-0">
                                <h5 class="mb-0">{{\Illuminate\Support\Str::plural($role->title)}}</h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end px-x1">
                            <div class="d-flex align-items-center" id="table-ticket-replace-element">
                                @if($role->name === \App\Models\User::ROLE_AGENT || $role->name === \App\Models\User::ROLE_ADMIN)

                                    <a class="btn btn-falcon-default btn-sm"
                                       href="{{route('admin.users.create', ['role' => $role->name])}}">
                                        <svg class="svg-inline--fa fa-plus fa-w-14"
                                             data-fa-transform="shrink-3"
                                             aria-hidden="true" focusable="false" data-prefix="fas"
                                             data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 448 512" data-fa-i2svg=""
                                             style="transform-origin: 0.4375em 0.5em;">
                                            <g transform="translate(224 256)">
                                                <g transform="translate(0, 0)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                                    <path fill="currentColor"
                                                          d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"
                                                          transform="translate(-224 -256)"></path>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="d-sm-inline-blockd-xxl-inline-block ms-1"> New </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive scrollbar">
                        <table class="table table-sm mb-0 fs--1 table-view-tickets">
                            <thead class="text-800 bg-light">
                            <tr>
                                <th class="align-middle py-3">Name</th>
                                <th class="align-middle">Email</th>
                                <th class="align-middle" >Phone</th>
                                <th class="align-middle" >Created at</th>
                                <th class="align-middle text-end" >Status</th>
                            </tr>
                            </thead>
                            <tbody class="list" id="table-ticket-body">
                            @foreach($users as $key => $user)
                                <tr>
                                    <td class="align-middle py-3">
                                        <div class="d-flex align-items-center gap-2 position-relative">
                                            <div class="avatar avatar-xl">
                                                <div class="avatar-name rounded-circle">
                                                    <span>{{substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)}}</span>
                                                </div>
                                            </div>
                                            <h6 class="mb-0">
                                                @if($user->deleted_at)
                                                    <span class="text-light-emphasis">{{$user->first_name . ' ' . $user->last_name}}</span>
                                                @else
                                                    <a href="{{route('admin.users.edit', $user->id)}}">{{$user->first_name . ' ' . $user->last_name}}</a>
                                                @endif

                                            </h6>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2 position-relative">
                                            <h6 class="mb-0">{{$user->email}}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2 position-relative">
                                            <h6 class="mb-0">{{$user->phone}}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2 position-relative">
                                            <h6 class="mb-0">{{ $user->created_at->format('m.d.y / H:i:s') }}</h6>
                                        </div>
                                    </td>
                                    @php
                                        if ($user->deleted_at) {
                                            $statusClass = 'badge-status-danger';
                                            $statusName = 'Deleted';
                                        } else {
                                            $statusClass = 'badge-status-success';
                                            $statusName = 'Active';
                                        }

                                    @endphp
                                    <td class="align-middle text-end">
                                        <small class="badge rounded {{$statusClass}}">{{$statusName}}</small>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer d-flex align-items-center justify-content-center">
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

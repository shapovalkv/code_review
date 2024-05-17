@php
    use App\Models\User;
    $isAdmin = auth()->user()->hasRole(User::ROLE_ADMIN);
@endphp
<x-app-layout>
    <div class="row g-3">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between py-2">
                                <nav style="--falcon-breadcrumb-divider: '»';" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        @php
                                            $dashboardLink = auth()->user()->hasRole(User::ROLE_AGENT) ? route('agent.dashboard') : '';
                                            $dashboardLink = auth()->user()->hasRole(User::ROLE_ADMIN) ? route('admin.dashboard') : $dashboardLink;
                                        @endphp
                                        <li class="breadcrumb-item"><a href="{{ $dashboardLink }}">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">User Project</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-10 col-xl-9">
                        <div class="card">
                            <div class="card-header border-bottom border-200 px-0">
                                <div class="d-flex justify-content-between">
                                    <div class="row flex-between-center gy-2 px-x1">
                                        <div class="col-auto pe-0">
                                            <h5 class="mb-0">Projects</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between justify-content-end px-x1">
                                        <button class="btn btn-sm btn-falcon-default d-xl-none" type="button"
                                                data-bs-toggle="offcanvas" data-bs-target="#ticketOffcanvas"
                                                aria-controls="ticketOffcanvas">
                                            <svg class="svg-inline--fa fa-filter fa-w-16"
                                                 data-fa-transform="shrink-4 down-1" aria-hidden="true"
                                                 focusable="false" data-prefix="fas" data-icon="filter" role="img"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                 data-fa-i2svg="" style="transform-origin: 0.5em 0.5625em;">
                                                <g transform="translate(256 256)">
                                                    <g transform="translate(0, 32)  scale(0.75, 0.75)  rotate(0 0 0)">
                                                        <path fill="currentColor"
                                                              d="M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z"
                                                              transform="translate(-256 -256)"></path>
                                                    </g>
                                                </g>
                                            </svg>
                                            <span class="ms-1 d-none d-sm-inline-block">Filter</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive scrollbar">
                                    <table class="table table-sm mb-0 fs--1 table-view-tickets">
                                        <thead class="text-800 bg-light">
                                        <tr>
                                            @php
                                                $sorter->setAscClass(' desc ');
                                                $sorter->setDescClass(' asc ');
                                                $sorter->setClassForSelectedHeader(' sort-link ');
                                                $sorter->setClassForNotSelectedHeader(' sort-link ');
                                            @endphp
                                            <th class="align-middle py-3">{!! $sorter->sortableLink('sortByName', 'Name') !!}</th>
                                            <th class="align-middle">{!! $sorter->sortableLink('sortByUser', 'Customer') !!}</th>
                                            <th class="align-middle">{!! $sorter->sortableLink('sortByReportDate', 'Last Report Date') !!}</th>
                                            <th class="align-middle">{!! $sorter->sortableLink('sortByPlan', 'Plan') !!}</th>
                                            <th class="align-middle @if(!$isAdmin) text-end @endif">{!! $sorter->sortableLink('sortByStatus', 'Status') !!}</th>
                                            @if($isAdmin)
                                                <th class="align-middle text-end">{!! $sorter->sortableLink('sortByAgent', 'Agent') !!}</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody class="list" id="table-ticket-body">

                                        @foreach($projects as $key => $project)
                                            <tr>
                                                <td class="align-middle py-3">
                                                    <h6 class="mb-0" title="{{$project->name}}"><a href="{{route('agent.user.project', $project->id)}}" > {{\Illuminate\Support\Str::limit($project->name, 15)}}</a></h6>
                                                </td>

                                                <td class="align-middle">
                                                    <h6 class="mb-0"><a href="{{route('agent.customer.view', $project->customer_id)}}"> {{$project->customer}}</a></h6>
                                                </td>
                                                <td class="align-middle">
                                                    <h6 class="mb-0">{{$project->report_date }}</h6>
                                                </td>
                                                <td class="align-middle">
                                                    <h6 class="mb-0">{{$project->plan_name }}</h6>
                                                </td>
                                                @php

                                                    switch ($project->status) {
                                                        case \App\Models\UserProject::ACTIVE:
                                                            $statusClass = 'badge-status-success';
                                                            break;
                                                        case \App\Models\UserProject::DRAFT:
                                                            $statusClass = 'badge-status-secondary';
                                                            break;
                                                        case \App\Models\UserProject::CREATED:
                                                            $statusClass = 'badge-status-info';
                                                            break;
                                                        case \App\Models\UserProject::IN_ACTIVE:
                                                            $statusClass = 'badge-status-in-active';
                                                            break;
                                                        default:
                                                            $statusClass = '';
                                                    }

                                                @endphp
                                                <td class="align-middle @if(!$isAdmin) text-end @endif">
                                                    <small class="badge rounded {{$statusClass}}">{{\App\Models\UserProject::STATUSES[$project->status]}}</small>
                                                </td>

                                                @if($isAdmin)
                                                    <td class="align-middle">
                                                        <select {{$project->agent_id}} project-id="{{$project->id}}"
                                                                class="form-select form-select-sm w-auto ms-auto"
                                                                name="haha" id="agent_dropdown">
                                                            <option value="0" @if (!$project->agent_id) selected="selected" @endif> None </option>
                                                            @if($project->status !== \App\Models\UserProject::IN_ACTIVE)
                                                                @foreach($agents as $agent)
                                                                    <option value="{{$agent->id}}"
                                                                            @if ($agent->id == $project->agent_id) selected="selected" @endif>{{$agent->first_name . ' ' . $agent->last_name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {{ $projects->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-xl-3">
                        <div class="offcanvas offcanvas-end offcanvas-filter-sidebar border-0 dark__bg-card-dark h-auto rounded-xl-3" tabindex="-1" id="ticketOffcanvas" aria-labelledby="ticketOffcanvasLabel">
                            <div class="offcanvas-header d-flex flex-between-center d-xl-none bg-light">
                                <h6 class="fs-0 mb-0 fw-semi-bold">Filter</h6><button class="btn-close text-reset d-xl-none shadow-none" id="ticketOffcanvasLabel" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="card scrollbar shadow-none shadow-show-xl">
                                <div class="card-header bg-light d-none d-xl-block">
                                    <h6 class="mb-0">Filter</h6>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="mb-2 mt-n2"><label class="mb-1">Customer</label>
                                            <select class="form-select form-select-sm" name="filterByUser">
                                                <option value="0"
                                                        @if (empty(request()->filterByUser) || request()->filterByUser === '0') selected="selected" @endif>
                                                    None
                                                </option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}"
                                                            @if ($customer->id == request()->filterByUser) selected="selected" @endif>{{$customer->first_name . ' ' . $customer->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2"><label class="mb-1 mt-2">Status</label>
                                            <select class="form-select form-select-sm" name="filterByStatus">
                                                <option value="0" @if (empty(request()->filterByStatus) || request()->filterByStatus === '') selected="selected" @endif>
                                                    None
                                                </option>
                                                @foreach(\App\Models\UserProject::STATUSES as $key => $status)
                                                    <option value="{{$key}}" @if ($key == request()->filterByStatus) selected="selected" @endif>
                                                        {{$status}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2"><label class="mb-1 mt-2">Plan</label>
                                            <select class="form-select form-select-sm" name="filterByPlan">
                                                <option value="0" @if (empty(request()->filterByPlan) || request()->filterByPlan === '') selected="selected" @endif>
                                                    None
                                                </option>
                                                @foreach($plans as $plan)
                                                    <option value="{{$plan->id}}" @if ($plan->id == request()->filterByPlan) selected="selected" @endif>
                                                        {{$plan->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($isAdmin)
                                            <div class="mb-2"><label class="mb-1 mt-2">Agent</label>
                                                <select class="form-select form-select-sm" name="filterByAgent">
                                                    <option value="0" @if (empty(request()->filterByAgent) || request()->filterByAgent === '') selected="selected" @endif>
                                                        None
                                                    </option>
                                                    <option value="-1" @if (request()->filterByAgent === '-1') selected="selected" @endif>
                                                        No Agent
                                                    </option>
                                                @foreach($agents as $agent)
                                                        <option value="{{$agent->id}}" @if ($agent->id == request()->filterByAgent) selected="selected" @endif>
                                                            {{$agent->first_name . ' ' . $agent->last_name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                                <div class="card-footer border-top border-200 py-x1">
                                    <button class="btn btn-dark w-100" id="filter_submit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    @push("scripts")
        <script type="module">

            $(document).on('change', '#agent_dropdown', function () {
                console.log(123)
                var selectedAgentId = $(this).val();
                var projectId = $(this).attr('project-id');
                var data = {};
                data['agentId'] = selectedAgentId;

                $.ajax({
                    url: '/admin/projects/' + projectId + '/assignAgent',
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.info(response.message)
                        } else {
                            toastr.error(response.message)
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong')
                    }
                });
            });

            $(document).ready(function () {
                $('#filter_submit').on('click', function (e) {
                    e.preventDefault();

                    var pairs = {
                        'filterByUser': $('select[name=filterByUser]').val(),
                        'filterByPlan': $('select[name=filterByPlan]').val(),
                        'filterByStatus': $('select[name=filterByStatus]').val(),
                        'filterByAgent': $('select[name=filterByAgent]').val(),
                        'page': 1
                    }
                    window.location.href = addParamsToCurrentUrl(pairs);
                });
            });

            function addParamsToCurrentUrl(pairs) {
                var url = window.location.href.substr(0, window.location.href.indexOf('?'));
                var vars = window.location.search.substring(1).split("&");
                var params = [];
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    params[pair[0]] = pair[1];
                }
                for (const key in pairs) {
                    params[key] = pairs[key];
                }
                var paramStr = '?';
                for (const key in params) {
                    if (key) {
                        paramStr += key + "=" + params[key] + "&";
                    }
                }
                return url + paramStr.slice(0, -1);
            }

        </script>
    @endpush


</x-app-layout>

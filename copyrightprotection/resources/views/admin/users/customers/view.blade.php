@php
    use App\Models\User;
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
                            <li class="breadcrumb-item active" aria-current="page">Customer</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4">
            <div class="position-xl-sticky top-0">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between py-2">
                        <h6 class="mb-0">Contact Information</h6>
                    </div>
                    <div class="card-body bg-light">
                        <div class="border rounded-3 p-x1 mt-3 bg-white dark__bg-1000 row mx-0 g-0">
                            <div class="col-md-6 col-xl-12 pe-md-4 pe-xl-0">
                                <div class="mb-4">
                                    <h6 class="false false">Name</h6>
                                    <p class="mb-0 text-700 fs--1">{{$customer->first_name . ' ' . $customer->last_name}}</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="mb-1 false">Email</h6><a class="fs--1" href="mailto:{{$customer->email}}">{{$customer->email}}</a>
                                </div>
                                <div class="mb-4">
                                    <h6 class="false mb-1">Phone Number</h6><a class="fs--1" href="tel:{{$customer->phone}}">{{$customer->phone}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9 col-xl-8">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between py-2">
                    <h6 class="mb-0">Projects Information</h6>
                </div>
                <div class="card-body bg-light">
                    <div class="table-responsive scrollbar">
                        <table class="table table-sm mb-0 fs--1 table-view-tickets">
                            <thead class="text-800 bg-light">
                            <tr>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle text-end">Plan</th>
                            </tr>
                            </thead>
                            <tbody class="list">

                            @foreach($projects as $key => $project)
                                <tr>
                                    <td class="align-middle white-space-now1rap fs-0 py-3">
                                        <h6 class="mb-0" title="{{$project->name}}">
                                            <a href="{{route('agent.user.project', $project->id)}}" > {{$project->name}}</a>
                                        </h6>
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
                                            default:
                                                $statusClass = '';
                                        }

                                    @endphp
                                    <td class="align-middle">
                                        <small class="badge rounded {{$statusClass}}">{{$project->status}}</small>
                                    </td>
                                    <td class="align-middle text-end">
                                        <h6 class="mb-0">{{$project->plan->name ?? ''}}</h6>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer d-flex align-items-center justify-content-center">
                            {{ $projects->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

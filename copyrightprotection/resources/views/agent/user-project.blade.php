@php
use \App\Models\User;
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
        <div class="col-xxl-12 col-xl-12">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card mb-3" style="min-height: 420px">
                        <div class="card-header d-flex align-items-center justify-content-between py-2">
                            <h6 class="mb-0">Customer Information</h6>
                        </div>
                        <div class="card-body bg-light">
                            <div class="border rounded-3 p-x1 mt-3 bg-white dark__bg-1000 row mx-0 g-0">
                                <div class="col-md-6 col-xl-12 pe-md-4 pe-xl-0">
                                    <div class="mb-4">
                                        <h6 class="mb-1 false">Customer First Name</h6>
                                        <p class="mb-0 text-700 fs--1">{{ $project->author->first_name }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="mb-1 false">Customer Last Name</h6>
                                        <p class="mb-0 text-700 fs--1">{{ $project->author->last_name }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="false mb-1">Customer Email</h6><a class="fs--1"
                                                                                     href="mailto:mattrogers@gmail.com">{{ $project->author->email }}</a>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="false mb-1">Customer Phone Number</h6><a class="fs--1"
                                                                                            href="tel:+6(855)747677">{{ $project->author->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3" style="min-height: 420px">
                        <div class="card-header d-flex align-items-center justify-content-between py-2">
                            <h6 class="mb-0">Project Information</h6>
                        </div>
                        <div class="card-body bg-light">
                            <div class="border rounded-3 p-x1 mt-3 bg-white dark__bg-1000 row mx-0 g-0">
                                <div class="mb-4">
                                    <h6 class="mb-1 false">Project Name</h6>
                                    <p class="mb-0 text-700 fs--1">{{ $project->name }}</p>
                                </div>
                                <div class="col-md-6 col-xl-12 ps-md-4 ps-xl-0">
                                    <div class="mb-4">
                                        <h6 class="false false">Subscription</h6>
                                        <p class="mb-0 text-700 fs--1">{{ !empty($project->projectSubscription) ? $project->projectSubscription->plan->name : 'This plan is still without a subscription.' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-12 ps-md-4 ps-xl-0">
                                    <div class="mb-4">
                                        <h6 class="false false">Project Status</h6>
                                        <p class="mb-0 text-700 fs--1">{{ $project->status }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.user-report-table-block')
        <div class="col-xxl-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-between-center bg-light py-2">
                    <h6 class="mb-0">Shared Files</h6>
                </div>
                <div class="card-body pb-0">
                    @foreach($legal_documents as $legal_document)
                        <div class="d-flex mb-3 hover-actions-trigger align-items-center">
                            <div class="file-thumbnail"><img class="img-fluid"
                                                             src="{{ asset('assets/img/icons/docs.png') }}"
                                                             alt=""/>
                            </div>
                            <div class="ms-3 flex-shrink-1 flex-grow-1">
                                <h6 class="mb-1"><a class="stretched-link text-900 fw-semi-bold project-document-links"
                                                    href="{{ route('legal.document.download', ['file' => $legal_document->id]) }}">{{ $legal_document->name }}</a>
                                </h6>
                                <div class="fs--1"><span
                                        class="fw-medium text-600 ms-2">{{ $legal_document->created_at }}</span>
                                </div>
                                <div class="hover-actions end-0 top-50 translate-middle-y"><a
                                        class="btn btn-light border-300 btn-sm me-1 text-600"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Download"
                                        href="{{ route('legal.document.download', ['file' => $legal_document->id]) }}"
                                        download="download"><img
                                            src="{{ asset('assets/img/icons/cloud-download.svg') }}"
                                            alt=""
                                            width="15"/></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <hr class="text-200"/>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card mb-3" style="min-height: 700px">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">Whitelisted Accounts</h5>
                        </div>
                        <div>
                            <a class="btn btn-falcon-default btn-sm"
                               href="{{ route('agent.project.exportAccounts', ['project' => $project->id]) }}">
                                <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>Export
                                Whitelisted Accounts
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive scrollbar">
                        <div class="table-responsive scrollbar" id="whitelisted_accounts">
                            <table class="table">
                                <tbody>
                                @foreach($whitelisted_accounts as $whitelisted_account)
                                    <tr>
                                        <td>{{ $whitelisted_account->content }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {!! $whitelisted_accounts->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-3" style="min-height: 700px">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="mb-0">Whitelisted Keyword</h5>
                        </div>
                        <div>
                            <a class="btn btn-falcon-default btn-sm"
                               href="{{ route('agent.project.exportKeywords', ['project' => $project->id]) }}">
                                <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>
                                Export Whitelisted Keyword
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive scrollbar">
                        <div class="table-responsive scrollbar" id="whitelisted_keywords">
                            <table class="table">
                                <tbody>
                                @foreach($whitelisted_keywords as $whitelisted_keyword)
                                    <tr>
                                        <td>{{ $whitelisted_keyword->content }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {!! $whitelisted_keywords->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('whitelisted_accounts')) {
                    const targetItem = document.getElementById('whitelisted_accounts');
                    if (targetItem) {
                        targetItem.scrollIntoView({behavior: 'smooth'});
                    }
                } else if (urlParams.has('whitelisted_keywords')) {
                    const targetItem = document.getElementById('whitelisted_keywords');
                    if (targetItem) {
                        targetItem.scrollIntoView({behavior: 'smooth'});
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>

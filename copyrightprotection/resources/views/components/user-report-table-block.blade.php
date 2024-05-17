<div class="col-lg-16">
    <div class="col-xxl-12 col-xl-12">
        <div class="card" data-list='{"valueNames":["name","phone-number","report","subscription","social"],"page":1,"pagination":true,"fallback":"contact-table-fallback"}'>
            <div class="card-header border-bottom border-200 px-0">
                <div class="d-lg-flex justify-content-between">
                    <div class="row flex-between-center gy-2 px-x1">
                        <div class="col-auto pe-0">
                            <h5 class="mb-0">Project Reports</h5>
                        </div>
                    </div>
                    <div class="border-bottom border-200 my-3"></div>
                    <div class="d-flex align-items-center justify-content-between justify-content-lg-end px-x1">
                        <div class="d-flex align-items-center"
                             id="table-contact-replace-element">
                            @if(!\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                                <button class="btn btn-falcon-default btn-sm" type="button"
                                        data-bs-toggle="modal" data-bs-target="#error-modal"><span
                                        class="fas fa-plus"
                                        data-fa-transform="shrink-3"></span><span
                                        class="d-none d-sm-inline-block d-xl-none d-xxl-inline-block ms-1">New</span>
                                </button>
                                <div class="modal fade" id="error-modal" tabindex="-1" role="dialog"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document"
                                         style="max-width: 500px">
                                        <div class="modal-content position-relative">
                                            <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                                                <button
                                                    class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <div class="rounded-top-3 py-3 ps-4 pe-6 bg-light">
                                                    <h4 class="mb-1" id="modalExampleDemoLabel">Import
                                                        Google Search report data</h4>
                                                </div>
                                                <div class="p-4 pb-0">
                                                    <div class="col-12 mb-3 d-flex justify-content-center">
                                                        <a href="{{ asset('assets/document/ReportExample.xlsx') }}">Report Example File</a>
                                                    </div>
                                                    <form
                                                        action="{{ route('agent.projectReport', ['project' => $project->id]) }}"
                                                        method="post" enctype="multipart/form-data"
                                                        class="row g-3">
                                                        @csrf
                                                        <div
                                                            class="col-12 mb-3 d-flex justify-content-start">
                                                            <label class="col-form-label"
                                                                   for="modal_report_date">Report
                                                                date: </label>
                                                            <input class="form-control" type="date"
                                                                   id="modal_report_date"
                                                                   name="report_date">
                                                        </div>
                                                        <div
                                                            class="col-12 mb-3 d-flex justify-content-start">
                                                            <input type="file" name="project_report"
                                                                   class="form-control">
                                                            <button class="btn btn-dark"
                                                                    type="submit">Import
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button"
                                                        data-bs-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="report-table" class="table-responsive scrollbar" anchor-to="report-table">

                    @include('components.report-table')
                    <div class="text-center d-none" id="contact-table-fallback">
                        <p class="fw-bold fs-1 mt-3">No contact found</p>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-center" anchor-to="report-table">
                {{ $project_reports->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>

    </script>
@endpush

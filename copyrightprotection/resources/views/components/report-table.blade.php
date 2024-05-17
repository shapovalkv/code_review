<table class="table table-sm mb-0 fs--1 table-view-tickets">
    <thead class="text-800 bg-light">
    <tr>
        @php
            $sorter->setAscClass(' desc ');
            $sorter->setDescClass(' asc ');
            $sorter->setClassForSelectedHeader(' sort-link ');
            $sorter->setClassForNotSelectedHeader(' sort-link ');
        @endphp


        <th class="align-middle py-3">{!! $sorter->sortableLink('sortByReportDate', 'Date of report') !!}</th>
        @if(!\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
            <th class="align-middle">{!! $sorter->sortableLink('sortByAuthor', 'Report Author') !!}</th>
        @endif
        <th class="align-middle">{!! $sorter->sortableLink('sortByGoogleSearch', 'Google searches') !!}</th>
        <th class="align-middle">{!! $sorter->sortableLink('sortByGoogleImage', 'Google images') !!}</th>
        <th class="align-middle">{!! $sorter->sortableLink('sortBySocialMedia', 'Social medias') !!}</th>
        <th class="align-middle">{!! $sorter->sortableLink('sortByATSource', 'At-sources') !!}</th>
        <th class="align-middle text-end">View</th>
    </tr>
    </thead>
    <tbody class="list" id="table-ticket-body">
    @foreach($project_reports as $project_report)
        <tr>
            <td class="align-middle py-3">
                <div
                    class="d-flex align-items-center gap-2 position-relative">
                    <h6 class="mb-0">
                        <p class="mb-0 text-700 fs--1">{{ $project_report->report_date }}</p>
                    </h6>
                </div>
            </td>
            @if(!\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                <td class="align-middle">
                    <p class="mb-0 text-700 fs--1">{{ $project_report->author }}</p>
                </td>
            @endif
            <td class="align-middle">
                <p class="mb-0 text-700 fs--1">{{ $project_report->google_search_count }}</p>
            </td>
            <td class="align-middle">
                <p class="mb-0 text-700 fs--1">{{ $project_report->google_image_count }}</p>
            </td>
            <td class="align-middle">
                <p class="mb-0 text-700 fs--1">{{ $project_report->social_media_count }}</p>
            </td>
            <td class="align-middle">
                <p class="mb-0 text-700 fs--1">{{ $project_report->at_source_count }}</p>
            </td>
            <td class="align-middle text-end">
                <div>
                    <a href="{{ route( 'project.report', ['project' => $project_report->project_id, 'report' => $project_report->id]) }}"
                       class="btn btn-link p-0" type="button"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top" title="View">
                        <span class="text-500 fas fa-eye"></span>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

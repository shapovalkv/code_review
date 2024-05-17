@php
use Carbon\Carbon;

$reportYears = $reportYears ? $reportYears : [Carbon::now()->format('Y')];
@endphp
<x-app-layout>
    @include('components.orders-buttons')
    <div class="row mb-3">
        <div class="col-lg-6">
            <div class="card h-100  equal-height-card">
                <div class="card-header">
                    <div class="row flex-between-end">
                        <div class="col-auto align-self-center">
                            <h5 class="mb-0" data-anchor="data-anchor">Links Chart</h5>
                        </div>
                        <div class="col-auto ms-auto">
                            <div class="nav nav-pills nav-pills-falcon flex-grow-1" role="tablist">
                                <select class="form-select form-select-sm chart-dropdown" name="links_chart_data_year">
                                    @foreach($reportYears as $year)
                                        <option value="{{$year}}" @if ($year == request()->links_chart_data_year ?? Carbon::now()->format('Y')) selected="selected" @endif>
                                            {{$year}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="tab-content">
                        <canvas id="chartLinks"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="row flex-between-end">
                        <div class="col-auto align-self-center">
                            <h5 class="mb-0" data-anchor="data-anchor">Reports Chart</h5>
                        </div>
                        <div class="col-auto ms-auto">
                            <div class="nav nav-pills nav-pills-falcon flex-grow-1" role="tablist">
                                <select class="form-select form-select-sm chart-dropdown" name="monthly_chart_data_month">
                                    @foreach(months() as $key => $month)
                                        <option value="{{$key}}" @if ($key == (request()->monthly_chart_data_month ?? Carbon::now()->month)) selected="selected" @endif>
                                            {{$month}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-auto ms-auto">
                            <div class="nav nav-pills nav-pills-falcon flex-grow-1" role="tablist">
                                <select class="form-select form-select-sm chart-dropdown" name="monthly_chart_data_year">
                                    @foreach($reportYears as $year)
                                        <option value="{{$year}}" @if ($year == (request()->monthly_chart_data_year ?? Carbon::now()->year)) selected="selected" @endif>
                                            {{$year}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="tab-content">
                        <div class="tab-content">
                            <canvas id="chartReports"></canvas>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-center">
                            {{ $monthly_chart_data->links('pagination::monthly-chart-data') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.user-report-table-block')
    @push('scripts')
        <script type="module">
            const ctx_report = document.getElementById('chartReports');

            new Chart(ctx_report, {
                type: 'bar',
                data: {
                    labels: [''],
                    datasets: [
                        {
                            label: 'Google Search',
                            data: [
                                @php
                                    $data = count($monthly_chart_data) === 0 ? '\'0\'' : '';
                                    foreach ($monthly_chart_data as $key => $value) {
                                        $data .= '\''.$value->googleSearchReports()->count().'\',';
                                    }
                                    $data = trim($data, ',');
                                @endphp
                                    {!! $data !!}],
                            backgroundColor: 'rgba(44, 123, 229, 1)', // Light green background
                            borderColor: 'rgb(40,46,168)', // Dark green border
                            borderWidth: 1
                        },
                        {
                            label: 'Google Images',
                            data: [
                                @php
                                    $data = count($monthly_chart_data) === 0 ? '\'0\'' : '';
                                    foreach ($monthly_chart_data as $key => $value) {
                                        $data .= '\''.$value->googleImagesReports()->count().'\',';
                                    }
                                    $data = trim($data, ',');
                                @endphp
                                    {!! $data !!}],
                            backgroundColor: 'rgba(39, 188, 253, 0.5)', // Light green background
                            borderColor: 'rgb(0,150,143)', // Dark green border
                            borderWidth: 1
                        },
                        {
                            label: 'Social Media',
                            data: [
                                @php
                                    $data = count($monthly_chart_data) === 0 ? '\'0\'' : '';
                                    foreach ($monthly_chart_data as $key => $value) {
                                        $data .= '\''.$value->socialMediaReports()->count().'\',';
                                    }
                                    $data = trim($data, ',');
                                @endphp
                                    {!! $data !!}],
                            backgroundColor: 'rgba(0, 210, 122, 0.5)', // Light green background
                            borderColor: 'rgba(40, 168, 40, 1)', // Dark green border
                            borderWidth: 1
                        },
                        {
                            label: 'At-Source',
                            data: [
                                @php
                                    $data = count($monthly_chart_data) === 0 ? '\'0\'' : '';
                                    foreach ($monthly_chart_data as $key => $value) {
                                        $data .= '\''.$value->atSourceReports()->count().'\',';
                                    }
                                    $data = trim($data, ',');
                                @endphp
                                    {!! $data !!}],
                            backgroundColor: 'rgba(245, 128, 62, 0.5)', // Light green background
                            borderColor: 'rgba(245, 128, 62, 1)', // Dark green border
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    elements: {
                        bar: {
                            borderWidth: 2,
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    }
                },
            });

            const ctx_links = document.getElementById('chartLinks');

            new Chart(ctx_links, {
                type: 'bar',
                data: {
                    labels: [
                        @php
                            $labels = '';
                            foreach ($chartDataLinks as $key => $value) {
                                $labels .= '\''.$value['name'].'\',';
                            }
                            $labels = trim($labels, ',');
                        @endphp
                        {!! $labels !!}],
                    datasets: [{
                        label: 'Links',
                        data: [
                            @php
                                $data = '';
                                foreach ($chartDataLinks as $key => $value) {
                                    $data .= '\''.$value['links'].'\',';
                                }
                                $data = trim($data, ',');
                            @endphp
                            {!! $data !!}],
                        backgroundColor: 'rgba(164, 228, 164, 0.5)', // Light green background
                        borderColor: 'rgba(40, 168, 40, 1)', // Dark green border
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
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

            $(document).on('change', '.chart-dropdown', function () {

                var pairs = {
                    'monthly_chart_data_month': $('select[name=monthly_chart_data_month]').val(),
                    'monthly_chart_data_year': $('select[name=monthly_chart_data_year]').val(),
                    'links_chart_data_year': $('select[name=links_chart_data_year]').val(),
                    'monthly_chart_data': 1
                }

                window.location.href = addParamsToCurrentUrl(pairs);
            });
        </script>
    @endpush
</x-app-layout>

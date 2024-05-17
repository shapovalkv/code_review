@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Plan Report")}}</h1>
            <a href="{{route('user.admin.plan_report.export')}}?{{http_build_query(request()->query->all())}}"
               class="btn btn-success">Export to XLSX</a>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-12">
                <form method="get" class="form-inline" role="search">
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Start date:</label>
                        <input type="text"
                               name="from"
                               class="form-control has-datepicker"
                               placeholder="{{__('MM/DD/YYY')}}"
                               value="{{ request()->query('from') ? \Illuminate\Support\Carbon::parse(request()->query('from'))->format('m/d/Y') : '' }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">End date:</label>
                        <input type="text"
                               name="to"
                               class="form-control has-datepicker"
                               placeholder="{{__('MM/DD/YYY')}}"
                               value="{{ request()->query('to') ? \Illuminate\Support\Carbon::parse(request()->query('to'))->format('m/d/Y') : '' }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Plan:</label>
                        <select name="plan_ids[]" class="form-control select-plan" multiple
                                data-placeholder="Choose plan" style="min-width: 210px;">
                            <option value="">{{__("All Plan")}}</option>
                            @foreach($plans as $plan)
                                <option @if(in_array($plan->id, request()->query('plan_ids', []))) selected
                                        @endif value="{{ $plan->id }}">{{ $plan->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Plan Type:</label>
                        <select name="plan_types[]" class="form-control select-plan-type" multiple
                                data-placeholder="Choose plan type" style="min-width: 210px;">
                            <option value="">{{__("All Types")}}</option>
                            @foreach($planTypes as $key => $type)
                                <option @if(in_array($key, request()->query('plan_types', []))) selected
                                        @endif value="{{ $key }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Date frame:</label>
                        <select name="separate" class="form-control">
                            @foreach($separate as $key => $name)
                                <option @if(request()->query('separate') === $key) selected
                                        @endif value="{{ $key }}">{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <button class="btn-info btn btn-icon btn_search" id="search-submit"
                                type="submit">{{__('Search')}}</button>
                        <a class="btn btn-link" href="{{route('user.admin.plan_report.index')}}">{{__('Clear')}}</a>
                    </div>
                </form>

                <div class="panel">
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{__("Period")}}</th>
                                <th>{{__("Plan Name")}}</th>
                                <th>{{__("Plan Price")}}</th>
                                <th>{{__("Payments")}}</th>
                                <th>{{__("Total price")}}</th>
                                <th>{{__("Status")}}</th>
                                <th width="100px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($items->count() > 0)
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            @if(request()->query('separate') === \Modules\User\Services\PlanReportService::SEPARATE_WEEK)
                                                @php
                                                    $parse = explode('-', $item->period);
                                                    $date = \Illuminate\Support\Carbon::now();
                                                    $date->setISODate($parse[0], $parse[1]);
                                                @endphp
                                                {{$date->startOfWeek()->format('m/d/Y')}}
                                                - {{$date->endOfWeek()->format('m/d/Y')}}
                                            @else
                                                {{$item->period}}
                                            @endif
                                        </td>
                                        <td>{{$item->plan->title}}</td>
                                        <td>{{format_money($item->plan->price)}}</td>
                                        <td>{{$item->count}}</td>
                                        <td>{{format_money($item->total)}}</td>
                                        <td>
                                                <span
                                                    class="badge badge-{{$item->plan->status === \Modules\User\Models\Plan::STATUS_PUBLISH ? 'success' : 'dark'}}">{{$item->plan->status}}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="6">{{__("No data")}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center">
                    {{$items->appends(request()->query())->links()}}
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-title d-flex justify-content-between align-items-center">
                        <h5>Monthly sales by plans</h5>
                        <div class="chartDataPicker" data-chartName="planChart" data-chartKey="plans"
                             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas id="planChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-title d-flex justify-content-between align-items-center">
                        <h5>Monthly total sales</h5>
                        <div class="chartDataPicker" data-chartName="saleChart" data-chartKey="sales"
                             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas id="saleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script.body')
    <script src="{{url('libs/chart_js/Chart.min.js')}}"></script>
    <script src="{{url('libs/daterange/moment.min.js')}}"></script>
    <script>
        $(function () {
            $('.select-plan').select2({
                placeholder: 'Choose plan',
                allowClear: true,
                multiple: true
            });
            $('.select-plan-type').select2({
                placeholder: 'Choose type',
                allowClear: true,
                multiple: true
            });
        });

        let options = {
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        let label = data.datasets[tooltipItem.datasetIndex].label || '';

                        if (label) {
                            label += ': ';
                        }

                        label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(tooltipItem.yLabel);

                        return label;
                    }
                }
            }
        }

        window.saleChart = new Chart(
            document.getElementById('saleChart').getContext('2d'),
            {
                type: 'bar',
                data: {!! json_encode($chartSalesData) !!},
                options: options
            });

        window.planChart = new Chart(
            document.getElementById('planChart').getContext('2d'),
            {
                type: 'bar',
                data: {!! json_encode($chartPlansData) !!},
                options: options
            });

        let start = moment('{{$startDate}}');
        let end = moment();

        function cb(obj, start, end) {
            obj.children('span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        function initDataPicker(obj) {
            obj.daterangepicker({
                startDate: start,
                endDate: end,
                "alwaysShowCalendars": true,
                "opens": "left",
                "showDropdowns": true,
                ranges: {
                    '{{__("Today")}}': [moment(), moment()],
                    '{{__("Yesterday")}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '{{__("Last 7 Days")}}': [moment().subtract(6, 'days'), moment()],
                    '{{__("Last 30 Days")}}': [moment().subtract(29, 'days'), moment()],
                    '{{__("This Month")}}': [moment().startOf('month'), moment().endOf('month')],
                    '{{__("Last Month")}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    '{{__("This Year")}}': [moment().startOf('year'), moment().endOf('year')],
                    '{{__('This Week')}}': [moment().startOf('week'), end]
                }
            }, function(start, end) {
                cb(obj, start, end);
            }).on('apply.daterangepicker', function (ev, picker) {
                // Reload Earning JS
                $.ajax({
                    url: '{{ route('user.admin.plan_report.chart') }}',
                    data: {
                        chart: obj.attr('data-chartKey'),
                        from: picker.startDate.format('YYYY-MM-DD'),
                        to: picker.endDate.format('YYYY-MM-DD'),
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (res) {
                        if (res.status) {
                            window[obj.attr('data-chartName')].data = res.data;
                            window[obj.attr('data-chartName')].update();
                        }
                    }
                })
            });
        }

        $('.chartDataPicker').each(function() {
            initDataPicker($(this))
            cb($(this), start, end);
        })
    </script>
@endsection

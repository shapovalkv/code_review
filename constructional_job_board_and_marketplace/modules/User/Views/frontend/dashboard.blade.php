@extends('layouts.user')
@section('head')
@endsection
@section('content')
    <h2 class="title-bar no-border-bottom">
        {{__("Dashboard")}}
    </h2>
    @include('admin.message')
    <div class="bravo-user-dashboard">
        <div class="row dashboard-price-info row-eq-height">
            @if(!empty($cards_report))
                @foreach($cards_report as $item)
                    <div class="col-lg-3 col-md-3">
                        <div class="dashboard-item {{$item['class']}}">
                            <div class="icon">
                                <i class="{{$item['icon']}}"></i>
                            </div>
                            <div class="wrap-box">
                                <div class="title">
                                    {{$item['title']}}
                                </div>
                                <div class="details">
                                    <div class="number">
                                        {{ $item['amount'] }}
                                    </div>
                                </div>
                                <div class="desc"> {{ $item['desc'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="bravo-user-chart">
        <div class="chart-title">
            {{__("Earning statistics")}}
            <div class="action-control">
                <div id="reportrange">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </div>
        <canvas class="bravo-user-render-chart"></canvas>
        <script>
            var earning_chart_data = {!! json_encode($earning_chart_data) !!};
        </script>
    </div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset("libs/chart_js/Chart.min.js") }}"></script>
    <script type="text/javascript">
        jQuery(function ($) {
            "use strict"
            $(".bravo-user-render-chart").each(function () {
                let ctx = $(this)[0].getContext('2d');
                window.myMixedChartForVendor = new Chart(ctx, {
                    type: 'bar',//line - bar
                    data: earning_chart_data,
                    options: {
                        min:0,
                        responsive: true,
                        legend: {
                            display: true
                        },
                        scales: {
                            xAxes: [{
                                stacked: true,
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: '{{__("Timeline")}}'
                                }
                            }],
                            yAxes: [{
                                stacked: true,
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: '{{__("Currency: :currency_main",['currency_main'=>setting_item('currency_main')])}}'
                                },
                                ticks: {
                                    beginAtZero: true,
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function (tooltipItem, data) {
                                    var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += tooltipItem.yLabel + " ({{setting_item('currency_main')}})";
                                    return label;
                                }
                            }
                        }
                    }
                });
            });


            $(".bravo-user-chart form select").on('change',function () {
                $(this).closest("form").submit();
            });

            var start = moment().startOf('week');
            var end = moment();

            const picker = new easepick.create({
                element: "#reportrange",
                css: [
                    '{{ asset("libs/easepick/easepick.css") }}',
                ],
                zIndex: 10,
                firstDay: 0,
                grid: 12,
                calendars: 2,
                format: 'MM/DD/YYYY',
                AmpPlugin: {
                    dropdown: {
                        months: true,
                        years: true
                    },
                    darkMode: false,
                },
                plugins: [
                    "AmpPlugin",
                    "RangePlugin",
                    "PresetPlugin"
                ],
                RangePlugin: {
                    startDate: start.format('MM/DD/YYYY'),
                    endDate: end.format('MM/DD/YYYY'),
                },
            })

            picker.on('select', (e) => {
                const { start, end } = e.detail;
                // Reload Earning JS
                $.ajax({
                    url: '{{url('user/reloadChart')}}',
                    data: {
                        chart: 'earning',
                        from: start.format('MM/DD/YYYY'),
                        to: end.format('MM/DD/YYYY'),
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (res) {
                        if (res.status) {
                            window.myMixedChartForVendor.data = res.data;
                            window.myMixedChartForVendor.update();
                        }
                    }
                })

            })
        });
    </script>
@endsection

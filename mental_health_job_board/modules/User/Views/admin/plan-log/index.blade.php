@extends('admin.layouts.app')
@section('script.head')
    <style>
        .select2-container {
            min-width: 210px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Plan log")}}</h1>
            <a href="{{route('user.admin.plan_log.export')}}?{{http_build_query(request()->query->all())}}"
               class="btn btn-success">Export to XLSX</a>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-12">

                <form method="get" class="form-inline" role="search">
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Expiry from:</label>
                        <input type="text"
                               name="from"
                               class="form-control has-datepicker"
                               placeholder="{{__('MM/DD/YYY')}}"
                               value="{{ request()->query('from') ? \Illuminate\Support\Carbon::parse(request()->query('from'))->format('m/d/Y') : '' }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Expiry to:</label>
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
                        <label class="mr-1">Status:</label>
                        <select name="status_ids[]" class="form-control select-status" multiple
                                data-placeholder="Choose status" style="min-width: 210px;">
                            <option value="">{{__("All Statuses")}}</option>
                            @foreach($statuses as $id => $title)
                                <option @if(in_array($id, request()->query('status_ids', []))) selected
                                        @endif value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Employer:</label>
                        <?php
                        $company = \App\User::find(request()->input('create_user'));
                        \App\Helpers\AdminForm::select2('create_user', [
                            'configs' => [
                                'ajax'        => [
                                    'url'      => route('user.admin.getForSelect2'),
                                    'dataType' => 'json'
                                ],
                                'allowClear'  => true,
                                'placeholder' => __('-- Select Employer --')
                            ]
                        ], !empty($company->id) ? [
                            $company->id,
                            $company->getDisplayName()
                        ] : false)
                        ?>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <button class="btn-info btn btn-icon btn_search" id="search-submit"
                                type="submit">{{__('Search')}}</button>
                        <a class="btn btn-link" href="{{route('user.admin.plan_log.index')}}">{{__('Clear')}}</a>
                    </div>
                </form>

                <div class="panel">
                    <div class="panel-body">
                        <form class="bravo-form-item">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th><a href="{{sortUrl('id')}}">{{__("ID")}} {!! sortDirectionIco('id') !!}</a></th>
                                    <th>
                                        <a href="{{sortUrl('user')}}">{{__("Employer")}} {!! sortDirectionIco('user') !!}</a>
                                    </th>
                                    <th>
                                        <a href="{{sortUrl('plan')}}">{{__("Plan Name")}} {!! sortDirectionIco('plan') !!}</a>
                                    </th>
                                    <th>
                                        <a href="{{sortUrl('end_date')}}">{{__("Expiry")}} {!! sortDirectionIco('end_date') !!}</a>
                                    </th>
                                    <th>
                                        <a href="{{sortUrl('price')}}">{{__("Price")}} {!! sortDirectionIco('price') !!}</a>
                                    </th>
                                    <th>
                                        <a href="{{sortUrl('status')}}">{{__("Status")}} {!! sortDirectionIco('status') !!}</a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($rows->total() > 0)
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>#{{$row->id}}</a></td>
                                            <td>{{ $row->user ? $row->user->getDisplayName() : '' }}</td>
                                            <td class="trans-id">{{$row->plan->title ?? ''}}</td>
                                            <td class="total-jobs">{{display_datetime($row->end_date)}}</td>
                                            <td class="remaining">{{format_money($row->price)}}</td>
                                            <td>
                                                @if($row->status === \Modules\User\Models\UserPlan::CURRENT)
                                                    <span class="text-success">{{__('Active')}}</span>
                                                @elseif($row->status === \Modules\User\Models\UserPlan::NOT_USED)
                                                    <span class="text-warning">{{__('Waiting')}}</span>
                                                @else
                                                    <div class="text-danger mb-3">{{__('Expired')}}</div>
                                                @endif
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
                            {{$rows->appends(request()->query())->links()}}
                        </form>
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
        });
        $(function () {
            $('.select-status').select2({
                placeholder: 'Choose status',
                allowClear: true,
                multiple: true
            });
        });
    </script>
@endsection

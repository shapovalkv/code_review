@extends("Layout::user")

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')

    <div class="upper-title-box">
        <h3 class="title">{{__("My Orders")}}</h3>
    </div>

    <div class="history ls-widget">
        <div class="tabs-box">
            <div class="widget-title"><h4>{{ __("My Orders") }}</h4></div>
            <div class="widget-content">
                <div class="table-outer">
                    <table class="default-table">
                        <thead>
                        <tr class="carttable_row">
                            <th class="cartm_title">{{__('No')}}</th>
                            <th class="cartm_title">{{__('Product')}}</th>
                            <th class="cartm_title">{{__('Price')}}</th>
                            <th class="cartm_title">{{__('Order Date')}}</th>
                            <th class="cartm_title">{{__('Gateway')}}</th>
                            <th class="cartm_title">{{__('Status')}}</th>
                        </tr>
                        </thead>
                        <tbody class="table_body">

                        @foreach($rows as $k=>$row)
                            <?php $model = $row->model();
                            ?>
                            <tr>
                                <td>{{$rows ->perPage() * ($rows->currentPage()-1) + $k + 1}}</td>
                                <td scope="row">
                                    @if($model)
                                        <?php $url = $model->getDetailUrl()?>
                                        <ul class="cart_list d-flex align-center list-unstyled">
                                            @if($model->image_id)
                                                <li class="list-inline-item pr20">
                                                    {!! get_image_tag($model->image_id ?? '','thumb',['class'=>'float-left img-120 mw-80'])!!}
                                                </li>
                                            @endif
                                            <li class="list-inline-item">{{$model->title}}</li>
                                        </ul>
                                    @else
                                        <ul class="cart_list d-flex align-center list-unstyled">
                                            <li class="list-inline-item pr20">
                                            </li>
                                            <li class="list-inline-item">{{$row->name}}</li>
                                        </ul>
                                    @endif
                                </td>
                                <td>{{format_money($row->price)}}</td>
                                <td>{{display_datetime($row->created_at)}}</td>
                                <td>{{$row->order->gateway ?? ''}}</td>
                                <td>{{$row->status_name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="ls-pagination">
                        {{$rows->appends(request()->query())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="post-form__send-btn-wrap d-xl-none">
        <a href="{{route('user.dashboard')}}" class="post-form__send-btn f-btn primary-btn theme-btn">
            {{__('Dashboard')}}

            <svg width="28" height="8" viewBox="0 0 28 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M28 4L23 8V0L28 4Z" fill="white"/>
                <path d="M23 3.58011H0V4.41989H23V3.58011Z" fill="white"/>
            </svg>
        </a>
    </div>
@endsection

@section('footer')
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
@endsection

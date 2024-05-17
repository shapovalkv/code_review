@extends("Layout::user")
@section('content')
    <div class="py-3 px-2">
        <ul class="page-breadcrumb">
            <li><a href="#">{{__("Buyer Dashboard")}}</a></li>
            <li><a href="#">{{__("My Orders")}}</a></li>
        </ul>
    </div>

    <div class="upper-title-box">
        <h3 class="title">{{__("My Orders")}}</h3>
    </div>
    <div class="ls-widget">
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
                                            <li class="list-inline-item"><a class="cart_title" href="{{$url ? $url : '#'}}">{{$model->title}}</a></li>
                                        </ul>
                                    @else
                                        <ul class="cart_list d-flex align-center list-unstyled">
                                            <li class="list-inline-item pr20">
                                            </li>
                                            <li class="list-inline-item"><a class="cart_title" >{{$row->name}}</a></li>
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

@endsection

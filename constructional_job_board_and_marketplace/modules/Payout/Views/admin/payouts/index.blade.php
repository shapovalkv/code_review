@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__("Payout request management")}}</h1>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if(!empty($rows))
                    <label >{{__("With selected:")}}</label>
                    <div  class="filter-form filter-form-left d-flex justify-content-start">
                        <button class="btn-info btn btn-icon dungdt-form-payout-btn" type="button">{{__('Bulk action')}}</button>
                        <button class="has-loading btn-danger btn btn-icon dungdt-form-payout-delete" type="button">{{__('Delete')}}
                            <i class="fa fa-spinner fa-spin fa-fw"></i>
                        </button>
                    </div>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="{{route('payout.admin.index')}} " class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    @if(!empty($rows))
                        <?php
                        $user = !empty(Request()->vendor_id) ? App\User::find(Request()->vendor_id) : false;
                        \App\Helpers\AdminForm::select2('vendor_id', [
                            'configs' => [
                                'ajax'        => [
                                    'url'      => route('candidate.admin.getForSelect2'),
                                    'dataType' => 'json'
                                ],
                                'allowClear'  => true,
                                'placeholder' => __('-- Candidate --')
                            ]
                        ], !empty($user->id) ? [
                            $user->id,
                            $user->getDisplayName()
                        ] : false)
                        ?>
                    @endif
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by payout id')}}" class="form-control">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{__('Search')}}</button>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bc-form-item">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="60px"><input type="checkbox" class="check-all"></th>
                                <th  width="80px"> {{ __('ID')}}</th>
                                <th> {{ __('Candidate')}}</th>
                                <th width="200px"> {{ __('Amount')}}</th>
                                <th width="230px"> {{ __('Payout Method')}}</th>
                                <th width="130px"> {{ __('Created At')}}</th>
                                <th width="100px"> {{ __('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($rows->total() > 0)
                                @foreach($rows as $payout)
                                    <tr class="status-{{$payout->status}}">
                                        <td><input type="checkbox" name="ids[]" class="check-item" value="{{$payout->id}}">
                                        </td>
                                        <td>#{{$payout->id}}</td>
                                        <td>
                                            <a target="_blank" href="{{ url("admin/module/user/edit/".$payout->vendor_id) }}">{{$payout->vendor->display_name}}</a>
                                        </td>
                                        <td>{{format_money($payout->total)}}</td>
                                        <td>
                                            {{__(':name to :info',['name'=>$payout->payout_method,'info'=>$payout->account_info[0]])}}
                                        </td>
                                        <td>{{display_date($payout->created_at)}}</td>
                                        <td>{{$payout->status}}</td>
                                        <td>
                                            <a class="btn btn-info edit-payout-btn" data-target="#bulkActionModal_{{ $payout->id }}" data-toggle="modal" href="#" onclick="return false"><i class="fa fa-edit"></i> {{__("Edit")}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">{{__("No data")}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </form>
                {{$rows->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
    @if($rows->total() > 0)
        @foreach($rows as $payout)
            <div class="modal bravo-form" tabindex="-1" id="bulkActionModal_{{ $payout->id }}" role="dialog" data-action="{{route('payout.admin.bulkEdit')}}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{__("Payout request bulk action")}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">{{__('Status')}}</label>
                                <div>
                                    <select name="action" class="custom-select">
                                        @foreach($all_statuses as $key=>$status)
                                            <option @if($payout->status == $key) selected @endif value="{{$key}}">{{$status['title']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{__('Pay date')}}</label>
                                <div>
                                    <input
                                        type="text"
                                        name="pay_date"
                                        value="{{ !empty($payout->pay_date) ? date('Y-m-d', strtotime($payout->pay_date)) : '' }}"
                                        class="form-control has-easepick"
                                        placeholder="{{__('MM/DD/YYYY')}}"
                                    >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{__('Note to Candidate')}}</label>
                                <div>
                                    <textarea name="note_to_vendor" cols="30" rows="5" class="form-control">{{ $payout->note_to_vendor }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                            <button type="button" class="btn btn-success dungdt-form-payout-save">{{__('Save changes')}}
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    <div class="modal bravo-form" tabindex="-1" id="bulkActionModal" role="dialog" data-action="{{route('payout.admin.bulkEdit')}}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Payout request bulk action")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{__('Status')}}</label>
                        <div>
                            <select name="action" class="custom-select">
                                @foreach($all_statuses as $key=>$status)
                                    <option value="{{$key}}">{{$status['title']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">{{__('Pay date')}}</label>
                        <div>
                            <input
                                type="text"
                                name="pay_date"
                                class="form-control has-easepick"
                                placeholder="{{__('MM/DD/YYYY')}}"
                            >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">{{__('Note to Candidate')}}</label>
                        <div>
                            <textarea name="note_to_vendor" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-success dungdt-form-payout-save">{{__('Save changes')}}
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script.body')
    <script src="{{asset('libs/daterange/moment.min.js')}}"></script>
    <script src="{{url('libs/easepick/easepick.min.js')}}"></script>

    <script>
        const datepickersList = document.querySelectorAll('.has-easepick')

        datepickersList.forEach(item => {
            new easepick.create({
                element: item,
                css: [
                    '{{ asset("libs/easepick/easepick.css") }}',
                ],
                zIndex: 10,
                format: 'MM/DD/YYYY',
                AmpPlugin: {
                    dropdown: {
                        months: true,
                        years: true
                    },
                    darkMode: false,
                },
                plugins: [
                    "AmpPlugin"
                ]
            })
        })

        $('.dungdt-form-payout-btn').click(function () {
            var ids = $('.check-item:checked').map(function(){
                return $(this).val();
            }).get();
            if(!ids || !ids.length){
                superioApp.showError("{{__('Please select at lease one item')}}")
                return;
            }
            $('#bulkActionModal').modal('show');
        });

        $('.edit-payout-btn').click(function () {
            $(this).closest('tr').find('.check-item').prop('checked',true);
            //$('.dungdt-form-payout-btn').trigger('click');
        })

        $('.dungdt-form-payout-delete').click(function () {

            var btn  = $(this);
            var ids = $('.check-item:checked').map(function(){
                return $(this).val();
            }).get();
            if(!ids || !ids.length){
                superioApp.showError("{{__('Please select at lease one item')}}")
                return;
            }
            superioApp.showConfirm({
                message:'{{__('Do you want to delete those items?')}}',
                callback:function (result) {
                    if(result){
                        btn.addClass('loading');
                        $.ajax({
                            url:'{{route('payout.admin.bulkEdit')}}',
                            data:{
                                action:'delete',
                                ids:ids
                            },
                            method:'post',
                            success:function (json) {
                                btn.removeClass('loading');

                                superioApp.showAjaxMessage(json);

                                if(json.status){
                                    window.location.reload();
                                }
                            },
                            error:function (e) {
                                btn.removeClass('loading');
                                superioApp.showAjaxError(e);
                            }
                        })
                    }
                }
            })

        });

        $('.dungdt-form-payout-save').click(function () {
            var form = $(this).closest('.modal');
            var status = form.find('[name=action]').val();
            if(!status){
                superioApp.showError("{{__("Status is empty")}}");
                return;
            }
            var ids = $('.check-item:checked').map(function(){
                return $(this).val();
            }).get();
            if(!ids || !ids.length){
                superioApp.showError("{{__('Please select at lease one item')}}")
                return;
            }

            var data = {
                ids:ids
            }

            form.find('input,textarea,select').serializeArray().map(function (val) {
                data[val.name] = val.value;
            });

            form.addClass('loading');


            $.ajax({
                url:form.data('action'),
                method:'post',
                data:data,
                success:function (json) {
                    form.removeClass('loading');

                    if(json.status){
                        form.modal('hide');
                    }

                    superioApp.showAjaxMessage(json);

                    if(json.status){
                        window.setTimeout(function () {
                            window.location.reload();
                        },2500);
                    }
                },
                error:function (e) {
                    form.removeClass('loading');
                    superioApp.showAjaxError(e);
                }
            })

        })
    </script>
@endsection

@extends('layouts.user')

@section ('content')
    <h2 class="title-bar no-border-bottom">
        {{ __("Availability equipments") }}
    </h2>
    <div class="language-navigation">
        <div class="panel-body">
            <div class="filter-div d-flex justify-content-between ">
                <div class="col-left">
                    <form method="get" action="" class="filter-form filter-form-left d-flex flex-column flex-sm-row" role="search">
                        <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by name')}}" class="form-control">&nbsp;&nbsp;
                        <button class="btn-info btn btn_search btn-sm" type="submit">{{__('Search')}}</button>
                    </form>
                </div>
                <div class="col-right">
                    @if($rows->total() > 0)
                        <span class="count-string">{{ __("Showing :from - :to of :total events",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(count($rows))
        <div class="user-panel">
            <div class="panel-title"><strong>{{__('Availability')}}</strong></div>
            <div class="panel-body no-padding" style="background: #f4f6f8;padding: 0px 15px;">
                <div class="row">
                    <div class="col-md-3" style="border-right: 1px solid #dee2e6;">
                        <ul class="nav nav-tabs  flex-column vertical-nav" id="items_tab"  role="tablist">
                            @foreach($rows as $k=>$item)
                                <li class="nav-item event-name ">
                                    <a class="nav-link" data-id="{{$item->id}}" data-toggle="tab" href="#calendar-{{$item->id}}" title="{{$item->title}}" >#{{$item->id}} - {{$item->title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-9" style="background: white;padding: 15px;">
                        <div id="dates-calendar" class="dates-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">{{__("No events found")}}</div>
    @endif
    <div class="d-flex justify-content-center">
        {{$rows->appends($request->query())->links()}}
    </div>
    <div id="bravo_modal_calendar" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Date Information')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="row form_modal_calendar form-horizontal" novalidate onsubmit="return false">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label >{{__('Date Ranges')}}</label>
{{--                                <input readonly type="text" class="form-control has-daterangepicker">--}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label >{{__('Status')}}</label>
                                <br>
                                <label ><input true-value=1 false-value=0 type="checkbox" v-model="form.active"> {{__('Available for booking?')}}</label>
                            </div>
                        </div>
                        <div class="col-md-12" v-if="ticket_types">
                            <div v-for="(type,index) in ticket_types">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>{{__("Name")}}</label>
                                            <input type="text" readonly class="form-control" v-model="ticket_types[index].name">
                                        </div>
                                        <div class="col-md-3">
                                            <label>{{__("Number")}}</label>
                                            <input type="text" v-model="ticket_types[index].number" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label>{{__("Price")}}</label>
                                            <input type="text" v-model="ticket_types[index].price" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div v-if="lastResponse.message">
                        <br>
                        <div  class="alert" :class="!lastResponse.status ? 'alert-danger':'alert-success'">@{{ lastResponse.message }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-primary" @click="saveForm">{{__('Save changes')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('libs/fullcalendar-4.2.0/core/main.css')}}">
    <link rel="stylesheet" href="{{asset('libs/fullcalendar-4.2.0/daygrid/main.css')}}">

    <style>
        .event-name{
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        #dates-calendar .loading{

        }
    </style>
@endsection

@section('footer')
    <script src="{{asset('libs/daterange/moment.min.js')}}"></script>
    <script src="{{asset('libs/fullcalendar-4.2.0/core/main.js')}}"></script>
    <script src="{{asset('libs/fullcalendar-4.2.0/interaction/main.js')}}"></script>
    <script src="{{asset('libs/fullcalendar-4.2.0/daygrid/main.js')}}"></script>

    <script>
        var calendarEl,calendar,lastId,formModal;
        $('#items_tab').on('show.bs.tab',function (e) {
            calendarEl = document.getElementById('dates-calendar');
            lastId = $(e.target).data('id');
            if(calendar){
                calendar.destroy();
            }
            calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'dayGrid' ,'interaction'],
                header: {},
                selectable: true,
                selectMirror: false,
                allDay:false,
                editable: false,
                eventLimit: true,
                defaultView: 'dayGridMonth',
                firstDay: daterangepickerLocale.first_day_of_week,
                events:{
                    url:"{{route('equipment.vendor.availability.loadDates')}}",
                    extraParams:{
                        id:lastId,
                    }
                },
                loading:function (isLoading) {
                    if(!isLoading){
                        $(calendarEl).removeClass('loading');
                    }else{
                        $(calendarEl).addClass('loading');
                    }
                },
                select: function(arg) {
                    formModal.show({
                        start_date:moment(arg.start).format('MM/DD/YYYY'),
                        end_date:moment(arg.end).format('MM/DD/YYYY'),
                    });
                },
                eventClick:function (info) {
                    var form = Object.assign({},info.equipment.extendedProps);
                    form.start_date = moment(info.equipment.start).format('MM/DD/YYYY');
                    form.end_date = moment(info.equipment.start).format('MM/DD/YYYY');
                    console.log(form);
                    formModal.show(form);
                },
                eventRender: function (info) {
                    $(info.el).find('.fc-title').html(info.equipment.title);
                    $(info.el).find('.fc-content').attr("data-html","true").attr("title",info.equipment.title).tooltip({ boundary: 'window' })
                }
            });
            calendar.render();
        });

        $('.event-name:first-child a').trigger('click');

        formModal = new Vue({
            el:'#bravo_modal_calendar',
            data:{
                lastResponse:{
                    status:null,
                    message:''
                },
                form:{
                    id:'',
                    price:'',
                    start_date:'',
                    end_date:'',
                    active:0
                },
                formDefault:{
                    id:'',
                    price:'',
                    start_date:'',
                    end_date:'',
                    active:0
                },
                ticket_types:[

                ],
                ticket_type_item:{
                    name:'',
                    desc:'',
                    number:'',
                    price:'',
                },
                onSubmit:false
            },
            methods:{
                show:function (form) {
                    $(this.$el).modal('show');
                    this.lastResponse.message = '';
                    this.onSubmit = false;

                    if(typeof form !='undefined'){
                        this.form = Object.assign({},form);
                        if(typeof this.form.ticket_types == 'object'){
                            this.ticket_types = this.form.ticket_types;
                        }else{
                            this.ticket_types = false;
                        }
                        // if(form.start_date){
                        //     var drp = $('.has-daterangepicker').data('daterangepicker');
                        //     drp.setStartDate(moment(form.start_date).format('MM/DD/YYYY'));
                        //     drp.setEndDate(moment(form.end_date).format('MM/DD/YYYY'));
                        // }
                    }
                },
                hide:function () {
                    $(this.$el).modal('hide');
                    this.form = Object.assign({},this.formDefault);
                    this.ticket_types = false;
                },
                saveForm:function () {
                    this.form.target_id = lastId;
                    var me = this;
                    me.lastResponse.message = '';
                    if(this.onSubmit) return;

                    if(!this.validateForm()) return;

                    this.onSubmit = true;
                    this.form.ticket_types = this.ticket_types;
                    $.ajax({
                        url:'{{route('equipment.vendor.availability.store')}}',
                        data:this.form,
                        dataType:'json',
                        method:'post',
                        success:function (json) {
                            if(json.status){
                                if(calendar)
                                    calendar.refetchequipments();
                                me.hide();
                            }
                            me.lastResponse = json;
                            me.onSubmit = false;
                        },
                        error:function (e) {
                            me.onSubmit = false;
                        }
                    });
                },
                validateForm:function(){
                    if(!this.form.start_date) return false;
                    if(!this.form.end_date) return false;

                    return true;
                },
            },
            created:function () {
                var me = this;
                this.$nextTick(function () {
                    // $('.has-daterangepicker').daterangepicker({ "locale": {"format": 'MM/DD/YYYY'}})
                    //     .on('apply.daterangepicker',function (e,picker) {
                    //         console.log(picker);
                    //         me.form.start_date = picker.startDate.format('MM/DD/YYYY');
                    //         me.form.end_date = picker.endDate.format('MM/DD/YYYY');
                    //     });
                    $(me.$el).on('hide.bs.modal',function () {

                        this.form = Object.assign({},this.formDefault);
                        this.ticket_types = [];
                    });
                })
            },
            mounted:function () {
            }
        });
    </script>
@endsection

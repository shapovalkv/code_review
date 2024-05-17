<?php
$checkNotify = \Modules\Core\Models\NotificationPush::query();
// Commented admin notification on dashboard
//if(is_admin()){
//    $checkNotify->where(function($query){
//        $query->where('data', 'LIKE', '%"for_admin":1%');
//        $query->orWhere('notifiable_id', Auth::id());
//    });
//}else{
    $checkNotify->where('data', 'LIKE', '%"for_admin":0%');
    $checkNotify->where('notifiable_id', Auth::id());
//}
$notifications = $checkNotify->orderBy('created_at', 'desc')->limit(5)->get();
$countUnread = $checkNotify->where('read_at', null)->count();
?>
@if(has_permission(['candidate_manage', 'employer_manage', \App\Enums\UserPermissionEnum::COMPANY_MESSAGING]))
<a href="{{route('user.chat.index')}}" class="menu-btn notify-button" data-toggle="tooltip" data-placement="bottom" title="{{__('Messaging')}}">
    <livewire:new-message-counter />
    <i class="icon fa-solid fa fa-comment-dots"></i>
</a>
@endif
<div class="dropdown-notifications dropdown p-0">
    <a href="#" data-bs-toggle="dropdown" class="menu-btn notify-button" data-toggle="tooltip" data-placement="bottom" title="{{__('Notifications')}}">
        @if($countUnread > 0)<span class="count wishlist_count text-center" style="margin-top: -6px;">{{$countUnread}}</span>@endif
            <i class="icon fa-solid fa fa-bell"></i>
    </a>
    <ul class="dropdown-menu">
        <div class="dropdown-toolbar">
            <h3 class="dropdown-toolbar-title fs-16 mb-0">{{__('Notifications')}} (<span class="notify-count">{{$countUnread}}</span>)</h3>
            <div class="dropdown-toolbar-actions">
                <a href="#" class="markAllAsRead fs-14">{{__('Mark all as read')}}</a>
            </div>
        </div>
        <div class="list-group">
            @if(count($notifications)> 0)
                @foreach($notifications as $oneNotification)
                    @php
                        $active = $class = '';
                        $data = json_decode($oneNotification['data']);

                        $idNotification = @$data->id;
                        $forAdmin = @$data->for_admin;
                        $usingData = @$data->notification;

                        $services = @$usingData->type;
                        $idServices = @$usingData->id;
                        $title = @$usingData->message;
                        $name = @$usingData->name;
                        $avatar = @$usingData->avatar;
                        $link = @$usingData->link;

                        if(empty($oneNotification->read_at)){
                            $class = 'markAsRead';
                            $active = 'active';
                        }
                    @endphp
                    <li class="notify-item {{$active}}">
                        <a class="{{$class}} p-0" data-id="{{$idNotification}}" href="{{$link}}">
                            <div class="media">
                                <div class="media-left">
                                    <div class="media-object">
                                        @if($avatar)
                                            <img class="image-responsive" src="{{$avatar}}" alt="{{$name}}">
                                        @else
                                            <span class="avatar-text">{{ucfirst($name[0])}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-body">
                                    {!! $title !!}
                                    <div class="notification-meta">
                                        <small class="timestamp">{{format_interval($oneNotification->created_at)}}</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            @else
                <a href="#" class="list-group-item list-group-item-action">
                    <span class="fs-14">{{ __("No notification") }}</span>
                </a>
            @endif
        </div>
        <div class="dropdown-footer">
            <a class="btn btn-primary" href="{{route('core.notification.loadNotify')}}">{{__('View More')}}</a>
        </div>
    </ul>
</div>

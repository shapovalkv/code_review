<div class="btn-box">
    @php
        $url = '';
        if(!empty($cv)){
            $file = (new \Modules\Media\Models\MediaFile())->findById($cv->file_id);
            $url  = asset('uploads/'.$file['file_path']);
        }
    @endphp
    @if(is_employer())
        <div class="ml-2">
            <a href="#" data-id="{{ $row->id }}" class="theme-btn btn-style-ten bc-apply-job-button bc-call-modal invite-job">{{ __("Invite For Job") }}</a>
        </div>
    @endif
    @if($url && is_applied($row->id))
        <div class="ml-2">
        @if(setting_item('candidate_download_cv_required_login') && !auth()->check())
            <a href="javascript:void(0)" class="theme-btn btn-style-one bc-call-modal login">{{__('Download CV')}}</a>
        @elseif((is_candidate() || !$row->enableDownloadCV()) && (!is_admin() && \Illuminate\Support\Facades\Auth::id() != $row->id))
            <a href="javascript:void(0)" class="theme-btn btn-style-one bc-required" data-require-text="{{ __('You do not have permission to download CV') }}" >{{__('Download CV')}}</a>
        @else
            <a href="{{$url}}" class="theme-btn btn-style-one" target="_blank" download >{{__('Download CV')}}</a>
        @endif
        </div>
    @endif
    @if(is_employer())
        <button class="bookmark-btn @if($row->wishlist) active @endif service-wishlist" data-id="{{$row->id}}" data-type="{{$row->type}}" data-toggle="tooltip" data-placement="bottom" title="{{__('Bookmark')}}"><span class="flaticon-lg flaticon-bookmark"></span></button>
    @endif
    @if(is_employer())
        <a href="{{route('user.chat.start', ['targetUser' => $row->id])}}" class="bookmark-btn" data-toggle="tooltip" data-placement="bottom" title="{{__('Messaging')}}"><i class="fa fa-lg fa-comment-dots"></i></a>
    @endif
    @include('Job::frontend.layouts.details.invite-job-popup')
</div>

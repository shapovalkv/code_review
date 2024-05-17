@if (!isChatFeature())
    <div class="col-12 col-md-{{$count ? 9 : 12}} list-messages" id="chatWindow" {!! $count ?: 'style="width: 100% !important; flex: 0 0 100% !important;"' !!}>
        <div class="alert alert-danger alert-block text-center mt-5 ml-5 mr-5">
            <h4>{!! __('Please upgrade your Subscription Plan to use the Chat Feature') !!}</h4>
            <a class="btn btn-link" href="{{route('subscription')}}">{{__('Change Your Subscription Plan')}}</a><br>
            <strong style="color: #000;">{{__('-or- contact :role using profile information (email/phone number)', ['role' => is_employer() ? 'Candidate' : 'Employer'])}}</strong>
        </div>
    </div>
@else
    <div class="col-12 col-md-9 list-messages" id="chatWindow" :wire:key="{{$chatId}}">
        @livewire('chat-media-screen')

        <div class="messages-wrap {{ $active ? '' : 'd-none' }}">
            @if ($chatId)
                <div class="head">
                    <button class="btn btn-link head__btn" type="button" onclick="backToChatList()">
                        <i class="la la-arrow-left"></i>
                    </button>

                    <div class="user">
                        <div class="head__user-link">
                            <div class="head__user-avatar"
                                 style="{{ backgroundImageStyles($avatar, 'cover', $avatar) }}"></div>

                            <div class="">
                                <div class="head__name">{{ $participantName }}
                                    @if(isset($profileLink))
                                        <a href="{{$profileLink}}">Profile</a>
                                    @endif
                                    @if(isset($jobLink))
                                        | <a href="{{$jobLink}}">Job</a>
                                    @endif
                                </div>
                                <div class="head__position">{{ $chatTopic }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button
                            class="head__dropdown-toggle btn btn-link dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"
                            style="transform: rotate(90deg); text-decoration: none;align-items: end;">
                            ...
                        </button>

                        <div class="dropdown-menu dropdown-menu-right">
                            {{--                        <a class="dropdown-item" href="#"--}}
                            {{--                           wire:click="$emit('onScreenChange', '{{ Modules\User\Constants\ConversationConstant::SCREEN_MEDIA }}')">Attachments</a>--}}
                            {{--                    <a class="dropdown-item" href="#" onclick="clearChatHistory()">Clear chat history</a>--}}
                            <a class="dropdown-item" href="#" onclick="deleteChat()">Delete chat</a>
                            {{--                    <a class="dropdown-item" href="#" onclick="initAbuseModal('{{ AbuseConstant::TARGET_CONVERSATIONS }}', '{{ $chatId }}', '{{ $participantName }}')">Misconduct</a>--}}
                        </div>
                    </div>
                </div>

                @livewire('chat-messages-screen')
                <div wire:poll.8000ms="isClosed">
                    @if(!$isClosed)
                        <livewire:chat-input
                            :chatId="$chatId"
                        />
                    @endif
                </div>
            @endif
        </div>
    </div>
    @push('bottom_content')
        <script>
            function initAbuseModal(target, targetId, userName) {
                Livewire.emit('initAbuseModal', target, targetId, userName);
            }

            Livewire.on('hideAbuseModal', () => {
                $("#modalMisconduct").modal('hide');
            });

            Livewire.on('showAbuseModal', () => {
                $("[name=reason]:checked").trigger('change');
                $("#modalMisconduct").modal('show');
            });

            $("#chatWindow").addClass('d-none').addClass('d-lg-block');
        </script>
    @endpush
@endif

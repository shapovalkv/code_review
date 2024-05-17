<div class="col-12 col-md-5 list-message-users @if (!$activeChatId) d-block @endif" id="chatList" wire:init="init">
    <div class="list-message-users__wrap">
        @if($chats)
        <div class="list-message-users__search-wrap">
            <input
                wire:model.defer="search"
                wire:keydown="$emit('chatSearch')"
                class="form-control list-message-users__search-input"
                name="keywords"
                type="text"
                placeholder="Search here"
            >
            <i class="list-message-users__search-icon ri-search-line la la-search"></i>
        </div>
        @endif
        <div wire:poll.8000ms="chatSearch" class="list-message-users__user-wrap">
            @foreach ($chats as $chat)
                <div
                    data-chat-list-id="{{ $chat['id'] }}"
                    class="user {{ $activeChatId === $chat['id'] ? 'active' : '' }}"
                    role="button"
                    onclick="onChatSelected(`{{ $chat['id'] }}`)"
                >
                    <div class="user__main">
                        <div class="user__img-wrap {{ $chat['isOnline'] ? 'online' : '' }} ">
                            <img
                                class="user__img"
                                src="{{ $chat['avatar'] }}"
                                alt="avatar {{ $chat['userName'] }}"
                            >
                        </div>
                        <div class="user__info">
                            <div class="user__info-name">{{ $chat['userName'] }}</div>
                            @if($chat['otherUserParticipantPosition'])
                                <div class="user__info-position">
                                    <i class="ri-briefcase-line"></i>

                                    <span>{{ $chat['otherUserParticipantPosition'] }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="user__param">
                            <i class="count @if (!$chat['unreadMessages']) d-none @endif">{{ $chat['unreadMessages'] }}</i>
                            <div class="user__param-time">
                                @if($chat['activity'])
                                    {{\Carbon\Carbon::create($chat['activity'])->diffForHumans()}}
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($chat['topic'])
                        <div class="user__additional">
                            <i class="ri-wechat-line"></i>
                            <span>{{$chat['topic']}}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="divider-80 d-md-none d-sm-block"></div>
    </div>
</div>

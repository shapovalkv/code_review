<div class="files-wrap {{ $active ? '' : 'd-none' }}">
    <div class="head">
        <button
            class="btn btn-link head__btn"
            type="button"
            wire:click="$emit('onScreenChange', '{{ \Modules\User\Constants\ConversationConstant::SCREEN_MESSAGES }}')"
        >
            <i class="la la-arrow-left"></i>
        </button>
    </div>

    <div class="select">
        <div>
            @foreach (\Modules\User\Constants\ConversationConstant::TABS as $tabKey => $tabTitle)
                <div data-tab-key="{{ $tabKey }}" class="{{/* $activeTab === $tabKey ?*/ 'active' /*: ''*/ }}" onclick="onMediaTabChange('{{ $tabKey }}')">{{ $counters[$tabKey] }} {{ $tabTitle }}</div>
            @endforeach
        </div>
    </div>

    <livewire:chat-media-photos-tab :active="\Modules\User\Constants\ConversationConstant::TAB_PHOTOS" />
    <livewire:chat-media-files-tab  :active="\Modules\User\Constants\ConversationConstant::TAB_FILES" />

     <div class="down">
         <div class="list-message-users__search-wrap">
             <input class="form-control list-message-users__search-input" name="keywords" type="text" placeholder="Search">

             <i class="list-message-users__search-icon ri-search-line"></i>
         </div>
    </div>

    @push('bottom_content')
        <script>
            function onMediaTabChange(tabKey)
            {
                if (!$(`[data-tab-key=${tabKey}].active`).length) {
                    Livewire.emit('onMediaTabChange', tabKey);
                }
            }
        </script>
    @endpush
</div>

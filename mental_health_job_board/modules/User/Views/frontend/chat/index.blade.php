@extends('layouts.user')

@section("head")
    <link href="{{ asset('dist/frontend/module/user/css/chat.css?' . config('app.asset_version')) }}" rel="stylesheet">
    @livewireStyles

    <style>
        .dashboard .dashboard-outer {
            padding: 69px 0 0 !important;
        }

        .dashboard .dashboard-outer .row {
            margin-left: -8px;
            margin-right: -8px;
        }

        .header-span {
            display: none;
        }

        @media screen and (min-width: 1200px) {
            .dashboard .dashboard-outer {
                padding: 84px 0 0 !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="row mt-5">
        @if(!$count && isChatFeature())
            <div class="col-12">
                <div class="alert alert-info alert-block">
                    <strong>{{__('Direct Messages from other Users are displayed here')}}</strong>
                </div>
            </div>
        @endif
        <div class="col-12">
            @include('admin.message')
        </div>
        @if($count)
            <livewire:chat-list :wire:key="'chat-list'" active-chat-id="{{ $activeChatId }}"/>
        @endif
        <livewire:chat/>
    </div>
@endsection

@push('js')
    @if (isChatFeature())
        <div class="divider-80 d-none d-lg-block"></div>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('libs/autosize/autosize.min.js') }}"></script>
        <script src="{{ asset('libs/picmo/index.js') }}"></script>
        <script src="{{ asset('libs/picmo/popup-picker.js') }}"></script>
        <script>
            let messageList = null
            let idLastMessage = 0
            let observer = null

            Livewire.on('chatReady', url => {
                displayChat()
                history.replaceState(null, null, url)
            })

            Livewire.on('newMessageAdded', function () {
                scrollToTheMessagesListBottom();
            });

            Livewire.on('chatSelected', function () {
                scrollToTheMessagesListBottom();
            });

            Livewire.on('updateChatList', function () {
                const newMessageList = $('.js-message-item')
                const newIdLastMessage = +newMessageList[newMessageList.length - 1].dataset?.id || 0

                if (newIdLastMessage !== idLastMessage) {
                    const targetMessage = $(`[data-id="${idLastMessage}"]`)[0]

                    if (targetMessage) {
                        const options = {
                            root: null,
                            rootMargin: "300px",
                            threshold: 0,
                        }

                        observer = new IntersectionObserver(entries => checkIsVisibleLastMessage(entries, newIdLastMessage), options)
                        observer.observe(targetMessage)
                    } else {
                        idLastMessage = newIdLastMessage
                    }
                }
            });

            Livewire.on('updateMessageValue', function (e) {
                autosize.update(document.querySelector('#messageText'))
            })

            Livewire.on('chatReady', function () {
                let interval = setInterval(() => {
                    if (document.querySelector(".message-list")) {
                        clearInterval(interval);
                        scrollToTheMessagesListBottom();
                    }

                    // initEmojiPopup()
                    autosize(document.querySelector('#messageText'));

                    $('#messageText').on('keyup', event => {
                        if (event.key === 'Enter' && !event.shiftKey) {
                            Livewire.emit('newMessage')
                        }
                    })

                    $('#messageText').on('keydown', event => {
                        if (event.key === 'Enter' && !event.shiftKey) {
                            event.preventDefault()
                        }
                    })

                    $('#chatWindow').on('click', event => {
                        Livewire.emit('recalculateCount')
                    })

                    initAutoScrollByLastMessage()
                }, 500);
            });

            Livewire.on('deleteChat', function () {
                window.location.href = '{{route('user.chat.index')}}';
            })

            function scrollToTheMessagesListBottom() {
                console.log('scrollToTheMessagesListBottom')
                const div = document.getElementById("message-list");
                // let div = $('.message-list');
                console.log(div);
                if (div !== null) {
                    div.scrollTo({
                        top: div.scrollHeight,
                        behavior: 'smooth',
                        block: "end"
                    });
                }
                // const messageList = document.querySelectorAll(".js-message-item");
                //
                // if (messageList.length > 0) {
                //     messageList[messageList.length - 1].scrollIntoView({behavior: "smooth", block: "end"});
                // }
            }

            function initEmojiPopup() {
                const trigger = document.querySelector('#trigger-emotion');
                const target = document.querySelector('#messageText');

                if (trigger && target) {
                    if (window.innerWidth >= 768) {
                        // const picker = createPopup({}, {
                        //     referenceElement: trigger,
                        //     triggerElement: trigger,
                        //     className: 'message-emoji-popup',
                        //     position: 'top-start',
                        // });
                        //
                        // trigger.addEventListener('click', () => {
                        //     picker.toggle();
                        // });
                        //
                        // picker.addEventListener('emoji:select', (selection) => {
                        //     target.value = target.value + selection.emoji
                        //     target.dispatchEvent(new Event("input"))
                        // });
                    } else {
                        const bsOffcanvas = new bootstrap.Offcanvas('#emoji-mobile-offcanvas')
                        const container = document.querySelector('.pickerContainer');
                        const picker = window.picmo.createPicker({
                            rootElement: container
                        });
                        trigger.addEventListener('click', () => {
                            bsOffcanvas.show();
                        });
                        picker.addEventListener('emoji:select', (selection) => {
                            target.value = target.value + selection.emoji
                            target.dispatchEvent(new Event("input"))
                            bsOffcanvas.hide();
                        });
                    }
                }
            }

            function backToChatList() {
                // remove only numbers from the end of a string?
                history.replaceState(null, '', window.location.href.replace(/[\d\.]+$/, ''))

                Livewire.emit('onRemoveChatId')

                $("#chatWindow")
                    .addClass('d-none')
                    .addClass('d-lg-block');

                $("#chatList").addClass('d-block');
            }

            function displayChat() {
                $("#chatWindow")
                    .removeClass('d-none')
                    .removeClass('d-lg-block');

                $("#chatList").removeClass('d-block');
            }

            function onChatSelected(id) {
                const element = $(`[data-chat-list-id=${id}]`);
                Livewire.emit('chatSelected', id);
                Livewire.emit('inputSelected', id);
                if (!element.is('.active')) {
                    Livewire.emit('chatSelected', id);
                    Livewire.emit('inputSelected', id);

                } else {
                    displayChat();
                }
            }

            function clearChatHistory() {
                Livewire.emit('onChatHistoryClear')
            }

            function deleteChat() {
                if (confirm('Are you shure?')) {
                    Livewire.emit('deleteChat')
                }
            }

            function initAutoScrollByLastMessage() {
                console.log('initAutoScrollByLastMessage')
                messageList = $('.js-message-item')

                if (messageList.length > 0) {
                    idLastMessage = +messageList[messageList.length - 1].dataset.id
                }
            }

            function checkIsVisibleLastMessage(entries, newIdLastMessage) {
                console.log('checkIsVisibleLastMessage')
                const targetMessage = $(`[data-id="${idLastMessage}"]`)[0]

                if (entries[0].isIntersecting) {
                    scrollToTheMessagesListBottom();
                }

                observer.unobserve(targetMessage)
                idLastMessage = newIdLastMessage
            }
        </script>
    @endif
@endpush


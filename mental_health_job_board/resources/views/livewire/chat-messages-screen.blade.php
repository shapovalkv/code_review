<div wire:poll.keep-alive.8000ms="updateMessages">
    @if(!empty($messagesList))
        <div id="message-list" class="body message-list">
            @foreach ($messagesList as $date => $dateMessages)
                <div class="day">
                    <div class="day__val">{{ humanReadableDate($date) }}</div>
                </div>
                @foreach ($dateMessages as $messageItem)
                    @if($messageItem['type'] === 'text')
                        <div
                            class="{{ $messageItem['participation_id'] === $participationId ? 'msg' : 'answer' }} js-message-item"
                            data-id="{{ $messageItem['id'] }}"
                        >
                            <div class="item">
                                @if ($messageItem['is_downloadable'])
                                    <a
                                        class="download-link"
                                        href="{{ route('message.download', ['message' => $messageItem['id'] ]) }}"
                                        target="_blank"
                                    >{{ $messageItem['name'] }}</a>
                                @else
                                    @php echo nl2br($messageItem['body']) @endphp
                                @endif
                            </div>
                            <div class="time">
                                <time>{{ $messageItem['time'] }}</time>
                            </div>
                        </div>
                    @else
                        <div class="day">
                            <div
                                class="day__val bg-danger text-white">@php echo nl2br($messageItem['body']) @endphp</div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    @else
        <div class="body message-list no-msg">
            <div>
                <i>
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M5.87014 7.87859L8.30822 11.834C8.41489 12.0074 8.58156 12.0054 8.6489 11.9961C8.71623 11.9867 8.87824 11.9454 8.93691 11.7487L11.9857 1.45173C12.039 1.26972 11.941 1.14572 11.897 1.10172C11.8543 1.05772 11.7323 0.963715 11.5557 1.01372L1.25134 4.03114C1.056 4.08848 1.01333 4.25248 1.00399 4.31982C0.994661 4.38849 0.991994 4.55849 1.16467 4.66716L5.16546 7.16924L8.70023 3.59713C8.89424 3.40112 9.21091 3.39912 9.40759 3.59313C9.60426 3.78713 9.60559 4.10448 9.41159 4.30048L5.87014 7.87859ZM8.5969 13.0001C8.13288 13.0001 7.70753 12.7641 7.45686 12.3587L4.87211 8.1646L0.63465 5.51452C0.177969 5.22851 -0.0607052 4.71916 0.0132971 4.18381C0.0866327 3.64846 0.453977 3.22312 0.969993 3.07178L11.2743 0.0543533C11.7483 -0.0843176 12.2563 0.0470198 12.6057 0.39503C12.955 0.746375 13.085 1.25972 12.9437 1.73574L9.89494 12.0321C9.74226 12.5501 9.31558 12.9161 8.78157 12.9874C8.7189 12.9954 8.65823 13.0001 8.5969 13.0001Z"
                              fill="white"/>
                    </svg>
                </i>
                <p>Start a conversation with the job poster to help you determine the scope of the request so
                    you
                    can determine if there is a mutual fit</p>
            </div>
        </div>
    @endif
</div>

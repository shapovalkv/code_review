<i data-message-counter="{{ $chatId }}" class="count @if (!$count) d-none @endif">{{ $count }}</i>

@push('bottom_content')
    <script>
        Livewire.on("updateUnreadCounter:{{ $chatId }}", function (count) {
            const element = $("[data-message-counter={{ $chatId }}]");

            count = +count;

            element.text(count);

            if (!count) {
                element.addClass('d-none');
            } else {
                element.removeClass('d-none');
            }
        })
    </script>
@endpush

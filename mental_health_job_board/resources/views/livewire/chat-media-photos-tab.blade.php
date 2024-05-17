<div class="body {{ $active ? '' : 'd-none' }}">
    @foreach ($messagesList as $date => $files)
        <div class="data">{{ $date }}</div>

        <div class="row gutters-3">
            @foreach ($files as $file)
                <div class="col-4">
                    <a href="{{ route('message.download', ['message' => $file['id']]) }}" target="_blank">
                        <div class="img-item" style="background-image: url('{{ $file['src'] }}')"></div>
                    </a>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<div class="body {{ $active ? '' : 'd-none' }}">
    @foreach ($messagesList as $date => $files)
        <div class="data">{{ $date }}</div>

        @foreach ($files as $file)
            <div class="file-item">
                <table>
                    <tr>
                        <td>
                            <div style="background-color: #3277DE">{{ $file['extension'] }}</div>
                        </td>
                        <td>
                            <div class="name">{{ $file['body'] }}</div>
                            <div class="info">{{ $file['size'] }} &middot; {{ humanReadableDate($file['date']) }}, {{ $file['time'] }}</div>
                        </td>
                        <td>
                            <a  href="{{ route('message.download', ['message' => $file['id']]) }}" target="_blank" class="link"><i class="fas fa-long-arrow-alt-down"></i></a>
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    @endforeach
</div>

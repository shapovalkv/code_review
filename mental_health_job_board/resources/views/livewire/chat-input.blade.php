<div class="row">
    <div class="col-md-12" style="margin-top: 10px;">
        <div class="input-group mb-3">
            <textarea
                id="messageText"
                class="input-send form-control"
                rows="2"
                placeholder="Type a message..."
                wire:model.defer="message"
                style="overflow-x: hidden;overflow-wrap: break-word;height: 50px;margin: 0 0 0 -6px;border-radius: 10px 0 0 10px;border:0;"
            ></textarea>
            <div class="input-group-append">
                <button wire:click.prevent="$emit('newMessage')" class="btn-send theme-btn btn-style-ten text-black" style="margin-right: -6px; border-radius: 0 10px 10px 0;"
                        style="border-radius: 10px;margin-top: 12px;">
                    {{__('Send')}}
                </button>
            </div>
        </div>

        @error('message') <span class="text-danger small">{{ $message }}</span> @enderror
        @error('files') <span class="text-danger small">{{ $message }}</span> @enderror
        @error('files.*') <span class="text-danger small">{{ $message }}</span> @enderror

        @if ($currentFiles)
            @foreach ($currentFiles as $key => $file)
                <div class="message-file">
                    <span class="small">{{ $file->getClientOriginalName() }}</span>

                    <i role="button" wire:click="removeFile('{{ $key }}')"
                       class="message-file__icon ri-close-line"></i>
                </div>
            @endforeach
        @endif
        <input wire:model="files" multiple type="file" class="d-none" id="file">
    </div>


        {{--        <button class="btn-attachment btn btn-link" onclick="$('#file').trigger('click')">--}}
        {{--            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">--}}
        {{--                <path fill="currentColor"--}}
        {{--                      d="m14.829 7.757l-5.657 5.657a1 1 0 1 0 1.414 1.415l5.657-5.657A3 3 0 0 0 12 4.929l-5.657 5.657a5 5 0 0 0 7.071 7.07L19.071 12l1.414 1.414l-5.656 5.657a7 7 0 1 1-9.9-9.9l5.657-5.656a5 5 0 0 1 7.071 7.07L12 16.244A3 3 0 1 1 7.758 12l5.656-5.657l1.415 1.414Z"/>--}}
        {{--            </svg>--}}
        {{--        </button>--}}
        {{--        <button id="trigger-emotion" class="btn-emotion btn btn-link">--}}
        {{--            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">--}}
        {{--                <path fill="currentColor"--}}
        {{--                      d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10Zm0-2a8 8 0 1 0 0-16a8 8 0 0 0 0 16Zm-5-7h2a3 3 0 1 0 6 0h2a5 5 0 0 1-10 0Zm1-2a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3Zm8 0a1.5 1.5 0 1 1 0-3a1.5 1.5 0 0 1 0 3Z"/>--}}
        {{--            </svg>--}}
        {{--        </button>--}}

</div>


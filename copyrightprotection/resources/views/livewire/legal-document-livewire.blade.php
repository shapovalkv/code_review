<div class="tab-pane px-sm-3 px-md-5 active show" role="tabpanel"
     aria-labelledby="bootstrap-wizard-validation-tab3" id="bootstrap-wizard-validation-tab4">
    <div id="wizard-dropzone" class="dropzone dropzone-single p-0" x-data="drop_file_component()"
         style="border:0; background: transparent">
        <div
            class="w-96 rounded border-dashed border-2 flex flex-col justify-center items-center"
            x-bind:class="dropingFile ? 'bg-gray-400 border-gray-500' : 'border-gray-500 bg-gray-200'"
            x-on:drop="dropingFile = false"
            x-on:dragover.prevent="dropingFile = true"
            x-on:dragleave.prevent="dropingFile = false">
            <div>
                <div class="dz-message" data-dz-message="data-dz-message">
                    <div class="dz-message-text content-center">
                        Drop your file here Or
                        <input class="hidden document-upload" type="file" id="file-upload" multiple/>

                        <div
                            x-data="{ isUploading: false, progress: 0 }"
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                        >
                            <div x-show="isUploading">
                                <progress max="100" x-bind:value="progress"></progress>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1" wire:loading.flex wire.target="files">
                    <img class="me-2" src="{{ asset('/assets/img/icons/cloud-upload.svg') }}" width="25" alt=""/>
                    <div>Processing Files</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @error('files')
            <span class="error" style="color: red">{{ $message }}</span>
            @enderror
            <span id="fileSizeError" class="error" style="color: red"></span>
        </div>

        @if(!empty($projectFiles))
            <div class="table-responsive scrollbar">
                <table class="table">
                    <tbody>
                    @foreach($projectFiles as $projectFile)
                        <tr>
                            <td>{{ $projectFile->name }}</td>
                            <td class="text-end">
                                <div>
                                    <button class="btn btn-link p-0" wire:click="downloadFile({{ $projectFile->id }})"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Download">
                                        <span class="text-500 fas fa-download"></span>
                                    </button>
                                    <button class="btn btn-link p-0 ms-2"
                                            wire:click="deleteFile({{ $projectFile->id }})"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                        <span class="text-500 fas fa-trash-alt"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@push('scripts')
    <script>
        document.getElementById('file-upload').addEventListener('change', function (event) {
            handleFileSelect(event)
        })

        document.getElementById('wizard-dropzone').addEventListener('drop', function (event) {
            event.preventDefault();
            handleFileDrop(event)
        })

        function handleFileSelect(event) {
            if (event.target.files.length) {
                uploadMultiple(event.target.files)
            }
        }

        function handleFileDrop(event) {
            if (event.dataTransfer.files.length > 0) {
                uploadMultiple(event.dataTransfer.files)
            }
        }

        function uploadMultiple(files) {
            const $this = this;
            const maxFileSize = 2 * 1024 * 1024; // 2 МБ в байтах

            this.isUploading = true;

            let hasOversizedFiles = false;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                if (file.size > maxFileSize) {
                    hasOversizedFiles = true;
                }
            }

            if (hasOversizedFiles) {
                const errorMessage = "One or more files are too large and cannot be uploaded.";
                document.getElementById('fileSizeError').textContent = errorMessage;
                $this.isUploading = false; // Останавливаем загрузку
            } else {
            @this.uploadMultiple(
                'files',
                files,
                function (success) {
                    $this.isUploading = false;
                    $this.progress = 0;
                },
                function (error) {
                    console.log('error', error);
                },
                function (event) {
                    $this.progress = event.detail.progress;
                }
            );
            }
        }

        function drop_file_component() {
            return {
                dropingFile: false,
                handleFileDrop(e) {
                    if (event.dataTransfer.files.length > 0) {
                        const files = e.dataTransfer.files;
                    @this.uploadMultiple('files', files,
                        (uploadedFilename) => {
                        }, () => {
                        }, (event) => {
                        }
                    )
                    }
                }
            };
        }

        Livewire.on('refreshComponent', function () {
            Livewire.emit('refresh');
        });
    </script>
@endpush

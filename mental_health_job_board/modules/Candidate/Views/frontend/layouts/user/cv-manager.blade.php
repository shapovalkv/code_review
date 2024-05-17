@extends('layouts.user')

@section('content')
    <div class="upper-title-box">
        <h3>{{ __("Resume/ CV Manager") }}</h3>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <!-- Ls widget -->
            <div class="cv-manager-widget ls-widget">
                <div class="tabs-box">

                    <div class="widget-title"><h4>{{ __("Resume/ CV Manager") }}</h4></div>

                    <div class="widget-content">
                        <div class="uploading-resume mb-3">
                            <div class="uploadButton cv-drag-area">
                                <input class="uploadButton-input" type="file"  name="attachments[]" accept=".doc,.docx,.pdf" id="upload"/>
                                <label class="cv-uploadButton" for="upload">
                                    <span class="title">{{ __("Drop files here to upload") }}</span>
                                    <span class="text">{{ __("To upload file size is (Max 5Mb) and allowed file types are (.doc, .docx, .pdf)") }}</span>
                                    <button class="theme-btn btn-style-one">{{ __("Upload") }}</button>
                                </label>
                                <img class="loading-icon" src="{{ asset('images/loading.gif') }}" alt="loading">
{{--                                <span class="uploadButton-file-name"></span>--}}
                            </div>
                        </div>

                        <div class="files-outer">
                            @if($rows->count() > 0)
                                @foreach($rows as $row)
                                    <div data-id="{{ $row->id }}" class="file-edit-box {{ $row->is_default == 1 ? 'is-default' : '' }}">
                                        <span class="title">{{ $row->media->file_name }}.{{ $row->media->file_extension }}</span>
                                        <input type="radio" {{ $row->is_default == 1 ? 'checked' : '' }} class="form-control" name="csv_default" value="{{ $row->id }}">
                                        <div class="edit-btns">
                                            <button class="delete-cv"><span class="la la-trash"></span></button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('footer')
@endsection

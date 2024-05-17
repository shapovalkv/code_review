@extends('layouts.user')

@section('content')
    @include('admin.message')
    <div class="row justify-content-md-center">
        <div class="col-lg-6">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{$row->id > 0 ? __("Update User") : __("Add User")}}</h4>
                    </div>
                    <div class="widget-content">
                        <form action="{{route('user.company.staff.store', ['user' => $row])}}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{$row->id}}">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                       value="{{old('first_name', $row->first_name)}}" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                       value="{{old('last_name', $row->last_name)}}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{old('email', $row->email)}}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       value="{{old('phone', $row->phone)}}" required placeholder="(###) ###-####">
                            </div>
                            <div class="form-group">
                                <label>Permissions</label>
                            </div>
                            <div class="form-group row permissions-block">
                                @foreach(\App\Enums\UserPermissionEnum::getConstants() as $item)
                                    <div class="col-md-6">
                                        <input type="checkbox" value="1" name="permissions[{{$item}}]"
                                               id="{{$item}}" {{$row->id > 0 && $row->hasPermission($item) ? 'checked' : ''}}>
                                        <label for="{{$item}}">{{\App\Enums\UserPermissionEnum::getName($item)}}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <small class="form-text text-muted">By clicking "Save and notify new user", I
                                    agree {{config('app.name')}} may share access to {{$company->name}} account with
                                    this user, that I am authorized to grant such access, and for my email address to be
                                    shared with the user in this email notification. This user's access will match the
                                    role(s) I have selected from them.</small>
                            </div>
                            <div class="form-group">
                                <small class="form-text text-muted">The password will be generated automatically</small>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="theme-btn btn-style-seven"><i
                                        class="fa fa-save"></i> {{__($row->id > 0 ? 'Save' : 'Save and notify new user')}}
                                </button>
                            </div>
                        </form>
                    </div>
                    @if($row->id > 0)
                        <form action="{{route('user.company.staff.disable', ['trashed_user' => $row])}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-warning pull-right mt-3"
                                    onclick="return confirm('Are You Sure?')"><i
                                    class="fa fa-trash"></i> {{__('Disable')}}</button>
                        </form>
                    @endif
                    @if($row->id > 0)
                        <form action="{{route('user.company.staff.delete', ['trashed_user' => $row])}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger pull-right mt-3"
                                    onclick="return confirm('Are You Sure?')"><i
                                    class="fa fa-trash"></i> {{__('Delete')}}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const phoneInputs = document.querySelectorAll('input[name="phone"]');
            Inputmask({
                mask: '(###) ###-####',
                repeat: 1,
                greedy: false
            }).mask(phoneInputs);
        });
        function disableCheckbox(checkbox) {
            checkbox.prop('checked', false).prop('disabled', true).next('label').css('opacity', '0.5');
        }
        function enableCheckbox(checkbox) {
            checkbox.prop('disabled', false).next('label').css('opacity', '1');
        }
        $(function () {
            $('.permissions-block input[type="checkbox"]').on('click', function () {
                if ($(this).attr('id') === '{{\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS}}') {
                    if ($(this).prop('checked')) {
                        $('.permissions-block').find('input[type="checkbox"]').each(function () {
                            if ($(this).attr('id') !== '{{\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS}}') {
                                disableCheckbox($(this));
                            }
                        })
                    } else {
                        $('.permissions-block').find('input[type="checkbox"]').each(function () {
                            if ($(this).attr('id') !== '{{\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS}}') {
                                enableCheckbox($(this));
                            }
                        })
                    }
                } else {
                    if ($(this).prop('checked')) {
                        $('.permissions-block').find('input[type="checkbox"]').each(function () {
                            if ($(this).attr('id') === '{{\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS}}') {
                                disableCheckbox($(this));
                            }
                        })
                    } else {
                        if ($('.permissions-block').find('input[type="checkbox"]:checked').length === 0) {
                            enableCheckbox($('.permissions-block').find('input[id="{{\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS}}"]').first());
                        }
                    }
                }
            })
        })
    </script>
@endpush

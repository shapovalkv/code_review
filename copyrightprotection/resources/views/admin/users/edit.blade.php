@php
    use App\Models\User;
@endphp
<x-app-layout>
    <div class="row gx-3">
        <div class="col-lg-12 pe-lg-2">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0"> Edit {{$user->first_name . ' ' . $user->last_name}}</h5>
                </div>
                <div class="card-body bg-light">

                    <form class="row g-3" action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf

                        <div class="col-lg-6">
                            <label class="form-label" for="first-name">First Name</label>
                            <input class="form-control" name="first_name" id="first-name" type="text" value="{{old('first_name', $user->first_name)}}">
                            @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input name="role" type="hidden" value="{{$role->name}}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="last-name">Last Name</label>
                            <input class="form-control" name="last_name" id="last-name" type="text" value="{{old('last_name', $user->last_name)}}">
                            @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" name="email" id="email" type="text" value="{{old('email', $user->email)}}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="phone">Phone</label>
                            <input class="form-control" name="phone" id="phone" type="text" value="{{old('phone', $user->phone)}}">
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="password1">Password</label>
                            <input class="form-control" name="password" id="password1" type="password" value="">
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="password2">Confirm password</label>
                            <input class="form-control" name="password_confirmation" id="password2" type="password" value="">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-dark" type="submit">Save</button>
                        </div>
                    </form>
                    @if(!$user->hasRole(User::ROLE_CUSTOMER))
                    <hr>
                    <div class="col-12 d-flex justify-content-start">
                        <form method="POST"
                              action="{{ route('admin.users.destroy', ['user' => $user]) }}"
                              style="display:inline-block">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Are you sure want to delete?')"
                                    class="btn btn-danger"
                                    title="Delete user">
                                Delete user
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

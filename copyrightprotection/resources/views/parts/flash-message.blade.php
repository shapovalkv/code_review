@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">{{ $message }}</div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger" role="alert">{{ $message }}</div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning" role="alert">{{ $message }}</div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info" role="alert">{{ $message }}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
	<span class="text-bold">Please check the form below for errors</span>
	<ul>
	@foreach ($errors->all() as $message)
		<li>{{ $message }}</li>
	@endforeach
	</ul>
</div>
@endif



@if ($message = Session::get('status'))
@php

if ($message === 'profile-updated') {
    $text = 'Profile Updated';
} else if($message === 'password-updated' ) {
    $text = 'Password Updated';
} else {
    $text = '';
}
@endphp
    <div class="alert alert-success" role="alert">{{ $text }}</div>
@endif

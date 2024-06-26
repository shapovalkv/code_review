@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('head')
    <link rel="stylesheet" href="{{ asset('dist/frontend/css/contact.css') }}">
	<style type="text/css">
		.bravo-contact-block .section{
			padding: 80px 0 !important;
		}
	</style>
@endsection
@section('content')
<div id="bravo_content-wrapper">
	@include("Contact::frontend.blocks.contact.index")
</div>
@endsection

@section('footer')
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
@endsection

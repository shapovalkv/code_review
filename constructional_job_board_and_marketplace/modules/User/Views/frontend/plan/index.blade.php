@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('head')

@endsection
@section('content')
    <section class="pricing-section">
        @include('admin.message')
        @include('User::frontend.plan.list')
    </section>
@endsection
@section('footer')
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
@endsection

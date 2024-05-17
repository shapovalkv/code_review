@extends('Email::layout')
@section('content')

    <div class="b-container">
        <div class="b-panel">
            <h3 class="email-headline"><strong>{{__('Hello, ')}}</strong></h3>
            <p>{{__('Thanks for your appeal. We will get back to you during next 24 hours.')}}</p>
            <p>{{__('Regards, constructional_job_board_and_marketplace.com')}}</p>
            <br>
        </div>
    </div>
@endsection

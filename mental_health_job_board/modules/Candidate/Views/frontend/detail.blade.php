@extends('layouts.app')
@section('head')
    <link href="{{ asset('dist/frontend/css/app.css?_ver='.config('app.version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/daterange/daterangepicker.css") }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/fotorama/fotorama.css") }}" />
@endsection
@section('content')
    @includeIf("Candidate::frontend.layouts.detail-ver.$style")
@endsection
@section('footer')
    <script>
        console.log(333)
        $(document).ready(function () {
            var baseURL = "{{ route('job.admin.getForSelect2')}}" + '?expiration_date=1&admin_invite=1&invited='
            $('.bc-apply-job-button').on('click', function () {
                var configs = $('.dungdt-select2-field-custom').data('options');
                configs.configs.ajax.url = baseURL + $(this).data('id');
                $('.dungdt-select2-field-custom').select2(configs.configs);
            })
            $('.bc-apply-job-button.bc-call-modal.invite-job').on('click', function () {
                var inviteMessageRequestUrl = "{{ route('candidate.admin.getInviteMessage')}}"
                $.ajax({
                    url: inviteMessageRequestUrl,
                    type: 'post',
                    data: {
                        candidateId: $(this).data('id')
                    },
                    success: function success(res) {
                        if (res.results) {
                            $('#content').val(res.results)
                        }
                    }
                });
            })
        });
    </script>
@endsection


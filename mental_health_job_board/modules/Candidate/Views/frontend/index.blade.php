@extends('layouts.app')

@section('content')
    @include('Candidate::frontend.layouts.search.'. $style)
@endsection

@section('footer')
    <script>
        $(document).ready(function () {
            $('#categories').select2({
                placeholder: 'Start typing'
            });
            $('#categories').on("change", function (e) {
                const items = $(this).val();
                if (items.length > 0) {
                    $('.categories_select').find('.categories_icon').remove()
                    $('.categories_select').find('.select2-selection--multiple').css('padding-left', '0')
                    $('.categories_select').find('.select2-selection--multiple').addClass('delete-before')
                    $('.categories_select').find('.select2-selection--multiple').removeClass('add-before')
                } else {
                    $('.categories_select').append("<span class='categories_icon flaticon-briefcase'></span>")
                    $('.categories_select').find('.select2-selection--multiple').css('padding-left', '54px')
                    $('.categories_select').find('.select2-selection--multiple').addClass('add-before')
                    $('.categories_select').find('.select2-selection--multiple').removeClass('delete-before')
                }
            })
        });
    </script>
    <script>
        jQuery(".view-more").on("click", function () {
            jQuery(this).closest('ul').find('li.tg').toggleClass("d-none");
            jQuery(this).find('.tg-text').toggleClass('d-none');
        });
    </script>
    <script>
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
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        var bravo_map_data = {
            markers: {!! json_encode($markers) !!},
            center: [{{ !empty($markers[0]['lat']) ? $markers[0]['lat'] : 40.80 }}, {{ !empty($markers[0]['lng']) ? $markers[0]['lng'] : -73.70 }}]
        };
    </script>
    <script type="text/javascript"
            src="{{ asset('module/candidate/js/candidate-map.js?_ver='.config('app.asset_version')) }}"></script>
    <script>
        jQuery(".view-more").on("click", function () {
            jQuery(this).closest('ul').find('li.tg').toggleClass("d-none");
            jQuery(this).find('.tg-text').toggleClass('d-none');
        });
    </script>
@endsection

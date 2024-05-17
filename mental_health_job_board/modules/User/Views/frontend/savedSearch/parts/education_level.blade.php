<li>{{__("Education Levels")}}:
@foreach($value as $id)
    @if($id === 'diploma') {{ __("High School Diploma") }};
    @elseif($id === 'associate') {{ __("Associate Degree") }};
    @elseif($id === 'bachelor') {{ __("Bachelor Degree") }};
    @elseif($id === 'master') {{ __("Master’s Degree") }};
    @elseif($id === 'professional') {{ __("Professional’s Degree") }}; @endif
@endforeach
</li>

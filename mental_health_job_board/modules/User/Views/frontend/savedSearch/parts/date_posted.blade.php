<li>{{__('Date Posted')}}:
@if($value == 'all') {{ __("All") }}
@elseif($value == 'last_hour') {{ __("Last Hour") }}
@elseif($value == 'last_1') {{ __("Last 24 Hours") }}
@elseif($value == 'last_7') {{ __("Last 7 Days") }}
@elseif($value == 'last_14') {{ __("Last 14 Days") }}
@elseif($value == 'last_30') {{ __("Last 30 Days") }} @endif
</li>

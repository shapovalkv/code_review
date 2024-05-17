<li>Location:
    @foreach($jobTypes as $type)
        @if(in_array($type->id, $value))
            {{$type->name}};
        @endif
    @endforeach
</li>

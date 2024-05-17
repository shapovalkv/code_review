<li>{{__("Training Location")}}:
    @foreach ($value as $id)
        {{ucwords(str_replace('_', ' ', $id))}};
    @endforeach
</li>

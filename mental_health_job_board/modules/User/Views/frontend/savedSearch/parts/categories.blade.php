<li>{{__("Categories")}}:
    @foreach ($value as $id)
        @if($row->page === 'candidate' && isset($candidateListCategories[$id])){{$candidateListCategories[$id]->name}};
        @elseif($row->page === 'job' && isset($jobListCategories[$id])){{$jobListCategories[$id]->name}};@endif
    @endforeach
</li>

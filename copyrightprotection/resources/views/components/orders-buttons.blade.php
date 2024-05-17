<div class="d-flex justify-content-between">
    <div class="dropdown font-sans-serif d-inline-block mb-2">
        <div class="btn-group">
            @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_CUSTOMER) && !empty(\Illuminate\Support\Facades\Auth::user()->selected_project_id))
                <button class="btn dropdown-toggle mb-2 btn-light" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: 1px solid #fff;">
                    {{ \App\Models\UserProject::find(\Illuminate\Support\Facades\Auth::user()->selected_project_id)->name }} </button>
                <div class="dropdown-menu">
                    @foreach(\App\Models\UserProject::where('user_id', \Illuminate\Support\Facades\Auth::id())->where('status', \App\Models\UserProject::ACTIVE)->get() as $project)
                        <a class="dropdown-item" href="{{ route('selectProject', ['project' => $project->id]) }}">{{ $project->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <form action="{{ route('createProject') }}">
        <button class="btn btn-falcon-default me-1 mb-1" type="submit" style="border: 1px solid #fff;">
            <span class="fas fa-plus me-1" data-fa-transform="shrink-3"></span>ADD NEW COPYRIGHT
        </button>
    </form>
</div>

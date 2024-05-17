@extends('layouts.user')
@section('head')

@endsection
@section('content')
    <div class="upper-title-box">
        @if(is_employer())
            <h3>{{__("Bookmark Resumes")}}</h3>
        @else
            <h3>{{__("Bookmark Jobs")}}</h3>
        @endif
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        @if(is_employer())
                            <h3>{{__("Shortlist Resumes")}}</h3>
                        @else
                            <h4>{{ __("My Favorite Jobs") }}</h4>
                        @endif

{{--                        <div class="chosen-outer">--}}
{{--                            <form method="get" class="default-form form-inline" action="">--}}
{{--                                <div class="form-group mb-0 mr-1">--}}
{{--                                    <input type="text" name="s" placeholder="{{ __("Search...") }}" value="{{ request()->get('s') }}" class="form-control">--}}
{{--                                </div>--}}
{{--                                <button type="submit" class="theme-btn btn-style-one">{{ __("Search") }}</button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
                    </div>

                    <div class="widget-content">
                        @if(is_employer())

                            @if($rows->total() > 0)
                                @foreach($rows as $row)
                                    @php
                                    $row = $row->service;
                                    if (!$row) {
                                        continue;
                                    }
                                    @endphp
                                    <div class="candidate-block-three">
                                        <div class="inner-box">
                                            <div class="content">
                                                <figure class="image">
                                                    <img src="{{is_applied($row->id) ? $row->user->getAvatarUrl() : asset('images/avatar.png')}}" alt="{{ is_applied($row->id) ? $row->user->getDisplayName() : $row->user->getShortCutName()}}">
                                                </figure>
                                                <h4 class="name"><a href="{{ $row->getDetailUrl() }}">{{ is_applied($row->id) ? $row->user->getDisplayName() : $row->user->getShortCutName() }}</a></h4>
                                                <ul class="candidate-info">
                                                    @if($row->title)
                                                        <li class="designation">{{$row->title}}</li>
                                                    @endif
                                                    @if($row->city)
                                                        <li><span class="icon flaticon-map-locator"></span> {{$row->city}}</li>
                                                    @endif
                                                    @if($row->expected_salary)
                                                        <li><span class="icon flaticon-money"></span> {{$row->expected_salary}} {{currency_symbol()}}  / {{$row->salary_type}}</li>
                                                    @endif
                                                </ul>
                                                <ul class="post-tags">
                                                    @if(!empty($row->categories))
                                                        @foreach($row->categories as $oneCategory)
                                                            @php $t = $oneCategory->translateOrOrigin(app()->getLocale()); @endphp
                                                            <li><a href="{{ route('candidate.index', ['category' => $oneCategory->id]) }}">{{$t->name}}</a></li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="option-box">
                                                <button class="delete-btn remove-wishlist" data-confirm="{{ __("Do you want to remove?") }}" data-id="{{ $row->id }}" data-text="{{ __("Remove Candidate") }}"><span class="la la-trash"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="ls-pagination">
                                    {{$rows->appends(request()->query())->links()}}
                                </div>
                            @else
                                <h4 class="text-center mb-5">{{ __("No items") }}</h4>
                            @endif
                        @else
                            <div class="table-outer">
                                <table class="default-table manage-job-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __("Job Title") }}</th>
                                        <th>{{ __("Posted Date") }}</th>
                                        <th>{{ __("Action") }}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if($rows->total() > 0)
                                        @foreach($rows as $row)
                                            @if(!$row->service)
                                                @continue
                                            @endif
                                            <tr class="wishlist-item">
                                                <td>
                                                    @include('Job::frontend.layouts.loop.job-item-bookmark',['row'=>$row->service])
                                                </td>
                                                <td>{{ display_date($row->service->create_date) }}</td>
                                                <td>
                                                    <div class="option-box">
                                                        <ul class="option-list">
                                                            <li><a href="#" data-text="Remove" data-confirm="{{ __("Do you want to remove?") }}" data-id="{{ $row->id }}" class="remove-wishlist" ><span class="la la-trash"></span></a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">{{ __("No items") }}</td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                            <div class="ls-pagination mt-0">
                                {{$rows->appends(request()->query())->links()}}
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection

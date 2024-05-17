@extends('layouts.user')

@php
    $manifestDir = 'dist/frontend';
@endphp

@section('content')
    <div class="upper-title-box">
        <h3>{{ __("Applied Jobs") }}</h3>
        <div class="text">{{ __("Ready to jump back in?") }}</div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <!-- Ls widget -->
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{ __("My Applied Jobs") }}</h4>


                        <div class="chosen-outer">

                            <form method="get" class="default-form form-inline" action="{{ route('user.applied_jobs') }}">
                                <!--Tabs Box-->
                                <div class="form-group mb-2 mb-md-0 mr-1">
                                    <select class="form-control" name="status">
                                        <option value="">{{ __("All Status") }}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{ __("Pending") }}</option>
                                        <option value="approved" @if(request()->get('status') == 'approved') selected @endif>{{ __("Approved") }}</option>
                                        <option value="rejected" @if(request()->get('status') == 'rejected') selected @endif>{{ __("Rejected") }}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0 mr-1">
                                    <input type="text" name="s" placeholder="{{ __("Search by job name") }}" value="{{ request()->get('s') }}" class="form-control">
                                </div>
                                <button type="submit" class="theme-btn btn-style-one">{{ __("Search") }}</button>
                            </form>

                            <form method="get" class="default-form form-inline ml-3 d-md-block d-none" action="{{ route('user.applied_jobs') }}">
                                <!--Tabs Box-->
                                <div class="form-group d-inline-flex mb-0 align-items-center" style="flex-wrap: nowrap; min-width: 230px">
                                    <label class="mr-2" style="min-width: 70px">{{ __("Order By") }}</label>
                                    <select class="form-control" name="orderby" onchange="this.form.submit()">
                                        <option value="" @if(request()->get('orderby') == '') selected @endif >{{ __("Default") }}</option>
                                        <option value="newest" @if(request()->get('orderby') == 'newest') selected @endif >{{ __("Newest") }}</option>
                                        <option value="oldest" @if(request()->get('orderby') == 'oldest') selected @endif >{{ __("Oldest") }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-outer">
                            <table class="default-table manage-job-table">
                                <thead>
                                <tr>
                                    <th>{{ __("Job Title") }}</th>
                                    <th>{{ __("Date Applied") }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if($rows->count() > 0)
                                    @foreach($rows as $row)
                                        @if($row->jobInfo)
                                            <tr>
                                                <td>
                                                    <!-- Job Block -->
                                                    <div class="job-block">
                                                        <div class="inner-box">
                                                            <div class="content">
                                                                @if($row->jobInfo->company && $company_logo = $row->jobInfo->getThumbnailUrl())
                                                                    <span class="company-logo">
                                                                        <a href="{{ $row->jobInfo->company->getDetailUrl() }}"><img src="{{ $company_logo }}" alt="{{ $row->jobInfo->company }}"></a>
                                                                    </span>
                                                                @endif
                                                                <h4><a href="{{ $row->jobInfo->getDetailUrl() }}">{{ $row->jobInfo->title }}</a></h4>
                                                                <ul class="job-info">
                                                                    @if($row->jobInfo->category)
                                                                        @php $cat_translation = $row->jobInfo->category->translateOrOrigin(app()->getLocale()) @endphp
                                                                        <li><span class="icon flaticon-briefcase"></span> {{ $cat_translation->name }}</li>
                                                                    @endif
                                                                    @if($row->jobInfo->location)
                                                                        @php $location_translation = $row->jobInfo->location->translateOrOrigin(app()->getLocale()) @endphp
                                                                        <li><span class="icon flaticon-map-locator"></span> {{ $location_translation->name }}</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ display_date($row->created_at) }}</td>
                                                <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span></td>
                                                <td>
                                                    <div class="option-box">
                                                        <ul class="option-list">
                                                            <li><a href="{{ $row->jobInfo->getDetailUrl() }}" data-text="View Job"><span class="la la-eye"></span></a></li>
                                                            @if($row->status == 'pending')
                                                                <li><a href="{{ route('user.myApplied.delete', ['id' => $row->id]) }}" data-confirm="{{__("Do you want to delete?")}}" data-text="Delete Application" class="bc-delete-item"><span class="fa fa-trash"></span></a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr><td colspan="4" class="text-center">{{ __("No Items") }}</td></tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="ls-pagination">
                                {{$rows->appends(request()->query())->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('footer')
    <script src="{{ mix('js/bootstrap5.js', $manifestDir) }}"></script>
@endsection

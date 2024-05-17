<a class="dropdown-item" href="{{route('user.dashboard')}}">{{__("Dashboard")}}</a>

@if(is_employee())
    @if(auth()->user()->hasPermission([\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS, \App\Enums\UserPermissionEnum::COMPANY_STAFF_MANAGE]))
        <a class="dropdown-item" href="{{ route('user.company.staff') }}">{{__("Add User")}}</a>
    @endif
    @if(auth()->user()->hasPermission([\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS,\App\Enums\UserPermissionEnum::COMPANY_JOB_MANAGE]))
        <a class="dropdown-item" href="{{route('user.manage.jobs')}}">{{__("Manage Jobs")}}</a>
        <a class="dropdown-item" href="{{route('user.applicants')}}">{{__("All Applicants")}}</a>
    @endif
    @if(auth()->user()->hasPermission([\App\Enums\UserPermissionEnum::COMPANY_FULL_ACCESS, \App\Enums\UserPermissionEnum::COMPANY_ANNOUNCEMENT_MANAGE]))
        <a class="dropdown-item" href="{{route('seller.all.marketplaces')}}">{{__("Manage Marketplace")}}</a>
    @endif
@endif

@if(is_employer())
    <div class="dropdown-divider"></div>
    <div class="dropdown-header">{{__("Employer")}}</div>
    <a class="dropdown-item" href="{{ route('user.company.profile') }}">{{__("Company profile")}}</a>
    <a class="dropdown-item" href="{{ route('user.company.staff') }}">{{__("Add User")}}</a>
    <a class="dropdown-item" href="{{ route('user.subscription') }}">{{__("My Subscription")}}</a>

    <a class="dropdown-item" href="{{route('user.manage.jobs')}}">{{__("Manage Jobs")}}</a>
    @if(\Modules\Marketplace\Models\Marketplace::isEnable())
        <a class="dropdown-item" href="{{route('marketplace.vendor.index')}}">{{__("Manage Marketplace")}}</a>
    @endif
    <a class="dropdown-item" href="{{route('user.applicants')}}">{{__("All Applicants")}}</a>
    <a class="dropdown-item" href="{{route('user.wishList.index')}}"> {{__("Bookmark")}}</a>
@endif
@if(is_candidate() && !is_admin())
    <div class="dropdown-divider"></div>
    <div class="dropdown-header">{{__("Candidate")}}</div>
    <a class="dropdown-item" href="{{ route('user.candidate.index') }}">{{__("My profile")}}</a>
    <a class="dropdown-item" href="{{ route('user.candidate.cvManager') }}">{{__("Resume/ CV Manager")}}</a>
    @if(\Modules\Gig\Models\Gig::isEnable() && \Modules\Payout\Models\VendorPayout::isEnable())
        <a class="dropdown-item" href="{{route('payout.candidate.index')}}">{{__("Payouts")}}</a>
    @endif
    <a class="dropdown-item" href="{{route('user.applied_jobs')}}">{{__("Applied Jobs")}}</a>
    {{--    <a class="dropdown-item" href="{{route('user.following.employers')}}">{{__("Following")}}</a>--}}
@endif
@if(is_marketplace_user())
    <div class="dropdown-divider"></div>
    <div class="dropdown-header">{{__("Marketplace user")}}</div>
    <a class="dropdown-item" href="{{ route('marketplace.vendor.index') }}">{{__("Manage Marketplace")}}</a>
    <a class="dropdown-item" href="{{ route('user.marketplace_user.index') }}">{{__("Marketplace User")}}</a>
@endif

<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{route('user.wishList.index')}}"> {{__("Bookmark")}}</a>
<a class="dropdown-item" href="{{route('user.search-params.index')}}"> {{__("Saved Search Parameters")}}</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{route('user.support.index')}}">{{__("Support")}}</a>

<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{route('user.change_password')}}">{{__("Change password")}}</a>

@if(is_admin())
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="{{url('/admin')}}">{{__("Admin Dashboard")}}</a>
@endif

<div class="dropdown-divider"></div>
<a class="dropdown-item" href="#"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>

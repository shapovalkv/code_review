@extends('layouts.user')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="upper-title-box">
                <h3>{{ __("All Marketplace Posts") }}</h3>
            </div>
        </div>
        <div class="col-md-3 text-right">
{{--            <a class="theme-btn btn-style-one" href="{{ route('vendor.marketplaces.create') }}">{{__("Add new Announcement")}}</a>--}}
        </div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <!-- Ls widget -->
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{ __("All Marketplace Posts") }}</h4>
                        <div class="chosen-outer">
                            <form method="get" class="default-form form-inline">
                                <div class="form-group mb-0 mr-1">
                                    <input type="text" name="s" placeholder="{{ __("Search by name,...") }}" value="{{ request()->get('s') }}" class="form-control">
                                </div>
                                <button type="submit" class="theme-btn btn-style-ten">{{ __("Search") }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="table-outer">
                            <table class="default-table manage-job-table">
                                <thead>
                                <tr>
                                    <th style="width: 20%">{{ __("Name") }}</th>
                                    <th>{{ __("Category") }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th>{{ __("Date of Creation") }}</th>
                                    <th>{{ __("Expiration Date") }}</th>
                                    <th>{{ __("Payment Date") }}</th>
                                    <th style="width: 100px;">{{ __("Action") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($rows->count() > 0)
                                    @foreach($rows as $row)
                                        <tr @if($row->deleted_at) style="opacity:0.5" @endif>
                                            <td><a href="{{ route('seller.marketplace.edit', ['id' => $row->id]) }}">{{ $row->title }}</a></td>
                                            <td>
                                               {{ $row->MarketplaceCategory->name }}
                                            </td>
                                            <td>
                                                @if($row->deleted_at)
                                                    <span class="badge badge-danger">{{__('Deleted')}}</span>
                                                @else
                                                    <span class="badge badge-{{ $row->status }}">{{ $row->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ display_date($row->created_at)}}</td>
                                            <td>{{ $row->expiration_date ? display_date($row->expiration_date) : '-'}}</td>
                                            <td>{{ $row->orderItem ? display_date($row->orderItem->created_at) : ($row->expiration_date ? 'Free' : '-')}}</td>
                                            <td>
                                                @if(!$row->deleted_at)
                                                <div class="option-box">
                                                    <ul class="option-list">
                                                        <li><a href="{{ route('seller.marketplace.edit', ['id' => $row->id]) }}" data-text="Edit" ><span class="la la-pencil-alt"></span></a></li>
                                                        @if($row->expiration_date && \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->expiration_date)->timestamp < \Carbon\Carbon::now()->timestamp)
                                                            <li><a href="{{ route('seller.choose.marketplace.plan', ['marketplace' => $row->id]) }}" data-text="{{ __("Renew Announcement") }}"><span class="la la-refresh"></span></a></li>
                                                        @endif
                                                        <li>
                                                            <form method="post" action="{{ route('seller.marketplace.delete', ['marketplace' => $row->id]) }}">
                                                                @csrf
                                                                <input type="hidden" name="marketplace_id" value="{{ $row->id }}" />
                                                                <button type="submit" data-text="Delete" class="bc-btn-delete bc-delete-item" data-confirm="{{ __("Do you want to delete?") }}" ><span class="la la-trash"></span></button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="7" class="text-center">{{ __("No Items") }}</td></tr>
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
@endsection

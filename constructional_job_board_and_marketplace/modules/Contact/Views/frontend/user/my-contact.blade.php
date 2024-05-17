@extends('layouts.user')

@section('content')
    <div class="upper-title-box">
        <h3>{{ __("My Contact") }}</h3>
        <div class="text">{{ __("Ready to jump back in?") }}</div>
    </div>
    @include('admin.message')
    <div class="row">
        <div class="col-lg-12">
            <!-- Ls widget -->
            <div class="ls-widget">
                <div class="tabs-box">
                    <div class="widget-title">
                        <h4>{{ __("My Contact") }}</h4>

                        <div class="chosen-outer">
                            <form method="get" class="default-form form-inline" action="{{ route('user.my-contact') }}">
                                <!--Tabs Box-->
                                <div class="form-group mb-0 mr-1">
                                    <select class="form-control" name="order_by" onchange="this.form.submit()">
                                        <option value="">{{ __("Order By") }}</option>
                                        <option value="newest" @if(request()->get('order_by') == 'newest') selected @endif>{{ __("Newest") }}</option>
                                        <option value="oldest" @if(request()->get('order_by') == 'oldest') selected @endif>{{ __("Oldest") }}</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0 mr-1">
                                    <input type="text" name="s" placeholder="{{ __("Search by email, name,...") }}" value="{{ request()->get('s') }}" class="form-control">
                                </div>
                                <button type="submit" class="theme-btn btn-style-one">{{ __("Search") }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-outer">
                            <table class="default-table manage-job-table">
                                <thead>
                                <tr>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Email") }}</th>
                                    <th>{{ __("Message") }}</th>
                                    <th>{{ __("Time Sent") }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if($rows->count() > 0)
                                    @foreach($rows as $row)
                                        <tr>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->message }}</td>
                                            <td>{{ display_date($row->created_at) }}</td>
                                        </tr>
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
@endsection

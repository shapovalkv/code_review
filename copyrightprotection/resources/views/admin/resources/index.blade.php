<x-app-layout>
    <div class="row gx-3">
        <div class="col-xxl-10 col-xl-9">
            <div class="card">
                <div class="card-header border-bottom border-200 px-0">
                    <div class="d-flex justify-content-between">
                        <div class="row flex-between-center gy-2 px-x1">
                            <div class="col-auto pe-0">
                                <h5 class="mb-0">Resources</h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end px-x1">
                            <div class="d-flex align-items-center" id="table-ticket-replace-element">
                                <a class="btn btn-falcon-default btn-sm ml-2"
                                   href="{{route('admin.resources.create')}}">
                                    <svg class="svg-inline--fa fa-plus fa-w-14"
                                         data-fa-transform="shrink-3"
                                         aria-hidden="true" focusable="false" data-prefix="fas"
                                         data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 448 512" data-fa-i2svg=""
                                         style="transform-origin: 0.4375em 0.5em;">
                                        <g transform="translate(224 256)">
                                            <g transform="translate(0, 0)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                                <path fill="currentColor"
                                                      d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"
                                                      transform="translate(-224 -256)"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="d-sm-inline-blockd-xxl-inline-block ms-1"> New </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive scrollbar">
                        <table class="table table-sm mb-0 fs--1 table-view-tickets">
                            <thead class="text-800 bg-light">
                            <tr>
                                @php
                                    $sorter->setAscClass(' desc ');
                                    $sorter->setDescClass(' asc ');
                                    $sorter->setClassForSelectedHeader(' sort-link ');
                                    $sorter->setClassForNotSelectedHeader(' sort-link ');
                                @endphp
                                <th class="align-middle py-3">{!! $sorter->sortableLink('sortByTitle', 'Title') !!}</th>
                                <th class="align-middle">{!! $sorter->sortableLink('sortByCreatedDate', 'Created Date') !!}</th>
                                <th class="align-middle text-center">{!! $sorter->sortableLink('sortByStatus', 'Status') !!}</th>
                                <th class="align-middle text-end" >Action</th>
                            </tr>
                            </thead>
                            <tbody class="list" id="table-ticket-body">
                            @foreach($resources as $key => $resource)
                                <tr>
                                    <td class="align-middle py-3">
                                        <div class="d-flex align-items-center gap-2 position-relative">
                                            <h6 class="mb-0">
                                                @if($resource->deleted_at)
                                                    <span class="text-light-emphasis">{{$resource->title}}</span>
                                                @else
                                                    <a href="{{route('admin.resources.edit', $resource->slug)}}">{{$resource->title}}</a>
                                                @endif

                                            </h6>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2 position-relative">
                                            <h6 class="mb-0">{{ $resource->created_at->format('m.d.y / H:i:s') }}</h6>
                                        </div>
                                    </td>
                                    @php
                                        if ($resource->status == \App\Models\Resource::DRAFT) {
                                            $statusClass = 'badge-status-in-active';
                                            $statusName = 'Draft';
                                        } else {
                                            $statusClass = 'badge-status-success';
                                            $statusName = 'Published';
                                        }
                                    @endphp
                                    <td class="align-middle text-center">
                                        <small class="badge rounded {{$statusClass}}">{{$statusName}}</small>
                                    </td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('pages.resources.single', ['resource' => $resource]) }}" class="badge rounded badge-status-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer d-flex align-items-center justify-content-center">
                            {{ $resources->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-xl-3">
            <div class="offcanvas offcanvas-end offcanvas-filter-sidebar border-0 dark__bg-card-dark h-auto rounded-xl-3" tabindex="-1" id="ticketOffcanvas" aria-labelledby="ticketOffcanvasLabel">
                <div class="offcanvas-header d-flex flex-between-center d-xl-none bg-light">
                    <h6 class="fs-0 mb-0 fw-semi-bold">Filter</h6><button class="btn-close text-reset d-xl-none shadow-none" id="ticketOffcanvasLabel" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="card scrollbar shadow-none shadow-show-xl">
                    <div class="card-header bg-light d-none d-xl-block">
                        <h6 class="mb-0">Filter</h6>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-2"><label class="mb-1 mt-2">Status</label>
                                <select class="form-select form-select-sm" name="filterByStatus">
                                    <option value="0" @if (empty(request()->filterByStatus) || request()->filterByStatus === '') selected="selected" @endif>
                                        None
                                    </option>
                                    @foreach(\App\Models\Resource::STATUSES as $key => $status)
                                        <option value="{{$key}}" @if ($key == request()->filterByStatus) selected="selected" @endif>
                                            {{$status}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer border-top border-200 py-x1">
                        <button class="btn btn-dark w-100" id="filter_submit">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script type="module">

            $(document).ready(function () {
                $('#filter_submit').on('click', function (e) {
                    e.preventDefault();

                    var pairs = {
                        'filterByStatus': $('select[name=filterByStatus]').val(),
                        'page': 1
                    }
                    window.location.href = addParamsToCurrentUrl(pairs);
                });
            });


            function addParamsToCurrentUrl(pairs) {
                var url = window.location.href.substr(0, window.location.href.indexOf('?'));
                var vars = window.location.search.substring(1).split("&");
                var params = [];
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    params[pair[0]] = pair[1];
                }
                for (const key in pairs) {
                    params[key] = pairs[key];
                }
                var paramStr = '?';
                for (const key in params) {
                    if (key) {
                        paramStr += key + "=" + params[key] + "&";
                    }
                }
                return url + paramStr.slice(0, -1);
            }

        </script>
    @endpush
</x-app-layout>


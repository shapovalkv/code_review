<script>
    var isFluid = JSON.parse(localStorage.getItem('isFluid'));
    if (isFluid) {
        var container = document.querySelector('[data-layout]');
        container.classList.remove('container');
        container.classList.add('container-fluid');
    }
</script>
<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
            aria-controls="navbarVerticalCollapse"
            aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span
                class="toggle-line"></span></span></button>
    <a class="navbar-brand me-1 me-sm-3" href="{{ route('home') }}">
        <img class="me-2" src="{{ asset('assets/img/logos/logo1.svg') }}" alt="" width="40"/>
    </a>

    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
        <li class="nav-item px-2">
            <div class="theme-control-toggle fa-icon-wait">
                <input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle" type="checkbox" data-theme-control="theme" value="dark"/>
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
                       data-bs-placement="left" title="Switch to light theme"><span
                        class="fas fa-sun fs-0"></span></label>
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
                       data-bs-placement="left" title="Switch to dark theme"><span
                        class="fas fa-moon fs-0"></span></label>
            </div>
        </li>
        @auth
        <li class="nav-item dropdown">
            <a class="nav-link {{ auth()->user()->unreadNotifications()->count() > 0 ? 'notification-indicator' : '' }} notification-indicator-primary px-0 fa-icon-wait"
               id="navbarDropdownNotification" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false" data-hide-on-body-scroll="data-hide-on-body-scroll"><span class="fas fa-bell"
                                                                                               data-fa-transform="shrink-6"
                                                                                               style="font-size: 33px;"></span></a>
            <div
                class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end dropdown-menu-card dropdown-menu-notification dropdown-caret-bg"
                aria-labelledby="navbarDropdownNotification">
                <div class="card card-notification shadow-none">
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h6 class="card-header-title mb-0">Notifications</h6>
                            </div>
                            <div class="col-auto ps-0 ps-sm-3"><a class="card-link fw-normal" href="{{ route('account.markNotification') }}">Mark all as
                                    read</a></div>
                        </div>
                    </div>
                        <div class="scrollbar-overlay" style="max-height:19rem">
                            <div class="list-group list-group-flush fw-normal fs--1">
                                @php
                                    $unreadNotifications = \Illuminate\Support\Facades\Auth::user()->unreadNotifications;
                                    $notifications = \Illuminate\Support\Facades\Auth::user()->readNotifications;
                                @endphp
                                @if(!$unreadNotifications->isEmpty())
                                    <div class="list-group-title border-bottom">NEW</div>
                                    @foreach($unreadNotifications as $unreadNotification)
                                        <div class="list-group-item">
                                            <a class="notification notification-flush notification-unread" href="{{ route('account.notifications') }}">
                                                <div class="notification-body">
                                                    <p class="mb-1"><strong>{{ $unreadNotification->data['user_name'] }}</strong> {{ $unreadNotification->data['content'] }}</p>
                                                    <span class="notification-time"><span class="me-2" role="img" aria-label="Emoji">💬</span>{{ \Carbon\Carbon::create($unreadNotification->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </a>

                                        </div>
                                    @endforeach
                                @endif
                                @if(!$notifications->isEmpty())
                                    <div class="list-group-title border-bottom">EARLIER</div>
                                    @foreach($notifications as $notification)
                                        <div class="list-group-item">
                                            <a class="notification notification-flush notification-unread" href="{{ route('account.notifications') }}">
                                                <div class="notification-body">
                                                    <p class="mb-1"><strong>{{ $notification->data['user_name'] }}</strong> {{ $notification->data['content'] }}</p>
                                                    <span class="notification-time"><span class="me-2" role="img" aria-label="Emoji">💬</span>{{ \Carbon\Carbon::create($notification->created_at)->diffForHumans() }}</span>
                                                </div>
                                            </a>

                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-center border-top"><a class="card-link d-block"
                                                                           href="{{ route('account.notifications') }}">View
                                all</a>
                        </div>
                </div>
            </div>
        </li>
        @endauth
        <li class="nav-item dropdown"><a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button"
                                         data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-xl">
                    <img class="rounded-circle" src="{{ asset('assets/img/team/avatar.png') }}" alt=""/>
                </div>
            </a>
            <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0"
                 aria-labelledby="navbarDropdownUser">
                <div class="bg-white dark__bg-1000 rounded-2 py-2">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile &amp; account</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link class="dropdown-item" :href="route('logout')"
                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>
<div class="modal fade" id="authentication-modal" tabindex="-1" role="dialog"
     aria-labelledby="authentication-modal-label" aria-hidden="true">
    <div class="modal-dialog mt-6" role="document">
        <div class="modal-content border-0">
            <div class="modal-header px-5 position-relative modal-shape-header bg-shape">
                <div class="position-relative z-1" data-bs-theme="light">
                    <h4 class="mb-0 text-white" id="authentication-modal-label">Register</h4>
                    <p class="fs--1 mb-0 text-white">Please create your free Falcon account</p>
                </div>
                <button class="btn-close btn-close-white position-absolute top-0 end-0 mt-2 me-2"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 px-5">
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="modal-auth-name">Name</label>
                        <input class="form-control" type="text" autocomplete="on" id="modal-auth-name"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="modal-auth-email">Email address</label>
                        <input class="form-control" type="email" autocomplete="on" id="modal-auth-email"/>
                    </div>
                    <div class="row gx-2">
                        <div class="mb-3 col-sm-6">
                            <label class="form-label" for="modal-auth-password">Password</label>
                            <input class="form-control" type="password" autocomplete="on" id="modal-auth-password"/>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label class="form-label" for="modal-auth-confirm-password">Confirm Password</label>
                            <input class="form-control" type="password" autocomplete="on"
                                   id="modal-auth-confirm-password"/>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="modal-auth-register-checkbox"/>
                        <label class="form-label" for="modal-auth-register-checkbox">I accept the <a
                                href="#!">terms </a>and <a href="#!">privacy policy</a></label>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-dark d-block w-100 mt-3" type="submit" name="submit">Register
                        </button>
                    </div>
                </form>
                <div class="position-relative mt-5">
                    <hr/>
                    <div class="divider-content-center">or register with</div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="#"><span
                                class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> google</a>
                    </div>
                    <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100" href="#"><span
                                class="fab fa-facebook-square me-2" data-fa-transform="grow-8"></span> facebook</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

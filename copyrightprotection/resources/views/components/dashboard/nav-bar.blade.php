<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>

    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                    data-bs-placement="left" aria-label="Toggle Navigation"
                    data-bs-original-title="Toggle Navigation">
                <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
            </button>
        </div>
        <a class="navbar-brand" href="{{ route('home') }}">
            <div class="d-flex align-items-center py-3"><img class="me-2" src="{{  asset('assets/img/logos/logo1.svg') }}" alt="" width="40">
                <span class="font-sans-serif" style="font-size: 19px;"></span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_CUSTOMER))
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('user.dashboard')) ? 'active' : '' }}"
                           href="{{ route('user.dashboard') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('user.accounts')) ? 'active' : '' }}"
                           href="{{ route('user.accounts') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user"></span></span><span class="nav-link-text ps-1">Whitelisted accounts</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('user.keywords')) ? 'active' : '' }}"
                           href="{{ route('user.keywords') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-key"></span></span><span class="nav-link-text ps-1">Whitelisted keywords</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('user.document')) ? 'active' : '' }}"
                           href="{{ route('user.document') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-file-alt"></span></span><span class="nav-link-text ps-1">Legal documents</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('user.plans')) ? 'active' : '' }}"
                           href="{{ route('user.plans') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-newspaper"></span></span><span class="nav-link-text ps-1">Project Plan</span></div>
                        </a>
                    </li>
                <hr>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('account.notifications')) ? 'active' : '' }}"
                           href="{{ route('account.notifications') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-bell"></span></span><span class="nav-link-text ps-1">Notifications</span>
                            </div>
                        </a>
                    </li>
                @elseif(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_AGENT))
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('agent.dashboard')) ? 'active' : '' }}"
                           href="{{ route('agent.dashboard') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('agent.projects')) ? 'active' : '' }}"
                           href="{{ route('agent.projects') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-folder"></span></span><span class="nav-link-text ps-1">Projects</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('account.notifications')) ? 'active' : '' }}"
                           href="{{ route('account.notifications') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-bell"></span></span><span class="nav-link-text ps-1">Notifications</span>
                            </div>
                        </a>
                    </li>
                @elseif(\Illuminate\Support\Facades\Auth::user()->hasRole(\App\Models\User::ROLE_ADMIN))
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('admin.dashboard')) ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text ps-1">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <a class="nav-link dropdown-indicator {{ isCurrentPage(route('admin.users')) ? '' : 'collapsed' }}" href="#admin-users" role="button" data-bs-toggle="collapse"
                       {!!  isCurrentPage(route('admin.users')) ? 'aria-expanded="true"' : 'aria-expanded="false"' !!}
                       aria-controls="support-desk">
                        <div class="d-flex align-items-center"><span class="nav-link-icon">
                                <span class="fas fa-user-alt"></span></span>
                            <span class="nav-link-text ps-1">Users</span></div>
                    </a>
                    <ul class="nav collapse {{ isCurrentPage(route('admin.users')) ? 'show' : '' }}" id="admin-users" style="">
                        <li class="nav-item">
                            <a class="nav-link {{ isCurrentPage(route('admin.users', ['role' => \App\Models\User::ROLE_CUSTOMER])) ? 'active' : '' }}"
                               href="{{ route('admin.users', ['role' => \App\Models\User::ROLE_CUSTOMER]) }}">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user"></span></span><span class="nav-link-text ps-1">Customers</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isCurrentPage(route('admin.users', ['role' => \App\Models\User::ROLE_AGENT])) ? 'active' : '' }}"
                               href="{{ route('admin.users', ['role' => \App\Models\User::ROLE_AGENT]) }}">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user"></span></span><span class="nav-link-text ps-1">Agents</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ isCurrentPage(route('admin.users', ['role' => \App\Models\User::ROLE_ADMIN])) ? 'active' : '' }}"
                               href="{{ route('admin.users', ['role' => \App\Models\User::ROLE_ADMIN]) }}">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-user"></span></span><span class="nav-link-text ps-1">Admins</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('agent.projects')) ? 'active' : '' }}"
                           href="{{ route('agent.projects') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-folder"></span></span><span class="nav-link-text ps-1">Projects</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('admin.resources.index', ['role' => \App\Models\User::ROLE_ADMIN])) ? 'active' : '' }}"
                           href="{{ route('admin.resources.index') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fab fa-microblog"></span></span><span class="nav-link-text ps-1">Resources</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ isCurrentPage(route('account.notifications')) ? 'active' : '' }}"
                           href="{{ route('account.notifications') }}">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-bell"></span></span><span class="nav-link-text ps-1">Notifications</span>
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

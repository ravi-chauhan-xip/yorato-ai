<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div id="google_translate_element" class="me-3"></div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item me-1 me-xl-0">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon style-switcher-toggle hide-arrow waves-effect waves-light"
                   href="{{ route('admin.toggle-theme') }}" data-bs-toggle="tooltip" title=""
                   data-original-title="{{ Auth::user()->isDarkTheme() ? 'Switch to Light Mode': 'Switch to Dark Mode' }}">
                    <i class="mdi mdi-24px {{ Auth::user()->isDarkTheme() ? 'mdi-weather-sunny': 'mdi-weather-night' }}"></i>
                </a>
            </li>
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                   href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                   aria-expanded="false">
                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                    @if(\App\Models\SupportTicketMessage::where('messageable_type',\App\Models\Member::class)->where('is_read',0)->count())
                        <span
                            class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Notification</h6>
                            <span class="badge rounded-pill bg-label-primary">
                                    <a href="{{ route('admin.support-ticket.clear') }}"
                                       class="text-dark"><small>Clear All</small></a>
                                </span>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                            @foreach(\App\Models\SupportTicketMessage::with('member.user')->where('messageable_type',\App\Models\Member::class)->where('is_read',0)->orderBy('id','desc')->get()->unique('support_ticket_id') as $message)
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex gap-2">
                                        <div class="flex-shrink-0">
                                            <div class="avatar me-1">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <i class='bx bx-chat'></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                            <h6 class="mb-1 text-truncate">{{ ucwords($message->member->user->name) }}</h6>
                                            <small class="text-truncate text-body">{{ $message->body }}</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <small class="text-muted">{{ $message->created_at->format('d,MY') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="dropdown-menu-footer border-top p-2">
                        <a href="{{ route('admin.support-ticket.get') }}"
                           class="btn btn-primary d-flex justify-content-center">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset(asset(env('FAVICON'))) }}" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset(asset(env('FAVICON'))) }}" alt
                                             class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ settings('company_name') }}</span>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('admin.password.edit') }}">
                            <i class="mdi mdi-lock me-2"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.login.destroy') }}">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

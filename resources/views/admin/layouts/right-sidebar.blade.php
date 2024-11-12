<div class="offcanvas offcanvas-end right-bar" tabindex="-1" id="theme-settings-offcanvas">
    <div class="d-flex align-items-center w-100 p-0 offcanvas-header">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-bordered nav-justified w-100" role="tablist">
            <li class="nav-item">
                <a class="nav-link py-2" data-bs-toggle="tab" href="#chat-tab" role="tab">
                    <i class="mdi mdi-message-text d-block font-22 my-1"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2 active" data-bs-toggle="tab" href="#settings-tab" role="tab">
                    <i class="mdi mdi-cog-outline d-block font-22 my-1"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="offcanvas-body p-3 h-100" data-simplebar>
        <!-- Tab panes -->
        <div class="tab-content pt-0">
            <div class="tab-pane" id="chat-tab" role="tabpanel">

                <form class="search-bar">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="mdi mdi-magnify"></span>
                    </div>
                </form>

                <h6 class="fw-medium mt-2 text-uppercase">Group Chats</h6>

                <div>
                    <a href="javascript: void(0);" class="text-reset notification-item ps-3 mb-2 d-block">
                        <i class="mdi mdi-checkbox-blank-circle-outline me-1 text-success"></i>
                        <span class="mb-0 mt-1">App Development</span>
                    </a>

                    <a href="javascript: void(0);" class="text-reset notification-item ps-3 mb-2 d-block">
                        <i class="mdi mdi-checkbox-blank-circle-outline me-1 text-warning"></i>
                        <span class="mb-0 mt-1">Office Work</span>
                    </a>

                    <a href="javascript: void(0);" class="text-reset notification-item ps-3 mb-2 d-block">
                        <i class="mdi mdi-checkbox-blank-circle-outline me-1 text-danger"></i>
                        <span class="mb-0 mt-1">Personal Group</span>
                    </a>

                    <a href="javascript: void(0);" class="text-reset notification-item ps-3 d-block">
                        <i class="mdi mdi-checkbox-blank-circle-outline me-1"></i>
                        <span class="mb-0 mt-1">Freelance</span>
                    </a>
                </div>
            </div>

            <div class="tab-pane active" id="settings-tab" role="tabpanel">

                <div class="mt-n3">
                    <h6 class="fw-medium py-2 px-3 font-13 text-uppercase bg-light mx-n3 mt-n3 mb-3">
                        <span class="d-block py-1">Theme Settings</span>
                    </h6>
                </div>

                <div class="alert alert-warning" role="alert">
                    <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                </div>

                <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Color Scheme</h5>

                <div class="colorscheme-cardradio">
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-light" value="light">
                            <label class="form-check-label" for="layout-color-light">Light</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-bs-theme" id="layout-color-dark" value="dark">
                            <label class="form-check-label" for="layout-color-dark">Dark</label>
                        </div>
                    </div>
                </div>

                <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Content Width</h5>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="data-layout-width" id="layout-width-default" value="default">
                        <label class="form-check-label" for="layout-width-default">Fluid (Default)</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="data-layout-width" id="layout-width-boxed" value="boxed">
                        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                    </div>
                </div>

                <div id="layout-mode">
                    <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Layout Mode</h5>

                    <div class="d-flex flex-column gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-default" value="default">
                            <label class="form-check-label" for="layout-mode-default">Default</label>
                        </div>


                        <div id="layout-detached">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="data-layout-mode" id="layout-mode-detached" value="detached">
                                <label class="form-check-label" for="layout-mode-detached">Detached</label>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Topbar Color</h5>

                <div class="d-flex flex-column gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-light" value="light">
                        <label class="form-check-label" for="topbar-color-light">Light</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-dark" value="dark">
                        <label class="form-check-label" for="topbar-color-dark">Dark</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="data-topbar-color" id="topbar-color-brand" value="brand">
                        <label class="form-check-label" for="topbar-color-brand">Brand</label>
                    </div>
                </div>

                <div>
                    <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Menu Color</h5>

                    <div class="d-flex flex-column gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-light" value="light">
                            <label class="form-check-label" for="leftbar-color-light">Light</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-dark" value="dark">
                            <label class="form-check-label" for="leftbar-color-dark">Dark</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-brand" value="brand">
                            <label class="form-check-label" for="leftbar-color-brand">Brand</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-menu-color" id="leftbar-color-gradient" value="gradient">
                            <label class="form-check-label" for="leftbar-color-gradient">Gradient</label>
                        </div>
                    </div>
                </div>

                <div id="menu-icon-color">
                    <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Menu Icon Color</h5>

                    <div class="d-flex flex-column gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-two-column-color" id="twocolumn-menu-color-light" value="light">
                            <label class="form-check-label" for="twocolumn-menu-color-light">Light</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-two-column-color" id="twocolumn-menu-color-dark" value="dark">
                            <label class="form-check-label" for="twocolumn-menu-color-dark">Dark</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-two-column-color" id="twocolumn-menu-color-brand" value="brand">
                            <label class="form-check-label" for="twocolumn-menu-color-brand">Brand</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="data-two-column-color" id="twocolumn-menu-color-gradient" value="gradient">
                            <label class="form-check-label" for="twocolumn-menu-color-gradient">Gradient</label>
                        </div>
                    </div>
                </div>

{{--                <div id="sidebar-size">--}}
{{--                    <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Sidebar Size</h5>--}}

{{--                    <div class="d-flex flex-column gap-2">--}}
{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-default" value="default">--}}
{{--                            <label class="form-check-label" for="leftbar-size-default">Default</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-compact" value="compact">--}}
{{--                            <label class="form-check-label" for="leftbar-size-compact">Compact (Medium Width)</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-small" value="condensed">--}}
{{--                            <label class="form-check-label" for="leftbar-size-small">Condensed (Icon View)</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-full" value="full">--}}
{{--                            <label class="form-check-label" for="leftbar-size-full">Full Layout</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" name="data-sidenav-size" id="leftbar-size-fullscreen" value="fullscreen">--}}
{{--                            <label class="form-check-label" for="leftbar-size-fullscreen">Fullscreen Layout</label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div id="sidebar-user">--}}
{{--                    <h5 class="fw-medium font-14 mt-4 mb-2 pb-1">Sidebar User Info</h5>--}}

{{--                    <div class="form-check form-switch">--}}
{{--                        <input type="checkbox" class="form-check-input" name="data-sidebar-user" id="sidebaruser-check">--}}
{{--                        <label class="form-check-label" for="sidebaruser-check">Enable</label>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
        </div>
    </div>

    <div class="offcanvas-footer border-top py-2 px-2 text-center">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-danger w-100" id="reset-layout">Reset</button>
        </div>
    </div>
</div>

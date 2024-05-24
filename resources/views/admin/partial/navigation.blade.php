<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="" class="brand-link bg-gradient-primary text-sm">
        <img src="{{ asset('st_isidore.jpg') }}" alt="St Isidore Picture"
            class="brand-image img-circle elevation-3 bg-gradient-light"
            style="opacity: .8;width: 1.5rem;height: 1.5rem;max-height: unset">
        <span class="brand-text font-weight-light">Baptismal Scheduling</span>
    </a>
    <!-- Sidebar -->
    <div
        class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
        <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                    <!-- Sidebar user panel (optional) -->
                    <div class="clearfix"></div>
                    <!-- Sidebar Menu -->
                    <nav class="mt-4">
                        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child"
                            data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item dropdown">
                                <a href="{{ url('/admin') }}"
                                    class="nav-link nav-home {{ request()->path() === 'admin' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-header">Main</li>
                            <li class="nav-item dropdown">
                                <a href="{{ route('admin.schedules') }}"
                                    class="nav-link nav-clients {{ request()->path() === 'admin/schedules' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-invoice"></i>
                                    <p>
                                        List of Schedules
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="{{ route('admin.client-scheduled') }}"
                                    class="nav-link nav-billings
                                    {{ request()->path() === 'admin/scheduled-clients' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Client Scheduled
                                    </p>
                                </a>
                            </li>
                            {{-- <li class="nav-header">Reports</li>
                            <li class="nav-item dropdown">
                                <a href=""
                                    class="nav-link nav-reports_monthly_billing {{ request()->path() === 'reports' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-circle"></i>
                                    <p>
                                        Monthly Report
                                    </p>
                                </a>
                            </li> --}}
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    // $(document).ready(function() {
    //     var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home'; ?>';
    //     var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : ''; ?>';
    //     page = page.replace(/\//g, '_');
    //     console.log(page)

    //     if ($('.nav-link.nav-' + page).length > 0) {
    //         $('.nav-link.nav-' + page).addClass('active')
    //         if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
    //             $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
    //             $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
    //         }
    //         if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
    //             $('.nav-link.nav-' + page).parent().addClass('menu-open')
    //         }

    //     }
    //     $('.nav-link.active').addClass('bg-gradient-primary')
    // })
</script>

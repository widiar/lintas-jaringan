<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="@if (auth()->user()->hasRole('Pelanggan')) {{ route('invoice') }} @else {{ route('admin.dashboard') }} @endif"
        class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->username }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @can('view_banner')
                <li class="nav-item">
                    <a href="{{ route('admin.banner') }}"
                        class="nav-link{{ str_contains(Route::currentRouteName(), 'admin.banner') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard"></i>
                        <p>
                            Banner
                        </p>
                    </a>
                </li>
                @endcan
                @can('view_service')
                <li class="nav-item">
                    <a href="{{ route('admin.service') }}"
                        class="nav-link{{ str_contains(Route::currentRouteName(), 'admin.service') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>
                            Layanan
                        </p>
                    </a>
                </li>
                @endcan
                @can('view_paket')
                <li class="nav-item">
                    <a href="{{ route('admin.paket') }}"
                        class="nav-link{{ str_contains(Route::currentRouteName(), 'admin.paket') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-feather-alt"></i>
                        <p>
                            Paket
                        </p>
                    </a>
                </li>
                @endcan
                @can('view_invoice')
                <li class="nav-item">
                    <a href="{{ route('invoice') }}"
                        class="nav-link{{ str_contains(Route::currentRouteName(), 'invoice') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Invoice
                        </p>
                    </a>
                </li>
                @endcan
                @can('view_ticket')
                <li class="nav-item">
                    <a href="{{ route('ticket') }}"
                        class="nav-link{{ str_contains(Route::currentRouteName(), 'ticket') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-ticket-alt"></i>
                        <p>
                            Tiket
                        </p>
                    </a>
                </li>
                @endcan
                @role('admin|super admin')
                <li class="nav-hr"></li>
                <li
                    class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.user') || str_contains(Route::currentRouteName(), 'admin.roles') ? ' menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ str_contains(Route::currentRouteName(), 'admin.user') || str_contains(Route::currentRouteName(), 'admin.roles') ? ' active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('view_user')
                        <li class="nav-item">
                            <a href="{{ route('admin.user') }}"
                                class="nav-link{{ str_contains(Route::currentRouteName(), 'admin.user') ? ' active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Kelola User
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('view_role_user')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles') }}"
                                class="nav-link{{ str_contains(Route::currentRouteName(), 'admin.roles') ? ' active' : '' }}">
                                <i class="nav-icon fas fa-cog"></i>
                                {{-- <i class="fas fa-chalkboard-teacher"></i> --}}
                                <p>
                                    Kelola Roles User
                                </p>
                            </a>
                        </li>
                        @endcan
                        @role('super admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.permission.create') }}"
                                class="nav-link{{ str_contains(Route::currentRouteName(), 'admin.permission') ? ' active' : '' }}">
                                <i class="nav-icon fas fa-fingerprint"></i>
                                <p>
                                    Tambah Permissions
                                </p>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
                @endrole
                <li class="nav-hr"></li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="https://adminlte.io/docs/3.2/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-bold ml-2 text-uppercase">My App</span>
    </a>

    <div class="user-panel mt-3 pb-3 mb-3 d-flex border-bottom border-secondary">
        <div class="image">
            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=0D8ABC&color=fff' }}" 
                class="img-circle elevation-2" 
                alt="User Image"
                style="width: 33.6px; height: 33.6px; object-fit: cover;">
        </div>
        <div class="info">
            <a href="{{ route('profile') }}" class="d-block">{{ auth()->user()->nama }}</a>
            <small class="text-muted text-xs"><i class="fas fa-circle text-success mr-1"></i> Online</small>
        </div>
    </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-header text-xs text-muted">MENU UTAMA</li>
                
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('master-*') || request()->routeIs('karyawan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('master-*') || request()->routeIs('karyawan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            User Management 
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('karyawan') }}" class="nav-link {{ request()->routeIs('karyawan') ? 'active' : '' }}">
                                <i class="fas fa-user-friends nav-icon text-info"></i>
                                <p>Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="fas fa-briefcase nav-icon text-warning"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-sitemap nav-icon text-danger"></i>
                                <p>Divisi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="fas fa-user-shield nav-icon text-success"></i>
                                <p>Role</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
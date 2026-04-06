<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
    <a href="{{ route('dashboard') }}" class="brand-link border-bottom border-secondary">
        <img src="https://adminlte.io/docs/3.2/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light ml-2">HRIS <span class="font-weight-bold">SYSTEM</span></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex border-bottom border-secondary align-items-center">
            <div class="image">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->nama) . '&background=0D8ABC&color=fff' }}" 
                     class="img-circle elevation-2" 
                     alt="User Image"
                     style="width: 35px; height: 35px; object-fit: cover;">
            </div>
            <div class="info">
                <a href="{{ route('profile') }}" class="d-block font-weight-bold">{{ auth()->user()->nama }}</a>
                <span class="text-success" style="font-size: 11px;">
                    <i class="fas fa-circle nav-icon fa-xs mr-1"></i> Online
                </span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat nav-compact" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-header text-uppercase" style="letter-spacing: 1px; opacity: 0.6;">Menu Utama</li>
                
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt text-primary"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('master-*') || request()->routeIs('karyawan*') || request()->routeIs('jabatan*') || request()->routeIs('divisi*') || request()->routeIs('role*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('master-*') || request()->routeIs('karyawan*') || request()->routeIs('jabatan*') || request()->routeIs('divisi*') || request()->routeIs('role*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('karyawan') }}" class="nav-link {{ request()->routeIs('karyawan') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon text-info"></i>
                                <p>Data Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jabatan') }}" class="nav-link {{ request()->routeIs('jabatan') ? 'active' : '' }}">
                                <i class="fas fa-briefcase nav-icon text-warning"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('divisi') }}" class="nav-link {{ request()->routeIs('divisi') ? 'active' : '' }}">
                                <i class="fas fa-sitemap nav-icon text-danger"></i>
                                <p>Divisi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('role') }}" class="nav-link {{ request()->routeIs('role') ? 'active' : '' }}">
                                <i class="fas fa-user-shield nav-icon text-success"></i>
                                <p>Role & Hak Akses</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                   <a href="{{ route('areakerja') }}" class="nav-link {{ request()->routeIs('areakerja') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marker-alt text-orange"></i>
                        <p>Area Kerja</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase mt-3" style="letter-spacing: 1px; opacity: 0.6;">Operasional</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-wallet text-success"></i>
                        <p>Penggajian</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clock text-info"></i>
                        <p>Data Presensi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list text-secondary"></i>
                        <p>
                            Proyek & Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon text-xs"></i>
                                <p>Pesan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon text-xs"></i>
                                <p>Laporan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon text-xs"></i>
                                <p>Tugas</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-signature text-danger"></i>
                        <p>
                            Sistem Persetujuan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon text-xs text-info"></i>
                                <p>Cuti</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon text-xs text-warning"></i>
                                <p>Dana</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon text-xs text-success"></i>
                                <p>Lembur</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('absensi') }}" class="nav-link {{ request()->routeIs('absensi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-fingerprint text-danger"></i>
                        <p>
                            Absensi
                        </p>
                    </a>
                </li>

                <li class="nav-header text-uppercase mt-3" style="letter-spacing: 1px; opacity: 0.6;">Pengaturan</li>
                
                <li class="nav-item">
                    <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Konfigurasi Sistem</p>
                    </a>
                </li>
            </ul>
        </nav>
        </div>
    </aside>
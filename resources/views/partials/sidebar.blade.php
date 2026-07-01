<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
    <a href="{{ route('dashboard') }}" class="brand-link border-bottom border-secondary">
        <img src="https://adminlte.io/docs/3.2/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light ml-2">HRS <span class="font-weight-bold">SYSTEM</span></span>
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
                    <i class="fas fa-circle nav-icon fa-xs mr-1"></i> {{ auth()->user()->role->role }}
                </span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat nav-compact" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-header text-uppercase" style="letter-spacing: 1px; opacity: 0.6;">Menu Utama</li>
                
                {{-- DASHBOARD: Diakses oleh Semua Role --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt text-primary"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- MASTER DATA: Hanya diakses oleh Admin & HRD --}}
                @if(in_array(auth()->user()->role->role, ['admin', 'hrd']))
                <li class="nav-item {{ request()->routeIs('karyawan*') || request()->routeIs('jabatan*') || request()->routeIs('divisi*') || request()->routeIs('kriteria*') || request()->routeIs('role*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('karyawan*') || request()->routeIs('jabatan*') || request()->routeIs('divisi*') || request()->routeIs('kriteria*') || request()->routeIs('role*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('karyawan') }}" class="nav-link {{ request()->routeIs('karyawan*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon text-info"></i>
                                <p>Data Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jabatan') }}" class="nav-link {{ request()->routeIs('jabatan*') ? 'active' : '' }}">
                                <i class="fas fa-briefcase nav-icon text-warning"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('divisi') }}" class="nav-link {{ request()->routeIs('divisi*') ? 'active' : '' }}">
                                <i class="fas fa-sitemap nav-icon text-danger"></i>
                                <p>Divisi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kriteria.index') }}" class="nav-link {{ request()->routeIs('kriteria*') ? 'active' : '' }}">
                                <i class="fas fa-list-ol nav-icon text-primary"></i>
                                <p>Kriteria SAW</p>
                            </a>
                        </li>
                        {{-- Role & Hak Akses: Hanya Admin --}}
                        @if(auth()->user()->role->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('role') }}" class="nav-link {{ request()->routeIs('role*') ? 'active' : '' }}">
                                <i class="fas fa-user-shield nav-icon text-success"></i>
                                <p>Role & Hak Akses</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- AREA KERJA: Hanya diakses oleh Admin & HRD --}}
                @if(in_array(auth()->user()->role->role, ['admin', 'hrd']))
                <li class="nav-item">
                   <a href="{{ route('areakerja') }}" class="nav-link {{ request()->routeIs('areakerja*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marker-alt text-orange"></i>
                        <p>Area Kerja</p>
                    </a>
                </li>
                @endif

                <li class="nav-header text-uppercase mt-3" style="letter-spacing: 1px; opacity: 0.6;">Operasional</li>

                {{-- PENILAIAN KINERJA: Admin, HRD, Manager, Kepala Cabang --}}
                @if(in_array(auth()->user()->role->role, ['admin', 'hrd', 'manager', 'kepala cabang']))
                <li class="nav-item {{ request()->routeIs('penilaian*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('penilaian*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star text-warning"></i>
                        <p>
                            Penilaian Kinerja
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Input Penilaian: Admin, Manager, Kepala Cabang --}}
                        @if(in_array(auth()->user()->role->role, ['admin', 'manager', 'kepala cabang']))
                        <li class="nav-item">
                            <a href="{{ route('penilaian.input') }}" class="nav-link {{ request()->routeIs('penilaian.input') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-primary"></i>
                                <p>Input Penilaian</p>
                            </a>
                        </li>
                        @endif
                        
                        {{-- Hasil Akhir Ranking SAW: Admin & HRD --}}
                        @if(in_array(auth()->user()->role->role, ['admin', 'hrd']))
                        <li class="nav-item">
                            <a href="{{ route('penilaian.ranking') }}" class="nav-link {{ request()->routeIs('penilaian.ranking') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-success"></i>
                                <p>Penilaian Akhir (Ranking)</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- DATA PRESENSI: Admin, HRD, Manager, Kepala Cabang --}}
                @if(in_array(auth()->user()->role->role, ['admin', 'hrd', 'manager', 'kepala cabang']))
                <li class="nav-item">
                    <a href="{{ route('presensi.index') }}" class="nav-link {{ request()->routeIs('presensi.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock text-info"></i>
                        <p>Data Presensi</p>
                    </a>
                </li>
                @endif

                {{-- PROYEK & LAPORAN --}}
                @if(in_array(auth()->user()->role->role, ['admin', 'hrd', 'manager', 'kepala cabang', 'karyawan']))
                <li class="nav-item {{ request()->routeIs('admin.laporan*') || request()->routeIs('laporan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.laporan*') || request()->routeIs('laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list text-secondary"></i>
                        <p>
                            Proyek & Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Sisi Management (Melihat Laporan): Admin, Manager, Kepala Cabang --}}
                        @if(in_array(auth()->user()->role->role, ['admin', 'hrd','manager', 'kepala cabang']))
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-info"></i>
                                <p>Laporan Karyawan</p>
                            </a>
                        </li>
                        @endif

                        {{-- Sisi Karyawan (Kirim Laporan): Admin & Karyawan --}}
                        @if(in_array(auth()->user()->role->role, ['admin', 'karyawan']))
                        <li class="nav-item">
                            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-success"></i>
                                <p>Kirim Laporan</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- SISTEM PERSETUJUAN / PENGAJUAN --}}
                @if(in_array(auth()->user()->role->role, ['admin', 'manager', 'kepala cabang', 'karyawan', 'hrd']))
                <li class="nav-item {{ request()->routeIs('cuti*') || request()->routeIs('dana*') || request()->routeIs('admin.dana*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('cuti*') || request()->routeIs('dana*') || request()->routeIs('admin.dana*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-signature text-danger"></i>
                        <p>
                            {{ in_array(auth()->user()->role->role, ['admin', 'manager', 'kepala cabang']) ? 'Sistem Persetujuan' : 'Pengajuan Saya' }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Approve (Admin, Manager, Kepala Cabang) --}}
                        @if(in_array(auth()->user()->role->role, ['admin', 'manager', 'kepala cabang','hrd']))
                        <li class="nav-item">
                            <a href="{{ route('cuti.admin') }}" class="nav-link {{ request()->routeIs('cuti.admin') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-info"></i>
                                <p>Persetujuan Cuti</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dana.index') }}" class="nav-link {{ request()->routeIs('admin.dana.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-warning"></i>
                                <p>Persetujuan Dana</p>
                            </a>
                        </li>
                        @endif

                        {{-- Input Pengajuan (Admin, Karyawan) --}}
                        @if(in_array(auth()->user()->role->role, ['admin', 'karyawan', 'manager', 'kepala cabang',]))
                        <li class="nav-item">
                            <a href="{{ route('cuti.index') }}" class="nav-link {{ request()->routeIs('cuti.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-info"></i>
                                <p>Form Cuti Saya</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dana.index') }}" class="nav-link {{ request()->routeIs('dana.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-xs text-warning"></i>
                                <p>Form Dana Saya</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                
                {{-- ABSENSI MANUAL / ABSENSI SAYA: Admin, HRD, Manager, Kepala Cabang, Karyawan --}}
                <li class="nav-item">
                    <a href="{{ route('absensi') }}" class="nav-link {{ request()->routeIs('absensi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-fingerprint text-danger"></i>
                        <p>
                            {{ in_array(auth()->user()->role->role, ['karyawan']) ? 'Absensi Saya' : 'Absensi Manual' }}
                        </p>
                    </a>
                </li>

                <li class="nav-header text-uppercase mt-3" style="letter-spacing: 1px; opacity: 0.6;">Pengaturan</li>
                
                {{-- PROFIL SAYA: Semua Role --}}
                <li class="nav-item">
                    <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog text-light"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>

                {{-- KONFIGURASI SISTEM: Hanya Admin --}}
                @if(auth()->user()->role->role == 'admin')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tools text-secondary"></i>
                        <p>Konfigurasi Sistem</p>
                    </a>
                </li>
                @endif
                
            </ul>
        </nav>
    </div>
</aside>
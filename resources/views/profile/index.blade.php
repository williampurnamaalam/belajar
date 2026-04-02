@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold"><i class="fas fa-user-circle mr-2 text-primary"></i>Profil Saya</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('profile.edit', auth()->id()) }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Perbarui Profil
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                        <img class="profile-user-img img-fluid img-circle shadow-sm"
                            src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=007bff&color=fff' }}"
                            alt="User profile picture" 
                            style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #adb5bd;">
                        </div>

                        <h3 class="profile-username text-center font-weight-bold mb-0">{{ auth()->user()->name }}</h3>
                        <p class="text-muted text-center mb-3">{{ auth()->user()->nip ?? 'NIP Belum Diatur' }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Status Karyawan</b> 
                                <span class="float-right badge {{ auth()->user()->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Role Akses</b> <span class="float-right text-primary font-weight-bold">{{ strtoupper(auth()->user()->role) }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Divisi</b> <span class="float-right">{{ auth()->user()->divisi ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Card Kontak --}}
                <div class="card card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-address-book mr-2"></i>Kontak</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-envelope text-primary mr-2"></i> Email Resmi</strong>
                        <p class="text-muted mb-3">{{ auth()->user()->email }}</p>

                        <strong><i class="fas fa-phone-alt text-success mr-2"></i> Nomor HP / WA</strong>
                        <p class="text-muted mb-0">
                            @if(auth()->user()->no_hp)
                                <a href="https://wa.me/{{ auth()->user()->no_hp }}" target="_blank">{{ auth()->user()->no_hp }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Detail Biodata --}}
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header p-2 bg-white">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab"><i class="fas fa-info-circle mr-1"></i>Informasi Pribadi</a></li>
                            <li class="nav-item"><a class="nav-link" href="#pekerjaan" data-toggle="tab"><i class="fas fa-briefcase mr-1"></i>Data Pekerjaan</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            {{-- Tab 1: Info Pribadi --}}
                            <div class="active tab-pane" id="info">
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">NIK (KTP)</div>
                                    <div class="col-sm-8">{{ auth()->user()->nik ?? 'Belum Diisi' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Jenis Kelamin</div>
                                    <div class="col-sm-8">{{ auth()->user()->gender ?? '-' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Tempat, Tgl Lahir</div>
                                    <div class="col-sm-8">
                                        {{ auth()->user()->tanggal_lahir ? \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Usia Saat Ini</div>
                                    <div class="col-sm-8">
                                        @if(auth()->user()->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse(auth()->user()->tanggal_lahir)->age }} Tahun
                                        @else
                                            <span class="text-muted small"><em>Lengkapi tgl lahir</em></span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 2: Data Pekerjaan --}}
                            <div class="tab-pane" id="pekerjaan">
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">NIP</div>
                                    <div class="col-sm-8 font-weight-bold text-primary">{{ auth()->user()->nip ?? '-' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Departemen</div>
                                    <div class="col-sm-8">{{ auth()->user()->divisi ?? '-' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Jabatan / Role</div>
                                    <div class="col-sm-8"><span class="badge badge-light border">{{ strtoupper(auth()->user()->role) }}</span></div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Tanggal Bergabung</div>
                                    <div class="col-sm-8">{{ auth()->user()->created_at->translatedFormat('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="col-sm-4 text-muted font-weight-bold">Tanggal Bergabung</div>
                        <div class="col-sm-8">
                            {{ auth()->user()->created_at ? auth()->user()->created_at->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
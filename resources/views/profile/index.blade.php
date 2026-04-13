@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold"><i class="fas fa-user-circle mr-2 text-primary"></i>Profil: {{ $user->nama }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary shadow-sm">
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
                                src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=007bff&color=fff' }}"
                                alt="User profile picture" 
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #adb5bd;">
                        </div>

                        <h3 class="profile-username text-center font-weight-bold mb-0">{{ $user->nama }}</h3>
                        <p class="text-muted text-center mb-3">{{ $user->nip ?? 'NIP Belum Diatur' }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Role Akses</b> 
                                <span class="float-right text-primary font-weight-bold">
                                    {{ $user->role->role ?? '-' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Jabatan</b> 
                                <span class="float-right text-muted">
                                    {{ $user->jabatan->jabatan ?? '-' }}
                                </span>
                            </li>
                            <li class="list-group-item border-bottom-0">
                                <b>Divisi</b> 
                                <span class="float-right text-muted">                    
                                    {{ $user->divisi->divisi ?? '-' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-address-book mr-2"></i>Kontak</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-envelope text-primary mr-2"></i> Email Resmi</strong>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <strong><i class="fas fa-phone-alt text-success mr-2"></i> Nomor HP / WA</strong>
                        <p class="text-muted mb-0">
                            @if($user->telepon)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->telepon) }}" target="_blank">{{ $user->telepon }}</a>
                            @else
                                <span class="text-muted small"><em>Belum diatur</em></span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

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
                            <div class="active tab-pane" id="info">
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">NIK (KTP)</div>
                                    <div class="col-sm-8">{{ $user->nik ?? 'Belum Diisi' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Jenis Kelamin</div>
                                    <div class="col-sm-8">{{ ucfirst($user->gender ?? '-') }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Tanggal Lahir</div>
                                    <div class="col-sm-8">
                                        {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Usia Saat Ini</div>
                                    <div class="col-sm-8">
                                        @if($user->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} Tahun
                                        @else
                                            <span class="text-muted small"><em>Lengkapi tgl lahir</em></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="pekerjaan">
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">NIP</div>
                                    <div class="col-sm-8 font-weight-bold text-primary">{{ $user->nip ?? '-' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Departemen / Divisi</div>
                                    <div class="col-sm-8">{{ $user->divisi->divisi ?? '-' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Jabatan Resmi</div>
                                    <div class="col-sm-8">{{ $user->jabatan->jabatan ?? '-' }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Akses Sistem (Role)</div>
                                    <div class="col-sm-8"><span class="badge badge-light border">{{ strtoupper($user->role->role ?? 'USER') }}</span></div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-sm-4 text-muted font-weight-bold">Tanggal Bergabung</div>
                                    <div class="col-sm-8">{{ $user->created_at->translatedFormat('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
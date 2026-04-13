@extends('layouts.app')

@section('title', 'Detail Area Kerja')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                    </div>
                    <h3 class="profile-username text-center font-weight-bold text-uppercase">{{ $area->lokasi }}</h3>
                    <p class="text-muted text-center small mb-4">ID Area: #{{ $area->id }}</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item border-top-0">
                            <b>Total Personil</b> 
                            <span class="float-right badge badge-primary p-2">{{ $area->karyawans->count() }} Orang</span>
                        </li>
                    </ul>

                    <div class="mt-4 p-3 border rounded bg-white shadow-xs">
                        <strong class="text-dark d-block mb-2">
                            <i class="fas fa-signal mr-1 text-success"></i> IP Wi-Fi Aktif
                        </strong>
                        <div class="d-flex flex-wrap" style="gap: 5px;">
                            @if(!empty($area->ip_address))
                                @php
                                    $ips = is_array($area->ip_address) ? $area->ip_address : explode(',', $area->ip_address);
                                @endphp
                                @foreach($ips as $ip)
                                    <span class="badge badge-light border px-2 py-1">
                                        <i class="fas fa-wifi text-muted mr-1 small"></i> {{ trim($ip) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted small italic">Belum ada IP terdaftar</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 p-3 border rounded bg-light">
                        <strong class="text-dark d-block mb-2">
                            <i class="fas fa-edit mr-1 text-primary"></i> Update Daftar IP
                        </strong>
                        <form action="{{ route('areakerja.update_ip', $area->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-2">
                                <textarea name="ip_address" class="form-control form-control-sm" rows="3" 
                                          placeholder="Contoh: 192.168.1.1, 103.11.22.33">{{ is_array($area->ip_address) ? implode(', ', $area->ip_address) : $area->ip_address }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm font-weight-bold">
                                <i class="fas fa-sync-alt mr-1"></i> Simpan Perubahan
                            </button>
                        </form>
                        <small class="text-muted mt-2 d-block" style="font-size: 0.7rem; line-height: 1.2;">
                            *Gunakan koma (,) sebagai pemisah jika terdapat lebih dari satu alamat IP Wi-Fi.
                        </small>
                    </div>

                    <div class="mt-4">
                        <strong class="text-dark"><i class="fas fa-info-circle mr-1 text-primary"></i> Deskripsi Lokasi</strong>
                        <p class="text-muted small mt-2 bg-light p-3 rounded border text-justify">
                            {{ $area->detail ?? 'Tidak ada informasi tambahan untuk area ini.' }}
                        </p>
                    </div>
                    <a href="{{ route('areakerja') }}" class="btn btn-outline-secondary btn-block mt-3 shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali 
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            {{-- Tabel Personil Tetap --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h3 class="card-title font-weight-bold text-dark mb-0">
                        <i class="fas fa-users-cog mr-2 text-primary"></i>Daftar Personil Aktif
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-personil-area" class="table table-hover w-100">
                            <thead>
                                <tr class="bg-light text-uppercase small font-weight-bold">
                                    <th>Nama Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Divisi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($area->karyawans as $karyawan)
                                <tr>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-primary">{{ $karyawan->nama }}</div>
                                        <small class="text-muted">{{ $karyawan->nip ?? 'No NIP' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-info shadow-xs font-weight-normal">{{ $karyawan->jabatan->jabatan ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-muted">
                                        {{ $karyawan->divisi->divisi ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('karyawan.show', $karyawan->id) }}" class="btn btn-light btn-sm border shadow-sm" title="Lihat Profil">
                                            <i class="fas fa-user-tag text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
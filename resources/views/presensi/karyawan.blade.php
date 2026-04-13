@extends('layouts.app')
@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title text-bold">Karyawan di Area: {{ $area->nama_area }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped border" id="table-karyawan">
            <thead>
                <tr>
                    <th>NAMA KARYAWAN</th>
                    <th>JABATAN</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $k)
                <tr>
                    <td>{{ $k->nama }}</td>
                    <td>{{ $k->jabatan->jabatan }}</td>
                    <td class="text-center">
                        <a href="{{ route('presensi.detail', $k->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Data karyawan tidak ditemukan untuk area ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
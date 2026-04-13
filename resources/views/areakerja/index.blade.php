@extends('layouts.app')

@section('title', 'Data Area Kerja')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 5px solid #28a745 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle mr-3 fa-lg"></i>
                    <div>
                        <span class="font-weight-bold">Berhasil!</span> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title text-bold text-dark">Daftar Seluruh Area Kerja</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Area
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="table-area" class="table table-hover table-striped table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%" class="text-center">NO</th>
                            <th>LOKASI & GPS</th>
                            <th class="text-center">IP KANTOR</th>
                            <th class="text-center">KARYAWAN</th>
                            <th width="15%" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areakerjas as $key => $area)
                        <tr>
                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle">
                                <span class="font-weight-bold text-dark d-block">{{ $area->lokasi }}</span>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt text-danger"></i> {{ $area->latitude }}, {{ $area->longitude }} 
                                    <span class="badge badge-secondary ml-1">Radius: {{ $area->radius }}m</span>
                                </small>
                            </td>
                            <td class="text-center align-middle">
                                @if($area->ip_address)
                                    @foreach((array)$area->ip_address as $ip)
                                        <code class="d-block small">{{ $ip }}</code>
                                    @endforeach
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-pill badge-primary px-3 shadow-sm">
                                    <i class="fas fa-users mr-1"></i> {{ $area->karyawans->count() }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('areakerja.insert', $area->id) }}" class="btn btn-success btn-sm shadow-sm" title="Kelola Tim">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    <a href="{{ route('areakerja.show', $area->id) }}" class="btn btn-info btn-sm shadow-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-warning btn-sm shadow-sm" data-toggle="modal" data-target="#modal-edit-{{ $area->id }}" title="Edit">
                                        <i class="fas fa-edit text-white"></i>
                                    </button>
                                    <form action="{{ route('areakerja.destroy', $area->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus area {{ $area->lokasi }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm shadow-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT (LOOPING) --}}
@foreach($areakerjas as $area)
    <div class="modal fade" id="modal-edit-{{ $area->id }}" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Area: {{ $area->lokasi }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('areakerja.update', $area->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control" value="{{ $area->lokasi }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Radius (Meter)</label>
                                    <input type="number" name="radius" class="form-control" value="{{ $area->radius }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control" value="{{ $area->latitude }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control" value="{{ $area->longitude }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Area & GPS</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('areakerja.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Kantor Utama" required>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label class="font-weight-bold">IP Address Kantor <span class="text-info small">(Pisahkan dengan koma jika lebih dari satu)</span></label>
                                <input type="text" name="ip_address" class="form-control" placeholder="114.125.xx.xx, 180.250.xx.xx">
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="form-group">
                                <label class="font-weight-bold">Latitude</label>
                                <input type="text" name="latitude" id="lat_add" class="form-control" placeholder="-6.234567" required>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="form-group">
                                <label class="font-weight-bold">Longitude</label>
                                <input type="text" name="longitude" id="lon_add" class="form-control" placeholder="106.876543" required>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="form-group">
                                <label class="font-weight-bold">Radius (Meter)</label>
                                <input type="number" name="radius" class="form-control" value="50" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
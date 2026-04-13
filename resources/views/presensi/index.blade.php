@extends('layouts.app')
@section('content')
<div class="row">
    @foreach($areakerjas as $area)
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow-sm hover-shadow">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                </div>
                <h5 class="font-weight-bold">{{ $area->lokasi }}</h5>
                <p class="text-muted small">{{ $area->karyawans_count }} Karyawan Terdaftar</p>
                <a href="{{ route('presensi.karyawan', $area->id) }}" class="btn btn-primary btn-sm btn-block">
                    Pilih Area Kerja <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
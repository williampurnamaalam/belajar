@extends('layouts.app')

@section('title', 'Kelola Tim Area')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold text-primary">
                        <i class="fas fa-user-plus mr-2"></i>Pilih Anggota Tim
                    </h3>
                </div>
                <form action="{{ route('areakerja.storeTeam', $area->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info flex-grow-1 border-0 shadow-sm mb-4">
                            <i class="fas fa-info-circle mr-2"></i> 
                            Silakan pilih karyawan dari daftar di bawah untuk ditugaskan di <strong>{{ $area->lokasi }}</strong>.
                        </div>

                        <div class="table-responsive">
                            <table id="table-pilih-karyawan" class="table table-hover table-striped border w-100">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center">PILIH</th>
                                        <th>KARYAWAN</th>
                                        <th>POSISI/JABATAN</th>
                                        <th class="d-none">Status Sort</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($karyawans as $karyawan)
                                    @php
                                        $sudahAdaArea = $karyawan->areakerja->count() > 0;
                                        $isDiAreaIni = in_array($karyawan->id, $currentTeamIds);
                                        $sortPriority = 2;
                                        if (!$sudahAdaArea) $sortPriority = 0;
                                        elseif ($isDiAreaIni) $sortPriority = 1;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="karyawan_ids[]" 
                                                    id="chk-{{ $karyawan->id }}" value="{{ $karyawan->id }}"
                                                    {{ $isDiAreaIni ? 'checked' : '' }}>
                                                <label for="chk-{{ $karyawan->id }}" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $karyawan->profile_picture ? asset('storage/' . $karyawan->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->nama) . '&background=0D8ABC&color=fff' }}" 
                                                    class="img-circle mr-2 shadow-sm" width="35" height="35" style="object-fit: cover">
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $karyawan->nama }}</div>

                                                    @if($sudahAdaArea)
                                                        @if($isDiAreaIni)
                                                            <small class="badge badge-success-light text-success"><i class="fas fa-check mr-1"></i> Anggota Area Ini</small>
                                                        @else
                                                            <small class="text-warning font-italic">
                                                                <i class="fas fa-exclamation-circle mr-1"></i> Terplot di: 
                                                                {{ $karyawan->areakerja->pluck('lokasi')->implode(', ') }}
                                                            </small>
                                                        @endif
                                                    @else
                                                        <small class="badge badge-warning-light text-danger font-weight-bold">
                                                            <i class="fas fa-user-clock mr-1"></i> Belum Ditempatkan
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border text-dark">{{ $karyawan->divisi->divisi ?? '-' }}</span><br>
                                            <span class="text-primary small font-weight-bold text-uppercase">{{ $karyawan->jabatan->jabatan ?? '-' }}</span>
                                        </td>
                                        <td class="d-none">{{ $sortPriority }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('areakerja') }}" class="btn btn-link text-muted mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Tim
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold text-success">
                        <i class="fas fa-users mr-2"></i>Tim Saat Ini
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success">{{ count($area->karyawans) }} Personil</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($area->karyawans as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ $member->nama }}</h6>
                                    <small class="text-muted">{{ $member->jabatan->jabatan ?? 'Staff' }}</small>
                                </div>
                            </div>
                            <i class="fas fa-check-circle text-success"></i>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-5">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted small">Belum ada anggota tim ditugaskan.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-primary bg-gradient-primary">
                <div class="card-body text-white">
                    <h5 class="font-weight-bold"><i class="fas fa-map-marker-alt mr-2"></i> Detail Lokasi</h5>
                    <hr class="border-white opacity-25">
                    <p class="mb-1 small text-white-50">Nama Area:</p>
                    <p class="h5">{{ $area->lokasi }}</p>
                    <p class="mb-1 mt-3 small text-white-50">Keterangan:</p>
                    <p class="mb-0 small italic">{{ $area->detail ?? 'Tidak ada detail tambahan.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    .badge-success-light { background-color: #d4edda; color: #155724; font-size: 10px; padding: 3px 8px; border-radius: 10px; }
    .badge-warning-light { background-color: #fff3cd; color: #856404; font-size: 10px; padding: 3px 8px; border-radius: 10px; border: 1px solid #ffeeba; }
    .text-warning { color: #fd7e14 !important; font-size: 11px; font-weight: 500; }
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
    .opacity-25 { opacity: 0.25; }
    
    div.dataTables_wrapper div.dataTables_filter input {
        width: 250px; border-radius: 20px; padding: 18px; border: 1px solid #4e73df; margin-left: 10px;
    }
    .table td { vertical-align: middle !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    var table = $('#table-pilih-karyawan').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[3, "asc"], [1, "asc"]], 
        "columnDefs": [
            { "orderable": false, "targets": 0 }, 
            { "searchable": true, "targets": [1, 2] },
            { "visible": false, "targets": 3 } 
        ],
        "language": {
            "search": "CARI:",
            "searchPlaceholder": "Ketik Nama / Jabatan...",
            "lengthMenu": "Tampilkan _MENU_",
            "info": "Menampilkan _TOTAL_ karyawan",
            "paginate": {
                "previous": "<i class='fas fa-chevron-left'></i>",
                "next": "<i class='fas fa-chevron-right'></i>"
            }
        },
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    });

    $('form').on('submit', function(e) {
        var form = this;
        table.$('input[type="checkbox"]:checked').each(function() {
            if(!$.contains(document, this)){
                $(form).append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', this.name)
                        .val(this.value)
                );
            }
        });
    });
});
</script>
@endpush
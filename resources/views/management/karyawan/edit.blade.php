@extends('layouts.app')

@section('title', 'Edit Data Karyawan')

@section('content')
<div class="container-fluid">
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

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 5px solid #dc3545 !important;">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle mr-3 fa-lg"></i>
        <div>
            <span class="font-weight-bold">Gagal!</span> {{ session('error') }}
        </div>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-edit mr-2 text-warning"></i> Form Edit Profil Karyawan
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('karyawan') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-4">
                        <div class="row">
                            {{-- FOTO PROFIL --}}
                            <div class="col-md-3 text-center border-right">
                                <div class="kv-avatar">
                                    <div class="file-loading">
                                        <label class="font-weight-bold">Foto Profil</label>
                                        <div class="mb-3">
                                            <img id="preview-image" 
                                                 src="{{ $karyawan->profile_picture ? asset('storage/'.$karyawan->profile_picture) : asset('assets/img/default-avatar.png') }}" 
                                                 class="img-thumbnail rounded-circle shadow-sm" 
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="profile_picture" class="custom-file-input @error('profile_picture') is-invalid @enderror" id="input-image" accept="image/*">
                                            <label class="custom-file-label" for="input-image">Pilih file...</label>
                                            @error('profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <small class="text-muted mt-2 d-block">Format: JPG, PNG (Max. 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            {{-- INFORMASI PRIBADI --}}
                            <div class="col-md-9">
                                <h5 class="text-primary border-bottom pb-2 mb-3">Informasi Pribadi</h5>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $karyawan->nama) }}" required>
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $karyawan->email) }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    {{-- TANGGAL LAHIR (Menggunakan old() dipadukan dengan data model) --}}
                                    <div class="form-group col-md-6">
                                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}" required>
                                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                    {{-- JENIS KELAMIN (Logika selected untuk menampilkan data lama) --}}
                                    <div class="form-group col-md-6">
                                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Pria" {{ old('gender', $karyawan->gender) == 'Pria' ? 'selected' : '' }}>Pria</option>
                                            <option value="Wanita" {{ old('gender', $karyawan->gender) == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                        </select>
                                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>NIK <span class="text-danger">*</span></label>
                                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $karyawan->nik) }}" required>
                                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>NIP <span class="text-danger">*</span></label>
                                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $karyawan->nip) }}" required>
                                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Telepon <span class="text-danger">*</span></label>
                                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', $karyawan->telepon) }}" required>
                                        @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">Penempatan Kerja</h5>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Jabatan <span class="text-danger">*</span></label>
                                        <select name="jabatan_id" class="form-control select2 @error('jabatan_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatans as $j)
                                                <option value="{{ $j->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $j->id ? 'selected' : '' }}>
                                                    {{ $j->jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jabatan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Divisi <span class="text-danger">*</span></label>
                                        <select name="divisi_id" class="form-control select2 @error('divisi_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Divisi --</option>
                                            @foreach($divisis as $d)
                                                <option value="{{ $d->id }}" {{ old('divisi_id', $karyawan->divisi_id) == $d->id ? 'selected' : '' }}>
                                                    {{ $d->divisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('divisi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Hak Akses (Role) <span class="text-danger">*</span></label>
                                        <select name="role_id" class="form-control select2 @error('role_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Role --</option>
                                            @foreach($roles as $r)
                                                <option value="{{ $r->id }}" {{ old('role_id', $karyawan->role_id) == $r->id ? 'selected' : '' }}>
                                                    {{ $r->role ?? $r->nama_role }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-4 border-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Kosongkan <strong>Password</strong> jika tidak ingin mengubahnya.
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Ganti Password Baru</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-right">
                        <button type="reset" class="btn btn-secondary px-4">Reset</button>
                        <button type="submit" class="btn btn-warning px-4 text-white font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Update Data Karyawan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Preview Image saat pilih file
        $("#input-image").change(function() {
            readURL(this);
            // Update label file input
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    });
</script>
@endpush
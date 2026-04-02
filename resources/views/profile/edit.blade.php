@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@section('content')
<div class="row">
    <div class="col-md-11 mx-auto">
        {{-- Pastikan Route diarahkan ke profile.update sesuai web.php Anda --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img id="preview-image" 
                                     src="{{ auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->nama).'&background=007bff&color=fff' }}" 
                                     class="profile-user-img img-fluid img-circle shadow-sm"
                                     style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #adb5bd;"
                                     alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center font-weight-bold mt-3">{{ auth()->user()->nama }}</h3>
                            <p class="text-muted text-center small">{{ auth()->user()->email }}</p>
                            
                            <div class="form-group mt-4">
                                <label for="profile_picture" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-camera mr-1"></i> Ubah Foto Profil
                                    <input type="file" name="profile_picture" id="profile_picture" class="d-none" onchange="previewImage(this)">
                                </label>
                                @error('profile_picture') <small class="text-danger d-block text-center mt-2">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning shadow-sm mt-3">
                        <small><i class="fas fa-exclamation-triangle mr-1"></i> Edit data anda</small>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white p-3">
                            <h3 class="card-title font-weight-bold text-primary">
                                <i class="fas fa-user-edit mr-2"></i> Pengaturan Profil
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', auth()->user()->nama) }}">
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Telepon/WA <span class="text-danger">*</span></label>
                                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', auth()->user()->telepon) }}">
                                        @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NIK (KTP) <span class="text-danger">*</span></label>
                                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', auth()->user()->nik) }}">
                                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir) }}">
                                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="text-muted small text-uppercase mb-3">Informasi Pekerjaan (Read-Only)</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Email Perusahaan</label>
                                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">NIP</label>
                                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->nip }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Jenis Kelamin</label>
                                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->gender }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-muted">Divisi / Jabatan</label>
                                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->divisi }} / {{ auth()->user()->role }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <label>Ganti Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock text-warning"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Hidden fields agar request tetap valid dengan aturan 'required' di Controller jika perlu --}}
                            <input type="hidden" name="jabatan_id" value="{{ auth()->user()->jabatan_id }}">
                            <input type="hidden" name="divisi_id" value="{{ auth()->user()->divisi_id }}">
                            <input type="hidden" name="role_id" value="{{ auth()->user()->role_id }}">
                            <input type="hidden" name="gender" value="{{ auth()->user()->gender }}">
                            <input type="hidden" name="nip" value="{{ auth()->user()->nip }}">
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                        </div>
                        <div class="card-footer bg-white text-right">
                            <a href="{{ route('profile') }}" class="btn btn-link text-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
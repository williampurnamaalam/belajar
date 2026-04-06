@extends('layouts.app')

@section('title', 'Edit Data Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 font-weight-bold text-primary">
                                <i class="fas fa-user-edit mr-2"></i>Edit Informasi Karyawan
                            </h5>
                            <p class="text-muted small mb-0">Perbarui data administrasi dan akses akun karyawan.</p>
                        </div>
                        <div class="col text-right">
                            <a href="{{ url('/karyawan') }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ url('/karyawan/' . $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body bg-light-50">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6 class="text-uppercase font-weight-bold text-muted small border-bottom pb-2">
                                    <i class="fas fa-id-card mr-1"></i> Data Identitas
                                </h6>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                       value="{{ old('nama', $user->nama) }}" placeholder="Contoh: Budi Santoso">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">NIK (KTP) <span class="text-danger">*</span></label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                       value="{{ old('nik', $user->nik) }}" placeholder="16 Digit Nomor Induk Kependudukan">
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                    <option value="pria" {{ old('gender', $user->gender) == 'pria' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="wanita" {{ old('gender', $user->gender) == 'wanita' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-bold">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                                       value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx">
                                @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12 mb-3 mt-4">
                                <h6 class="text-uppercase font-weight-bold text-muted small border-bottom pb-2">
                                    <i class="fas fa-briefcase mr-1"></i> Informasi Pekerjaan & Akun
                                </h6>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">NIP <span class="text-danger">*</span></label>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                                       value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                                @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" placeholder="email@perusahaan.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-bold">Jabatan <span class="text-danger">*</span></label>
                                <select name="jabatan_id" class="form-control">
                                    @foreach($jabatans as $j)
                                        <option value="{{ $j->id }}" {{ old('jabatan_id', $user->jabatan_id) == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-bold">Divisi <span class="text-danger">*</span></label>
                                <select name="divisi_id" class="form-control">
                                    @foreach($divisis as $d)
                                        <option value="{{ $d->id }}" {{ old('divisi_id', $user->divisi_id) == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama_divisi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-bold">Role Akses <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-control">
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}" {{ old('role_id', $user->role_id) == $r->id ? 'selected' : '' }}>
                                            {{ strtoupper($r->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 form-group mt-3">
                                <div class="alert alert-light border shadow-sm p-3">
                                    <label class="font-weight-bold mb-1"><i class="fas fa-lock mr-1"></i> Keamanan Akun</label>
                                    <p class="text-muted small">Biarkan kosong jika tidak ingin mengubah password karyawan.</p>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Masukkan password baru">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3 text-right">
                        <button type="reset" class="btn btn-link text-muted mr-2">Reset Form</button>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('template.main')
@section('title', 'Edit Data')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">@yield('title')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/data">Data</a></li>
            <li class="breadcrumb-item active">@yield('title')</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="text-right">
                <a href="/data" class="btn btn-warning btn-sm"><i class="fa-solid fa-arrow-rotate-left"></i>
                  Back
                </a>
              </div>
            </div>
            <form class="needs-validation" novalidate action="{{ route('data.update', $data->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="namaLengkap">Nama Lengkap</label>
                      <input type="text" name="namaLengkap" class="form-control @error('namaLengkap') is-invalid @enderror" id="namaLengkap" placeholder="Nama Lengkap" value="{{ old('namaLengkap', $data->namaLengkap) }}" required>
                      @error('namaLengkap')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="nomorKTP">Nomor KTP</label>
                      <input type="text" name="nomorKTP" class="form-control @error('nomorKTP') is-invalid @enderror" id="nomorKTP" placeholder="Nomor KTP" value="{{ old('nomorKTP', $data->nomorKTP) }}" required>
                      @error('nomorKTP')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="tanggalLahir">Tanggal Lahir</label>
                      <input type="date" name="tanggalLahir" class="form-control @error('tanggalLahir') is-invalid @enderror" id="tanggalLahir" value="{{ old('tanggalLahir', $data->tanggalLahir) }}" required>
                      @error('tanggalLahir')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="alamat">Alamat</label>
                      <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" placeholder="Alamat" value="{{ old('alamat', $data->alamat) }}" required>
                      @error('alamat')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="foto">Foto</label>
                      <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" id="foto" accept="image/*">
                      @error('foto')
                      <span class="invalid-feedback text-danger">{{ $message }}</span>
                      @enderror
                      <small class="form-text text-muted">Max size: 200 KB</small>
                      @if ($data->foto)
                        <img src="{{ asset('storage/' . $data->foto) }}" alt="Current Photo" class="img-thumbnail mt-2" style="max-height: 150px;">
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-dark mr-1" type="reset"><i class="fa-solid fa-arrows-rotate"></i>
                  Reset</button>
                <button class="btn btn-success" type="submit"><i class="fa-solid fa-floppy-disk"></i>
                  Update</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /.content -->
      </div>
    </div>
  </div>
</div>

@endsection

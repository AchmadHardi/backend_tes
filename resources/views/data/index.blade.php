@extends('template.main')
@section('title', 'Data KTP')
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-right">
                                <a href="{{ route('data.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Tambah Data KTP
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-striped table-bordered table-hover text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nomor KTP</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Alamat</th>
                                        <th>Foto</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->namaLengkap }}</td>
                                            <td>{{ $item->nomorKTP }}</td>
                                            <td>{{ $item->tanggalLahir }}</td>
                                            <td>{{ $item->alamat }}</td>
                                            <td>
                                                @if ($item->foto)
                                                    <img src="{{ asset('uploads/foto/' . $item->foto) }}" alt="Foto" style="max-width: 100px;">
                                                @else
                                                    No Photo
                                                @endif
                                            </td>
                                            <td>
                                                <input type="checkbox" data-id="{{ $item->id }}" class="status-checkbox" {{ $item->status ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <form class="d-inline" action="{{ route('data.edit', $item->id) }}" method="GET">
                                                    <button type="submit" class="btn btn-success btn-sm mr-1">
                                                        <i class="fa-solid fa-pen"></i> Edit
                                                    </button>
                                                </form>
                                                <form class="d-inline" action="{{ route('data.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm" id="btn-delete">
                                                        <i class="fa-solid fa-trash-can"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.status-checkbox').change(function() {
        var id = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route('data.updateStatus', ['id' => '__id__']) }}'.replace('__id__', id),
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                if (response.success) {
                    alert('Status updated successfully');
                } else {
                    alert('An error occurred');
                }
            },
            error: function(xhr) {
                alert('An error occurred');
            }
        });
    });
});
</script>

@endsection

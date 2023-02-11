@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Data Layanan
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Judul</th>
                        <td>:</td>
                        <td>{{ $data->judul }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>:</td>
                        <td>{{ $data->keterangan }}</td>
                    </tr>
                    <tr>
                        <th>Icons</th>
                        <td>:</td>
                        <td>
                            <img src="{{ Storage::url('service/icon/') . $data->gambar }}" alt="" class="img-thumbnail">
                        </td>
                    </tr>

                </table>
                <a href="{{ url()->previous() }}">
                    <button class="btn btn-primary float-right">Kembali</button>
                </a>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
Detail Layanan
@endsection
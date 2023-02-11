@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Data Banner
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Judul</th>
                        <td>:</td>
                        <td>{{ $data->judul }}</td>
                    </tr>
                    <tr>
                        <th>Sub Judul</th>
                        <td>:</td>
                        <td>{{ $data->sub_judul }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>:</td>
                        <td>{{ $data->deskripsi }}</td>
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
Detail Banner
@endsection
@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Data Paket
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Judul</th>
                        <td>:</td>
                        <td>{{ $data->judul }}</td>
                    </tr>
                    <tr>
                        <th>Kecepatan</th>
                        <td>:</td>
                        <td>{{ $data->kecepatan }} Mbps</td>
                    </tr>
                    <tr>
                        <th>Fitur</th>
                        <td>:</td>
                        <td>
                            <ul class="list-group list-group-flush">
                                @foreach (explode(";", $data->fitur) as $item)
                                <li class="list-group-item">{{ $item }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>Tampil di Home</th>
                        <td>:</td>
                        <td>
                            {{ $data->is_show == 1 ? 'Tampil di Home' : 'Tidak Tampil di Home' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Aktif</th>
                        <td>:</td>
                        <td>
                            {{ $data->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
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
Detail Paket
@endsection
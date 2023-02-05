@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Data User
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Username</th>
                        <td>:</td>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>:</td>
                        <td>
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>:</td>
                        <td>{{ $user->getRoleNames()->toArray()[0] }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @if($user->getRoleNames()->toArray()[0] == 'Pelanggan')
        <div class="card mt-3">
            <div class="card-header">
                Data Pelanggan
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>:</td>
                        <td>{{ $user->pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td>{{ $user->pelanggan->alamat }}</td>
                    </tr>
                    <tr>
                        <th>No Handphone</th>
                        <td>:</td>
                        <td>
                            <a href="https://api.whatsapp.com/send?phone={{ $user->pelanggan->nohp }}">{{
                                $user->pelanggan->nohp }}</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
        <a href="{{ url()->previous() }}">
            <button class="btn btn-primary float-right">Kembali</button>
        </a>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
Detail User
@endsection
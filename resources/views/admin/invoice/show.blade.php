@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Paket</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <span class="badge badge-info"><i class="fas fa-minus"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th>Nama Paket</th>
                                <td>:</td>
                                <td>{{ $data->nama_paket }}</td>
                            </tr>
                            <tr>
                                <th>Kecepatan</th>
                                <td>:</td>
                                <td>{{ $data->kecepatan }} Mbps</td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td>:</td>
                                <td>Rp <span class="harga">{{ $data->harga }}</span></td>
                            </tr>
                            <tr>
                                <th>Tanggal Beli</th>
                                <td>:</td>
                                <td>{{ strftime("%e %B %Y", strtotime($data->created_at)) }}</td>
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
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pelanggan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <span class="badge badge-info"><i class="fas fa-minus"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tr>
                                <th>Nama Pelanggan</th>
                                <td>:</td>
                                <td>{{ $data->nama }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>:</td>
                                <td>{{ $data->alamat }} Mbps</td>
                            </tr>
                            <tr>
                                <th>No Handphone</th>
                                <td>:</td>
                                <td>{{ $data->nohp }}</td>
                            </tr>
                        </table>
                        <a href="{{ url()->previous() }}">
                            <button class="mt-3 btn btn-sm btn-primary float-right">Kembali</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
{{ $data->inv_number }} <span class="badge @if($data->status == 'PAID')badge-success @else badge-warning @endif">{{
    $data->status }}</span>
@endsection

@section('script')
<script>
    $('.harga').simpleMoneyFormat();
</script>
@endsection
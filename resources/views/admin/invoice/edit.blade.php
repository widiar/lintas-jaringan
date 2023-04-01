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
                                <td>{{ $data->alamat }}</td>
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
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Status Invoice</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <span class="badge badge-info"><i class="fas fa-minus"></i></span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="POST" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="text">Status<span class="text-danger">*</span></label>
                        <select name="status" class="form-control" id="status" required>
                            <option @if($data->status == 'PENDING') selected @endif value="PENDING">PENDING</option>
                            <option @if($data->status == 'PAID') selected @endif value="PAID">PAID</option>
                            <option @if($data->status == 'PROSES') selected @endif value="PROSES">PROSES</option>
                            <option @if($data->status == 'DONE') selected @endif value="DONE">DONE</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group tanggal" style="display: none">
                        <label for="text">Tanggal Pasang<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="date" id="tanggal_pasang" required name="tanggal_pasang"
                            class="form-control  @error('tanggal_pasang') is-invalid @enderror"
                            value="{{ old('tanggal_pasang', $data->tanggal_pasang ?? null) }}">
                        @error('tanggal_pasang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <a href="{{ route('invoice') }}">
                        <button type="button" class="btn btn-primary">Kembali</button>
                    </a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
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
    let status = $('#status').val()
    if(status == 'PROSES'){
        $('.tanggal').show()
    }
    $('#status').change(function(e){
        let val = $(this).val()
        if(val == 'PROSES'){
            $('.tanggal').show()
        }else{
            $('.tanggal').hide()
        }
    })
    let formValidation = $('#form').validate({
        rules:{
            status: 'required',
            tanggal_pasang: 'required',
        },
        submitHandler: function(form, e) {
            // e.preventDefault()
            $('button[type="submit"]').attr('disabled', 'disabled')
            $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
            // $(form).submit();

            $.ajax({
                url: '',
                method: 'POST',
                data: $(form).serialize(),
                success: (res) => {
                    if(res.status == 'success'){
                        Swal.fire(
                            "Sukses",
                            "Berhasil update status",
                            "success"
                        ).then((result) => {
                            if(result.isConfirmed) location.reload()
                        });
                    }else{
                        let element = $('#status');
                        let errorMessage = res.message;
    
                        let errors = {};
                        errors[element.attr('name')] = errorMessage;
                        formValidation.showErrors(errors)
                    }
                    $('button[type="submit"]').removeAttr('disabled')
                    $('button[type="submit"]').html(`Save`)
                },
                error: (err) => {
                    console.log(err)
                }
            })
        }
    })

</script>
@endsection
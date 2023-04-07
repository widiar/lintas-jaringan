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
                        <select name="status" class="form-control select2" id="status" required>
                            <option @if($data->status == 'PENDING') selected @endif value="PENDING">PENDING</option>
                            <option @if($data->status == 'PAID') selected @endif value="PAID">PAID</option>
                            <option @if($data->status == 'PROSES') selected @endif value="PROSES">PROSES</option>
                            <option @if($data->status == 'RESCHEDULE') selected @endif value="RESCHEDULE">RESCHEDULE
                            </option>
                            <option @if($data->status == 'DONE') selected @endif value="DONE">DONE</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Tanggal Pasang<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="date" id="tanggal_pasang" readonly name="tanggal_pasang"
                            class="form-control  @error('tanggal_pasang') is-invalid @enderror"
                            value="{{ old('tanggal_pasang', $data->tanggal_pasang ?? null) }}">
                        @error('tanggal_pasang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="tanggal_res" style="display: none">
                        <div class="form-group">
                            <label for="text">Tanggal Reschedule<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="date" id="reschedule" name="reschedule"
                                class="form-control  @error('reschedule') is-invalid @enderror"
                                value="{{ old('reschedule', $data->reschedule ?? null) }}">
                            @error('reschedule')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="text">Keterangan Reschedule<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" name="keterangan_res"
                                class="form-control  @error('keterangan_res') is-invalid @enderror"
                                value="{{ old('keterangan_res', $data->keterangan_res ?? null) }}">
                            @error('keterangan_res')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="tanggal" style="display: none">
                        <div class="form-group">
                            <label for="text">Teknisi<span class="text-danger">*</span></label>
                            <select required class="form-control teknisi" name="teknisi" @role('Teknisi') disabled
                                @endrole id="teknisi">
                                @if(!is_null($teknisi))
                                <option value="{{ $teknisi->id }}">{{ $teknisi->nama }}</option>
                                @endif
                            </select>
                            @error('teknisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="text">No Handphone Teknisi<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" readonly name="nohp"
                                class="form-control nohpteknisi @error('nohp') is-invalid @enderror"
                                value="{{ old('nohp', $teknisi->nohp ?? null) }}">
                            @error('nohp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
    $(document).ready(function(){
        $(".teknisi").select2({
            theme: "bootstrap4",
            minimumInputLength: 2,
            placeholder: 'Pilih teknisi',
            width: '100%',
            ajax: {
                url: `{{ route('list.teknisi') }}`,
                dataType: 'json',
                delay: 1000,
                data: function (params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    // console.log(data)
                    return {
                        results: data
                    };
                }
            }
        });
        $('.teknisi').on('select2:select', function (e) {
            // Ambil data yang dipilih
            let data = e.params.data;
            
            $('.nohpteknisi').val(data.nohp)
        });
    })
    $('.harga').simpleMoneyFormat();
    let status = $('#status').val()
    if(status == 'PROSES'){
        $('.tanggal').show()
    }
    if(status == 'RESCHEDULE'){
        $('.tanggal_res').show()
    }
    $('#status').change(function(e){
        let val = $(this).val()
        if(val == 'PROSES'){
            $('.tanggal').show()
        }else{
            $('.tanggal').hide()
        }
        if(val == 'RESCHEDULE'){
            $('.tanggal_res').show()
        }else{
            $('.tanggal_res').hide()
        }
    })
    let formValidation = $('#form').validate({
        rules:{
            status: 'required',
            tanggal_pasang: 'required',
            teknisi: 'required'
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
@extends('site.template.main')


@section('content')
<div class="our-services section">
    {{-- <div class="services-right-dec">
        <img src="{{ asset('assets/images/services-right-dec.png') }}" alt="">
    </div> --}}
    <div class="container">
        {{-- <div class="services-left-dec">
            <img src="{{ asset('assets/images/services-left-dec.png') }}" alt="">
        </div> --}}
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading">
                    <h2>{{ $data->judul }}</h2>
                    <span>Data Pelanggan</span>
                </div>
            </div>
        </div>
        <form action="" method="POST" class="validate-form">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="card" style="text-align: unset">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="text">Nama Lengkap<span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" required name="nama"
                                    class="form-control  @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $user->nama ?? null) }}">
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="text">Alamat<span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" required name="alamat"
                                    class="form-control  @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat', $user->alamat ?? null) }}">
                                @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="text">No Handphone<span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" required name="nohp"
                                    class="form-control  @error('nohp') is-invalid @enderror"
                                    value="{{ old('nohp', $user->nohp ?? null) }}">
                                <small class="text-info"><i>*Pastikan nomor handphone dapat dihubungi</i></small>
                                @error('nohp')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="text">Rencana Pasang<span class="text-danger">*</span></label>
                                <input autocomplete="off" type="date" required name="tanggal"
                                    class="form-control  @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', $user->tanggal ?? null) }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            {{-- <ul class="list-group list-group-flush">
                                <li class="list-group-item">Harga Paket</li>
                            </ul> --}}
                            <table class="table">
                                <tr>
                                    <td>Harga :</td>
                                    <td><b>Rp <span class="harga">{{ $data->harga }}</span></b></td>
                                </tr>
                                <tr>
                                    <td>Kecepatan :</td>
                                    <td><b>{{ $data->kecepatan }}</b></td>
                                </tr>
                                <tr>
                                    <td>Benefit :</td>
                                    <td>
                                        <b>
                                            <ul>
                                                @foreach (explode(';',$data->fitur) as $item)
                                                <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Biaya Pasang :</td>
                                    <td><b>GRATIS</b></td>
                                </tr>
                                @php
                                $ppn = round($data->harga * 0.11);
                                $total = $data->harga + $ppn;
                                @endphp
                                <tr>
                                    <td>PPN :</td>
                                    <td><b>Rp <span class="harga">{{ $ppn }}</span></b></td>
                                </tr>
                                <tr>
                                    <td>Total Harga</td>
                                    <td><b>Rp <span class="harga">{{ $total }}</span></b></td>
                                </tr>
                            </table>
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="text-center">
                                <button type="submit" class="btn btn-outline-primary btn-pesan">Pesan Sekarang</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $.validator.addMethod("indonesianPhone", function(value, element) {
            return this.optional(element) || /^0\d{10}$/.test(value);
        }, "Masukkan nomer hp yang valid");

        $(document).ready(function(){
            $('.validate-form').validate({
                rules: {
                    nama: 'required',
                    alamat: 'required',
                    nohp: {
                        required: true,
                        number: true,
                        indonesianPhone: true
                    },
                    tanggal: 'required'
                },
                submitHandler: function(form, e) {
                    e.preventDefault()
                    $('.btn-pesan').attr('disabled', 'disabled')
                    $('.btn-pesan').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
                    // $(form).submit();
                    $.ajax({
                        url: `{{ route('beli.paket') }}`,
                        data: $(form).serialize(),
                        type: 'post',
                        success: (res) => {
                            window.location.href = res.data.invoice_url;
                        },
                        error: (res) => {
                            console.log(res)
                        }
                    })
                }
            })
        })
    })
</script>
@endsection
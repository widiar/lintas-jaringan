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
                            <div class="form-group">
                                <label for="text">Kabupaten<span class="text-danger">*</span></label>
                                <select name="kabupaten" id="kabupaten" class="form-control kabupaten" required @error('kabupaten') is-invalid @enderror>
                                    <option value="{{ $kabupaten->id }}" selected>{{ $kabupaten->nama }}</option>
                                </select>
                                @error('kabupaten')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="text">Kecamatan<span class="text-danger">*</span></label>
                                <select name="kecamatan" id="kecamatan" class="form-control kecamatan" required @error('kecamatan') is-invalid @enderror>
                                    <option value="{{ $kecamatan->id }}" selected>{{ $kecamatan->nama }}</option>
                                </select>
                                @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group lurah">
                                <label for="text">Kelurahan<span class="text-danger">*</span></label>
                                <select name="kelurahan" id="kelurahan" class="form-control kelurahan" required @error('kelurahan') is-invalid @enderror>
                                    @if(!is_null($kelurahan))
                                    <option value="{{ $kelurahan->id }}" selected>{{ $kelurahan->nama }}</option>
                                    @endif
                                </select>
                                @error('kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="text">Detail Alamat<span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" required name="alamat"
                                    class="form-control  @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat', $user->alamat ?? null) }}">
                                @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="text">Latitude Longitude<span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" required id="lat" name="lat"
                                    class="form-control  @error('lat') is-invalid @enderror"
                                    value="{{ old('lat', $user->lat ?? null) }}">
                                @error('lat')
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
                            <div class="text-center">
                                <button type="button" id="btn-area" class="btn btn-primary">Cek Coverage Area</button>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="text-align: unset">
                        <div class="card-body">
                            <div id="map" style="width: 100%; height: 570px;"></div>
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
                                    <td><b>Rp <span class="harga">250000</span></b></td>
                                </tr>
                                @php
                                $ppn = round($data->harga * 0.11);
                                // $total = $data->harga + $ppn;
                                $total = $data->harga + 250000;
                                @endphp
                                <tr>
                                    <td>PPN :</td>
                                    <td><b>-</b></td>
                                </tr>
                                <tr>
                                    <td>Total Harga</td>
                                    <td><b>Rp <span class="harga">{{ $total }}</span></b></td>
                                </tr>
                            </table>
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="text-center div-pesan" tabindex="0" data-toggle="tooltip" title="Silahkan Cek Coverage Area terlebih dahulu">
                                <button type="submit" class="btn btn-outline-primary btn-pesan" disabled>Pesan Sekarang</button>
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
        $('[data-toggle="tooltip"]').tooltip()
        function initLurah(){
            $('#kelurahan').val('')
            $('#kelurahan').select2("destroy").select2({
                placeholder: 'Pilih Kecamatan Dahulu',
                width: '100%',
                theme: 'bootstrap4'
            })
        }

        $('#kabupaten').select2({
            placeholder: 'Pilih Kabupaten',
            width: '100%',
            theme: 'bootstrap4',
            ajax: {
                url: `{{ route('api.kabupaten') }}`,
                data: function (params) {
                    let query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        })
        $('#kecamatan').select2({
            placeholder: 'Pilih Kabupaten Dahulu',
            width: '100%',
            theme: 'bootstrap4'
        })

        const confCamat = () => {
            $("#kecamatan").select2("destroy").select2({
                placeholder: 'Pilih Kecamatan',
                width: '100%',
                theme: 'bootstrap4',
                ajax: {
                    url: `{{ route('api.kecamatan') }}`,
                    data: function (params) {
                        let query = {
                            search: params.term,
                            idkab: $('#kabupaten').val()
                        }
                        return query;
                    },
                    processResults: function (data) {
                        // console.log(data)
                        if(data.lurah === 0) {
                            $('.lurah').hide(200)
                            $('#keluarahan').removeAttr('required')
                        }else{
                            $('#keluarahan').attr('required', 'required')
                            $('.lurah').show(200)
                        } 
                        return {
                            results: data.select2
                        };
                    }
                }
            });
        }

        $('#kelurahan').select2({
            placeholder: 'Pilih Kecamatan Dahulu',
            width: '100%',
            theme: 'bootstrap4'
        })
        const confLurah = () => {
            $("#kelurahan").select2("destroy").select2({
                placeholder: 'Pilih Kelurahan',
                width: '100%',
                theme: 'bootstrap4',
                ajax: {
                    url: `{{ route('api.kelurahan') }}`,
                    data: function (params) {
                        let query = {
                            search: params.term,
                            idkec: $('#kecamatan').val()
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        }

        if($('#kabupaten').val() != '') confCamat();
        if($('#kecamatan').val() != '') confLurah();

        $('#kabupaten').change(function(e){
            $("#kecamatan").val('')
            confCamat();
            initLurah();
        })

        $('#kecamatan').change(function(e){
            $("#kelurahan").val('')
            confLurah()
        })

        $.validator.addMethod("indonesianPhone", function(value, element) {
            return this.optional(element) || /^0\d{10}$/.test(value);
        }, "Masukkan nomer hp yang valid");
        
        $('.validate-form').validate({
            rules: {
                nama: 'required',
                alamat: 'required',
                kabupaten: 'required',
                kecamatan: 'required',
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

        $('#kecamatan').change(function(e){
            let camat = $('#kecamatan').select2('data');
            console.log(camat)
        })

        L.mapquest.key = 'SOYicg9QxMDoJgjephqEQrVPuU91HHu9';

        let map;

        let latlngs = @JSON($pathArea);
        console.log(latlngs)
        // latlngs = JSON.parse(latlngs[0])

        // // 'map' refers to a <div> element with the ID map
        map = L.mapquest.map('map', {
            center: [-8.540851437717537, 115.32410958305795],
            layers: L.mapquest.tileLayer('map'),
            zoom: 12
        });

        let polygon = L.polygon(latlngs, {
            color: '#5897fc', 
            dashArray: true,
            opacity: 1,
            weight: 2
        }).addTo(map);
        map.fitBounds(polygon.getBounds());
        
        $('#btn-area').click(function(e){
            if($('.validate-form').valid()){
                let markLat = $('#lat').val().split(',')
                let marker = L.marker(markLat).addTo(map);
                map.flyTo(markLat,14);
                $.ajax({
                    url: `{{ route('check.area') }}`,
                    data: $('.validate-form').serialize(),
                    type: 'post',
                    success: (res) => {
                        if(res.message == 'success'){
                            $('[data-toggle="tooltip"]').tooltip('disable')
                            $('.btn-pesan').removeAttr('disabled')
                            Swal.fire(
                                "Selamat",
                                "Anda berada di wilayah jaringan kami",
                                "success"
                            );
                        }else{
                            $('[data-toggle="tooltip"]').tooltip('enable')
                            $('.btn-pesan').attr('disabled', 'disabled')
                            Swal.fire(
                                "Maaf",
                                "Untuk saat ini jaringan kami belum tersedia di tempat anda",
                                "info"
                            );
                        }
                    },
                    error: (res) => {
                        console.log(res)
                    }
                })
            }
        })
        
    })
</script>
@endsection
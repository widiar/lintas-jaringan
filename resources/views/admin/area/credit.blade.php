@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                {{-- <form action="@if(Route::current()->getName() == 'admin.area.create'){{ route('admin.area.store') }}
                    @else{{ route('admin.area.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data"> --}}
                <form action="{{ route('admin.area.store') }}" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'admin.area.edit')
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="text">Kabupaten<span class="text-danger">*</span></label>
                        <select name="kabupaten" id="kabupaten" class="form-control kabupaten" required @error('kabupaten') is-invalid @enderror>
                            <option value=""></option>
                        </select>
                        @error('kabupaten')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Kecamatan<span class="text-danger">*</span></label>
                        <select name="kecamatan" id="kecamatan" class="form-control kecamatan" required @error('kecamatan') is-invalid @enderror>
                            <option value=""></option>
                        </select>
                        @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group lurah">
                        <label for="text">Kelurahan<span class="text-danger">*</span></label>
                        <select name="kelurahan" id="kelurahan" class="form-control kelurahan" required @error('kelurahan') is-invalid @enderror>
                            <option value=""></option>
                        </select>
                        @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                    <a href="{{ route('admin.area') }}">
                        <button type="button" class="btn btn-primary float-right">Kembali</button>
                    </a>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('content-header')
@if(Route::current()->getName() == 'admin.area.edit')
Edit
@else
Tambah
@endif Area
@endsection

@section('script')
<script>
    $(document).ready(function(){

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

        $('#kelurahan').select2({
                placeholder: 'Pilih Kecamatan Dahulu',
                width: '100%',
                theme: 'bootstrap4'
            })

        $('#kabupaten').change(function(e){
            $("#kecamatan").val('')
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
            initLurah();
        })

        $('#kecamatan').change(function(e){
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
        })

        // $('#kecamatan').change(function(e){
        //     let camat = $('#kecamatan').select2('data');
        //     console.log(camat)
        // })

            
        jQuery.validator.addMethod("trim", function(value, element) {
            return this.optional(element) || /^[\S]/.test(value) && /[\S]$/.test(value);
        }, "Input tidak boleh mengandung spasi di awal atau akhir");
        $('#form').validate({
            rules: {
                kabupaten: 'required',
                kecamatan: 'required',
            },
            submitHandler: function(form, e) {
                // e.preventDefault()
                $('button[type="submit"]').attr('disabled', 'disabled')
                $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
                form.submit();
            }
        })
    })
</script>
@endsection
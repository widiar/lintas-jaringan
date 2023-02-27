@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="@if(Route::current()->getName() == 'admin.paket.create'){{ route('admin.paket.store') }}
                    @else{{ route('admin.paket.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'admin.paket.edit')
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="text">Judul<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="judul"
                            class="form-control  @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $data->judul ?? null) }}">
                        @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Kecepatan<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" min="0" required name="kecepatan"
                            class="form-control  @error('kecepatan') is-invalid @enderror"
                            value="{{ old('kecepatan', $data->kecepatan ?? null) }}">
                        @error('kecepatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Persen<span class="text-danger">*</span> <span class="badge badge-info"
                                title="Persentase kecepatan bar yang akan di tampilkan di home">?</span></label>
                        <input autocomplete="off" type="text" min="0" max="100" required name="persen"
                            class="form-control  @error('persen') is-invalid @enderror"
                            value="{{ old('persen', $data->percent ?? null) }}">
                        @error('persen')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Fitur<span class="text-danger">*</span></label>
                        <select name="fitur[]" class="form-control select2-multi @error('fitur') is-invalid @enderror"
                            required multiple="multiple">
                            @isset($data)
                            @foreach (explode(";",$data->fitur) as $item)
                            <option value="{{ $item }}" selected>{{ $item }}</option>
                            @endforeach
                            <option value=""></option>
                            @endisset
                        </select>
                        @error('fitur')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Tampil di Home<span class="text-danger">*</span></label>
                        <select name="show" class="form-control select2 @error('show') is-invalid @enderror" required>
                            <option></option>
                            <option {{ old('show', $data->is_show ?? null) == 0 ? 'selected' : '' }} value="0">Tidak
                            </option>
                            <option {{ old('show', $data->is_show ?? null) == 1 ? 'selected' : '' }} value="1">Ya
                            </option>
                        </select>
                        @error('show')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Paket Aktif<span class="text-danger">*</span></label>
                        <select name="active" class="form-control select2 @error('active') is-invalid @enderror"
                            required>
                            <option></option>
                            <option {{ old('active', $data->is_active ?? null) == 0 ? 'selected' : '' }} value="0">Tidak
                            </option>
                            <option {{ old('active', $data->is_active ?? null) == 1 ? 'selected' : '' }} value="1">Ya
                            </option>
                        </select>
                        @error('active')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="cek" value="{{ $id ?? null }}" id="cek">

                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                    <a href="{{ route('admin.paket') }}">
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
@if(Route::current()->getName() == 'admin.paket.edit')
Edit
@else
Tambah
@endif Paket
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('.select2-multi').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Please select',
            tags: true,
            tokenSeparators: [',', '', ';']
        })

        $('#form').validate({
            rules: {
                judul: 'required',
                kecepatan:{
                    required: true,
                    number: true,
                    min: 0
                },
                persen: {
                    required: true,
                    digits: true,
                    min: 0,
                    max: 100
                },
                show: {
                    required: true,
                    remote: {
                        url: `{{ route('admin.paket.check') }}`,
                        type: 'post',
                        data:{
                            id: function() {
                                return $("#cek").val();
                            }
                        }
                    }
                }
            },
            messages:{
                show:{
                    remote: 'Tampilan show hanya max 3. Data Paket yang di show sudah lebih dari 3.'
                }
            }
        })
    })
</script>
@endsection
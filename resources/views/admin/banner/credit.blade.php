@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="@if(Route::current()->getName() == 'admin.banner.create'){{ route('admin.banner.store') }}
                    @else{{ route('admin.banner.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'admin.banner.edit')
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
                        <label for="text">Sub Judul<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="sub_judul"
                            class="form-control  @error('sub_judul') is-invalid @enderror"
                            value="{{ old('sub_judul', $data->sub_judul ?? null) }}">
                        @error('sub_judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Deskripsi<span class="text-danger">*</span></label>
                        <textarea name="deskripsi" required
                            class="form-control @error('deskripsi') is-invalid @enderror" cols="30"
                            rows="10">{{ old('deskripsi', $data->deskripsi ?? null) }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                    <a href="{{ route('admin.banner') }}">
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
@if(Route::current()->getName() == 'admin.banner.edit')
Edit
@else
Tambah
@endif Banner
@endsection

@section('script')
<script>
    $(document).ready(function(){
        jQuery.validator.addMethod("trim", function(value, element) {
            return this.optional(element) || /^[\S]/.test(value) && /[\S]$/.test(value);
        }, "Input tidak boleh mengandung spasi di awal atau akhir");
        $('#form').validate({
            rules: {
                judul: 'required',
                sub_judul: 'required',
                deskripsi: {
                    required: true,
                    trim: true
                }
            },
        })
    })
</script>
@endsection
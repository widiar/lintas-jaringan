@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="@if(Route::current()->getName() == 'admin.service.create'){{ route('admin.service.store') }}
                    @else{{ route('admin.service.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'admin.service.edit')
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
                        <label for="text">Keterangan<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="keterangan"
                            class="form-control  @error('keterangan') is-invalid @enderror"
                            value="{{ old('keterangan', $data->keterangan ?? null) }}">
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Icon</label>
                        @if(Route::current()->getName() == 'admin.service.edit')
                        <img src="{{ Storage::url('service/icon/') . $data->gambar }}" alt="" class="img-thumbnail">
                        @endif
                        <div class="custom-file">
                            <input type="file" name="icon"
                                class="file custom-file-input @error('icon') is-invalid @enderror" id="icon"
                                value="{{ old('icon') }}" accept="image/*">
                            <label class="custom-file-label" for="icon">
                                <span class="d-inline-block text-truncate">Browse File</span>
                            </label>
                            @error("icon")
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                    <a href="{{ route('admin.service') }}">
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
@if(Route::current()->getName() == 'admin.service.edit')
Edit
@else
Tambah
@endif Layanan
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
                keterangan: 'required',
                deskripsi: {
                    required: true,
                    trim: true
                }
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
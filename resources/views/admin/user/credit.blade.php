@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="@if(Route::current()->getName() == 'admin.user.create'){{ route('admin.user.store') }}
                    @else{{ route('admin.user.update', $id ?? null) }}@endif" id="form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if(Route::current()->getName() == 'admin.user.edit')
                    @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="text">Username<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="username"
                            class="form-control  @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username ?? null) }}">
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Email<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="email" required name="email"
                            class="form-control  @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email ?? null) }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Role<span class="text-danger">*</span></label>
                        <select name="role" class="select2" required id="role"
                            class="form-control @error('username') is-invalid @enderror">
                            <option></option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $perm ?? null)==$role->name ? 'selected' :
                                '' }}>{{
                                ucwords($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if(Route::current()->getName() == 'admin.user.create')
                    <div class="form-group">
                        <label for="text">Password<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="password" required name="password"
                            class="form-control  @error('password') is-invalid @enderror"
                            value="{{ old('password', $user->password ?? null) }}">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    <div class="form-pelanggan" style="display: none">
                        <div class="form-group">
                            <label for="text">Nama Lengkap<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" name="nama"
                                class="form-control  @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->pelanggan->nama ?? null) }}">
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="text">Alamat<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" name="alamat"
                                class="form-control  @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat', $user->pelanggan->alamat ?? null) }}">
                            @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="text">No Handphone<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" name="nohp" id="nohp"
                                class="form-control  @error('nohp') is-invalid @enderror"
                                value="{{ old('nohp', $user->pelanggan->nohp ?? null) }}">
                            @error('nohp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-teknisi" style="display: none">
                        <div class="form-group">
                            <label for="text">Nama Lengkap<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" name="namatek"
                                class="form-control  @error('namatek') is-invalid @enderror"
                                value="{{ old('namatek', $user->teknisi->nama ?? null) }}">
                            @error('namatek')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="text">No Handphone<span class="text-danger">*</span></label>
                            <input autocomplete="off" type="text" name="nohptek" id="nohptek"
                                class="form-control  @error('nohptek') is-invalid @enderror"
                                value="{{ old('nohptek', $user->teknisi->nohp ?? null) }}">
                            @error('nohptek')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right mx-2">Save</button>
                    <a href="{{ route('admin.user') }}">
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
@if(Route::current()->getName() == 'admin.user.edit')
Edit
@else
Tambah
@endif User
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $.validator.addMethod("indonesianPhone", function(value, element) {
            return this.optional(element) || /^0\d{10,12}$/.test(value);
        }, "Masukkan nomer hp yang valid");

        $('#form').validate({
            rules: {
                name: 'required',
                role: 'required'
            },
            submitHandler: function(form, e) {
                // e.preventDefault()
                $('button[type="submit"]').attr('disabled', 'disabled')
                $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
                form.submit();
            }
        })
        let roleval = $('#role').val()
        showFormPelanggan(roleval)
        $('#role').change(function(){
            let role = $(this).val()
            showFormPelanggan(role)
        })

        function showFormPelanggan(role){
            if (role == 'Pelanggan'){
                $('.form-pelanggan').show()
                $('.form-pelanggan input').attr('required', 'required')
                $('#nohp').rules('add', {
                    required: true,
                    number: true,
                    indonesianPhone: true
                })
            }else{
                $('.form-pelanggan').hide()
                $('#nohp').rules('remove')
                $('.form-pelanggan input').removeAttr('required')
                $('.form-pelanggan input').val('')
                $('.form-pelanggan input').removeClass('is-invalid')
            }
            if(role == 'Teknisi'){
                $('.form-teknisi').show()
                $('.form-teknisi input').attr('required', 'required')
                $('#nohptek').rules('add', {
                    required: true,
                    number: true,
                    indonesianPhone: true
                })
            }else{
                $('.form-teknisi').hide()
                $('#nohptek').rules('remove')
                $('.form-teknisi input').removeAttr('required')
                $('.form-teknisi input').val('')
                $('.form-teknisi input').removeClass('is-invalid')
            }
        }
    })
</script>
@endsection
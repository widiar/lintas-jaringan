@extends('admin.index')

@section('main-content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data" id="form">
                    @csrf
                    <div class="form-group">
                        <label for="text">Username<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="text" required name="username"
                            class="form-control  @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username ?? null) }}" disabled>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Email<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="email" required name="email"
                            class="form-control  @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email ?? null) }}" disabled>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-pelanggan">
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
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#gantiPwModal">Ganti
                            Password</button>
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

<div class="modal fade" id="gantiPwModal" tabindex="-1" role="dialog" aria-labelledby="gantiPwModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('change.password') }}" method="POST" id="form-pw">
                    @csrf
                    <div class="form-group">
                        <label for="text">Password Lama<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="password" required name="passwordLama"
                            class="form-control @error('passwordLama') is-invalid @enderror" id="passwordLama"
                            value="{{ old('passwordLama') }}">
                        @error('passwordLama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Password Baru<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="password" required name="passwordBaru"
                            class="form-control @error('passwordBaru') is-invalid @enderror" id="passwordBaru"
                            value="{{ old('passwordBaru') }}">
                        @error('passwordBaru')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Password Confirm<span class="text-danger">*</span></label>
                        <input autocomplete="off" type="password" required name="passwordConfirm"
                            class="form-control  @error('passwordConfirm') is-invalid @enderror"
                            value="{{ old('passwordConfirm') }}">
                        @error('passwordConfirm')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content-header')
Edit Profile
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $.validator.addMethod("indonesianPhone", function(value, element) {
            return this.optional(element) || /^0\d{10}$/.test(value);
        }, "Masukkan nomer hp yang valid");

        let formValidate = $('#form').validate({
            rules: {
                name: 'required',
                role: 'required',
                nohp:{
                    required: true,
                    number: true,
                    indonesianPhone: true
                }
            },
            submitHandler: function(form, e) {
                // e.preventDefault()
                $('button[type="submit"]').attr('disabled', 'disabled')
                $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
                // $('.alert-success').text('')
                form.submit()
            }
        })
        $('.form-pelanggan input').attr('required', 'required')

        let formPwValidation = $('#form-pw').validate({
            rules:{
                password: 'required',
                passwordBaru: 'required',
                passwordConfirm: {
                    required: true,
                    equalTo: "#passwordBaru"
                }
            },
            messages: {
                passwordConfirm: {
                    equalTo: "Konfirmasi password harus sama dengan password baru."
                }
            },
            submitHandler: function(form, e) {
                // e.preventDefault()
                $('button[type="submit"]').attr('disabled', 'disabled')
                $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
                // $(form).submit();

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: $(form).serialize(),
                    success: (res) => {
                        if(res.status == 'success'){
                            $('#gantiPwModal').modal('hide')
                            $('#form-pw').find('input').val('')
                            Swal.fire(
                                "Sukses",
                                "Berhasil ganti password",
                                "success"
                            );
                        }if(res.status == 'password_salah'){
                            let element = $('#passwordLama');
                            let errorMessage = 'Password salah!';

                            let errors = {};
                            errors[element.attr('name')] = errorMessage;
                            formPwValidation.showErrors(errors)
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
    })
</script>
@endsection
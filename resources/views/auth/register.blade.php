<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registrasi</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/Favicon-LJN.png') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/animate/animate.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/util-login.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main-login.css') }}">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100" style="padding-top: 75px; padding-bottom: 20px">
                <form class="login100-form validate-form" method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <span class="login100-form-title">
                        Member Register
                    </span>
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @elseif(session('status'))
                    <div class="alert alert-danger">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="wrap-input100 validate-input @error('username') alert-validate @enderror"
                        @error('username') data-validate="{{ $message }}" @enderror>
                        <input autocomplete="off" class="input100" type="text" name="username" placeholder="Username"
                            value="{{ old('username') }}">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input @error('email') alert-validate @enderror" @error('email')
                        data-validate="{{ $message }}" @enderror>
                        <input autocomplete="off" class="input100" type="email" name="email" placeholder="Email"
                            value="{{ old('email') }}">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input @error('nama') alert-validate @enderror" @error('nama')
                        data-validate="{{ $message }}" @enderror>
                        <input autocomplete="off" class="input100" type="text" name="nama" placeholder="Nama Lengkap"
                            value="{{ old('nama') }}">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input @error('alamat') alert-validate @enderror" @error('alamat')
                        data-validate="{{ $message }}" @enderror>
                        <input autocomplete="off" class="input100" type="text" name="alamat" placeholder="Alamat"
                            value="{{ old('alamat') }}">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-map" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input @error('nohp') alert-validate @enderror" @error('nohp')
                        data-validate="{{ $message }}" @enderror>
                        <input autocomplete="off" class="input100" type="text" name="nohp" placeholder="No Handphone"
                            value="{{ old('nohp') }}">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-mobile" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            Daftar
                        </button>
                    </div>

                    <div class="text-center p-t-136" style="padding-top: 80px">
                        <a class="txt2" href="{{ route('login') }}">
                            <i class="fa fa-long-arrow-left m-l-5" aria-hidden="true"></i>
                            Login
                        </a>
                    </div>
                </form>
                <div class="login100-pic js-tilt" style="margin-top: 120px" data-tilt>
                    <img src="{{ asset('assets/images/img-01.png') }}" alt="IMG">
                </div>

            </div>
        </div>
    </div>




    <!--===============================================================================================-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('vendor/tilt/tilt.jquery.min.js') }}"></script>
    <script>
        $('.js-tilt').tilt({
			scale: 1.1
		})
    </script>
    <!--===============================================================================================-->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script>
        jQuery.validator.setDefaults({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                element.closest('.validate-input').attr('data-validate', error.text())
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parent().addClass('alert-validate');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().removeClass('alert-validate');
            }
        });
        $.validator.addMethod("indonesianPhone", function(value, element) {
            return this.optional(element) || /^0\d{09,13}$/.test(value);
        }, "Masukkan nomer hp yang valid");

        $(document).ready(function(){
            $('.validate-form').validate({
                rules: {
                    username: 'required',
                    nama: 'required',
                    alamat: 'required',
                    nohp: {
                        required: true,
                        number: true,
                        indonesianPhone: true
                    },
                    password: 'required',
                    email: 'required'
                },
                submitHandler: function(form) {
                    $('button[type="submit"]').attr('disabled', 'disabled')
                    $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
                    form.submit()
                    // $('.login100-form-btn').attr('disabled', 'disabled')
                    // $(form).submit();
                }
            })
        })
    </script>
</body>

</html>
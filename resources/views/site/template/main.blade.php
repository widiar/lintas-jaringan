<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <title>Lintas Jaringan</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-onix-digital.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animated.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/gauge.css') }}">

    @yield('css')

    <style>
        .text-sm {
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    @include('site.template.header')

    @yield('content')

    <div class="footer-dec">
        <img src="{{ asset('assets/images/footer-dec.png') }}" alt="">
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="about footer-item">
                        <div class="logo">
                            <a href="#"><img src="{{ asset('assets/images/logo.png') }}"
                                    alt="Onix Digital TemplateMo"></a>
                        </div>
                        <a href="#">info@company.com</a>
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-behance"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="services footer-item">
                        <h4>Services</h4>
                        <ul>
                            <li><a href="#">SEO Development</a></li>
                            <li><a href="#">Business Growth</a></li>
                            <li><a href="#">Social Media Managment</a></li>
                            <li><a href="#">Website Optimization</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="community footer-item">
                        <h4>Community</h4>
                        <ul>
                            <li><a href="#">Digital Marketing</a></li>
                            <li><a href="#">Business Ideas</a></li>
                            <li><a href="#">Website Checkup</a></li>
                            <li><a href="#">Page Speed Test</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="subscribe-newsletters footer-item">
                        <h4>Subscribe Newsletters</h4>
                        <p>Get our latest news and ideas to your inbox</p>
                        <form action="#" method="get">
                            <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email"
                                required="">
                            <button type="submit" id="form-submit" class="main-button "><i
                                    class="fa fa-paper-plane-o"></i></button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="copyright">
                        <p>Copyright © 2021 Onix Digital Co., Ltd. All Rights Reserved.
                            <br>
                            Designed by <a rel="nofollow" href="https://templatemo.com"
                                title="free CSS templates">TemplateMo</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('assets/js/animation.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.js') }}"></script>
    <script src="{{ asset('assets/js/GaugeMeter.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/simple.money.format.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script>
        jQuery.validator.setDefaults({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    </script>
    @yield('script')

    <script>
        $(".GaugeMeter").gaugeMeter();
        $('.harga').simpleMoneyFormat();
        // Acc
    $(document).on("click", ".naccs .menu div", function() {
      var numberIndex = $(this).index();

      if (!$(this).is("active")) {
          $(".naccs .menu div").removeClass("active");
          $(".naccs ul li").removeClass("active");

          $(this).addClass("active");
          $(".naccs ul").find("li:eq(" + numberIndex + ")").addClass("active");

          var listItemHeight = $(".naccs ul")
            .find("li:eq(" + numberIndex + ")")
            .innerHeight();
          $(".naccs ul").height(listItemHeight + "px");
        }
    });
    </script>
</body>

</html>
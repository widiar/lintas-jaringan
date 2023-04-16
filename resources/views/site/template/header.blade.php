<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ asset('assets/images/LJN-1.png') }}" style="width: 150px !important">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                        <li class="scroll-to-section"><a href="#services">Layanan</a></li>
                        <li class="scroll-to-section"><a href="#pricing">Harga</a></li>
                        <li class="scroll-to-section"><a href="#about">Tentang</a></li>
                        <li class="scroll-to-section"><a href="#contact">Hubungi Kami</a></li>
                        <li class="scroll-to-section"><a href="https://lintasjaringan.speedtestcustom.com/"
                                target="_blank" rel="noreferrer noopener">Speed Test</a></li>
                        @guest
                        <li class="">
                            <div class="main-red-button-hover"><a href="{{ route('login') }}">Login</a></div>
                        </li>
                        @endguest
                        @auth
                        <li class="scroll-to-section">
                            <a href="{{ route('ticket') }}">Tiket</a>
                        </li>
                        <li class="">
                            <div class="main-red-button-hover"><a href="{{ route('invoice') }}">Invoice</a></div>
                        </li>
                        @endauth
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->
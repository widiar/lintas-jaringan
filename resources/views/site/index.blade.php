@extends('site.template.main')


@section('content')
<div class="main-banner" id="top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="owl-carousel owl-banner">
                            @foreach ($banners as $banner)
                            <div class="item header-text">
                                <h6>{{ $banner->sub_judul }}</h6>
                                @php
                                $judul = explode(" ", $banner->judul, 5);
                                @endphp
                                <h2>
                                    @foreach ($judul as $i => $val)
                                    @if($i == 1) <em>{{ $val }}</em>
                                    @elseif($i == 3) <span>{{ $val }}</span>
                                    @else
                                    {{ $val }}
                                    @endif
                                    @endforeach
                                </h2>
                                <p>{{ $banner->deskripsi }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="services" class="our-services section">
    <div class="services-right-dec">
        <img src="assets/images/services-right-dec.png" alt="">
    </div>
    <div class="container">
        <div class="services-left-dec">
            <img src="assets/images/services-left-dec.png" alt="">
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading">
                    <h2>Kami Memberikan <em>Layanan</em> yang <span>Terbaik</span></h2>
                    <span>Layanan Kami</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel owl-services">
                    @foreach ($services as $service)
                    <div class="item">
                        <h4>{{ $service->judul }}</h4>
                        <div class="icon">
                            @if(env('APP_ENV') == 'local')
                            <img src="{{ Storage::url('service/icon/') . $service->gambar }}" alt="">
                            @else
                            <img src="{{ asset('assets/images/service-icon-0') . rand(1,4) . '.png' }}" alt="">
                            @endif
                        </div>
                        <p>{{ $service->keterangan }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pricing" class="pricing-tables">
    <div class="tables-left-dec">
        <img src="assets/images/tables-left-dec.png" alt="">
    </div>
    <div class="tables-right-dec">
        <img src="assets/images/tables-right-dec.png" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading">
                    <h2>Pilihan <em>paket</em> yang kami <span>sediakan</span></h2>
                    <span>Paket</span>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($pakets as $paket)
            <div class="col-lg-4">
                <div class="item first-item">
                    <h4>{{ $paket->judul }}</h4>
                    <em></em>
                    <span class="harga">{{ $paket->harga }}</span>
                    <div class="GaugeMeter" data-width="15" data-percent="{{ $paket->percent }}"
                        data-text="{{ $paket->kecepatan }}" data-label="MB/s" data-style="Arch">
                    </div>
                    <ul>
                        @foreach (explode(';', $paket->fitur) as $item)
                        <li>{{ $item }}</li>

                        @endforeach
                    </ul>
                    <div class="main-blue-button-hover mb-3">
                        @guest
                        <a href="{{ route('login') }}">Pilih Paket</a>
                        @endguest
                        @auth
                        <a href="{{ route('paket', $paket->id) }}">Pilih Paket</a>
                        @endauth
                    </div>
                    <a href="https://api.whatsapp.com/send?phone=6287750142697&text=Hallo, saya ingin bertanya mengenai {{ $paket->judul }}"
                        class="text-sm">Tanyakan</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div id="about" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center">
                <div class="left-image">
                    <img src="assets/images/about-left-image-1.png" alt="Two Girls working together">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-heading">
                    <h2><span>Tentang</span> Kami</h2>
                    <p style="margin-top: 10px; text-align: justify">Lintas Jaringan Nusantara atau LJN adalah salah
                        satu perusahaan yang
                        bergerak di bidang Internet
                        Service Provider dan One Stop IT Solution di Indonesia, yang memulai karirnya melalui kafe
                        internet. LJN menyediakan produk dan layanan untuk berbagai kebutuhan IT mulai dari pengadaan
                        perangkat, konfigurasi lokal, konfigurasi perangkat keras dan perangkat lunak, hingga
                        pemeliharaan jaringan area lokal.</p>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="fact-item">
                                <div class="count-area-content">
                                    <div class="icon">
                                        <img src="assets/images/service-icon-01.png" alt="">
                                    </div>
                                    <div class="count-digit">1000</div>
                                    <div class="count-title">Pelanggan</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fact-item">
                                <div class="count-area-content">
                                    <div class="icon">
                                        <img src="assets/images/service-icon-02.png" alt="">
                                    </div>
                                    <div class="count-digit">640</div>
                                    <div class="count-title">Websites</div>
                                    <div class="count-title"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="contact" class="contact-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="section-heading">
                    <h2><em>Masukan anda</em> anda sangat <span>berarti</span> untuk kami</h2>
                    <div id="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3945.3712271438458!2d115.33874999999996!3d-8.560255999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zOMKwMzMnMzYuOSJTIDExNcKwMjAnMTkuNSJF!5e0!3m2!1sen!2sid!4v1680444464670!5m2!1sen!2sid"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="info">
                        <span>
                            <i class="fa fa-phone"></i>
                            <a href="#" class="phone-info">
                                <elem href="https://api.whatsapp.com/send?phone=6287750142697">+62 877-5014-2697</elem>
                                <br>
                                <elem href="https://api.whatsapp.com/send?phone=6281913434057">+62 819-1343-4057</elem>
                            </a>
                            {{-- <div class="col">
                                <a href="">+62 877-5014-2697</a>
                            </div> --}}
                        </span>
                        <span>
                            <i class="fa fa-envelope"></i>
                            <div class="col">
                                <a href="mailto:globaltechonolgy2020@gmail.com">globaltechonolgy2020@gmail.com</a>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 align-self-center">
                <form id="contact" action="{{ route('saran') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <fieldset>
                                <input type="text" name="name" id="name" placeholder="Name" autocomplete="off" required>
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*"
                                    placeholder="Email anda" required>
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <input type="text" name="masukan" id="masukan" placeholder="Masukan anda">
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Kirim Masukan</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="contact-dec">
        <img src="assets/images/contact-dec.png" alt="">
    </div>
    <div class="contact-left-dec">
        <img src="assets/images/contact-left-dec.png" alt="">
    </div>
</div>
@endsection

@section('script')
<script>
    $('.phone-info')..click(function(e){
        e.preventDefault()
    })
    $('.info a elem').click(function(e){
        window.location.href = $(this).attr('href')
    })
    $('#contact').validate({
        rules: {
            name: 'required',
            email: 'required',
            masukan: 'required',
        },
        submitHandler: function(form, e) {
            e.preventDefault()
            $('button[type="submit"]').attr('disabled', 'disabled')
            $('button[type="submit"]').html(`<i class="fa fa-spinner fa-spin"></i> Processing`)
            // $('.alert-success').text('')
            // form.submit()
            // console.log(form.attr('action'))
            $.ajax({
                url: `{{ route('saran') }}`,
                method: 'POST',
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    masukan: $('#masukan').val()
                },
                success: (res) => {
                    if(res.status == 'success'){
                        Swal.fire(
                            "Sukses",
                            "Masukkan anda sudah berhasil di input",
                            "success"
                        );
                    }
                },
                complete: (res) => {
                    $('button[type="submit"]').removeAttr('disabled')
                    $('button[type="submit"]').html(`Kirim Masukan`)
                    $('#contact').find('input').val('');
                }
            })
        }
    })
</script>
@endsection
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
                    <a href="https://api.whatsapp.com/send?phone=6283189871080&text=Hallo, saya ingin bertanya mengenai {{ $paket->judul }}"
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
                    <img src="assets/images/about-left-image.png" alt="Two Girls working together">
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
                            src="https://maps.google.com/maps?q=Av.+L%C3%BAcio+Costa,+Rio+de+Janeiro+-+RJ,+Brazil&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            width="100%" height="360px" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                    </div>
                    <div class="info">
                        <span><i class="fa fa-phone"></i> <a href="#">010-020-0340<br>090-080-0760</a></span>
                        <span><i class="fa fa-envelope"></i> <a href="#">info@company.com<br>mail@company.com</a></span>
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
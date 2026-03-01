<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">

    <!--====== Title ======-->
    <title>Sistem Antrian Pengajuan Proposal</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">

    <!--====== Magnific Popup CSS ======-->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">

    <!--====== Slick CSS ======-->
    <link rel="stylesheet" href="assets/css/slick.css">

    <!--====== Font Awesome CSS ======-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!--====== Line Icons CSS ======-->
    <link rel="stylesheet" href="assets/css/LineIcons.css">

    <!--====== Animate CSS ======-->
    <link rel="stylesheet" href="assets/css/animate.css">

    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="assets/css/default.css">

    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .modal-backdrop {
            display: none;
        }

        li a {
            text-decoration: none;
        }

        .header-hero .play-btn {
            text-decoration: none;
        }
    </style>



</head>

<body>


    <!--====== PRELOADER PART START ======-->

    <div class="preloader">
        <div class="loader">
            <div class="ytp-spinner">
                <div class="ytp-spinner-container">
                    <div class="ytp-spinner-rotator">
                        <div class="ytp-spinner-left">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                        <div class="ytp-spinner-right">
                            <div class="ytp-spinner-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <header class="header-area">
        <div class="navbar-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" style="width: 100px;" href="index.html">
                                <img src="/Images/logo.svg" width="100px" alt="Logo">
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav m-auto">
                                    <li class="nav-item active">
                                        <a class="page-scroll" href="#home">Beranda</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#service">Fitur Utama</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#alur">Alur Sistem</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#about">Tentang</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#footer">Kontak</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="navbar-btn d-none d-sm-inline-block">
                                <a class="main-btn" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                            </div>

                            <!-- Modal Login -->
                            <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg border-0 rounded-4">
                                        <div class="modal-header bg-primary text-white rounded-top-4">
                                            <h5 class="modal-title fw-bold" id="loginModalLabel"> <i
                                                    class="bi bi-person-circle me-2"></i>Login Admin </h5> <button
                                                type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('login.proses') }}" method="POST"> @csrf <div
                                                class="modal-body p-4">
                                                @if ($errors->has('login'))
                                                    <div class="alert alert-danger py-2">{{ $errors->first('login') }}
                                                    </div>
                                                @endif
                                                <div class="mb-3"> <label for="username"
                                                        class="form-label fw-semibold">Username</label>
                                                    <div class="input-group"> <span class="input-group-text"><i
                                                                class="bi bi-person-fill"></i></span> <input
                                                            type="text" name="username" id="username"
                                                            class="form-control" placeholder="Masukkan username"
                                                            required> </div>
                                                </div>
                                                <div class="mb-3"> <label for="password"
                                                        class="form-label fw-semibold">Password</label>
                                                    <div class="input-group"> <span class="input-group-text"><i
                                                                class="bi bi-lock-fill"></i></span> <input
                                                            type="password" name="password" id="password"
                                                            class="form-control" placeholder="Masukkan password"
                                                            required> </div>
                                                </div>
                                                <div class="form-check mb-3"> <input type="checkbox"
                                                        class="form-check-input" id="showPassword"> <label
                                                        class="form-check-label" for="showPassword">Tampilkan
                                                        Password</label> </div>
                                            </div>
                                            <div class="modal-footer border-0"> <button type="button"
                                                    class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary px-4"> <i
                                                        class="bi bi-box-arrow-in-right me-1"></i>Masuk </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div id="home" class="header-hero bg_cover d-lg-flex align-items-center"
            style="background-image: url(assets/images/header-hero.jpg)">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-hero-content">
                            <h1 class="hero-title wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                                Sistem Antrian Pengajuan Proposal
                            </h1>
                            <p class="text wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
                                Platform digital untuk mengatur antrian pengajuan proposal di Biro Kesejahteraan Rakyat
                                Kantor Gubernur Sumatera Utara. Sistem ini membantu pemohon memilih tanggal yang
                                tersedia, memperoleh nomor antrian, serta mengetahui jadwal penyerahan tanpa menumpuk di
                                loket.
                            </p>
                            <div class="header-play wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.8s">
                                <a class="play-btn" target="_blank" href="{{ route('pemohon.form') }}">Ajukan Proposal <i class="lni-play"></i></a>
                                <a class="play-btn ms-2" target="_blank" href="{{ route('pemohon.tracking') }}">Tracking Antrian <i class="lni-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-hero-image d-flex align-items-center wow fadeInRightBig" data-wow-duration="1s"
                data-wow-delay="1.1s">
                <div class="image">
                    <img src="assets/images/hero-image.png" alt="Hero Image">
                </div>
            </div>

            <div class="header-shape">
                <img src="assets/images/shape/header-shape.png" alt="shape">
            </div>
        </div>
    </header>



    <section id="service" class="service-area pt-90 pb-50">
        <div class="container">
            <div class="service">
                <div class="row no-gutters justify-content-center">
                    <div class="col-lg-4 col-md-7">
                        <div class="single-services wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                            <div class="services-shape"></div>
                            <div class="services-separator"></div>

                            <div class="services-items services-color-1 d-sm-flex">
                                <div class="services-icon">
                                    <i class="lni-layout"></i>
                                </div>
                                <div class="services-content media-body">
                                    <h5 class="title">Pengajuan Proposal</h5>
                                    <p class="text">
                                        Pemohon mengisi data diri atau lembaga dan jenis proposal melalui form sederhana.
                                        Sistem memvalidasi ketersediaan slot sebelum menyimpan pengajuan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-7">
                        <div class="single-services services-active wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.4s">
                            <div class="services-shape"></div>
                            <div class="services-separator"></div>

                            <div class="services-items services-color-2 d-sm-flex">
                                <div class="services-icon">
                                    <i class="lni-vector"></i>
                                </div>
                                <div class="services-content media-body">
                                    <h5 class="title">Nomor Antrian Otomatis</h5>
                                    <p class="text">
                                        Setelah slot tersedia, sistem menghasilkan nomor antrian dan menetapkan
                                        jadwal penyerahan proposal. Pemohon menerima bukti antrian berisi nomor
                                        dan tanggal.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-7">
                        <div class="single-services wow fadeIn" data-wow-duration="1s" data-wow-delay="0.6s">
                            <div class="services-shape"></div>
                            <div class="services-separator"></div>

                            <div class="services-items services-color-3 d-sm-flex">
                                <div class="services-icon">
                                    <i class="lni-blackboard"></i>
                                </div>
                                <div class="services-content media-body">
                                    <h5 class="title">Monitoring dan Laporan</h5>
                                    <p class="text">
                                        Admin dapat melihat daftar antrian harian, mengubah status,
                                        serta menyusun rekap harian atau bulanan untuk evaluasi layanan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
            </div> <!-- service -->
        </div> <!-- container -->
    </section>


    <section id="alur" class="analysis-area pt-115 pb-120 bg_cover" style="background-image: url(assets/images/analysis.jpg)">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="analysis-title text-center pb-30 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay="0.2s">
                        <h3 class="title">Ringkasan Alur dan Capaian Layanan</h3>
                    </div> <!-- analysis title -->
                </div>
            </div> <!-- row -->
            <div class="analysis-counter">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="single-analysis-counter mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.3s">
                            <span class="count"><span class="counter">150</span><span class="plus">+</span></span>
                            <p class="text">Pemohon Terdaftar</p>
                        </div> <!-- single counter -->
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="single-analysis-counter mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.5s">
                            <span class="count"><span class="counter">100</span><span class="plus">%</span></span>
                            <p class="text">Slot Tanggal Terkelola</p>
                        </div> <!-- single counter -->
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="single-analysis-counter mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.7s">
                            <span class="count"><span class="counter">7</span><span class="plus">+</span></span>
                            <p class="text">Laporan Bulanan</p>
                        </div> <!-- single counter -->
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="single-analysis-counter mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.9s">
                            <span class="count"><span class="counter">80</span><span class="plus">+</span></span>
                            <p class="text">Antrian Diselesaikan</p>
                        </div> <!-- single counter -->
                    </div>
                </div> <!-- row -->
            </div> <!-- analysis counter -->
        </div> <!-- container -->
    </section>


    <section id="about" class="about-area pt-110 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="about-title text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                        <h6 class="welcome">SELAMAT DATANG</h6>
                        <h3 class="title"><span>Tentang </span> Sistem Antrian Proposal</h3>
                    </div>
                </div>
            </div> <!-- row -->
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-image mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                        <img src="assets/images/about.jpg" alt="">
                    </div> <!-- about image -->
                </div>
                <div class="col-lg-6">
                    <div class="about-content mt-50 wow fadeInRightBig" data-wow-duration="1s" data-wow-delay="0.5s">
                        <h3 class="title">Mengenal Lebih Dekat <br> Sistem Antrian Pengajuan <br> Proposal</h3>
                        <ul class="about-line">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <p class="text">
                            Sistem Antrian Pengajuan Proposal dirancang untuk Biro Kesejahteraan Rakyat
                            Kantor Gubernur Sumatera Utara. Sistem ini melibatkan dua aktor utama: pemohon
                            (masyarakat atau lembaga) dan admin atau petugas. Tujuannya mengatur antrian,
                            memberikan jadwal penyerahan, dan menghindari penumpukan pemohon. <br><br>
                            Pemohon dapat mengecek ketersediaan slot, mendapatkan nomor antrian,
                            serta memantau status tanpa login menggunakan kode. Admin mengelola kuota
                            harian, memantau daftar antrian, dan menyusun laporan.
                        </p>
                        <div class="about-counter pt-20">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="single-counter counter-color-1 mt-30 d-flex">
                                        <div class="counter-shape">
                                            <span class="shape-1"></span>
                                            <span class="shape-2"></span>
                                        </div>
                                        <div class="counter-content media-body">
                                            <span class="counter-count"><span class="counter">200</span>+</span>
                                            <p class="text">Pemohon Terdata</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="single-counter counter-color-2 mt-30 d-flex">
                                        <div class="counter-shape">
                                            <span class="shape-1"></span>
                                            <span class="shape-2"></span>
                                        </div>
                                        <div class="counter-content media-body">
                                            <span class="counter-count"><span class="counter">90</span>+</span>
                                            <p class="text">Antrian Selesai</p>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div> <!-- about counter -->
                    </div> <!-- about content -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>



    <footer id="footer" class="footer-area bg_cover" style="background-image: url(assets/images/footer-bg.jpg)">
        <div class="footer-shape">
            <img src="assets/images/shape/footer-shape.png" alt="footer shape">
        </div>
        <div class="container">
            <div class="footer-widget pt-30 pb-70">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 order-sm-1 order-lg-1">
                        <div class="footer-about pt-40 wow fadeInLeftBig" data-wow-duration="1s"
                            data-wow-delay="0.3s">
                            <a href="#">
                                <img src="/Images/logo.svg" width="100px" alt="Logo">
                            </a>
                            <p class="text">
                                Sistem Antrian Pengajuan Proposal adalah platform digital untuk mengatur kuota
                                penyerahan dan nomor antrian secara tertib dan transparan.
                            </p>
                            <p class="text">
                                Dikelola oleh Biro Kesejahteraan Rakyat Kantor Gubernur Sumatera Utara untuk
                                meningkatkan layanan kepada masyarakat dan lembaga.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 order-sm-3 order-lg-2">
                        <div class="footer-link pt-40 wow fadeInLeftBig" data-wow-duration="1s"
                            data-wow-delay="0.5s">
                            <div class="footer-title">
                                <h5 class="title">Fitur</h5>
                            </div>
                            <ul>
                                <li><a href="#">Form Pengajuan Proposal</a></li>
                                <li><a href="#">Nomor Antrian Otomatis</a></li>
                                <li><a href="#">Status Antrian</a></li>
                                <li><a href="#">Pengaturan Kuota Harian</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 order-sm-4 order-lg-3">
                        <div class="footer-link pt-40 wow fadeInLeftBig" data-wow-duration="1s"
                            data-wow-delay="0.7s">
                            <div class="footer-title">
                                <h5 class="title">Tentang Layanan</h5>
                            </div>
                            <ul>
                                <li><a href="#">Profil Biro Kesra</a></li>
                                <li><a href="#">Tujuan Sistem</a></li>
                                <li><a href="#">Alur Pengajuan</a></li>
                                <li><a href="#">Panduan Pemohon</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 order-sm-2 order-lg-4">
                        <div class="footer-contact pt-40 wow fadeInLeftBig" data-wow-duration="1s"
                            data-wow-delay="0.9s">
                            <div class="footer-title">
                                <h5 class="title">Kontak</h5>
                            </div>
                            <div class="contact pt-10">
                                <p class="text">
                                    Biro Kesejahteraan Rakyat <br>
                                    Kantor Gubernur Sumatera Utara <br>
                                    Jl. Pangeran Diponegoro No. 30, Medan.
                                </p>
                                <p class="text">kesra.sumut@sumutprov.go.id</p>
                                <p class="text">+62 61 1234 5678</p>

                                <ul class="social mt-40">
                                    <li><a href="#"><i class="lni-facebook"></i></a></li>
                                    <li><a href="#"><i class="lni-twitter"></i></a></li>
                                    <li><a href="#"><i class="lni-instagram"></i></a></li>
                                    <li><a href="#"><i class="lni-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
            </div>

            <div class="footer-copyright text-center">
                <p class="text">
                    2025 Sistem Antrian Pengajuan Proposal | Dikembangkan untuk
                    Biro Kesejahteraan Rakyat Kantor Gubernur Sumatera Utara.
                </p>
            </div>
        </div>
    </footer>




    <a href="#" class="back-to-top"><i class="lni-chevron-up"></i></a>

    <!--====== BACK TOP TOP PART ENDS ======-->

    <!--====== PART START ======-->

    <!--
    <section class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-">
                    
                </div>
            </div>
        </div>
    </section>
-->

    <!--====== PART ENDS ======-->



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <!--====== Jquery js ======-->
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.7.1.min.js"></script>

    <!--====== Bootstrap js ======-->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>


    <!--====== Counter Up js ======-->
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>

    <!--====== WOW js ======-->
    <script src="assets/js/wow.min.js"></script>

    <!--====== Scrolling Nav js ======-->
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/scrolling-nav.js"></script>

    <!--====== Scroll It js ======-->
    <script src="assets/js/scrollIt.min.js"></script>


    <!--====== Main js ======-->
    <script src="assets/js/main.js"></script>
    <!-- Script kecil untuk toggle password -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('showPassword');
            const passInput = document.getElementById('password');
            toggle.addEventListener('change', () => {
                passInput.type = toggle.checked ? 'text' : 'password';
            });
        });
    </script>

</body>

</html>

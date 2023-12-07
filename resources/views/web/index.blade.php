<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content name="keywords">
    <meta content name="description">

    <!-- Favicon -->
    <link href="favicon.html" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500&family=Jost:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="assets/web/css/all.min.css" rel="stylesheet">
    <link href="assets/web/css/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="assets/web/css/animate.min.css" rel="stylesheet">
    <link href="assets/web/css/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/web/css/bootstrap.min.css" rel="stylesheet">


    <link href="assets/web/css/style.css" rel="stylesheet">
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="51">
    <div class="bg-white p-0">

        <!-- Navbar & Hero Start -->
        <div class="position-relative p-0" id="home">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="#" class="navbar-brand p-0">
                    <h1 class="m-0">{{ env('APP_NAME') }}</h1>
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="#home" class="nav-item nav-link active">Home</a>
                        <a href="#about" class="nav-item nav-link">About</a>

                        <a href="#contact" class="nav-item nav-link">Contact</a>
                    </div>
                    <a href="tel:+91{{ $phone }}"
                        class="btn btn-primary-gradient rounded-pill py-2 px-4 ms-3 d-none d-lg-block">{{ $phone }}</a>
                </div>
            </nav>

            <div class="bg-primary hero-header">
                <div class="container px-lg-5">
                    <div class="row g-5">
                        <div style="margin-top:inherit !important" class="col-lg-8 text-center text-lg-start">
                            <h1 style="margin-top:30px !important" class="text-white mb-4 animated slideInDown">WELCOME
                                TO {{ env('APP_NAME') }}</h1>
                            <p class="text-white pb-3 animated slideInDown">Overall Customer Satisfaction 100 % </p>
                            <a href="{{ $app_link }}"
                                class="btn btn-primary-gradient py-sm-3 px-4 px-sm-5 rounded-pill me-3 animated slideInLeft">Download</a>
                            <a href="#"
                                class="btn btn-secondary-gradient py-sm-3 px-4 px-sm-5 rounded-pill animated slideInRight">{{$phone}}</a>
                        </div>
                        <div class="col-lg-4 d-flex justify-content-center justify-content-lg-end wow fadeInUp"
                            data-wow-delay="0.3s"
                            style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                            <img src="assets/web/images/ff-star-app.jpeg" alt="Ffmatka" width="300px" height="620px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->


        <!-- About Start -->
        <div class="container-xxl py-5" id="about">
            <div class="container py-5 px-lg-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <h5 class="text-primary-gradient fw-medium">About App</h5>
                        <h1 class="mb-4">#1 App For Matka</h1>
                        <p class="mb-4">The word matka is derived from a word for an earthen pot. Such pots were used
                            in the past to draw the numbers.</p>
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex">
                                    <i class="fa fa-cogs fa-2x text-primary-gradient flex-shrink-0 mt-1"></i>
                                    <div class="ms-3">
                                        <h2 class="mb-0" data-toggle="counter-up">15460</h2>
                                        <p class="text-primary-gradient mb-0">Active Install</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                                <div class="d-flex">
                                    <i class="fa fa-comments fa-2x text-secondary-gradient flex-shrink-0 mt-1"></i>
                                    <div class="ms-3">
                                        <h2 class="mb-0" data-toggle="counter-up">550</h2>
                                        <p class="text-secondary-gradient mb-0">Clients Reviews</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img class="img-fluid wow fadeInUp" data-wow-delay="0.5s"
                            src="assets/web/images/ff-star-app.jpeg" width="300px" height="620px">
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->




        <!-- Footer Start -->
        <div class="container-fluid bg-primary text-light footer wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">


                </div>
            </div>
            <div class="container px-lg-5">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            © <a class="border-bottom" href="#">{{ env('APP_NAME') }}</a>, All Right Reserved.
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-lg-square back-to-top pt-2"><i
                class="bi bi-arrow-up text-white"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script data-cfasync="false" src="assets/web/js/email-decode.min.js"></script>
    <script src="assets/web/js/jquery-3.4.1.min.js"></script>
    <script src="assets/web/js/bootstrap.bundle.min.js"></script>
    <script src="assets/web/js/wow.min.js"></script>
    <script src="assets/web/js/easing.min.js"></script>
    <script src="assets/web/js/waypoints.min.js"></script>
    <script src="assets/web/js/counterup.min.js"></script>
    <script src="assets/web/js/owl.carousel.min.js"></script>
    <script src="assets/web/js/main.js"></script>

</body>

</html>

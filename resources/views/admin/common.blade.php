<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{ $title }} | {{ $site_data->app_name }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/common/images/favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Nunito:300,400,400i,600,700">
    <link rel="stylesheet" href="{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/vendors/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/admin/vendors/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
</head>

<body>
    <div class="hk-wrapper">
        <div class="hk-pg-wrapper hk-auth-wrapper">
            <header class="d-flex justify-content-between align-items-center">
                <a class="d-flex text-white auth-brand" href="{{ url('/') }}">{{ $site_data->app_name }}</a>
            </header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-5 pa-0">
                        <div id="owl_demo_1" class="owl-carousel dots-on-item owl-theme">
                            <div class="fadeOut item auth-cover-img overlay-wrap"
                                style="background-image:url({{ asset('assets/admin/img/mock1.jpg') }});">
                                <div class="auth-cover-info py-xl-0 pt-100 pb-50">
                                    <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
                                        <h1 class="display-3 text-white mb-20">The major fortunes in America have been
                                            made in land.</h1>
                                    </div>
                                </div>
                                <div class="bg-overlay bg-trans-dark-50"></div>
                            </div>
                            <div class="fadeOut item auth-cover-img overlay-wrap"
                                style="background-image:url({{ asset('assets/admin/img/mock2.jpg') }});">
                                <div class="auth-cover-info py-xl-0 pt-100 pb-50">
                                    <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
                                        <h1 class="display-3 text-white mb-20">Ninety percent of all millionaires become
                                            so through owning real estate.</h1>
                                    </div>
                                </div>
                                <div class="bg-overlay bg-trans-dark-50"></div>
                            </div>
                        </div>
                    </div>

                    @yield('content')

                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/common/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/common/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/common/sweetalert/sweetalert.min.js') }}"></script>
    {{-- <script src="{{asset('assets/common/babel-react/babel.min.js')}}"></script>
    <script src="{{asset('assets/common/babel-react/react.production.min.js')}}"></script>
    <script src="{{asset('assets/common/babel-react/react-dom.production.min.js')}}"></script> --}}
    <script src="{{ asset('assets/admin/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dropdown-bootstrap-extended.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/admin/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/login-data.js') }}"></script>
    <script src="{{ asset('assets/admin/js/init.js') }}"></script>
</body>

</html>
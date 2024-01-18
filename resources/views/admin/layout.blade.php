<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{ $title }} | {{ $site_data->app_name }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/common/images/favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Nunito:300,400,400i,600,700">
    <link rel="stylesheet" href="{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/vendors/datatables.net-dt/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/admin/vendors/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/toastr/toastr.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
</head>

<body>
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <div class="hk-wrapper hk-vertical-nav">
        <nav class="navbar navbar-expand-xl fixed-top hk-navbar navbar-dark">
            <a id="navbar_toggle_btn" class="navbar-toggle-btn nav-link-hover" href="javascript:void(0);">
                <span class="feather-icon"><i data-feather="menu"></i></span>
            </a>
            <a class="navbar-brand font-weight-700" href="{{ url('/') }}/admin"
                style="color:#fff; font-size:x-large;">Admin Zone</a>
            <ul class="navbar-nav hk-navbar-content">
                <li class="nav-item dropdown dropdown-authentication">
                    <a class="nav-link dropdown-toggle no-caret" href="javascript:void(0);" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media">
                            <div class="media-img-wrap">
                                <div class="avatar">
                                    <img src="{{ asset('assets/admin/img/user.jpg') }}"
                                        class="avatar-img rounded-circle">
                                </div>
                                <span class="badge badge-success badge-indicator"></span>
                            </div>
                            <div class="media-body">
                                <span style="font-weight:bold;">My Account<i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="flipInX"
                        data-dropdown-out="flipOutX">
                        <a class="dropdown-item" href="{{ url('/') }}/admin/update-profile"><i
                                class="dropdown-icon zmdi zmdi-account"></i><span>My Profile</span></a>
                        <a class="dropdown-item" href="{{ url('/') }}/admin/change-password"><i
                                class="dropdown-icon zmdi zmdi-lock"></i><span>Change Password</span></a>
                        <a class="dropdown-item" href="{{ url('/') }}/admin/settings"><i
                                class="dropdown-icon zmdi zmdi-settings"></i><span>My Settings</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('/') }}/logout"><i
                                class="dropdown-icon zmdi zmdi-power"></i><span>Logout</span></a>
                    </div>
                </li>
            </ul>
        </nav>
        <nav class="hk-nav hk-nav-dark">
            <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close">
                <span class="feather-icon"><i data-feather="x"></i></span>
            </a>
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}/admin">
                                <span class="feather-icon"><i data-feather="activity"></i></span>
                                <span class="nav-link-text">My Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}/admin/game/active">
                                <span class="feather-icon"><i data-feather="play"></i></span>
                                <span class="nav-link-text">Running Games</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}/admin/game/history">
                                <span class="feather-icon"><i data-feather="clock"></i></span>
                                <span class="nav-link-text">Games History</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#manage-games">
                                <span class="feather-icon"><i data-feather="box"></i></span>
                                <span class="nav-link-text">Manage Games</span>
                            </a>
                            <ul id="manage-games" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/') }}/admin/game/create">Create
                                                New Game</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/') }}/admin/game/list/all">All
                                                Games List</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/game/list/active">Active
                                                Games</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/game/list/inactive">Inactive
                                                Games</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#manage-customers">
                                <span class="feather-icon"><i data-feather="users"></i></span>
                                <span class="nav-link-text">Manage Users</span>
                            </a>
                            <ul id="manage-customers" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/') }}/admin/user/add">Add New
                                                User</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ url('/') }}/admin/user/list/all">All
                                                Users List</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/user/list/active">Active
                                                Users</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/user/list/suspend">Suspend
                                                Users</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#manage-deposit">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text">Manage Deposits</span>
                            </a>
                            <ul id="manage-deposit" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/deposit/request">New Deposit
                                                Requests</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/deposit/approved">Approved
                                                Deposits</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/deposit/rejected">Rejected
                                                Deposits</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#manage-payout">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text">Manage Payouts</span>
                            </a>
                            <ul id="manage-payout" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/payout/request">New Payout
                                                Requests</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/payout/approved">Approved
                                                Payouts</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/payout/rejected">Rejected
                                                Payouts</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#manage-wallet">
                                <span class="feather-icon"><i data-feather="credit-card"></i></span>
                                <span class="nav-link-text">Manage Wallet</span>
                            </a>
                            <ul id="manage-wallet" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/transaction">Credit/Debit
                                                Wallet</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/wallet/history">Wallets History</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <hr class="nav-separator">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#trash-can">
                                <span class="feather-icon"><i data-feather="trash"></i></span>
                                <span class="nav-link-text">Trash Can</span>
                            </a>
                            <ul id="trash-can" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/user/list/trash">Removed
                                                Users</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ url('/') }}/admin/game/list/trash">Removed
                                                Games</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <hr class="nav-separator">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}/logout">
                                <span class="feather-icon"><i data-feather="log-out"></i></span>
                                <span class="nav-link-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <div class="hk-footer-wrap container">
            <footer class="footer text-center">
                <div class="row" style="font-weight:bold;">
                    <div class="col-md-12 col-sm-12">
                    </div>
                </div>
            </footer>
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
    <script src="{{ asset('assets/admin/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dropdown-bootstrap-extended.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dataTables-data.js') }}"></script>
    <script src="{{ asset('assets/admin/js/init.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select2-data.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('page-scripts')
</body>

</html>

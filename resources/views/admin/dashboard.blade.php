@extends('admin/layout')
@section('content')

    <div class="hk-pg-wrapper">
        <div class="container mt-xl-50 mt-sm-30 mt-15">
            <div class="hk-pg-header align-items-top"
                style="@if ($errors->any() || session('message')) {{ 'margin-bottom: 0;' }} @endif">
                <div>
                    <h2 class="hk-pg-title font-weight-600">{{ $title }}</h2>
                </div>
            </div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li class="flash-message">{{ $error }}</li>
                @endforeach
                <br>
            @endif
            @if (session('message'))
                <li class="flash-message">{{ session('message') }}</li>
                <br>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="hk-row">
                        <div class="col-lg-12">
                            <div class="hk-row">
                                <div class="col-sm-4">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-dark font-weight-500">Running Games</span>
                                            </div>
                                            <span class="d-block display-5 text-dark mb-5">
                                                {{ $running_game }}
                                            </span>
                                            <a href="{{ url('/') }}/admin/game/active">
                                                <span class="d-block badge badge-success">View</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-dark font-weight-500">Payout
                                                    Requests</span>
                                                {{-- <span class="badge badge-info badge-sm">00</span> --}}
                                            </div>
                                            <span class="d-block display-5 text-dark mb-5">
                                                {{ $payout }}
                                            </span>
                                            @if ($payout > 0)
                                                <a href="{{ url('/') }}/admin/wallet/payout/request">
                                                    <span class="d-block badge badge-info">View</span>
                                                </a>
                                            @else
                                                <a href="#">
                                                    <span class="d-block badge badge-info"
                                                        style="background-color: #c9c9c9b2
                                                    ">View</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-dark font-weight-500">Deposit
                                                    Requests</span>
                                                {{-- <span class="badge badge-success badge-sm">00</span> --}}
                                            </div>
                                            <span class="d-block display-5 text-dark mb-5">
                                                {{ $diposit }}
                                            </span>
                                            @if ($diposit > 0)
                                                <a href="{{ url('/') }}/admin/wallet/deposit/request">
                                                    <span class="d-block badge badge-success">View</span>
                                                </a>
                                            @else
                                                <a href="#">
                                                    <span class="d-block badge badge-info"
                                                        style="background-color: #c9c9c9b2
                                                ">View</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="hk-row">
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-gradient-info">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Total
                                                    Players</span>
                                            </div>
                                            <span class="d-block display-5 text-white mb-5">{{ $total_customer }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-gradient-success">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Total Wallet
                                                    Balance</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_customer_wallet_balance }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_customer_wallet_balance / $site_data->currency_value . 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-info">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Total Deposit
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_deposit_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_deposit_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-danger">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Total Withdrawal
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_withdrawal_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_withdrawal_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-info">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Today Bid
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_today_bid_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_today_bid_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-success">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Today Win
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_today_win_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_today_win_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-warning">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Today Deposit
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $today_deposit_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($today_deposit_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-success">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Today Withdrawal
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $today_withdrawal_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($today_withdrawal_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-gradient-warning">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Monthly Bid
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_monthly_bid_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_monthly_bid_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-gradient-danger">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Monthly Win
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $total_monthly_win_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($total_monthly_win_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-gradient-success">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Monthly Deposit
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $monthly_deposit_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($monthly_deposit_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="card card-sm">
                                        <div class="card-body bg-info">
                                            <div class="d-flex justify-content-between mb-5">
                                                <span class="d-block font-15 text-white font-weight-500">Monthly Withdrawal
                                                    Amount</span>
                                            </div>
                                            <span
                                                class="d-block display-5 text-white mb-5">{{ $site_data->currency_symbol }}
                                                {{ $monthly_withdrawal_amount }}

                                                @if ($site_data->currency_value > 1)
                                                    <small>
                                                        (₹
                                                        {{ round($monthly_withdrawal_amount / $site_data->currency_value, 2) }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

@extends('admin.layout')
@section('content')
    <script src="{{ url('/') }}/assets/admin/ckeditor5-classic/ckeditor.js"></script>

    <div class="hk-pg-wrapper">
        <nav class="hk-breadcrumb" aria-label="breadcrumb" style="font-weight:bold;">
            <ol class="breadcrumb breadcrumb-light bg-transparent">
                <li class="breadcrumb-item">{{ $page }}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        <div class="container">
            <div class="hk-pg-header">
                <h4 class="hk-pg-title">
                    <span class="pg-title-icon">
                        <span class="feather-icon">
                            <i data-feather="box"></i>
                        </span>
                    </span>
                    {{ $title }}
                </h4>
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
                    <section class="hk-sec-wrapper">
                        {{-- <h3 class="pb-3">Application Settings</h3> --}}
                        <div class="row">
                            <div class="col-sm">
                                <form action="" method="post" onsubmit="return clickBtn()">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Single Win Value<strong class="text-danger">*</strong></label>
                                                <input type="text" placeholder="Single Win value" class="form-control"
                                                    name="single_win_value"
                                                    value="{{ old('single_win_value', $game_result ? $game_result->single_win_value : '') }}"
                                                    maxlength="1" required oninput="change()">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Patti Win Value<strong class="text-danger">*</strong></label>
                                                <input type="text" placeholder="Patti Win Value" class="form-control"
                                                    name="patti_win_value"
                                                    value="{{ old('patti_win_value', $game_result ? $game_result->patti_win_value : '') }}"
                                                    maxlength="3" required oninput="change()">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="btn"
                                        disabled="true">Process</button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <section class="hk-sec-wrapper">
                        <h6 class="pb-2">Single Data: </h6>
                        <div class="mb-3">
                            <p><Strong>Total Amount: </Strong>{{ $site_data->currency_symbol }} {{ $single_total }}</p>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="table-wrap table-format">
                                    <table class="table table-hover table-responsive display">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>Bid Number</th>
                                                <th>Total Bid</th>
                                                <th>Total Bid Amount</th>
                                                <th>P/L</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($single_bids->isNotEmpty())
                                                @foreach ($single_bids as $item)
                                                    <tr>
                                                        <td>{{ $item->bid_number }}</td>
                                                        <td>{{ $item->totalBid }}</td>
                                                        <td>{{ $site_data->currency_symbol }} {{ $item->totalAmount }}</td>
                                                        <td>{{ round($single_total - $item->totalAmount * $single_percent, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">No data found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-xl-6">
                    <section class="hk-sec-wrapper">
                        <h6 class="pb-2">Patti Data:</h6>
                        <div class="mb-3">
                            <p><Strong>Patti Amount: </Strong>{{ $site_data->currency_symbol }} {{ $patti_total }}</p>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="table-wrap table-format">
                                    <table class="table table-hover table-responsive display">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>Bid Number</th>
                                                <th>Total Bid</th>
                                                <th>Total Bid Amount</th>
                                                <th>P/L</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($patti_bids->isNotEmpty())
                                                @foreach ($patti_bids as $item)
                                                    <tr>
                                                        <td>{{ $item->bid_number }}</td>
                                                        <td>{{ $item->totalBid }}</td>
                                                        <td>{{ $site_data->currency_symbol }} {{ $item->totalAmount }}</td>
                                                        <td>{{ round($patti_total - $item->totalAmount * $patti_percent, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">No data found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-xl-6">
                    <section class="hk-sec-wrapper">
                        <h6 class="pb-2">Jodi Data:</h6>
                        <div class="mb-3">
                            <p><Strong>Total Amount: </Strong>{{ $site_data->currency_symbol }} {{ $jodi_total }}</p>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="table-wrap table-format">
                                    <table class="table table-hover table-responsive display">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>Bid Number</th>
                                                <th>Total Bid</th>
                                                <th>Total Bid Amount</th>
                                                <th>P/L</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($jodi_bids->isNotEmpty())
                                                @foreach ($jodi_bids as $item)
                                                    <tr>
                                                        <td>{{ $item->bid_number }}</td>
                                                        <td>{{ $item->totalBid }}</td>
                                                        <td>{{ $site_data->currency_symbol }} {{ $item->totalAmount }}</td>
                                                        <td>{{ round($jodi_total - $item->totalAmount * $jodi_percent, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">No data found.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        @push('page-scripts')
            <script>
                function clickBtn() {
                    document.getElementById('btn').disabled = true;
                }

                function change() {
                    console.log(5424)
                    document.getElementById('btn').disabled = false;
                }
            </script>
        @endpush
    @endsection

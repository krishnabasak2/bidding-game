@extends('admin/layout')
@section('content')

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
                            <i data-feather="users"></i>
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
                        {{-- <h4 class="pb-3">{{ $heading }}</h4> --}}
                        <div class="row">
                            <div class="col-sm">
                                <form action="" method="post">
                                    @csrf


                                    {{-- <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone No.<strong class="text-danger">*</strong></label>
                                                <input type="text" placeholder="User's Phone No." class="form-control"
                                                    name="phone" value="{{ old('phone'), isset($phone) ? $phone : '' }}">
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Phone No.<strong class="text-danger">*</strong></label>
                                                <select class="js-example-basic-single" name="phone">
                                                    <option value="">Select user</option>
                                                    @if ($users->isNotEmpty())
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user['phone'] }}">{{ $user['name'] }} -
                                                                {{ $user['phone'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>




            <div class="row">
                <div class="col-xl-12">
                    <section class="hk-sec-wrapper">
                        <div class="row">
                            <div class="col-sm">
                                <div class="table-wrap table-format">
                                    <table id="data-table-1" class="table table-hover table-responsive display">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>#</th>
                                                <th>TXN Id.</th>
                                                <th>Amount</th>
                                                <th>TXN Type</th>
                                                <th>Status</th>
                                                <th>Wallet Balance</th>
                                                <th>Date & Time</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        @if (!empty($data_list))
                                            <tbody>
                                                @foreach ($data_list as $key => $data)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $data['txn_id'] }}</td>
                                                        <td>{{ $site_data->currency_symbol }} {{ $data['amount'] }}
                                                            @if ($site_data->currency_value > 1)
                                                                <small>
                                                                    (₹
                                                                    {{ round($data['amount'] / $site_data->currency_value, 2) }})
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $data['type'] == '0' ? 'Debited' : 'Credited' }}
                                                        </td>
                                                        <td>
                                                            {{ ((($data['status'] == '0'
                                                                            ? 'Bidding'
                                                                            : $data['status'] == '1')
                                                                        ? 'Winning'
                                                                        : $data['status'] == '2')
                                                                    ? 'Deposit'
                                                                    : $data['status'] == '3')
                                                                ? 'Payout'
                                                                : 'By Admin' }}
                                                        </td>
                                                        <td>{{ $site_data->currency_symbol }}
                                                            {{ $data['current_balance'] }}
                                                            @if ($site_data->currency_value > 1)
                                                                <small>
                                                                    (₹
                                                                    {{ round($data['current_balance'] / $site_data->currency_value, 2) }})
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ date('M j, Y - h:i:s a', strtotime($data['created_at'])) }}
                                                        </td>
                                                        <td>{{ $data['remarks'] ? $data['remarks'] : 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

    @endsection

    @push('page-scripts')
        <script>
            $(document).ready(function() {
                $('.js-example-basic-single').select2();
            });
        </script>
    @endpush

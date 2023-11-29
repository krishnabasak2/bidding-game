@extends('admin/layout')
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
                            <i data-feather="settings"></i>
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
                        <h3 class="pb-3">Application Settings</h3>
                        <div class="row">
                            <div class="col-sm">
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Application Name <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="app_name"
                                                    value="@if (!empty($settings_data)) {{ $settings_data->app_name }}@else{{ old('app_name') }} @endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Application Website <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="url"
                                                    value="@if (!empty($settings_data)) {{ $settings_data->url }}@else{{ old('url') }} @endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Phone <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="phone"
                                                    value="@if (!empty($settings_data)) {{ $settings_data->phone }}@else{{ old('phone') }} @endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Contact Email <strong class="text-danger">*</strong></label>
                                                <input type="email" class="form-control" name="email"
                                                    value="@if (!empty($settings_data)) {{ $settings_data->email }}@else{{ old('email') }} @endif"
                                                    required>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Currency In Word <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="currency_word"
                                                    value="@if (!empty($settings_data)) {{ $settings_data->currency_word }}@else{{ old('currency_word') }} @endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Currency In Symbol <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="currency_symbol"
                                                    value="@if (!empty($settings_data)) {{ $settings_data->currency_symbol }}@else{{ old('currency_symbol') }} @endif"
                                                    required>
                                            </div>
                                        </div>



                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Application Logo <strong class="text-danger">*</strong></label>
                                                <input type="file" class="form-control" name="logo" multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <img src="{{ asset('storage/images') }}/{{ $settings_data->logo }}"
                                                    alt="" width="150px">
                                            </div>
                                        </div>



                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Welcome Banners <strong class="text-danger">*</strong></label>
                                                <input type="file" class="form-control" name="baner[]" multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                @php
                                                    $banner = json_decode($settings_data->baner, true);
                                                @endphp
                                                @if ($banner && count($banner) > 1)
                                                    @foreach ($banner as $item)
                                                        <div class="col-md-4">
                                                            <img src="{{ asset('storage/images') }}/{{ $item }}"
                                                                alt="" width="150px">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Game Rules <strong class="text-danger">*</strong></label>
                                                <textarea id="gameRules" class="form-control" name="game_rule" required>
                                                    @if (!empty($settings_data))
{{ $settings_data->game_rule }}@else{{ old('game_rule') }}
@endif
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Deposit Details <strong class="text-danger">*</strong></label>
                                                <textarea id="add_money_details" class="form-control" name="add_money_details" required>
                                                    @if (!empty($settings_data))
{{ $settings_data->add_money_details }}@else{{ old('add_money_details') }}
@endif
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Withdrawal Details <strong class="text-danger">*</strong></label>
                                                <textarea id="withdrawal_details" class="form-control" name="withdrawal_details" required>
                                                    @if (!empty($settings_data))
{{ $settings_data->withdrawal_details }}@else{{ old('withdrawal_details') }}
@endif
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Notice <strong class="text-danger">*</strong></label>
                                                <textarea class="form-control" name="notice" required>
@if (!empty($settings_data))
{{ $settings_data->notice }}@else{{ old('notice') }}
@endif
</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea class="form-control" name="message">
@if (!empty($settings_data))
{{ $settings_data->message }}@else{{ old('message') }}
@endif
</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="py-3">Game Settings</h3>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Withdrawal <strong class="text-danger">*</strong></label>
                                                <select name="withdrawal" class="form-control" required id="withdrawal"
                                                    onchange="valueCheck()">
                                                    <option value="1"
                                                        {{ $settings_data->withdrawal == '1' ? 'selected' : '' }}>Always On
                                                    </option>
                                                    <option value="0"
                                                        {{ $settings_data->withdrawal == '0' ? 'selected' : '' }}>Always
                                                        Off
                                                    </option>
                                                    <option value="2"
                                                        {{ $settings_data->withdrawal == '2' ? 'selected' : '' }}>Custom
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Game Days<strong class="text-danger">*</strong></label>
                                                <br>

                                                <a class="form-control dropdown-toggle col-md-12"
                                                    data-toggle="dropdown">Select Days</a>

                                                @php
                                                    $game_days = [];
                                                    if ($settings_data->wd_days !== null) {
                                                        $game_days = json_decode($settings_data->wd_days, true);
                                                    }
                                                @endphp
                                                <ul class="dropdown-menu dropdown_day p-3">
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option1" tabIndex="-1"><input id="day0"
                                                                type="checkbox" name="wd_days[]" value="0"
                                                                {{ in_array('0', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Sunday</a>
                                                    </li>
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option2" tabIndex="-1"><input id="day1"
                                                                type="checkbox" name="wd_days[]" value="1"
                                                                {{ in_array('1', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Monday</a>
                                                    </li>
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option3" tabIndex="-1"><input id="day2"
                                                                type="checkbox" name="wd_days[]" value="2"
                                                                {{ in_array('2', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Tuesday</a>
                                                    </li>
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option4" tabIndex="-1"><input id="day3"
                                                                type="checkbox" name="wd_days[]" value="3"
                                                                {{ in_array('3', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Wednesday</a>
                                                    </li>
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option5" tabIndex="-1"><input id="day4"
                                                                type="checkbox" name="wd_days[]" value="4"
                                                                {{ in_array('4', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Thursday</a>
                                                    </li>
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option6" tabIndex="-1"><input id="day5"
                                                                type="checkbox" name="wd_days[]" value="5"
                                                                {{ in_array('5', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Friday</a>
                                                    </li>
                                                    <li class="p-2"><a href="#" class="small"
                                                            data-value="option6" tabIndex="-1"><input id="day6"
                                                                type="checkbox" name="wd_days[]" value="6"
                                                                {{ in_array('6', $game_days) ? 'checked' : '' }}
                                                                {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }} />Saturday</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Start Time<strong class="text-danger">*</strong></label>
                                                <input type="time" class="form-control" name="wd_start_time"
                                                    id="start"
                                                    value="{{ old('wd_start_time', $settings_data->wd_start_time) }}"
                                                    {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>End Time<strong class="text-danger">*</strong></label>
                                                <input type="time" id="end" class="form-control"
                                                    name="wd_end_time"
                                                    value="{{ old('wd_end_time', $settings_data->wd_end_time) }}"
                                                    {{ $settings_data->withdrawal == '2' ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Minimum Withdrawal Amount <strong
                                                        class="text-danger">*</strong></label>
                                                <input type="number" class="form-control" name="min_withdraw"
                                                    value="{{ old('min_withdraw', $settings_data->min_withdraw) }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Minimum Deposit Amount <strong
                                                        class="text-danger">*</strong></label>
                                                <input type="number" class="form-control" name="min_add_money"
                                                    value="{{ old('min_add_money', $settings_data->min_add_money) }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Maximum Single Bidding Number <strong
                                                        class="text-danger">*</strong></label>
                                                <input type="number" class="form-control" name="max_single_bet"
                                                    value="{{ old('max_single_bet', $settings_data->max_single_bet) }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Maximum Bidding Amount <strong
                                                        class="text-danger">*</strong></label>
                                                <input type="number" class="form-control" name="max_bet_amount"
                                                    value="{{ old('max_bet_amount', $settings_data->max_bet_amount) }}"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <script>
            ClassicEditor
                .create(document.querySelector('#gameRules'))
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#add_money_details'))
                .catch(error => {
                    console.error(error);
                });

            ClassicEditor
                .create(document.querySelector('#withdrawal_details'))
                .catch(error => {
                    console.error(error);
                });


            const valueCheck = () => {
                let value = document.getElementById('withdrawal').value;
                if (value == '2') {
                    console.log(value);
                    document.getElementById('start').disabled = false;
                    document.getElementById('end').disabled = false;
                    document.getElementById('day0').disabled = false;
                    document.getElementById('day1').disabled = false;
                    document.getElementById('day2').disabled = false;
                    document.getElementById('day3').disabled = false;
                    document.getElementById('day4').disabled = false;
                    document.getElementById('day5').disabled = false;
                    document.getElementById('day6').disabled = false;
                } else {
                    document.getElementById('start').disabled = true;
                    document.getElementById('end').disabled = true;
                    document.getElementById('day0').disabled = true;
                    document.getElementById('day1').disabled = true;
                    document.getElementById('day2').disabled = true;
                    document.getElementById('day3').disabled = true;
                    document.getElementById('day4').disabled = true;
                    document.getElementById('day5').disabled = true;
                    document.getElementById('day6').disabled = true;
                }
            }
        </script>
    @endsection

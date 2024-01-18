@extends('admin/layout')
@section('content')

{{-- @php
print_r($settings);
exit;
@endphp --}}

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
                    <div class="row">
                        <div class="col-sm">
                            <form action="" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Setting</label>
                                        <select name="setting" class="form-control">
                                            <option value="off" {{$settings && $settings->setting == 'off' ? 'selected' :
                                                ''}}>Off</option>
                                            <option value="on" {{$settings && $settings->setting == 'on' ? 'selected' :
                                                ''}}>On</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Maximum Single Bidding Number</label>
                                        <input type="number" class="form-control" name="max_single_bid_num" max="10"
                                            placeholder="Maximum Single Bidding Number"
                                            value="{{ $settings ? $settings->max_single_bid_num : old('max_single_bid_num') }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Maximum Bidding Amount</label>
                                        <input type="number" class="form-control" name="max_bid_amo"
                                            placeholder="Maximum Bidding Amount"
                                            value="{{ $settings ? $settings->max_bid_amo : old('max_bid_amo') }}">
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @endsection
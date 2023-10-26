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
                            <form action="" method="post" onsubmit="clickBtn()">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>User Phone No.<strong class="text-danger">*</strong></label>
                                            <input type="text" placeholder="User Phone No." class="form-control"
                                                name="phone" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>TXN. Type<strong class="text-danger">*</strong></label>
                                            <select name="type" class="form-control">
                                                <option value="0">Debit</option>
                                                <option value="1">Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Amount<strong class="text-danger">*</strong></label>
                                            <input type="number" step="0.01" placeholder="Amount" class="form-control"
                                                name="amount" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="btn">Process</button>
                            </form>
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
    </script>
    @endpush
    @endsection
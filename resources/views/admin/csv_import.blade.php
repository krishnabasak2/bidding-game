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
                            <i data-feather="lock"></i>
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
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label mb-10">Type</label>
                                            <div class="input-group">
                                                <select class="form-control" name="type" required>
                                                    <option value="" disabled selected>Select an option</option>
                                                    <option value="1">Users</option>
                                                    <option value="2">Games</option>
                                                    <option value="3">Game Times</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label mb-10">CSV</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="csv_file" required>
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

    @endsection

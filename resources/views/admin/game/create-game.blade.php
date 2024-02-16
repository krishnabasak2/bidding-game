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
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Game Title<strong class="text-danger">*</strong></label>
                                                <input type="text" placeholder="Game Title" class="form-control"
                                                    name="title"
                                                    value="{{ old('title', $game_data ? $game_data->title : '') }}"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Game Icon <small>(200px*200px)</small></label>
                                                <input type="file" class="form-control" name="icon">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Single Win Value<strong class="text-danger">*</strong></label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="single_win_value" placeholder="eg, 90"
                                                    value="{{ old('single_win_value', $game_data ? $game_data->single_win_value : '') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Patti Win Value<strong class="text-danger">*</strong></label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="patti_win_value" placeholder="eg, 90"
                                                    value="{{ old('patti_win_value', $game_data ? $game_data->patti_win_value : '') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jodi Win Value<strong class="text-danger">*</strong></label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="jodi_win_value" placeholder="eg, 90"
                                                    value="{{ old('jodi_win_value', $game_data ? $game_data->jodi_win_value : '') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>CP Win Value<strong class="text-danger">*</strong></label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="cp_win_value" placeholder="eg, 90"
                                                    value="{{ old('cp_win_value', $game_data ? $game_data->cp_win_value : '') }}"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endsection

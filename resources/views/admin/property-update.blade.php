@extends('admin/layout')
@section('content')

<div class="hk-pg-wrapper">
    <nav class="hk-breadcrumb" aria-label="breadcrumb" style="font-weight:bold;">
        <ol class="breadcrumb breadcrumb-light bg-transparent">
            <li class="breadcrumb-item">{{$page}}</li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </nav>
    <div class="container">
        <div class="hk-pg-header">
            <h4 class="hk-pg-title">
                <span class="pg-title-icon">
                    <span class="feather-icon">
                        <i data-feather="home"></i>
                    </span>
                </span>
                {{$title}}
            </h4>
        </div>

        @if($errors->any())
            @foreach($errors->all() as $error)
                <li class="flash-message">{{$error}}</li>
            @endforeach
            <br>
        @endif
        @if(session('message'))
            <li class="flash-message">{{session('message')}}</li>
            <br>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <section class="hk-sec-wrapper">
                    <div class="row">
                        <div class="col-sm">
                            <form action="" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Property Name <strong class="text-danger">*</strong></label>
                                            <input type="text" class="form-control" name="name" placeholder="Property Name" value="@if(!empty($property_data)){{$property_data->name}}@else{{old('name')}}@endif" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Property Address <strong class="text-danger">*</strong></label>
                                            <textarea class="form-control" name="address" rows="5" placeholder="Property Address" required>@if(!empty($property_data)){{$property_data->address}}@else{{old('address')}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Property Value <strong class="text-danger">*</strong></label>
                                            <input type="number" class="form-control" name="price" placeholder="Property Value ({{$settings_data->currency_word}})" min="0" step="any" value="@if(!empty($property_data)){{$property_data->price}}@else{{old('price')}}@endif" required>
                                        </div>
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
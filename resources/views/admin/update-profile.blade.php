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
                        <i data-feather="user"></i>
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
                                <div class="form-group">
                                    <label class="control-label mb-10">Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="name" placeholder="Name" value="@if(!empty($admin_data)){{$admin_data->name}}@else{{old('name')}}@endif" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">Email ID</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-envelope"></i></span>
                                        </div>
                                        <input type="email" class="form-control" name="email" placeholder="Email ID" value="@if(!empty($admin_data)){{$admin_data->email}}@else{{old('email')}}@endif" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label mb-10">Phone No.</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="phone" placeholder="Phone No." value="@if(!empty($admin_data)){{$admin_data->phone}}@else{{old('phone')}}@endif" required>
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
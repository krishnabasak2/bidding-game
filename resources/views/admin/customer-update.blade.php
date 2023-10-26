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
                        <div class="row">
                            <div class="col-sm">
                                <form action="" method="post">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Name <strong class="text-danger">*</strong></label>
                                            <input type="text" class="form-control" name="name" placeholder="Name"
                                                value="{{ $user ? $user->name : old('name') }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Phone No.<strong class="text-danger">*</strong></label>
                                            <input type="text" class="form-control" name="phone" placeholder="Phone"
                                                value="{{ $user ? $user->phone : old('phone') }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email ID<strong class="text-danger">*</strong></label>
                                            <input type="email" class="form-control" name="email" placeholder="Email"
                                                value="{{ $user ? $user->email : old('email') }}" required>
                                        </div>
                                        @if (!$user)
                                            <div class="form-group col-md-6">
                                                <label>Password <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="password"
                                                    placeholder="Password" required>
                                            </div>
                                        @endif
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

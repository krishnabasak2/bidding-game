@extends('admin/common')
@section('content')

    <div class="col-xl-7 pa-0">
        <div class="auth-form-wrap py-xl-0 py-50">
            <div class="auth-form w-xxl-55 w-xl-75 w-sm-90 w-xs-100">
                <form action="" method="post">
                    @csrf
                    <h1 class="display-4 mb-10">Hi, let's get started.</h1>
                    <p class="mb-20" style="font-size:1.1rem;">Login to your account.</p>

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

                    <div class="form-group">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Email ID" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" minlength="6"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="font-weight:bold;">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
@endsection


@extends('layouts.auth')

@section('content')
    <div class="app-auth-body mx-auto">
        <div class="app-auth-branding mb-5">
            <a class="app-logo" href="#">
                <img class="logo-icon" src="{{ asset('assets/images/logo.svg') }}" alt="logo"
                    style="width: 100%;height:80px;">
            </a>
        </div>
        <h2 class="auth-heading text-center">Masuk</h2>
        <p class="small">Silahkan masukkan email dan password untuk masuk</p>
        <div class="auth-form-container text-start mt-4">
            <form class="auth-form login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingEmail"
                            placeholder="" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username">
                        <label for="floatingEmail">Email address</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="" name="password"
                            @error('password') is-invalid @enderror value="{{ old('password') }}" required
                            autocomplete="new-password">
                        <label for="floatingPassword">Password</label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                        <i class="fas fa-sign-in me-2"></i>
                        Masuk
                    </button>
                </div>
            </form>

            <div class="auth-option text-center pt-3">Belum punya akun ? <a class="text-link"
                    href="{{ route('register') }}">Buat akun</a>.</div>
        </div><!--//auth-form-container-->
        {{-- <div class="mt-4">
            <a href="/" class="btn btn-block btn-warning text-white"><i class="fas fa-home me-2"></i>Kembali ke
                Awal</a>
        </div> --}}

    </div><!--//auth-body-->
@endsection

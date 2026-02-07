@extends('layouts.auth')

@section('content')
    <div class="app-auth-body mx-auto">
        <div class="app-auth-branding mb-5">
            <a class="app-logo" href="#">
                <img class="logo-icon" src="{{ asset('assets/images/logo.svg') }}" alt="logo"
                    style="width: 100%;height:80px;">
            </a>
        </div>
        <h2 class="auth-heading text-center">Daftar Akun</h2>
        <p class="small">Silahkan lengkapi data berikut</p>
        <div class="auth-form-container text-start mx-auto mt-4">
            <form class="auth-form auth-signup-form" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control text-capitalize @error('name') is-invalid @enderror"
                            id="floatingName" placeholder="" name="name" value="{{ old('name') }}" required autofocus
                            autocomplete="name">
                        <label for="floatingName">Nama Lengkap</label>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingEmail" placeholder="" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email">
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
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="floatingPassword" placeholder="" name="password" value="{{ old('password') }}" required
                            autofocus autocomplete="password">
                        <label for="floatingPassword">Password</label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div>
                    <div class="form-floating mb-3">
                        <input type="password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="floatingPassword_confirmation" placeholder="" name="password_confirmation"
                            value="{{ old('password_confirmation') }}" required autofocus
                            autocomplete="password_confirmation">
                        <label for="floatingPassword_confirmation">Password Confirmation</label>
                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="extra mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="RememberPassword" required>
                        <label class="form-check-label" for="RememberPassword">
                            I agree to Portal's <a href="#" class="app-link">Terms of Service</a> and <a
                                href="#" class="app-link">Privacy Policy</a>.
                        </label>
                    </div>
                </div><!--//extra-->

                <div class="text-center">
                    <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
                        <i class="fas fa-user-plus me-2"></i>
                        Buat Akun
                    </button>
                </div>
            </form><!--//auth-form-->

            <div class="auth-option text-center pt-3">Sudah punya akun ? <a class="text-link"
                    href="{{ route('login') }}">Masuk</a></div>
        </div><!--//auth-form-container-->
        {{-- <div class="mt-4">
            <a href="/" class="btn btn-block btn-warning text-white"><i class="fas fa-home me-2"></i>Kembali ke
                Awal</a>
        </div> --}}



    </div><!--//auth-body-->
@endsection

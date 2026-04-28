@extends('layouts.auth')

@section('title', 'Login - Risk Register')

@section('content')
<main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder" style="color: #007774;">Welcome back</h3>
                  <p class="mb-0">Masukan username dan password untuk login</p>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" action="{{ route('login.authenticate') }}">
                    @csrf
                    <label>Username</label>
                    <div class="mb-3">
                      <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" value="{{ old('username') }}" required>
                      @error('username')
                        <span class="text-danger text-xs">{{ $message }}</span>
                      @enderror
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" required>
                      @error('password')
                        <span class="text-danger text-xs">{{ $message }}</span>
                      @enderror
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn w-100 mt-4 mb-0" style="background-color: #007774; color: white;">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Masalah login? Hubungi Administrator.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('{{ asset('style/assets/img/curved-images/curved6.jpg') }}')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection

@extends('layouts.admin.app')

@section('title', 'Login')

@section('page-specific-styles')
<!--begin::Page Custom Styles(used by this page)-->
<link href="{{asset('assets/admin/css/pages/login/classic/login-5.css')}}" rel="stylesheet" type="text/css" />
<!--end::Page Custom Styles-->
@endsection
@section('guest')
<!--begin::Login-->
<div class="login login-5 login-signin-on d-flex flex-row-fluid" id="kt_login">
    <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid"
        style="background-image: url({{asset('assets/admin/media/bg/bg-2.jpg')}}">
        <div class="login-form text-center text-white p-7 position-relative overflow-hidden">
            <!--begin::Login Header-->
            <div class="d-flex flex-center mb-15">
                <a href="#">
                    <img src="assets/media/logos/logo-letter-13.png" class="max-h-75px" alt="" />
                </a>
            </div>
            <!--end::Login Header-->
            <!--begin::Login Sign in form-->
            <div class="login-signin">
                <div class="mb-20">
                    <h3 class="opacity-40 font-weight-normal">Sign In To Self Drive Admin Panel</h3>
                    <p class="opacity-40">Enter your details to login to your account:</p>
                </div>
                <form class="form" id="kt_login_signin_form" action="{{ route('admin.login') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8"
                            type="text" placeholder="Username" name="username" autocomplete="off" />
                        @error('username')
                        <div class="fv-plugins-message-container">
                            <div data-field="username" class="fv-help-block">{{ $message}}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input class="form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8"
                            type="password" id="password" placeholder="Password" name="password" />
                        <i class="fas fa-eye-slash" id="eye"></i>
                        @error('password')
                        <div class="fv-plugins-message-container">
                            <div data-field="username" class="fv-help-block">{{ $message }}</div>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-between align-items-center px-8 opacity-60">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-outline checkbox-white text-white m-0">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : ''
                                    }}>
                                <span></span>Remember me</label>
                        </div>
                        <a href="{{route('password.request')}}" class="text-white font-weight-bold">Forget Password
                            ?</a>
                    </div>
                    <div class="form-group text-center mt-10">
                        <button id="kt_login_signin_submit" class="btn btn-pill btn-primary opacity-90 px-15 py-3">Sign
                            In</button>
                    </div>
                </form>
            </div>
            <!--end::Login Sign in form-->
        </div>
    </div>
</div>
<!--end::Login-->
@endsection
@section('page-specific-scripts')
<!--begin::Page Scripts(used by this page)-->
<script src="{{asset('assets/admin/js/pages/custom/login/login-general.js')}}"></script>
<!--end::Page Scripts-->

<script>
    const password = document.querySelector("#password");
    const eyeIcon = document.querySelector("#eye");

    eyeIcon.addEventListener("click", () => {
        if (eyeIcon.classList.contains("fa-eye-slash")) {
            password.setAttribute("type", "text");
            eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            password.setAttribute("type", "password");
            eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
        }
    });
</script>
@endsection
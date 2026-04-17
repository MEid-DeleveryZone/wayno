@extends('layouts.store', ['title' => 'Register'])
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template) && $set_template->template_id == 1)
    @include('layouts.store/left-sidebar-template-one')
    @elseif(isset($set_template) && $set_template->template_id == 2)
    @include('layouts.store/left-sidebar')
    @else
    @include('layouts.store/left-sidebar-template-one')
    @endif
</header>
<section class="register-page section-b-space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            Your password has been changed successfully!
                        </div>
                        <div class="bg-dark text-light pl-2 py-1 d-none d-sm-block app-link-box">
                            <a href="" class="app-link text-light"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    var login_url = "{{url('user/login')}}";
    var reset_password_success_url = "{{url('reset-password-success')}}";
    var reset_password_url = "{{route('reset-password')}}";
</script>
<script src="{{asset('js/forgot_password.js')}}"></script>
@endsection
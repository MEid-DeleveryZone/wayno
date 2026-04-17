@yield('css')

<!-- icons -->
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />

@if(isset($mode) && $mode == 'rtl')

<!-- App css -->
@if(isset($demo) && $demo == 'creative')
<link href="{{asset('assets/css/bootstrap-creative.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" disabled />
<link href="{{asset('assets/css/app-creative-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" disabled />
<link href="{{asset('assets/css/bootstrap-creative-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-creative-dark-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css"/>
@else
@if(isset($demo) && $demo == 'modern')
<link href="{{asset('assets/css/bootstrap-modern.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-modern-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-modern-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-modern-dark-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
@if(isset($demo) && $demo == 'material')
<link href="{{asset('assets/css/bootstrap-material.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-material-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-material-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-material-dark-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
@if(isset($demo) && $demo == 'purple')
<link href="{{asset('assets/css/bootstrap-purple.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-purple-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-purple-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-purple-dark-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
@if(isset($demo) && $demo == 'saas')
<link href="{{asset('assets/css/bootstrap-saas.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-saas-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-saas-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-saas-dark-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-dark-rtl.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@endif
@endif
@endif
@endif
@endif

@else
<!-- App css -->
@if(isset($demo) && $demo == 'creative')
@if(isset($theme) && $theme->theme_admin == 'dark')
<link href="{{asset('assets/css/bootstrap-creative-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-creative-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
<link href="{{asset('assets/css/bootstrap-creative.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-creative.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@endif
@else
@if(isset($demo) && $demo == 'modern')
<link href="{{asset('assets/css/bootstrap-modern.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-modern.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-modern-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-modern-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
@if(isset($demo) && $demo == 'material')
<link href="{{asset('assets/css/bootstrap-material.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-material.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-material-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-material-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
@if(isset($demo) && $demo == 'purple')
<link href="{{asset('assets/css/bootstrap-purple.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-purple.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-purple-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-purple-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
@if(isset($demo) && $demo == 'saas')
<link href="{{asset('assets/css/bootstrap-saas.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app-saas.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-saas-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-saas-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@else
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
<link href="{{asset('assets/css/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
<link href="{{asset('assets/css/bootstrap-dark.min.css')}} " rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
<link href="{{asset('assets/css/app-dark.min.css')}} " rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" type="text/css" />
@endif
@endif
@endif
@endif
@endif
@endif

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB85kLYYOmuAhBUPd7odVmL6gnQsSGWU-4&v=3.exp&libraries=places,drawing"></script>
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/libs/clockpicker/clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/jquery-ui.css')}}" rel="stylesheet" type="text/css" />

@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Refer And Earn'])

@section('content')

<div class="container-fluid ">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Refer And Earn") }}</h4>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-8">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <form method="POST" action="{{route('referandearn.reffered_by_amount')}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">{{ __("Reffered by earning") }}</h4>
                            <p class="sub-header">
                                {{ __("Update reffered by earning here.") }}
                            </p>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="reffered_by_amount">{{ __("Reffered by earning") }}</label>
                                        <input type="text" name="reffered_by_amount" id="reffered_by_amount" placeholder="xyz" class="form-control" value="{{ old('reffered_by_amount', $reffer_by ?? '')}}">
                                        @if($errors->has('reffered_by_amount'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('reffered_by_amount') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-info btn-block" type="submit"> {{ __("Save") }} </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-6">
            <form method="POST" action="{{route('referandearn.reffered_to_amount')}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">{{ __("Reffered to earning") }}</h4>
                            <p class="sub-header">
                                {{ __("Update reffered to earning here.") }}
                            </p>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="reffered_to_amount">{{ __("Reffered to earning") }} </label>
                                        <input type="text" name="reffered_to_amount" id="reffered_to_amount" class="form-control" value="{{ old('reffered_to_amount', $reffer_to ?? '')}}">
                                        @if($errors->has('reffered_to_amount'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('reffered_to_amount') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-info btn-block" type="submit"> {{ __("Save") }} </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div> <!-- container -->
@endsection

@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>

@endsection
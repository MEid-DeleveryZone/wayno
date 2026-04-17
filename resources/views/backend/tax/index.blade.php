@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Tax'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Tax") }}</h4>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
                @if (\Session::has('error_delete'))
                <div class="alert alert-danger">
                    <span>{!! \Session::get('error_delete') !!}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h4 class="page-title">{{ __("Tax Category") }}</h4>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right addTaxCateModal"
                             userId="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __("Add") }}
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Code") }}</th>
                                    <th>{{ __("Description") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($taxCates as $cat)
                                <tr data-row-id="{{$cat->id}}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a class="editTaxCateModal text-capitalize" userId="{{$cat->id}}" href="javascript:void(0);"> {{ $cat->title }}</a> </td>
                                    <td> {{ $cat->code }} </td>
                                    <td> {{ $cat->description }} </td>
                                    
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon editTaxCateModal" userId="{{$cat->id}}" href="javascript:void(0);"><i class="mdi mdi-square-edit-outline"></i></a> 
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('tax.destroy', $cat->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                       <button type="submit" onclick="return confirm('Are you sure? You want to delete the tax category.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button> 

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0"></div>
                </div>
            </div> 
        </div> 
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h4 class="page-title">{{ __("Tax Rate") }}</h4>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right addTaxRateModal"
                             userId="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __("Add") }}
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="Rate-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("Identifier") }}</th>
                                    <th>{{ __("Tax Categories") }}</th>
                                    <th>{{ __("Postal Code(s)") }}</th>
                                    <th>{{ __("Tax Rate") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($taxRates as $rat)
                                <tr data-row-id="{{$cat->id}}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $rat->identifier }} </td>
                                    <td> 
                                        @foreach($rat->category as $cats)
                                            <span>{{$cats->title}}</span><br/>
                                        @endforeach
                                    </td>
                                    <td> 
                                        @if( $rat->is_zip == 1)
                                            {{ $rat->zip_code }}
                                        @elseif( $rat->is_zip == 2)
                                            {{ $rat->zip_from }} - {{ $rat->zip_to }}
                                        @else
                                            {{ __("N/A") }}
                                        @endif
                                    </td>
                                    <td> {{ $rat->tax_rate }} </td>
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon editTaxRateModal" userId="{{$rat->id}}" href="javascript:void(0);"><i class="mdi mdi-square-edit-outline"></i></a> 
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('taxRate.destroy', $rat->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                       <button type="submit" onclick="return confirm('Are you sure? You want to delete the tax rate.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button> 
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.tax.modals')
@endsection
@section('script')
@include('backend.tax.pagescript')
@endsection
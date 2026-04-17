@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Promocode'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@php
$timezone = Auth::user()->timezone ? Auth::user()->timezone : 'UTC';
@endphp
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Promocode') }}</h4>
            </div>
        </div>
        <div class="col-sm-6 text-sm-right">
            <button class="btn btn-info waves-effect waves-light text-sm-right openPromoModal" userId="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }} </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                                @if (\Session::has('Data_Updated'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('Data_Updated') !!}</span>
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
                    <div class="table-responsive">
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Promo Code') }}</th>
                                    <th style="width:150px">{{ __('Title') }}</th>
                                    <th style="width:150px">{{ __('Description') }}</th>
                                    <th>{{ __('Promo Types') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                    <th>{{ __('Expiry Date') }}</th>
                                    <!-- <th>Min Amount To Spend</th>
                                    <th>Max Amount To Spend</th>
                                    <th>Limit Per User</th>
                                    <th>Total Limit</th>
                                    <th>Restriction On</th>
                                    <th>Restriction Type</th> -->
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($promocodes as $promo)
                                <tr data-row-id="">
                                    <td class="draggableTd"><span class="dragula-handle"></span></td>
                                    <td> 
                                        @if($promo->primary && $promo->primary->image)
                                            <img class="promo_img" src="{{$promo->primary->image['proxy_url'].'200/100'.$promo->primary->image['image_path']}}" alt="{{$promo->id}}" >
                                        @else
                                            <img class="promo_img" src="{{asset('default/default_image.png')}}" alt="{{$promo->id}}" >
                                        @endif
                                    </td>
                                    <td><a class="openPromoModal text-capitalize" userId="{{$promo->id}}" href="#"> {{$promo->name}}</a></td>
                                    <td style="width:100px"><p class="ellips">{{$promo->primary ? $promo->primary->title : ''}}</p></td>
                                    <td style="width:100px"><p class="ellips">{{$promo->primary ? $promo->primary->short_desc : ''}}</p></td>
                                    <td>{{$promo->type ? $promo->type->title : ''}}</td>
                                    <td>{{$promo->amount}}</td>
                                    <td>{{convertDateTimeInTimeZone($promo->expiry_date, $timezone, 'M d Y, H:i A')}}</td>
                                    <td>
                                        @if($promo->added_by == Auth::id() || Auth::user()->is_superadmin == 1)
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon openPromoModal" userId="{{$promo->id}}" href="#">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{route('promocode.destroy', $promo->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" onclick="return confirm('Are you sure? You want to delete the Promocode.')" class="btn btn-primary-outline action-icon">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{-- $promocode->links() --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.promocode.modals')
@endsection
@section('script')
@include('backend.promocode.pagescript')
@endsection
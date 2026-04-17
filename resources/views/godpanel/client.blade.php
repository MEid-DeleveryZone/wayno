@extends('layouts.god-vertical', ['title' => 'Clients'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Clients') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if(\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif

                                @if(\Session::has('error'))
                                <div class="alert alert-error">
                                    <span>{!! \Session::get('error') !!}</span>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <a class="btn btn-info waves-effect waves-light text-sm-right" href="{{route('client.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Email") }}</th>
                                    <th>{{ __("Password") }}</th>
                                    <th>{{ __("Phone") }}</th>
                                    <th>{{ __("DB Name") }}</th>
                                    <th>{{ __("SUB Domain") }}</th>
                                    <th>{{ __("Client Code") }}</th>
                                    <th style="width: 85px;">{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr id="tr_{{ $client->id }}">
                                    <td class="table-user">
                                        <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$client->name}}</a>
                                    </td>
                                    <td> {{$client->email}} </td>
                                    <td style="width:100px;max-width:100px;"> </td>
                                    <td> {{$client->phone_number}} </td>
                                    <td> {{$client->database_name}} </td>
                                    <td><a target="_blank" href="{{$client->sub_domain_url}}">{{$client->sub_domain }}{{env('SUBMAINDOMAIN') }}</a> </td>
                                    <td> {{$client->code}} </td>
                                    <td>
                                        <a href="{{route('client.edit', $client->id)}}" class="btn btn-primary-outlineaction-icon p-0"> 
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <a class="btn btn-primary-outlineaction-icon delete-client p-0" data-client_id="{{ $client->id }}" data-url="{{ URL::to('godpanel/delete/client/'.$client->id) }}">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}
        });
        $(document).on("click",".delete-client",function() {
            var url = $(this).data('url');
            var client_id = $(this).data('client_id');
            if(confirm('Are you sure? You want to delete this client.')) {
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: {client_id:client_id},
                    headers: {Accept: "application/json"},
                    success: function(response) {
                        $("#promo_code_list_main_div").html('');
                        if (response.status == "Success") {
                            $('#tr_'+client_id).remove();
                        }
                    }
                });
            }
            return false;
        });
    }); 
</script>
@endsection

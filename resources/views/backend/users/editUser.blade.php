
@extends('layouts.vertical', ['title' =>  'Customer' ])


@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                @if(isset($subadmin))
                <h4 class="page-title">{{ __('Update Customer') }}</h4>
                @endif
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($subadmin))
                    <form id="UpdateSubadmin" method="post" action="{{route('customer.new.update', $subadmin->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreSubadmin" method="post" action="{{route('acl.store')}}"
                            enctype="multipart/form-data">
                            @endif
                            @csrf
                           
                            <div class=" row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">{{ __("NAME") }}</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $subadmin->name ?? '')}}" placeholder="John Doe" readonly>
                                        @if($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="control-label">{{ __('EMAIL') }}</label>
                                         <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $subadmin->email ?? '')}}" placeholder="Enter email address" readonly>
                                        @if($errors->has('email'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                               
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">{{ __("Status") }}</label>
                                        <select name="status" class="form-control">
                                            <option value="1" @if($subadmin->status==1) selected @endif>{{ __("Active") }}</option>
                                            <option value="3" @if($subadmin->status==3) selected @endif>{{ __("Inactive") }}</option>
                                           
                                        </select>                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">{{ __("Provide Admin Access") }}</label>
                                         <select name="is_admin" class="form-control">
                                            <option value="0" @if($subadmin->is_admin==0) selected @endif>{{ __("No") }}</option>
                                            <option value="1" @if($subadmin->is_admin==1) selected @endif>{{ __("Yes") }}</option>
                                           
                                        </select>                                        
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-lg-0 mb-3 user_perm_section table-responsive">
                                    @php
                                        $userpermissions = [];
                                        if(isset($user_permissions))
                                        {
                                            foreach ($user_permissions as $singlepermission) {
                                                $userpermissions[] = $singlepermission->permission_id;
                                            }
                                        }
                                    @endphp
                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">
        
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ __("Permission Name") }}</th>
                                                <th>{{ __("Status") }}</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            @php 
                                            $brity = \App\Models\ClientPreference::where(['id' => 1])->first('celebrity_check');
                                            @endphp
                                            @foreach($permissions as $singlepermission)
                                            @if($singlepermission->name == 'CELEBRITY')
                                                
                                                    @if(!empty($brity) && $brity->celebrity_check == 1)
                                                    <tr>
                                                    <td>
                                                        <h5 class="m-0 font-weight-normal">{{ ucwords(strtolower($singlepermission->name)) }}</h5>
                                                    </td>
            
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >
                                                            
                                                            <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                    @endif

                                            @elseif($singlepermission->name == 'CMS Pages' || $singlepermission->name == 'CMS Emails')
                                                <tr>
                                                    <td>
                                                        <h5 class="m-0 font-weight-normal">{{ $singlepermission->name }}</h5>
                                                    </td>
            
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >
                                                            
                                                            <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                        </div>
                                                    </td>
                                                </tr> 
                                            @else   
                                                <tr>
                                                    <td>
                                                        <h5 class="m-0 font-weight-normal">{{ ucwords(strtolower($singlepermission->name)) }}</h5>
                                                    </td>
            
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >
                                                            
                                                            <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                        </div>
                                                    </td>
                                                </tr> 
                                            @endif
                                            @endforeach
        
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-lg-6 team_perm_section table-responsive">
                                
                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">
        
                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{__('Vendors')}}</th>
                                                <th>{{ __("Status") }}</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody> 
                                           
                                            
                                            @foreach($vendors as $vendor)
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 font-weight-normal">{{ $vendor->name }}</h5>
                                                </td>
        
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input vendor_permission_check" data-id="{{ $vendor->id }}" 
                                                        data-event-type="vendor_permission" id="vendor_permission_{{ $vendor->id}}" 
                                                        name="vendor_permissions[]" value="{{ $vendor->id }}" 
                                                        @if(in_array($vendor->id, $vendor_permissions)) checked @endif>
                                                        <label class="custom-control-label" for="vendor_permission_{{ $vendor->id}}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
        
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                                                    
                            
                            
                        

                            <div class="row mb-2 mt-4">
                                <div class="col-12">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-blue btn-block" type="submit"> {{ __("Submit") }} </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
  

@endsection
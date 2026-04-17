@extends('layouts.vertical', ['title' =>  'ACL' ])

@section('css')
   <link rel="stylesheet" href="{{ asset('telinput/css/intlTelInput.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('telinput/css/demo.css') }}" type="text/css">
    <style>

        .cursors {
            cursor:move;
            margin-right: 0rem !important;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Managers</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
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
                            </div>
                        </div>
                        <div class="col-sm-4 text-right btn-auto">
                            <a class="btn btn-blue waves-effect waves-light text-sm-right"
                                href="{{route('acl.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add
                                Manager</a>
                            {{-- <button type="button" class="btn btn-blue waves-effect waves-light openModal" data-toggle="modal" data-target="" data-backdrop="static" data-keyboard="false"><i class="mdi mdi-plus-circle mr-1"></i> Add Sub Admin</button> --}}
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-responsive nowrap w-100" id="">
                            <thead>
                                <tr>                                    
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($subadmins as $singleuser)
                                <tr> 
                                    <td>
                                        {{ $singleuser->name }}
                                    </td>
                                    <td>
                                        {{ $singleuser->email }}
                                    </td>
                                    <td>
                                        {{ $singleuser->phone_number }}
                                    </td>
                                    <td>
                                        {{ ($singleuser->status==1)?"Active":"Inactive" }}
                                    </td>                                    
                                    <td>
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div"> <a href1="#" href="{{route('acl.edit', $singleuser->id)}}"  class="action-icon editIconBtn"> <i class="mdi mdi-square-edit-outline"></i></a></div>
                                            {{-- <div class="inner-div">
                                                <form method="POST" action="{{route('acl.destroy', $singleuser->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary-outline action-icon"> <i class="mdi mdi-delete"></i></button>

                                                    </div>
                                                </form>
                                            </div> --}}
                                        </div>                                        
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $subadmins->links() }}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

@endsection

@section('script')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <script src="{{ asset('assets/js/storeAgent.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>  --}}
    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
    <script src="{{ asset('telinput/js/intlTelInput.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />>


@endsection
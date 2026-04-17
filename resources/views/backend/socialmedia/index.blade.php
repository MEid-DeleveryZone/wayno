@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Social Media'])
@section('css')
<link href="{{asset('assets/libs/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Social Media") }}</h4>
            </div>
        </div>
        <div class="col-sm-6 text-sm-right">
            <button class="btn btn-info waves-effect waves-light text-sm-right" id="add_social_media_modal"><i class="mdi mdi-plus-circle mr-1"></i>Add</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("Icon") }}</th>
                                    <th>{{ __("Title") }}</th>
                                    <th>{{ __("URL") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($social_media_details as $social_media_detail)
                                <tr data-row-id="">
                                    <td></td>
                                    <td><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></td>
                                    <td style="width:100px"><p class="ellips">{{$social_media_detail->title}}</p></td>
                                    <td style="width:100px"><p class="ellips">{{$social_media_detail->url}}</p></td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="standard_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title" id="standard-modalLabel">{{ __("Social Media") }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="" action="">
                        <div class="form-group">
                            <label for="">{{ __("Name") }}</label>
                             <input class="form-control icp icp-auto" value="fas fa-anchor" type="text"/>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __("Name") }}</label>
                            <input class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label for="">{{ __("Name") }}</label>
                            <input class="form-control" type="text">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">{{ __("Save changes") }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js')}}">
@endsection
<script type="text/javascript">
    $(document ).ready(function() {
        $(document).on("click","#add_social_media_modal",function() {
            $('#standard_modal').modal('show');
        });
    });
</script>
@endsection


@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Notifications'])
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Notifications") }}</h4>
            </div>
        </div>
    </div>
    <div class="row cms-cols">
        <div class="col-lg-5 col-xl-3 mb-2">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4>{{ __("List") }}</h4>
                    </div> 
                   <div class="table-responsive pages-list-data">
                        <table class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ __("Notification Name") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notification_templates as $notification_template)
                                    <tr class="page-title active-page notification-page-detail" data-notification_template_id="{{$notification_template->id}}" data-show_url="{{route('cms.notifications.show', ['id'=> $notification_template->id])}}">
                                        <td>
                                            <a class="text-body d-block" href="javascript:void(0)" id="text_body_{{$notification_template->id}}">{{$notification_template->label}}</a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                   </div>
                </div>            
            </div>
        </div>
        <div class="col-lg-7 col-xl-9 mb-2">
            <div class="card">
                <div class="card-body p-3" id="edit_page_content">
                    <div class="row mb-2">
                        <div class="offset-xl-8 col-md-4 col-xl-2">
                            <div class="form-group mb-0">
                                <select class="form-control" id="client_language">
                                   @foreach($client_languages as $client_language)
                                    <option value="{{$client_language->langId}}">{{$client_language->langName}}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xl-2 text-right">
                            <button type="button" class="btn btn-info w-100" id="update_notification_template"> {{ __("Update") }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="notification_template_id" value="">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="title" class="control-label">{{ __("Subject") }}</label>
                                    <input class="form-control" id="subject" placeholder="Subject" name="subject" type="text">
                                    <span class="text-danger error-text updatetitleError"></span>
                                </div>
                                <div class="col-md-10 mb-3">
                                    <label for="title" class="control-label">{{ __("Content") }}</label>
                                    <textarea class="form-control" id="content" placeholder="Content" rows="6" name="content" cols="10"></textarea>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="title" class="control-label">{{ __("Tags") }}:-<div id="tags" disabled=""></div></label>
                                </div>
                            </div>         
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){ 
            $('tr.page-title:first').trigger('click');
        }, 500);
        $(document).on("change","#client_language",function() {
            let notification_template_id = $('#edit_page_content #notification_template_id').val();
            $('.notification-page-detail[data-notification_template_id="'+notification_template_id+'"]').trigger('click');
        });
        $(document).on("click",".notification-page-detail",function() {
            $('#edit_page_content #content').val('');
            // $('#edit_page_content #content').summernote('destroy');
            let url = $(this).data('show_url');
            let language_id = $('#edit_page_content #client_language :selected').val();
            $.get(url, {language_id:language_id}, function(response) {
              if(response.status == 'Success'){
                if(response.data){
                    $('#edit_page_content #notification_template_id').val(response.data.id);
                    $('#edit_page_content #tags').html(response.data.tags);
                    if(response.data.translation){
                        $('#edit_page_content #subject').val(response.data.translation.subject);
                        $('#edit_page_content #content').val(response.data.translation.content);
                        // $('#edit_page_content #content').summernote({'height':450});
                    }else{
                      $('#edit_page_content #subject').val('');
                      $('#edit_page_content #content').val('');
                    }
                }else{
                    $('textarea').val('');
                    $(':input:text').val('');
                    $('#edit_page_content #notification_template_id').val('');
                }
              }
            });
        });
        $(document).on("click","#update_notification_template",function() {
            var update_url = "{{route('cms.notifications.update')}}";
            let subject = $('#edit_page_content #subject').val();
            let content = $('#edit_page_content #content').val();
            let notification_template_id = $('#edit_page_content #notification_template_id').val();
            let language_id = $('#edit_page_content #client_language :selected').val();
            var data = { subject: subject, content: content, notification_template_id: notification_template_id, language_id: language_id};
            $.post(update_url, data, function(response) {
              $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
              setTimeout(function() {
                    location.reload()
                }, 2000);
            }).fail(function(response) {
                if(response.responseJSON.errors){
                    if(response.responseJSON.errors.subject){
                        $('#edit_page_content .updatetitleError').html(response.responseJSON.errors.subject[0]);
                    }
                }
            });
        });
    });
</script>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
@endsection
<form id="edit-html-form" action="javascript:void(0)" method="post">
    <input type="hidden" id="layout_id" value="" name="layout_id">
    <div class="dd-item dd3-item" data-id="1" data-row-id="0">
        <div class="language-inputs w-100 style-4">
            <div class="row no-gutters align-items-center my-2">
                @foreach($langs as $key => $lang)
                @php
                $exist = 0;
                $value = '';
                @endphp
                <input class="form-control" type="hidden" value="{{$lang->langId}}" name="languages[]">
                                
                <div class="col-md-12 mb-3">
                    <label for="title" class="control-label">Description ({{$lang->langName}})</label>
                    <textarea class="form-control description_ck"  rows="5" name="description[]" id="description{{$key}}" cols="50">
                        @foreach ($html_data as $keyset => $data)
                            @if($lang->langId == $data->language_id)
                            {{$data->body_html}}
                            @endif
                        @endforeach
                    </textarea>
                    <span class="text-danger error-text updatedescrpitionError"></span>
                </div>

                @endforeach
            </div>
        </div>
      
        <div class="mt-3 mb-2">
            <button class="btn btn-info waves-effect waves-light text-center w-100"  type="submit" id="submit_exist_pickup_section">{{ __('Update') }}</button>
        </div>
    </div>
</form>
<script>
        var allEditors = document.querySelectorAll('.description_ck');
    console.log(allEditors.length);
    for (var i = 0; i < allEditors.length; ++i) {
       CKEDITOR.replace('description'+i);
       CKEDITOR.config.height = 150;
    }



     // save html data section 
     $('#edit-html-form').submit(function(e) {
        e.preventDefault();  
        var form = document.getElementById('edit-html-form');

        var formData = new FormData(form);
        for (var i = 0; i < allEditors.length; ++i) {
            formData.append("description_["+i+"]", CKEDITOR.instances["description"+i].getData());
        }
        var data_uri = "{{route('edit.Dynamic.Html.Section')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
         $.ajax({
            type: "post",
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                Accept: "application/json"
            },
            success: function(response) {
                if (response.status == 'success') {
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    var r = document.querySelector(':root');
                    r.style.setProperty('--theme-deafult', 'lightblue');
                    location.reload();
                }
            }
        });
    });
</script>
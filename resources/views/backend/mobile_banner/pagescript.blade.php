<script>
    var bannerOn = $('.chk_box');

    $(bannerOn).on("change" , function() {
        var ban_id = $(this).attr('bid');
        var chk = $('#cur_' + ban_id + ':checked').length;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ url('client/mobilebanner/changeValidity') }}",
            data: {
                _token: CSRF_TOKEN,
                value: chk,
                banId: ban_id
            },
            success: function(response) {

                if (response.status == 'success') {
                }
                return response;
            }
        });
    });

    
    $('.openAddModal').click(function(){
        $('#add-form').modal({
            //backdrop: 'static',
            keyboard: false
        });
        //var now = ;
        runPicker();
    });

    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            startDate: new Date(),
            minDate: new Date(),
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
    }

    $(".openBannerModal").click(function (e) {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uri = "{{route('mobilebanner.create')}}";
       
        var uid = $(this).attr('userId');
        if(uid > 0){
            uri = "<?php echo url('client/mobilebanner'); ?>" + '/' + uid + '/edit';

        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            beforeSend: function(){
                $(".loader_box").show();
            },
            success: function (data) {
                if(uid > 0){
                    $('#edit-form #editCardBox').html(data.html);
                    $('#edit-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    
                }else{
                    $('#add-form #AddCardBox').html(data.html);
                    $('#add-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                var now = new Date();
                runPicker();
                $('.dropify').dropify();
                $('.selectize-select').selectize();
                
                // Add dropify event listeners for image removal detection
                $('.dropify').on('dropify.afterClear', function() {
                    var input = $(this);
                    var hasExistingImage = input.attr('data-has-existing-image') === 'true';
                    if (hasExistingImage) {
                        // Mark that the existing image was removed
                        input.attr('data-image-removed', 'true');
                        
                        // Show warning message only when image is actually removed
                        var warningDiv = input.closest('#imageInput').find('.image-removed-warning');
                        if (warningDiv.length === 0) {
                            input.closest('#imageInput').append('<div class="image-removed-warning alert alert-warning mt-2"><i class="fas fa-exclamation-triangle"></i> {{ __("Warning: Image has been removed. You must upload a new image to continue.") }}</div>');
                        }
                    }
                });
                
                // Reset the removed flag when a new image is selected
                $('.dropify').on('change', function() {
                    var input = $(this);
                    if (input[0].files.length > 0) {
                        input.attr('data-image-removed', 'false');
                        
                        // Hide warning message
                        input.closest('#imageInput').find('.image-removed-warning').remove();
                    }
                });
                
                // Also reset the removed flag when the form is loaded (in case of edit)
                $('.dropify').each(function() {
                    var input = $(this);
                    var hasExistingImage = input.attr('data-has-existing-image') === 'true';
                    if (hasExistingImage) {
                        // Initially, no image is removed
                        input.attr('data-image-removed', 'false');
                    }
                });

                elem1 = document.getElementsByClassName('validity_add');
                if(elem1.length > 0){
                    var switchery = new Switchery(elem1[0]);
                }
                elem2 = document.getElementsByClassName('validity_edit');
                if(elem2.length > 0){
                    var switchery = new Switchery(elem2[0]);
                }

            },
            error: function (data) {
                console.log('data2');
            },
            complete: function(){
                $('.loader_box').hide();
            }
        });
    });

    $(document).on('change', '.assignToSelect', function(){
        var val = $(this).val();
        if(val == 'category'){
            $('.modal .category_vendor').show();
            $('.modal .category_list').show();
            $('.modal .vendor_list').hide();
        }else if(val == 'vendor'){
            $('.modal .category_vendor').show();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').show();
        }else{
            $('.modal .category_vendor').hide();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').hide();
        }
    });

    $(document).on('click', '.submitAddForm', function(e) { 
        e.preventDefault();


        var form =  document.getElementById('save_banner_form');
        
        
        
        var formData = new FormData(form);


        var url =  document.getElementById('bannerId').getAttribute('url');
        saveData(formData, 'add', url );


    });

    $(document).on('click', '.submitEditForm', function(e) { 
        e.preventDefault();
        
        // Check if image is required and if it's been removed
        var imageInput = document.querySelector('#save_edit_banner_form input[name="image"]');
        var hasExistingImage = imageInput.getAttribute('data-has-existing-image') === 'true';
        var hasNewImage = imageInput.files.length > 0;
        var imageRemoved = imageInput.getAttribute('data-image-removed') === 'true';
        
        // Only prevent submission if:
        // 1. Banner had an existing image, AND
        // 2. No new image is uploaded, AND  
        // 3. The existing image was actually removed
        if (hasExistingImage && !hasNewImage && imageRemoved) {
            alert('Image is required. Please upload an image or keep the existing one.');
            return false;
        }
        
        var form =  document.getElementById('save_edit_banner_form');
        var formData = new FormData(form);
        var url =  document.getElementById('bannerId').getAttribute('url');
        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, banner_uri){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
      
        // Set the HTTP method based on the type
        var httpMethod = (type === 'edit') ? 'PUT' : 'POST';
        
        // Add the _method field for PUT requests (Laravel method spoofing)
        if (type === 'edit') {
            formData.append('_method', 'PUT');
        }

        // Debug logging
        console.log('Type:', type);
        console.log('URL:', banner_uri);
        console.log('Method:', httpMethod);
        console.log('FormData contents:');
        for(var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        $.ajax({
            type: "post", // Always use POST, Laravel will handle the method spoofing
            headers: {
                Accept: "application/json"
            },
            url: banner_uri,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $(".loader_box").show();
            },
            success: function(response) {
                console.log("----",response);
                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload(); 
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                console.log("====",response)
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input input").addClass("is-invalid");
                        $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            },
            complete: function(){
                $('.loader_box').hide();
            }
        });
    }

    $("#banner-datatable tbody").sortable({
        placeholder : "ui- state-highlight",
        handle: ".dragula-handle",
        update  : function(event, ui)
        {
            var post_order_ids = new Array();
            $('#post_list tr').each(function(){
                post_order_ids.push($(this).data("row-id"));
            });
            console.log(post_order_ids);
            saveOrder(post_order_ids);
        }
    });

    var CSRF_TOKEN = $("input[name=_token]").val();
    function saveOrder(orderVal){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ url('client/mobilebanner/saveOrder') }}",
             data: {
                _token: CSRF_TOKEN,
                order: orderVal
            },
            success: function(response) {

                if (response.status == 'success') {
                }
                return response;
            },
            beforeSend: function(){
                $(".loader_box").show();
            },
            complete: function(){
                $(".loader_box").hide();
            },
        });
    }

    $("#user-modal #add_user").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.addVendorForm', function() { 
        var form =  document.getElementById('add_customer');
        var formData = new FormData(form);
        var urls = "{{URL::route('vendor.store')}}";
        saveCustomer(urls, formData, inp = '', modal = 'user-modal');
    });

    $("#edit-user-modal #edit_user").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.editVendorForm', function(e) {
        e.preventDefault();
        var form =  document.getElementById('edit_customer');
        var formData = new FormData(form);
        var urls =  document.getElementById('customer_id').getAttribute('url');
        saveCustomer(urls, formData, inp = 'Edit', modal = 'edit-user-modal');
        console.log(urls);
    });
</script>
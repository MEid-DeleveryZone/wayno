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
            url: "{{ url('client/banner/changeValidity') }}",
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

        var uri = "{{route('banner.create')}}";
       
        var uid = $(this).attr('userId');
        if(uid > 0){
            uri = "<?php echo url('client/banner'); ?>" + '/' + uid + '/edit';

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
      

        // for(var pair of formData.entries()) {
        //     console.log(pair)
        // }

     

        $.ajax({
            type: "post",
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
            url: "{{ url('client/banner/saveOrder') }}",
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
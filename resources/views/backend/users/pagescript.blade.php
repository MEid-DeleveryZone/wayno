<script>
    var CSRF_TOKEN = $("input[name=_token]").val();
    $('.addUserModal').click(function(){
        $('#user-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });
        $('.dropify').dropify();
        $('.selectize-select').selectize();
    });

    var userActive = $('.chk_box');

    // $(userActive).on("change" , function() {
    $(document).delegate('input[name="userAccountStatus"]', 'change', function() {
        var user_id = $(this).data('id');
        var chk = $('#cur_' + user_id + ':checked').length;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('customer.changeStatus') }}",
            data: {
                _token: CSRF_TOKEN,
                value: chk,
                userId: user_id
            },
            success: function(response) {

                if (response.status == 'success') {
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    });

    $(".editVendor").click(function (e) {  
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var uid = $(this).attr('userId');

        $.ajax({
            type: "get",
            url: "<?php echo url('vendor'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function (data) {


                $('#edit-user-modal #editCardBox').html(data.html);
                $('#edit-user-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $("#user-modal #add_user").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.submitCustomerForm', function() { 
        var form =  document.getElementById('add_user');
        var formData = new FormData(form);
        var urls = "{{URL::route('customer.store')}}";
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

    function saveCustomer(urls, formData, inp = '', modal = ''){

         $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                    $("#" + modal + " .close").click();
                    location.reload(); 
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });

    }

    
</script>
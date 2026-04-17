<script>
   
    $('.addTaxCateModal').click(function(){
        $('#add-tax-category').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(".editTaxCateModal").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var uid = $(this).attr('userId');
        if(uid > 0){
            uri = "<?php echo url('client/tax'); ?>" + '/' + uid + '/edit';

        }

        
        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                $('#edit-tax-category #taxCategoryBox').html(data.html);
                    $('#edit-tax-category').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });
   
    $(document).on('click', '.submitAddTaxCate', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_tax_category');
        var formData = new FormData(form);
        var url =  "{{route('tax.store')}}";
        saveData(formData, '', url );

    });

    $(document).on('click', '.submitEditTaxCate', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('update_tax_category');
        var formData = new FormData(form);
        var url =  document.getElementById('tc_id').getAttribute('url');

        saveData(formData, 'Edit', url);

    });

    function saveData(formData, type, save_uri){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: save_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

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
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + type + " input").addClass("is-invalid");
                        $("#" + key + "Input" + type + " span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input" + type + " span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });
    }

    /*                       Tax Rate JS                    */

    $('.addTaxRateModal').click(function(){
        $('#add-tax-rate').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#add-tax-rate .select2-multiple').select2();
    });

    $(".editTaxRateModal").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var uid = $(this).attr('userId');
        if(uid > 0){
            uri = "<?php echo url('client/taxRate'); ?>" + '/' + uid + '/edit';

        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                $('#edit-tax-rate #taxRateBox').html(data.html);
                $('#edit-tax-rate').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#edit-tax-rate .select2-multiple').select2();
            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.postalSelect', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('update_tax_category');
        var formData = new FormData(form);
        var url =  document.getElementById('tc_id').getAttribute('url');

        saveData(formData, 'Edit', url);

    });

    $(document).on('change', '.postalSelect', function(){

        var val = $(this).val();
        var act = $(this).attr('for');
        if(val == 2){
            $('#singlePostal-' + act).hide();
            $('#multiPostal-' + act).show();
        }else if(val == 1){
            $('#singlePostal-' + act).show();
            $('#multiPostal-' + act).hide();
        }else{
            $('#singlePostal-' + act).hide();
            $('#multiPostal-' + act).hide();
        }
    });

    $(document).on('click', '.submitAddTaxRate', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_tax_rate');
        var formData = new FormData(form);
        var url =  "{{route('taxRate.store')}}";
        savetaxRate(formData, '', url );

    });

    $(document).on('click', '.submitEditTaxRate', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('update_tax_rate');
        var formData = new FormData(form);
        var url =  document.getElementById('tr_id').getAttribute('url');
        savetaxRate(formData, 'Edit', url);

    });

    function savetaxRate(formData, type, save_uri){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: save_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

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
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + type + " input").addClass("is-invalid");
                        $("#" + key + "Input" + type + " span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input" + type + " span.invalid-feedback").show();
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
<script type="text/javascript">

    summernoteInit();
    var regexp = /^[a-z0-9-_-]+$/;

    function alphaNumeric(evt) {
        var charCode = String.fromCharCode(event.which || event.keyCode);

        if (!regexp.test(charCode)) {
            return false;
        }
        var n2 = document.getElementById('slug');
        // n2.value = n2.value+charCode;
        return true;
    }

    function summernoteInit() {
        $('#warning_page_design').summernote({
            placeholder: 'Warning Page',
            tabsize: 2,
            height: 120,
        });
    }
    $(".openCategoryModal").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var uri = "{{route('category.create')}}";
        var id = $(this).attr('dataid');
        var is_vendor = $(this).attr('is_vendor');
        if (id > 0) {
            uri = "<?php echo url('client/category'); ?>" + '/' + id + '/edit';
        }
        $.ajax({
            type: "get",
            url: uri,
            data: {
                'is_vendor': is_vendor
            },
            dataType: 'json',
            success: function(data) {
                $("#p-error").empty();
                if (id > 0) {
                    $('#edit-category-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#edit-category-form #editCategoryBox').html(data.html);
                    setTimeout(function() {
                        $('input[name="type_id"]:checked').trigger('change');
                        $('input[name="warning_page_id"]:checked').trigger('change');
                        $('input[name="template_type_id"]:checked').trigger('change');
                    }, 1000);
                    element1 = document.getElementsByClassName('edit-switch_menu');
                    element2 = document.getElementsByClassName('edit-wishlist_switch');
                    element3 = document.getElementsByClassName('edit-add_product_switch');
                    element4 = document.getElementsByClassName('edit-is_category_visible_switch');
                    element5 = document.getElementsByClassName('edit-is_enabled_switch');
                    element6 = document.getElementsByClassName('edit-is_vendor_register_switch');
                    element7 = document.getElementsByClassName('edit-is_pickup_enabled_switch');
                    element8 = document.getElementsByClassName('edit-is_dropoff_enabled_switch');
                    element9 = document.getElementsByClassName('edit-is_schedule_enabled_switch');
                    element10 = document.getElementsByClassName('edit-is_image_upload_enabled_switch');
                    element11 = document.getElementsByClassName('edit-is_prohibited_item_enabled_switch');
                    element12 = document.getElementsByClassName('edit-is_product_price_show_switch');
                    element13 = document.getElementsByClassName('edit-is_msg_txt_mandatory_switch');
                    element14 = document.getElementsByClassName('edit-is_vehicle_number_required_switch');
                    element15 = document.getElementsByClassName('edit-is_show_products_in_popup_switch');
                    var switchery = new Switchery(element1[0]);
                    var switchery = new Switchery(element2[0]);
                    var switchery = new Switchery(element3[0]);
                    var switchery = new Switchery(element4[0]);
                    var switchery = new Switchery(element5[0]);
                    var switchery = new Switchery(element6[0]);
                    var switchery = new Switchery(element7[0]);
                    var switchery = new Switchery(element8[0]);
                    var switchery = new Switchery(element9[0]);
                    var switchery = new Switchery(element10[0]);
                    var switchery = new Switchery(element11[0]);
                    var switchery = new Switchery(element12[0]);
                    var switchery = new Switchery(element13[0]);
                    var switchery = new Switchery(element14[0]);
                    var switchery = new Switchery(element15[0]);
                    makeTag();
                    summernoteInit();
                } else {
                    $('#add-category-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#add-category-form #AddCategoryBox').html(data.html);
                    element1 = document.getElementsByClassName('switch_menu');
                    element2 = document.getElementsByClassName('wishlist_switch');
                    element3 = document.getElementsByClassName('add_product_switch');
                    element4 = document.getElementsByClassName('is_category_visible_switch');
                    element5 = document.getElementsByClassName('is_enabled_switch');
                    element6 = document.getElementsByClassName('is_pickup_enabled_switch');
                    element7 = document.getElementsByClassName('is_dropoff_enabled_switch');
                    element8 = document.getElementsByClassName('is_schedule_enabled_switch');
                    element9 = document.getElementsByClassName('is_image_upload_enabled_switch');
                    element10 = document.getElementsByClassName('is_prohibited_item_enabled_switch');
                    element11 = document.getElementsByClassName('is_product_price_show_switch');
                    element12 = document.getElementsByClassName('is_msg_txt_mandatory_switch');
                    element13 = document.getElementsByClassName('is_vehicle_number_required_switch');
                    element14 = document.getElementsByClassName('is_show_products_in_popup_switch');
                    var switchery = new Switchery(element1[0]);
                    var switchery = new Switchery(element2[0]);
                    var switchery = new Switchery(element3[0]);
                    var switchery = new Switchery(element4[0]);
                    var switchery = new Switchery(element5[0]);
                    var switchery = new Switchery(element6[0]);
                    var switchery = new Switchery(element7[0]);
                    var switchery = new Switchery(element8[0]);
                    var switchery = new Switchery(element9[0]);
                    var switchery = new Switchery(element10[0]);
                    var switchery = new Switchery(element11[0]);
                    var switchery = new Switchery(element12[0]);
                    var switchery = new Switchery(element13[0]);
                    var switchery = new Switchery(element14[0]);
                    makeTag();
                    summernoteInit();
                }
                $('.dropify').dropify();
                $('.selectize-select').selectize();
                
                // Add dropify event listeners for image removal detection
                $('.dropify').on('dropify.afterClear', function() {
                    var input = $(this);
                    var hasExistingImage = input.attr('data-has-existing-image') === 'true';
                    if (hasExistingImage) {
                        // Mark that the existing image was removed
                        input.attr('data-image-removed', 'true');
                        
                        // Add hidden input for removal flag
                        var fieldName = input.attr('name');
                        var removalFieldName = 'remove_' + fieldName;
                        var existingRemovalField = input.closest('[id$="InputEdit"], [id$="Input"]').find('input[name="' + removalFieldName + '"]');
                        
                        if (existingRemovalField.length === 0) {
                            input.closest('[id$="InputEdit"], [id$="Input"]').append('<input type="hidden" name="' + removalFieldName + '" value="1">');
                        } else {
                            existingRemovalField.val('1');
                        }
                        
                        // Show warning message only when image is actually removed
                        var warningDiv = input.closest('[id$="InputEdit"], [id$="Input"]').find('.image-removed-warning');
                        if (warningDiv.length === 0) {
                            input.closest('[id$="InputEdit"], [id$="Input"]').append('<div class="image-removed-warning alert alert-warning mt-2"><i class="fas fa-exclamation-triangle"></i> {{ __("Warning: Image has been removed and will be deleted from storage. You can leave it empty or upload a new image.") }}</div>');
                        }
                    }
                });
                
                // Reset the removed flag when a new image is selected
                $('.dropify').on('change', function() {
                    var input = $(this);
                    if (input[0].files.length > 0) {
                        input.attr('data-image-removed', 'false');
                        
                        // Remove removal flag
                        var fieldName = input.attr('name');
                        var removalFieldName = 'remove_' + fieldName;
                        var removalField = input.closest('[id$="InputEdit"], [id$="Input"]').find('input[name="' + removalFieldName + '"]');
                        if (removalField.length > 0) {
                            removalField.val('0');
                        }
                        
                        // Hide warning message
                        input.closest('[id$="InputEdit"], [id$="Input"]').find('.image-removed-warning').remove();
                    }
                });
                
                // Also reset the removed flag when the form is loaded (in case of edit)
                $('.dropify').each(function() {
                    var input = $(this);
                    var hasExistingImage = input.attr('data-has-existing-image') === 'true';
                    if (hasExistingImage) {
                        // Initially, no image is removed
                        input.attr('data-image-removed', 'false');
                        
                        // Add hidden input for removal flag (initially set to 0)
                        var fieldName = input.attr('name');
                        var removalFieldName = 'remove_' + fieldName;
                        var existingRemovalField = input.closest('[id$="InputEdit"], [id$="Input"]').find('input[name="' + removalFieldName + '"]');
                        
                        if (existingRemovalField.length === 0) {
                            input.closest('[id$="InputEdit"], [id$="Input"]').append('<input type="hidden" name="' + removalFieldName + '" value="0">');
                        }
                    }
                });

            },
            error: function(data) {
                $("#p-error").empty();
                console.log('data2');
            }
        });
    });
    $(document).on('click', '.addCategorySubmit', function(e) {
        e.preventDefault();
        var form = document.getElementById('addCategoryForm');
        var formData = new FormData(form);
        var url = "{{route('category.store')}}";
        saveCategory(formData, '', url);

    });
    $(document).on('change', '.type-select', function() {
        var id = $(this).val();
        var for1 = $(this).attr('for');
        $('#warning_page_main_div').hide();
        $('#template_type_main_div').hide();
        $('#warning_page_design_main_div').hide();
        if (id == '1') {
            $("#" + for1 + "-category-form #" + for1 + "ProductHide").show();
            $("#" + for1 + "-category-form #" + for1 + "DispatcherHide").hide();
            $('.pickup-dropoff-section').hide();
        } else if (id == '2') {
            $("#" + for1 + "-category-form #" + for1 + "ProductHide").hide();
            $("#" + for1 + "-category-form #" + for1 + "DispatcherHide").show();
            $('.pickup-dropoff-section').hide();
        } else if (id == '3') {
            $("#" + for1 + "-category-form #" + for1 + "ProductHide").show();
            $("#" + for1 + "-category-form #" + for1 + "DispatcherHide").hide();
            $('.pickup-dropoff-section').hide();
        } else if (id == '7') {
            $('#warning_page_main_div').show();
            $('#template_type_main_div').show();
            $('#warning_page_design_main_div').show();
            $("#" + for1 + "-category-form #" + for1 + "DispatcherHide").hide();
            $('.pickup-dropoff-section').hide();
        } else if (id == '10') {
            $("#" + for1 + "-category-form #" + for1 + "ProductHide").show();
            $("#" + for1 + "-category-form #" + for1 + "DispatcherHide").hide();
            $('.pickup-dropoff-section').show();
        } else {
            $("#" + for1 + "-category-form #" + for1 + "ProductHide").hide();
            $("#" + for1 + "-category-form #" + for1 + "DispatcherHide").hide();
            $('.pickup-dropoff-section').hide();
        }
    });
    $(document).on('change', '#warningPageSelectBox', function() {
        if ($('input[name="type_id"]:checked').val() == '7') {
            $('#warning_page_design_main_div').show();
        }
    });
    $(document).on('click', '.editCategorySubmit', function(e) {
        e.preventDefault();
        var form = document.getElementById('editCategoryForm');
        var formData = new FormData(form);
        var url = document.getElementById('cateId').getAttribute('url');
        saveCategory(formData, 'Edit', url);
    });

    function saveCategory(formData, type, base_uri) {
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
            url: base_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload();
                } else if (response.status == 'error1') {
                    $("#p-error1").empty();
                    $("#p-error").empty();
                    $("#p-error1").append("*!Cannot create a sub-category of product type of category.");                   
                    $("#p-error").append("*!Cannot create a sub-category of product type of category.");
                    $(".show_all_error.invalid-feedback").show();
                } else if (response.status == 'error2') {
                    $("#p-error1").empty();
                    $("#p-error1").append("*!Either delete the sub categories of this category or do  not change type to product.");
                    $(".show_all_error.invalid-feedback").show();
                } else {
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        if (key == 'name.0') {
                            var valiField = 'nameInput' + type;
                            $("#" + valiField + " input").addClass("is-invalid");
                            $("#nameInput" + type + " span.invalid-feedback").children("strong").text('The default language name field is required.');
                            $("#nameInput" + type + " span.invalid-feedback").show();
                        } else {
                            var valiField = key + 'Input' + type;
                            $("#" + valiField + " input").addClass("is-invalid");
                            $("#" + valiField + " span.invalid-feedback").children("strong").text(errors[key][0]);
                            $("#" + valiField + " span.invalid-feedback").show();
                        }
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
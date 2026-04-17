<script>

    $(".openCategoryModal").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uri = "{{route('category.create')}}";
       
        var id = $(this).attr('dataid');
        if(id > 0){
            uri = "<?php echo url('client/category'); ?>" + '/' + id + '/edit';

        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                if(id > 0){
                    $('#edit-category-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#edit-category-form #editCategoryBox').html(data.html);
                    elems1 = document.getElementsByClassName('switch1Edit');
                    elems2 = document.getElementsByClassName('switch2Edit');
                    var switchery = new Switchery(elems1[0]);
                    var switchery = new Switchery(elems2[0]);

                }else{

                    $('#add-category-form').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#add-category-form #AddCategoryBox').html(data.html);
                    elems1 = document.getElementsByClassName('switch1');
                    elems2 = document.getElementsByClassName('switch2');
                    var switchery = new Switchery(elems1[0]);
                    var switchery = new Switchery(elems2[0]);

                }

                $('.dropify').dropify();
                $('.selectize-select').selectize(); 
                
            },
            error: function (data) {
                console.log('data2');
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

    $(document).on('click', '.addCategorySubmit', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('addCategoryForm');
        var formData = new FormData(form);
        var url =  "{{route('category.store')}}";
        saveData(formData, 'add', url );

    });

    $(document).on('click', '.editCategorySubmit', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('editCategoryForm');
        var formData = new FormData(form);
        var url =  document.getElementById('cateId').getAttribute('url');

        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, base_uri){
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
                        if(key == 'name.0'){
                            $("#nameInput input").addClass("is-invalid");
                            $("#nameInput span.invalid-feedback").children("strong").text('The default language name field is required.');
                            $("#nameInput span.invalid-feedback").show();
                        }else{
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                            $("#" + key + "Input span.invalid-feedback").show();
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

    

    $("#banner-datatable tbody").sortable({
        placeholder : "ui-state-highlight",
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
            }
        });
    }


    /*          Variant Modals Started      */

    /*addOptionRow: function (isNullOptionRow) {
                    const rowCount = this.optionRowCount++;
                    const id = 'option_' + rowCount;
                    let row = {'id': id};

                                            row['en'] = '';
                                            row['fr'] = '';
                                            row['nl'] = '';
                    
                    row['notRequired'] = '';

                    if (isNullOptionRow) {
                        this.idNullOption = id;
                        row['notRequired'] = true;
                    }

                    this.optionRows.push(row);
                },

                removeRow: function (row) {
                    if (row.id === this.idNullOption) {
                        this.idNullOption = null;
                        this.isNullOptionChecked = false;
                    }

                    const index = this.optionRows.indexOf(row);
                    Vue.delete(this.optionRows, index);
                },*/

    $(".addVariantbtn").click(function (e) {
        $('#addVariantmodal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $(".EditVariantbtn").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uri = "{{route('variant.create')}}";
       
        var id = $(this).attr('dataid');
        if(id > 0){
            uri = "<?php echo url('client/variant'); ?>" + '/' + id + '/edit';

        }

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                if(id > 0){
                    $('#editVariantmodal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#editVariantmodal #editVariantBox').html(data.html);

                }else{
                    
                    $('#addVariantmodal #AddVariantBox').html(data.html);
                }

                $('.dropify').dropify();
                $('.selectize-select').selectize(); 
                
            },
            error: function (data) {
                console.log('data2');
            }
        });
    });
</script>
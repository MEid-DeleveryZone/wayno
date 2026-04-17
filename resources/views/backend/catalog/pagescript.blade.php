<script>
    var options = {
        zIndex: 9999
    };
    $(document).on('change', '.assignToSelect', function() {
        var val = $(this).val();
        if (val == 'category') {
            $('.modal .category_vendor').show();
            $('.modal .category_list').show();
            $('.modal .vendor_list').hide();
        } else if (val == 'vendor') {
            $('.modal .category_vendor').show();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').show();
        } else {
            $('.modal .category_vendor').hide();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').hide();
        }
    });

    $("#banner-datatable tbody").sortable({
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            var post_order_ids = new Array();
            $('#post_list tr').each(function() {
                post_order_ids.push($(this).data("row-id"));
            });
            console.log(post_order_ids);
            saveOrder(post_order_ids);
        }
    });
    var CSRF_TOKEN = $("input[name=_token]").val();

    function saveOrder(orderVal) {
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
                if (response.status == 'success') {}
                return response;
            }
        });
    }
    $(".addVariantbtn").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var did = $(this).attr('dataid');
        $.ajax({
            type: "get",
            url: "{{ route('variant.create') }}",
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#addVariantmodal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#addVariantForm #AddVariantBox').html(data.html);
                $('.dropify').dropify();
                $('.selectize-select').selectize();

                var picker = new jscolor('#add-hexa-colorpicker-1', options);
            },
            error: function(data) {
                console.log('data2');
            }
        });

    });
    $('.editVariantBtn').on('click', function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var did = $(this).attr('dataid');
        $.ajax({
            type: "get",
            url: "<?php echo url('client/variant'); ?>" + '/' + did + '/edit',
            data: '',
            dataType: 'json',
            beforeSend: function() {
                $(".loader_box").show();
            },
            success: function(data) {
                $('#editVariantmodal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#editVariantForm #editVariantBox').html(data.html);
                $('.dropify').dropify();
                $('.selectize-select').selectize();
                $("#editVariantForm .hexa-colorpicker").each(function() {
                    var ids = $(this).attr('id');
                    try {
                        var picker = new jscolor('#' + ids, options);
                    } catch (err) {
                        console.log(err.message);
                    }
                });
                var getURI = document.getElementById('submitEditHidden').value;
                document.getElementById('editVariantForm').action = data.submitUrl;
            },
            error: function(data) {
                console.log('data2');
            },
            complete: function() {
                $('.loader_box').hide();
            }
        });
    });
    $(document).on('click', '.addOptionRow-Add', function(e) {
        var d = new Date();
        var n = d.getTime();
        var $tr = $('.optionTableAdd tbody>tr:first').next('tr');
        console.log('asasd');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find('.hexa-colorpicker').attr("id", "hexa-colorpicker-" + n);
        $clone.find('.lasttd').html(
            '<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>'
        );
        $('.optionTableAdd').append($clone);
        var picker = new jscolor("#hexa-colorpicker-" + n, options);
    });

    $(document).on('click', '.addOptionRow-edit', function(e) {
        var d = new Date();
        var n = d.getTime();
        var $tr = $('.optionTableEdit tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find(':hidden').val('');
        $clone.find('.hexa-colorpicker').attr("id", "hexa-colorpicker-" + n);
        $clone.find('.lasttd').html(
            '<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>'
        );
        $('.optionTableEdit').append($clone);
        $('.hexa-colorpicker').colorpicker();
        var picker = new jscolor("#hexa-colorpicker-" + n, options);
    });

    $("#addVariantmodal").on('click', '.deleteCurRow', function() {
        $(this).closest('tr').remove();
    });

    $("#editVariantmodal").on('click', '.deleteCurRow', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.deleteVariant', function() {
        var did = $(this).attr('dataid');
        if (confirm("Are you sure? You want to delete this variant.")) {
            $('#varDeleteForm' + did).submit();
        }
        return false;
    });




    $("#varient-datatable tbody").sortable({
        placeholder: "ui-state-highlight",
        handle: ".dragula-handle",
        update: function(event, ui) {}
    });

    $('.saveBrandOrder').on('click', function(e) {
        //alert(1);
        var var_ids = new Array();
        $(".brandList").each(function() {
            var_ids.push($(this).data("row-id"));
        });
    });

    $(document).on('change', '.dropDownType', function() {
        var did = $(this).val();
        var dataFor = $(this).attr('dataFor');
        if (did == 1) {
            $('#' + dataFor + 'Variantmodal .hexacodeClass-' + dataFor).hide();
        } else {
            $('#' + dataFor + 'Variantmodal .hexacodeClass-' + dataFor).show();
        }
    });

    $('.saveVariantOrder').on('click', function(e) {
        var var_ids = new Array();
        $(".variantList").each(function() {
            var_ids.push($(this).data("row-id"));
        });
        document.getElementById('orderVariantData').value = var_ids;
        $('#variant_order').submit();
    });

    $('.addBrandbtn').on('click', function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var did = $(this).attr('dataid');
        $.ajax({
            type: "get",
            url: "{{ route('brand.create') }}",
            data: '',
            dataType: 'json',
            success: function(data) {

                $('#addBrandmodal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#addBrandForm #AddbrandBox').html(data.html);
                $('.dropify').dropify();
                $('.selectize-select').selectize();

            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
    $(document).ready(function() {

        $('#addVariantmodal .selectize-select').selectize();
        $('#addBrandmodal .selectize-select').selectize();

    });

    $("#brand-datatable tbody").sortable({
        placeholder: "ui-state-highlight",
        handle: ".dragula-handle",
        update: function(event, ui) {}
    });
    $(document).on('click', '.deleteBrand', function() {
        var did = $(this).attr('dataid');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('brandProductsCheck') }}",
            data: {
                id: did
            },
            success: function(response) {
                if (response.success) {
                    if (confirm("Are you sure? You want to delete this brand." + response.msg)) {
                        $('#brandDeleteForm' + did).submit();
                    }
                }

                return false;
            }

        });

    });
    $('.saveBrandOrder').on('click', function(e) {
        var var_ids = new Array();
        $(".brandList").each(function() {
            var_ids.push($(this).data("row-id"));
        });
        document.getElementById('orderBrandData').value = var_ids;
        $('#brand_order').submit();
    });
    $('.editBrandBtn').on('click', function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var did = $(this).attr('dataid');
        $.ajax({
            type: "get",
            url: "<?php echo url('client/brand'); ?>" + '/' + did + '/edit',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#editBrandmodal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#editBrandForm #editBrandBox').html(data.html);
                $('.dropify').dropify();
                $('.selectize-select').selectize();

                // $("#cateSelectBox")[0].selectize.clear();
                //$('#cateSelectBox option:selected')[0].selectize.clear();

                document.getElementById('editBrandForm').action = data.submitUrl;


            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
</script>

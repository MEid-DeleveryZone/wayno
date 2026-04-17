<script src="{{ asset('js/validate.min.js') }}"></script>
<script>
    $('.openAddModal').click(function() {
        $('#add-form').modal({
            //backdrop: 'static',
            keyboard: false
        });
        //runPicker();
        $('.dropify').dropify();
        $('.selectize-select').selectize();
        autocompletesWraps.push('add');
        loadMap(autocompletesWraps);
    });

    $('.openImportModal').click(function() {
        $('#import-form').modal({
            //backdrop: 'static',
            keyboard: false
        });
        //runPicker();
        $('.dropify').dropify();
    });

    function runPicker() {
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
    }

    var autocomplete = {};
    var autocompletesWraps = [];
    var count = 1;
    editCount = 0;
    var infowindow = new google.maps.InfoWindow();
    var geocoder   = new google.maps.Geocoder();
    $(document).ready(function() {
        autocompletesWraps.push('def');
        loadMap(autocompletesWraps);
    });

    function loadMap(autocompletesWraps) {

        // console.log(autocompletesWraps);
        $.each(autocompletesWraps, function(index, name) {
            //const geocoder = new google.maps.Geocoder;

            if ($('#' + name).length == 0) {
                return;
            }
            var options = {
                types: ["establishment"], 
                componentRestrictions: {country: "ae"}
            };
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name + "-address"), options);
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {

                var place = autocomplete[name].getPlace();

                geocoder.geocode({
                    'placeId': place.place_id
                }, function(results, status) {

                    if (status === google.maps.GeocoderStatus.OK) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        document.getElementById(name + '_latitude').value = lat;
                        document.getElementById(name + '_longitude').value = lng;
                    }
                });
            });
        });
    }
    $('#show-map-modal').on('hide.bs.modal', function() {
        $('#add-customer-modal').removeClass('fadeIn');

    });

    $(document).on('click', '.showMap', function() {
        var no = $(this).attr('num');
        console.log(no);

        var lats = document.getElementById(no + '_latitude').value;
        var lngs = document.getElementById(no + '_longitude').value;
        console.log(lats + '--' + lngs);

        document.getElementById('map_for').value = no;

        if (lats == null || lats == 0) {
            lats = 24.4539;
        }
        if (lngs == null || lngs == 0) {
            lngs = 54.3773;
        }

        var myLatlng = new google.maps.LatLng(lats, lngs);
        var mapProp = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP

        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Hello World!',
            draggable: true
        });
        document.getElementById('lat_map').value = lats;
        document.getElementById('lng_map').value = lngs;
        // marker drag event
        google.maps.event.addListener(marker, 'drag', function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
        });

        //marker drag event end
        google.maps.event.addListener(marker, 'dragend', function(event) {
            var zx = JSON.stringify(event);
            console.log(zx);

            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
            //alert("lat=>"+event.latLng.lat());
            //alert("long=>"+event.latLng.lng());
            geocoder.geocode({
                'latLng': marker.getPosition()
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            document.getElementById('address_map').value= results[0].formatted_address;
                            infowindow.setContent(results[0].formatted_address);
                            infowindow.open(map, marker);
                        }
                    }
                });
            });
        $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $(document).on('click', '.selectMapLocation', function() {

        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;
        var address = document.getElementById('address_map').value;

        document.getElementById(mapFor + '_latitude').value = mapLat;
        document.getElementById(mapFor + '_longitude').value = mapLlng;
        document.getElementById(mapFor + '-address').value = address;

        $('#show-map-modal').modal('hide');
    });

    $(".openEditModal").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var uri = "{{ isset($vendor) ? route('vendor.edit', $vendor->id) : '' }}";
        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-form').modal('show');
                $('#edit-form #editCardBox').html(data.html);
                $('.selectize-select').selectize();
                $('.dropify').dropify();
                dine = document.getElementsByClassName('dine_in');
                var switchery = new Switchery(dine[0]);
                take = document.getElementsByClassName('takeaway');
                var switchery = new Switchery(take[0]);
                delivery = document.getElementsByClassName('delivery');
                var switchery = new Switchery(delivery[0]);
                autocompletesWraps.push('edit');
                loadMap(autocompletesWraps);
                // },
                // error: function (data) {
                //     console.log('data2');
                // },
                // beforeSend: function(){
                //     $(".loader_box").show();
                // },
                // complete: function(){
                //     $(".loader_box").hide();
            }
        });
    });

    function submitProductImportForm(files) {
        modalFooter = $('.import-product-footer');
        // Check for the various File API support.
        if (window.FileReader) {
            // FileReader are supported.
            getAsText(files[0]);
            var form = document.getElementById('save_imported_products');
            var formData = new FormData(form);
            var data_uri = "{{route('product.import')}}";
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
                url: data_uri,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('.alert-danger').hide();
                    modalFooter.html('');
                    if (response.status == 'success') {
                        // alert($('#product-table tr').length-1)
                        modalFooter.html(`<button class="btn btn-info upload-import-btn" onclick="uploadProductImportFile()">Upload (<b>${$('#product-table tr').length-1} Items</b>)</button>`);
                    }else if(response.status == 'validation_failed'){
                        $('.alert-danger').empty();
                        // $('.alert-danger').show();
                        $('.alert-danger').append('<p>' + response.error + '</p>').show();
                    } else {
                        appendColumn(response.error);
                        // $(".show_all_error.invalid-feedback").show();
                        // $(".show_all_error.invalid-feedback").text(response.message);
                    }
                    return response;
                },
                beforeSend: function() {
                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                }
            });
        } else {
            alert('FileReader are not supported in this browser.');
        }
    }
    function uploadProductImportFile() {
        var form = document.getElementById('save_imported_products');
        var formData = new FormData(form);
        var data_uri = "{{route('product.importUpload')}}";
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
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('.alert-danger').hide();
                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload();
                }else if(response.status == 'validation_failed'){
                    $('.alert-danger').empty();
                    $('.alert-danger').show();
                    $('.alert-danger').append('<p>' + response.error + '</p>');
                } else {
                    appendColumn(response.error);
                    // $(".show_all_error.invalid-feedback").show();
                    // $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            beforeSend: function() {

                $(".loader_box").show();
            },
            complete: function() {

                $(".loader_box").hide();
            }
        });
    }
    // append column to the HTML table
    function appendColumn(error) {
        // var tr = document.getElementById('product-table').tHead.lastChild,
        // th = document.createElement('th');
        // th.innerHTML = "Errors";
        // th.setAttribute('class', 'text-danger');  
        // tr.after(th);
        $('#product-table tr:first').append('<th>Error(s)</th>');
        var tbl = document.getElementById('product-table'), // table reference
        i;
        
        // open loop for each row and append cell
        for (i = 0; i < tbl.rows.length; i++) {
            if(i != 0){
                //$('#product-table tr').append('<td>fffff</td>');
                createCell(tbl.rows[i].insertCell(tbl.rows[i].cells.length), error[i], i);
            }
        }
    } 
    // create DIV element and append to the table cell
    function createCell(cell, text, i) {
        const ul = document.createElement("OL");
        $.each(text, function(j, row) {
            txt = document.createTextNode(row); // create text node
            li = document.createElement("LI");
            li.appendChild(txt);    
            ul.appendChild(li);    
            $( "#import_"+i+"_"+j ).addClass( "bg-danger-light" );
        })
        //ul.appendChild(txt);                   // append DIV to the table cell
        cell.appendChild(ul);                   // append DIV to the table cell
        cell.setAttribute('class', 'text-danger');  
    }
    function getAsText(fileToRead) {
        var reader = new FileReader();
        // Handle errors load
        reader.onload = loadHandler;
        reader.onerror = errorHandler;
        // Read file into memory as UTF-8      
        reader.readAsText(fileToRead);
    }

    function loadHandler(event) {
        var csv = event.target.result;
        processData(csv);
    }

    function processData(csv) {
        var allTextLines = csv.split(/\r\n|\n/);
        var lines = [];
        while (allTextLines.length-1) {
        //for (var i = 0; i <= allTextLines.length; i++) {  
            lines.push(allTextLines.shift().split(','));
        }
        console.log(lines);
        drawOutputJquery(lines);
    }

    function errorHandler(evt) {
        if (evt.target.error.name == "NotReadableError") {
            alert("Cannot read file !");
        }
    }

    function drawOutputJquery(lines) {
        //Clear previous data
        $("#output").html('');
        tableHTML = `<table class="table table-centered table-nowrap table-striped" id="product-table">`;
        rowcount = 0;
            $.each(lines, function(i, row) {
            tableHTML += `<tr>`;
            th_or_td = i == 0 ? 'th' : 'td';
             if(i!=0){
                rowcount++;
            } 
            $.each(row, function(j, column) {
                columnHTML = column;
                col_row_id = `import_${i}_${j}`;
                if(j==0){ //row count/serial number 
                    tableHTML += `<${th_or_td}>${i==0 ? '#' : rowcount}</${th_or_td}>
                    <${th_or_td} id="${col_row_id}">${columnHTML}</${th_or_td}>`;
                }else{
                    if (i != 0 && j == 17 || i != 0 && j == 20) {
                        columnHTML = `<img src="${$("#proxy_url").val()}30/30${$("#image_path").val()}/${column}">`;
                    }
                    tableHTML += `<${th_or_td} id="${col_row_id}">${columnHTML}</${th_or_td}>`;

                }

            })
            tableHTML += `</tr>`;
        });
        tableHTML += `</table>`;
        $('#output').html(tableHTML);
    }

    function drawOutput(lines) {
        //Clear previous data
        document.getElementById("output").innerHTML = "";
        var table = document.createElement("table");
        table.setAttribute('class', 'table table-centered table-nowrap table-striped');
        table.setAttribute('id', 'product-table');
        for (var i = 0; i < lines.length; i++) {
            var row = table.insertRow(-1);
            const head = document.createElement('thead')
            for (var j = 0; j < lines[i].length; j++) {
                if(i == 0){
                    // Create th tags for an example.
                    const th1 = document.createElement('th')
                    th1.appendChild(document.createTextNode(lines[i][j]))
                    // Add the th tags to the thead
                    head.appendChild(th1)
                    // Give the table the thead.
                    table.appendChild(head)
                }else{ 
                    var firstNameCell = row.insertCell(-1);
                    if(i != 0 && j== 17 || i != 0 && j== 20){
                        var proxy_url = $("#proxy_url").val();
                        var image_path = $("#image_path").val();
                        var img = document.createElement('img');
                        img.src = `${proxy_url}30/30${image_path}/${lines[i][j]}`;
                        firstNameCell.appendChild(img);
                    }else{
                        firstNameCell.appendChild(document.createTextNode(lines[i][j]));
                    }
                }
            }
        }
        document.getElementById("output").appendChild(table);
    }
    function submitImportForm() {
        var form = document.getElementById('save_imported_vendors');
        var formData = new FormData(form);
        var data_uri = "{{route('vendor.import')}}";
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
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                location.reload();
                if (response.status == 'success') {
                    // $("#import-form").modal('hide');
                    $('#p-message').empty();
                    $('#p-message').append('Document uploaded Successfully!');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);

                } else {
                    $('#p-message').empty();
                    $('#p-message').append('Document uploading Failed!');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);

                }
                return response;
            },
            beforeSend: function() {
                $('#p-message').empty();
                $('#p-message').append('Document uploading!');

                setTimeout(function() {
                    location.reload();
                }, 2000);

                $(".loader_box").show();
            },
            complete: function() {
                $('#p-message').empty();
                $('#p-message').append('Document uploading!');
                setTimeout(function() {
                    location.reload();
                }, 2000);


                $(".loader_box").hide();
            }
        });
    }

    $(document).on('click', '.submitAddForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_banner_form');
        var formData = new FormData(form);
        var url = "{{route('vendor.store')}}";
        $('#save_banner_form').validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                password:{
                    minlength: 6,
                    maxlength: 30,
                    required: true,
                },
                phone_number: {
                    required: true,
                    minlength: 9,
                    maxlength: 15,
                    digits: true
                },
                address: {
                    required: true,
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        if ($("#save_banner_form").valid()) {
            saveData(formData, 'add', url);
        }

    });

    $(document).on('click', '.submitEditForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_edit_banner_form');
        var formData = new FormData(form);
        var url = "{{ isset($vendor) ? route('vendor.update', $vendor->id) : ''}}";

        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, data_uri) {
        console.log(data_uri);
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
            url: data_uri,
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
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
            error: function(response) {
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
            }
        });
    }
    $(".openAddonModal").click(function(e) {
        $('#addAddonmodal').modal({
            backdrop: 'static',
            keyboard: false
        });
        var slider = $("#slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#banner-datatable >tbody >tr.input_tr').length;
        slider.update({
            grid: false,
        });
    });
    $(document).on('click', '.addOptionRow-Add', function(e) {
        var $tr = $('.optionTableAdd tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
        $('.optionTableAdd').append($clone);
        var slider = $("#slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#banner-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });

    $(document).on('click', '.addOptionRow-edit', function(e) {
        var $tr = $('.optionTableEdit tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find(':hidden').val('');
        $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
        $('.optionTableEdit').append($clone);
        var slider = $("#slider-range1").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#edit_addon-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });
    $("#addAddonmodal").on('click', '.deleteCurRow', function() {
        var slider = $("#addAddonmodal #slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#addAddonmodal #banner-datatable >tbody >tr.input_tr').length - 1;
        slider.update({
            min: from,
            max: to,
        });
        $(this).closest('tr').remove();
        var slider = $("#slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#banner-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });

    $("#editdAddonmodal").on('click', '.deleteCurRow', function() {
        var slider = $("#editdAddonmodal #slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#editdAddonmodal #edit_addon-datatable >tbody >tr.input_tr').length - 1;
        if (to == 1) {
            from = 0;
        }
        slider.update({
            min: from,
            max: to,
        });
        $(this).closest('tr').remove();
        var slider = $("#slider-range1").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#edit_addon-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });

    $(document).on('click', '.deleteAddon', function() {

        var did = $(this).attr('dataid');
        if (confirm("Are you sure? You want to delete this addon set.")) {
            $('#addonDeleteForm' + did).submit();
        }
        return false;
    });

    $('.editAddonBtn').on('click', function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var did = $(this).attr('dataid');
        $.ajax({
            type: "get",
            url: "<?php echo url('client/addon'); ?>" + '/' + did + '/edit',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#editdAddonmodal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#editAddonForm #editAddonBox').html(data.html);
                $('#editdAddonmodal .modal-title').html('Edit AddOn Set');
                $('#editdAddonmodal .editAddonSubmit').html('Update');
                document.getElementById('editAddonForm').action = data.submitUrl;
                setTimeout(function() {
                    var max = $('#edit_addon-datatable >tbody >tr.input_tr').length;
                    var $d4 = $("#editAddonForm #slider-range1");
                    $d4.ionRangeSlider({
                        type: "double",
                        grid: false,
                        min: 0,
                        max: max,
                        from: data.min_select,
                        to: data.max_select
                    });
                    $d4.on("change", function() {
                        var $inp = $(this);
                        $("#editAddonForm #max_select").val($inp.data("to"));
                        $("#editAddonForm #min_select").val($inp.data("from"));
                    });
                }, 1000);
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });

    ///// **************** 1.1  check vendor exists in dispatcher or not for pickup********** //////////

    $(".openConfirmDispatcher").click(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        e.preventDefault();


        var uri = "{{route('update.Create.Vendor.In.Dispatch')}}";
        var id = $(this).data('id');

        $.ajax({
            type: "post",
            url: uri,
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                var url = data.url;
                window.open(url, '_blank');
            },
            error: function(data) {
                alert(data.message);
            },
            beforeSend: function() {
                $(".loader_box").show();
                var token = $('meta[name="csrf_token"]').attr('content');
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            complete: function() {
                $(".loader_box").hide();
            }
        });
    });
    /////////////// **************   end 1.1 *****************************///////////////

    ///// **************** 1.2  check vendor exists in dispatcher or not for on demand********** //////////

    $(".openConfirmDispatcherOnDemand").click(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        e.preventDefault();


        var uri = "{{route('update.Create.Vendor.In.Dispatch.OnDemand')}}";
        var id = $(this).data('id');

        $.ajax({
            type: "post",
            url: uri,
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                var url = data.url;
                window.open(url, '_blank');
            },
            error: function(data) {
                alert(data.message);
            },
            beforeSend: function() {
                $(".loader_box").show();
                var token = $('meta[name="csrf_token"]').attr('content');
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            complete: function() {
                $(".loader_box").hide();
            }
        });
    });
    /////////////// **************   end 1.2 *****************************///////////////
</script>
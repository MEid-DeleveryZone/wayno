        <div class="d-flex align-items-center">
            <label class="order-items d-flex align-items-center" for="item_one">
                <div class="item-img mr-1">
                    <img src="{{ $return_details->product->image['proxy_url'] . '74/100' . $return_details->product->image['image_path'] }}"
                        alt="">
                </div>
                <div class="items-name ml-2">
                    <h4 class="mt-0 mb-1"><b>{{ $return_details->product->product_name }}</b></h4>
                    <label><b>Quantity</b>: {{ $return_details->product->quantity }}</label>
                </div>
            </label>
        </div>

        <form id="return-upload-form" class="theme-form" action="javascript:void(0)" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$return_details->id}}">
            <input type="hidden" name="return_ref_id" value="{{$return_details->return_ref_id}}">
            @if (isset($return_details->returnFiles))
            <div class="row form-group rating_files">
                <div class="col-12">
                    <label>Uploaded Files</label>
                </div>

                <div class="col-10">
                    <span class="row show-multiple-image-preview" id="thumb-output">
                        @if (isset($return_details->returnFiles))
                            @foreach ($return_details->returnFiles as $files)
                                <img class="col-6 col-md-3 update_pic"
                                    src="{{ $files->file['proxy_url'] . '300/300' . $files->file['image_path'] }}">
                            @endforeach
                        @endif
                    </span>
                </div>

            </div>
            @endif


            <div class="form-group">
                    <label>{{ __('Reason for return product :') }}</label> {{ $return_details->reason }}
            </div>
            @if(isset($return_details->coments))
            <div class="form-group">
                <label>Comments :</label>
                <p>{{ $return_details->coments ?? '' }}</p>
            </div>
            @endif
            <div class="row form-group">
                <div class="col-md-12">
                    <label>{{__('Update Status')}}</label>
                </div>
                <div class="col-md-12">
                    <select class="form-control" name="status">
                       <option value="Pending" @if($return_details->status == 'Pending') selected="selected" @endif>Pending</option>
                       <option value="Accepted" @if($return_details->status == 'Accepted') selected="selected" @endif>Accepted</option>
                       <option value="Rejected" @if($return_details->status == 'Rejected') selected="selected" @endif>Rejected</option>
                    </select>
                </div>     
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <label>{{__('Refund Account')}} : </label>
                    @if($return_details->refund_account == 1)
                        <span class="badge badge-danger">Wallet</span>
                    @elseif($return_details->refund_account == 2)
                        <span class="badge badge-danger">Card</span>
                    @endif
                </div>     
            </div>

            <div class="form-group">
                <label>{{__('Comment By Vendor (Optional)')}}:</label>
                <textarea class="form-control" name="reason_by_vendor" id="reason_by_vendor" cols="20" rows="4">{{$return_details->reason_by_vendor}}</textarea>
            </div>

            <span class="text-danger" id="error-msg"></span>
            <span class="text-success" id="success-msg"></span>
            <button class="btn btn-primary w-100 mt-3" id="return_form_button">Update</button>
        </form>


<script type="text/javascript">
    $(document).ready(function(e) {
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#return-upload-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('update.order.return.client') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                   $("#return_form_button").html(
                            '<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop(
                            'disabled', true);
                },
                success: (data) => {
                    if (data.status == 'Success') {
                       $("#return_form_button").html('Submitted');
                       location.reload();
                    } else {
                        $('#error-msg').text(data.message);
                        $("#return_form_button").html('Update').prop('disabled',
                            false);
                    }
                },
                error: function(data) {
                    $('#error-msg').text(data.message);
                    $("#return_form_button").html('Update').prop('disabled',
                        false);
                }
            });
        });

    });

</script>

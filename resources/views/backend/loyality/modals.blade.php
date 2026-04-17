
 @php
 $Loyalty_Cards = getNomenclatureName('Loyalty Cards', false);
 $newLoyalty_Cards = ($Loyalty_Cards === 'Loyalty Cards') ? __('Loyalty Cards') : $Loyalty_Cards ;
 @endphp


<div class="modal fade bd-example-modal-lg addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header border-0 p-0 mb-3">
                <h4 class="modal-title">{{ __('Add') }} {{ $newLoyalty_Cards }}</h4><br>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_loyality_form">
                @csrf
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>{{ __("Upload Image") }}</label>
                        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                        <label class="logo-size text-right w-100">{{ __('Image Size') }} 120x120</label>
                    </div> 
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="minimum_pointsInput">
                            {!! Form::label('title', __('Minimum Points to reach this level *'),['class' => 'control-label']) !!}
                            {!! Form::text('minimum_points', null, ['class' => 'form-control']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="descriptionInput">
                            {!! Form::label('title', __('Description *'),['class' => 'control-label']) !!}
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">{{ __('Earnings') }}</h5>

                <div class="form-group" id="per_order_pointsInput">
                    {!! Form::label('title', __('Earnings Per Order*'),['class' => 'control-label']) !!}
                    {!! Form::text('per_order_points', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
                <!-- <div class="form-group" id="per_purchase_minimum_amountInput">
                    {!! Form::label('title', 'Minimum Order Amount to redeem Purchase Points*',['class' => 'control-label']) !!}
                    {!! Form::text('per_purchase_minimum_amount', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div> -->
                <br>

                <label for="purchase">{{ __("Order Amount to earn 1") }} {{ $newLoyalty_Cards }} {{ __("point") }} ({{ __("as per primary currency") }})</label>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">1 {{ $newLoyalty_Cards }} {{ __("Point") }} =</span>
                                </div>
                                <input type="text" onkeypress="return isNumberKey(event);" class="form-control" name="amount_per_loyalty_point" id="amount_per_loyalty_point" placeholder="Value" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddForm">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-loyalty-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit") }} {{ $newLoyalty_Cards }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="update_loyality_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editLoyaltyBox">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditForm">{{ __('Submit') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>


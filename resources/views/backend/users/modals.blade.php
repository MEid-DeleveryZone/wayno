<div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Customer") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_user" action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="imageInput">
                                <label>{{ __('Profile image') }}</label>
                                <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify" />
                                <label class="logo-size text-right w-100">{{ __('Image Size') }} 110x110 </label>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="emailInput">
                                        {!! Form::label('title', __('Email'),['class' => 'control-label']) !!}
                                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="phone_numberInput">
                                        {!! Form::label('title', __('Phone Number'),['class' => 'control-label']) !!}
                                        <input type="tel" class="form-control phone" id="phone" placeholder={{ __("Phone Number") }} name="phone_number" value="{{ old('full_number')}}">
                                        <input type="hidden" id="countryCode" name="country" value="{{ old('countryData') ? old('countryData') : 'us'}}">
                                        <input type="hidden" id="dialCode" name="dial_code" value="{{ old('dialCode') ? old('dialCode') : Session::get('default_country_phonecode',1) }}">
                                        <input type="hidden" id="addphoneHidden" name="phoneHidden">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="passwordInput">
                                        {!! Form::label('title', __('Password'),['class' => 'control-label']) !!}
                                        <input type="password" class="form-control" id="password" placeholder={{ __("Password") }} required="" name="password" value="{{ old('password')}}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" id="countryInput">
                                        {!! Form::label('title', __('Country'),['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="country_id">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($countries as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="form-group" id="typeInput">
                                        {!! Form::label('title', 'Type',['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="role_id">
                                            <option value="1">Buyer</option>
                                            <option value="2">Seller</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', __('Email Verified'),['class' => 'control-label']) !!}
                                        <div>
                                            <input type="checkbox" data-plugin="switchery" name="is_email_verified" class="form-control email_verify_add">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', __('Phone Verified'),['class' => 'control-label']) !!}
                                        <div>
                                            <input type="checkbox" data-plugin="switchery" name="is_phone_verified" class="form-control phone_verify_add">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="addCountryData" name="countryData" value="us">
                    <button type="button" class="btn btn-info w-100 submitCustomerForm">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Edit Customer') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="add_user" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6" id="imageInput">
                            <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify" />
                            <p class="text-muted text-center mt-2 mb-0">{{ __('Profile image') }}</p>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInputEdit">
                                        {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="emailInputEdit">
                                        {!! Form::label('title', __('Email'),['class' => 'control-label']) !!}
                                        {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="phone_numberInputEdit">
                                        {!! Form::label('title', __('Phone Number'),['class' => 'control-label']) !!}
                                        <input type="tel" class="form-control phone" id="phone" placeholder={{ __("Phone Number") }} name="phone_number" value="{{ old('full_number')}}">
                                        <input type="hidden" id="countryCode" name="country" value="{{ old('countryData') ? old('countryData') : 'us'}}">
                                        <input type="hidden" id="dialCode" name="country_code" value="{{ old('dialCode') ? old('dialCode') : Session::get('default_country_phonecode',1) }}">

                                        <input type="hidden" id="addphoneHidden" name="phoneHidden">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="passwordInputEdit">
                                        {!! Form::label('title', __('Password'),['class' => 'control-label']) !!}
                                        <input type="password" class="form-control" id="password" placeholder={{ __("Password") }} required="" name="password" value="{{ old('password')}}">
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="typeInputEdit">
                                        {!! Form::label('title', __('Type'),['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="role_id">
                                            <option value="1">{{ __('Buyer') }}</option>
                                            <option value="2">{{ __('Seller') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="countryInputEdit">
                                        {!! Form::label('title', __('Country'),['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" name="country_id">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($countries as $key => $val)
                                            <option value="{{$val->id}}">{{$val->nicename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="editCountryData" name="editCountryData" value="us">
                    <button type="submit" class="btn btn-info w-100">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="customer-wallet-transactions-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Wallet Transactions') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped w-100" id="customer_wallet_transactions_datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-nowrap">{{ __('Date') }}</th>
                                        <th>{{ __("Description") }}</th>
                                        <th>{{ __("Credit") }} / {{ __("Debit") }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
                </form>
            </div>
        </div>
    </div>
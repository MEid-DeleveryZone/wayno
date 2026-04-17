
<div class="row ">
    <div class="col-md-12">
        <div class="row mb-6">
            <div class="col-sm-2" id="iconInputEdit">
                <label>{{ __("Upload Category Icon") }} <small class="text-muted optional-text">({{ __("Optional - Leave empty to keep current image") }})</small></label>
                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="{{$category->icon ? $category->icon['proxy_url'].'400/400'.$category->icon['image_path'] : ''}}" data-has-existing-image="{{$category->icon ? 'true' : 'false'}}" />
                <input type="hidden" name="remove_icon" value="0">
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 150x150</label>
                <small class="form-text text-muted note-text">{{ __("Note: You can upload a new image to replace the current one, or leave empty to keep the existing image. If you remove the image, it will be permanently deleted from storage.") }}</small>
                <small class="form-text text-muted">{{ __("Accepted formats: JPG, JPEG, PNG, GIF. Max size: 2MB") }}</small>
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
            <div class="col-sm-3" id="imageInputEdit">
                <label>{{ __("Upload Category image") }} <small class="text-muted optional-text">({{ __("Optional - Leave empty to keep current image") }})</small></label>
                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$category->image ? $category->image['proxy_url'].'1000/200'.$category->image['image_path'] : ''}}" data-has-existing-image="{{$category->image ? 'true' : 'false'}}" />
                <input type="hidden" name="remove_image" value="0">
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1026x200</label>
                <small class="form-text text-muted note-text">{{ __("Note: You can upload a new image to replace the current one, or leave empty to keep the existing image. If you remove the image, it will be permanently deleted from storage.") }}</small>
                <small class="form-text text-muted">{{ __("Accepted formats: JPG, JPEG, PNG, GIF. Max size: 5MB") }}</small>
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
            <div class="col-sm-3" id="order_details_imageInputEdit">
                <label>{{ __("Upload Order Details Image") }} <small class="text-muted optional-text">({{ __("Optional - Leave empty to keep current image") }})</small></label>
                <input type="file" accept="image/*" data-plugins="dropify" name="order_details_image" class="dropify" data-default-file="{{$category->order_details_image ? $category->order_details_image['proxy_url'].'1000/200'.$category->order_details_image['image_path'] : ''}}" data-has-existing-image="{{$category->order_details_image ? 'true' : 'false'}}" />
                <input type="hidden" name="remove_order_details_image" value="0">
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1026x200</label>
                <small class="form-text text-muted note-text">{{ __("Note: You can upload a new image to replace the current one, or leave empty to keep the existing image. If you remove the image, it will be permanently deleted from storage.") }}</small>
                <small class="form-text text-muted">{{ __("Accepted format: GIF only. Max size: 5MB. GIF animations are supported.") }}</small>
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" id="slugInputEdit">
                            {!! Form::label('title', __('URL Slug'),['class' => 'control-label']) !!}
                            {!! Form::text('slug', $category->slug, ['class'=>'form-control','id' => 'slug', 'onkeypress' => "return alphaNumeric(event)"]) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                            {!! Form::hidden('login_user_type', session('preferences.login_user_type'), ['class'=>'form-control']) !!}
                            {!! Form::hidden('login_user_id', auth()->user()->id, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', __('Select Parent Category'),['class' => 'control-label']) !!}
                            <select class="selectize-select1 form-control parent-category" id="cateSelectBox" name="parent_cate">
                                @foreach($parCategory as $pc)
                                @if($pc->translation_one)
                                <option value="{{$pc->id}}" {{ ($pc->id == $category->parent_id) ? 'selected' : '' }}> {{ucfirst($pc->translation_one['name'])}}</option>
                                @endif
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Visible In Menus'),['class' => 'control-label']) !!}
                    <div>
                        @if($category->is_visible == '1')
                        <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control edit-switch_menu" data-color="#43bee1" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control edit-switch_menu" data-color="#43bee1">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Show Wishlist'),['class' => 'control-label']) !!}
                    <div>
                        @if($category->show_wishlist == '1')
                        <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control edit-wishlist_switch" data-color="#43bee1" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control edit-wishlist_switch" data-color="#43bee1">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="{{($category->type_id != 1) ? 'display:none;' : ''}}" id="editProductHide">
                <div class="form-group">
                    {!! Form::label('title', __('Can Add Products'),['class' => 'control-label']) !!}
                    <div>
                        @if($category->can_add_products == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-add_product_switch" data-color="#43bee1" name="can_add_products" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-add_product_switch" data-color="#43bee1" name="can_add_products">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Is Visible'),['class' => 'control-label', 'title'=>"to list categories in mobile application home"]) !!}
                    <div>
                        @if($category->is_category_visible == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_category_visible_switch" data-color="#43bee1" name="is_category_visible" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_category_visible_switch" data-color="#43bee1" name="is_category_visible">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Is Enabled'),['class' => 'control-label', 'title'=>"to enable categories in mobile application home"]) !!}
                    <div>
                        @if($category->is_enabled == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_enabled_switch" data-color="#43bee1" name="is_enabled" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_enabled_switch" data-color="#43bee1" name="is_enabled">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Visible In Vendor Reg'),['class' => 'control-label', 'title'=>"to enable categories in vendor registration form"]) !!}
                    <div>
                        @if($category->is_vendor_register == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_vendor_register_switch" data-color="#43bee1" name="is_vendor_register" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_vendor_register_switch" data-color="#43bee1" name="is_vendor_register">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 pickup-dropoff-section" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Pickup'),['class' => 'control-label', 'title'=>"to enable pickup in order"]) !!}
                    <div>
                        @if($category->is_pickup_enabled == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_pickup_enabled_switch" data-color="#43bee1" name="is_pickup_enabled" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_pickup_enabled_switch" data-color="#43bee1" name="is_pickup_enabled">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 pickup-dropoff-section" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Dropoff'),['class' => 'control-label', 'title'=>"to enable dropoff in order"]) !!}
                    <div>
                        @if($category->is_dropoff_enabled == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_dropoff_enabled_switch" data-color="#43bee1" name="is_dropoff_enabled" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_dropoff_enabled_switch" data-color="#43bee1" name="is_dropoff_enabled">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Schedule'),['class' => 'control-label', 'title'=>"to enable schedule"]) !!}
                    <div>
                        @if($category->is_schedule_enabled == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_schedule_enabled_switch" data-color="#43bee1" name="is_schedule_enabled" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_schedule_enabled_switch" data-color="#43bee1" name="is_schedule_enabled">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 pickup-dropoff-section" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Image Upload'),['class' => 'control-label', 'title'=>"to enable image upload"]) !!}
                    <div>
                        @if($category->is_image_upload_enabled == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_image_upload_enabled_switch" data-color="#43bee1" name="is_image_upload_enabled" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_image_upload_enabled_switch" data-color="#43bee1" name="is_image_upload_enabled">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Prohobited Item'),['class' => 'control-label', 'title'=>"to enable Prohobited Item"]) !!}
                    <div>
                        @if($category->is_prohibited_item_enabled == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_prohibited_item_enabled_switch" data-color="#43bee1" name="is_prohibited_item_enabled" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_prohibited_item_enabled_switch" data-color="#43bee1" name="is_prohibited_item_enabled">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Vehicle Number (Required)'),['class' => 'control-label', 'title'=>"to enable Vehicle Number requirement"]) !!}
                    <div>
                        @if($category->is_vehicle_number_required == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_vehicle_number_required_switch" data-color="#43bee1" name="is_vehicle_number_required" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_vehicle_number_required_switch" data-color="#43bee1" name="is_vehicle_number_required">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', __('Show Products in Popup'),['class' => 'control-label', 'title'=>"to show products in popup for this category"]) !!}
                    <div>
                        @if($category->is_show_products_in_popup == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_show_products_in_popup_switch" data-color="#43bee1" name="is_show_products_in_popup" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_show_products_in_popup_switch" data-color="#43bee1" name="is_show_products_in_popup">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 pickup-dropoff-section" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Show Product Price'),['class' => 'control-label', 'title'=>"To Show Product Price"]) !!}
                    <div>
                        @if($category->is_product_price_show == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_product_price_show_switch" data-color="#43bee1" name="is_product_price_show" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_product_price_show_switch" data-color="#43bee1" name="is_product_price_show">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 pickup-dropoff-section" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Order Detail required'),['class' => 'control-label', 'title'=>"Order Detail required"]) !!}
                    <div>
                        @if($category->is_msg_txt_mandatory == '1')
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_msg_txt_mandatory_switch" data-color="#43bee1" name="is_msg_txt_mandatory" checked='checked'>
                        @else
                        <input type="checkbox" data-plugin="switchery" class="form-control edit-is_msg_txt_mandatory_switch" data-color="#43bee1" name="is_msg_txt_mandatory">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="{{ ($category->type_id != 2) ? 'display:none;' : '' }}" id="editDispatcherHide">
                <div class="form-group mb-0">

                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3 pickup-dropoff-section" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Max Purchase Amount'),['class' => 'control-label']) !!}
                    {!! Form::text('max_purchase_amount', $category->max_purchase_amount, ['class'=>'form-control','id' => 'max_purchase_amount']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row mt-3 edit-category">
            @foreach($typeArray as $type)
            @if($type->title == 'Celebrity' && $preference->celebrity_check == 0)
            @continue
            @endif
            <div class="col-sm-6 col-md-2">
                <div class="card p-0 text-center select-category" id="tooltip-container">
                    <input class="form-check-input type-select" for="edit" type="radio" id="type_id_{{$type->id}}" name="type_id" @if($category->type_id == $type->id) checked @endif value="{{$type->id}}">
                    <label for="type_id_{{$type->id}}" class="card-body p-0 mb-0">
                        <div class="category-img">
                            <img src="{{url('images/category-types/'.$type->image)}}" alt="">
                        </div>
                        <div class="form-check form-check-info p-2">
                            <h6 class="mt-0" for="customradio5">{{$type->title}}</h6>
                        </div>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        <input type="hidden" id="cateId" url="{{route('category.update', $category->id)}}">
        <div class="row">
            <div class="col-md-4" id="template_type_main_div" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Warning Page'),['class' => 'control-label']) !!}
                    <div class="row">
                        @foreach($dispatcher_warning_page_options as $dwpo => $dispatcher_warning_page_option)
                        <div class="col-lg-6 custom-radio radio_new mt-2">
                            @if($category->warning_page_id)
                            <input type="radio" value="{{$dispatcher_warning_page_option->id}}" id="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}" name="warning_page_id" class="custom-control-input tab_bar_options radio-none" {{ ($category->warning_page_id == $dispatcher_warning_page_option->id) ? 'checked' : '' }}>
                            @else
                            <input type="radio" value="{{$dispatcher_warning_page_option->id}}" id="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}" name="warning_page_id" class="custom-control-input tab_bar_options radio-none">
                            @endif
                            <label class="custom-control-label" for="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}">
                                <img class="card-img-top img-fluid" src="{{asset('images/'.$dispatcher_warning_page_option->image_path)}}" alt="Card image cap">
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="warning_page_main_div" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Template Type'),['class' => 'control-label']) !!}
                    <div class="row">
                        @foreach($dispatcher_template_type_options as $dtto => $dispatcher_template_type_option)
                        <div class="col-lg-6 custom-radio radio_new mt-2">
                            @if($category->template_type_id)
                            <input type="radio" value="{{$dispatcher_template_type_option->id}}" id="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}" name="template_type_id" class="custom-control-input tab_bar_options" {{ ($category->template_type_id == $dispatcher_template_type_option->id) ? 'checked' : '' }}>
                            @else
                            <input type="radio" value="{{$dispatcher_template_type_option->id}}" id="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}" name="template_type_id" {{ ($dtto == 0) ? 'checked' : '' }} class="custom-control-input tab_bar_options">
                            @endif
                            <label class="custom-control-label" for="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}">
                                <img class="card-img-top img-fluid" src="{{asset('images/'.$dispatcher_template_type_option->image_path)}}" alt="Card image cap">
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            @foreach($category->translationSetUnique as $trans)
            <div class="col-lg-6">
                <div class="outer_box px-3 py-2 mb-3">
                    <div class="row rowYK">
                        <h4 class="col-md-12"> {{ $trans->langName.' Language' }}</h4>
                        <div class="col-md-6">
                            <div class="form-group" id="{{ ($trans->is_primary == 1) ? 'nameInputEdit' : 'nameotherInput' }}">
                                {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                @if($trans->is_primary == 1)
                                {!! Form::text('name[]', $trans->name, ['class' => 'form-control', 'required' => 'required']) !!}
                                @else
                                {!! Form::text('name[]', $trans->name, ['class' => 'form-control']) !!}
                                @endif
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        {!! Form::hidden('language_id[]', $trans->langId) !!}
                        {!! Form::hidden('trans_id[]', $trans->id) !!}
                        <div class="col-md-6">
                            <div class="form-group" id="meta_titleInput">
                                {!! Form::label('title', __('Meta Title'),['class' => 'control-label']) !!}
                                {!! Form::text('meta_title[]', $trans->meta_title, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Meta Description'),['class' => 'control-label']) !!}
                                {!! Form::textarea('meta_description[]', $trans->meta_description, ['class'=>'form-control', 'rows' => '3']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Meta Keywords'),['class' => 'control-label']) !!}
                                {!! Form::textarea('meta_keywords[]', $trans->meta_keywords, ['class' => 'form-control', 'rows' => '3']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Order Detail Placeholder'),['class' => 'control-label']) !!}
                                {!! Form::text('order_detail_placeholder[]', $trans->order_detail_placeholder, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Vendor Heading'),['class' => 'control-label']) !!}
                                {!! Form::text('vendor_heading[]', $trans->vendor_heading, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Product Heading'),['class' => 'control-label']) !!}
                                {!! Form::text('product_heading[]', $trans->product_heading, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if(count($langIds) != count($existlangs))
            @foreach($languages as $langs)
            @if(!in_array($langs->langId, $existlangs) && in_array($langs->langId, $langIds))
            <div class="col-lg-6">
                <div class="outer_box px-3 py-2 mb-3">
                    <div class="row rowYK">
                        <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>
                        <div class="col-md-6">
                            <div class="form-group" id="{{ ($langs->is_primary == 1) ? 'nameInputEdit' : 'nameotherInput' }}">
                                {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                @if($langs->is_primary == 1)
                                {!! Form::text('name[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                @else
                                {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                                @endif
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        {!! Form::hidden('language_id[]', $langs->langId) !!}
                        <div class="col-md-6">
                            <div class="form-group" id="meta_titleInput">
                                {!! Form::label('title', __('Meta Title'),['class' => 'control-label']) !!}
                                {!! Form::text('meta_title[]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Meta Description'),['class' => 'control-label']) !!}
                                {!! Form::textarea('meta_description[]', null, ['class'=>'form-control', 'rows' => '3']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Meta Keywords'),['class' => 'control-label']) !!}
                                {!! Form::textarea('meta_keywords[]', null, ['class' => 'form-control', 'rows' => '3']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Order Detail Placeholder'),['class' => 'control-label']) !!}
                                {!! Form::text('order_detail_placeholder[]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Vendor Heading'),['class' => 'control-label']) !!}
                                {!! Form::text('vendor_heading[]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('title', __('Product Heading'),['class' => 'control-label']) !!}
                                {!! Form::text('product_heading[]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif
        </div>
    </div>
   
</div>

<script>
    $(function() {

        var inputs = $('input.radio-none');
        var checked = inputs.filter(':checked').val();

        inputs.on('click', function() {

            if ($(this).val() === checked) {

                $(this).prop('checked', false);

                checked = '';

            } else {
                $(this).prop('checked', true);
                checked = $(this).val();

            }
        });

    });
</script>
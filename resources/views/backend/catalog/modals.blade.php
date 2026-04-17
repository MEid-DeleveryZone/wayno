<div id="addVariantmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Variant") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addVariantForm" method="post" enctype="multipart/form-data" action="{{route('variant.store')}}">
                @csrf
                <div class="modal-body" id="AddVariantBox">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light addVariantSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editVariantmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Variant") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editVariantForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editVariantBox">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light addVariantSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--   Brand      modals   -->
<div id="addBrandmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Brand") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addBrandForm" method="post" enctype="multipart/form-data" action="{{route('brand.store')}}">
                @csrf
                <div class="modal-body" id="AddbrandBox">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light addbrandSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editBrandmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Brand") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editBrandForm" method="post" enctype="multipart/form-data" action="">
                @method('PUT')
                @csrf
                <div class="modal-body" id="editBrandBox">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light editbrandSubmit">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
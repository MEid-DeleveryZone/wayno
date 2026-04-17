<div id="add-promo-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Promocode') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addPromoForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="addCardBox"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddForm">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="edit-promo-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Promocode") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editPromoForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCardBox"></div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitEditForm">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="add-category-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Category") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddCategoryBox">

                </div>
                <div class="modal-footer justify-content-start mb-2">
                    <p id="p-error" style="color:red;font-size:20px;text-align:left;justify-content: flex-start;"></p>
                    <button type="submit" class="btn btn-info waves-effect waves-light addCategorySubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-category-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Category") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="editCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCategoryBox">

                </div>

                <div class="modal-footer justify-content-start mb-2">
                    <p id="p-error1" style="color:red;font-size:20px;text-align:left;justify-content:flex-start;"></p>
                    <button type="button" class="btn btn-info waves-effect waves-light editCategorySubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
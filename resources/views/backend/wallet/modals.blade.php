

<div id="edit-loyalty-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Balance</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="update_loyality_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editLoyaltyBox">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditForm">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>
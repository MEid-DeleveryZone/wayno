<div id="update-card_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add a Card</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label">Select Plan</label>
                            <select class="form-control" data-style="btn-light" name="team_id" id="team_id" require>
                            <option value="other"></option>
                                @foreach($plan as $plans)
                                <option value="{{$plans->id}}">{{$plans->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light">Update</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->
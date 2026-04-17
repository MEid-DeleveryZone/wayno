<div id="add-webhook-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Webhook</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ route('set.webhook.url')}}" method="POST">
            @csrf
            <input type="hidden" name="notification_event_id" id="notification_event_id" value=""/>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="webhook_url" class="control-label">Web Url</label>
                            <input type="text" class="form-control" name="webhook_url" id="webhook_url" placeholder="https://yourwebhookurl.com">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light">Add</button>
            </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->
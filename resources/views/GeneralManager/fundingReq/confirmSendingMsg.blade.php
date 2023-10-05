
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Are you sure want to approve it?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label for="comment" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Comment') }}</label>
                    <input id="comment" name="comment" type="text" class="form-control" autocomplete="comment">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn-no2" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si2" class="btn btn-info">{{ MyHelpers::admin_trans(auth()->user()->id,'Approve') }}</button>
            </div>
        </div>
    </div>
</div>
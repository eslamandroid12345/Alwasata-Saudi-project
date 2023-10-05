

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Are you sure want to cancel it?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" id="modal-btn-no" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si" class="btn btn-danger">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
            </div>
        </div>
    </div>
</div>
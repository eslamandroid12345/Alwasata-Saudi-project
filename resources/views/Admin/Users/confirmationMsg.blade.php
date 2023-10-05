<div class="modal fade" id="mi-modal" tabindex="-1" role="dialog" aria-labelledby="mi-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mi-modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Are you sure want to archive it?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-footer">
                <button type="button" id="modal-btn-no" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si" class="btn btn-danger">{{ MyHelpers::admin_trans(auth()->user()->id,'Archive') }}</button>
            </div>
        </div>
    </div>
</div>

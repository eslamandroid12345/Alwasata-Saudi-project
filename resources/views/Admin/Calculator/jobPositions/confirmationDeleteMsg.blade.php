<div id="confirmDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Are you sure want to delete it?') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">
                <button type="button" name="no_button" id="no_button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" name="ok_button" id="ok_button" class="btn btn-danger">{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}</button>
            </div>
        </div>
    </div>
</div>




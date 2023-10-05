<div id="Confirm"  class="modal fade delete_modal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" style="margin-top: 150px">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Are you sure want to delete it?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-footer">
                <form action="" method="" class="deleteForm delete_form">
                    @csrf
                    <button type="button" id="modal-btn-no3" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" id="modal-btn-si3" class="btn btn-danger btn-delete-send">{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>




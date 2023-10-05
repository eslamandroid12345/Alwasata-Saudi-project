<!--begin::Modal-->
<div id="zoom_image"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" style="margin-top: 60px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Zoom') }} {{ MyHelpers::admin_trans(auth()->user()->id,'image') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="modal-content" id="zoomImage" >
            </div>
        </div>
    </div>
</div>


<!--end::Modal-->

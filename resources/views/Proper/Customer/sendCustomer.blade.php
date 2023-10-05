<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal3">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">ارسال نص جماعي</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="#" method="POST" id="frm-update">
                <div class="modal-body row">


                <div class="form-group col-12 col-md-12" id="subject_msg_div">
                        <label class="control-label mb-1">العنوان</label>
                        <input id="subject_msg" name="subject_msg" type="subject"  class="form-control"  placeholder=""></input>
                        <span id="subjectError" style="color: red;"></span>
                </div>

                <div class="form-group col-12 col-md-12">
                        <label class="control-label mb-1">النص</label>
                        <textarea id="text_msg" name="text_msg" cols="50" class="form-control"  placeholder=""></textarea>
                        <span id="textError" style="color: red;"></span>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="modal-btn-no3">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-si3">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
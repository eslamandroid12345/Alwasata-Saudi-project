<div class="modal fade bd-example-modal-lg"  id="add-form" style="margin-top: 60px" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form-contact" method="POST" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <input type="hidden"  id="id"   required name="id">
                    <input type="hidden"  id="vote"   required name="vote" value="no">
                    <input type="hidden"  id="suggestable_type"   required name="suggestable_type" value="FundingYear">
                    <div class="form-group">
                        <label for="question" class="col-form-label">السبب : </label>
                        <textarea class="form-control" id="not_reason" rows="7" autofocus name="not_reason" required placeholder="بسبب ..."></textarea>
                        <span class="text-danger">
                            <strong id="not_reason-error"></strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">إرسال</button>
                </div>
            </form>
        </div>
    </div>
</div>

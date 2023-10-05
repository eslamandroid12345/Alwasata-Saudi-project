<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal3">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">هل انت متأكد </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6>
                        لم تقم بتحديد اى مستخدمين أو مسمي وظيفي أو مدير مبيعات

                        هل انت متأكدمن أنك تريد إضافة التعميم لمدير البنك و المتعاون
                    </h6>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="modal-btn-no3" class="btn btn-secondary pull-right" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" name="all" value="not" class="btn btn-primary">لا ترسل لمدير البنك والمتعاون</button>
                <button type="submit" name="all" value="all" class="btn btn-danger">نعم , ارسل للجميع</button>
            </div>
        </div>
    </div>
</div>




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

                    <div class="form-group">
                        <label for="question" class="col-form-label">سبب الرفض : </label>
                        <input type="text" class="form-control" id="title"  autofocus name="title">
                        <span class="text-danger">
                            <strong id="title-error"></strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

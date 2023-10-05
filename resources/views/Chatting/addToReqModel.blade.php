<!-- Modal -->
<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">


        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">إضافته إلى مرفقات العميل</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <span class="text-danger" id="addError" role="alert" style="text-align:center"></span>
          
                <div class="modal-body">


                    <div class="form-group">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'File Name') }}</label>
                        <input id="filename" name="name" type="text" class="form-control" autocomplete="name" autofocus placeholder="">

                        <span class="text-danger" id="nameError" role="alert"> </span>

                    </div>
               

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="button" class="btn btn-info"id="addToReqButton">إضافة</button>
                </div>
          
        </div>

    </div>
</div>
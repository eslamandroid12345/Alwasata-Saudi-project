<div class="modal fade bd-example-modal-lg"  id="add-file-form" style="margin-top: 60px" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form-contact" enctype="multipart/form-data" method="POST" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <input type="hidden"  id="user_id"   required name="user_id" value="{{$user->id}}">

                    <div class="form-group">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'File Name') }}</label>
                        <input id="name" name="name" type="text" class="form-control" autocomplete="name" autofocus placeholder="">

                        <span class="text-danger" id="filename-error" role="alert"> </span>

                    </div>
                    <br>

                    <div class="form-group">
                        <label for="file" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'File') }}</label>
                        <input type="file" name="file" id="file" class="form-control">

                        <span class="text-danger" id="fileError" role="alert"> </span>
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

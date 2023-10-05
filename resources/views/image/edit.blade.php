<!--begin::Modal-->
<div id="edit_image"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" style="margin-top: 100px;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }} {{ MyHelpers::admin_trans(auth()->user()->id,'image') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="update_image_form"  method="post" action="{{route('image.update')}}" id="EditImage" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="id" value="" id="image_id">
                    <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                        <label for="image" class="form-control-label">{{ MyHelpers::admin_trans(auth()->user()->id,'image') }}</label>
                        <input type="file" name="image" required id="update_form_image" class="form-control" accept="image/*" value="{{old('image')}}" autocomplete="off">
                        <span class="help-block col-md-12">
                            <strong class="text-danger error-note" style="color:red" id="imageSaveError" role="alert"></strong>
                        </span>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit-btn-send" onclick="$('#EditImage').submit()" >{{ MyHelpers::admin_trans(auth()->user()->id,'Save') }}</button>
            </div>
        </div>
    </div>
</div>


<!--end::Modal-->

<!-- sample modal content -->
<div id="edit_property_request"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="EditPropertyRequest" class="update_form form-horizontal r-separator" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="property_request_id" value=""/>
                    <div class="card-body">
                        <div class="form-group row align-items-center m-b-0 {{ $errors->has('statusReq') ? ' has-error' : '' }}">
                            <label class="col-4 text-right control-label col-form-label">{{ MyHelpers::admin_trans(auth()->user()->id,'property status') }}</label>
                            <div class="col-8 border-left p-b-10 p-t-10">
                                <select name="statusReq" id="update_statusReq" class="form-control statusReq" placeholder="" value="{{ old('statusReq') }}">
{{--                                    <option value=0 id="option0"> {{ MyHelpers::admin_trans(auth()->user()->id,'new') }} </option>--}}
{{--                                    <option value=1 id="option1"> {{ MyHelpers::admin_trans(auth()->user()->id,'open') }} </option>--}}
                                    <option value=2 id="option2"> {{ MyHelpers::admin_trans(auth()->user()->id,'canceled') }} </option>
                                    <option value=3 id="option3"> {{ MyHelpers::admin_trans(auth()->user()->id,'completed') }} </option>
                                    <option value=4 id="option4"> {{ MyHelpers::admin_trans(auth()->user()->id,'convertedToTamweel') }} </option>
                                </select>
                                <span class="help-block col-md-12">
                                    <strong class="text-danger error-note" style="color:red" id="statusReqUpdateError" role="alert"></strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row align-items-center m-b-0 {{ $errors->has('class_id') ? ' has-error' : '' }}">
                            <label class="col-4 text-right control-label col-form-label">{{ MyHelpers::admin_trans(auth()->user()->id,'agent classification') }}</label>
                            <div class="col-8 border-left p-b-10 p-t-10">
                                <select name="class_id" id="update_class_id" class="form-control classID" placeholder="" value="{{ old('class_id') }}">

                                </select>
                                <span class="help-block col-md-12">
                                    <strong class="text-danger error-note" style="color:red" id="class_idUpdateError" role="alert"></strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row align-items-center m-b-0 {{ $errors->has('comment') ? ' has-error' : '' }}">
                            <label class="col-4 text-right control-label col-form-label">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <div class="col-8 border-left p-b-10 p-t-10">
                                <textarea rows="5" name="comment" id="update_comment" class="form-control" placeholder="" value=""> </textarea>
                                <span class="help-block col-md-12">
                                    <strong class="text-danger error-note" style="color:red" id="commentUpdateError" role="alert"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="button" class="btn btn-primary waves-effect btn-send" onclick="$('#EditPropertyRequest').submit()" >{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


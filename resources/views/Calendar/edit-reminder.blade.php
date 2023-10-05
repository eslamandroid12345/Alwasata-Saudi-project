<div id="edit_reminder" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Reminder') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('updateReminder')}}" method="post" class="updateReminder" id="updateReminder">
                    @csrf
                    <input type="hidden" name="id" value="" id="not_id">
                    <input type="hidden" name="req_id" value="" id="req_id">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="date" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'date') }}</label>
                                <input type="date" name="date" id="reminder_date" min="{{ date('Y-m-d')}}" class="form-control" onkeydown="return false">
                                <span class="text-danger error-note" id="dateUpdateError" role="alert"> </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="notify" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'message') }}</label>
                                <input type="text" class="form-control" id="reminder_msg" name="notify">
                                <span class="text-danger error-note" id="notifyUpdateError" role="alert"> </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-info" href="" id="openReq" style="margin-left: 63%;">
                   <i class="fa fa-file-text-o"></i> فتح الطلب
                </a>

                <button type="submit" id="modal-btn-si3" class="btn btn-primary btn-update-send" onclick="$('.updateReminder').submit()"> {{ MyHelpers::admin_trans(auth()->user()->id,'Save') }}</button>
                <button type="button" id="modal-btn-no3" class="btn btn-danger deleteBtn" href=""> <i class="fa fa-trash"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}</button>
            </div>
        </div>
    </div>
</div>
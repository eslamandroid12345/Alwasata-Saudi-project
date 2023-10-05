<div id="create_reminder"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Reminder') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{route('createReminder')}}" method="post" class="createReminder" id="createReminder"  >
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="custMob" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                                <input name ="mobile" id="custMob" type="text" class="form-control"  onchange="checkCustomerMobile(this.value)" value=""  onkeypress="return isNumber(event)" maxlength="9" onsubmit="checkCustomerMobile(this.value)">
                                <span class="text-danger error-note" id="custMobSaveError" role="alert"> </span>
                                <div class="mobile-check-create error-note"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="date" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'date') }}</label>
                                <input type="date" name="date"  min="{{ date('Y-m-d')}}" class="form-control" onkeydown="return false">
                                <span class="text-danger error-note" id="dateSaveError" role="alert"> </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="notify" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'message') }}</label>
                                <input type="text"  class="form-control"  name="notify">
                                <span class="text-danger error-note" id="notifySaveError" role="alert"> </span>

                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn-no3" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si3" class="btn btn-primary" onclick="$('.createReminder').submit()" >{{ MyHelpers::admin_trans(auth()->user()->id,'Save') }}</button>
            </div>
        </div>
    </div>
</div>




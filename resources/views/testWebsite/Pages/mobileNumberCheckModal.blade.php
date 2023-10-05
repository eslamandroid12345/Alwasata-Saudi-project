<!--MODAL-->

<div class="modal fade" id="mi-modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">التحقق من رقم الموبايل<i class="fa fa-check" aria-hidden="true" style="color:green"></i> </h4>
            </div>
            <div class="modal-body">
                <h4>هل أنت متأكد أن هذا رقم جوالك :</h4>
                <h5 style="text-align: right;" id="mobileNumberChek"> </h5>
            </div>
            <div class="modal-footer">
            <button type="button" id="no_mobileNumberChek" class="btn btn-secondary" data-dismiss="modal">لا ، أرغب بالتعديل</button>
            <button type="button" id="yes_mobileNumberChek" class="btn btn-success">{{ MyHelpers::guest_trans('Yes') }}</button>
            
            </div>
        </div>
    </div>
</div>

<!--MODAL-->


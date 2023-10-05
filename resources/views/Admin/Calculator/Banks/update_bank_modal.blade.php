<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="update_bank_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Bank') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <form id="form-bank-update">
                <div class="modal-body">
                    @csrf
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">
                    <!--here past addUserPage-->
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="name_ar" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_ar') }}</label>
                            <input type="text" id="name_ar" name="name_ar"  class="form-control" value="{{ old('name_ar') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="name_ar" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_en') }}</label>
                            <input type="text" id="name_en" name="name_en"  class="form-control" value="{{ old('name_en') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="name_ar" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'code') }}</label>
                            <input type="text" id="code" name="code"  class="form-control" value="{{ old('code') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="sort_order" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'sort_order') }}</label>
                            <input type="tel" id="sort_order" name="sort_order"  class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>

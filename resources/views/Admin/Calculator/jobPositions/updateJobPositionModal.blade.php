<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="updateJobPositionModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <form id="editFormID">
                <div class="modal-body">
                    @csrf
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="name_ar" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_ar') }}</label>
                            <input type="text" id="name_ar" name="name_ar"  class="form-control" value="{{ old('name_ar') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="name_en" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_en') }}</label>
                            <input type="text" id="name_en" name="name_en"  class="form-control" value="{{ old('name_en') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="code" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'code') }}</label>
                            <input type="text" id="code" name="code"  class="form-control" value="{{ old('code') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="sort_order" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'sort_order') }}</label>
                            <input type="tel" id="sort_order" name="sort_order"  class="form-control" value="{{ old('sort_order') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="salary_deduction" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary_deduction') }}</label>
                            <input type="tel" id="salary_deduction" name="salary_deduction"  class="form-control" value="{{ old('salary_deduction') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="retirement_age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'retirement_age') }}</label>
                            <input type="tel" id="retirement_age" name="retirement_age"  class="form-control" value="{{ old('retirement_age') }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="retirement_calc_number" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'retirement_calc_number') }}</label>
                            <input type="tel" id="retirement_calc_number" name="retirement_calc_number"  class="form-control" value="{{ old('retirement_calc_number') }}">
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




{{--<form action="{{ route('admin.updateJobPosition')}}" method="POST" id="job_position_update_form">--}}
{{--    <div class="modal-body">--}}
{{--        <div class="alert alert-danger errors-job" style="display:none"></div>--}}
{{--        @csrf--}}
{{--        <input name="_token" value="{{ csrf_token() }}" type="hidden">--}}
{{--        <input type="hidden" name="id" class="form-control" id="id">--}}
{{--        <div class="row">--}}
{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_ar') }}</label>--}}
{{--                <input type="text" id="name_ar" name="name_ar"  class="form-control" value="{{ old('name_ar') }}">--}}
{{--            </div>--}}

{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name_en') }}</label>--}}
{{--                <input type="text" id="name_en" name="name_en"  class="form-control" value="{{ old('name_en') }}">--}}

{{--            </div>--}}
{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'code') }}</label>--}}
{{--                <input type="tel" id="code" name="code"  class="form-control" value="{{ old('code') }}">--}}

{{--            </div>--}}
{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'sort_order') }}</label>--}}
{{--                <input type="tel" id="sort_order" name="sort_order"  class="form-control" value="{{ old('sort_order') }}">--}}

{{--            </div>--}}

{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary_deduction') }}</label>--}}
{{--                <input type="tel" id="salary_deduction" name="salary_deduction"  class="form-control" value="{{ old('salary_deduction') }}">--}}

{{--            </div>--}}
{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'retirement_age') }}</label>--}}
{{--                <input type="tel" id="retirement_age" name="retirement_age"  class="form-control" value="{{ old('retirement_age') }}">--}}

{{--            </div>--}}
{{--            <div class="form-group col-6 mt-4">--}}
{{--                <label for="job_position" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'retirement_calc_number') }}</label>--}}
{{--                <input type="tel" id="retirement_calc_number" name="retirement_calc_number"  class="form-control" value="{{ old('retirement_calc_number') }}">--}}

{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="modal-footer">--}}
{{--            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>--}}
{{--            <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>--}}
{{--        </div>--}}
{{--</form>--}}

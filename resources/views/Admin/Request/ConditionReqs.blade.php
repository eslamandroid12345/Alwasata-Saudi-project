<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="contion_req_model">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'add_condition_to_pending_request') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{url('admin/pending/request/condition')}}" id="contion_req_form_model">
                <div class="modal-body row">
                    @csrf
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                        <div class="form-group">
                            <label for="birth_date">@lang('language.from birth date'): </label>
                            <div class="col-md-12">
                                <input type="date" id="birth_date" name="request_validation_from_birth_date" class="form-control" value="{{old('request_validation_from_birth_date')}}" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: block;" id="birth_gerous">
                        <div class="form-group">
                            <label for="birth_date1"> @lang('language.to birth date'): </label>
                            <div class="col-md-12">
                                <input type="date" id="birth_date1" name="request_validation_to_birth_date" class="form-control" value="{{old('request_validation_to_birth_date')}}" placeholder="يوم/شهر/سنة" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                        <div class="form-group">
                            <label for="birth_date"> @lang('language.from birth date hijri'): </label>
                            <div class="col-md-12">
                                <input type="text" name="request_validation_from_birth_hijri" style="text-align: right;" value="{{old('request_validation_from_birth_hijri')}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri1">
                        <div class="form-group">
                            <label for="birth_date"> @lang('language.to birth date hijri'): </label>
                            <div class="col-md-12">
                                <input type="text" name="request_validation_to_birth_hijri" style="text-align: right;" value="{{old('request_validation_to_birth_hijri')}}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date1">
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="birth_date">@lang('language.from_salary'): </label>
                            <div class="col-md-12">
                                <input type="number" name="request_validation_from_salary" style="text-align: right;" value="{{old('request_validation_from_salary')}}" class="form-control" placeholder="@lang('language.salary')">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="birth_date">@lang('language.to_salary'): </label>
                            <div class="col-md-12">
                                <input type="number" name="request_validation_to_salary" style="text-align: right;" value="{{old('request_validation_to_salary')}}" class="form-control" placeholder="@lang('language.salary')">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                            <select id="work" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('work') is-invalid @enderror" name="request_validation_to_work">


                                <option value="">---</option>
                                <option value="عسكري" @if (old('request_validation_to_work')=='عسكري' ) selected="selected" @endif>عسكري</option>
                                <option value="مدني" @if (old('request_validation_to_work')=='مدني' ) selected="selected" @endif>مدني</option>
                                <option value="متقاعد" @if (old('request_validation_to_work')=='متقاعد' ) selected="selected" @endif>متقاعد</option>
                                <option value="شبه حكومي" @if (old('request_validation_to_work')=='شبه حكومي' ) selected="selected" @endif>شبه حكومي</option>
                                <option value="قطاع خاص" @if (old('request_validation_to_work')=='قطاع خاص' ) selected="selected" @endif>قطاع خاص</option>
                                <option value="قطاع خاص غير معتمد" @if (old('request_validation_to_work')=='قطاع خاص غير معتمد' ) selected="selected" @endif>قطاع خاص غير معتمد</option>
                                <option value="قطاع خاص معتمد" @if (old('request_validation_to_work')=='قطاع خاص معتمد' ) selected="selected" @endif>قطاع خاص معتمد</option>


                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                            <select id="support" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('support') is-invalid @enderror" name="request_validation_to_support">


                                <option value="">---</option>
                                <option value="yes" @if (old('request_validation_to_support')=='yes' ) selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('request_validation_to_support')=='no' ) selected="selected" @endif>لا</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="property" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}؟</label>

                            <select id="property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('property') is-invalid @enderror" name="request_validation_to_hasProperty">


                                <option value="">---</option>
                                <option value="yes" @if (old('request_validation_to_hasProperty')=='yes' ) selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('request_validation_to_hasProperty')=='no' ) selected="selected" @endif>لا</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="joint" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}؟</label>

                            <select id="joint" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('joint') is-invalid @enderror" name="request_validation_to_hasJoint">


                                <option value="">---</option>
                                <option value="yes" @if (old('request_validation_to_hasJoint') == 'yes') selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('request_validation_to_hasJoint') == 'no') selected="selected" @endif>لا</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}؟</label>

                            <select id="obligations" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('obligations') is-invalid @enderror" name="request_validation_to_has_obligations">


                                <option value="">---</option>
                                <option value="yes" @if (old('request_validation_to_has_obligations') == 'yes') selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('request_validation_to_has_obligations') == 'no') selected="selected" @endif>لا</option>

                            </select>
                        </div>
                    </div>


                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}؟</label>

                            <select id="distress" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('distress') is-invalid @enderror" name="request_validation_to_has_financial_distress">


                                <option value="">---</option>
                                <option value="yes" @if (old('request_validation_to_has_financial_distress') == 'yes') selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('request_validation_to_has_financial_distress') == 'no') selected="selected" @endif>لا</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="owning_property" class="control-label mb-1">هل يمتلك عقار؟</label>

                            <select id="owning_property" onchange="this.size=1; this.blur();" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('owning_property') is-invalid @enderror" name="request_validation_to_owningProperty">


                                <option value="">---</option>
                                <option value="yes" @if (old('request_validation_to_owningProperty') == 'yes') selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('request_validation_to_owningProperty') == 'no') selected="selected" @endif>لا</option>

                            </select>
                        </div>
                    </div>




                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                        <button type="button" class="btn btn-primary" id="btn_condition">{{ MyHelpers::admin_trans(auth()->user()->id,'apply') }}</button>
                    </div>
            </form>
        </div>

    </div>
</div>

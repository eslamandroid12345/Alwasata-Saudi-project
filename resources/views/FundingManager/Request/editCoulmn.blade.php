<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Edit Columns') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div style="text-align:center;" id="error"></div>

            <form action="{{ route('funding.manager.editCoulmn')}}" method="POST" id="frm-update_coulmn">
                <div class="modal-body">

                    @csrf
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="tableName" class="form-control" value="underReqsTable">


                    <div>
                        <input type="checkbox" id="select_all" name="select_all">
                        <label for="select_all" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'select_all') }}</label>
                    </div>

                    <hr />

                    <div class="row">

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="profit_cost" name="profit_cost" value=0 @if($editCoulmns->where('coulmnName','profit_cost')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="profit_cost" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'profit cost') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="mortgage_value" name="mortgage_value" value=1 @if($editCoulmns->where('coulmnName','mortgage_value')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="mortgage_value" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'mortgage cost') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="Customer" name="Customer" value=2 @if($editCoulmns->where('coulmnName','Customer')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="Customer" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="mobile" name="mobile" value=3 @if($editCoulmns->where('coulmnName','mobile')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="mobile" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_estate_type" name="real_estate_type" value=4 @if($editCoulmns->where('coulmnName','real_estate_type')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="real_estate_type" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="state" name="state" value=5 @if($editCoulmns->where('coulmnName','state')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="state" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'state') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="owner_name" name="owner_name" value=6 @if($editCoulmns->where('coulmnName','owner_name')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="owner_name" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'owner_name') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="owner_mobile" name="owner_mobile" value=7 @if($editCoulmns->where('coulmnName','owner_mobile')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="owner_mobile" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="salary_source" name="salary_source" value=8 @if($editCoulmns->where('coulmnName','salary_source')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="salary_source" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        </div>


                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_source" name="funding_source" value=9 @if($editCoulmns->where('coulmnName','funding_source')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="funding_source" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="bank_employee" name="bank_employee" value=10 @if($editCoulmns->where('coulmnName','bank_employee')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="bank_employee" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'bank_employee') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="bank_order_num" name="bank_order_num" value=11 @if($editCoulmns->where('coulmnName','bank_order_num')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="bank_order_num" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'bank_order_num') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="req_source" name="req_source" value=12 @if($editCoulmns->where('coulmnName','req_source')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="req_source" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'req_source') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="type" name="type" value=13 @if($editCoulmns->where('coulmnName','type')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="type" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="Sales_Agent" name="Sales_Agent" value=14 @if($editCoulmns->where('coulmnName','Sales_Agent')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="Sales_Agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales_Agent') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="recived_date" name="recived_date" value=15 @if($editCoulmns->where('coulmnName','recived_date')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="recived_date" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived_date') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="req_classification" name="req_classification" value=16 @if($editCoulmns->where('coulmnName','req_classification')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="req_classification" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req_classification') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="comment" name="comment" value=17 @if($editCoulmns->where('coulmnName','comment')->count() > 0 || $editCoulmns->count() == 0 ) checked @endif>
                            <label for="comment" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                        </div>


                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="birth_date" name="birth_date" value=18 @if($editCoulmns->where('coulmnName','birth_date')->count() > 0) checked @endif>
                            <label for="birth_date" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} {{ MyHelpers::admin_trans(auth()->user()->id,'client') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="salary" name="salary" value=19 @if($editCoulmns->where('coulmnName','salary')->count() > 0) checked @endif>
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="work" name="work" value=20 @if($editCoulmns->where('coulmnName','work')->count() > 0) checked @endif>
                            <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="madany_id" name="madany_id" value=21 @if($editCoulmns->where('coulmnName','madany_id')->count() > 0) checked @endif>
                            <label for="madany_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'civilian_ministry') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="military_rank" name="military_rank" value=22 @if($editCoulmns->where('coulmnName','military_rank')->count() > 0) checked @endif>
                            <label for="military_rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'military_rank') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="is_supported" name="is_supported" value=23 @if($editCoulmns->where('coulmnName','is_supported')->count() > 0) checked @endif>
                            <label for="is_supported" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is_supported') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="has_obligations" name="has_obligations" value=24 @if($editCoulmns->where('coulmnName','has_obligations')->count() > 0) checked @endif>
                            <label for="has_obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_name" name="joint_name" value=25 @if($editCoulmns->where('coulmnName','joint_name')->count() > 0) checked @endif>
                            <label for="joint_name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_birth_date_higri" name="joint_birth_date_higri" value=26 @if($editCoulmns->where('coulmnName','joint_birth_date_higri')->count() > 0) checked @endif>
                            <label for="joint_birth_date_higri" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_mobile" name="joint_mobile" value=27 @if($editCoulmns->where('coulmnName','joint_mobile')->count() > 0) checked @endif>
                            <label for="joint_mobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_salary" name="joint_salary" value=28 @if($editCoulmns->where('coulmnName','joint_salary')->count() > 0) checked @endif>
                            <label for="joint_salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_salary_id" name="joint_salary_id" value=29 @if($editCoulmns->where('coulmnName','joint_salary_id')->count() > 0) checked @endif>
                            <label for="joint_salary_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_work" name="joint_work" value=30 @if($editCoulmns->where('coulmnName','joint_work')->count() > 0) checked @endif>
                            <label for="joint_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_madany_id" name="joint_madany_id" value=31 @if($editCoulmns->where('coulmnName','joint_madany_id')->count() > 0) checked @endif>
                            <label for="joint_madany_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'civilian_ministry') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="joint_military_rank" name="joint_military_rank" value=32 @if($editCoulmns->where('coulmnName','joint_military_rank')->count() > 0) checked @endif>
                            <label for="joint_military_rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }} <br> {{ MyHelpers::admin_trans(auth()->user()->id,'solidarity') }}</label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_age" name="real_age" value=33 @if($editCoulmns->where('coulmnName','real_age')->count() > 0) checked @endif>
                            <label for="real_age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property_age') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_status" name="real_status" value=34 @if($editCoulmns->where('coulmnName','real_status')->count() > 0) checked @endif>
                            <label for="real_status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_cost" name="real_cost" value=35 @if($editCoulmns->where('coulmnName','real_cost')->count() > 0) checked @endif>
                            <label for="real_cost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_evaluated" name="real_evaluated" value=36 @if($editCoulmns->where('coulmnName','real_evaluated')->count() > 0) checked @endif>
                            <label for="real_evaluated" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_tenant" name="real_tenant" value=37 @if($editCoulmns->where('coulmnName','real_tenant')->count() > 0) checked @endif>
                            <label for="real_tenant" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="real_mortgage" name="real_mortgage" value=38 @if($editCoulmns->where('coulmnName','real_mortgage')->count() > 0) checked @endif>
                            <label for="real_mortgage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_funding_duration" name="funding_funding_duration" value=39 @if($editCoulmns->where('coulmnName','funding_funding_duration')->count() > 0) checked @endif>
                            <label for="funding_funding_duration" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_personalFun_cost" name="funding_personalFun_cost" value=40 @if($editCoulmns->where('coulmnName','funding_personalFun_cost')->count() > 0) checked @endif>
                            <label for="funding_personalFun_cost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_personalFun_pre" name="funding_personalFun_pre" value=41 @if($editCoulmns->where('coulmnName','funding_personalFun_pre')->count() > 0) checked @endif>
                            <label for="funding_personalFun_pre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_realFun_cost" name="funding_realFun_cost" value=42 @if($editCoulmns->where('coulmnName','funding_realFun_cost')->count() > 0) checked @endif>
                            <label for="funding_realFun_cost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_realFun_pre" name="funding_realFun_pre" value=43 @if($editCoulmns->where('coulmnName','funding_realFun_pre')->count() > 0) checked @endif>
                            <label for="funding_realFun_pre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_ded_pre" name="funding_ded_pre" value=44 @if($editCoulmns->where('coulmnName','funding_ded_pre')->count() > 0) checked @endif>
                            <label for="funding_ded_pre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="funding_monthly_in" name="funding_monthly_in" value=45 @if($editCoulmns->where('coulmnName','funding_monthly_in')->count() > 0) checked @endif>
                            <label for="funding_monthly_in" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="payStatus" name="payStatus" value=46 @if($editCoulmns->where('coulmnName','payStatus')->count() > 0) checked @endif>
                            <label for="payStatus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'pay status') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="incValue" name="incValue" value=47 @if($editCoulmns->where('coulmnName','incValue')->count() > 0) checked @endif>
                            <label for="incValue" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'increase value') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="prepaymentVal" name="prepaymentVal" value=48 @if($editCoulmns->where('coulmnName','prepaymentVal')->count() > 0) checked @endif>
                            <label for="prepaymentVal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment value') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="prepaymentPre" name="prepaymentPre" value=49 @if($editCoulmns->where('coulmnName','prepaymentPre')->count() > 0) checked @endif>
                            <label for="prepaymentPre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'prepayment %') }} </label>
                        </div>

                        <div class="form-group col-3 col-md-3">
                            <input type="checkbox" id="prepaymentCos" name="prepaymentCos" value=50 @if($editCoulmns->where('coulmnName','prepaymentCos')->count() > 0) checked @endif>
                            <label for="prepaymentCos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }} </label>
                        </div>


                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="filter-edit-column">{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
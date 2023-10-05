<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-briefcase"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
            <div id="tableAdminOption" class=" row">


                <div class="col-12">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="product_type" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="product_code" class="control-label mb-1">نوع المنتج</label>
                        <select id="product_code" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('product_code') is-invalid @enderror" value="{{ old('product_code') }}" name="product_code">

                            <option value="">---</option>
                            @if ($product_types != null)
                            @foreach ($product_types as $product_type )
                            @if ($purchaseFun->product_code == $product_type['code'])
                            <option value="{{$product_type['code']}}" selected>{{$product_type['name_ar']}}</option>
                            @else
                            <option value="{{$product_type['code']}}">{{$product_type['name_ar']}}</option>
                            @endif

                            @endforeach
                            @endif
                        </select>

                        @error('product_code')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>

                @if ($show_funding_source)
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="funding_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="funding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                        <select id="funding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control funding_source_missedFiledInput FiledInput select2-request @error('funding_source') is-invalid @enderror" value="{{ old('funding_source') }}" name="funding_source" >

                            <option value="">---</option>
                            @foreach ($funding_sources as $funding_source )
                            @if ($purchaseFun->funding_source == $funding_source->id || (old('funding_source') == $funding_source->id))
                            <option value="{{$funding_source->id}}" selected>{{$funding_source->value}}</option>
                            @else
                            <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
                            @endif

                            @endforeach
                        </select>

                        <small style="color:#e60000" class="d-none funding_source_missedFileds missedFileds">الحقل مطلوب</small>


                        @error('funding_source')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                @endif


                <div @if ($show_funding_source) class="col-6" @else class="col-12" @endif>
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundDur" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingdur" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                        <input id="fundingdur" name="fundingdur" type="number" max="30" class="form-control fundingdur_missedFiledInput FiledInput @error('fundingdur') is-invalid @enderror" value="{{  old('fundingdur',$purchaseFun->funding_duration) }}" autocomplete="fundingdur" >

                        <small style="color:#e60000" class="d-none fundingdur_missedFileds missedFileds">الحقل مطلوب</small>


                        @error('fundingdur')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundPers" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingpersonal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                        <input id="fundingpersonal" name="fundingpersonal" type="number" class="form-control funding_personal_m @error('fundingpersonal') is-invalid @enderror" value="{{  old('fundingpersonal',$purchaseFun->personalFun_cost) }}" autocomplete="fundingpersonal">


                        @error('fundingpersonal')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundPersPre" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingpersonalp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1" max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ old('fundingpersonalp',$purchaseFun->personalFun_pre) }}" autocomplete="fundingpersonalp">


                        @error('fundingpersonalp')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundReal" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingreal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</label>
                        <input id="fundingreal" name="fundingreal" type="number" class="form-control fundingreal_missedFiledInput FiledInput @error('fundingreal') is-invalid @enderror" value="{{ old('fundingreal',$purchaseFun->realFun_cost) }}" autocomplete="fundingreal" >


                        <small style="color:#e60000" class="d-none fundingreal_missedFileds missedFileds">الحقل مطلوب</small>


                        @error('fundingreal')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundRealPre" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingrealp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input id="fundingrealp" name="fundingrealp" type="number" min="0.1" max="100" class="form-control fundingrealp_missedFiledInput FiledInput @error('fundingrealp') is-invalid @enderror" value="{{ old('fundingrealp',$purchaseFun->realFun_pre) }}" autocomplete="fundingrealp" >


                        <small style="color:#e60000" class="d-none fundingrealp_missedFileds missedFileds">الحقل مطلوب</small>


                        @error('fundingrealp')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundFlex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="flexiableFun_cost" class="control-label mb-1">مبلغ التمويل المرن</label>
                        <input id="flexiableFun_cost" name="flexiableFun_cost" type="number" class="form-control @error('flexiableFun_cost') is-invalid @enderror" value="{{ old('flexiableFun_cost',$purchaseFun->flexiableFun_cost) }}" autocomplete="flexiableFun_cost">


                        @error('flexiableFun_cost')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundExten" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="extendFund_cost" class="control-label mb-1">مبلغ التمويل الممتد</label>
                        <input id="extendFund_cost" name="extendFund_cost" type="number" class="form-control @error('extendFund_cost') is-invalid @enderror" value="{{ old('extendFund_cost',$purchaseFun->extendFund_cost) }}" autocomplete="extendFund_cost">


                        @error('extendFund_cost')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>



                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="personal_salary_deduction" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="personal_salary_deduction" class="control-label mb-1"> (شخصي){{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                        <input id="personal_salary_deduction" name="personal_salary_deduction" type="number" min="0.1" max="100" class="form-control @error('personal_salary_deduction') is-invalid @enderror" value="{{ old('personal_salary_deduction',$purchaseFun->personal_salary_deduction) }}" autocomplete="personal_salary_deduction">

                        @error('personal_salary_deduction')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="personal_installment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="personal_monthly_installment" class="control-label mb-1">(شخصي){{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                        <input id="personal_monthly_installment" name="personal_monthly_installment" type="number" class="form-control" value="{{ old('personal_monthly_installment',$purchaseFun->personal_monthly_installment) }}" autocomplete="personal_monthly_installment">

                    </div>

                </div>


                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundDed" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="dedp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                        <input id="dedp" name="dedp" type="number" min="0.1" max="100" class="form-control dedp_missedFiledInput FiledInput @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ old('dedp',$purchaseFun->ded_pre) }}" autocomplete="dedp">

                        <small style="color:#e60000" class="d-none dedp_missedFileds missedFileds">الحقل مطلوب</small>


                        @error('dedp')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundMonth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="monthIn" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                        <input id="monthIn" name="monthIn" type="number" class="form-control monthIn_missedFiledInput FiledInput" value="{{ old('monthIn',$purchaseFun->monthly_in) }}" autocomplete="monthIn" >


                        <small style="color:#e60000" class="d-none monthIn_missedFileds missedFileds">الحقل مطلوب</small>

                    </div>

                </div>


                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="installment_after_support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="monthly_installment_after_support" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }} بعد الدعم</label>
                        <input id="monthly_installment_after_support" name="monthly_installment_after_support" type="number" min="0.1" max="100" class="form-control @error('monthly_installment_after_support') is-invalid @enderror" value="{{ old('monthly_installment_after_support',$purchaseFun->monthly_installment_after_support) }}" autocomplete="monthly_installment_after_support">

                        @error('monthly_installment_after_support')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
            </div>
            @else
            <div id="tableAdminOption" class="row">


                <div class="col-12">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="product_type" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="product_code" class="control-label mb-1">نوع المنتج</label>
                        <select disabled id="product_code" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('product_code') is-invalid @enderror" value="{{ old('product_code') }}" name="product_code">

                            <option value="">---</option>
                            @if ($product_types != null)
                            @foreach ($product_types as $product_type )
                            @if ($purchaseFun->product_code == $product_type['code'])
                            <option value="{{$product_type['code']}}" selected>{{$product_type['name_ar']}}</option>
                            @else
                            <option value="{{$product_type['code']}}">{{$product_type['name_ar']}}</option>
                            @endif

                            @endforeach
                            @endif

                        </select>

                        @error('product_code')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>

                @if ($show_funding_source)
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="funding_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="funding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                        <select disabled id="funding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('funding_source') }}" name="funding_source">

                            <option value="">---</option>
                            @foreach ($funding_sources as $funding_source )
                            @if ($purchaseFun->funding_source == $funding_source->id)
                            <option value="{{$funding_source->id}}" selected>{{$funding_source->value}}</option>
                            @else
                            <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
                            @endif

                            @endforeach
                        </select>

                        @error('funding_source')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                @endif

                <div @if ($show_funding_source) class="col-6" @else class="col-12" @endif>
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundDur" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingdur" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                        <input readonly id="fundingdur" name="fundingdur" type="number" max="30" class="form-control @error('fundingdur') is-invalid @enderror" value="{{ $purchaseFun->funding_duration }}" autocomplete="fundingdur">


                        @error('fundingdur')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundPers" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingpersonal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                        <input readonly id="fundingpersonal" name="fundingpersonal" type="number" class="form-control @error('fundingpersonal') is-invalid @enderror" value="{{ $purchaseFun->personalFun_cost }}" autocomplete="fundingpersonal">


                        @error('fundingpersonal')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundPersPre" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingpersonalp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input readonly id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1" max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ $purchaseFun->personalFun_pre }}" autocomplete="fundingpersonalp">


                        @error('fundingpersonalp')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundReal" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingreal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</label>
                        <input readonly id="fundingreal" name="fundingreal" type="number" class="form-control @error('fundingreal') is-invalid @enderror" value="{{ $purchaseFun->realFun_cost }}" autocomplete="fundingreal">


                        @error('fundingreal')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundRealPre" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="fundingrealp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input readonly id="fundingrealp" name="fundingrealp" type="number" min="0.1" max="100" class="form-control @error('fundingrealp') is-invalid @enderror" value="{{ $purchaseFun->realFun_pre }}" autocomplete="fundingrealp">


                        @error('fundingrealp')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundFlex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="flexiableFun_cost" class="control-label mb-1">مبلغ التمويل المرن</label>
                        <input readonly id="flexiableFun_cost" name="flexiableFun_cost" type="number" class="form-control @error('flexiableFun_cost') is-invalid @enderror" value="{{ old('flexiableFun_cost',$purchaseFun->flexiableFun_cost) }}" autocomplete="flexiableFun_cost">


                        @error('flexiableFun_cost')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundExten" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="extendFund_cost" class="control-label mb-1">مبلغ التمويل الممتد</label>
                        <input readonly id="extendFund_cost" name="extendFund_cost" type="number" class="form-control @error('extendFund_cost') is-invalid @enderror" value="{{ old('extendFund_cost',$purchaseFun->extendFund_cost) }}" autocomplete="extendFund_cost">


                        @error('extendFund_cost')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="personal_salary_deduction" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="personal_salary_deduction" class="control-label mb-1"> (شخصي){{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                        <input readonly id="personal_salary_deduction" name="personal_salary_deduction" type="number" min="0.1" max="100" class="form-control @error('personal_salary_deduction') is-invalid @enderror" value="{{ old('personal_salary_deduction',$purchaseFun->personal_salary_deduction) }}" autocomplete="personal_salary_deduction">

                        @error('personal_salary_deduction')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="personal_installment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="personal_monthly_installment" class="control-label mb-1">(شخصي){{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                        <input readonly id="personal_monthly_installment" name="personal_monthly_installment" type="number" class="form-control" value="{{ old('personal_monthly_installment',$purchaseFun->personal_monthly_installment) }}" autocomplete="personal_monthly_installment">

                    </div>

                </div>


                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundDed" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="dedp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                        <input readonly id="dedp" name="dedp" type="number" min="0.1" max="100" class="form-control @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ $purchaseFun->ded_pre }}" autocomplete="dedp">


                        @error('dedp')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="fundMonth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="monthIn" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                        <input readonly id="monthIn" name="monthIn" type="number" class="form-control" value="{{ $purchaseFun->monthly_in }}" autocomplete="monthIn" readonly>
                    </div>

                </div>

                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="installment_after_support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="monthly_installment_after_support" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }} بعد الدعم</label>
                        <input readonly id="monthly_installment_after_support" name="monthly_installment_after_support" type="number" min="0.1" max="100" class="form-control @error('monthly_installment_after_support') is-invalid @enderror" value="{{ old('monthly_installment_after_support',$purchaseFun->monthly_installment_after_support) }}" autocomplete="monthly_installment_after_support">

                        @error('monthly_installment_after_support')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>

            </div>
            @endif

        </div>
    </div>
</div>

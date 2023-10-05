<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-briefcase"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div id="tableAdminOption" class="row">
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
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="funding_source" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="funding_source">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                        <select name="funding_source" id="funding_source" class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('funding_source') }}">
                            <option value="">---</option>
                            @foreach ($funding_sources as $funding_source )
                            @if ($purchaseFun->funding_source == $funding_source->id || (old('funding_source') == $funding_source->id))
                            <option value="{{$funding_source->id}}" selected>{{$funding_source->value}}</option>
                            @else
                            <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('funding_source')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="fundDur" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>

                        <label for="fundingdur">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                        <input id="fundingdur" name="fundingdur" type="number" max="30" class="form-control @error('fundingdur') is-invalid @enderror" value="{{  old('fundingdur',$purchaseFun->funding_duration) }}" autocomplete="fundingdur">
                        @error('fundingdur')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="fundPers" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingpersonal">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                        <input id="fundingpersonal" name="fundingpersonal" type="number" class="form-control @error('fundingpersonal') is-invalid @enderror" value="{{  old('fundingpersonal',$purchaseFun->personalFun_cost) }}" autocomplete="fundingpersonal">
                        @error('fundingpersonal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="fundPersPre" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingpersonalp">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1" max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ old('fundingpersonalp',$purchaseFun->personalFun_pre) }}" autocomplete="fundingpersonalp">
                        @error('fundingpersonalp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="fundReal" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingreal">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</label>
                        <input id="fundingreal" name="fundingreal" type="number" class="form-control @error('fundingreal') is-invalid @enderror" value="{{ old('fundingreal',$purchaseFun->realFun_cost) }}" autocomplete="fundingreal">
                        @error('fundingreal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="fundRealPre" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingrealp">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input id="fundingrealp" name="fundingrealp" type="number" min="0.1" max="100" class="form-control @error('fundingrealp') is-invalid @enderror" value="{{ old('fundingrealp',$purchaseFun->realFun_pre) }}" autocomplete="fundingrealp">
                        @error('fundingrealp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
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
                        <input id="dedp" name="dedp" type="number" min="0.1" max="100" class="form-control @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ old('dedp',$purchaseFun->ded_pre) }}" autocomplete="dedp" >
                       

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
                        <input id="monthIn" name="monthIn" type="number" class="form-control" value="{{ old('monthIn',$purchaseFun->monthly_in) }}" autocomplete="monthIn" >
                      
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
        </div>

    </div>
</div>
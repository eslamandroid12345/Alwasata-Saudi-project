<!-- Status mor pur of agent-->
<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-briefcase"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            @if ($reqStatus == 19 )
                <div  id="tableAdminOption" class=" row">
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="funding_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="funding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                            <select id="funding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('funding_source') }}" name="funding_source">

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
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="fundDur" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="fundingdur" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                            <input id="fundingdur" name="fundingdur" type="number" max="30" class="form-control @error('fundingdur') is-invalid @enderror" value="{{ $purchaseFun->funding_duration }}" autocomplete="fundingdur">


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
                            <input id="fundingpersonal" name="fundingpersonal" type="number" class="form-control funding_personal_m @error('fundingpersonal') is-invalid @enderror" value="{{ $purchaseFun->personalFun_cost }}" autocomplete="fundingpersonal">


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
                            <input id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1" max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ $purchaseFun->personalFun_pre }}" autocomplete="fundingpersonalp">


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
                            <input id="fundingreal" name="fundingreal" type="number" class="form-control @error('fundingreal') is-invalid @enderror" value="{{ $purchaseFun->realFun_cost }}" autocomplete="fundingreal">


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
                            <input id="fundingrealp" name="fundingrealp" type="number" min="0.1" max="100" class="form-control @error('fundingrealp') is-invalid @enderror" value="{{ $purchaseFun->realFun_pre }}" autocomplete="fundingrealp">


                            @error('fundingrealp')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                            <input id="salary1" name="salary1" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary" readonly>
                        </div>

                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="fundDed" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="dedp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                            <input id="dedp" name="dedp" type="number" min="0.1" max="100" class="form-control @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ $purchaseFun->ded_pre }}" autocomplete="dedp">


                            @error('dedp')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="fundMonth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="monthIn" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                            <input id="" name="monthIn" type="number" class="form-control" value="{{ old('monthIn',$purchaseFun->monthly_in) }}" autocomplete="monthIn" >
                        </div>

                    </div>
                </div>
            @else
                <div  id="tableAdminOption" class="row">
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
                    <div class="col-6">
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
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                            <input readonly id="salary1" name="salary1" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary" readonly>
                        </div>

                    </div>
                    <div class="col-3">
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
                    <div class="col-5">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="fundMonth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="monthIn" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                            <input readonly id="monthIn" name="monthIn" type="number" class="form-control" value="{{ $purchaseFun->monthly_in }}" autocomplete="monthIn" readonly>
                        </div>

                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

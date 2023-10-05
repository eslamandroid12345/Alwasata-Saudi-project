<!-- Status mor pur of agent-->
<div class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-briefcase"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div  id="tableAdminOption" class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="funding_source" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="funding_source" >{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                        <select  {{$reqStatus == 26 ? 'disabled' : ''}} name="funding_source" id="funding_source" class="form-control select2-request @error('funding_source') is-invalid @enderror"  value="{{ old('funding_source') }}">
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundDur"  data-toggle="modal"  data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>

                        <label for="fundingdur" >{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}}  id="fundingdur" name="fundingdur" type="number" max="30" class="form-control @error('fundingdur') is-invalid @enderror" value="{{  old('fundingdur',$purchaseFun->funding_duration) }}" autocomplete="fundingdur">
                        @error('fundingdur')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundPers" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingpersonal">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}}  id="fundingpersonal" name="fundingpersonal" type="number" class="form-control @error('fundingpersonal') is-invalid @enderror" value="{{  old('fundingpersonal',$purchaseFun->personalFun_cost) }}" autocomplete="fundingpersonal">
                        @error('fundingpersonal')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundPersPre"  data-toggle="modal"  data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingpersonalp" >{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}}  id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1"  max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ old('fundingpersonalp',$purchaseFun->personalFun_pre) }}" autocomplete="fundingpersonalp">
                        @error('fundingpersonalp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundReal"  data-toggle="modal"  data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingreal">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="fundingreal" name="fundingreal" type="number" class="form-control @error('fundingreal') is-invalid @enderror" value="{{ old('fundingreal',$purchaseFun->realFun_cost) }}" autocomplete="fundingreal">
                        @error('fundingreal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundRealPre"  data-toggle="modal"  data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="fundingrealp" >{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="fundingrealp" name="fundingrealp" type="number" min="0.1"  max="100" class="form-control @error('fundingrealp') is-invalid @enderror" value="{{ old('fundingrealp',$purchaseFun->realFun_pre) }}" autocomplete="fundingrealp">
                        @error('fundingrealp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="salary">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="salary1" name="salary1" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundDed"  data-toggle="modal"  data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="dedp" >{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="dedp" name="dedp" type="number" min="0.1"  max="100" class="form-control @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ old('dedp',$purchaseFun->ded_pre) }}" autocomplete="dedp">
                        @error('dedp')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="fundMonth"  data-toggle="modal"  data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="monthIn" >{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="" name="monthIn" type="number" class="form-control" value="{{ old('monthIn',$purchaseFun->monthly_in) }}" autocomplete="monthIn" />
                        @error('monthIn')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

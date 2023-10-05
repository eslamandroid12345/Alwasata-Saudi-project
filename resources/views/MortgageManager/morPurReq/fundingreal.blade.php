<!-- Status mor pur of agent-->
<div class="userFormsInfo  ">
    <div class="headER topRow text-center">
        <i class="fas fa-home"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}</h4>
    </div>
    <div class="addUser my-4 topRow ">
        <div class="userBlock d-flex align-items-center justify-content-center flex-wrap">
            <div class="addBtn">
                <button class="print text-white">
                    <a href="{{ route('all.aqarReport',['id'=>$id])}}" class="text-white" target="_blank" style="text-decoration: none">
                        <i class="fas fa-print mr-1"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Print Aqar Completed Report') }}</a>
                </button>
            </div>
        </div>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
        @if ($reqStatus != 23 && $reqStatus != 25  && $reqStatus != 26 && $reqStatus != 27) <!-- Status mor pur of funding manager-->
                <div id="tableAdminOption" class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</label>
                            <input id="realname" name="realname" type="text" class="form-control @error('realname') is-invalid @enderror" value="{{ old('realname',$purchaseReal->name) }}" autocomplete="realname" autofocus placeholder="">
                            @error('realname')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</label>
                            <input id="realmobile" name="realmobile" type="tel" class="form-control @error('realmobile') is-invalid @enderror" value="{{ old('realmobile',$purchaseReal->mobile) }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx">
                            @error('realmobile')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realCity" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realcity" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                            <select id="realcity" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('realcity') is-invalid @enderror" name="realcity">


                                <option value="" selected>---</option>
                                @foreach ($cities as $citiy)
                                    @if ($purchaseReal->city == $citiy->id || (old('realcity') == $citiy->id) )
                                        <option value="{{$citiy->id}}" selected>{{$citiy->value}}</option>
                                    @else
                                        <option value="{{$citiy->id}}">{{$citiy->value}}</option>
                                    @endif
                                @endforeach

                            </select>

                            @error('realcity')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realRegion" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realregion" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</label>
                            <input id="realregion" name="realregion" type="text" class="form-control @error('realregion') is-invalid @enderror" value="{{ old('realregion',$purchaseReal->region) }}">
                            @error('realregion')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realPursuit" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realpursuit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</label>
                            <input id="realpursuit" name="realpursuit" type="number" class="form-control @error('realpursuit') is-invalid @enderror" value="{{ old('realpursuit',$purchaseReal->pursuit) }}">
                            @error('realpursuit')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realStatus" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realstatus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>

                            <select id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('realstatus') is-invalid @enderror" name="realstatus">


                                <option value="">---</option>
                                <option value="مكتمل" @if (old('realstatus')=='مكتمل' ) selected="selected" @elseif ($purchaseReal->status == 'مكتمل') selected="selected" @endif>مكتمل</option>
                                <option value="عظم" @if (old('realstatus')=='عظم' ) selected="selected" @elseif ($purchaseReal->status == 'عظم') selected="selected" @endif>عظم</option>


                            </select>

                            @error('realstatus')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realAge" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age')}}</label>
                            <input id="realage" name="realage" type="number" class="form-control @error('realage') is-invalid @enderror" value="{{ old('realage',$purchaseReal->age) }}" autocomplete="realage" autofocus>
                            @error('realage')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realType" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>

                            <select id="realtype" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('realtype') is-invalid @enderror" name="realtype">


                                <option value="" selected>---</option>

                                @foreach($realTypes as $realType)

                                    @if ((old('realtype')== $realType->id ) || ($purchaseReal->type == $realType->id))
                                        <option value="{{$realType->id}}" selected>{{$realType->value}}</option>
                                    @else
                                        <option value="{{$realType->id}}">{{$realType->value}}</option>
                                    @endif

                                @endforeach


                            </select>

                            @error('realtype')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realcost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ old('realcost',$purchaseReal->cost) }}" autocomplete="realcost" autofocus>
                            @error('realcost')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="owning_property" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="owning_property" class="control-label mb-1">هل يمتلك العميل عقار</label>

                            <select id="owning_property" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('owning_property') is-invalid @enderror" name="owning_property">


                                <option value="">---</option>
                                <option value="yes" @if (old('owning_property')=='yes' ) selected="selected" @elseif ($purchaseReal->owning_property == 'yes') selected="selected" @endif>{{ MyHelpers::guest_trans('Yes') }}</option>
                                <option value="no" @if (old('owning_property')=='no' ) selected="selected" @elseif ($purchaseReal->owning_property == 'no') selected="selected" @endif>{{ MyHelpers::guest_trans('No') }}</option>


                            </select>

                            @error('owning_property')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realhasprop" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has property?') }}</label>
                            <div class="row">
                                @if ($purchaseReal->has_property == 'نعم' || (old('realhasprop')) == 'نعم' || $purchaseReal->has_property == 'yes' || (old('realhasprop')) == 'yes')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop" checked>
                                            <label class="custom-control-label" for="hasyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                            <label class="custom-control-label" for="hasno">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->has_property == 'لا' || (old('realhasprop')) == 'لا' || $purchaseReal->has_property == 'no' || (old('realhasprop')) == 'no')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                            <label class="custom-control-label" for="hasyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop" checked>
                                            <label class="custom-control-label" for="hasno">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                            <label class="custom-control-label" for="hasyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                            <label class="custom-control-label" for="hasno">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realeva" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</label>
                            <div class="row">

                                @if ($purchaseReal->evaluated == 'نعم' || (old('realeva')) == 'نعم' )
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva" checked>
                                            <label class="custom-control-label" for="evayes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                            <label class="custom-control-label" for="evano">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->evaluated == 'لا' || (old('realeva')) == 'لا' )
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                            <label class="custom-control-label" for="evayes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva" checked>
                                            <label class="custom-control-label" for="evano">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                            <label class="custom-control-label" for="evayes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                            <label class="custom-control-label" for="evano">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realten" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                            <div class="row">
                                @if ($purchaseReal->tenant == 'نعم' || (old('realten')) == 'نعم')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten" checked>
                                            <label class="custom-control-label" for="tenyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                            <label class="custom-control-label" for="tenno">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->tenant == 'لا' || (old('realten')) == 'لا')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                            <label class="custom-control-label" for="tenyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten" checked>
                                            <label class="custom-control-label" for="tenno">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                            <label class="custom-control-label" for="tenyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                            <label class="custom-control-label" for="tenno">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realmor" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</label>
                            <div class="row">

                                @if ($purchaseReal->mortgage == 'نعم' || (old('realmor')) == 'نعم')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor" checked>
                                            <label class="custom-control-label" for="moryes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                            <label class="custom-control-label" for="morno">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->mortgage == 'لا' || (old('realmor')) == 'لا')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                            <label class="custom-control-label" for="moryes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="morno" name="realmor" checked>
                                            <label class="custom-control-label" for="morno">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                            <label class="custom-control-label" for="moryes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                            <label class="custom-control-label" for="morno">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div id="tableAdminOption" class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</label>
                            <input readonly id="realname" name="realname" type="text" class="form-control @error('realname') is-invalid @enderror" value="{{ $purchaseReal->name }}" autocomplete="realname" autofocus placeholder="">
                            @error('realname')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</label>
                            <input readonly id="realmobile" name="realmobile" type="tel" class="form-control @error('realmobile') is-invalid @enderror" value="{{ $purchaseReal->mobile }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx">
                            @error('realmobile')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realCity" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realcity" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                            <select disabled id="realcity" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('realcity') is-invalid @enderror" name="realcity">


                                <option value="" selected>---</option>
                                @foreach ($cities as $citiy)
                                    @if ($purchaseReal->city == $citiy->id || (old('realcity') == $citiy->id) )
                                        <option value="{{$citiy->id}}" selected>{{$citiy->value}}</option>
                                    @else
                                        <option value="{{$citiy->id}}">{{$citiy->value}}</option>
                                    @endif
                                @endforeach

                            </select>



                            @error('realcity')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realRegion" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realregion" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</label>
                            <input readonly id="realregion" name="realregion" type="text" class="form-control @error('realregion') is-invalid @enderror" value="{{ old('realregion',$purchaseReal->region) }}">
                            @error('realregion')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realPursuit" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realpursuit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</label>
                            <input readonly id="realpursuit" name="realpursuit" type="number" class="form-control @error('realpursuit') is-invalid @enderror" value="{{ old('realpursuit',$purchaseReal->pursuit) }}">
                            @error('realpursuit')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realStatus" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realstatus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>

                            <select disabled id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('realstatus') is-invalid @enderror" name="realstatus">

                                @if ($purchaseReal->status == 'مكتمل')
                                    <option value="">---</option>
                                    <option value="مكتمل" selected>مكتمل</option>
                                    <option value="عظم">عظم</option>
                                @elseif ($purchaseReal->status == 'عظم')
                                    <option value="">---</option>
                                    <option value="مكتمل">مكتمل</option>
                                    <option value="عظم" selected>عظم</option>
                                @else
                                    <option value="">---</option>
                                    <option value="مكتمل">مكتمل</option>
                                    <option value="عظم">عظم</option>
                                @endif

                            </select>

                            @error('realstatus')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realAge" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age') }}</label>
                            <input readonly id="realage" name="realage" type="number" class="form-control @error('realage') is-invalid @enderror" value="{{ $purchaseReal->age }}" autocomplete="realage" autofocus>
                            @error('realage')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realType" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>

                            <select disabled id="realtype" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('realtype') is-invalid @enderror" name="realtype">


                                <option value="" selected>---</option>

                                @foreach($realTypes as $realType)

                                    @if ((old('realtype')== $realType->id ) || ($purchaseReal->type == $realType->id))
                                        <option value="{{$realType->id}}" selected>{{$realType->value}}</option>
                                    @else
                                        <option value="{{$realType->id}}">{{$realType->value}}</option>
                                    @endif

                                @endforeach


                            </select>

                            @error('realtype')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realcost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input readonly id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ old('realcost',$purchaseReal->cost) }}" autocomplete="realcost" autofocus>
                            @error('realcost')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="owning_property" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="owning_property" class="control-label mb-1">هل يمتلك العميل عقار</label>

                            <select disabled id="owning_property" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('owning_property') is-invalid @enderror" name="owning_property">


                                <option value="">---</option>
                                <option value="yes" @if (old('owning_property')=='yes' ) selected="selected" @elseif ($purchaseReal->owning_property == 'yes') selected="selected" @endif>{{ MyHelpers::guest_trans('Yes') }}</option>
                                <option value="no" @if (old('owning_property')=='no' ) selected="selected" @elseif ($purchaseReal->owning_property == 'no') selected="selected" @endif>{{ MyHelpers::guest_trans('No') }}</option>


                            </select>

                            @error('owning_property')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realhasprop" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has property?') }}</label>
                            <div class="row">
                                @if ($purchaseReal->has_property == 'نعم' || (old('realhasprop')) == 'نعم' || $purchaseReal->has_property == 'yes' || (old('realhasprop')) == 'yes')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop" checked>
                                            <label class="custom-control-label" for="hasyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                            <label class="custom-control-label" for="hasno">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->has_property == 'لا' || (old('realhasprop')) == 'لا' || $purchaseReal->has_property == 'no' || (old('realhasprop')) == 'no')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                            <label class="custom-control-label" for="hasyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop" checked>
                                            <label class="custom-control-label" for="hasno">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                            <label class="custom-control-label" for="hasyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                            <label class="custom-control-label" for="hasno">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realeva" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</label>
                            <div class="row">

                                @if ($purchaseReal->evaluated == 'نعم')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva" checked>
                                            <label class="custom-control-label" for="evayes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                            <label class="custom-control-label" for="evano">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->evaluated == 'لا')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                            <label class="custom-control-label" for="evayes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="evano" name="realeva" checked>
                                            <label class="custom-control-label" for="evano">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                            <label class="custom-control-label" for="evayes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                            <label class="custom-control-label" for="evano">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realten" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                            <div class="row">
                                @if ($purchaseReal->tenant == 'نعم')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten" checked>
                                            <label class="custom-control-label" for="tenyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                            <label class="custom-control-label" for="tenno">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->tenant == 'لا')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                            <label class="custom-control-label" for="tenyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="tenno" name="realten" checked>
                                            <label class="custom-control-label" for="tenno">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                            <label class="custom-control-label" for="tenyes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                            <label class="custom-control-label" for="tenno">لا</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realmor" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</label>
                            <div class="row">

                                @if ($purchaseReal->mortgage == 'نعم')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor" checked>
                                            <label class="custom-control-label" for="moryes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                            <label class="custom-control-label" for="morno">لا</label>
                                        </div>
                                    </div>
                                @elseif ($purchaseReal->mortgage == 'لا')
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                            <label class="custom-control-label" for="moryes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="morno" name="realmor" checked>
                                            <label class="custom-control-label" for="morno">لا</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-6">

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                            <label class="custom-control-label" for="moryes">نعم</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input disabled type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                            <label class="custom-control-label" for="morno">لا</label>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

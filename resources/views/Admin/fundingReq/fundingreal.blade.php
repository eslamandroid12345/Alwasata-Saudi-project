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
            <div id="tableAdminOption" class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realName" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realname">{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</label>
                        <input id="realname" name="realname" type="text" class="form-control @error('realname') is-invalid @enderror" value="{{ old('realname',$purchaseReal->name) }}" autocomplete="realname" autofocus placeholder="">
                        @error('realname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realMobile" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realmobile" >{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</label>
                        <input id="realmobile" name="realmobile" type="tel" class="form-control @error('realmobile') is-invalid @enderror" value="{{ old('realmobile',$purchaseReal->mobile) }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx">
                        @error('realmobile')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realCity" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realcity" >{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                        <select id="realcity" class="form-control select2-request @error('realcity') is-invalid @enderror" name="realcity">


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
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realRegion" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realregion" >{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</label>
                        <input id="realregion" name="realregion" type="text" class="form-control @error('realregion') is-invalid @enderror" value="{{ old('realregion',$purchaseReal->region) }}">
                        @error('realregion')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realPursuit" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realpursuit" >{{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</label>
                        <input id="realpursuit" name="realpursuit" type="number" class="form-control @error('realpursuit') is-invalid @enderror" value="{{ old('realpursuit',$purchaseReal->pursuit) }}">
                        @error('realpursuit')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realStatus" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realstatus" >{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>
                        <select id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('realstatus') is-invalid @enderror" name="realstatus">
                            <option value="">---</option>
                            <option value="مكتمل" @if (old('realstatus')=='مكتمل' ) selected="selected" @elseif ($purchaseReal->status == 'مكتمل') selected="selected" @endif>مكتمل</option>
                            <option value="عظم" @if (old('realstatus')=='عظم' ) selected="selected" @elseif ($purchaseReal->status == 'عظم') selected="selected" @endif>عظم</option>
                        </select>
                        @error('realstatus')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realAge" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realage" >{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age')}}</label>
                        <input id="realage" name="realage" type="number" class="form-control @error('realage') is-invalid @enderror" value="{{ old('realage',$purchaseReal->age) }}" autocomplete="realage" autofocus>
                        @error('realage')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="residence_type" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="residence_type" class="control-label mb-1">المسكن</label>
                        <select id="residence_type" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('residence_type') is-invalid @enderror" name="residence_type" >


                            <option value="">---</option>
                            <option value="1" @if (old('residence_type')=='1' ) selected="selected" @elseif ($purchaseReal->residence_type == '1') selected="selected" @endif>مسكن أول</option>
                            <option value="2" @if (old('residence_type')=='2' ) selected="selected" @elseif ($purchaseReal->residence_type == '2') selected="selected" @endif>مسكن ثاني</option>


                        </select>

                        @error('residence_type')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="mortValue" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="mortgage_value" >{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</label>
                        <input id="mortgage_value" name="mortgage_value" type="number" class="form-control @error('mortgage_value') is-invalid @enderror" value="{{ old('mortgage_value',$purchaseReal->mortgage_value) }}">
                        @error('mortgage_value')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realType" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realType" >{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>
                        <select id="realtype"  class="form-control select2-request @error('realtype') is-invalid @enderror" name="realtype">


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
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="realCost" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="realcost" >{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                        <input id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ old('realcost',$purchaseReal->cost) }}" autocomplete="realcost" autofocus>
                        @error('realcost')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="owning_property" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="owning_property" >هل يمتلك العميل عقار</label>
                        <select id="owning_property" class="form-control @error('owning_property') is-invalid @enderror" name="owning_property">
                            <option value="">---</option>
                            <option value="yes" @if (old('owning_property')=='yes' ) selected="selected" @elseif ($purchaseReal->owning_property == 'yes') selected="selected" @endif>{{ MyHelpers::guest_trans('Yes') }}</option>
                            <option value="no" @if (old('owning_property')=='no' ) selected="selected" @elseif ($purchaseReal->owning_property == 'no') selected="selected" @endif>{{ MyHelpers::guest_trans('No') }}</option>
                        </select>
                        @error('owning_property')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="realhasprop" >{{ MyHelpers::admin_trans(auth()->user()->id,'has property?') }}</label>
                        <div class="row" style="padding-top: 10px">
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="realeva" >{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</label>
                        <div class="row" style="padding-top: 10px">
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="realten" >{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                        <div class="row" style="padding-top: 10px">
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="realmor" >{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</label>
                        <div class="row" style="padding-top: 10px">

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
        </div>

    </div>
</div>

@section('scripts')
<script>

</script>
@endsection

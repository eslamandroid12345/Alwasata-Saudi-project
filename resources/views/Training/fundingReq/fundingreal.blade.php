<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}</h3>
                </div>
                <div class="table-data__tool-right">
                    <a href="{{ route('all.aqarReport',['id'=>$id])}}" target="_blank">
                        <button type="button" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                            <i class="zmdi zmdi-print"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Print Aqar Completed Report') }}</button></a>
                </div>
                <hr>


                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</label>
                            <input readonly id="realname" name="realname" type="text" class="form-control @error('realname') is-invalid @enderror" value="{{ $purchaseReal->name }}" autocomplete="realname" autofocus placeholder="">
                            @error('realname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</label>
                            <input readonly id="realmobile" name="realmobile" type="tel" class="form-control @error('realmobile') is-invalid @enderror" value="{{ $purchaseReal->mobile }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx">
                            @error('realmobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                </div>




                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realCity" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realRegion" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realregion" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</label>
                            <input readonly id="realregion" name="realregion" type="text" class="form-control @error('realregion') is-invalid @enderror" value="{{ old('realregion',$purchaseReal->region) }}">
                            @error('realregion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-4">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realPursuit" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realpursuit" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</label>
                            <input readonly id="realpursuit" name="realpursuit" type="number" class="form-control @error('realpursuit') is-invalid @enderror" value="{{ old('realpursuit',$purchaseReal->pursuit) }}">
                            @error('realpursuit')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                </div>



                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realStatus" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realAge" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age') }}</label>
                            <input readonly id="realage" name="realage" type="number" class="form-control @error('realage') is-invalid @enderror" value="{{ $purchaseReal->age }}" autocomplete="realage" autofocus>
                            @error('realage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
              
                </div>



                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realcost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input readonly id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ old('realcost',$purchaseReal->cost) }}" autocomplete="realcost" autofocus>
                            @error('realcost')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                </div>


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
    </div>
</div>

@section('scripts')
<script>

</script>
@endsection
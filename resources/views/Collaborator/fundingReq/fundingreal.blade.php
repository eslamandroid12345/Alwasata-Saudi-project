<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

        @if ($purchaseCustomer-> is_canceled == 0 && ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 4)) <!-- Status req of Sales agent-->
            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}</h3>
                </div>
                <hr>


                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <button class="item" id="record" data-id="realName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</label>
                            <input id="realname" name="realname" type="text" class="form-control @error('realname') is-invalid @enderror" value="{{ $purchaseReal->name }}" autocomplete="realname" autofocus placeholder="">
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
                            <input id="realmobile" name="realmobile" type="tel" class="form-control @error('realmobile') is-invalid @enderror" value="{{ $purchaseReal->mobile }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx">
                            @error('realmobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="form-group">
                <button class="item" id="record" data-id="realCity" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                    <label for="realcity" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                    <select id="realcity" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('realcity') is-invalid @enderror" name="realcity">


                        @if ($purchaseReal->city == 'الرياض')
                        <option value="">---</option>
                        <option value="الرياض" selected>الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>

                        @elseif ($purchaseReal->city == 'جدة')
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة" selected>جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>
                        @elseif ($purchaseReal->city == 'الدمام')
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام" selected>الدمام</option>
                        <option value="أبها">أبها</option>
                        @elseif ($purchaseReal->city == 'أبها')
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها" selected>أبها</option>
                        @else
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>
                        @endif

                    </select>

                    @error('realcity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>



                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                        <button class="item" id="record" data-id="realStatus" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realstatus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>

                            <select id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('realstatus') is-invalid @enderror" name="realstatus">

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
                    <div class="col-4">
                        <div class="form-group">
                        <button class="item" id="record" data-id="realAge" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age')}}</label>
                            <input id="realage" name="realage" type="number" class="form-control @error('realage') is-invalid @enderror" value="{{ $purchaseReal->age }}" autocomplete="realage" autofocus>
                            @error('realage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                        <button class="item" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realcost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ $purchaseReal->cost }}" autocomplete="realcost" autofocus>
                            @error('realcost')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                <button class="item" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                    <label for="realtype" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>
                    <div class="row">

                        @if ($purchaseReal->type == 'فيلا')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype" checked>
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype">
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="أرض" id="land" name="realtype">
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="آخر" id="other" name="realtype">
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->type == 'مبنى')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype">
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype" checked>
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="أرض" id="land" name="realtype">
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="آخر" id="other" name="realtype">
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->type == 'أرض')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype">
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype">
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="أرض" id="land" name="realtype" checked>
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="آخر" id="other" name="realtype">
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->type == 'آخر')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype">
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype">
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="أرض" id="land" name="realtype" checked>
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="آخر" id="other" name="realtype" checked>
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>

                @if ($purchaseReal->type == 'آخر')
                <div class="form-group" id="othervalue">
                    <label for="othervalue" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                    <input id="otherinput" name="othervalue" type="text" class="form-control" value="{{ $purchaseReal->other_value }}" autofocus placeholder="">
                </div>
                @else
                <div class="form-group" style="display:none;" id="othervalue">
                    <label for="othervalue" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                    <input id="otherinput" name="othervalue" type="text" class="form-control" autofocus placeholder="">
                </div>
                @endif




                <div class="form-group">
                    <label for="realeva" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                    <div class="row">

                        @if ($purchaseReal->evaluated == 'نعم')
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
                        @elseif ($purchaseReal->evaluated == 'لا')
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
                        @endif
                    </div>

                </div>

                <div class="form-group">
                    <label for="realten" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                    <div class="row">
                        @if ($purchaseReal->tenant == 'نعم')
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
                        @elseif ($purchaseReal->tenant == 'لا')
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
                        @endif
                    </div>

                </div>

                <div class="form-group">
                    <label for="realmor" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</label>
                    <div class="row">

                        @if ($purchaseReal->mortgage == 'نعم')
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
                        @elseif ($purchaseReal->mortgage == 'لا')
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
                        @endif
                    </div>

                </div>


            </div>
            @else

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}</h3>
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

                <div class="form-group">
                <button class="item" id="record" data-id="realCity" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                    <label for="realcity" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                    <select disabled id="realcity" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('realcity') is-invalid @enderror" name="realcity">


                        @if ($purchaseReal->city == 'الرياض')
                        <option value="">---</option>
                        <option value="الرياض" selected>الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>

                        @elseif ($purchaseReal->city == 'جدة')
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة" selected>جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>
                        @elseif ($purchaseReal->city == 'الدمام')
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام" selected>الدمام</option>
                        <option value="أبها">أبها</option>
                        @elseif ($purchaseReal->city == 'أبها')
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها" selected>أبها</option>
                        @else
                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>
                        @endif

                    </select>

                    @error('realcity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>



                <div class="row">
                    <div class="col-4">
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
                    <div class="col-4">
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
                    <div class="col-4">
                        <div class="form-group">
                        <button class="item" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="realcost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input readonly id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ $purchaseReal->cost }}" autocomplete="realcost" autofocus>
                            @error('realcost')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                <button class="item" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                    <label for="realtype" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>
                    <div class="row">

                        @if ($purchaseReal->type == 'فيلا')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype" checked>
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype">
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="أرض" id="land" name="realtype">
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="آخر" id="other" name="realtype">
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->type == 'مبنى')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype">
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype" checked>
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="أرض" id="land" name="realtype">
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="آخر" id="other" name="realtype">
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->type == 'أرض')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype">
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype">
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="أرض" id="land" name="realtype" checked>
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="آخر" id="other" name="realtype">
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->type == 'آخر')
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="فيلا" id="villa" name="realtype">
                                <label class="custom-control-label" for="villa">فيلا</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="مبنى" id="build" name="realtype">
                                <label class="custom-control-label" for="build">مبنى</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input  disabled type="radio" class="custom-control-input" value="أرض" id="land" name="realtype" checked>
                                <label class="custom-control-label" for="land">أرض</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="آخر" id="other" name="realtype" checked>
                                <label class="custom-control-label" for="other">آخر</label>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>

                @if ($purchaseReal->type == 'آخر')
                <div class="form-group" id="othervalue">
                    <label for="othervalue" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                    <input readonly id="otherinput" name="othervalue" type="text" class="form-control" value="{{ $purchaseReal->other_value }}" autofocus placeholder="">
                </div>
                @else
                <div class="form-group" style="display:none;" id="othervalue">
                    <label for="othervalue" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                    <input readonly id="otherinput" name="othervalue" type="text" class="form-control" autofocus placeholder="">
                </div>
                @endif




                <div class="form-group">
                    <label for="realeva" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
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
                                <input  disabled type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                <label class="custom-control-label" for="tenyes">نعم</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input  disabled type="radio" class="custom-control-input" value="لا" id="tenno" name="realten" checked>
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
                        @endif
                    </div>

                </div>


            </div>

            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>

</script>
@endsection
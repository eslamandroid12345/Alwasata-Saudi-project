<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}</h3>
                </div>
                <hr>


                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="realname" class="control-label mb-1"> {{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}</label>
                            <input id="realname" name="realname" type="text" class="form-control @error('realname') is-invalid @enderror" value="{{ old('realname') }}" autocomplete="realname" autofocus placeholder="">
                            @error('realname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="realmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}</label>
                            <input id="realmobile" name="realmobile" type="tel" class="form-control @error('realmobile') is-invalid @enderror" value="{{ old('realmobile') }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx">
                            @error('realmobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <label for="realcity" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                    <select id="realcity" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' value="{{ old('realcity') }}" class="form-control @error('realcity') is-invalid @enderror" name="realcity">

                        <option value="">---</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="أبها">أبها</option>

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
                            <label for="realstatus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>

                            <select id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' value="{{ old('realstatus') }}" class="form-control @error('realstatus') is-invalid @enderror" name="realstatus">

                                <option value="">---</option>
                                <option value="مكتمل">مكتمل</option>
                                <option value="عظم">عظم</option>

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
                            <label for="realage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate age') }}</label>
                            <input id="realage" name="realage" type="number" class="form-control @error('realage') is-invalid @enderror" value="{{ old('realage') }}" autocomplete="realage" autofocus>
                            @error('realage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="realcost" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input id="realcost" name="realcost" type="number" class="form-control @error('realcost') is-invalid @enderror" value="{{ old('realcost') }}" autocomplete="realcost" autofocus>
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
                    </div>

                </div>

                <div class="form-group">
                    <label for="realtype" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>
                    <div class="row">
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
                    </div>

                </div>

                <div class="form-group" style="display:none;" id="othervalue">
                    <label for="othervalue" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                    <input name="othervalue" type="text" class="form-control" autofocus placeholder="">
                </div>




                <div class="form-group">
                    <label for="realeva" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</label>
                    <div class="row">
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
                    </div>

                </div>

                <div class="form-group">
                    <label for="realten" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                    <div class="row">
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
                    </div>

                </div>

                <div class="form-group">
                    <label for="realmor" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</label>
                    <div class="row">
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
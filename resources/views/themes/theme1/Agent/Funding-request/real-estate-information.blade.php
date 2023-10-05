 <!-- begin::portlet  -->
 <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head">
      <!-- begin::portlet__head-label  -->
      <div class="portlet__head-label">بيانات العقار</div>
      <a class="btn btn-primary" href="{{ route('all.aqarReport',['id'=>$id])}}" target="_blank">
        <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.325" height="15.325" viewBox="0 0 15.325 15.325">
          <g id="Icon_feather-printer" data-name="Icon feather-printer" transform="translate(-2.5 -2.5)">
            <path id="Path_3985" data-name="Path 3985" d="M9,8.014V3h8.6V8.014" transform="translate(-3.135)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
            <path
              id="Path_3986"
              data-name="Path 3986"
              d="M5.865,19.946H4.433A1.433,1.433,0,0,1,3,18.514V14.933A1.433,1.433,0,0,1,4.433,13.5h11.46a1.433,1.433,0,0,1,1.433,1.433v3.581a1.433,1.433,0,0,1-1.433,1.433H14.46"
              transform="translate(0 -5.486)"
              fill="none"
              stroke="#fff"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1"
            ></path>
            <path id="Path_3987" data-name="Path 3987" d="M9,21h8.6v5.73H9Z" transform="translate(-3.135 -9.405)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
          </g>
        </svg>
        طباعة استمارة إفراغ عقار
      </a>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    <div class="portlet__body pt-0">
      <div class="border rounded-5 p-3">
        <div class="row">

        @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="realName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'owner name') }}
              </label>
              <input id="realname" name="realname" type="text" class="form-control  realname_missedFiledInput FiledInput @error('realname') is-invalid @enderror" value="{{ old('realname',$purchaseReal->name) }}" autocomplete="realname" >
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="realMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'owner mobile') }}
              </label>
              {{-- <input class="form-control" type="text" value="059834252" /> --}}
              <input id="realmobile" name="realmobile" type="tel" class="form-control realmobile_missedFiledInput FiledInput @error('realmobile') is-invalid @enderror" value="{{ old('realmobile',$purchaseReal->mobile) }}" pattern="(05)[0-9]{8}" maxlength="10" autofocus placeholder="05xxxxxxxx" >

                <small style="color:#e60000" class="d-none realmobile_missedFileds missedFileds">الحقل مطلوب</small>


                @error('realmobile')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realcity" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realCity" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                            </svg>
                        </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'city') }}</label>
                <br>
                <select id="realcity" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); 'class="form-control realcity_missedFiledInput FiledInput selectpicker @error('realcity') is-invalid @enderror" data-live-search="true" name="realcity" >


                    <option value="" selected>---</option>
                    @foreach ($cities as $citiy)
                    @if ($purchaseReal->city == $citiy->id || (old('realcity') == $citiy->id) )
                    <option value="{{$citiy->id}}" selected>{{$citiy->value}}</option>
                    @else
                    <option value="{{$citiy->id}}">{{$citiy->value}}</option>
                    @endif
                    @endforeach

                </select>


                <small style="color:#e60000" class="d-none realcity_missedFileds missedFileds">الحقل مطلوب</small>


                @error('realcity')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror

                <!--SEEKING FOR PROPERTY AND CITY OF REAL-->
                @if(session()->has('message5'))
                <small style="color:maroon" role="alert">
                    <strong> {{ session()->get('message5') }}</strong>
                </small>
                @endif
                <!---->
            </div>
        </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realregion" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realRegion" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                            </svg>
                    </span>

                    {{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</label>
                <input id="realregion" name="realregion" type="text" class="form-control @error('realregion') is-invalid @enderror" value="{{ old('realregion',$purchaseReal->region) }}">
                @error('realregion')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realpursuit" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realPursuit" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                            </svg>
                    </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'pursuit') }}</label>
                <input id="realpursuit" name="realpursuit" type="number" class="form-control realpursuit_missedFiledInput FiledInput @error('realpursuit') is-invalid @enderror" value="{{ old('realpursuit',$purchaseReal->pursuit) }}" >



                <small style="color:#e60000" class="d-none realpursuit_missedFileds missedFileds">الحقل مطلوب</small>


                @error('realpursuit')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realstatus" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realStatus" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                        </svg>
                    </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>

                <select id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control  realstatus_missedFiledInput FiledInput @error('realstatus') is-invalid @enderror" name="realstatus" >


                    <option value="">---</option>
                    <option value="مكتمل" @if (old('realstatus')=='مكتمل' ) selected="selected" @elseif ($purchaseReal->status == 'مكتمل') selected="selected" @endif>مكتمل</option>
                    <option value="عظم" @if (old('realstatus')=='عظم' ) selected="selected" @elseif ($purchaseReal->status == 'عظم') selected="selected" @endif>عظم</option>


                </select>


                <small style="color:#e60000" class="d-none realstatus_missedFileds missedFileds">الحقل مطلوب</small>


                @error('realstatus')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror

            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realage" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realAge" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                        </svg>
                    </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'real estate age')}}</label>
                <input id="realage" name="realage" type="number" class="form-control realage_missedFiledInput FiledInput @error('realage') is-invalid @enderror" value="{{ old('realage',$purchaseReal->age) }}" autocomplete="realage" autofocus>


                <small style="color:#e60000" class="d-none realage_missedFileds missedFileds">الحقل مطلوب</small>


                @error('realage')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="residence_type" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="residence_type" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                        </svg>
                    </span>

                    المسكن</label>
                <select id="residence_type" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('residence_type') is-invalid @enderror selectpicker" data-live-search="true" name="residence_type">


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
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realType" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                        </svg>
                    </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>

                <select id="realtype" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class=" realtype_missedFiledInput FiledInput  @error('realtype') is-invalid @enderror selectpicker" data-live-search="true" name="realtype" >


                    <option value="" selected>---</option>

                    @foreach($realTypes as $realType)

                    @if ((old('realtype')== $realType->id ) || ($purchaseReal->type == $realType->id))
                    <option value="{{$realType->id}}" selected>{{$realType->value}}</option>
                    @else
                    <option value="{{$realType->id}}">{{$realType->value}}</option>
                    @endif

                    @endforeach


                </select>



                <small style="color:#e60000" class="d-none realtype_missedFileds missedFileds">الحقل مطلوب</small>


                @error('realtype')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror

            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realcost" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="realCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                        </svg>
                    </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                <input id="realcost" name="realcost" type="number" class="form-control realcost_missedFiledInput FiledInput realcost_m @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ old('realcost',$purchaseReal->cost) }}" autocomplete="realcost" autofocus>

                <small style="color:#e60000" class="d-none realcost_missedFileds missedFileds">الحقل مطلوب</small>

                @error('realcost')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="owning_property" class="control-label mb-1">
                    <span class="item pointer span-20" id="record" data-id="owning_property" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                        </svg>
                    </span>

                    هل يمتلك العميل عقار</label>

                <select id="owning_property" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control owning_property_missedFiledInput FiledInput @error('owning_property') is-invalid @enderror selectpicker" data-live-search="true" name="owning_property" >


                    <option value="">---</option>
                    <option value="yes" @if (old('owning_property')=='yes' ) selected="selected" @elseif ($purchaseReal->owning_property == 'yes') selected="selected" @endif>{{ MyHelpers::guest_trans('Yes') }}</option>
                    <option value="no" @if (old('owning_property')=='no' ) selected="selected" @elseif ($purchaseReal->owning_property == 'no') selected="selected" @endif>{{ MyHelpers::guest_trans('No') }}</option>


                </select>



                <small style="color:#e60000" class="d-none owning_property_missedFileds missedFileds">الحقل مطلوب</small>


                @error('owning_property')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror

            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realhasprop" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has property?') }}</label>
                <div class="row">
                    @if ($purchaseReal->has_property == 'نعم' || (old('realhasprop')) == 'نعم' || $purchaseReal->has_property == 'yes' || (old('realhasprop')) == 'yes')
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5" for="hasyes">
                                <input type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop" checked>
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @elseif ($purchaseReal->has_property == 'لا' || (old('realhasprop')) == 'لا' || $purchaseReal->has_property == 'no' || (old('realhasprop')) == 'no')
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <input type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                            <label class="custom-control-label" for="hasyes">نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop" checked>
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @else
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5" for="hasyes">
                                <input type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realeva" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat evaluated?') }}</label>
                <div class="row">

                    @if ($purchaseReal->evaluated == 'نعم' || (old('realeva')) == 'نعم' )
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva" checked>
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>

                    @elseif ($purchaseReal->evaluated == 'لا' || (old('realeva')) == 'لا' )
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva" checked>
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @else
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>

            {{-- Khaled  --}}
            <div class="col-lg-8 real_estate_is_evaluated">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <label for="realeva" class="control-label mb-1">التقييم</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex pt-2">
                                    <label class="m-radio ms-5">
                                        <input type="radio" class="custom-control-input" value="جهة التمويل" name="financing_or_tsaheel" @if (old('financing_or_tsaheel')=='جهة التمويل' ) checked="checked" @elseif ($purchaseReal->financing_or_tsaheel == 'جهة التمويل') checked="checked" @endif>
                                        <span class="checkmark"></span>جهة التمويل
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex pt-2">
                                    <label class="m-radio ms-5">
                                        <input type="radio" class="custom-control-input" value="تساهيل" name="financing_or_tsaheel" @if (old('financing_or_tsaheel')=='تساهيل' ) checked="checked" @elseif ($purchaseReal->financing_or_tsaheel == 'تساهيل') checked="checked" @endif>
                                        <span class="checkmark"></span>تساهيل
                                    </label>
                                </div>
                            </div>
                            @error('financing_or_tsaheel')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                            <label for="">مبلغ التقييم</label>
                            <input type="text" class="form-control @error('evaluation_amount') is-invalid @enderror" name="evaluation_amount" id="evaluation_amount" value="{{ old('evaluation_amount',$purchaseReal->evaluation_amount)}}">
                            @error('evaluation_amount')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>


        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realten" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'are there tenants?') }}</label>
                <div class="row">
                    @if ($purchaseReal->tenant == 'نعم' || (old('realten')) == 'نعم')
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten" checked>
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @elseif ($purchaseReal->tenant == 'لا' || (old('realten')) == 'لا')
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten" checked>
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @else
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label for="realmor" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is real estat mortgaged?') }}</label>
                <div class="row">

                    @if ($purchaseReal->mortgage == 'نعم' || (old('realmor')) == 'نعم')
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor" checked>
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @elseif ($purchaseReal->mortgage == 'لا' || (old('realmor')) == 'لا')
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="morno" name="realmor" checked>
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @else
                    <div class="col-6">

                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                <span class="checkmark"></span>نعم</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex pt-2">
                            <label class="m-radio ms-5">
                                <input type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                <span class="checkmark"></span>لا</label>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @else

        <div id="tableAdminOption" class=" row">
            <div class="col-lg-4 col-sm-6">
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
            <div class="col-lg-4 col-sm-6">
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
                    <select disabled id="realcity"  class=" @error('realcity') is-invalid @enderror selectpicker" data-live-search="true" name="realcity">


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
            <div class="col-4">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="realStatus" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="realstatus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate status') }}</label>

                    <select disabled id="realstatus" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class=" @error('realstatus') is-invalid @enderror selectpicker" data-live-search="true" name="realstatus">

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
            <div class="col-4">
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
                    <span class="item pointer span-20" id="record" data-id="residence_type" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="residence_type" class="control-label mb-1">المسكن</label>
                    <select disabled id="residence_type" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="@error('residence_type') is-invalid @enderror selectpicker" data-live-search="true" name="residence_type">


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
            <div class="col-4">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="realType" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="realType" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>

                    <select disabled id="realtype" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="@error('realtype') is-invalid @enderror selectpicker" data-live-search="true" name="realtype">


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
                    <input readonly id="realcost" name="realcost" type="number" class="form-control realcost_m @error('realcost') is-invalid @enderror" onblur="setCheckCost()" value="{{ old('realcost',$purchaseReal->cost) }}" autocomplete="realcost" autofocus>
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

                    <select disabled id="owning_property" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class=" @error('owning_property') is-invalid @enderror selectpicker" data-live-search="true" name="owning_property">


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
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="hasyes">
                                    <input disabled type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop" checked>
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="hasno">
                                    <input disabled type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                    <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->has_property == 'لا' || (old('realhasprop')) == 'لا' || $purchaseReal->has_property == 'no' || (old('realhasprop')) == 'no')
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="hasyes">
                                    <input disabled type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="hasno">
                                    <input disabled type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop" checked>
                                    <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="hasyes">
                                    <input disabled type="radio" class="custom-control-input" value="yes" id="hasyes" name="realhasprop">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="hasno">
                                    <input disabled type="radio" class="custom-control-input" value="no" id="hasno" name="realhasprop">
                                    <span class="checkmark"></span>لا</label>
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
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="evayes">
                                    <input disabled type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva" checked>
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="evano">
                                    <input disabled type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                    <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->evaluated == 'لا')
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="evayes">
                                    <input disabled type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="evano">
                                    <input disabled type="radio" class="custom-control-input" value="لا" id="evano" name="realeva" checked>
                                    <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="evayes">
                                    <input type="radio" class="custom-control-input" value="نعم" id="evayes" name="realeva">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="evano">
                                    <input type="radio" class="custom-control-input" value="لا" id="evano" name="realeva">
                                    <span class="checkmark"></span>لا</label>
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
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="tenyes">
                                    <input disabled type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten" checked>
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="tenno">
                                    <input disabled type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->tenant == 'لا')
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="tenyes">
                                    <input disabled type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="tenno">
                                    <input disabled type="radio" class="custom-control-input" value="لا" id="tenno" name="realten" checked>
                                    <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="tenyes">
                                    <input type="radio" class="custom-control-input" value="نعم" id="tenyes" name="realten">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="tenno">
                                    <input type="radio" class="custom-control-input" value="لا" id="tenno" name="realten">
                                    <span class="checkmark"></span>لا</label>
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
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor" checked>
                                <label class="m-radio ms-5" for="moryes">نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input disabled type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                <label class="m-radio ms-5" for="morno">لا</label>
                            </div>
                        </div>
                        @elseif ($purchaseReal->mortgage == 'لا')
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="moryes">
                                    <input disabled type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="morno">
                                    <input disabled type="radio" class="custom-control-input" value="لا" id="morno" name="realmor" checked>
                                    <span class="checkmark"></span>لا</label>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-4 col-sm-6">

                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="moryes">
                                    <input disabled type="radio" class="custom-control-input" value="نعم" id="moryes" name="realmor">
                                    <span class="checkmark"></span>نعم</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="custom-control custom-radio custom-control-inline">
                                <label class="m-radio ms-5" for="morno">
                                    <input disabled type="radio" class="custom-control-input" value="لا" id="morno" name="realmor">
                                    <span class="checkmark"></span>لا</label>
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
    <!-- end::portlet__body  -->
</div>
  <!-- end::portlet  -->


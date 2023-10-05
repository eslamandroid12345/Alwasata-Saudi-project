<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Information') }} {{ MyHelpers::admin_trans(auth()->user()->id,'user') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.editUser')}}" method="POST" id="frm-update">
                <div class="modal-body">
                    @csrf
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">
                    <!--here past addUserPage-->
                    <div class="form-group">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                        <div class="input-group">
                            <input type="text" id="name" name="name" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}" class="form-control" value="{{ old('name') }}">
                            <div class="input-group-addon">
                                <i class="fa fa-diamond"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="nameError" role="alert"> </span>

                        @if ($errors->has('name'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group" id="usernameDiv">
                        <label for="Username" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</label>
                        <div class="input-group">
                            <input type="text" id="username" name="username" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}" class="form-control" value="{{ old('username') }}">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="usernameError" role="alert"> </span>

                        @if ($errors->has('username'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name_for_admin" class="control-label mb-1">@lang('attributes.name_for_admin')</label>
                        <div class="input-group">
                            <input type="text" id="name_for_admin" name="name_for_admin" placeholder="" class="form-control" value="{{ old('name_for_admin') }}">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="name_for_adminError" role="alert"> </span>

                        @if ($errors->has('name_for_admin'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('name_for_admin') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="callCenterName" class="control-label mb-1">الاسم في الكول سينتر</label>
                        <div class="input-group">
                            <input type="text" id="callCenterName" name="callCenterName" class="form-control" value="{{ old('callCenterName') }}">
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="callCenterNameError" role="alert"> </span>

                        @if ($errors->has('callCenterName'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('callCenterName') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="Email" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Email') }}</label>
                        <div class="input-group">
                            <input type="email" id="email2" name="email" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Email') }}" class="form-control" value="{{ old('email') }}">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="emailError" role="alert"> </span>

                        @if ($errors->has('email'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="mobile" class="control-label mb-1">رقم الجوال</label>
                        <div class="input-group">
                            <input type="number" id="mobile" name="mobile" placeholder="5xxxxxxxx" class="form-control" value="{{ old('mobile') }}">
                            <div class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="mobileError" role="alert"> </span>

                        @if ($errors->has('mobile'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group" id="passwordDiv">
                        <label for="password" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}" class="form-control" value="" autocomplete="off">
                            <div class="input-group-addon">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>

                        <input type="checkbox" onclick="myFunction()">{{ MyHelpers::admin_trans(auth()->user()->id,'Show') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}

                        <span class="text-danger" id="passwordError" role="alert"> </span>

                        @if ($errors->has('password'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group" id="langDiv">
                        <label for="local" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Language') }}</label>
                        <div class="input-group">
                            <select id="locale" name="locale" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Language') }}" class="form-control">

                                @if (Input::old('locale') == 'en')
                                    <option value="en" selected> {{ MyHelpers::admin_trans(auth()->user()->id,'English') }} </option>
                                @else
                                    <option value="en"> {{ MyHelpers::admin_trans(auth()->user()->id,'English') }} </option>
                                @endif

                                @if (Input::old('locale') == 'ar')
                                    <option value="ar" selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Arabic') }} </option>
                                @else
                                    <option value="ar"> {{ MyHelpers::admin_trans(auth()->user()->id,'Arabic') }} </option>
                                @endif

                            </select>

                            <div class="input-group-addon">
                                <i class="fa fa-language"></i>
                            </div>
                        </div>

                        <span class="text-danger" id="localeError" role="alert"> </span>

                        @if ($errors->has('locale'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('locale') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="role" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'role') }}</label>
                        <div class="input-group">
                            <select id="role" name="role" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;'>
                                <option value='' selected>-----</option>
                                @foreach($RolesSelect as $role)
                                    <option {{$role['id'] == old('role') ? 'selected' : '' }} value="{{$role['id']}}">{!! $role['name'] !!}</option>
                                @endforeach
                                <option value='20'>أخري</option>
                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-bookmark"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="roleError" role="alert"> </span>

                        @if ($errors->has('role'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                        </span>
                        @endif
                    </div>


                    <div id="othersDiv"  class="form-group" style="display:none;">
                        <div class="form-group">
                            <label for="others">الوظيفة </label>
                            <input id="others" name="others" placeholder="المسمى الوظيفي" class="form-control" value="{{Input::old('others')}}">
                        </div>
                        @if ($errors->has('others'))
                            <span class="help-block col-md-12">
                                <strong style="color:red ;font-size:10pt">{{ $errors->first('others') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-12 mb-3 bank-delegate">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="bank_id">@lang('attributes.bank_id')</label>
                                    <select id="bank_id" name="bank_id" placeholder="@lang('attributes.bank_id')" class="form-control">
                                        <option value="0" selected disabled>{{old('bank_id')}}</option>
                                        @foreach($BanksSelect as $bank)
                                            <option value="{{$bank['id']}}" {{old('bank_id') == $bank['id'] ? 'selected' : ''}}>{{$bank['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger" id="bank_idError" role="alert"> </span>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="subdomain">@lang('attributes.subdomain')</label>
                                    <input id="subdomain" name="subdomain" placeholder="@lang('attributes.subdomain')" class="form-control" value="{{old('subdomain')}}">
                                </div>
                                <span class="text-danger" id="subdomainError" role="alert"> </span>
                            </div>
                        </div>
                    </div>

                    <div id="tsaheelDiv" class="form-group" style="display:none;">
                        <label for="isTsaheel" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Tsaheel Agent?') }}</label>
                        <div class="input-group">
                            <select id="isTsaheel" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); checkTsaheel2(this);' class="form-control @error('isTsaheel') is-invalid @enderror" name="isTsaheel">

                                <option value="0" @if (old('isTsaheel')=='0' ) selected="selected" @endif>لا</option>
                                <option value="1" @if (old('isTsaheel')=='1' ) selected="selected" @endif>نعم</option>

                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>
                        @error('isTsaheel')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                    <div id="accountantDiv" class="form-group" style="display:none;">
                        <label for="accountant_type" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Accountant type') }}</label>
                        <div class="input-group">
                            <select id="accountant_type" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('accountant_type') is-invalid @enderror" name="accountant_type">

                                <option value="" selected="selected">---</option>
                                <option value="0" @if (old('accountant_type')=='0' ) selected="selected" @endif>محاسب تساهيل</option>
                                <option value="1" @if (old('accountant_type')=='1' ) selected="selected" @endif>محاسب الوساطة</option>

                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-money"></i>
                            </div>
                        </div>

                        <span class="text-danger" id="accountant_typeError" role="alert"> </span>

                        @error('accountant_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>

                    <div id="salesmanagerDiv" class="form-group" style="display:none;">
                        <label for="salesmanager" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</label>
                        <div class="input-group">
                            <select id="salesmanager" name="salesmanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('salesmanager') }}">
                                <option value=''> -----</option>

                                @foreach ($salesManagers as $salesManager)

                                    <option value="{{$salesManager->id}}">{{$salesManager->name}}</option>

                                @endforeach

                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-users"></i>
                            </div>

                        </div>

                        <span class="text-danger" id="salesmanagerError" role="alert"> </span>

                        @if ($errors->has('salesmanager'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesmanager') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div id="fundingmanagerDiv" class="form-group" style="display:none;">
                        <label for="fundingmanager" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</label>
                        <div class="input-group">
                            <select id="fundingmanager" name="fundingmanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('fundingmanager') }}">

                                <option value=''> -----</option>

                                @foreach ($fundingManagers as $fundingManager)

                                    <option value="{{$fundingManager->id}}">{{$fundingManager->name}}</option>

                                @endforeach

                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>

                        </div>

                        <span class="text-danger" id="fundingmanagerError" role="alert"> </span>

                        @if ($errors->has('fundingmanager'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('fundingmanager') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div id="mortgagemanagerDiv" class="form-group" style="display:none;">
                        <label for="mortgagemanager" id="mortgage_label" class="control-label mb-1"></label>
                        <div class="input-group">
                            <select id="mortgagemanager" name="mortgagemanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('mortgagemanager') }}">

                                <option value=''> -----</option>

                                @foreach ($mortgageManagers as $mortgageManager)

                                    <option value="{{$mortgageManager->id}}">{{$mortgageManager->name}}</option>

                                @endforeach

                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-unlock-alt"></i>
                            </div>

                        </div>
                        <span class="text-danger" id="mortgagemanagerError" role="alert"> </span>

                        @if ($errors->has('mortgagemanager'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('mortgagemanager') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div id="generalmanagerDiv" class="form-group" style="display:none;">
                        <label for="generalmanager" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }}</label>
                        <div class="input-group">
                            <select id="generalmanager" name="generalmanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('generalmanager') }}">

                                <option value=''> -----</option>

                                @foreach ($generalManagers as $generalManager)

                                    <option value="{{$generalManager->id}}">{{$generalManager->name}}</option>

                                @endforeach

                            </select>
                            <div class="input-group-addon">
                                <i class="fa fa-sun-o"></i>
                            </div>

                        </div>

                        <span class="text-danger" id="generalmanagerError" role="alert"> </span>

                        @if ($errors->has('generalmanager'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('generalmanager') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div id="salesagentDiv" class="form-group" style="display:none;">
                        <label for="salesagent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>

                        <select id="salesagent" name="salesagents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2" multiple="multiple" style="width: 100%;">

                            @foreach ($salesAgents as $salesAgent)

                                <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>

                            @endforeach

                        </select>

                        <span class="text-danger" id="salesagentsError" role="alert"> </span>

                        @if ($errors->has('salesagents'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div id="qualtyDiv" class="form-group" style="display:none;">
                        <label for="quality" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>

                        <select id="quality" name="quality[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2 " multiple="multiple">

                            @foreach ($salesAgents as $salesAgent)

                                <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>


                            @endforeach

                        </select>

                        <span class="text-danger" id="qualityError" role="alert"> </span>

                        @if ($errors->has('quality'))
                            <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('quality') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div id="collabarator" style="display: none">
                        <input type="checkbox" id="domain_col" name="domain_col">
                        يظهر ضمن التقارير

                    </div>
                    <br>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

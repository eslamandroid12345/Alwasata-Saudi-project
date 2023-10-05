@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'users') }}
@endsection


@section('css_style')

    {{--    OLD STYLE --}}
    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .commentStyle {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .reqNum {
            width: 1%;
        }

        .reqType {
            width: 2%;
        }
    </style>

    {{--    NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
@endsection

@section('customer')




    @if(session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif

    @if(session()->has('message2'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message2') }}
        </div>
    @endif

    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3 id="textData">  {{ MyHelpers::admin_trans(auth()->user()->id,'users') }} :</h3>
            <div class="addBtn">{{--href="{{ route('admin.addUserPage')}}"--}}
                <a onclick="addUser()" id="add-btn">
                    <button>
                        <i class="fas fa-plus-circle"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'user') }}</button>
                </a>
                <button type="button" id="cancel-btn" style="display: none" class="btn btn-secondary" onclick="displayDatatable()">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }} والعودة لجميع المستخدمين</button>
            </div>
        </div>
    </div>

    <div class="tableBar" id="edit-user" style="display: none">
        <section class="new-content mt-5">
            <div class="container-fluid">
                <div class="row ">
                    {{--                <div class="col-md-8 offset-md-2">--}}
                    <div class="col-12">
                        <div class="row">
                            {{--                        <div class="col-lg-12 mb-md-0">--}}
                            <div class="col-md-8 mb-md-0 offset-md-2">
                                <div class="userFormsInfo  ">
                                    <div class="headER topRow text-center">
                                        <i class="fas fa-user"></i>
                                        <h4>@lang('global.userDetails')</h4>
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
                                                    <input type="password" id="password" name="password" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}" class="form-control" value="" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly');">
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
                                                    <select id="role" name="role" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;'  style="width: 100%;">
                                                        <option value='' selected>-----</option>
                                                        @foreach($RolesSelect as $role)
                                                            <option  value="{{$role['id']}}">{!! $role['name'] !!}</option>
                                                        @endforeach
                                                        <option value='20'>أخري</option>
                                                    </select>
                                                </div>
                                                <span class="text-danger" id="roleError" role="alert"> </span>

                                                @if ($errors->has('role'))
                                                    <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                        </span>
                                                @endif
                                            </div>
                                            <input type="hidden" name="check" id="check" value="0">


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

                                            <div class="col- mb-3 bank-delegate">
                                                <div class="row">

                                                    <div class="col-12 mb-3">
                                                        <label for="salesagents" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                                                        <select id="salesagents" name="salesagents_users[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2" multiple="multiple" style="width: 100%;">
                                                            <option value="all">الكل</option>
                                                            @foreach ($salesAgents as $salesAgent)
                                                                <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger" id="salesagentsError" role="alert"> </span>
                                                        <div id="activeError2"> </div>
                                                        @if ($errors->has('salesagents'))
                                                            <span class="help-block col-md-12">
                                                             <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label for="fundingmanagers" class="control-label mb-1">مشرفين التمويل</label>
                                                        <select id="fundingmanagers" name="fundingmanagers[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2" multiple="multiple" style="width: 100%;">
                                                            <option value="all">الكل</option>
                                                            @foreach ($fundingManagers as $fundingManager)
                                                                <option value="{{$fundingManager->id}}">{{$fundingManager->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger" id="fundingmanagerError" role="alert"> </span>

                                                        @if ($errors->has('fundingmanagers'))
                                                            <span class="help-block col-md-12">
                                                                <strong style="color:red ;font-size:10pt">{{ $errors->first('fundingmanager') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
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
                                                    <div class="col-12 mb-3">
                                                        <div class="form-group">
                                                            <label for="code">@lang('attributes.code')</label>
                                                            <input id="code" name="code" placeholder="@lang('attributes.code')" class="form-control" value="{{old('code')}}">
                                                        </div>
                                                        <span class="text-danger" id="codeError" role="alert"> </span>
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
                                                        <option value='' > -----</option>

                                                        @foreach ($salesManagers as $salesManager)

                                                            <option value="{{$salesManager->id}}">{{$salesManager->name}}</option>

                                                        @endforeach

                                                    </select>
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
                                                </div>

                                                <span class="text-danger" id="generalmanagerError" role="alert"> </span>

                                                @if ($errors->has('generalmanager'))
                                                    <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('generalmanager') }}</strong>
                        </span>
                                                @endif
                                            </div>

                                            <div id="salesagentDiv" class="form-" style="display:none;">
                                                <label for="salesagent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>

                                                <select id="salesagent" name="salesagents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2" multiple="multiple" style="width: 100%;">

                                                    @foreach ($salesAgents as $salesAgent)

                                                        <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>

                                                    @endforeach

                                                </select>

                                                <span class="text-danger" id="salesagentsError" role="alert"> </span>
                                                <div id="activeError"> </div>

                                                @if ($errors->has('salesagents'))
                                                    <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>


                            </span>
                                                @endif
                                                <div class="row">
                                                    <div class="col-12 mb-3 pt-5">
                                                        <label for=""><b>أين يخدم ؟</b></label>
                                                        <div class="form-group">
                                                            <label for="locale">{{ MyHelpers::admin_trans(auth()->user()->id,'area') }}</label>
                                                            <select id="area_id" multiple name="area_id[]" class="area  select2-request form-control @error('region') is-invalid @enderror">
                                                                @foreach($areas as $area)
                                                                    <option value="{{$area->id}}">{{$area->value}}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('locale'))
                                                                <small class="help-block col-md-12">
                                                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('locale') }}</strong>
                                                                </small>
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="city_id" class="control-label mb-1">المدينه</label>
                                                            <select id="city_id" multiple name="city_id[]" class="city  select2-request  form-control @error('city_id') is-invalid @enderror">

                                                            </select>
                                                            @error('city_id')
                                                            <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="district_id" class="control-label mb-1">الحى </label>
                                                            <select id="district_id" name="district_id[]" class="district  select2-request  form-control @error('district_id') is-invalid @enderror" multiple>
                                                            </select>
                                                            @error('district_id')
                                                            <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="direction" class="control-label mb-1">الإتجاه</label>
                                                            <select id="direction" name="direction" class="city  select2-request  form-control @error('city_id') is-invalid @enderror" style="height: 45px">
                                                                <option disabled selected>أختار الإتجاه ..</option>
                                                                <option value="west" >شمالي</option>
                                                                <option value="south" >جنوبي</option>
                                                                <option value="east">شرقي</option>
                                                                <option value="north" >غربي</option>
                                                            </select>
                                                            @error('city_id')
                                                            <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="checkbox" checked id="is_agent_show" name="is_agent_show">
                                                        <label for="is_agent_show">إظهار إسم الإستشاريين </label>

                                                    </div>
                                                    {{--<div class="col-6">
                                                        <input type="checkbox" id="domain_col" name="domain_col">
                                                        <label for="domain_col">يظهر ضمن التقارير </label>

                                                    </div>--}}


                                                </div>
                                            </div>

                                            <div id="qualityUser"  style="display: none">

                                                <input type="checkbox" checked id="is_follow" name="is_follow">
                                                <label for="is_follow">قابل للمتابعه من قبل مشرف الجودة</label>

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



                                            <br>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" onclick="displayDatatable()">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                                            <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div  id="datatable-table">
        <div class="addUser my-4">
            <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_of_user" id="inlineRadio1" value="">
                    <label class="form-check-label" for="inlineRadio1">الكل</label>
                </div>

                {{-- allow_recived = 1 --}}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" checked type="radio" name="status_of_user" id="inlineRadio2" value="1">
                    <label class="form-check-label" for="inlineRadio2">النشطين</label>
                </div>

                {{-- allow_recived = 0 --}}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_of_user" id="inlineRadio2" value="0">
                    <label class="form-check-label" for="inlineRadio2">الغير نشطين</label>
                </div>


                {{-- active = 0 --}}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_of_user" id="inlineRadio3" value="2">
                    <label class="form-check-label" for="inlineRadio3">المؤرشفين</label>
                </div>

                {{-- active = 1 --}}
                {{-- <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="status_of_user" id="inlineRadio3" value="3">
                  <label class="form-check-label" for="inlineRadio3">الغير مؤرشفين</label>
                </div> --}}

                {{-- role = 6 --}}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_of_user" id="inlineRadio3" value="4">
                    <label class="form-check-label" for="inlineRadio3">المتعاونين</label>
                </div>


                <select id='role-of-user' class="form-control" style="width: 200px">
                    <option value=""> نوع المستخدم</option>
                    @foreach($RolesSelect as $role)
                        <option value="{{$role['id']}}">{{ $role['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="tableBar">
            <div class="topRow">
                <div class="row align-items-center text-center text-md-left">
                    <div class="col-lg-2">
                        <div class="selectAll">
                            <div class="form-check">
                                <input type="checkbox" id="allreq" class="form-check-input" onclick="chbx_toggle1(this);"/>
                                <label class="form-check-label" for="allreq">تحديد الكل </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 ">
                        <div class="tableUserOption  flex-wrap ">
                            <div class="addBtn col-md-5 mt-lg-0 mt-3">
                                <button disabled id="archAll" onclick="getReqests1()">
                                    <i class="fas fa-trash-alt"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Archive User') }}
                                </button>
                            </div>
                            <div class="input-group col-md-7 mt-lg-0 mt-3">
                                <input class="form-control py-2" type="search" placeholder="ابحث هنا" id="example-search-input">
                                <span class="input-group-append">
                            <button class="btn btn-outline-info" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-2 mt-lg-0 mt-3">
                        <div id="dt-btns" class="tableAdminOption">

                        </div>
                    </div>
                </div>
            </div>
            <div class="dashTable">
                <table id="myusers-table" class="table table-bordred table-striped data-table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                        <th>@lang('attributes.name_for_admin')</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'email') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'role') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'user status') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'registered_on') }}</th>
                        <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    {{--@if ($users > 0)--}}
    {{--@else--}}
    {{--    <div class="middle-screen">--}}
    {{--        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Users') }}</h2>--}}
    {{--    </div>--}}
    {{--@endif--}}


@endsection

@section('updateModel')
    @include('Admin.Users.confirmationMsg')
    @include('Admin.Users.updateUser')
    @include('Admin.Users.confirmArchMsg')
    @include('Admin.Users.confirmationSwitchMsg')
    @include('Admin.Users.accessUsersRequests')
@endsection


@section('scripts')
    <script src="{{ asset('js/tokenize2.min.js') }}"></script>

    <script>
        $('.tokenizeable').tokenize2();
        $(".tokenizeable").on("tokenize:select", function() {
            $(this).trigger('tokenize:search', "");
        });
        $('#salesagent').select2();
        $('#salesagents').select2({
            placeholder: " أختار إستشاري المبيعات ",
            allowClear: true
        }).on("select2:select", function (e) {
            var data = e.params.data.text;
            if(data=='الكل'){
                $("#salesagents > option").prop("selected","selected");
                $("#salesagents").trigger("change");
            }
        });

        $('#fundingmanagers').select2({
            placeholder: "أختار مشرف التمويل ",
            allowClear: true
        }).on("select2:select", function (e) {
            var data = e.params.data.text;
            if(data=='الكل'){
                $("#fundingmanagers > option").prop("selected","selected");
                $("#fundingmanagers").trigger("change");
            }
        });

        $('#role').select2();
    </script>

    <script>
        $(document).ready(function (){
            $('#area_id').change(function (){
                var city_id = $('#city_id');
                city_id.html("");
                // console.log(area_id)
                AjaxArea();

            });
            $('#city_id').change(function (){
                var district_id = $('#district_id');
                district_id.html("");
                AjaxCity()
            });
        });
        function AjaxCity(){

            var district_id = $('#district_id');

            // console.log(area_id)
            $.ajax({
                url:'{{route("all.gets.districts")}}',
                data:{
                    id:$("#city_id").val(),
                    profile :"profile"
                },
                success:function (data){
                    district_id.append(data);
                }
            });

        }

        function AjaxArea(){
            var city_id = $('#city_id');
            // console.log(area_id)
            $.ajax({
                url: '{{route("all.gets.cities")}}',
                data:{
                    id:$("#area_id").val()
                },
                success:function (data){
                    city_id.append(data);
                }
            });
        }
        function displayDatatable(){
            $("#add-user").css("display","none")
            $("#add-btn").css("display","block")
            $("#cancel-btn").css("display","none")
            $("#textData").html(" المستخدمين :")
            $("#datatable-table").css("display","block")
            $("#edit-user").css("display","none")
        }
        function addUser(){
            $('#edit-user form')[0].reset();
            $("#add-btn").css("display","none")
            $("#cancel-btn").css("display","block")
            $("#add-user").css("display","block")
            $("#datatable-table").css("display","none")
            $("#edit-user").css("display","block")
            $(".headER h4").html("أضافة مستخدم")
            $("#textData").html("أضافة مستخدم :")
            $(".headER i").removeClass("fa-user fa-edit").addClass("fa-plus")
            $('input[name=_method]').val('POST');
            $('#frm-update').attr('action',"{{route('admin.addUser')}}");
            $("#salesagent").val('').change();
            $("#salesagents").val('').change();
            $("#area_id").val('').change();
            $("#city_id").val('').change();
            $("#district_id").val('').change();
            $("#check").val(0);
            $("#role").val('').change();
            $("#activeError").css("display","none")
            $("#activeError2").css("display","none")
            /* $('#salesagent').html("");*/
            //resetAgents()
        }
        ////////////////////////////////////////
        function getReqests1() {
            var array = []
            var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].value != "on" && checkboxes[i].value != "off") {
                    var val = parseInt(checkboxes[i].value);
                    array.push(val);
                }
            }

            //console.log(array);
            archiveAllReqs(array);
            //  alert(array);
        }

        //


        /////////////////////////////////////////

        function archiveAllReqs(array) {


            var modalConfirm = function (callback) {


                $("#mi-modal3").modal('show');


                $("#modal-btn-si3").on("click", function () {

                    callback(true);
                    $("#mi-modal3").modal('hide');

                });


                $("#modal-btn-no3").on("click", function () {
                    callback(false);
                    $("#mi-modal3").modal('hide');
                });
            };

            modalConfirm(function (confirm) {
                if (confirm) {

                    $.post("{{ route('admin.archUserArray')}}", {
                        array: array,
                        _token: "{{csrf_token()}}",
                    }, function (data) {

                        var url = '{{ route("admin.users") }}';

                        if (data != 0) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly')}}");
                            window.location.href = url; //using a named route

                        } else
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'Try Again')}}");

                    });


                } else {
                    //reject
                }
            });


        };

        function disabledButton() {

            if ($(':checkbox[name="chbx[]"]:checked').length > 0) {
                document.getElementById("archAll").disabled = false;
                document.getElementById("archAll").style = "";
            } else {
                document.getElementById("archAll").disabled = true;
                document.getElementById("archAll").style = "cursor: not-allowed";
            }

        }

        function chbx_toggle1(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }

            disabledButton();
        }
        var role = $('#role').val(0);
        var role = $('.bank-delegate').addClass("d-none");
        $(document).ready(function () {
            /*$('#salesagent , #quality').select2();*/
            var role = $('#role').val();
            var tsaheel = $('#isTsaheel').val();

            // alert(role);

            if ($("#role")[0].selectedIndex <= 0) {

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";
                document.getElementById("othersDiv").style.display = "none";
                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";
            } else {
                document.getElementById("othersDiv").style.display = "none";
                if (role == 'sa') {
                    document.getElementById("salesmanagerDiv").style.display = "block";
                    document.getElementById("tsaheelDiv").style.display = "block";
                }
                if (role == 'sa' && tsaheel == 'yes') {
                    document.getElementById("mortgagemanagerDiv").style.display = "block";
                    document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";


                    document.getElementById("salesmanagerDiv").style.display = "none";
                    document.getElementById("salesmanager").value = "";
                }

                if (role == 6)
                    document.getElementById("salesagentDiv").style.display = "block";
                if (role == 1) {
                    document.getElementById("fundingmanagerDiv").style.display = "block";
                    document.getElementById("mortgagemanagerDiv").style.display = "block";
                }
                // if (role == 5)
                //  document.getElementById("qualtyDiv").style.display = "block";

                if (role == 2 || role == 3) {
                    document.getElementById("generalmanagerDiv").style.display = "block";
                }


                if (role == 8) {
                    document.getElementById("accountantDiv").style.display = "block";
                }
                if (role == 20) {

                    document.getElementById("othersDiv").style.display = "block";
                }

            }


        });

        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function check(that) {

            //console.log(that.value);
            document.getElementById("qualityUser").style.display = "none";
            if (($("#role")[0].selectedIndex <= 0 == false) && (that.value == 'sa')) { // sales agent should has sales maanger
                document.getElementById("salesmanagerDiv").style.display = "block";
                document.getElementById("tsaheelDiv").style.display = "block";


                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";


                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */


            } else if (that.value == "0") { // colloberatot should has sales agents
                document.getElementById("salesmanagerDiv").style.display = "block";
                document.getElementById("salesagentDiv").style.display = "none";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";
            } else if (that.value == 6) { // colloberatot should has sales agents

                document.getElementById("salesagentDiv").style.display = "block";

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";


            } else if (that.value == 1) { // sales should has funding & mortgage managers

                document.getElementById("fundingmanagerDiv").style.display = "block";
                document.getElementById("mortgagemanagerDiv").style.display = "block";
                document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}";


                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            } else if (that.value == 5) { // sales should has funding & mortgage managers

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("qualityUser").style.display = "block";

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";


                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                // document.getElementById("qualtyDiv").style.display = "block";

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            } else if (that.value == 2 || that.value == 3) { // funding & mortgage managers shpuld has general manager


                document.getElementById("generalmanagerDiv").style.display = "block";


                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */


                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";


            } else if (that.value == 8) {

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";


                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";


                document.getElementById("accountantDiv").style.display = "block";

            } else {
                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            }
        }


        $(document).on('change', 'select#role', function () {
            check(this);
            changeRole($(this).val())
        })

        $(document).ready(function () {
            var table = $('#myusers-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        pageLength: "عرض",
                    }
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url:"{{ url('admin/myusers-datatable') }}",
                    data:function(d){
                        d.status=$('input[name="status_of_user"]:checked').val()
                        d.role=$('#role-of-user').val()
                        d.name=$('#example-search-input').val()
                    }
                },
                scrollY: '50vh',
                columns: [
                    {
                        "targets": 0,
                        "data": "id",
                        "render": function (data, type, row, meta) {
                            return '<input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()"  value="' + data + '"/>';
                        }
                    },


                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'name_for_admin',
                        name: 'name_for_admin'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "order": [
                    [6, "desc"]
                ], // Order on init. # is the column, starting at 0
                createdRow: function (row, data, index) {


                    $('td', row).eq(3).addClass('commentStyle');
                    $('td', row).eq(3).attr('title', data.email);


                    $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(2).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(4).addClass('reqNum'); // 6 is index of column
                    $('td', row).eq(5).addClass('reqNum'); // 6 is index of column
                },

                "initComplete": function (settings, json) {
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function () {
                        table.search($(this).val()).draw();
                    })

                    $('#role-of-user').change(function(){
                        table.draw();
                    });

                    $('input:radio').on('click', function(e) {
                        table.draw();
                    });

                    table.buttons().container()
                        .appendTo('#dt-btns');

                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');


                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)

                    /* To Adaptive with New Design */
                }

            });

        });


        $(document).on('click', '#access_requests', function () {
            $('#AccessModal').modal('show');
         });

        $(document).on('click', '#active', function (e) {//لما يضغط السماح او عدم السماح باستلام الطلبات


            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            var name = $(this).attr('data-name');

            if(type == "agent" && typeof name !== 'undefined'){
                swal({
                    title: 'هل انت متأكد',
                    text: "هذا الإستشاري تابع للمتعاون ["+name+ "] هل انت متأكد ؟",
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    buttons: ["إلغاء","نعم , موافق !"],
                }).then(function(inputValue) {
                    if (inputValue != null) {
                        changeStatus(id)
                    }
                });
            }else{
                changeStatus(id)
            }


        });
        function changeStatus(id){
            $.post("{{route('admin.updateUserStatus')}}", {
                id: id,
                _token: "{{csrf_token()}}",
            }, function (data) {
                if (data != 0) {
                    $('#myusers-table').DataTable().ajax.reload();
                }
            })
        }
        $(document).on('click', '#archive', function (e) {
            var id = $(this).attr('data-id');


            var modalConfirm = function (callback) {


                $("#mi-modal").modal('show');


                $("#modal-btn-si").on("click", function () {
                    callback(true);
                    $("#mi-modal").modal('hide');
                });

                $("#modal-btn-no").on("click", function () {
                    callback(false);
                    $("#mi-modal").modal('hide');
                });
            };

            modalConfirm(function (confirm) {
                if (confirm) {


                    $.post("{{ route('admin.deleteUser')}}", {
                        id: id,
                        _token: "{{csrf_token()}}",
                    }, function (data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

                        //console.log(data);
                        if (data.status == 1) {
                            var d = ' # ' + id;
                            var test = d.replace(/\s/g, ''); // to remove all spaces in var d , to find the <tr/> that i deleted and reomve it
                            $(test).remove(); // remove by #id

                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        } else {

                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        }


                    });


                } else {
                    //No delete
                }
            });


        });
        $("#salesagent").change(function () {
            $("#check").val(0);
        })
        $(document).on('click', '#edit', function (e) {
            $("#salesagent").val('').change();
            $("#salesagents").val('').change();
            $("#fundingmanagers").val('').change();
            $("#area_id").val('').change();
            $("#city_id").val('').change();
            $("#district_id").val('').change();
            $("#activeError").css("display","none")
            $("#activeError2").css("display","none")
            $("#check").val(0);
            //  resetAgents()
            $('#nameError').addClass("d-none");
            $('#usernameError').addClass("d-none");
            $('#callCenterNameError').addClass("d-none");
            $('#passwordError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            $('#localeError').addClass("d-none");
            $('#roleError').addClass("d-none");
            $('#salesmanagerError').addClass("d-none");
            $('#fundingmanagerError').addClass("d-none");
            $('#mortgagemanagerError').addClass("d-none");
            $('#salesagentsError').addClass("d-none");
            $('#fundingmanagersError').addClass("d-none");
            $('#generalmanagerError').addClass("d-none");
            $('#qualityError').addClass("d-none");
            $('#accountant_typeError').addClass("d-none");
            $('#bank_idError').addClass("d-none");
            $('#subdomainError').addClass("d-none");
            $('#coedError').addClass("d-none");
            $('#name_for_adminError').addClass("d-none");
            $('#frm-update').attr('action',"{{route('admin.editUser')}}");
            $(".headER h4").html("تعديل مستخدم")
            $("#textData").html("تعديل مستخدم :")
            $(".headER i").removeClass("fa-user fa-plus").addClass("fa-edit")
            var id = $(this).attr('data-id');

            $.get("{{route('admin.getUser')}}", {id}, function (data) {
                // console.log(data);
                if (data.status != 0) {
                    $('#frm-update').find('#domain_col').prop("checked",false)
                    $('#frm-update').find('#is_follow').prop("checked",false)
                    $('#frm-update').find('#is_agent_show').prop("checked",false)
                    $('#frm-update').find('#id').val(data.user.id);
                    $('#frm-update').find('#name').val(data.user.name);
                    $('#frm-update').find('#username').val(data.user.username);
                    $('#frm-update').find('#callCenterName').val(data.user.name_in_callCenter);
                    $('#frm-update').find('#email2').val(data.user.email);
                    $('#frm-update').find('#mobile').val(data.user.mobile);
                    $('#frm-update').find('#locale').val(data.user.locale);
                    $('#frm-update').find('#bank_id').val(data.user.bank_id);
                    $('#frm-update').find('#subdomain').val(data.user.subdomain);
                    $('#frm-update').find('#code').val(data.user.code);
                    $('#frm-update').find('#others').val(data.user.subdomain);
                    $('#frm-update').find('#name_for_admin').val(data.user.name_for_admin);

                    if (data.user.role != 0)
                        $('#frm-update').find('#role').val(data.user.role);
                    else
                        $('#frm-update').find('#role').val(0);
                    // $('#frm-update').find('#role').val('sa');
                    //because sales agent ll not appear if i just pass 0 value


                    if (data.user.role != null)
                        appearDiv(data.user.role); // to know which div has to be appeared

                    $('#frm-update').find('#isTsaheel').val(data.user.isTsaheel);
                    checkTsaheel(data.user.isTsaheel);

                    $('#frm-update').find('#salesmanager').val(data.user.manager_id);
                    $('#frm-update').find('#fundingmanager').val(data.user.funding_mnager_id);
                    $('#frm-update').find('#mortgagemanager').val(data.user.mortgage_mnager_id);
                    $('#frm-update').find('#generalmanager').val(data.user.manager_id);

                    $('#frm-update').find('#area_id').val(data.user.area_id != null ? data.user.area_id.value : '');
                    // var areaid = data.user.area_id.value
                    // var cityid = data.user.city_id.value
                    var areaid = data.user.areas != null ? data.user.areas : ''
                    var cityid = data.user.cities != null ? data.user.cities : ''
                    var userid = data.user.id

                    $.ajax({
                        url: '{{route("all.gets.areas")}}',
                        data:{
                            user_id:userid,
                            profile :"profile"
                        },
                        success:function (data){
                            $('#area_id').append(data);
                        }
                    });


                    $.ajax({
                        url: '{{route("all.gets.cities")}}',
                        data:{
                            user_id:userid,
                            profile :"profile"
                        },
                        success:function (data){
                            $('#city_id').append(data);
                        }
                    });


                    $.ajax({
                        url:'{{route("all.gets.districts")}}',
                        data:{
                            user_id:userid,
                            profile :"profile"
                        },
                        success:function (data){
                            $('#district_id').append(data);
                        }
                    });
                    $('#frm-update').find('#direction').val(data.user.direction? data.user.direction.value : '');
                    $('#frm-update').find('#accountant_type').val(data.user.accountant_type);

                    if(data.user.role == 6 && data.user.subdomain != null){

                        $('#frm-update').find('#domain_col').prop("checked",true);
                    }

                    if(data.user.role == 5 && data.user.subdomain != null){
                        $('#frm-update').find('#is_follow').prop("checked",true);
                    }else{
                        $('#frm-update').find('#is_follow').prop("checked",false);
                    }

                    if(data.user.role == 6 && data.user.subdomain != null){
                        $('#frm-update').find('#is_agent_show').prop("checked",true);
                    }else{
                        $('#frm-update').find('#is_agent_show').prop("checked",false);
                    }

                    //-------------------TO RETRIVE SALES AGENTS IN SELECT2 ---------------------------

                    var lengthArr = data.quality.length;

                    if (lengthArr > 0) { // for collobreator user and thier salesagents

                        var arrData = data.quality;

                        for (i = 0; i < lengthArr; ++i) {
                            //console.log(arrData[i]);

                            var selectobject = document.getElementById("quality");

                            for (var j = 0; j < selectobject.length; j++) {

                                // console.log(arrData[i].user_id);

                                if (selectobject.options[j].value == arrData[i].Agent_id) {

                                    var name = selectobject.options[j].text;
                                    // console.log(name);
                                    selectobject.remove(j);
                                    $('#quality').append($("<option selected></option>").attr("value", arrData[i].Agent_id).text(name));
                                    break;

                                }


                            }

                        }

                    }else{
                        //    resetAgents()
                    }


                    //-------------------TO RETRIVE SALES AGENTS IN SELECT2 ---------------------------

                    var lengthArr = data.salesagents.length;

                    if (lengthArr > 0) { // for collobreator user and thier salesagents

                        var arrData = data.salesagents;

                        for (i = 0; i < lengthArr; ++i) {
                            //console.log(arrData[i]);

                            var selectobject = document.getElementById("salesagent");
                            var selectobjecst = document.getElementById("salesagents");
                            var selectobjecsts = document.getElementById("fundingmanagers");

                            for (var j = 0; j < selectobject.length; j++) {

                                // console.log(arrData[i].user_id);

                                if (selectobject.options[j].value == arrData[i].user_id) {

                                    var name = selectobject.options[j].text;
                                    // console.log(name);
                                    selectobject.remove(j);
                                    $('#salesagent').append($("<option selected></option>").attr("value", arrData[i].user_id).text(name));
                                    break;

                                }
                            }

                            for (var j = 0; j < selectobjecst.length; j++) {
                                if (selectobjecst.options[j].value == arrData[i].user_id) {

                                    var name = selectobjecst.options[j].text;
                                    // console.log(name);
                                    selectobjecst.remove(j);
                                    $('#salesagents').append($("<option selected></option>").attr("value", arrData[i].user_id).text(name));
                                    break;
                                }
                            }

                            for (var j = 0; j < selectobjecsts.length; j++) {
                                if (selectobjecsts.options[j].value == arrData[i].user_id) {

                                    var name = selectobjecsts.options[j].text;
                                    // console.log(name);
                                    selectobjecsts.remove(j);
                                    $('#fundingmanagers').append($("<option selected></option>").attr("value", arrData[i].user_id).text(name));
                                    break;
                                }
                            }

                        }

                    }

                    //-------------------END RETRIVE SALES AGENTS IN SELECT2 ---------------------------
                    $("#datatable-table").css("display","none")
                    $("#edit-user").css("display","block")
                    $("#add-user").css("display","none")
                    $("#add-btn").css("display","none")
                    $("#cancel-btn").css("display","block")
                    $('#role').change()

                } else
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            });
        });
        function resetAgents (){
            var selectobject = document.getElementById("salesagent");
            for (var j = 0; j < selectobject.length; j++) {
                selectobject.remove(j);
            }
        }
        $('#frm-update').on('submit', function (e) {
            $('#nameError').addClass("d-none");
            $('#usernameError').addClass("d-none");
            $('#callCenterNameError').addClass("d-none");
            $('#passwordError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            $('#localeError').addClass("d-none");
            $('#roleError').addClass("d-none");
            $('#activeError').addClass("d-none");
            $('#activeError2').addClass("d-none");
            $('#salesmanagerError').addClass("d-none");
            $('#fundingmanagerError').addClass("d-none");
            $('#mortgagemanagerError').addClass("d-none");
            $('#salesagentsError').addClass("d-none");
            $('#generalmanagerError').addClass("d-none");
            $('#accountant_typeError').addClass("d-none");

            $('#bank_idError').addClass("d-none");
            $('#subdomainError').addClass("d-none");
            $('#codeError').addClass("d-none");
            $('#name_for_adminError').addClass("d-none");

            e.preventDefault();
            var data = $(this).serialize();
            var url = $(this).attr('action');


            $.post(url, data, function (data) {
                $('#myusers-table').DataTable().ajax.reload();
                $("#datatable-table").css("display","block")
                $("#edit-user").css("display","none")
                $("#add-user").css("display","none")
            }).fail(function (data) {
                var errors = data.responseJSON;
                $("#add-btn").css("display","none")
                $("#cancel-btn").css("display","block")
                var isActive=true;
                if ($.isEmptyObject(errors) == false) {
                    $.each(errors.errors, function (key, value) {

                        var ErrorID = '#' + key + 'Error';
                        if(ErrorID == "#salesagentsError"){
                            isActive=false;
                        }
                        if(ErrorID == "#activeError" && isActive){
                            if($('#check').val() == 0 && value != ""){
                                console.log(value != "")
                                $("#activeError").css("display","block").removeClass("d-none")
                                    .html('<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">'+value+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                        '<span aria-hidden="true">&times;</span>' +
                                        '</button><br><a onclick="editCheck()" class="btn btn-link p-0" style="text-decoration: underline;color: #002c9a">نعم متأكد </a>'+'</div>');

                                $("#activeError2").css("display","block").removeClass("d-none")
                                    .html('<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">'+value+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                        '<span aria-hidden="true">&times;</span>' +
                                        '</button><br><a onclick="editCheck()" class="btn btn-link p-0" style="text-decoration: underline;color: #002c9a">نعم متأكد </a>'+'</div>');
                            }

                        }else{
                            $(ErrorID).removeClass("d-none");
                            $(ErrorID).text(value);
                        }

                    })
                }
            });

        });


        function editCheck() {
            $("#check").val(1);
            $("#activeError").fadeOut(1200)
        }
        function getRole(r) {

            if (r == 0)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Sales Agent') }}";
            else if (r == 1)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Sales Manager') }}";
            else if (r == 2)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Funding Manager') }}";
            else if (r == 3)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Mortgage Manager') }}";
            else if (r == 4)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'General Manager') }}";
            else if (r == 5)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Quality Manager') }}";
            else if (r == 6)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Collaborator') }}";
            else if (r == 7)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Admin') }}";
            else if (r == 8)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Accountant') }}";
            else if (r == 11)
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Training') }}";
            else
                role = "{{MyHelpers::admin_trans(auth()->user()->id, 'Undefined') }}";


            return role;
        }

        function appearDiv(that) {

            document.getElementById("othersDiv").style.display = "none";
            document.getElementById("langDiv").style.display = "block";
            document.getElementById("passwordDiv").style.display = "block";
            document.getElementById("usernameDiv").style.display = "block";
            document.getElementById("collabarator").style.display = "none";
            document.getElementById("qualityUser").style.display = "none";
            if (that == 20) { // sales agent should has sales maanger
                document.getElementById("othersDiv").style.display = "block";

                document.getElementById("langDiv").style.display = "none";
                document.getElementById("passwordDiv").style.display = "none";
                document.getElementById("usernameDiv").style.display = "none";
                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";
            }
            if (that == 0) { // sales agent should has sales maanger

                document.getElementById("salesmanager").value = "";
                document.getElementById("salesmanagerDiv").style.display = "block";
                document.getElementById("tsaheelDiv").style.display = "block";
                document.getElementById("isTsaheel").value = "";
                // document.getElementById("mortgage_label").innerHTML  = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";


                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";


            } else if (that == 6) { // colloberatot should has sales agents

                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";
                document.getElementById("salesagentDiv").style.display = "block";
                document.getElementById("collabarator").style.display = "block";


                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";


            } else if (that == 1) { // sales should has funding & mortgage managers


                document.getElementById("fundingmanager").value = "";
                document.getElementById("mortgagemanager").value = "";
                document.getElementById("fundingmanagerDiv").style.display = "block";
                document.getElementById("mortgagemanagerDiv").style.display = "block";
                document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}";


                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            } else if (that == 2 || that == 3) { //  funding & mortgage manager shold has general maanger

                document.getElementById("generalmanager").value = "";
                document.getElementById("generalmanagerDiv").style.display = "block";

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            } else if (that == 5) { // sales should has funding & mortgage managers
                document.getElementById("qualityUser").style.display = "block";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("mortgagemanagerDiv").style.display = "none";

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";


                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";
                // document.getElementById("qualtyDiv").style.display = "block";
                // document.getElementById("quality").value = "";

            } else if (that == 8) {

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "";

                document.getElementById("accountantDiv").style.display = "block";

            } else {

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            }
        }

        $(document).on('click', '#switch', function (e) {
            var id = $(this).attr('data-id');


            // alert(id);

            var modalConfirm = function (callback) {


                $("#mi-modal4").modal('show');


                $("#modal-btn-si4").on("click", function () {
                    callback(true);
                    $("#mi-modal4").modal('hide');
                });

                $("#modal-btn-no4").on("click", function () {
                    callback(false);
                    $("#mi-modal4").modal('hide');
                });
            };

            modalConfirm(function (confirm) {
                if (confirm) {


                    $.post("{{ route('switch.userSwitch')}}", {
                        id: id,
                        _token: "{{csrf_token()}}",
                    }, function (data) {

                        // console.log(data);

                        var url = data;
                        window.location.href = url; //using a named route


                    });


                } else {
                    //No
                }
            });


        });


        function checkTsaheel(that) {

            var role = $('#role').val();

            if (that == 'yes' && role == 'sa') {

                document.getElementById("mortgagemanagerDiv").style.display = "block";
                document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";


                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

            } else if (that == 'no' && role == 'sa') {

                document.getElementById("salesmanagerDiv").style.display = "block";


                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */
            }
        }


        function checkTsaheel2(that) {

            var role = $('#role').val();

            if (that.value == 'yes' && role == 'sa') {

                document.getElementById("mortgagemanagerDiv").style.display = "block";
                document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";


                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

            } else if (that.value == 'no' && role == 'sa') {

                document.getElementById("salesmanagerDiv").style.display = "block";


                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */
            } else if (that.value == 'no' && role != 'sa') {

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";
                document.getElementById("salesagents").value = "";


                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("fundingmanager").value = "";

                document.getElementById("mortgagemanagerDiv").style.display = "none";
                document.getElementById("mortgagemanager").value = "";

                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            }
        }


        @if(isset($errorSms) && $errorSms)
        window.alertError('{!! $errorSms !!}')
        @endif
    </script>
@endsection

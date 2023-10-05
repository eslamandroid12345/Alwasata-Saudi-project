@extends('layouts.content')

@section('title', __("language.Add New user"))

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

    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! session('success') !!}</li>
                    </ul>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{!! session('error') !!}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>


    <div class="addUser mt-4">
        <div class="userBlock  text-center">
            <div class="addBtn">
                <h3>
                    <i class="fas fa-plus-circle"></i>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Add New user') }}
                </h3>
            </div>
        </div>
    </div>

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
                                <form action="{{route('admin.addUser')}}" method="post" class="">
                                    @csrf
                                    {{--                                {!! Form::formGroup(request()->all(),[--}}
                                    {{--                                    "route"     => 'admin.addUser',--}}
                                    {{--                                    "method"    => "post",--}}
                                    {{--                                ])!!}--}}
                                    <div class="userFormsContainer mb-3">
                                        <div class="userFormsDetails topRow">
                                            <div class="row">
                                                {{--
                                                {!! Form::textGroup('name', __("attributes.name"), 'fas fa-file-signature') !!}
                                                {!! Form::textGroup('username', __("attributes.username"), 'fas fa-file-signature') !!}
                                                {!! Form::textGroup('callCenterName', __("attributes.callCenterName"), 'fas fa-file-signature',[]) !!}
                                                {!! Form::emailGroup('email', __("attributes.email"), 'fas fa-file-signature',[]) !!}
                                                {!! Form::numberGroup('mobile', __("attributes.mobile"), 'fas fa-file-signature',[]) !!}
                                                {!! Form::passwordGroup('password', __("attributes.password"), 'fas fa-file-signature') !!}

                                                <div class="col-12 mb-3">
                                                       <label>
                                                           <input type="checkbox" onclick="myFunction()"> @lang('language.Show') @lang('language.Password')
                                                       </label>
                                                </div>
                                                --}}

                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="name">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                                                        <input type="text" id="name" name="name" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}" class="form-control" value="{{ old('name') }}">
                                                    </div>
                                                    @if ($errors->has('name'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="name_for_admin">@lang('attributes.name_for_admin')</label>
                                                        <input type="text" id="name_for_admin" name="name_for_admin" placeholder="@lang('attributes.name_for_admin')" class="form-control" value="{{ old('name_for_admin') }}">
                                                    </div>
                                                    @if ($errors->has('name_for_admin'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('name_for_admin') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3" id="usernameDiv">
                                                    <div class="form-group">
                                                        <label for="Username">{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</label>
                                                        <input type="text" id="username" name="username" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}" class="form-control" value="{{ old('username') }}">
                                                    </div>
                                                    @if ($errors->has('username'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('username') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="Username">الاسم في الكول سينتر</label>
                                                        <input type="text" id="callCenterName" name="callCenterName" class="form-control" value="{{ old('callCenterName') }}">
                                                    </div>
                                                    @if ($errors->has('callCenterName'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('callCenterName') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="Email">{{ MyHelpers::admin_trans(auth()->user()->id,'Email') }}</label>
                                                        <input type="email" id="email2" name="email" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Email') }}" class="form-control" value="{{ old('email') }}">
                                                    </div>
                                                    <span class="text-danger" id="mobileError" role="alert"> </span>
                                                    @if ($errors->has('email'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('email') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="mobile">رقم الجوال</label>
                                                        <input type="number" id="mobile" name="mobile" placeholder="5xxxxxxxx" class="form-control" value="{{ old('mobile') }}">
                                                    </div>
                                                    <span class="text-danger" id="mobileError" role="alert"> </span>
                                                    @if ($errors->has('mobile'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('mobile') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3" id="passwordDiv">
                                                    <div class="form-group">
                                                        <label for="password">{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}</label>
                                                        <input type="password" id="password" name="password" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}" class="form-control" value="">
                                                    </div>
                                                    <input type="checkbox" onclick="myFunction()"> {{ MyHelpers::admin_trans(auth()->user()->id,'Show') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}
                                                    @if ($errors->has('password'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('password') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3" id="langDiv">
                                                    <div class="form-group">
                                                        <label for="local">@lang('language.Language')</label>
                                                        <select required id="locale" name="locale" placeholder="@lang('language.Language')" class="form-control">
                                                            <option selected disabled>---</option>
                                                            <option value="ar" {{Input::old('locale') == 'ar' ? 'selected' : ''}}>@lang('language.Arabic')</option>
                                                            <option value="en" {{Input::old('locale') == 'en' ? 'selected' : ''}}>@lang('language.English')</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('locale'))
                                                        <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('locale') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="role">@lang('language.role')</label>
                                                        <select id="role" name="role" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check(this);' placeholder="@lang('language.role')">
                                                            <option value="0" selected disabled>---</option>
                                                            @foreach($RolesSelect as $role)
                                                                <option {{$role['id'] == Input::old('role') ? 'selected' : '' }} value="{{$role['id']}}">{!! $role['name'] !!}</option>
                                                            @endforeach
                                                            <option value="20">أخري</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('role'))
                                                        <span class="help-block col-md-12">
                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-12 mb-3 bank-delegate">
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <div class="form-group">
                                                                <label for="bank_id">@lang('attributes.bank_id')</label>
                                                                <select id="bank_id" name="bank_id" placeholder="@lang('attributes.bank_id')" class="form-control">
                                                                    <option value="0" selected disabled>---</option>
                                                                    @foreach($BanksSelect as $bank)
                                                                        <option value="{{$bank['id']}}" {{Input::old('bank_id') == $bank['id'] ? 'selected' : ''}}>{{$bank['name']}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @if ($errors->has('bank_id'))
                                                                <span class="help-block col-md-12">
                                                                <strong style="color:red ;font-size:10pt">{{ $errors->first('bank_id') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <div class="form-group">
                                                                <label for="subdomain">@lang('attributes.subdomain')</label>
                                                                <input id="subdomain" name="subdomain" placeholder="@lang('attributes.subdomain')" class="form-control" value="{{Input::old('subdomain')}}">
                                                            </div>
                                                            @if ($errors->has('subdomain'))
                                                                <span class="help-block col-md-12">
                                                                <strong style="color:red ;font-size:10pt">{{ $errors->first('subdomain') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--{!! Form::selectGroup("locale",  __("attributes.locale"), null,$LanguagesSelect ) !!}
                                                {!! Form::selectGroup("role",  __("attributes.role"), null,$RolesSelect ) !!}
                                                {!! Form::selectGroup("bank_id",  __("attributes.bank_id"), null,$BanksSelect,null, ['class' => 'bank-delegate','required' => !0] ) !!}--}}

                                                <div class="col-12 mb-3">
                                                    <div id="othersDiv"  class="form-group" style="display:none;">
                                                        <div class="form-group">
                                                            <label for="others"> الوظيفة</label>
                                                            <input id="others" name="others" placeholder="المسمى الوظيفي" class="form-control" value="{{Input::old('others')}}">
                                                        </div>
                                                        @if ($errors->has('others'))
                                                            <span class="help-block col-md-12">
                                                                <strong style="color:red ;font-size:10pt">{{ $errors->first('others') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div id="tsaheelDiv" class="form-group" style="display:none;">
                                                        <label for="isTsaheel">{{ MyHelpers::admin_trans(auth()->user()->id,'Tsaheel Agent?') }}</label>
                                                        <select id="isTsaheel" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); checkTsaheel(this);' class="form-control @error('isTsaheel') is-invalid @enderror" name="isTsaheel">
                                                            <option value="0" @if (old('isTsaheel')=='0' ) selected="selected" @endif>لا</option>
                                                            <option value="1" @if (old('isTsaheel')=='1' ) selected="selected" @endif>نعم</option>
                                                        </select>
                                                        @error('isTsaheel')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div id="accountantDiv" class="form-group" style="display:none;">
                                                        <label for="accountant_type">{{ MyHelpers::admin_trans(auth()->user()->id,'Accountant type') }}</label>
                                                        <select id="accountant_type" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('accountant_type') is-invalid @enderror" name="accountant_type">
                                                            <option disabled selected="selected">---</option>
                                                            <option value="0" @if (old('accountant_type')=='0' ) selected="selected" @endif>محاسب تساهيل</option>
                                                            <option value="1" @if (old('accountant_type')=='1' ) selected="selected" @endif>محاسب الوساطة</option>
                                                        </select>
                                                        @if ($errors->has('accountant_type'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('accountant_type') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div id="salesmanagerDiv" class="form-group" style="display:none;">
                                                        <label for="salesmanager">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</label>
                                                        <select id="salesmanager" name="salesmanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('salesmanager') }}">
                                                            <option disabled selected="selected">---</option>
                                                            @foreach ($salesManagers as $salesManager)

                                                                @if (Input::old('salesmanager') == $salesManager->id)
                                                                    <option value="{{$salesManager->id}}" selected>{{$salesManager->name}}</option>
                                                                @else
                                                                    <option value="{{$salesManager->id}}">{{$salesManager->name}}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('salesmanager'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesmanager') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div id="fundingmanagerDiv" class="form-group" style="display:none;">
                                                        <label for="fundingmanager">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</label>
                                                        <select id="fundingmanager" name="fundingmanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('fundingmanager') }}">
                                                            <option disabled selected="selected">---</option>
                                                            @foreach ($fundingManagers as $fundingManager)

                                                                @if (Input::old('fundingmanager') == $fundingManager->id)
                                                                    <option value="{{$fundingManager->id}}" selected>{{$fundingManager->name}}</option>
                                                                @else
                                                                    <option value="{{$fundingManager->id}}">{{$fundingManager->name}}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('fundingmanager'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('fundingmanager') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div id="mortgagemanagerDiv" class="form-group" style="display:none;">
                                                        <label for="mortgagemanager" id="mortgage_label"></label>
                                                        <select id="mortgagemanager" name="mortgagemanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('mortgagemanager') }}">
                                                            <option disabled selected="selected">---</option>
                                                            @foreach ($mortgageManagers as $mortgageManager)

                                                                @if (Input::old('mortgagemanager') == $mortgageManager->id)
                                                                    <option value="{{$mortgageManager->id}}" selected>{{$mortgageManager->name}}</option>
                                                                @else
                                                                    <option value="{{$mortgageManager->id}}">{{$mortgageManager->name}}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('mortgagemanager'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('mortgagemanager') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <div id="generalmanagerDiv" class="form-group" style="display:none;">
                                                        <label for="generalmanager">{{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }}</label>
                                                        <select id="generalmanager" name="generalmanager" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' value="{{ old('generalmanager') }}">
                                                            <option disabled selected="selected">---</option>
                                                            @foreach ($generalManagers as $generalManager)

                                                                @if (Input::old('generalmanager') == $generalManager->id)
                                                                    <option value="{{$generalManager->id}}" selected>{{$generalManager->name}}</option>
                                                                @else
                                                                    <option value="{{$generalManager->id}}">{{$generalManager->name}}</option>
                                                                @endif

                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('generalmanager'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('generalmanager') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <div id="salesagentDiv" class="form-group" style="display:none;">
                                                        <label for="salesagent">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                                                        <br>
                                                        <select id="salesagent" name="salesagents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple" style="width: 100%;">

                                                            @foreach ($salesAgents as $salesAgent)
                                                                <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('salesagents'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <div id="qualtyDiv" class="form-group" style="display:none;">
                                                        <label for="quality">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                                                        <br>
                                                        <select id="quality" name="quality[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control  " multiple="multiple">
                                                            @foreach ($salesAgents as $salesAgent)
                                                                @if($agent_quality->where('Agent_id',$salesAgent->id)->first() == null)
                                                                    <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('quality'))
                                                            <span class="help-block col-md-12">
                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('quality') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <button class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                                {{--                                </form>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>

        $(document).on('change', 'select#role', function () {
            check(this);
            changeRole($(this).val())
        })

        $(document).ready(function () {
            $('#salesagent ,#quality,#role,#bank_id').select2();
            // $('form select').select2();
            // changeRole($("#bank_id").val());
            $("#role").change();


            var role = $('#role').val();
            var tsaheel = $('#isTsaheel').val();

            // alert(role);

            if ($("#role")[0].selectedIndex <= 0) {

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

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            } else {
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
                // document.getElementById("qualtyDiv").style.display = "block";

                if (role == 2 || role == 3) {
                    document.getElementById("generalmanagerDiv").style.display = "block";
                }

                if (role == 8)
                    document.getElementById("accountantDiv").style.display = "block";


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
            document.getElementById("langDiv").style.display = "block";
            document.getElementById("passwordDiv").style.display = "block";
            document.getElementById("usernameDiv").style.display = "block";
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
                document.getElementById("othersDiv").style.display = "none";

            } else if (that.value == 20) { // colloberatot should has sales agents
                document.getElementById("othersDiv").style.display = "block";
                document.getElementById("passwordDiv").style.display = "none";
                document.getElementById("usernameDiv").style.display = "none";
                document.getElementById("langDiv").style.display = "none";
                document.getElementById("salesagentDiv").style.display = "none";

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
                document.getElementById("othersDiv").style.display = "none";
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
                document.getElementById("othersDiv").style.display = "none";
                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";

                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";


            } else if (that.value == 5) { // sales should has funding & mortgage managers

                document.getElementById("fundingmanagerDiv").style.display = "none";
                document.getElementById("mortgagemanagerDiv").style.display = "none";

                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";


                document.getElementById("generalmanagerDiv").style.display = "none";
                document.getElementById("generalmanager").value = "";

                // document.getElementById("qualtyDiv").style.display = "block";
                document.getElementById("othersDiv").style.display = "none";
                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";


            } else if (that.value == 2 || that.value == 3) { // funding & mortgage managers shpuld has general manager


                document.getElementById("generalmanagerDiv").style.display = "block";

                document.getElementById("othersDiv").style.display = "none";
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

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */
                document.getElementById("othersDiv").style.display = "none";
                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";


                document.getElementById("accountantDiv").style.display = "block";

            } else {

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

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

                document.getElementById("tsaheelDiv").style.display = "none";
                document.getElementById("isTsaheel").value = "0";


                document.getElementById("accountantDiv").style.display = "none";
                document.getElementById("accountant_type").value = "";

            }
        }

        function checkTsaheel(that) {

            var role = $('#role').val();

            if (that.value == 'yes' && role == 'sa') {

                document.getElementById("mortgagemanagerDiv").style.display = "block";
                document.getElementById("mortgage_label").innerHTML = " {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} (طلب تساهيل)";


                document.getElementById("salesmanagerDiv").style.display = "none";
                document.getElementById("salesmanager").value = "";

                document.getElementById("salesagentDiv").style.display = "none";
                document.getElementById("salesagent").value = "";

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

    </script>
@endpush

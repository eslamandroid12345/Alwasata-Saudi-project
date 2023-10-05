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
                                <form action="{{route('HumanResource.addUser')}}" method="post" class="">
                                    @csrf
                                    <div class="userFormsContainer mb-3">
                                        <div class="userFormsDetails topRow">
                                            <div class="row">
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
                                                <input type="hidden" name="role" value="20">

                                                <div class="col-12 mb-3">
                                                    <div id="othersDiv"  class="form-group">
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

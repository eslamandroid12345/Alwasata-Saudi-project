@extends('layouts.content')

@section('title')
    تعديل تعميمات
@endsection


@section('css_style')

    <style>
        .select2-results {
            max-height: 350px;
        }

        .bigdrop {
            width: 600px !important;
        }
    </style>
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

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
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

                <div class="row">

                    <div class="col-lg-7 offset-md-3">
                        <div class="card">
                            <div class="card-header">التعديل على التعميمات</div>
                            <div class="card-body card-block">

                                <form action="{{route('admin.editAnnounce')}}" method="post"  id="form-data" enctype="multipart/form-data" class="">
                                    @csrf


                                    <input type="hidden" name="id" id="announceID" class="form-control" value="{{$announce->id}}">


                                    <div class="form-group">
                                        <label for="content" class="control-label mb-1">نص التعميمات </label>
                                        <div class="input-group">
                                            <textarea name="content" id="content" cols="5" rows="3" placeholder="محتوى التعميمات" class="form-control" value="{{  old('content', $announce->content) }}">{{$announce->content}} </textarea>
                                            <div class="input-group-addon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('content'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('content') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="status" class="control-label mb-1">حالة التعميمات</label>
                                        <div class="input-group">
                                            <select id="status" name="status" placeholder="حالة التنبيه" class="form-control">
                                                <option value="1" @if (old('status')=='1' ) selected="selected" @elseif ($announce->status == '1') selected="selected" @endif >مفعل</option>
                                                <option value="0" @if (old('status')=='0' ) selected="selected" @elseif ($announce->status == '0') selected="selected" @endif > غير مفعل </option>
                                            </select>
                                            <div class="input-group-addon">
                                                <i class="fa fa-stop-circle-o"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('status'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('status') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="attachment" class="control-label mb-1">مرفق التعميمات </label>
                                        <br>
                                        @if ($announce->attachment != null)
                                            <div class="tableAdminOption" style="float:right;padding:5px;">
                                        <span class="item pointer" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                            <a href="{{ route('admin.openAnnounceFile',$announce->id)}}" target="_blank"> <i class="fa fa-eye"></i></a>
                                        </span>

                                                <span class="item pointer" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}">
                                            <a href="{{ route('admin.deleteAnnounceFile',$announce->id)}}"> <i class="fa fa-trash"></i></a>
                                        </span>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="end_at" class="control-label mb-1">تاريخ النهاية </label>
                                            <div class="input-group">
                                                <input type = "date" name="end_at" min="{{date("Y-m-d",strtotime(now('Asia/Riyadh')->subDay()))}}" id="end_at" class="form-control" value="{{ $announce->end_at }}">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-paperclip"></i>
                                                </div>
                                            </div>
                                            @if ($errors->has('end_at'))
                                                <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('end_at') }}</strong>
                                    </span>
                                            @endif
                                        </div>


                                        <div class="input-group">
                                            <input type="file" name="attachment" id="attachment" class="form-control" value="{{  old('attachment', $announce->attachment) }}">
                                            <div class="input-group-addon">
                                                <i class="fa fa-paperclip"></i>
                                            </div>
                                        </div>


                                        @if ($errors->has('attachment'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('attachment') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="color" class="control-label mb-1">لون التعميمات </label>

                                        <input type="color" name="color" id="color" value="{{  old('color', $announce->color) }}" style=" width: 100%;">

                                        @if ($errors->has('color'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('color') }}</strong>
                                    </span>
                                        @endif
                                    </div>



                                    <div class="form-group">
                                        <label for="roles" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'role') }}</label>

                                        <select id="roles" name="roles[]" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' multiple="multiple">


                                            @if (in_array(8, $roles))
                                                <option value=8 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Accountant') }} </option>
                                            @else
                                                <option value=8> {{ MyHelpers::admin_trans(auth()->user()->id,'Accountant') }} </option>
                                            @endif

                                            @if (in_array(7, $roles))
                                                <option value=7 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Admin') }} </option>
                                            @else
                                                <option value=7> {{ MyHelpers::admin_trans(auth()->user()->id,'Admin') }} </option>
                                            @endif

                                            @if (in_array(6, $roles))
                                                <option value=6 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Collaborator') }} </option>
                                            @else
                                                <option value=6> {{ MyHelpers::admin_trans(auth()->user()->id,'Collaborator') }} </option>
                                            @endif

                                            @if (in_array(5, $roles))
                                                <option value=5 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Manager') }} </option>
                                            @else
                                                <option value=5> {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Manager') }} </option>
                                            @endif

                                            @if (in_array(4, $roles))
                                                <option value=4 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }} </option>
                                            @else
                                                <option value=4> {{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }} </option>
                                            @endif

                                            @if (in_array(3, $roles))
                                                <option value=3 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} </option>
                                            @else
                                                <option value=3> {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }} </option>
                                            @endif

                                            @if (in_array(2, $roles))
                                                <option value=2 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }} </option>
                                            @else
                                                <option value=2> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }} </option>
                                            @endif

                                            @if (in_array(1, $roles))
                                                <option value=1 selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }} </option>
                                            @else
                                                <option value=1> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }} </option>
                                            @endif

                                            @if (in_array(0, $roles))
                                            <!-- sa = 0 but because html consder 0 as empty value-->
                                                <option value='sa' selected> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }} </option>
                                            @else
                                                <option value='sa'> {{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }} </option>
                                            @endif


                                        </select>

                                        @if ($errors->has('roles'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('roles') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div id="managersDiv" class="form-group">
                                        <label for="managers" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</label>

                                        <select id="managers" name="managers[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                            @foreach ($allManagers as $allManager)

                                                <option value="{{$allManager->id}}" @if (in_array($allManager->id, $managers)) selected="selected" @endif>{{$allManager->name}}</option>

                                            @endforeach

                                        </select>


                                        @if ($errors->has('managers'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('managers') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div id="usersDiv" class="form-group">
                                        <label for="users" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'users') }}</label>

                                        <select id="users" name="users[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                            @foreach ($allUsers as $allUser)

                                                <option value="{{$allUser->id}}" @if (in_array($allUser->id, $users)) selected="selected" @endif>{{$allUser->name}}</option>

                                            @endforeach

                                        </select>


                                        @if ($errors->has('users'))
                                            <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('users') }}</strong>
                                    </span>
                                        @endif
                                    </div>


                                    <br>
                                    <div class="form-actions form-group">
                                        <button role="button" type="button" onclick="checkType()" class="btn btn-info btn-block">تعديل</button>
                                        @include("Admin.Announcements.confirmMsg")
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script>
        function checkType(){
            if($("#roles").val() == "" && $("#users").val() == "" && $("#managers").val() == ""  && $("#managers").val() == "" ){
                $("#mi-modal3").modal('show');
            }else{
                $("#form-data").submit()
            }
        }
        $(document).ready(function() {
            $('#users,#roles,#managers').select2();


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



        /////////////////////////////////////////////

        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }


        //////////////////////////////////////////

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

                /*  document.getElementById("qualtyDiv").style.display = "none";
                document.getElementById("quality").value = "";
                */

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
@endsection

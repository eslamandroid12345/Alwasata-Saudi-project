@extends('layouts.content')

@section('title')
إضافة تنبيه
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
                        <div class="card-header">إضافه تحكم جديد بتنبيه إيميل جديد</div>
                        <div class="card-body card-block">

                            <form action="{{route('admin.addEmail')}}" method="post" class="">
                                @csrf
                                <div class="form-group">
                                    <label for="display_name" class="control-label mb-1">الأسم الظاهر</label>
                                    <div class="input-group">
                                        <input type="text" id="display_name" name="display_name" placeholder="الأسم الظاهر" class="form-control" value="{{ old('display_name') }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-diamond"></i>
                                        </div>
                                    </div>
                                    @if ($errors->has('display_name'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('display_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="email_name" class="control-label mb-1">إسم التنبيه بدون مسافات </label>
                                    <div class="input-group">
                                        <input type="text" id="email_name" name="email_name" placeholder="إسم التنبيه بدون مسافات" class="form-control" value="{{ old('email_name') }}">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    @if ($errors->has('email_name'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('email_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="status" class="control-label mb-1">الحالة</label>
                                    <div class="input-group">
                                        <select id="status" name="status" placeholder="حالة التنبيه" class="form-control">
                                            <option value="active" >مفعل</option>
                                            <option value="inactive" > غير مفعل </option>
                                        </select>
                                        <div class="input-group-addon">
                                            <i class="fa fa-language"></i>
                                        </div>
                                    </div>
                                    @if ($errors->has('status'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('status') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <br>
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">إضافة</button>
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
    $(document).ready(function() {
        $('#salesagent ,#quality').select2();



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

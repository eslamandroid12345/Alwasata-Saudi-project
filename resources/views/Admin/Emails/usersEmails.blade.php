@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add New user') }}
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

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">تعديل إرسال الإشعارت للمستخدم </div>
                        <div class="card-body card-block">
                            @if(auth()->user()->role != 1)
                            <form action="{{route('admin.saveAllMailsNotifications')}}" method="post" class="">
                                @csrf
                                <div class="row">
                                    @foreach($emails as $email)
                                        <div class="form-group col-lg-4">
                                            <input type="checkbox" name="emails[]"
                                                   value="{{$email->id}}" {{\App\EmailUser::where(['user_id'   => $userId,'email_id' =>$email->id])->count() > 0 ? 'checked' :''}}>
                                            {{$email->display_name}}
                                        </div>
                                    @endforeach
                                </div>
                                <br>
                                <input type="hidden" name="user_id" value="{{$userId}}">
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">إضافة</button>
                                </div>
                            </form>
                            @else
                                <form action="{{route('sales.manager.saveAllMailsNotifications')}}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        @foreach($emails as $email)
                                            <div class="form-group col-lg-4">
                                                <input type="checkbox" name="emails[]"
                                                       value="{{$email->id}}" {{\App\EmailUser::where(['user_id'   => $userId,'email_id' =>$email->id])->count() > 0 ? 'checked' :''}}>
                                                {{$email->display_name}}
                                            </div>
                                        @endforeach
                                    </div>
                                    <br>
                                    <input type="hidden" name="user_id" value="{{$userId}}">
                                    <div class="form-actions form-group">
                                        <button type="submit" class="btn btn-info btn-block">إضافة</button>
                                    </div>
                                </form>
                                @endif
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

@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Filter Engine') }}
@endsection

@section('css_style')
    {{--    NEW STYLE   --}}
    <style>
        .tFex{
            position: relative !important;
            width: 100% !important;
        }
        .dataTables_filter { display: none; }
        span.redBg{
            background: #E67681;
        }
        .pointer{
            cursor: pointer;
        }
        .dataTables_info{
            margin-left: 15px;
            font-size: smaller;
        }
        .dataTables_paginate {
            color: #333;
            font-size: smaller;
        }
        .dataTables_paginate , .dataTables_info{
            margin-bottom: 10px;
            margin-top: 10px;
        }


    </style>


    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }


        tr {
            background-color: rgba(192, 192, 192, 0.3);
        }

        td {
            text-align: center;
        }


        .select2-container .select2-selection--single {
            height: 40px !important;
        }
        .select2-dropdown{
            z-index: 9999999;
        }
    </style>
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

@endsection

@section('customer')

    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Filter Engine') }} :</h3>
        </div>
    </div>

    {{-- For Search style   --}}
    <div class="topRow" >
        <form name="filter" id="filter">
            <div class="row align-items-center text-center text-md-left">
                <div class="col-md-3">
                    <div class="form-group">
                        <select id="input0" class="form-control" name="inputs[]" style="height : 100%" onchange="this.size=1; this.blur(); checkInput(this);">

                            <option value="" disabled selected>اختر</option>
                            <option value="sex">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</option>
                            <option value="birth_date_higri">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</option>
                            <option value="work">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</option>
                            <option value="mobile"> جوال العميل</option>
                            <option value="madany_id"> جهة المدني</option>
                            <option value="military_rank">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }} </option>
                            <option value="salary"> {{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</option>
                            <option value="salary_id"> {{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</option>
                            <option value="is_supported"> {{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</option>
                            <option value="has_obligations"> {{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</option>
                            <option value="obligations_value"> {{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }}</option>
                            <option value="has_financial_distress"> {{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</option>
                            <option value="financial_distress_value"> {{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }}</option>
                            <option value="class_id_agent">تصنيف الاستشاري</option>
                            <option value="realname">اسم المالك</option>
                            <option value="realmobile">جوال المالك</option>
                            <option value="city"> مدينة العقار</option>
                            <option value="region"> حي العقار</option>
                            <option value="region_ip"> منطقة العميل</option>
                            <option value="user_id"> استشاري المبيعات</option>
                            <option value="statusReq"> حالة الطلب</option>
                            <option value="id"> رقم الطلب</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">

                        <select id="condition0" class="form-control" name="condition[]">
                            <option value="=" selected>يساوي</option>
                            <option value="!=">لايساوي</option>
                            <option value="<">أقل من</option>
                            <option value=">">أكبر من</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="option0">
                    <div class="form-group afnan" id="work0" style="display: none;">

                        <select id="work_0" class="form-control" name="work[]">

                        @foreach ($worke_sources as $worke_source )
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                        @endforeach


                        </select>
                    </div>

                    <div class="form-group afnan" id="sex0" style="display: none;">

                        <select id="sex_0" class="form-control" name="sex[]">

                            <option value="أنثى">أنثى</option>
                            <option value="ذكر">ذكر</option>

                        </select>
                    </div>


                    <div class="form-group afnan" id="birth_date_higri0" style="display: none;">

                        <input type="text" name="birth_date_higri[]" style="text-align: right;" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date0">
                    </div>

                    <div class="form-group afnan" id="realname0" style="display: none;">

                        <input type="text" name="realname[]" class="form-control" id="realname_0">
                    </div>

                    <div class="form-group afnan" id="mobile0" style="display: none;">

                        <input type="text" name="mobile[]" class="form-control" id="mobile_0">
                    </div>

                    <div class="form-group afnan" id="id0" style="display: none;">

                        <input type="text" name="id[]" class="form-control" id="id_0">
                    </div>


                    <div class="form-group afnan" id="region0" style="display: none;">

                        <input type="text" name="region[]" class="form-control" id="region_0">
                    </div>

                    <div class="form-group afnan" id="realmobile0" style="display: none;">

                        <input type="text" name="realmobile[]" class="form-control" id="realmobile_0">
                    </div>


                    <div class="form-group afnan" id="madany_id0" style="display: none;">

                        <select id="madany_0" class="form-control" name="madany_id[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="military_rank0" style="display: none;">

                        <select id="military_0" class="form-control" name="military_rank[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="class_agent0" style="display: none;">

                        <select id="classAgent_0" class="form-control" name="class_id_agent[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="salary_id0" style="display: none;">

                        <select id="salaryid_0" class="form-control" name="salary_id[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="city0" style="display: none;">

                        <select id="city_0" class="form-control" name="city[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="regionip0" style="display: none;">

                        <select id="regionip_0" class="form-control" name="region_ip[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="userid0" style="display: none;">

                        <select id="userid_0" class="form-control" name="user_id[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="statusReq0" style="display: none;">

                        <select id="statusReq_0" class="form-control" name="statusReq[]">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="form-group afnan" id="salary0" style="display: none;">

                        <input id="salary_0" name="salary[]" type="number" class="form-control" autocomplete="salary">
                    </div>

                    <div class="form-group afnan" id="is_supported0" style="display: none;">

                        <select id="is_supported_0" class="form-control" name="is_supported[]">

                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group afnan" id="has_obligations0" style="display: none;">

                        <select id="has_obligations_0" class="form-control" name="has_obligations[]">

                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group afnan" id="has_financial_distress0" style="display: none;">

                        <select id="has_financial_distress_0" class="form-control" name="has_financial_distress[]">

                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group afnan" id="obligations_value0" style="display: none;">

                        <input id="obligations_value_0" name="obligations_value[]" type="number" class="form-control" autocomplete="obligations_value">
                    </div>

                    <div class="form-group afnan" id="financial_distress_value0" style="display: none;">

                        <input id="financial_distress_value_0" name="financial_distress_value[]" type="number" class="form-control" autocomplete="financial_distress_value">
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center padding-top-15" style="display: block">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                            <button class="text-center mr-3 mov item" name="add" id="add" type="button" >
                                <i class="fa fa-plus-circle"></i>
                                إضافة شرط
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div  id="dynamic_field" >

            </div>
            <div class="searchSub text-center d-block col-12">
                <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center padding-top-15" style="display: block">
                    <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                        <button class="text-center mr-3 green item"  name="submit" id="submit" type="button" >
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <span class="text-danger" id="error" role="alert" style="text-align:center;  display: flex;flex-direction: column;justify-content: center;"> </span>

    <br>

    <div class="middle-screen" style="display:none;" id="parrentTable">
        <div class="topRow">

            <div class="row align-items-center text-center text-md-left">

                <div class="col-lg-8 ">
                    <div class="tableUserOption  flex-wrap ">
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
                <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                    <div  id="dt-btns" class="tableAdminOption">

                    </div>

                </div>
            </div>
        </div>
        <div class="dashTable">
            <table id="request-table"  class="table table-bordred table-striped">

                <thead>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'agent comment') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</th>
                    <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
                </thead>
                <tbody id="customerTable">

                </tbody>

            </table>
        </div>
    </div>

@endsection

@section('updateModel')
    @include('FilterEngine.moveReq')
    @include('FilterEngine.confirmSendMsg')
@endsection

@section('confirmMSG')
    @include('QualityManager.Customer.confirmationMsg')
@endsection

@section('scripts')
    <script src="{{ url("/") }}/js/bootstrap-hijri-datetimepicker.min.js"></script>
{{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>--}}
    <script>
        $(document).ready(function() {
            $('#input0').select2({
                placeholder: 'اختر',
                allowClear: true
            });

            $('#qulityManagers').select2();
        });

        function checkInput(that) {

            var input_id = $(that).attr("id");
            var option_id = input_id.substr(5);

            //HIDE ALL ELEMENTS INDIDE <TD></TD>
            var x = document.getElementById("option" + option_id).querySelectorAll(".afnan");
            var i;
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            //END HIDE ALL ELEMENTS INDIDE <TD></TD>

            if (that.value == "sex") {
                document.getElementById("sex" + option_id).style.display = "block";
            }

            if (that.value == "work") {
                document.getElementById("work" + option_id).style.display = "block";
            }

            if (that.value == "salary") {
                document.getElementById("salary" + option_id).style.display = "block";
            }

            if (that.value == "mobile") {
                document.getElementById("mobile" + option_id).style.display = "block";
            }

            if (that.value == "id") {
                document.getElementById("id" + option_id).style.display = "block";
            }

            if (that.value == "is_supported") {
                document.getElementById("is_supported" + option_id).style.display = "block";
            }

            if (that.value == "has_obligations") {
                document.getElementById("has_obligations" + option_id).style.display = "block";
            }

            if (that.value == "obligations_value") {
                document.getElementById("obligations_value" + option_id).style.display = "block";
            }

            if (that.value == "has_financial_distress") {
                document.getElementById("has_financial_distress" + option_id).style.display = "block";
            }

            if (that.value == "financial_distress_value") {
                document.getElementById("financial_distress_value" + option_id).style.display = "block";
            }

            if (that.value == "birth_date_higri") {
                document.getElementById("birth_date_higri" + option_id).style.display = "block";
                $("#hijri-date" + option_id).hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });
            }

            if (that.value == "realname") {
                document.getElementById("realname" + option_id).style.display = "block";
            }
            if (that.value == "realmobile") {
                document.getElementById("realmobile" + option_id).style.display = "block";
            }
            if (that.value == "region") {
                document.getElementById("region" + option_id).style.display = "block";
            }
            if (that.value == "madany_id") {
                getMadanyValues("madany_" + option_id);
                document.getElementById("madany_id" + option_id).style.display = "block";

            }
            if (that.value == "military_rank") {
                getMiliratyValues("military_" + option_id);
                document.getElementById("military_rank" + option_id).style.display = "block";

            }
            if (that.value == "salary_id") {
                getSalaryValues("salaryid_" + option_id);
                document.getElementById("salary_id" + option_id).style.display = "block";

            }
            if (that.value == "city") {
                getCityValues("city_" + option_id);
                document.getElementById("city" + option_id).style.display = "block";

            }
            if (that.value == "class_id_agent") {
                getAgentClassValues("classAgent_" + option_id);
                document.getElementById("class_agent" + option_id).style.display = "block";

            }
            if (that.value == "region_ip") {
                getRegionValues("regionip_" + option_id);
                document.getElementById("regionip" + option_id).style.display = "block";

            }

            if (that.value == "user_id") {
                getAgentsValues("userid_" + option_id);
                document.getElementById("userid" + option_id).style.display = "block";

            }

            if (that.value == "statusReq") {
                getStatusReqValues("statusReq_" + option_id);
                document.getElementById("statusReq" + option_id).style.display = "block";

            }




        }

        function getMadanyValues(name) {
            $.get('{{route('getMadanyValue')}}',
                function(response) {
                    $data = '';
                    response.madany_works.forEach(($madany_works, $index) => {
                        $data += '<option selected value="' + $madany_works.id + '"' +
                            '>' + $madany_works.value + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getCityValues(name) {
            $.get('{{route('getCityValue')}}',
                function(response) {
                    $data = '';
                    response.cities.forEach(($cities, $index) => {
                        $data += '<option selected value="' + $cities.id + '"' +
                            '>' + $cities.value + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getStatusReqValues(name) {
            $.get('{{route('getStatusReqValues')}}',
                function(response) {
                    $data = '';
                    response.status.forEach(($status, $index) => {
                        $data += '<option selected value="' + $index + '"' +
                            '>' + $status + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getAgentsValues(name) {
            $.get('{{route('getAgentsValues')}}',
                function(response) {
                    $data = '';
                    response.users.forEach(($users, $index) => {
                        $data += '<option selected value="' + $users.id + '"' +
                            '>' + $users.name + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getRegionValues(name) {
            $.get('{{route('getRegionValues')}}',
                function(response) {
                    $data = '';
                    response.regions.forEach(($regions, $index) => {
                        if ($regions.region_ip != null)
                            $data += '<option selected value="' + $regions.region_ip + '"' +
                                '>' + $regions.region_ip + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getAgentClassValues(name) {
            $.get('{{route('getAgentClassValue')}}',
                function(response) {
                    $data = '';
                    response.classes.forEach(($classes, $index) => {
                        $data += '<option selected value="' + $classes.id + '"' +
                            '>' + $classes.value + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getMiliratyValues(name) {
            $.get('{{route('getMiliratyValue')}}',
                function(response) {
                    $data = '';
                    response.military_ranks.forEach(($military_ranks, $index) => {
                        $data += '<option selected value="' + $military_ranks.id + '"' +
                            '>' + $military_ranks.value + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }

        function getSalaryValues(name) {
            $.get('{{route('getSalaryValue')}}',
                function(response) {
                    $data = '';
                    response.salary_source.forEach(($salary_source, $index) => {
                        $data += '<option selected value="' + $salary_source.id + '"' +
                            '>' + $salary_source.value + '</option>';

                    });
                    $('#' + name).html($data);
                });
        }


        $(document).ready(function() {

            var i = 1;
            $('#add').click(function() {

                i++;

                var content = '<div class="row lign-items-center text-center text-md-left" id="row' + i + '">'; // ROW

                content += ' <div class="col-md-3">  <div class="form-group">  <select id="input' + i + '" class="form-control" name="inputs[]" onchange="this.size=1; this.blur(); checkInput(this);">'; //INPUT HEADER
                content += " <option value='' disabled selected>اختر</option>   <option value='sex'>{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</option>   <option value='birth_date_higri'>{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</option>  <option value='work'>{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</option>  <option value='mobile'>جوال العميل</option>  <option value='madany_id'> جهة المدني</option>  <option value='military_rank'>{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }} </option>"; //INPUT CONTENT
                content += "<option value='salary'> {{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</option>   <option value='salary_id'> {{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</option>   <option value='is_supported'> {{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</option>  <option value='has_obligations'> {{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</option>  <option value='obligations_value'> {{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }}</option>  <option value='has_financial_distress'> {{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</option>  <option value='financial_distress_value'> {{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }}</option> <option value='class_id_agent'>تصنيف الاستشاري</option> <option value='realname'>اسم المالك</option>  <option value='realmobile'>جوال المالك</option> <option value='city'> مدينة العقار</option>  <option value='region'> حي العقار</option> <option value='region_ip'> منطقة العميل</option> <option value='user_id'> استشاري المبيعات</option> <option value='statusReq'> حالة الطلب</option>  <option value='id'>رقم الطلب</option> "; //INPUT CONTENT2
                content += ' </select> </div> </div>'; // CLOSE INPUT

                content += '<div class="col-md-3">  <div class="form-group">  <select id="condition' + i + '" class="form-control" name="condition[]">'; //  CONDITION HEADER
                content += " <option value='=' selected>يساوي</option> <option value='!='>لايساوي</option> <option value='<'>أقل من</option> <option value='>'>أكبر من</option>"; //  CONDITION CONTENT
                content += ' </select> </div> </div>'; // CLOSE CONDITION

                content += '<div class="col-md-3" id="option' + i + '">'; //OPTION HEADER

                content += '<div class="form-group afnan" id="work' + i + '" style="display: none;"> <select id="work_' + i + '" class="form-control" name="work[]">'; //WORK HEADER
                var worke_sources = @json($worke_sources);
                var data ='';
                worke_sources.forEach((worke_sources, $index) => {
                    data += '<option value="' + worke_sources.id + '">' + worke_sources.value + '</option>';
                });
                content +=  data ; //WORK CONTENT
                content += ' </select> </div>'; // CLOSE WORK

                content += '<div class="form-group afnan" id="sex' + i + '" style="display: none;">  <select id="sex_' + i + '" class="form-control" name="sex[]">'; //SEX HEADER
                content += ' <option value="أنثى">أنثى</option>  <option value="ذكر">ذكر</option>'; //SEX CONTENT
                content += ' </select> </div>'; // CLOSE SEX

                content += '<div class="form-group afnan" id="birth_date_higri' + i + '" style="display: none;">'; //BIRTH DATE HEADER
                content += ' <input type="text" name="birth_date_higri[]" style="text-align: right;" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="hijri-date' + i + '">'; //BIRTH DATE CONTENT
                content += ' </div>'; // CLOSE BIRTH DATE

                content += '<div class="form-group afnan" id="realname' + i + '" style="display: none;">'; //realname HEADER
                content += ' <input type="text" name="realname[]"  class="form-control" id="realname_' + i + '">'; //realname CONTENT
                content += ' </div>'; // CLOSE realname

                content += '<div class="form-group afnan" id="id' + i + '" style="display: none;">'; //req id HEADER
                content += ' <input type="text" name="id[]"  class="form-control" id="id_' + i + '">'; //req id CONTENT
                content += ' </div>'; // CLOSE req id

                content += '<div class="form-group afnan" id="mobile' + i + '" style="display: none;">'; //mobile HEADER
                content += ' <input type="text" name="mobile[]"  class="form-control" id="mobile_' + i + '">'; //mobile CONTENT
                content += ' </div>'; // CLOSE mobile

                content += '<div class="form-group afnan" id="realmobile' + i + '" style="display: none;">'; //realmobile HEADER
                content += ' <input type="text" name="realmobile[]"  class="form-control" id="realmobile_' + i + '">'; //realmobile CONTENT
                content += ' </div>'; // CLOSE realmobile

                content += '<div class="form-group afnan" id="region' + i + '" style="display: none;">'; //region HEADER
                content += ' <input type="text" name="region[]"  class="form-control" id="region_' + i + '">'; //region CONTENT
                content += ' </div>'; // CLOSE region

                content += ' <div class="form-group afnan" id="madany_id' + i + '" style="display: none;">'; //MADANY HEADER
                content += '<select id="madany_' + i + '" class="form-control" name="madany_id[]">'; //MADANY CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE MADANY

                content += ' <div class="form-group afnan" id="military_rank' + i + '" style="display: none;">'; //MILIRATY HEADER
                content += '<select id="military_' + i + '" class="form-control" name="military_rank[]">'; //MILIRATY CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE MILIRATY

                content += ' <div class="form-group afnan" id="statusReq' + i + '" style="display: none;">'; //statusReq HEADER
                content += '<select id="statusReq_' + i + '" class="form-control" name="statusReq[]">'; //statusReq CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE statusReq

                content += ' <div class="form-group afnan" id="salary_id' + i + '" style="display: none;">'; //SALARY SOURCE HEADER
                content += '<select id="salaryid_' + i + '" class="form-control" name="salary_id[]">'; //SALARY SOURCE CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE SALARY SOURCE

                content += ' <div class="form-group afnan" id="city' + i + '" style="display: none;">'; //CITY HEADER
                content += '<select id="city_' + i + '" class="form-control" name="city[]">'; //CITY CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE CITY

                content += ' <div class="form-group afnan" id="userid' + i + '" style="display: none;">'; //AGENT HEADER
                content += '<select id="userid_' + i + '" class="form-control" name="user_id[]">'; //AGENT CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // AGENT CITY

                content += ' <div class="form-group afnan" id="regionip' + i + '" style="display: none;">'; //REGION HEADER
                content += '<select id="regionip_' + i + '" class="form-control" name="region_ip[]">'; //REGION CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE REGION

                content += ' <div class="form-group afnan" id="class_agent' + i + '" style="display: none;">'; //CLASS HEADER
                content += '<select id="classAgent_' + i + '" class="form-control" name="class_id_agent[]">'; //CLASS CONETENT
                content += "<option value=''>---</option>";
                content += ' </select> </div>'; // CLOSE CLASS

                content += '<div class="form-group afnan" id="salary' + i + '" style="display: none;">'; //SALARY HEADER
                content += '<input id="salary_' + i + '" name="salary[]" type="number" class="form-control" autocomplete="salary">'; //SALARY CONTENT
                content += "<option value=''>---</option>";
                content += ' </div>'; // CLOSE SALARY

                content += '<div class="form-group afnan" id="is_supported' + i + '" style="display: none;">  <select id="is_supported_' + i + '" class="form-control" name="is_supported[]">'; //SUPPORTED HEADER
                content += '<option value="yes">نعم</option> <option value="no">لا</option>'; //SUPPORTED CONTENT
                content += ' </select> </div>'; // CLOSE SUPPORTED

                content += '<div class="form-group afnan" id="has_obligations' + i + '" style="display: none;">  <select id="has_obligations_' + i + '" class="form-control" name="has_obligations[]">'; //obligations HEADER
                content += ' <option value="yes">نعم</option> <option value="no">لا</option>'; //obligations CONTENT
                content += ' </select> </div>'; // CLOSE obligations

                content += '<div class="form-group afnan" id="has_financial_distress' + i + '" style="display: none;">  <select id="has_financial_distress_' + i + '" class="form-control" name="has_financial_distress[]">'; //distress HEADER
                content += ' <option value="yes">نعم</option> <option value="no">لا</option>'; //distress CONTENT
                content += ' </select> </div>'; // CLOSE distress

                content += '<div class="form-group afnan" id="obligations_value' + i + '" style="display: none;">'; //obligations value HEADER
                content += '<input id="obligations_value_' + i + '" name="obligations_value[]" type="number" class="form-control" autocomplete="obligations_value">'; //obligations value CONTENT
                content += ' </div>'; // CLOSE obligations value

                content += '<div class="form-group afnan" id="financial_distress_value' + i + '" style="display: none;">'; //distress value HEADER
                content += '<input id="financial_distress_value_' + i + '" name="financial_distress_value[]" type="number" class="form-control" autocomplete="financial_distress_value">'; //distress value CONTENT
                content += ' </div>'; // CLOSE distress value

                content += ' </div>'; //close td


                content += '<div class="col-md-3"><div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">' +
                           '<div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">' +
                           '<button class="text-center mr-3 warning item btn_remove" name="remove" id="' + i + '" type="button" role="button">' +
                           '<i class="fa fa-times-circle" title="حذف الشرط"></i> حذف الشرط</button></div>'; //ADD REMOVE BUTTON

                content += ' </div>'; //close tr

                $('#dynamic_field').append(content);


                // $('#input' + i).select2({
                //     placeholder: 'اختر',
                //     allowClear: true
                // });

            });



            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });


            $('#submit').click(function() {
                document.getElementById('parrentTable').style.display = "none";
                document.getElementById('error').display = "none";

                $('#submit').attr("disabled", true);
                document.querySelector('#submit').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";



                $.ajax({
                    url: "{{ route('testFilter') }}",
                    method: "GET",
                    data: $('#filter').serialize(),
                    success: function(data) {


                        if (data.status != 2) {

                            //console.log(data);
                            document.getElementById('error').innerHTML ='';
                            document.getElementById('error').display = "none";
                            var table = $('#request-table').DataTable();
                            table.destroy();

                            tableRequest(data);

                            document.querySelector('#submit').innerHTML = " ابحث";
                            $('#submit').attr("disabled", false);
                        }

                        if (data.status == 2) {
                            document.getElementById('error').innerHTML = data.message;
                            document.getElementById('error').display = "block";

                            document.querySelector('#submit').innerHTML = " ابحث";
                            $('#submit').attr("disabled", false);

                        }
                        // $('#filter')[0].reset();
                    }
                });
            });
        });

        function tableRequesttt(requestData) {

            $.get("{{route('getrequest-datatable')}}", {
                requestData: requestData,

            }, function(data) {
                console.log(data);
            });


        }

        function tableRequest(requestData) {



            //$('#request-table').DataTable().clear().draw();


            var table = $('#request-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                    buttons: {
                        excelHtml5: "اكسل",
                        print: "طباعة",
                        pageLength: "عرض",

                    }
                },
                "lengthMenu": [
                    [10, 100, 500, 1000],
                    [10, 100, 500, 1000]
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
                ajax: ({
                    'url': "{{ url('/getrequest-datatable') }}",
                    'method': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data': {
                        "requestData": requestData
                    },


                }),
                columns: [

                    {
                        data: 'id',
                        name: 'requests.id'
                    },
                    {
                        data: 'user_name',
                        name: 'users.name'
                    },
                    {
                        data: 'cust_name',
                        name: 'customers.name'
                    },
                    {
                        data: 'mobile',
                        name: 'customers.mobile'
                    },
                    {
                        data: 'statusReq',
                        name: 'requests.statusReq'
                    },
                    {
                        data: 'comment',
                        name: 'requests.comment'
                    },
                    {
                        data: 'value',
                        name: 'classifcations.value'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                createdRow: function(row, data, index) {


                    $('td', row).eq(4).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(4).attr('title', data.statusReq); // to show other text of comment

                    $('td', row).eq(5).addClass('commentStyle'); // 6 is index of column
                    $('td', row).eq(5).attr('title', data.comment); // to show other text of comment


                },
                "initComplete": function(settings, json) {
                    $(".paginate_button").addClass("pagination-circle");
                    /* To Adaptive with New Design */
                    $('#example-search-input').keyup(function(){
                        table.search($(this).val()).draw() ;
                    })



                    table.buttons().container()
                        .appendTo( '#dt-btns' );

                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr( 'title', 'تصدير' );
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr( 'title', 'طباعة' ) ;
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr( 'title', 'عرض' );

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');

                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)


                    /* To Adaptive with New Design */
                }


            });


            document.getElementById('parrentTable').style.display = "block";
        }


        //-----------------------------------------------

        $(document).on('click', '#move', function(e) {



            document.getElementById("salesagent").value = '';
            document.getElementById('salesagentsError').innerHTML = '';

            var id = $(this).attr('data-id');

            $('#frm-update1').find('#id1').val(id);

            document.getElementById("movedReqID").value = id;

            $('#mi-modal7').modal('show');


        });



        //-----------------------------------------------

        $(document).on('click', '#submitMove', function(e) {



            $('#submitMove').attr("disabled", true);
            document.querySelector('#submitMove').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Please Wait') }}";

            var salesAgent = document.getElementById("salesagent").value;
            var id = document.getElementById("movedReqID").value;



            var url = "{{ route('admin.moveReqToAnother')}}";


            if (salesAgent != '') {

                $.get(url, {
                    salesAgent: salesAgent,
                    id: id
                }, function(data) { //data is array with two veribles (request[], ss)


                    if (data.updatereq == 1) {

                        $('#request-table').DataTable().ajax.reload();

                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    }


                    $('#mi-modal7').modal('hide');



                })

            } else
                document.getElementById('salesagentsError').innerHTML = 'الرجاء اختيار استشاري';
            document.querySelector('#submitMove').innerHTML = "تحويل";
            $('#submitMove').attr("disabled", false);


        });

        $(document).ready(function() {
            $('#qulityManager').select2();
        });

        $(document).on('click', '#addQuality', function(e) {

            document.getElementById("qualityError").innerHTML ='';
            var quality = '';

            var id = $(this).attr('data-id');

            $('#msg2').removeClass(["alert-success", "alert-danger"]).removeAttr("style").html("");


            var modalConfirm = function(callback) {


                $("#mi-modal5").modal('show');


                $("#modal-btn-si5").on("click", function() {

                    quality = document.getElementById("qulityManager").value;

                    $("#mi-modal5").modal('hide');
                    if (quality != '') {
                        callback(true);
                        $("#mi-modal5").modal('hide');
                    } else
                        document.getElementById("qualityError").innerHTML = 'الحقل مطلوب';

                });


                $("#modal-btn-no5").on("click", function() {
                    callback(false);
                    $("#mi-modal5").modal('hide');
                });
            };

            modalConfirm(function(confirm) {
                if (confirm) {

                    $.get("{{route('admin.addReqToQuality')}}", {
                        id: id, quality: quality,
                    }, function(data) {

                        console.log(data);
                        if (data.status != 0) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        } else
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    });



                } else {
                    //reject
                }
            });


        });


    </script>


@endsection

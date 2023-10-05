@extends('layouts.content')

@section('title')
    الملف الشخصى  | {{$user->name}}
@endsection


@section('css_style')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 1%;
        }

        .reqType {
            width: 2%;
        }

        .reqDate {
            text-align: center;
        }

        .commentStyle {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        tr:hover td {
            background: #d1e0e0
        }
    </style>
    <style>

        .select2-results {
            max-height: 350px;
        }

        .bigdrop {
            width: 600px !important;
        }
        .select2-container .select2-selection--single{
            height: 45px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 35px;
        }
        .new-content ul li {
            font-size: 14px;
            font-weight: bold;
            padding: 18px;
            color: #0F5B94;
            cursor: pointer;
            transition: ease-in-out 0.3s;
            -webkit-transition: ease-in-out 0.3s;
            -moz-transition: ease-in-out 0.3s;
            -ms-transition: ease-in-out 0.3s;
            -o-transition: ease-in-out 0.3s;
            text-align: center;
            width: auto;
        }
        .head{
           width: 25%;
        }
        td{
            text-align: center;
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
    <section class="new-content mt-0" >
        <div class="container-fluid">
            <div class="row "  >

                <div class="col-md-12">

                    <div class="row">
                        <div class="col-lg-10 pt-5  mb-md-0" id="menu1" style="display:{{$user->profile?'block':'none'}}">
                            <div class="userFormsDetails topRow">
                               <div class="row">
                                   <div class="col-lg-9 p-3">
                                        <h6><b class="w-20"> الرقــم الوظيفى</b>:
                                            <span id="SSNHead"> {{optional($user->profile)->job_number}}</span>
                                        </h6>
                                        <h6><b class="w-20"> الاســم بالعـربى</b> :
                                            <span id="nameArHead">{{@$user->profile->name ?? '-'}}</span>
                                        </h6>
                                        <h6><b class="w-20">الاسم بالإنجليزى</b> :
                                            <span id="nameEnHead">{{@$user->profile->name_en}}</span>
                                        </h6>
                                        <h6><b class="w-20">الجنسيــــــــــــــة </b> :
                                            <span id="nationalityHead">{{@optional($user->profile)->nationality->value}}</span>
                                        </h6>
                                       <hr>
                                       <a href="{{route('HumanResource.user.profile',[@$user->id,'pdf'])}}" class="btn btn-primary ">
                                           <i class="fa fa-file-pdf"></i>
                                           تحميل ملف الموظف بصيفة ال PDF
                                       </a>
                                   </div>
                                   <div class="col-lg-3">
                                       <img id="genderShow" src="{{asset(@$user->profile->gender.'.jpg')}}" alt="{{@$user->profile->gender}}" class="img-thumbnail img-fluid">
                                   </div>
                               </div>
                            </div>
                        </div>

                        <div class="col-lg-12 pt-5  mb-md-0">
                            <div class="userFormsInfo  w-100  ">

                                <ul class="list-unstyled d-flex flex-wrab align-items-center d-block topRow">
                                    <li id="content1" class="tab width-20  active-on">
                                        <i class="fa fa-user pr-2 pl-2"></i>
                                        المعلومات الشخصيه
                                    </li>
                                    <li id="content2" class="tab">
                                        <i class="fa fa-building pr-2 pl-2"></i>
                                        معلومات الوظيفة
                                    </li>
                                    <li id="content3" class="tab">
                                        <i class="fa fa-file-pdf pr-2 pl-2"></i>
                                        مرفقات الملف الشخصى
                                    </li>
                                    <li id="content4" class="tab width-20">
                                        <i class="fa fa-graduation-cap pr-2 pl-2"></i>
                                        المؤهلات
                                    </li>
                                    <li id="content5" class="tab width-20">
                                        <i class="fa fa-user-shield pr-2 pl-2"></i>
                                        الكفالة
                                    </li>
                                    <li id="content6" class="tab width-20">
                                        <i class="fa fa-id-card pr-2 pl-2"></i>
                                        الهوية
                                    </li>
                                    <li id="content7" class="tab width-20">
                                        <i class="fa fa-sticky-note pr-2 pl-2"></i>
                                        ملاحظات
                                    </li>

                                </ul>
                                <form id="profile-form" method="post" class="tab-body" enctype="multipart/form-data">
                                    @csrf

                                    <div class="tabs-serv">
                                        <div class="tab-body" >
                                            <div class="row hdie-show display-flex" id="content1-cont">

                                                <div class="col-lg-12   mb-md-0">
                                                    <input type="hidden" name="is_personal" value="1">
                                                    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">

                                                    <div class="userFormsContainer mb-3">
                                                        <div class="userFormsDetails topRow">
                                                            <div class="row">
                                                                <div class="col-9 mb-3">
                                                                    <h5>
                                                                      <b>  المعلومات الشخصية</b>
                                                                    </h5>

                                                                </div>
                                                                <div class="col-lg-3 text-right">
                                                                    @if(auth()->user()->role != 1)
                                                                    <a class="btn mr-2 col-lg-5 btn-primary text-white" id="personalEdit" type="button" role="button"><i class="fa fa-edit mr-1 ml-1"></i>تعديل</a>
                                                                    <a class="btn mr-2 col-lg-5 btn-dark text-white" id="personalShow" style="display: none" type="button" role="button"><i class="fa fa-eye mr-1 ml-1"></i>عرض</a>
                                                                    <button style="display:none" id="personalSubmit" type="submit" class="btn mr-2 col-lg-5 btn-success text-white">
                                                                        <i class="fas fa-save"></i>
                                                                        <b>حفظ</b>
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-6 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="name" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="name">إسم الموظف كاملاً</label>
                                                                        <input type="text" disabled id="name" name="name" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}" class="form-control" value="{{optional($user->profile)->name ?? old('name') }}">
                                                                    </div>
                                                                    <span class="text-danger" id="name-error"></span>
                                                                </div>
                                                                <div class="col-6 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="name_en" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="name_en">إسم الموظف باللغة الإنجليزية</label>
                                                                        <input type="text" disabled id="name_en" name="name_en" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}" class="form-control" value="{{optional($user->profile)->name_en ?? old('name_en') }}">
                                                                    </div>
                                                                    <span class="text-danger" id="name-error"></span>
                                                                </div>


                                                                <div class="col-6 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="email" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="email">البريد الإلكترونى الرسمى</label>
                                                                        <input type="email" disabled id="email" name="email" placeholder="البريد الإلكترونى الرسمى" class="form-control" value="{{ optional($user->profile)->email ?? old('email') }}">
                                                                    </div>
                                                                    <span class="text-danger" id="email-error"></span>

                                                                </div>
                                                                @php($numbers_count = \App\CustomersPhone::where('user_id',$user->id)->count())
                                                                <div class="col-md-3 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20"  data-id="mobile" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20"></i></span>
                                                                        <label for="Customer" >
                                                                            رقم الجوال
                                                                            <small id="checkMobile" style="display: none" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                                                                <i class="fas fa-question i-20"></i>
                                                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                                                            </small>
                                                                        </label>
                                                                        @if ($numbers_count > 0)
                                                                            <span class="badge badge-dark">{{$numbers_count+1}}</span>
                                                                        @else
                                                                            <span class="badge badge-dark" id="ShowFormNumber">1</span>
                                                                        @endif


                                                                        <a onclick="addForm()" id="addMobiles" data-toggle="modal"  class="badge text-white badge-primary p-1" style="float: left;display: none"><i class="fa fa-plus"></i></a>
                                                                        <a onclick="showForm()" data-toggle="modal" id="showForm" style="display:{{$numbers_count==0 ? 'none' : 'block'}};float: left" class="badge text-white badge-success btn-sm  p-1 mr-1 ml-1"><i class="fa fa-list" style="font-size: 11px"></i></a>

                                                                        <input id="mobile" name="mobile" disabled type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile',optional($user->profile)->mobile) }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="5xxxxxxxx">

                                                                    </div>
                                                                    <span class="text-danger" id="mobile-error"></span>
                                                                </div>

                                                                <div class="col-3 mb-3">
                                                                    <div class="form-group">
                                                                <span id="record" role="button" type="button" class="item span-20" data-id="control_nationality_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                    <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                </span>
                                                                        <label for="control_nationality_id">الجنسية</label>
                                                                        <select id="control_nationality_id" disabled name="control_nationality_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                            <option {{!optional($user->profile)->control_nationality_id ? 'selected' : ''}} disabled>أختار الجنسية</option>
                                                                            @if(isset($controls['nationality']))
                                                                                @foreach($controls['nationality'] as $key=>$nationality)
                                                                                    <option value="{{$nationality->id}}" data-id="{{$nationality->parent_id}}" {{optional($user->profile)->control_nationality_id == $nationality->id ?'selected' :""}}> {{$nationality->value}} </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <span class="text-danger" id="control_nationality_id-error"></span>
                                                                </div>


                                                                <div class="col-3 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="marital_status" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="marital_status">الحالة الإجتماعية</label>
                                                                        <select id="marital_status" disabled name="marital_status" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                            <option {{!optional($user->profile)->marital_status ? 'selected' : ''}} disabled>أختار الحالة الإجتماعية</option>
                                                                            <option value="single" {{optional($user->profile)->marital_status == "single" ?'selected' :""}}> أعزب /عزباء </option>
                                                                            <option value="married" {{optional($user->profile)->marital_status == "married" ?'selected' :""}}> متزوج/ـة </option>
                                                                            <option value="divorced" {{optional($user->profile)->marital_status == "divorced" ?'selected' :""}}> مطلق/ـة </option>
                                                                            <option value="widow" {{optional($user->profile)->marital_status == "widow" ?'selected' :""}}> أرمل/ـة </option>
                                                                        </select>
                                                                    </div>
                                                                    <span class="text-danger" id="marital_status-error"></span>

                                                                </div>
                                                                <div class="col-3 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="family_count" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="family_count">عدد افراد الأسرة</label>
                                                                        <input type="number" disabled id="family_count" name="family_count" placeholder=" عدد افراد الأسرة " class="form-control" value="{{ optional($user->profile)->family_count ?? old('family_count') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="family_count-error"></span>

                                                                </div>
                                                                <div class="col-lg-3">

                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="birth_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="birth_date">تاريخ الميلاد هجري </label>

                                                                        <input type="text" disabled name="birth_date" style="text-align: right;"  value="{{ optional($user->profile)->birth_date ?? old('birth_date') }}" class="form-control hijri-date" placeholder="يوم/شهر/سنة" id="birth_date">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="birth_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="birth_date_m">تاريخ الميلاد ميلادى </label>
                                                                        <input type="date" disabled style="text-align: right;" class="form-control" id="birth_date_m">
                                                                    </div>
                                                                    <span class="text-danger" id="birth_date-error"></span>

                                                                </div>
                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="gender" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="gender">النوع</label>
                                                                        <select id="gender" name="gender" disabled onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                            <option {{!optional($user->profile)->gender ? 'selected' : ''}} disabled>أختار النوع</option>
                                                                            <option value="male" {{optional($user->profile)->gender == "male" ?'selected' :""}}> ذكر </option>
                                                                            <option value="female" {{optional($user->profile)->gender == "female" ?'selected' :""}}> انثى </option>
                                                                        </select>
                                                                    </div>
                                                                    <span class="text-danger" id="gender-error"></span>

                                                                </div>
                                                                <div class="col-8 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="title" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="title"> العنوان المختصر   </label>
                                                                        <input type="text" disabled id="title" name="title" placeholder=" العنوان المختصر" class="form-control" value="{{ optional($user->profile)->title ?? old('title') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="title-error"></span>

                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="area_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="area_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property region') }}</label>
                                                                        <select id="area_id" name="area_id" class="area  select2-request form-control @error('region') is-invalid @enderror" style="height: 45px">
                                                                            <option disabled selected>أختار المنطقة ..</option>
                                                                            @foreach($areas as $area)
                                                                                <option value="{{$area->id}}" {{$area->id ==optional($user->profile)->area_id ? 'selected' : '' }}>{{$area->value}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        المنطقة الحالية:<b  class="area_show">  [  {{@optional($user->profile)->area->value}}  ]  </b>
                                                                        @error('area_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                          <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                         <span id="record" role="button" type="button" class="item span-20" data-id="city_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="city_id" class="control-label mb-1">المدينه

                                                                        </label>
                                                                        <select id="city_id" name="city_id" class="city  select2-request form-control @error('city_id') is-invalid @enderror" style="height: 45px">
                                                                            <option disabled selected>أختار المدينه ..</option>
                                                                        </select>
                                                                        المدينة الحالية:<b  class="city_show">  [  {{@optional($user->profile)->city->value ?? 'لا يوجد'}}  ]  </b>
                                                                        @error('city_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                          <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                         <span id="record" role="button" type="button" class="item span-20" data-id="district_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="district_id" class="control-label mb-1">الحى </label>
                                                                        <select id="district_id" name="district_id" class="district  select2-request form-control @error('district_id') is-invalid @enderror" style="height: 45px">
                                                                            <option disabled selected>أختار الحى ..</option>
                                                                        </select>
                                                                        الحى الحالى :<b class="district_show">  [  {{@optional($user->profile)->district->value ?? 'لا يوجد'}}  ]  </b>
                                                                        @error('district_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                          <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="street_name" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="street_name">إسم الشارع  </label>
                                                                        <input type="text" disabled id="street_name" name="street_name" placeholder=" إسم الشارع  " class="form-control" value="{{ optional($user->profile)->street_name ?? old('street_name') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="street_name-error"></span>

                                                                </div>
                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="building_number" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="building_number"> رقم المبنى  </label>
                                                                        <input type="text" disabled id="building_number" name="building_number" placeholder=" رقم المبنى " class="form-control" value="{{ optional($user->profile)->building_number ?? old('building_number') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="building_number-error"></span>

                                                                </div>
                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="unit_number" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="unit_number">رقم الوحدة   </label>
                                                                        <input type="text" disabled id="unit_number" name="unit_number" placeholder=" رقم الوحدة  " class="form-control" value="{{ optional($user->profile)->unit_number ?? old('unit_number') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="unit_number-error"></span>

                                                                </div>


                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="contact_person_name" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="contact_person_name"> إسم الشخص القريب  </label>
                                                                        <input type="text" disabled id="contact_person_name" name="contact_person_name" placeholder=" إسم الشخص القريب " class="form-control" value="{{ optional($user->profile)->contact_person_name ?? old('contact_person_name') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="contact_person_name-error"></span>

                                                                </div>
                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="contact_person_relation" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="contact_person_relation"> صلة القرابة   </label>
                                                                        <input type="text" disabled id="contact_person_relation" name="contact_person_relation" placeholder="صلة القرابة" class="form-control" value="{{ optional($user->profile)->contact_person_relation ?? old('contact_person_relation') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="contact_person_relation-error"></span>

                                                                </div>
                                                                <div class="col-4 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="contact_person_number" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="contact_person_number"> رقم جوال الشخص قريب   </label>
                                                                        <input type="number" disabled id="contact_person_number" name="contact_person_number" placeholder="رقم جوال الشخص قريب " class="form-control" value="{{ optional($user->profile)->contact_person_number ?? old('contact_person_number') }}">

                                                                    </div>
                                                                    <span class="text-danger" id="contact_person_number-error"></span>

                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hdie-show display-flex" id="content2-cont">
                                                <div class="col-lg-12   mb-md-0">
                                                    <div class="userFormsContainer mb-3">
                                                            <div class="userFormsDetails topRow">
                                                                <div class="row">
                                                                    <div class="col-9 mb-3">
                                                                        <h5>
                                                                            <b>  معلومات الوظيفة</b>
                                                                        </h5>

                                                                    </div>
                                                                    <div class="col-lg-3 text-right">
                                                                        @if(auth()->user()->role != 1)
                                                                        <a class="btn mr-2 col-lg-5 btn-primary text-white" id="jobEdit" type="button" role="button"><i class="fa fa-edit mr-1 ml-1"></i>تعديل</a>
                                                                        <a class="btn mr-2 col-lg-5 btn-dark text-white" id="jobShow" style="display: none" type="button" role="button"><i class="fa fa-eye mr-1 ml-1"></i>عرض</a>
                                                                        <button type="submit"id="jobSubmit" style="display: none" class="btn mr-2 col-lg-5 btn-success text-white">
                                                                            <i class="fas fa-save"></i>
                                                                            <b>حفظ</b>
                                                                        </button>
                                                                            @endif
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <hr>
                                                                    </div>
                                                                    <div class="col-6 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="job" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="job">الوظيفة </label>
                                                                            <input type="text"  id="job" name="job" placeholder="من فضلك أدخل وظيفة الموظف" class="form-control" value="{{ optional($user->profile)->job ?? old('job') }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="job_number" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="job_number">الرقم الوظيفى</label>
                                                                            <input type="text"  id="job_number" name="job_number" placeholder="الرقم الوظيفى " class="form-control" value="{{@$user->profile->job_number ?? old('job') }}">

                                                                        </div>

                                                                    </div>
                                                                    <div class="col-4 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="control_company_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="control_company_id">الشركة</label>
                                                                            <select id="control_company_id" name="control_company_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                                <option {{!optional($user->profile)->control_company_id ? 'selected' : ''}} disabled>أختار الشركة</option>
                                                                                @if(isset($controls['company']))
                                                                                    @foreach($controls['company'] as $key=>$company)
                                                                                        <option value="{{$company->id}}" {{optional($user->profile)->control_company_id == $company->id ?'selected' :""}}> {{$company->value}} </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-4 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="control_section_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="control_section_id">القسم </label>
                                                                            <select id="control_section_id" name="control_section_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                                <option {{!optional($user->profile)->control_section_id ? 'selected' : ''}} disabled>أختار القسم</option>
                                                                                @if(isset($controls['section']))
                                                                                    @foreach($controls['section'] as $key=>$section)
                                                                                        <option value="{{$section->id}}" {{optional($user->profile)->control_section_id == $section->id ?'selected' :""}}> {{$section->value}} </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-4 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="control_subsection_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="control_subsection_id">القسم الفرعى</label>
                                                                            <select id="control_subsection_id" name="control_subsection_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                                <option {{!optional($user->profile)->control_subsection_id ? 'selected' : ''}} disabled>أختار القسم الرئيسي أولاً</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-4 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="control_work_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="control_work_id">طبيعه العمل </label>
                                                                            <input type="text" id="control_work_id" name="control_work_id" value="{{optional($user->profile)->control_work_id }}" class="form-control">
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-4 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="control_insurances_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="control_insurances_id">التأمينات</label>
                                                                            <select id="control_insurances_id" name="control_insurances_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                                <option {{!optional($user->profile)->control_insurances_id ? 'selected' : ''}} disabled>أختار التأمينات</option>
                                                                                @if(isset($controls['insurances']))
                                                                                    @foreach($controls['insurances'] as $key=>$insurances)
                                                                                        <option value="{{$insurances->id}}" {{optional($user->profile)->control_insurances_id == $insurances->id ?'selected' :""}}> {{$insurances->value}} </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-4 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="control_medical_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="control_medical_id">التأمين الطبى   </label>
                                                                            <select id="control_medical_id" name="control_medical_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                                <option {{!optional($user->profile)->control_guaranty_id ? 'selected' : ''}} disabled>التأمين الطبى</option>
                                                                                @if(isset($controls['medical']))
                                                                                    @foreach($controls['medical'] as $key=>$medical)
                                                                                        <option value="{{$medical->id}}" {{optional($user->profile)->control_medical_id == $medical->id ?'selected' :""}}> {{$medical->value}} </option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>

                                                                    </div>

                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="work_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="work_date">تاريخ العقد هجري   </label>
                                                                            <input id="work_date" style="text-align: right" name="work_date" type="text"  class="form-control hijri-date"  value="{{ optional($user->profile)->work_date ?? old('work_date') }}" placeholder="يوم/شهر/سنة" autofocus>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="work_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="work_date_m">تاريخ العقد ميلادي   </label>
                                                                            <input id="work_date_m" type="date" style="text-align: right" class="form-control ">
                                                                        </div>
                                                                        <span class="text-danger"  role="alert"> </span>

                                                                    </div>
                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="work_date_2" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="work_date_2">تاريخ العقد 2 هجري   </label>
                                                                            <input id="work_date_2" style="text-align: right" name="work_date_2" type="text" class="form-control hijri-date" placeholder="يوم/شهر/سنة" value="{{ optional($user->profile)->work_date_2 ?? old('work_date_2') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group" >
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="work_date_2" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="work_date_2_m">تاريخ العقد 2 ميلادي   </label>
                                                                            <input id="work_date_2_m" style="text-align: right" type="date" class="form-control">
                                                                        </div>
                                                                        <span class="text-danger"  role="alert"> </span>

                                                                    </div>

                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="work_end_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="work_end_date">تاريخ نهاية العقد هجري  </label>
                                                                            <input id="work_end_date" style="text-align: right" name="work_end_date" type="text" class="form-control  hijri-date" placeholder="يوم/شهر/سنة" value="{{ optional($user->profile)->work_end_date ?? old('work_end_date') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="work_end_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="work_end_date_m">تاريخ نهاية العقد ميلادي  </label>
                                                                            <input id="work_end_date_m" style="text-align: right" type="date" class="form-control">
                                                                        </div>
                                                                        <span class="text-danger"  role="alert"> </span>

                                                                    </div>
                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="direct_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="direct_date">تاريخ مباشرة العمل هجري </label>
                                                                            <input id="direct_date" style="text-align: right" name="direct_date" type="text" placeholder="يوم/شهر/سنة" value="{{ optional($user->profile)->direct_date ?? old('direct_date') }}" class="form-control hijri-date" autofocus>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-3 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="direct_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="direct_date_m" style="">تاريخ مباشرة العمل ميلادي </label>
                                                                            <input id="direct_date_m" style="text-align: right"  type="date" class="form-control" autofocus>
                                                                        </div>
                                                                        <span class="text-danger"  role="alert"> </span>

                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                    <div class="mb-3" id="custodyContainer">
                                                                        <label for="direct_date_m" style="">العهدة </label>

                                                                        @if($user->profile && count(@$user->profile->custodies) > 0)
                                                                            @foreach(optional($user->profile)->custodies as $custa)

                                                                                <div class="row pl-4 mb-1">
                                                                                    <select id="custody" name="custody[]" style="height: 45px;" class="form-control mr-2 col-lg-2 custody"  >
                                                                                        <option> أختار العهدة </option>
                                                                                        @if(isset($controls['custody']))
                                                                                            @foreach($controls['custody'] as $key=>$cust)
                                                                                                <option value="{{$cust->id}}" {{$custa->control_id == $cust->id ? "selected":""}}> {{$cust->value}} </option>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </select>
                                                                                    <input type="text" name="descriptions[]" style="height: 45px;" class="form-control mr-2 col-lg-8 " id="descriptions" value="{!! $custa->description !!}">
                                                                                    <label style="float: left" onclick="addAnotherRow()" >
                                                                                        <button class="btn btn-dark" style="height: 45px;" type="button"  role="button">
                                                                                            <i class="fa fa-plus-square text-white"></i>
                                                                                        </button>
                                                                                    </label>
                                                                                    @if(!$loop->first)
                                                                                        <label style="float: left;margin-right:3px;margin-left:3px" onclick="removeRow(this)">
                                                                                            <button class="btn btn-danger" style="height: 45px;" type="button"  role="button">
                                                                                                <i class="fa fa-trash"></i>
                                                                                            </button>
                                                                                        </label>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="row pl-4 mb-1">
                                                                                <select id="custody" name="custody[]" style="height: 45px;" class="form-control mr-2 col-lg-2 custody"  >
                                                                                    @if(isset($controls['custody']))
                                                                                        @foreach($controls['custody'] as $key=>$cust)
                                                                                            <option value="{{$cust->id}}"> {{$cust->value}} </option>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </select>
                                                                                <input type="text" name="descriptions[]" style="height: 45px;" class="form-control mr-2 col-lg-8 " id="descriptions">
                                                                                <label style="float: left" onclick="addAnotherRow()" >
                                                                                    <button class="btn btn-dark" style="height: 45px;" type="button"  role="button">
                                                                                        <i class="fa fa-plus-square text-white"></i>
                                                                                    </button>
                                                                                </label>
                                                                            </div>
                                                                        @endif


                                                                    </div>
                                                                    </div>

                                                                    <input type="hidden" name="is_job" value="1">
                                                                    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">

                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row hdie-show display-flex" id="content3-cont">
                                                <div class="col-lg-12   mb-md-0">
                                                    <div class="userFormsContainer mb-3">
                                                        <div class="userFormsDetails topRow">
                                                                <div class="row">
                                                                    <div class="col-9 mb-3">
                                                                        <h5>
                                                                            <b>  مرفقات الملف الشخصى </b>
                                                                        </h5>

                                                                    </div>
                                                                    <input type="hidden" name="is_file" value="1">
                                                                    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">

                                                                    <div class="col-lg-12">
                                                                        <hr>
                                                                    </div>

                                                                    <div class="col-lg-7 ">
                                                                        <div class="tableUserOption  flex-wrap ">
                                                                            @if(auth()->user()->role != 1)
                                                                            <div class="addBtn col-md-5 mt-lg-0 mt-3">
                                                                                <a class="btn text-white mr-2 Cloud" data-toggle="add-form" onclick="addFileForm()">
                                                                                    <i class="fas fa-plus"></i>
                                                                                    إضافة ملف
                                                                                </a>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4 text-md-right mt-lg-0 mt-3">

                                                                        <div id="dt-btns" class="tableAdminOption">
                                                                            {{-- Here We Will Add Buttons of Datatable  --}}

                                                                        </div>

                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="dashTable">
                                                                            <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                                                                                <thead>
                                                                                <tr>

                                                                                    <th>#</th>
                                                                                    <th>إسم الملف</th>
                                                                                    <th>تاريخ الرفع </th>
                                                                                    <th>التحكم</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-9 mb-3">
                                                                        <h5>
                                                                            <b>  المرفقات المؤرشفة  </b>
                                                                        </h5>

                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="dashTable">
                                                                            <table id="pendingReqs-tables" class="table table-bordred table-striped data-tables">
                                                                                <thead>
                                                                                <tr>

                                                                                    <th>#</th>
                                                                                    <th>إسم الملف</th>
                                                                                    <th>تم الحذف فى </th>
                                                                                    <th>تاريخ الرفع </th>
                                                                                    <th>التحكم</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hdie-show display-flex" id="content4-cont">
                                                <div class="col-lg-12   mb-md-0">
                                                    <div class="userFormsContainer mb-3">
                                                        <div class="userFormsDetails topRow">
                                                            <div class="row">
                                                                <div class="col-9 mb-3">
                                                                    <h5>
                                                                        <i class="fa fa-graduation-cap"></i>
                                                                        <b>  المؤهلات  </b>
                                                                    </h5>

                                                                </div>
                                                                <input type="hidden" name="is_qualification" value="1">
                                                                <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">

                                                                <div class="col-lg-3 text-right">
                                                                    @if(auth()->user()->role != 1)
                                                                    <a class="btn mr-2 col-lg-5 btn-primary text-white" id="qualificationEdit" type="button" role="button"><i class="fa fa-edit mr-1 ml-1"></i>تعديل</a>
                                                                    <a class="btn mr-2 col-lg-5 btn-dark text-white" id="qualificationShow" style="display: none" type="button" role="button"><i class="fa fa-eye mr-1 ml-1"></i>عرض</a>
                                                                    <button type="submit" style="display:none" id="qualificationSubmit" class="btn mr-2 col-lg-5 btn-success text-white">
                                                                        <i class="fas fa-save"></i>

                                                                        <b>حفظ</b>
                                                                    </button>
                                                                        @endif
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="form-group">
                                                                        <label for="qualification">المؤهلات</label>
                                                                        <textarea class="form-control" id="qualification" name="qualification">{{optional($user->profile)->qualification }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="form-group">
                                                                        <label for="specialization">التخصص</label>
                                                                        <input type="text" class="form-control" id="specialization" name="specialization" value="{{optional($user->profile)->specialization }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row hdie-show display-flex" id="content5-cont">
                                                <div class="col-lg-12   mb-md-0">
                                                    <div class="userFormsContainer mb-3">
                                                        <div class="userFormsDetails topRow">
                                                            <div class="row">
                                                                <div class="col-9 mb-3">
                                                                    <h5>
                                                                        <i class="fa fa-user-shield"></i>
                                                                        <b>  الكفالة  </b>
                                                                    </h5>

                                                                </div>
                                                                <div class="col-lg-3 text-right">
                                                                    @if(auth()->user()->role != 1)
                                                                    <a class="btn mr-2 col-lg-5 btn-primary text-white" id="guarantyEdit" type="button" role="button"><i class="fa fa-edit mr-1 ml-1"></i>تعديل</a>
                                                                    <a class="btn mr-2 col-lg-5 btn-dark text-white" id="guarantyShow" style="display: none" type="button" role="button"><i class="fa fa-eye mr-1 ml-1"></i>عرض</a>
                                                                    <button type="submit"  style="display:none" id="guarantySubmit" class="btn mr-2 col-lg-5 btn-success text-white">
                                                                        <i class="fas fa-save"></i>

                                                                        <b>حفظ</b>
                                                                    </button>
                                                                        @endif
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-6 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="control_guaranty_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="control_guaranty_id">الكفالة</label>
                                                                        <select id="control_guaranty_id" name="control_guaranty_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                            <option {{!optional($user->profile)->control_guaranty_id ? 'selected' : ''}} disabled>أختار الكفالة</option>
                                                                            @if(isset($controls['guaranty']))
                                                                                @foreach($controls['guaranty'] as $key=>$guaranty)
                                                                                    <option value="{{$guaranty->id}}" {{optional($user->profile)->control_guaranty_id == $guaranty->id ?'selected' :""}}> {{$guaranty->value}} </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-6 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="control_guaranty_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="control_guaranty_company_id">الشركة الكافلة</label>
                                                                        <select id="control_guaranty_company_id" name="control_guaranty_company_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                            <option {{!optional($user->profile)->control_guaranty_company_id ? 'selected' : ''}} disabled>أختار الكفالة</option>
                                                                            @if(isset($controls['company']))
                                                                                @foreach($controls['company'] as $key=>$guaranty_company)
                                                                                    <option value="{{$guaranty_company->id}}" {{optional($user->profile)->control_guaranty_company_id == $guaranty_company->id ?'selected' :""}}> {{$guaranty_company->value}} </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="guaranty_name" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="guaranty_name">إسم الكفيل </label>
                                                                        <input type="text"  id="guaranty_name" name="guaranty_name" placeholder="من فضلك أدخل الكفيل" class="form-control" value="{{ optional($user->profile)->guaranty_name ?? old('guaranty_name') }}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hdie-show display-flex" id="content6-cont">
                                                <div class="col-lg-12   mb-md-0">
                                                    <div class="userFormsContainer mb-3">
                                                        <div class="userFormsDetails topRow">
                                                            <div class="row">
                                                                <div class="col-9 mb-3">
                                                                    <h5>
                                                                        <i class="fa fa-id-card"></i>
                                                                        <b>  الهوية  </b>
                                                                    </h5>

                                                                </div>
                                                                <input type="hidden" name="is_identity" value="1">
                                                                <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">

                                                                <div class="col-lg-3 text-right">
                                                                    @if(auth()->user()->role != 1)
                                                                    <a class="btn mr-2 col-lg-5 btn-primary text-white" id="identityEdit" type="button" role="button"><i class="fa fa-edit mr-1 ml-1"></i>تعديل</a>
                                                                    <a class="btn mr-2 col-lg-5 btn-dark text-white" id="identityShow" style="display: none" type="button" role="button"><i class="fa fa-eye mr-1 ml-1"></i>عرض</a>
                                                                    <button type="submit" class="btn mr-2 col-lg-5 btn-success text-white" style="display:none" id="identitySubmit">
                                                                        <i class="fas fa-save"></i>

                                                                        <b>حفظ</b>
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-3 mb-3" id="residence_type_container">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="control_identity_id" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="control_identity_id">نوع الهوية  </label>
                                                                        <select id="control_identity_id" name="control_identity_id"  onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control" style="width:100%">
                                                                            <option {{!optional($user->profile)->control_guaranty_id ? 'selected' : ''}} disabled>أختار الهوية</option>
                                                                            @if(isset($controls['identity']))
                                                                                @foreach($controls['identity'] as $key=>$identity)
                                                                                    <option value="{{$identity->id}}" {{optional($user->profile)->control_identity_id == $identity->id ?'selected' :""}}> {{$identity->value}} </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3 mb-3" id="residence_number_container">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="residence_number" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="residence_number">رقم الهوية  </label>
                                                                        <input id="residence_number" style="text-align: right" name="residence_number" type="text" value="{{ optional($user->profile)->residence_number ?? old('residence_number') }}"  placeholder="رقم الهوية" class="form-control" autocomplete="birth" autofocus>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3 mb-3">
                                                                    <div class="form-group" id="residence_end_date_h_container">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="residence_end_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="residence_end_date">تاريخ إنتهاء الهوية هجري </label>
                                                                        <input id="residence_end_date" style="text-align: right" name="residence_end_date" value="{{ optional($user->profile)->residence_end_date ?? old('residence_end_date') }}" placeholder="يوم/شهر/سنة" type="text" class="hijri-date form-control" autofocus>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3 mb-3">
                                                                    <div class="form-group">
                                                                        <span id="record" role="button" type="button" class="item span-20" data-id="residence_end_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                            <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                        </span>
                                                                        <label for="residence_end_date_m">تاريخ إنتهاء الهوية ميلادي </label>
                                                                        <input id="residence_end_date_m" style="text-align: right" type="date" class="form-control" autofocus>
                                                                    </div>
                                                                    <span class="text-danger"  role="alert"> </span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hdie-show display-flex" id="content7-cont">
                                                <div class="col-lg-12   mb-md-0">
                                                    <div class="userFormsContainer mb-3">
                                                            <div class="userFormsDetails topRow">
                                                                <div class="row">
                                                                    <div class="col-9 mb-3">
                                                                        <h5>
                                                                            <i class="fa fa-file"></i>
                                                                            <b>  ملاحظات  </b>
                                                                        </h5>

                                                                    </div>
                                                                    <div class="col-lg-3 text-right">
                                                                        <input type="hidden" name="is_notes" value="1">
                                                                        <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                                                                        @if(auth()->user()->role != 1)
                                                                        <a class="btn mr-2 btn-primary text-white" id="notesEdit" type="button" role="button"><i class="fa fa-edit mr-1 ml-1"></i>تعديل</a>
                                                                        <a class="btn mr-2 btn-dark text-white" id="notesShow" style="display: none" type="button" role="button"><i class="fa fa-eye mr-1 ml-1"></i>عرض</a>
                                                                        <button type="submit" style="display:none" id="notesSubmit" class="btn mr-2 col-lg-5 btn-success text-white rounded">
                                                                            <i class="fas fa-save"></i>

                                                                            <b>حفظ</b>
                                                                        </button>
                                                                            @endif
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <hr>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <div class="form-group">
                                                                            <span id="record" role="button" type="button" class="item span-20" data-id="notes" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                                                                <i class="fa fa-history i-20" style="font-size: medium;"></i>
                                                                            </span>
                                                                            <label for="notes">ملاحظات </label>
                                                                            <textarea id="notes" rows="5" style="text-align: right" placeholder="ملاحظات" name="notes" class="form-control"  autofocus>{!! optional($user->profile)->notes ?? old('notes') !!}</textarea>
                                                                        </div>
                                                                        <span class="text-danger" id="notesError" role="alert"> </span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="myModal">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content text-center">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="mediumModalLabel">السجل</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input id="recordColom" type="hidden">
                                    <div class="table-responsive table--no-card m-b-30">
                                        <table class="table text-center table-borderless table-striped table-earning" style="table-layout: auto;">
                                            <thead>
                                            <tr>
                                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'The Update') }}</th>
                                                <th style="width:30%">{{ MyHelpers::admin_trans(auth()->user()->id,'Update At') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="records" class="text-center">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Close') }}</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('HumanResource.Users.addPhone')
    @include('HumanResource.Users.add-file')

@endsection

@section('scripts')
    @include('HumanResource.Users.addPhoneScript')
    <script src="{{ url("/") }}/js/bootstrap-hijri-datetimepicker.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/balloon/ckeditor.js"></script>
    <script>
        BalloonEditor
            .create( document.querySelector( '#notes' ) )
            .catch( error => {
                console.error( error );
            } );

        BalloonEditor
            .create( document.querySelector( '.custa' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script type="text/javascript">
        function removeRow(elem){
            $(elem).parent().remove();
        }
        function addAnotherRow(){
            var div='<div class="row pl-4 mb-1">\n' +
                '        <select id="custody" name="custody[]" style="height: 45px;" class="form-control mr-2 col-lg-2 custody">\n' +
                '            @if(isset($controls['custody']))\n' +
                '                @foreach($controls['custody'] as $key=>$medical)\n' +
                '                    <option value="{{$medical->id}}"> {{$medical->value}} </option>\n' +
                '                @endforeach\n' +
                '            @endif\n' +
                '        </select>\n' +
                '        <input type="text" name="descriptions[]" style="height: 45px;" class="form-control mr-2 col-lg-8 " id="descriptions">\n' +
                '        <label style="float: left" onclick="addAnotherRow()">\n' +
                '            <button class="btn btn-dark" type="button" style="height: 45px;" role="button">\n' +
                '                <i class="fa fa-plus-square text-white"></i>\n' +
                '            </button>\n' +
                '        </label>\n' +
                '       <label style="float: left;margin-right:3px;margin-left:3px" onclick="removeRow(this)">\n' +
                '            <button class="btn btn-danger" type="button" style="height: 45px;" role="button">\n' +
                '                <i class="fa fa-trash"></i>\n' +
                '            </button>\n' +
                '        </label>\n' +
                '</div>'

            $("#custodyContainer").append(div)
        }
        function convertToHijri(value,attr){
            $.ajax({
                url: "{{ URL('all/convertToHijri') }}",
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "gregorian": value,
                },
                success: function(response) {
                    $("#"+attr).val($.trim(response));
                },
                error: function() {
                    swal({
                        title: "Failed!",
                        text: "Converted Failed! Try Again.",
                        html: true,
                        type: "error",
                    });
                }
            });
        }
        function convertToGregorian(value,attr){
            $.ajax({
                url: "{{ URL('all/convertToGregorian') }}",
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "hijri": value,
                },
                success: function(response) {
                    $("#"+attr+'_m').val($.trim(response));
                },
                error: function() {
                    swal({
                        title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                        text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                        html: true,
                        type: "error",
                    });
                }
            });
        }
        @if($user->profile)
        @if($user->profile->birth_date != null)
        convertToGregorian($('#birth_date').val(),'birth_date');
        @endif
        @if($user->profile->residence_end_date != null)
        convertToGregorian($('#residence_end_date').val(),'residence_end_date');
        @endif
        @if($user->profile->work_end_date != null)
        convertToGregorian($('#work_end_date').val(),'work_end_date');
        @endif
        @if($user->profile->work_date != null)
        convertToGregorian($('#work_date').val(),'work_date');
        @endif
        @if($user->profile->work_date_2 != null)
        convertToGregorian($('#work_date_2').val(),'work_date_2');
        @endif
        @if($user->profile->direct_date != null)
        convertToGregorian($('#direct_date').val(),'direct_date');
        @endif
        @endif
        $('.area').change(function(){
            if($(this).val() != '')
            {
                var select = $(this).attr("id");
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('cities.fetch') }}",
                    method:"POST",
                    data:{select:select, id:value, _token:_token},
                    success:function(result)
                    {
                        $('#city_id').html(result);
                    }

                })
            }
        });

        $('.city').change(function(){
            if($(this).val() != '')
            {
                var select = $(this).attr("id");
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('districts.fetch') }}",
                    method:"POST",
                    data:{select:select, id:value, _token:_token},
                    success:function(result)
                    {
                        $('#district_id').html(result);
                    }

                })
            }
        });
        $(document).ready(function() {
            var dts = $('.data-tables').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}",
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('employee.files.archives',[$user->id,auth()->user()->role]) }}",
                columns: [

                    {
                        data: 'idn',
                        name: 'idn'
                    },
                    {
                        data: 'filename',
                        name: 'filename'
                    },
                    {
                        data: 'deleted_at',
                        name: 'deleted_at'
                    },

                    {
                        data: 'uploaded_at',
                        name: 'uploaded_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                initComplete: function() {



            },
            });
            var dt = $('.data-table').DataTable({
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
                ajax: "{{ route('employee.files',[$user->id,auth()->user()->role]) }}",
                columns: [

                    {
                        data: 'idn',
                        name: 'idn'
                    },
                    {
                        data: 'filename',
                        name: 'filename'
                    },
                    {
                        data: 'uploaded_at',
                        name: 'uploaded_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                initComplete: function() {


                    dt.buttons().container()
                        .appendTo('#dt-btns');

                    $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
                    $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
                    $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
                    $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

                    $('.buttons-excel').addClass('no-transition custom-btn');
                    $('.buttons-print').addClass('no-transition custom-btn');
                    $('.buttons-collection').addClass('no-transition custom-btn');

                    $('.tableAdminOption span').tooltip(top)
                    $('button.dt-button').tooltip(top)

                    /* To Adaptive with New Design */

                },
            });
        });
        function addFileForm() {
            save_method = "add";
            $('#add-file-form input[name=_method]').val('POST');
            $('#add-file-form').modal('show');
            $('#add-file-form form')[0].reset();
            $('#add-file-form .modal-title').text(' ارفاق مرفق');

        }
        function deleteData(id,val=null) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد',
                text: val == null ? "هل انت متأكد من أرشفة الملف ؟" : "هل انت متأكد من مسح الملف نهائيا,لن تسطيع إرجاع الملف ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#10103e',
                confirmButtonColor: '#9a0707',
                buttons: val == null ? ["إلغاء","نعم , أرشفة !"]  : ["إلغاء","نعم , مسح نهائي !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ url('HumanResource/file-delete/') }}" + '/' + id,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function(data) {
                            $('.data-table').DataTable().ajax.reload();
                            $('.data-tables').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: 'تم حذف الملف',
                                type: 'success',
                                timer: '750'
                            })
                        },
                        error: function(e) {
                            swal({
                                title: 'خطأ',
                                text: e.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                } else {

                }

            });
        }

        function deleteRestoreData(id) {
            swal({
                title: 'هل انت متأكد ',
                text: "هل انت متأكد من إستعادة الملف ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء","نعم , إستعادة !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ url('HumanResource/file-restore/') }}" + '/' + id,
                        type: "GET",
                        success: function(data) {
                            $('.data-table').DataTable().ajax.reload();
                            $('.data-tables').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: 'تم إستعادة الملف',
                                type: 'success',
                                timer: '750'
                            })
                        },
                        error: function() {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                } else {

                }

            });
        }
        $(function() {
            $('#form-contact').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#id').val();
                   url = "{{ url('HumanResource/file-upload') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: new FormData($("#form-contact")[0]),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.errors) {
                                if (data.errors.name) {
                                    $('#filename-error').html(data.errors.name[0]);
                                }
                                if (data.errors.file) {
                                    $('#fileError').html(data.errors.file[0]);
                                }
                            }
                            if (data.success) {
                                $('#add-file-form').modal('hide');
                                $('.data-table').DataTable().ajax.reload();
                                swal({
                                    title: 'تم!',
                                    text: data.message,
                                    type: 'success',
                                    timer: '750'
                                })
                            }
                        },
                        error: function(data) {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                    return false;
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#profile-form :input').prop("disabled",true)
        $('#personalShow').click(function () {
            $('#profile-form #content1-cont  :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#personalEdit').css("display","inline-block").prop("disabled",false)
            $('#addMobiles').css("display","none").prop("disabled",true)
            $('#personalSubmit').css("display","none").prop("disabled",false)
        })
        $('#personalEdit').click(function () {
            $('#profile-form  #content1-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#personalShow').css("display","inline-block").prop("disabled",false)
            $('#addMobiles').css("display","inline-block").prop("disabled",false)
            $('#personalSubmit').css("display","inline-block").prop("disabled",false)
        })

        $('#jobShow').click(function () {
            $('#profile-form #content2-cont :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#jobEdit').css("display","inline-block").prop("disabled",false)
            $('#jobSubmit').css("display","none").prop("disabled",false)

        })
        $('#jobEdit').click(function () {
            $('#profile-form #content2-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#jobShow').css("display","inline-block").prop("disabled",false)
            $('#jobSubmit').css("display","inline-block").prop("disabled",false)

        })

        $('#fileShow').click(function () {
            $('#profile-form #content3-cont :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#fileEdit').css("display","inline-block").prop("disabled",false)
            $('#fileSubmit').css("display","none").prop("disabled",false)
        })
        $('#fileEdit').click(function () {
            $('#profile-form #content3-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#fileShow').css("display","inline-block").prop("disabled",false)
            $('#fileSubmit').css("display","inline-block").prop("disabled",false)
        })

        $('#qualificationShow').click(function () {
            $('#profile-form #content4-cont :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#qualificationEdit').css("display","inline-block").prop("disabled",false)
            $('#qualificationSubmit').css("display","none").prop("disabled",false)

        })
        $('#qualificationEdit').click(function () {
            $('#profile-form #content4-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#qualificationShow').css("display","inline-block").prop("disabled",false)
            $('#qualificationSubmit').css("display","inline-block").prop("disabled",false)

        })

        $('#guarantyShow').click(function () {
            $('#profile-form #content5-cont :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#guarantyEdit').css("display","inline-block").prop("disabled",false)
            $('#guarantySubmit').css("display","none").prop("disabled",false)


        })
        $('#guarantyEdit').click(function () {
            $('#profile-form #content5-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#guarantyShow').css("display","inline-block").prop("disabled",false)
            $('#guarantySubmit').css("display","inline-block").prop("disabled",false)


        })

        $('#identityShow').click(function () {
            $('#profile-form #content6-cont :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#identityEdit').css("display","inline-block").prop("disabled",false)
            $('#identitySubmit').css("display","none").prop("disabled",false)

        })
        $('#identityEdit').click(function () {
            $('#profile-form #content6-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#identityShow').css("display","inline-block").prop("disabled",false)
            $('#identitySubmit').css("display","inline-block").prop("disabled",false)

        })

        $('#notesShow').click(function () {
            $('#profile-form #content7-cont :input').prop("disabled",true)
            $(this).css("display","none").prop("disabled",false)
            $('#notesEdit').css("display","inline-block").prop("disabled",false)
            $('#notesSubmit').css("display","none").prop("disabled",false)

        })
        $('#notesEdit').click(function () {
            $('#profile-form #content7-cont :input').prop("disabled",false)
            $(this).css("display","none").prop("disabled",false)
            $('#notesShow').css("display","inline-block").prop("disabled",false)
            $('#notesSubmit').css("display","inline-block").prop("disabled",false)

        })


        $('#contract_file').val(null)
        $('#company_contract').val(null)
        $(function() {
            $("#birth_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                maxDate:'{{now()}}',
                showTodayButton: false,
                showClose: true
            }).on('dp.change', function (arg) {
                convertToGregorian($(this).val(),'birth_date')
            });
            $("#birth_date_m").change(function () {
                convertToHijri($(this).val(),'birth_date')
            })
            $("#residence_end_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: false,
                showClose: true
            }).on('dp.change', function (arg) {
                convertToGregorian($(this).val(),'residence_end_date')
            });

            $("#residence_end_date_m").change(function () {
                convertToHijri($(this).val(),'residence_end_date')
            })

            $("#work_end_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: false,
                showClose: true
            }).on('dp.change', function (arg) {
                convertToGregorian($(this).val(),'work_end_date')
            });

            $("#work_end_date_m").change(function () {
                convertToHijri($(this).val(),'work_end_date')
            })

            $("#work_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                maxDate:'{{now()}}',
                showTodayButton: false,
                showClose: true
            }).on('dp.change', function (arg) {
                convertToGregorian($(this).val(),'work_date')
            });

            $("#work_date_m").change(function () {
                convertToHijri($(this).val(),'work_date')
            })

            $("#work_date_2").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                maxDate:'{{now()}}',
                showTodayButton: false,
                showClose: true
            }).on('dp.change', function (arg) {
                convertToGregorian($(this).val(),'work_date_2')
            });

            $("#work_date_2_m").change(function () {
                convertToHijri($(this).val(),'work_date_2')
            })

            $("#direct_date").hijriDatePicker({
                hijri: true,
                format: "YYYY/MM/DD",
                hijriFormat: 'iYYYY-iMM-iDD',
                showSwitcher: false,
                showTodayButton: false,
                maxDate:'{{now()}}',
                showClose: true
            }).on('dp.change', function (arg) {
                convertToGregorian($(this).val(),'direct_date')
            });

            $("#direct_date_m").change(function () {
                convertToHijri($(this).val(),'direct_date')
            })
        });

        $(document).on('click', '#record', function(e) {

            var coloum = $(this).attr('data-id');
            // var body = document.getElementById("records");
            $('#records').html("<tr><td colspan='3'><i class='fa fa-spinner fa-spin'></i> <b>تحمـــــيـل</b></td></tr>");
            $.get("{{ route('all.reqRecords') }}", {
                coloum: coloum,
                userID: " {{$user->id}}"
            }, function(data) {
                $('#records').empty();
                if (data.status == 1) {
                    $.each(data.histories, function(i, value) {
                        var fn = $("<tr/>").attr('id', value.id);
                        var name = '';
                        if (value.comment == null) {
                            if (value.switch != null)
                                name = value.switch+' / ' + value.name;
                            else
                                name = value.name;

                        } else
                            name = value.name + ' / ' + value.comment;



                        fn.append($("<td/>", {
                            text: name
                        })).append($("<td/>", {
                            text: value.value
                        })).append($("<td/>", {
                            text: value.updateValue_at
                        }));

                        $('#records').append(fn);
                    });



                    // body.append(fn)

                    $('#myModal').modal('show');

                }
                if (data.status == 0) {

                    var fn = $("<tr/>");

                    fn.append($("<td/>", {
                        text: ""
                    })).append($("<td/>", {
                        text: data.message
                    })).append($("<td/>", {
                        text: ""
                    }));



                    $('#records').append(fn);
                    $('#myModal').modal('show');

                }



            }).fail(function(data) {


                document.getElementById('archiveWarning').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}!";
                document.getElementById('archiveWarning').style.display = "block";


            });


        })
    </script>
    <script>
        var nationality = $('#control_nationality_id')
        @if(isset($controls['nationality']))
            @foreach($controls['nationality'] ?? [] as $nationality)
            if(nationality.val() == "{{$nationality->id}}"){
                @if(is_numeric($nationality->parent_id ))
                hideNationalityValues()
                @else
                getNationalityValue()
                @endif
            }
        @endforeach
        @endif


        nationality.change(function(){
            @if(isset($controls['nationality']))
                @foreach($controls['nationality'] ?? [] as $nationality)
            if(nationality.val() == {{$nationality->id}}){
                @if(is_numeric($nationality->parent_id ))
                hideNationalityValues()
                @else
                getNationalityValue()
                @endif
            }
            @endforeach
            @endif
            //   $(this).val($(this).val().split("|")[0])
        });
        $(document).ready(function() {
            $('#control_nationality_id,#control_guaranty_id,#marital_status,#control_company_id,#control_section_id,#control_subsection_id,#gender,#control_identity_id,#control_medical_id,#control_insurances_id').select2();



            var section =$('#control_section_id');
            section.change(function(){
                ajax()
            });
            ajax()
            function ajax() {
                if(section.val() != '')
                {
                    var select = section.attr("id");
                    var value = section.val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('HumanResource.subsections.fetch') }}",
                        method:"POST",
                        data:{select:select, value:value, _token:_token},
                        success:function(result)
                        {
                            $('#control_subsection_id').html(result);
                            $('#control_subsection_id').val("{{optional($user->profile)->control_subsection_id ?? old('control_subsection_id')}}")
                        }

                    })
                }
            }

        });
        function hideNationalityValues(){
            $('#content5').css("display","none")
            $('#content6').css("display","none")
        }

        function getNationalityValue(){
            $('#content5').css("display","block")
            $('#content6').css("display","block")
        }

        $(function() {
            $('#profile-form').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {

                    $('#name-error').html("");
                    $('#email-error').html("");
                    $('#mobile-error').html("");
                    $('#family_count-error').html("");
                    $('#control_nationality_id-error').html("");
                    $('#gender-error').html("");
                    $('#marital_status-error').html("");
                    $('#qualification-error').html("");
                    $('#birth_date-error').html("");

                    $.ajax({
                        url: "{{route('HumanResource.profile.personal.post')}}",
                        type: "POST",
                        data: new FormData($("#profile-form")[0]),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.errors) {
                                swal({
                                    title: 'خطأ',
                                    text: data.message,
                                    type: 'error',
                                    timer: '750'
                                })
                                if (data.errors.name) {
                                    $('#name-error').html(data.errors.name[0]);
                                }
                                if (data.errors.email) {
                                    $('#email-error').html(data.errors.email[0]);
                                }
                                if (data.errors.mobile) {
                                    $('#mobile-error').html(data.errors.mobile[0]);
                                }
                                if (data.errors.family_count) {
                                    $('#family_count-error').html(data.errors.family_count[0]);
                                }
                                if (data.errors.family_count) {
                                    $('#family_count-error').html(data.errors.family_count[0]);
                                }
                                if (data.errors.control_nationality_id) {
                                    $('#control_nationality_id-error').html(data.errors.control_nationality_id[0]);
                                }
                                if (data.errors.gender) {
                                    $('#gender-error').html(data.errors.gender[0]);
                                }
                                if (data.errors.marital_status) {
                                    $('#marital_status-error').html(data.errors.marital_status[0]);
                                }
                                if (data.errors.qualification) {
                                    $('#qualification-error').html(data.errors.qualification[0]);
                                }
                                if (data.errors.birth_date) {
                                    $('#birth_date-error').html(data.errors.birth_date[0]);
                                }

                            }
                            if (data.success) {
                                $('#add-form').modal('hide');
                                if(data.created == true){
                                    $('#menu1').css("display","block")
                                }
                                swal({
                                    title: 'تم!',
                                    text: data.message,
                                    type: 'success',
                                    timer: '750'
                                })
                                $('.district_show').html("[ "+data.district+" ]")
                                $('.city_show').html("[ "+data.city+" ]")
                                $('.area_show').html("[ "+data.area+" ]")
                                if(data.gender != null){
                                    $('#genderShow').prop("src",'/'+data.gender+'.jpg')
                                    $('#SSNHead').html(data.employee.job_number)
                                    $('#nameArHead').html(data.employee.name)
                                    $('#nameEnHead').html(data.employee.name_en)
                                    $('#nationalityHead').html(data.employee.nationality.value)
                                }


                            }
                        },
                        error: function(data) {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                    return false;
                }
            });

        });
    </script>

@endsection

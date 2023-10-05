@extends('layouts.content')

@section('title')
طلبات التوظيف
@endsection


@section('css_style')

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

    table {
        text-align: center;
    }
</style>
{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection

@section('customer')
<div class="myOrders">
    <div class="container">

        <section class="goal mt-0  ">
            <div class="wizard-form">

		        	<div id="form-total">

		        		<!-- SECTION 1 -->
                        <div class="addBtn col-md-5 mt-lg-0 mt-3" style="margin-right: 80%;">
                            <a href="{{ route('HumanResource.job_applications.index')}}">
                                <button class="mr-2 Cloud">
                                    <i class="fas fa-solid fa-briefcase"></i>
                                    جميع الطلبات
                                </button>
                            </a>
                            <br>
                        </div>

                        <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&7 -->
                        <div class="addUser my-4">
                            <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                                <h3><i class="fas fa-user"></i> البيانات الشخصية </h3>
                            </div>
                        </div>

                        <!--agent & tools -->
                        <div class="tableBar">
                                <div class="topRow">
                                    <div class="row align-items-center text-center text-md-left">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">الاسم الاول</label>
                                                <input  class="form-control" value="{{$job->first_name}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">اسم العائله </label>
                                                <input  class="form-control" value="{{$job->sur_name}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> الجنسيه </label>
                                                <input  class="form-control" value="{{($job->nationality_id!=NULL)?($job->nationality->title ?? ''):$job->other_nationality}}" style="<?php if($job->nationality_id==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> تاريخ الميلاد </label>
                                                <input  class="form-control" value="{{$job->date_of_birth}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">  المدينه </label>
                                                <input  class="form-control" value="{{($job->city_id!=NULL)?($job->city->value):$job->other_city}}" style="<?php if($job->city_id==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">  رقم الجوال  </label>
                                                <input  class="form-control" value="{{$job->phone}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">  البريد الالكترونى </label>
                                                <input  class="form-control" value="{{$job->email}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> الراتب المتوقع بالريال </label>
                                                <input  class="form-control" value="{{$job->salary}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> الجنس</label>
                                                <input  class="form-control" value="{{($job->gender=='male')?'ذكر':'انثى'}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <!-- ===========================المؤهلات الاكاديمية ========================= -->
                        <div class="addUser my-4">
                            <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                                <h3><i class="fas fa-solid fa-briefcase"></i> المؤهلات الاكاديمية </h3>
                            </div>
                        </div>

                        <!--agent & tools -->
                        <div class="tableBar">
                                <div class="topRow">
                                    <div class="row align-items-center text-center text-md-left">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> يرغب فى تدريب جامعى</label>
                                                <input class="form-control" value="{{($job->need_traning=='0')?'لا':'نعم'}}" style="<?php if($job->need_traning=='1'){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> تاريخ التخرج </label>
                                                <input  class="form-control" value="{{($job->graduation_date==NULL)?'لم يتم تحديده':$job->graduation_date}}" style="<?php if($job->graduation_date==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> الجامعه </label>
                                                <input  class="form-control" value="{{($job->university_id!=NULL)?($job->university->title ?? ''):$job->other_university}}" style="<?php if($job->university_id==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">  المؤهل </label>
                                                <input  class="form-control" value="{{$job->level}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> تخصص المؤهل </label>
                                                <input  class="form-control" value="{{($job->level_specialization==NULL)?'لم يتم تحديده':$job->level_specialization}}"  style="<?php if($job->level_specialization==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> التقدير </label>
                                                <input  class="form-control"  value="{{($job->grade==NULL)?'لم يتم تحديده':$job->grade}}"  style="<?php if($job->grade==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> التخصص المرغوب</label>
                                                <input  class="form-control" value="{{($job->specialization==NULL)?'لم يتم تحديده':$job->specialization}}"  style="<?php if($job->specialization==NULL){echo 'color:red';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">  المُسمى الوظيفى </label>
                                                <input  class="form-control" value="{{($job->job_title->title ?? '')}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">   طبيعه الدوام </label>
                                                <input  class="form-control" value="<?php if($job->duration=='online'){echo'عن بعد ';}elseif($job->duration=='full_time'){echo'دوام كلى ';}else{echo'دوام جزئى ';}?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer">  متى تستطيع ان تبدا </label>
                                                <input  class="form-control" value="{{$job->possible_start_date}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label for="Customer"> سنوات الخبره</label>
                                                <input  class="form-control" value="{{$job->experance_years}}"  disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <!-- ================الدورات================ -->
                        @if(sizeOf($courses)>0)
                            <div class="addUser my-4">
                                    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                                    <h3><i class="fas fa-solid fa-briefcase"></i>  الدورات </h3>
                                    </div>
                                </div>

                            <!--agent & tools -->
                            <div class="tableBar">
                                    <div class="topRow">
                                        <div class="row align-items-center text-center text-md-left">
                                        @foreach($courses as $course)
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group">
                                                    <label for="Customer">  اسم الدوره </label>
                                                    <input  class="form-control" value="{{$course->title ?? ''}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group">
                                                    <label for="Customer">   تاريخ البدايه </label>
                                                    <input  class="form-control" value="{{$course->start_date}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group">
                                                    <label for="Customer">  تاريخ النهايه  </label>
                                                    <input  class="form-control" value="{{$course->end_date}}" disabled>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                            </div>
                        @endif

                        <!-- ================الخبرات================ -->
                        @if(sizeOf($experances)>0)
                            <div class="addUser my-4">
                                    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                                    <h3><i class="fas fa-solid fa-briefcase"></i>  الخبرات </h3>
                                    </div>
                                </div>

                            <!--agent & tools -->
                            <div class="tableBar">
                                    <div class="topRow">
                                        <div class="row align-items-center text-center text-md-left">
                                        @foreach($experances as $ex)
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group">
                                                    <label for="Customer">  اسم الشركه </label>
                                                    <input  class="form-control" value="{{$ex->title ?? ''}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group">
                                                    <label for="Customer">   تاريخ البدايه </label>
                                                    <input  class="form-control" value="{{$ex->start_date}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group">
                                                    <label for="Customer">  تاريخ النهايه  </label>
                                                    <input  class="form-control" value="{{$ex->end_date}}" disabled>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                            </div>
                        @endif

                        <!-- ===========================كلمنا عن نفسك ========================= -->
                        <div class="tableBar">
                            <div class="topRow">
                                <div class="row align-items-center text-center text-md-left">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="Customer">   كلمنا عن نفسك </label>
                                            <textarea class="form-control" disabled>{!!$job->notes!!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&7 -->
                        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                           <br> <h3 style="color:red"><i class="fas fa-sticky-note"></i>  ملاحظات ال HR </h3><br>
                         </div>
                         
                         <form action="{{route('HumanResource.job_applications.update',$job->id)}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group col-6 col-md-12">
                                <label for="type" class="control-label mb-1">التصنيفات</label>
                                <select class="form-control py-2" style="height: 50px" name="type_id" id="type_id">
                                    <option value="0">اختر التصنيف</option>
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}"<?php if($job->type_id==$type->id){echo'selected';}?>>{{$type->title}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-6 col-md-12">
                                <label for="type" class="control-label mb-1">ملاحظات</label>
                                <textarea  class="form-control py-2" name="hr_notes">{{$job->hr_notes}}</textarea>
                                <input type="hidden" name="job_id" value="{{$job->id}}"/>
                            </div>
                            
                            <div class="col-lg-4 mb-3" style="margin-right: 30%;">
                                <button type="submit" class="form-control btn-success">حفظ</button>
                            </div>
                            
                         </form>
                         
		        	</div>

			</div>
        </section>
    </div>
</div>
@endsection
@section('scripts')

@endsection

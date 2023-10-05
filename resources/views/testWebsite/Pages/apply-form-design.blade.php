@extends('testWebsite.layouts.master')

@section('title') طلبات التوظيف @endsection

@section('style')
<link rel="stylesheet" href="{{ asset('newWebsiteStyle/css/style-wizared.css')}}"/>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<style>
    span.select2-selection.select2-selection--single {
        height: 2.4rem;
        background-color: #f3f3f3;
        border: 0;
    }
    .form-register .steps {
        margin-bottom: 33px;
        justify-content: center;
        display: flex;
    }
    .last {
        color: unset;
        padding: unset;
    }
    #form-total li a span{
        text-align: left;
    }
    .first-calc{
        padding: 0;
    }
    #form-total input, #form-total textarea, #form-total select{
        background-color: #f3f3f3;
        border: 1px solid #f3f3f3;
    }
    #form-total input.btn-primary{
        background-color: #2b78b0;
        border: 1px solid #2b78b0;
    }
    span.step-text {
        font-size: 12px !important;
        color: #2b78b0 !important;
        font-weight: bold !important;
    }
    #form-total li a span {
        text-align: center;
        margin: 0 4rem;
    }
    #form-total li a span.step-icon {
        /* text-align: center; */
        margin: auto;
    }
    .last .step-icon::before{
        display: none;

    }
    .last .step-icon::after{
        /* background: #24c1e8; */
        background-color: #fff !important;
        background-image: radial-gradient(#000 10%, transparent 11%), radial-gradient(#24c1e8 10%, transparent 11%) !important;
        background-size: 19px 60px !important;
        background-position: 0 0, 12px 32px !important;
        background-repeat: repeat !important;

    }
    .step-icon::before{
        /* background: #24c1e8; */
        background-color: #fff !important;
        background-image: radial-gradient(#000 10%, transparent 11%), radial-gradient(#24c1e8 10%, transparent 11%) !important;
        background-size: 19px 60px !important;
        background-position: 0 0, 12px 32px !important;
        background-repeat: repeat !important;

    }
</style>

@endsection

@section('pageMenu')
    @include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">
        {{-- <div class="head-div text-center">
            <h1>طلب توظيف</h1>
        </div> --}}
        <section class="goal mt-0  ">
            <div class="wizard-form">
				<div class="wizard-header">
					<h3 class="heading">طلب وظيفي</h3>
					<p>يسعدنا انضمامك لفريقنا</p>
				</div>

                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <center>{{ session()->get('message') }}</center>
                    </div>
                    @endif
                    @if(session()->has('message2'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <center>{{ session()->get('message2') }}</center>
                    </div>
                    @endif


                     @if ($errors->any())
                        <div class="alert alert-danger">
                            <center>من فضلك قم بمراجعه جميع المدخلات وتاكد من ادخالها بشكل صحيح'</center>
                        </div>
                    @endif

		        <form class="form-register" method="post" action="{{route('apply_job')}}">
                    @csrf
                    @method('post')
		        	<div id="form-total">
		        		<!-- SECTION 1 -->
			            <h2>
			            	<span class="step-icon"><i class="">1</i></span>
			            	<span class="step-text">البيانات الشخصية</span>
			            </h2>
			            <section>
			                <div class="inner">
			                	<h3>البيانات الشخصية:</h3>
			                	<div class="row first-calc">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="first_name">الاسم الاول<small style="color:red;">*</small></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"  placeholder="الاسم الاول" value="{{old('first_name')}}" onkeyup="javascript:check_name('first_name')" required>
                                        <span style="color:red;display:none;" id="error_first_name"></span>
                                        @error('first_name')
                                          <span class="text-danger" style="color:red;" role="alert">{{ $message }} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="sur_name">اسم العائله<small style="color:red;">*</small></label>
                                        <input type="text" name="sur_name" class="form-control" id="sur_name" placeholder="اسم العائله" value="{{old('sur_name')}}"  onkeyup="javascript:check_name('sur_name')" required>

                                        <span style="color:red;display:none;" id="error_sur_name"></span>
                                        @error('sur_name')
                                          <span class="text-danger" style="color:red;"  role="alert"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="date_of_birth">تاريخ الميلاد<small style="color:red;">*</small></label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Birthday" value="{{old('date_of_birth')}}" required>
                                        @error('date_of_birth')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="phone">رقم الجوال<small style="color:red;">*</small></label>
                                        <input type="number" name="phone" id="phone" class="form-control"  placeholder="966561111111" value="{{old('phone')}}" onkeyup="javascript:checkphone();"  required >
                                        <span style="color:red;display:none;" id="error_phone">تاكد من ادخال الجوال بشكل صحيح</span>
                                        @error('phone')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="email">البريد الالكترونى<small style="color:red;">*</small></label>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="example@example.com" value="{{old('email')}}" onkeyup="javascript:checkmail();" required>
                                        <span style="color:red;display:none;" id="error_mail">تاكد من ادخال البريد الالكترونى بشكل صحيح</span>

                                        @error('email')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="salary">الراتب المتوقع بالريال<small style="color:red;">*</small></label>
                                        <input type="number" name="salary" class="form-control" id="salary" value="{{old('salary')}}" min="0" onkeyup="javascript:check_salary()" required>
                                        <span style="color:red;display:none;" id="error_salary"></span>

                                        @error('salary')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="gender">الجنس<small style="color:red;">*</small></label>
                                        <select class="form-control select2-request"  id="gender" name="gender" required>
                                            <option value="">--</option>
                                            <option value="male" @if(old('gender') == 'male') selected="selected" @endif>ذكر</option>
                                            <option value="female" @if(old('gender') == 'female') selected="selected" @endif>انثى</option>
                                        </select>
                                        @error('gender')
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="nationality_id">الجنسيه<small style="color:red;">*</small></label>
                                        <select class="form-control select2-request"  id="nationality_id" name="nationality_id" onchange="javascript:check_other_option(this.value,'nationality');"  required>
                                            <option value="">--</option>
                                            @foreach($nationality as $n)
                                               <option value="{{$n->id}}" @if(old('nationality_id') == $n->id) selected="selected" @endif>{{$n->title}}</option>
                                            @endforeach
                                            <option value="other" @if(old('nationality_id') == 'other') selected="selected" @endif>اخرى</option>
                                        </select>
                                        @error('nationality_id')
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="nationality" style="display:@if(old('nationality_id') == 'other')block;@else none;@endif">
                                        <label for="other_nationality">اسم الجنسيه<small style="color:red;">*</small></label>
                                        <input type="text" name="other_nationality" class="form-control" id="other_nationality" placeholder="ادخل اسم الجنسيه الاخرى" value="{{old('other_nationality')}}" onkeyup="javascript:check_name('other_nationality')">
                                        <span style="color:red;display:none;" id="error_other_nationality"></span>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="city_id">المدينه<small style="color:red;">*</small></label>
                                        <select class="form-control select2-request" id="city_id" name="city_id" onchange="javascript:check_other_option(this.value,'city');" required>
                                            <option value="">--</option>
                                            @foreach($cities as $c)
                                               <option value="{{$c->id}}" @if(old('city_id') == $c->id) selected="selected" @endif>{{$c->value}}</option>
                                            @endforeach
                                            <option value="other" @if(old('city_id') == 'other') selected="selected" @endif>اخرى</option>
                                        </select>
                                        @error('city_id')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="city" style="display:@if(old('city_id') == 'other')block;@else none;@endif">
                                        <label for="other_city">اسم المدينه<small style="color:red;">*</small></label>
                                        <input type="text" name="other_city" class="form-control" id="other_city" placeholder="ادخل اسم المدينه الاخرى" value="{{old('other_city')}}" onkeyup="javascript:check_name('other_city')">
                                        <span style="color:red;display:none;" id="error_other_city"></span>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" style=" display: flex; align-items: flex-end; ">
                                        <input type="checkbox" name="need_traning" id="need_traning" class="form-control" style="width: 15px; margin-top: 20px;" >
                                        <label for="need_traning" style="padding-right: 5px;"> ارغب فى تدريب جامعى</label>
                                    </div>

                                </div>
							</div>
			            </section>
						<!-- SECTION 2 -->
			            <h2>
			            	<span class="step-icon"><i class="">2</i></span>
			            	<span class="step-text">المؤهلات الاكاديمية</span>
			            </h2>
			            <section>
			                <div class="inner">
			                	<h3>المؤهلات الاكاديمية</h3>
			                	<div class="row first-calc">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                       <label for="graduation_date">تاريخ التخرج</label>
                                       <input type="date" class="form-control" id="graduation_date" name="graduation_date" value="{{old('graduation_date')}}" >
                                       @error('graduation_date')
                                       <span class="text-danger" style="color:red;" role="alert">{{ $message }} </span>
                                        @enderror
                                   </div>

                                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                       <label for="university_id">الجامعه<small style="color:red;">*</small></label><br>
                                       <select class="form-control select2-request" style="width:100%"  id="university_id" name="university_id" onchange="javascript:check_other_option(this.value,'university');"  required>
                                           <option value="">--</option>
                                           @foreach($university as $u)
                                              <option value="{{$u->id}}" @if(old('university_id') == $u->id) selected="selected" @endif>{{$u->title}}</option>
                                           @endforeach
                                           <option value="other" @if(old('university_id') == 'other') selected="selected" @endif>اخرى</option>
                                       </select>
                                       @error('university_id')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="university" style="display:@if(old('university_id') == 'other')block;@else none;@endif">
                                        <label for="other_university">اسم الجامعه<small style="color:red;">*</small></label>
                                        <input type="text" name="other_university" class="form-control" id="other_university" placeholder="ادخل اسم الجامعه الاخرى" value="{{old('other_university')}}" onkeyup="javascript:check_name('other_university')">
                                        <span style="color:red;display:none;" id="error_other_university"></span>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="level">المؤهل<small style="color:red;">*</small></label>
                                        <select class="form-control select2-request"  name="level" onchange="javascript:check_other_option(this.value,'level');"  required>
                                            <option value="">--</option>
                                            <option value="دكتوراه"  @if(old('level') == 'دكتوراه') selected="selected" @endif>دكتوراه </option>
                                            <option value="ماجستير"   @if(old('level') == 'ماجستير') selected="selected" @endif>ماجستير </option>
                                            <option value="بكالوريوس" @if(old('level') == 'بكالوريوس') selected="selected" @endif>بكالوريوس  </option>
                                            <option value="ثانوي" @if(old('level') == 'ثانوي') selected="selected" @endif>ثانوي  </option>
                                            <option value="other" @if(old('level') == 'other') selected="selected" @endif>اخرى</option>
                                        </select>
                                        @error('level')
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="level" style="display:@if(old('level') == 'other')block;@else none;@endif">
                                        <label for="other_level">اسم المؤهل<small style="color:red;">*</small></label>
                                        <input type="text" name="other_level" class="form-control" id="other_level" placeholder="ادخل اسم المؤهل " value="{{old('other_level')}}" onkeyup="javascript:check_name('other_level')">
                                        <span style="color:red;display:none;" id="error_other_level"></span>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
                                       <label for="level_specialization">تخصص المؤهل</label>
                                       <input type="text" name="level_specialization" class="form-control" id="level_specialization" placeholder="اكتب تخصص المؤهل" value="{{old('level_specialization')}}" onkeyup="javascript:check_name('level_specialization')" >
                                       <span style="color:red;display:none;" id="error_level_specialization"></span>
                                       @error('level_specialization')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                       <label for="grade">التقدير</label>
                                       <select class="form-control" name="grade" onchange="javascript:check_other_option(this.value,'grade');" >
                                            <option value=" ">--</option>
                                            <option value="مقبول" @if(old('grade') == 'مقبول') selected="selected" @endif >مقبول</option>
                                            <option value="جيد" @if(old('grade') == 'جيد') selected="selected" @endif >جيد</option>
                                            <option value="جيد جدا" @if(old('grade') == 'جيد جدا') selected="selected" @endif>جيد جدا</option>
                                            <option value="امتياز" @if(old('grade') == 'امتياز') selected="selected" @endif>امتياز</option>
                                            <option value="other" @if(old('grade') == 'other') selected="selected" @endif>اخرى</option>
                                        </select>
                                       @error('grade')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="grade" style="display:@if(old('grade') == 'other')block;@else none;@endif">
                                        <label for="other_grade">التقدير<small style="color:red;">*</small></label>
                                        <input type="text" name="other_grade" class="form-control" id="other_grade" placeholder="ادخل التقدير " value="{{old('other_grade')}}" onkeyup="javascript:check_name('other_grade')">
                                        <span style="color:red;display:none;" id="error_other_grade"></span>
                                    </div>

                                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                       <label for="specialization">التخصص المرغوب</label>
                                       <input type="text" name="specialization" class="form-control" id="specialization" placeholder="اكتب التخصص المرغوب" value="{{old('specialization')}}" onkeyup="javascript:check_name('specialization')">
                                       <span style="color:red;display:none;" id="error_specialization"></span>
                                       @error('specialization')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                       <label for="job_id">المسمى الوظيفى<small style="color:red;">*</small></label>
                                       <select class="form-control select2-request" style="width:100%;"  id="job_id" name="job_id" required>
                                          <option value="">--</option>
                                           @foreach($jobs as $j)
                                              <option value="{{$j->id}}" @if(old('job_id') == $j->id) selected="selected" @endif>{{$j->title}}</option>
                                           @endforeach
                                       </select>
                                       @error('job_id')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="duration">طبيعه الدوام<small style="color:red;">*</small></label>
                                        <select class="form-control select2-request"  name="duration" required>
                                            <option value="">--</option>
                                            <option value="online"  @if(old('duration') == 'online') selected="selected" @endif>عن بعد </option>
                                            <option value="full_time"   @if(old('duration') == 'full_time') selected="selected" @endif>دوام كلى  </option>
                                            <option value="part_time" @if(old('duration') == 'part_time') selected="selected" @endif>دوام جزئى  </option>
                                        </select>
                                        @error('duration')
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
                                       <label for="possible_start_date">متى تستطيع ان تبدا<small style="color:red;">*</small></label>
                                       <input type="date" class="form-control" id="possible_start_date" name="possible_start_date" value="{{old('possible_start_date')}}" required>
                                       @error('possible_start_date')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                        <label for="experance_years">سنوات الخبره <small style="color:red;">*</small></label>
                                        <select class="form-control" name="experance_years" onchange="javascript:check_other_option(this.value,'experance_years');"  required>
                                            @for($year=0;$year<=10;$year++)
                                                <option value="{{$year}}" @if(old('experance_years') == $year) selected="selected" @endif >{{$year}}</option>
                                            @endfor
                                            <option value="other" @if(old('experance_years') == 'other') selected="selected" @endif>اخرى</option>
                                        </select>
                                        @error('experance_years')
                                        <span class="text-danger" style="color:red;" role="alert">{{$message}} </span>
                                        @enderror
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" id="experance_years" style="display:@if(old('experance_years') == 'other')block;@else none;@endif">
                                        <label for="other_experance_years">سنوات الخبره<small style="color:red;">*</small></label>
                                        <input type="text" name="other_experance_years" class="form-control" id="other_experance_years" placeholder="ادخل سنوات الخبره " value="{{old('other_experance_years')}}">
                                        <span style="color:red;display:none;" id="error_other_experance_years"></span>

                                    </div>

                               </div>
                               <!-- ================الدورات================ -->
                               <br>
                               <h3>الدورات</h3>
                               <div class="row first-calc" id="courses">

                                   <div id="courses" class="col-md-10 row">
                                       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                           <label for="Name"
                                               class="mr-sm-2">اسم الدوره
                                               :</label>
                                           <input class="form-control" type="text" name="courses[]" value="{{old('courses.0')}}" required/>
                                            @if($errors->first('courses.*'))
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $errors->first('courses.*') }} </span>
                                            @endif
                                       </div>
                                       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                           <label for="Name"
                                               class="mr-sm-2">تاريخ البدء
                                               :</label>
                                           <input class="form-control" type="date" name="course_start_date[]" value="{{old('course_start_date.0')}}" required/>
                                            @if($errors->first('course_start_date.*'))
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $errors->first('course_start_date.*') }} </span>
                                            @endif
                                        </div>
                                       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                           <label for="Name"
                                               class="mr-sm-2">تاريخ النهايه
                                               :</label>
                                           <input class="form-control" type="date" name="course_end_date[]" value="{{old('course_end_date.0')}}"required/>
                                            @if($errors->first('course_end_date.*'))
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $errors->first('course_end_date.*') }} </span>
                                            @endif
                                    </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="Name"
                                               class="mr-sm-2">اضافة جديد</label>
                                         <input type="button" class="btn btn-primary" style="width:90%;" onclick="javascript:add_course();" value="اضافه"/>
                                     </div>
                               </div>
                               <!-- ================================ -->

                               <!-- ================الخبرات================ -->
                               <br>
                               <h3>الخبرات</h3>
                               <div class="row first-calc" id="experances">

                                   <div id="experances" class="col-md-10 row">
                                       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                           <label for="Name"
                                               class="mr-sm-2">اسم الشركه
                                               :</label>
                                           <input class="form-control" type="text" name="experances[]" value="{{old('experances.0')}}" required/>
                                            @if($errors->first('experances.*'))
                                                <span class="text-danger" style="color:red;" role="alert"> {{ $errors->first('experances.*') }} </span>
                                            @endif
                                        </div>

                                       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                           <label for="Name"
                                               class="mr-sm-2">تاريخ البدء
                                               :</label>
                                           <input class="form-control" type="date" name="experance_start_date[]" value="{{old('experance_start_date.0')}}" required/>
                                           @if($errors->first('experance_start_date.*'))
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $errors->first('experance_start_date.*') }} </span>
                                            @endif
                                        </div>
                                       <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                           <label for="Name"
                                               class="mr-sm-2">تاريخ النهايه
                                               :</label>
                                           <input class="form-control" type="date" name="experance_end_date[]" value="{{old('experance_end_date.0')}}" required/>
                                           @if($errors->first('experance_end_date.*'))
                                            <span class="text-danger" style="color:red;" role="alert"> {{ $errors->first('experance_end_date.*') }} </span>
                                            @endif
                                        </div>
                                   </div>
                                   <div class="col-md-2">
                                        <label for="New"
                                            class="mr-sm-2">اضافة جديد:</label>
                                        <input type="button" class="btn btn-primary" style="width:90%;" onclick="javascript:add_experance();" value="اضافه"/>

                                   </div>
                               </div>
                            <br>
                            <hr/>
                            <br>
                               <div class="col-12">
                                <label for="notes">كلمنا عن نفسك</label>
                                <textarea name="notes" class="form-control" id="notes" placeholder="كلمنا عن نفسك">{!!old('notes')!!}</textarea>
                                @error('notes')
                                    <span class="text-danger" style="color:red;" role="alert" >{{$message}} </span>
                                @enderror
                                </div>
                               <!-- ================================ -->
							</div>
			            </section>

		        	</div>
		        </form>
			</div>
        </section>
    </div>
</div>
@endsection
@section('scripts')

<script src="/newWebsiteStyle/js/jquery.steps.js"></script>
<script src="/newWebsiteStyle/js/jquery-ui.min.js"></script>
<script src="/newWebsiteStyle/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script>

    $(document).ready(function() {
        $('#nationality_id').select2({
            dir:'rtl'
        });
        $('#city_id').select2({
            dir:'rtl'
        });
        $('#university_id').select2({
            dir:'rtl'
        });
        $('#job_id').select2({
            dir:'rtl'
        });
    });

    $(document).on('click', ".actions a[href$='#finish']", function(){
        $(this).closest('form').submit();
    })
    function add_course(){
        let extra='<div id="courses" class="col-md-10 row"><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label for="Name" class="mr-sm-2">اسم الدوره:</label><input class="form-control" type="text" name="courses[]" required/></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-3"><label for="Name" class="mr-sm-2">تاريخ البدء:</label><input class="form-control" type="date" name="course_start_date[]" required/></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-3"><label for="Name" class="mr-sm-2">تاريخ النهايه :</label><input class="form-control" type="date" name="course_end_date[]" required/></div></div>';
        $('#courses').append(extra);
    }

    function add_experance(){
        let extra='<div id="experances" class="col-md-10 row"><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label for="Name" class="mr-sm-2">اسم الشركه:</label><input class="form-control" type="text" name="experances[]" required/></div> <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3"><label for="Name" class="mr-sm-2">تاريخ البدء:</label><input class="form-control" type="date" name="experance_start_date[]" required/></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-3"><label for="Name" class="mr-sm-2">تاريخ النهايه:</label><input class="form-control" type="date" name="experance_end_date[]" required/></div></div>';
        $('#experances').append(extra);
    }


    //================================================================================
   function checkphone(){
    var phone= document.getElementById('phone').value;
    var filter_phone=/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/;
        if (!filter_phone.test(phone)) {
            document.getElementById('error_phone').style.display = 'block';
            document.getElementById('phone').style = 'border: 4px solid rgb(227 40 66)';
        }else{
            document.getElementById('error_phone').style.display = 'none';
            document.getElementById('phone').style = '';
        }
   }
   //================================================================================
   function checkmail(){
        var email= document.getElementById('email').value;
        var filter_mail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!filter_mail.test(email)) {
                document.getElementById('error_mail').style.display = 'block';
                document.getElementById('email').style = 'border: 4px solid rgb(227 40 66)';
         }else{
            document.getElementById('error_mail').style.display = 'none';
            document.getElementById('email').style = '';
         }
    }
    //================================================================================
    function check_name(id){
        var name= document.getElementById(id).value;
        var name_vaild=/[\u0600-\u06FF]/;
        if(name.length <2){
            document.getElementById('error_'+id).innerHTML = 'الحقل يجب الا يقل عن حرفين ';
            document.getElementById('error_'+id).style.display = 'block';
            document.getElementById(id).style = 'border: 4px solid rgb(227 40 66)';
        }else if(!name_vaild.test(name)){
            document.getElementById('error_'+id).innerHTML = 'الحقل يجب ان يحتوى ع حروف عربيه';
            document.getElementById('error_'+id).style.display = 'block';
            document.getElementById(id).style = 'border: 4px solid rgb(227 40 66)';
        }else{
            document.getElementById('error_'+id).style.display = 'none';
            document.getElementById(id).style = '';
        }
    }
    //================================================================================
    function check_salary(){
        var salary= document.getElementById('salary').value;
        var salary_vaild=/^\d*$/;
        if(!salary_vaild.test(salary)){
            document.getElementById('error_salary').innerHTML = 'الراتب يجب ان يكون رقم صحيح';
            document.getElementById('error_salary').style.display = 'block';
            document.getElementById('salary').style = 'border: 4px solid rgb(227 40 66)';
        }else{
            document.getElementById('error_salary').style.display = 'none';
            document.getElementById('salary').style = '';
        }
    }
     //================================================================================
     function check_other_option(id,type){
        if(id=='other'){
            document.getElementById(type).style.display = 'block';
            document.getElementById('other_'+type).setAttribute('required','');
        }else{
            document.getElementById(type).style.display = 'none';
            document.getElementById('other_'+type).removeAttribute('required');
        }
    }
</script>
@endsection

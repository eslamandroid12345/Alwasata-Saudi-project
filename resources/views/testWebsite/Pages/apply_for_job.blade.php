@extends('testWebsite.layouts.master')

@section('title') طلب توظيف @endsection

@section('style')

<!-- Google -->
<!-- Global site tag (gtag.js) - Google Ads: 831160911 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-831160911"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'AW-831160911');
</script>
<script>
    function gtag_report_conversion(url) {
        var callback = function() {
            if (typeof(url) != 'undefined') {
                window.location = url;
            }
        };
        gtag('event', 'conversion', {
            'send_to': 'AW-831160911/gokUCPj0--0BEM-EqowD',
            'event_callback': callback
        });
        return false;
    }
</script>
<!-- Google -->

<!-- Twitter universal website tag code -->
<script>
    ! function(e, t, n, s, u, a) {
        e.twq || (s = e.twq = function() {
                s.exe ? s.exe.apply(s, arguments) : s.queue.push(arguments);
            }, s.version = '1.1', s.queue = [], u = t.createElement(n), u.async = !0, u.src = '//static.ads-twitter.com/uwt.js',
            a = t.getElementsByTagName(n)[0], a.parentNode.insertBefore(u, a))
    }(window, document, 'script');
    // Insert Twitter Pixel ID and Standard Event data below
    twq('init', 'o5ari');
    twq('track', 'PageView');
</script>
<!-- End Twitter universal website tag code -->

@endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<section class="calc">
    <div class="container">
        <div class="head-div text-center">
            <h1>طلب توظيف</h1>
        </div>
        <p style="text-align: center;">لتقديم طلب توظيف برجاء تعبئة البيانات التالية ليقوم أحد مستشارينا بالتواصل معك والعمل على طلبك في أسرع وقت.</p>
        <div class="calc-form funding_request_wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="calc-cont">
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <center>{{ session()->get('message') }}</center>
                    </div>      
                    @endif
                        <form method="post" action="{{route('apply_job')}}">
                            <p class="message-box alert"></p>
                            @csrf
                            @method('post')
                            <label>البيانات الشخصيه</label>
                            <div class="row first-calc">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="first_name">الاسم الاول<small id="first_name"></small><small style="color:red;">*</small></label>
                                    <input type="text" name="first_name" class="form-control" id="inputfirst_nameError" placeholder="الاسم الاول">
                                    <span class="text-danger" style="color:red;" id="first_nameError" role="alert"> </span>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="sur_name">اسم العائله<small id="sur_name"></small><small style="color:red;">*</small></label>
                                    <input type="text" name="sur_name" class="form-control" id="inputsur_nameError" placeholder="اسم العائله">
                                    <span class="text-danger" style="color:red;" id="sur_nameError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="nationality_id">الجنسيه</label>
                                    <select class="form-control @error('nationality_id') is-invalid @enderror"  id="inputnationality_idError" name="nationality_id">
                                        @foreach($nationality as $n)
                                           <option value="{{$n->id}}">{{$n->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" style="color:red;" id="nationality_idError" role="alert"> </span>
                                </div>
                                
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"  id="date_of_birth">
                                    <label for="date_of_birth">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" id="inputdate_of_birthError" name="date_of_birth" placeholder="Birthday">
                                    <span class="text-danger" style="color:red;" id="date_of_birthError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="ssd">رقم الهويه</label>
                                    <input type="text" name="ssd" class="form-control" id="inputssdError" placeholder="12345678901234">
                                    <span class="text-danger" style="color:red;" id="ssdError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="city_id">المدينه</label>
                                    <select class="form-control @error('city_id') is-invalid @enderror"  id="inputcity_idError" name="city_id">
                                        @foreach($cities as $c)
                                           <option value="{{$c->id}}">{{$c->value}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" style="color:red;" id="city_idError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="address">الحى</label>
                                    <input type="text" name="address" class="form-control" id="inputaddressError" placeholder="الحى">
                                    <span class="text-danger" style="color:red;" id="addressError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="email">البريد الالكترونى</label>
                                    <input type="email" name="email" class="form-control" id="inputemailError" placeholder="example@example.com">
                                    <span class="text-danger" style="color:red;" id="emailError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="linked_link">linkedIn</label>
                                    <input type="text" name="linked_link" class="form-control" id="inputlinked_linkError" placeholder="www.linkedinprofile/98.com">
                                    <span class="text-danger" style="color:red;" id="linked_linkError" role="alert"> </span>
                                </div>
                            </div>

                            <br><hr><br>
                            <label>المؤهلات الاكاديميه</label>
                            <div class="row first-calc">
                                 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"  id="graduation_date">
                                    <label for="graduation_date">تاريخ التخرج</label>
                                    <input type="date" class="form-control" id="inputgraduation_dateError" name="graduation_date" >
                                    <span class="text-danger" style="color:red;" id="graduation_dateError" role="alert"> </span>
                                </div> 

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="university_id">الجامعه</label>
                                    <select class="form-control @error('university_id') is-invalid @enderror"  id="inputuniversity_idError" name="university_id">
                                        @foreach($university as $u)
                                           <option value="{{$u->id}}">{{$u->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" style="color:red;" id="university_idError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="grade">التقدير</label>
                                    <select class="form-control @error('grade') is-invalid @enderror"  id="inputgradeError" name="grade">
                                           <option value="مقبول">مقبول</option>
                                           <option value="جيد">جيد</option>
                                           <option value="جيد جدا">جيد جدا</option>
                                           <option value="امتياز">امتياز</option>
                                    </select>
                                    <span class="text-danger" style="color:red;" id="gradeError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="specialization">التخصص</label>
                                    <input type="text" name="specialization" class="form-control" id="inputspecializationError" placeholder="المحاسبه واداره الاعمال">
                                    <span class="text-danger" style="color:red;" id="specializationError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                                    <label for="job_id">المسمى الوظيفى</label>
                                    <select class="form-control @error('job_id') is-invalid @enderror"  id="inputjob_idError" name="job_id">
                                        @foreach($jobs as $j)
                                           <option value="{{$j->id}}">{{$j->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" style="color:red;" id="job_idError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"  id="possible_start_date">
                                    <label for="possible_start_date">متى تستطيع ان تبدا</label>
                                    <input type="date" class="form-control" id="inputpossible_start_dateError" name="possible_start_date" >
                                    <span class="text-danger" style="color:red;" id="possible_start_dateError" role="alert"> </span>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <label for="notes">كلمنا عن نفسك</label>
                                    <textarea name="notes" class="form-control" id="inputnotesError" placeholder="كلمنا عن نفسك"></textarea>
                                    <span class="text-danger" style="color:red;" id="notesError" role="alert"> </span>
                                </div>
                                <br><br>
                            </div>
                            
                            <!-- ================الدورات================ -->
                            <br><hr><br>
                            <label>الدورات</label>
                            <div class="row first-calc">

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <input type="button" onclick="javascript:add_course();" value="اضافه"/>
                                </div>
                                <div id="courses">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                        <label for="Name"
                                            class="mr-sm-2">اسم الدوره
                                            :</label>
                                        <input class="form-control" type="text" name="courses[]" />
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                        <label for="Name"
                                            class="mr-sm-2">تاريخ البدء
                                            :</label>
                                        <input class="form-control" type="date" name="course_start_date[]" />
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                        <label for="Name"
                                            class="mr-sm-2">تاريخ النهايه
                                            :</label>
                                        <input class="form-control" type="date" name="course_end_date[]"/>
                                    </div>
                                </div>   
                            </div>
                            <!-- ================================ -->
                                  
                            <!-- ================الخبرات================ -->
                            <br><hr><br>
                            <label>الخبرات</label>
                            <div class="row first-calc">

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                    <input type="button" onclick="javascript:add_experance();" value="اضافه"/>
                                </div>
                                <div id="experances">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                        <label for="Name"
                                            class="mr-sm-2">اسم الشركه
                                            :</label>
                                        <input class="form-control" type="text" name="experances[]" />
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                        <label for="Name"
                                            class="mr-sm-2">تاريخ البدء
                                            :</label>
                                        <input class="form-control" type="date" name="experance_start_date[]" />
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                                        <label for="Name"
                                            class="mr-sm-2">تاريخ النهايه
                                            :</label>
                                        <input class="form-control" type="date" name="experance_end_date[]"/>
                                    </div>
                                </div>   
                            </div>
                            <!-- ================================ -->
                            <div class="send-btn">
                                <button type="submit"><i class="fas fa-paper-plane ml-2"></i> تقديم</button>
                            </div>
                        </form>
                </div>
</section>

@endsection


@section('modal')
@include('testWebsite.Pages.mobileNumberCheckModal')
@endsection

@section('scripts')
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>


<script type="text/javascript">

    function add_course(){
       // document.getElementById('courses').append('<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name"class="mr-sm-2">اسم الدوره:</label><input class="form-control" type="text" name="courses[]" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name" class="mr-sm-2">تاريخ البدء:</label><input class="form-control" type="date" name="start_date[]" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name"class="mr-sm-2">تاريخ النهايه:</label><input class="form-control" type="date" name="end_date[]"/></div>');
        document.getElementById('courses').innerHTML+='<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name"class="mr-sm-2">اسم الدوره:</label><input class="form-control" type="text" name="courses[]" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name" class="mr-sm-2">تاريخ البدء:</label><input class="form-control" type="date" name="course_start_date[]" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name"class="mr-sm-2">تاريخ النهايه:</label><input class="form-control" type="date" name="course_end_date[]"/></div>';
    }

    function add_experance(){
        document.getElementById('experances').innerHTML+='<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name"class="mr-sm-2">اسم الشركه:</label><input class="form-control" type="text" name="experances[]" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name" class="mr-sm-2">تاريخ البدء:</label><input class="form-control" type="date" name="experance_start_date[]" /></div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-12"><label for="Name"class="mr-sm-2">تاريخ النهايه:</label><input class="form-control" type="date" name="experance_end_date[]"/></div>';
    }
    
    $(document).ready(function() {
        var today = new Date().toISOString().split("T")[0];
        $('#date_of_birthError').attr("max", today);
        /*
        document.getElementById("saveBtn").disabled = true;
        document.getElementById("saveBtn").style.cursor = "not-allowed";
        */
    });



    jQuery(function(e) {

        $(document).on('click', '#saveBtn', function(e) {

            $('#nameError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            $('#birth_hijriError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#workError').addClass("d-none");
            $('#salaryError').addClass("d-none");
            $('#is_supportedError').addClass("d-none");
            $('#has_obligationsError').addClass("d-none");
            $('#has_financial_distressError').addClass("d-none");
            $('#owning_propertyError').addClass("d-none");

            e.preventDefault();
            var loader = $('.message-box');
            var btn = $(this);

        });
    });
</script>
@endsection

@extends('Customer.fundingReq.customerReqLayout')


@section('title') الصفحة الرئيسية @endsection


@section('content')



@php
$request=App\Http\Controllers\CustomerController::requestInfo();
@endphp


<!--
    <div class="fix-phone">
        <a class="nav-link order-price cont-phone" href="tel:920009423" data-scroll="contact">
            <i class="fas fa-phone mr-2"></i>
            920009423</a>
    </div>
    -->

<br> <br>

<div class="jobs-form">
    <div class="container">

        @php

        if(isset($request->class_id_agent))
        $classID=$request->class_id_agent;
        else
        $classID='';

        if(isset($request->noteWebsite))
        $reqNote=$request->noteWebsite;
        else
        $reqNote='';

        @endphp

        {{--

            <div class="head-div text-center wow fadeInUp">
            <h1>حالة طلبك</h1>

        </div>

        <input type="hidden" value="{{$request->id}}" id="reqID">



        <div class="order_status text-center mt-3">
            @if(\App\AskAnswer::where(['batch' => 0,'request_id' => $request->id,'customer_id' => auth('customer')->user()->id,'user_id' => $request->user_id])->count() ==0)

            @if ($reqNote == '')
            <h3>لا يزال طلبك تحت المراجعة</h3>
            @else
            <h3>{{$reqNote}}</h3>
            @endif
            @else
            <h3>تم إرسال طلب الإلغاء </h3>

            @endif
        </div>
        --}}


        @include('Customer.classificationsConditions')

        <div class="row mt-5">
            @if (session('status'))
            <div class="col-lg-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>مرحبا !</strong>{{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            @if (session('message'))
            <div class="col-lg-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif

            {{--

                <div class="col-md-4 notHasProblem" style="display: {{ ($classID != 16 || $request->customer_resolve_problem != '') ? 'block' : 'none' }} ;">
                <div class="single-userItem text-center">
                    <form method="post" action="{{route('CustomernewChat')}}">
                        @csrf
                        <input type="hidden" name="receivers[]" value=" {{App\Http\Controllers\CustomerController::salesAgent()}}" />
                        <input type="hidden" name="redirect" value="0" />
                        <div class="mess__item" onclick="$(this).closest('form').submit();">

                            <div class="userItem_icon mb-3 color1">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <div class="userItem_text color1">
                                <h4>تواصل مع مستشار التمويل</h4>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
                --}}

            
            <div class="col-md-4 notHasProblem" style="display: {{ ($classID != 16 || $request->customer_resolve_problem != '') ? 'block' : 'none' }} ;">

                <div class="single-userItem text-center">
                    <a href="{{route('customer.fundingRequestCustomer',App\Http\Controllers\CustomerController::requestID())}}">
                        <div class="userItem_icon mb-3 color2">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="userItem_text color2">
                            <h4>تابع طلبك</h4>
                        </div>
                </div>
                </a>
            </div>

            <div class="col-md-4 notHasProblem" style="display: {{ ($classID != 16 || $request->customer_resolve_problem != '') ? 'block' : 'none' }} ;">

                <div class="single-userItem text-center">
                    <a href="{{route('customer.customer-reminders.index')}}">
                        <div class="userItem_icon mb-3 color3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="userItem_text color3">
                            <h4> التذكيرات</h4>
                        </div>
                </div>
                </a>
            </div>
            {{--
                <div class="col-md-3 notHasProblem" style="display: {{ ($classID != 16 || $request->customer_resolve_problem != '') ? 'block' : 'none' }} ;">

            <div class="single-userItem text-center">
                <a href="{{url('/properties')}}">
                    <div class="userItem_icon mb-3 color5">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="userItem_text color4">
                        <h4> العقارات</h4>
                    </div>
            </div>
            </a>
        </div>
        --}}
        @if(\App\AskAnswer::where(['batch' => 0,'request_id' => $request->id,'customer_id' => auth('customer')->user()->id,'user_id' =>$request->user_id])->count() ==0)
        @if($request->class_id_agent != 13)
        <div class="row" style="padding: 20px">
            <div class="col-lg-12">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
                    إلغاء الطلب
                </button>

                <!-- Modal -->
                <div class="modal fade pt-5" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">إلغاء الطلب</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                هل انت متأكد من إلغاء الطلب ؟
                                <br>
                                سوف تقوم بالإجابة على التقييم الخاص بإلغاء الطلب
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                <a href="{{route('customer.survey.index',$request->id)}}" type="button" class="btn btn-primary">نعم أوافق على إلغاء الطلب</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @else
        @if(DB::table('notifications')->where(['type' => 5,'req_id'=>$request->id,'request_type' => 22])->count() == 0)
        <div class="row" style="padding: 20px">
            <div class="col-lg-12">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                    إعادة فتح الطلب
                </button>

                <!-- Modal -->
                <div class="modal fade pt-5" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">إعادة فتح الطلب</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                هل انت متأكد من إعادة فتح الطلب ؟
                                <br>
                                سوف يتم إرسال تنبيه للإستشارى برغبتك فى إعادة فتح طلبك
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                <a href="{{route('customer.request.reopen',$request->id)}}" type="button" class="btn btn-primary">نعم أوافق على إعادة فتح الطلب </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
        {{--

                <div class="col-md-4 notHasProblem" style="display: {{ ($classID != 16 || $request->customer_resolve_problem != '') ? 'block' : 'none' }} ;">

        <div class="single-userItem text-center">
            <a href="{{url('complain')}}">
                <div class="userItem_icon mb-3 color3">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="userItem_text color3">
                    <h4>شكاوي و اقتراحات</h4>
                </div>
        </div>
        </a>
    </div>


    --}}





    {{--

         <div class="col-md-4 hasProblem" style="display: {{ ($classID == 16 && $request->customer_resolve_problem == '') ? 'block' : 'none' }} ;">
</div>

<div class="col-md-4 hasProblem" style="display: {{ ($classID == 16 && $request->customer_resolve_problem == '') ? 'block' : 'none' }} ;">

    <div class="single-userItem text-center">
        <a href="{{url('complain')}}">
            <div class="userItem_icon mb-3 color3">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="userItem_text color3">
                <h4>شكاوي و اقتراحات</h4>
            </div>
    </div>
    </a>
</div>
<div class="col-md-4 hasProblem" style="display: {{ ($classID == 16 && $request->customer_resolve_problem == '') ? 'block' : 'none' }} ;">
</div>
--}}


 @include('testWebsite.Pages.appsection') 





</div>
</div>

@endsection

@section('updateModel')
@include('Customer.confirmationMsg')
@endsection

@section('scripts')

<script>
    $(document).on('click', '#foundProperty', function(e) {

        var modalConfirm = function(callback) {

            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {
                callback(true);
                $("#mi-modal").modal('hide');

            });

            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');


            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                document.getElementById("foundPropertyError").innerHTML = "";
                document.getElementById("foundProperty").innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::guest_trans('Loading') }}";
                document.getElementById("foundProperty").style.cursor = "no-drop";
                document.getElementById("foundProperty").disabled = true;
                var id = document.getElementById("reqID").value;

                $.get("{{ route('customer.updateFoundProperty')}}", {
                    id: id
                }, function(data) {
                    if (data == 1)
                        document.getElementById("foundProperty").innerHTML = "تم التحديث بنجاح ، سيتم التواصل بك قريبا";
                    else {
                        document.getElementById("foundProperty").innerHTML = "نعم ، وجدت عقار ";
                        document.getElementById("foundProperty").style.cursor = "pointer";
                        document.getElementById("foundProperty").disabled = false;
                        document.getElementById("foundPropertyError").innerHTML = "حدث خطأ ، حاول مجددا";
                    }


                });

            } else {

            }
        });

    });



    /*
            $(document).on('click', '#wantToUplad', function(e) {


                $('#nameError').text('');
                $('#fileError').text('');
                document.getElementById("filename").value = "";
                document.getElementById("file").value = "";
                //alert('h');
                $('#myModal1').modal('show');

            });

            //////////////////////////////

            $('#file-form').submit(function(event) {

                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    url: "{{ route('customer.uploadFile')}}",
                    data: formData,
                    type: 'post',
                    async: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        // console.log(response);
                        $('#myModal1').modal('hide');

                        alert('تم الرفع بنجاح');


                    },
                    error: function(xhr) {

                        var errors = xhr.responseJSON;

                        if ($.isEmptyObject(errors) == false) {

                            $.each(errors.errors, function(key, value) {

                                var ErrorID = '#' + key + 'Error';
                                // $(ErrorID).removeClass("d-none");
                                $(ErrorID).text(value);

                            })

                        }

                    }
                });

            });
            */



    $(document).on('click', '#sendReason', function(e) {


        document.getElementById("updateRejectStatus").innerHTML = "";
        $('#sendReasonError').text('');
        document.getElementById("sendReason").disabled = true;
        document.getElementById("sendReason").style.cursor = "not-allowed";

        var reasonvalue = $('#reason').val();
        if (reasonvalue != '') {

            document.getElementById("sendReasonError").style.color = "#29a3a3";
            document.getElementById("sendReasonError").innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::guest_trans('Loading') }}";


            var id = document.getElementById("reqID").value;

            $.get("{{ route('customer.updateCustomerWantToReject')}}", {
                id: id,
                customerwant: 1,
                reasonvalue: reasonvalue,
            }, function(data) {
                if (data == 1) {
                    $("#noIwant").fadeOut(200);
                    $("#yesIwant").fadeOut(200);
                    $("#rejectReason").fadeOut(200);
                    document.getElementById("updateRejectStatus").style.color = "#00b33c";
                    document.getElementById("updateRejectStatus").innerHTML = "شكرا لك";

                } else {
                    document.getElementById("sendReasonError").style.color = "#ff4d4d";
                    document.getElementById("sendReasonError").innerHTML = "حدث خطأ ، حاول مجددا";
                    document.getElementById("sendReason").style.cursor = "pointer";
                    document.getElementById("sendReason").disabled = false;
                }


            });

        } else {
            document.getElementById("sendReasonError").style.color = "crimson";
            $('#sendReasonError').text('الحقل مطلوب');
            document.getElementById("sendReason").style.cursor = "pointer";
            document.getElementById("sendReason").disabled = false;
        }



    });


    $(document).on('click', '#yesIwant', function(e) {

        $("#rejectReason").fadeIn(1100);
        $('#sendReasonError').text('');
        $('#reason').val('');


    });

    $(document).on('click', '#noIwant', function(e) {

        document.getElementById("updateRejectStatus").innerHTML = "";

        $("#rejectReason").fadeOut(500);
        $('#sendReasonError').text('');
        $('#reason').val('');


        var modalConfirm = function(callback) {

            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {
                callback(true);
                $("#mi-modal").modal('hide');

            });

            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');


            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {
                document.getElementById("updateRejectStatus").style.color = "#29a3a3";
                document.getElementById("updateRejectStatus").innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::guest_trans('Loading') }}";

                var id = document.getElementById("reqID").value;

                $.get("{{ route('customer.updateCustomerWantToReject')}}", {
                    id: id,
                    customerwant: 0,
                }, function(data) {
                    if (data == 1) {
                        $("#noIwant").fadeOut(200);
                        $("#yesIwant").fadeOut(200);
                        document.getElementById("updateRejectStatus").style.color = "#00b33c";
                        document.getElementById("updateRejectStatus").innerHTML = "تم التحديث بنجاح ، سيتم التواصل بك قريبا";

                    } else {
                        document.getElementById("updateRejectStatus").style.color = "#ff4d4d";
                        document.getElementById("updateRejectStatus").innerHTML = "حدث خطأ ، حاول مجددا";
                    }


                });

            } else {

            }
        });




    });

    $(document).on('click', '#yesResolveProblem', function(e) {

        var modalConfirm = function(callback) {

            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {
                callback(true);
                $("#mi-modal").modal('hide');

            });

            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');


            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                document.getElementById("ResolveProblemError").innerHTML = "";
                document.getElementById("yesResolveProblem").innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::guest_trans('Loading') }}";
                document.getElementById("yesResolveProblem").style.cursor = "no-drop";
                document.getElementById("yesResolveProblem").disabled = true;
                var id = document.getElementById("reqID").value;

                $.get("{{ route('customer.updateCustomerResolveProblem')}}", {
                    id: id
                }, function(data) {
                    if (data == 1) {
                        $(".hasProblem").fadeOut(200);
                        $(".notHasProblem").fadeIn(500);
                        document.getElementById("yesResolveProblem").innerHTML = "تم التحديث بنجاح ، سيتم التواصل بك قريبا";

                    } else {
                        document.getElementById("yesResolveProblem").innerHTML = "نعم ، حللت المشكلة";
                        document.getElementById("yesResolveProblem").style.cursor = "pointer";
                        document.getElementById("yesResolveProblem").disabled = false;
                        document.getElementById("ResolveProblemError").innerHTML = "حدث خطأ ، حاول مجددا";
                    }


                });

            } else {

            }
        });

    });
</script>

@endsection
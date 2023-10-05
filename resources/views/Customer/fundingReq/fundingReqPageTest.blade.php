@extends('Customer.fundingReq.customerReqLayout')

@section('title') طلب العميل @endsection

@section('style')
<style>
    .edit_request h5 {
        display: inline-block;
        padding: 15px 40px;
        background: #072a46;
        color: #fff;
        border-radius: 5px;
    }

    .edit_request {
        max-width: 700px;
        margin: auto;
    }

    .edit_request :hover {
        background: #0e558b;
        cursor: pointer;
    }
</style>
@endsection

@section('content')


<div class="container-fluid px-lg-5">

    <div class="ContTabelPage">
        <div class="addUser my-4">
            <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
                <h3 style="text-align: center;"> تفاصيل طلبك :</h3>
                <div style="text-align: left; padding: 2% ; font-size:large">
                    <a href="{{url('/customer') }}">
                        الرئيسية
                        <i class="fa fa-home"> </i>
                    </a>
                    |
                    <a href="{{ url()->previous() }}">
                        رجوع
                        <i class="fa fa-arrow-circle-left"> </i>
                    </a>

                </div>
            </div>
        </div>



        @if (session('message2'))
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message2') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        @endif

        @php
            $fields = $fields??collect();
            $arrFields = $fields->pluck('option_name')->toArray();
        @endphp

        <section class="new-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">


                        <input type="hidden" name="reqID" id="reqID" value="{{$id}}">

                        <ul class="list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow">

                            @if ($purchaseCustomer-> type == 'رهن' || $purchaseCustomer-> type == 'تساهيل' || ($purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment)) ) || (!empty($paymentForDisplayonly)))

                            @if (in_array( 'customerReq_attachments' , $arrFields))
                            <li class="tab" id="tab5">
                                <i class="fas fa-layer-group"></i>
                                مرفقات الطلب
                            </li>
                            @endif

                            <li class="tab" id="tab4">
                                <i class="fas fa-briefcase"></i>
                                بيانات الدفعة
                            </li>

                            <li class="tab" id="tab3">
                                <i class="fas fa-briefcase"></i>
                                بيانات التمويل
                            </li>

                            <li class="tab" id="tab2">
                                <i class="fas fa-home"></i>
                                بيانات العقار
                            </li>
                            <li class="tab active-on" id="tab1">
                                <i class="fas fa-user"></i>
                                بيانات العميل
                            </li>


                            @else

                            @if (in_array( 'customerReq_attachments' , $arrFields))
                            <li class="tab" id="tab4">
                                <i class="fas fa-layer-group"></i>
                                مرفقات الطلب
                            </li>
                            @endif

                            <li class="tab" id="tab3">
                                <i class="fas fa-briefcase"></i>
                                بيانات التمويل
                            </li>

                            <li class="tab" id="tab2">
                                <i class="fas fa-home"></i>
                                بيانات العقار
                            </li>
                            <li class="tab active-on" id="tab1">
                                <i class="fas fa-user"></i>
                                بيانات العميل
                            </li>

                            @endif


                        </ul>
                        <div class="tabs-serv">

                            <div class="tab-body">

                                @if ($purchaseCustomer-> type == 'رهن' || $purchaseCustomer-> type == 'تساهيل' || ($purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment)) ) || (!empty($paymentForDisplayonly)))

                                <div class="row hdie-show" id="tab1-cont">
                                    @include('Customer.fundingReq.fundingCustomerTest')

                                </div>

                                <div class="row hdie-show" id="tab2-cont">

                                    @include('Customer.fundingReq.fundingRealTest')


                                </div>

                                <div class="row hdie-show" id="tab3-cont">

                                    @include('Customer.fundingReq.fundingInfoTest')

                                </div>

                                <div class="row hdie-show" id="tab4-cont">

                                    @include('Customer.fundingReq.fundingPrepaymentTest')

                                </div>

                                @if (in_array( 'customerReq_attachments' , $arrFields))

                                <div class="row hdie-show" id="tab5-cont">

                                    @include('Customer.fundingReq.documentTest')

                                </div>

                                @endif



                                @else

                                <div class="row hdie-show" id="tab1-cont">
                                    @include('Customer.fundingReq.fundingCustomerTest')

                                </div>

                                <div class="row hdie-show" id="tab2-cont">

                                    @include('Customer.fundingReq.fundingRealTest')


                                </div>

                                <div class="row hdie-show" id="tab3-cont">

                                    @include('Customer.fundingReq.fundingInfoTest')

                                </div>

                                @if (in_array( 'customerReq_attachments' , $arrFields))

                                <div class="row hdie-show" id="tab4-cont">

                                    @include('Customer.fundingReq.documentTest')

                                </div>

                                @endif

                                @endif

                            </div>


                        </div>
                    </div>
                </div>


                {{--
                    @include('Customer.fundingReq.fundingReqInfoTest')
                    --}}



            </div>

            <div class="edit_request text-center mt-3">

                <h5 id="needToEdit">بياناتك بحاجة إلى تصحيح؟</h5>
                <br>
                <span style="color:brown" id="needToEditError"></span>

            </div>

            <br> <br>

        </section>



    </div>
</div>

@endsection
@section('updateModel')
@include('Customer.confirmationMsg')
@endsection

@section('scripts')
<script>
    $(document).on('click', '#needToEdit', function() {

        document.getElementById("needToEditError").innerHTML = '';

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
                document.getElementById("needToEdit").innerHTML = "<i class='fa fa-spinner fa-spin'></i> الرجاء الانتظار";
                document.getElementById("needToEdit").style.cursor = "no-drop";
                document.getElementById("needToEdit").style.background = "#1271ba";
                var id = document.getElementById("reqID").value;

                $.get("{{ route('customer.needToEditReqInfo')}}", {
                    id: id
                }, function(data) {

                    if (data == 1) {
                        document.getElementById("needToEdit").style.background = "#009933";
                        document.getElementById("needToEdit").innerHTML = "تم إرسال طلبك بنجاح ، سيتم التواصل بك قريبا";
                    } else if (data == 0) {
                        document.getElementById("needToEdit").style.background = "#660000";
                        document.getElementById("needToEdit").innerHTML = "لديك طلب تعديل بيانات سابقاً ، يرجى الإنتظار";
                    } else {
                        document.getElementById("needToEdit").innerHTML = "بياناتك بحاجة إلى تصحيح؟";
                        document.getElementById("needToEdit").style.background = "#072a46";
                        document.getElementById("needToEdit").style.cursor = "pointer";
                        document.getElementById("needToEditError").innerHTML = "حدث خطأ ، حاول مجددا";
                    }

                });

            } else {

            }
        });

    });
</script>
@endsection

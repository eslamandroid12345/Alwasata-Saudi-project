@extends('Customer.fundingReq.customerReqLayout')


@section('title') الشكاوي والاقتراحات @endsection
<style>
    .data td{
        color: #333;
    }
</style>

@section('content')

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

    <div class="container">

        <div class="asks-form mt-5 mb-5">
            <div class="head-div text-center wow fadeInUp">
                <h1>التقييم</h1>
                <p>رأيك يفرق معنا ويؤسفنا إلغاؤك للطلب، فضلا زودنا بسبب الإلغاء؛ لخدمتك بشكل أفضل في المرات القادمة :</p>
            </div>

            @if(session()->has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('success') }}
                </div>
            @endif

            @if(session()->has('errorSugg'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('errorSugg') }}
                </div>
            @endif
        </div>
        <div class="add-new pb-5">
                <form action="{{route('customer.survey.store')}}" class="row" method="POST" id="formPost">
                    @csrf

                    <input type="hidden" name="user_id" value="{{auth('customer')->user()->id}}">
                    <input type="hidden" name="request_id" value="{{$requestId}}">
                    <input type="hidden" name="count" value="{{$count}}">
                    @foreach($questions as $question)
                        <div class="col-lg-12 p-3 m-2" style="background: #fff">
                            <h4>{{$loop->iteration}} - {{$question->question}}</h4>
                            <label class="survey" for="yes{{$loop->iteration}}">نعم
                                <input type="radio" value="1" name="answer[{{$question->id}}]" id="yes{{$loop->iteration}}">
                                <span class="checkmark"></span>
                            </label>
                            <label class="survey" for="no{{$loop->iteration}}">لا
                                <input type="radio" value="0" name="answer[{{$question->id}}]" id="no{{$loop->iteration}}">
                                <span class="checkmark"></span>
                            </label>
                            <div id="error{{$question->id}}"></div>
                        </div>

                    @endforeach
                    <div class="col-lg-12 mt-2 mb-5">
                        <label for="customer_reason_for_cancel">سبب الإلغاء</label>
                        <textarea name="customer_reason_for_cancel" id="customer_reason_for_cancel" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="col-lg-12">
                        <input type="submit" value="إرسال" class="btn btn-success" id="submit-btn">
                        <input type="submit" value="تخطى التقييم , وإلغاء الطلب" style="float: left" class="btn btn-danger pull-left"  name="empty" id="submit-btn">
                    </div>
                </form>
        </div>

    </div>

@endsection
@section('style')
    <style>
        .survey {
            display: inline-block;
            position: relative;
            min-width: 20%;
            padding-right: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 20px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default radio button */
        .survey input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom radio button */
        .survey .checkmark {
            position: absolute;
            top: 0;
            right: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 50%;
        }

        /* On mouse-over, add a grey background color */
        .survey:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .survey input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .survey .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .survey input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .survey .checkmark:after {
            top: 9px;
            right: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
        }
    </style>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="http://ericjgagnon.github.io/wickedpicker/javascript/smooth_scroll.js"></script>

    <script type="text/javascript" src="http://ericjgagnon.github.io/wickedpicker/wickedpicker/wickedpicker.min.js"></script>
    <script type="text/javascript">
        $('#submit-btn').click(function(e){
            e.preventDefault();
            var arr=[];
            @foreach($questions as $key => $question)
            var val = $("input[name='answer[{{$question->id}}]']").is(':checked');
            if(val == false){
                arr.push("{{$question->id}}");
                $('#error{{$question->id}}').html('<div class="text-danger">هذا السؤال مطلوب</div>');
            }else{
                $('#error{{$question->id}}').html("");
            }
            @endforeach
            if(arr.length == 0){
                $('#formPost').submit();
            }
        });
    </script>
@endsection

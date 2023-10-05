@extends('Customer.fundingReq.customerReqLayout')


@section('title') الشكاوي والاقتراحات @endsection


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

    <div class="asks-form mt-5">
        <div class="head-div text-center wow fadeInUp">
            <h1>شكاوي واقتراحات</h1>

        </div>

        @if(session()->has('status'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('status') }}
        </div>
        @endif

        @if(session()->has('errorSugg'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('errorSugg') }}
        </div>
        @endif


        <div class="add-new">
            <div class="box">
                <a href="#"> <i class="fas fa-plus mr-2"></i> اضف جديد </a>
            </div>
            <div class="newForm">
                <form action="{{route('complain.store')}}" method="post">
                    @csrf
                    {{method_field('POST')}}
                    <div class="closeBox">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="askBox d-flex mt-4">


                        <div class="form-check mr-4">
                            <input type="radio" class="form-check-input" id="exampleCheck01" name="type" value="complain" @if ( old('type')=='complain' ) checked @endif>
                            <label class="form-check-label" for="exampleCheck01">شكوى</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="exampleCheck02" name="type" value="suggestion" @if ( old('type')=='suggestion' ) checked @endif>
                            <label class="form-check-label" for="exampleCheck02">اقتراح</label>
                        </div>

                    </div>

                    <strong style="color:darkred; font-size: 100%"> {{ $errors->first('type') }}</strong>

                    <div class="ask-body mt-4">
                        <div class="askTitle">

                            <label for="">العنوان</span> </label>
                            <input class="d-block" type="text" name="title" id="" value="{{ old('title')}}">

                            <strong style="color:darkred; font-size: 100%"> {{ $errors->first('title') }}</strong>

                        </div>
                        <div class="askText mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">الوصف</label>
                                <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ old('description')}}</textarea>

                                <strong style="color:darkred; font-size: 100%"> {{ $errors->first('description') }}</strong>

                            </div>
                        </div>
                    </div>
                    <div class="subAsk send-btn my-2">
                        <button type="submit"><i class="fas fa-paper-plane ml-2"></i> ارسال</button>
                    </div>
                </form>
            </div>

        </div>

        <div class="asksAll mt-5">
            <div class="h5 mb-3">سجل الشكاوي والاقتراحات</div>
            @if($complains->count() == 0)
            <div class="alert alert-success text-center" role="alert">
                يمكنك إضافة شكاوى ومقتارحات ومتابعة الشكوى الخاصة بك
            </div>
            @endif
            @foreach($complains as $complain)

            @if($complain->type == 'complain')
            <div class="singleAsk">
                <div class="singleTitle">
                    <h5>
                        <a href="{{route('complain.chat',$complain->id)}}">{{$complain->title}} </a>
                        <span>شكوى</span></h5>
                </div>
                <div class="singleText mt-3 d-block">
                    <p>{{$complain->description}}</p>
                </div>
            </div>
            @else
            <div class="singleAsk">
                <div class="singleTitle">
                    <h5> {{$complain->title}}<span class="hint">اقتراح</span></h5>
                </div>
                <div class="singleText mt-3 d-block">
                    <p>{{$complain->description}}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>

@endsection
@extends('Customer.fundingReq.customerReqLayout')


@section('title') نعديل بيانات الصفحة الشخصية @endsection


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

<div class="container mt-5">
  <div class="privateData">
    <div class="head-div text-center wow fadeInUp mb-5">
      <h1>تعديل بياناتي</h1>

    </div>

    @if (session('success'))
    <div class="alert alert-success" role="alert">
    {!! session('success') !!}
    </div>
    @elseif(session('error'))
    <div class="alert alert-danger" role="alert">
    {!! session('error') !!}
    </div>
    @endif  

    <div class="container">

      <div class="row flex-lg-nowrap">


        <div class="col">
          <div class="row">
            <div class="col mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="e-profile">


                    <div class="tab-content pt-3">
                      <div class="tab-pane active">
                        <form action="{{route('customer.updateProfile')}}" method="post" class="form">
                          @csrf
                          <div class="row">
                            <div class="col">
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label>الاسم</label>
                                    <input class="form-control" type="text" name="name" placeholder="John Smith" value="  {{auth()->guard('customer')->user()->name}}">
                                    @if ($errors->has('name'))
                                    <span class="help-block col-md-12">
                                      <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                  </div>
                                </div>
                                <div class="col">
                                  <div class="form-group">
                                    <label>رقم الجوال</label>
                                    <input readonly class="form-control" type="text" name="phonenumber"  value="  {{auth()->guard('customer')->user()->mobile}}">
                                    @if ($errors->has('phonenumber'))
                                    <span class="help-block col-md-12">
                                      <strong style="color:red ;font-size:10pt">{{ $errors->first('phonenumber') }}</strong>
                                    </span>
                                    @endif
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label>البريد الالكتروني</label>
                                    <input class="form-control" type="text" name="email" value="  {{auth()->guard('customer')->user()->email}}">
                                    @if ($errors->has('email'))
                                    <span class="help-block col-md-12">
                                      <strong style="color:red ;font-size:10pt">{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <label>كلمة المرور</label>
                                    <input class="form-control" type="password" name="password" id="password" value="">
                                    <input type="checkbox" onclick="myFunction()">{{ MyHelpers::guest_trans('Show') }} {{ MyHelpers::guest_trans('Password') }}
                                    @if ($errors->has('password'))
                                    <span class="help-block col-md-12">
                                      <strong style="color:red ;font-size:10pt">{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>

                          <div class="row">
                            <div class="col d-flex justify-content-end">
                              <button class="btn btn-primary" type="submit">حفظ التعديلات</button>
                            </div>
                          </div>
                        </form>

                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-3 mb-3">
              <div class="card">
                <div class="card-body">
                  <h6 class="card-title font-weight-bold">تحتاح مساعدة ؟ </h6>
                  <p class="card-text">تواصل مع فريق الدعم الفني في حال لديك اي استقسارات</p>
                  <a href="{{route('customer.helpDesk')}}"><button type="button" class="btn btn-primary">تواصل الان</button></a>
                </div>
              </div>
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
  function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
</script>

@endsection
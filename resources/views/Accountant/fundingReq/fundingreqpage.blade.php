@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
@endsection

@section('css_style')


<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />


<style>
  .clearfix:before,
  .clearfix:after {
    content: " ";
    display: table;
  }

  .clearfix:after {
    clear: both;
  }

  .custom-control-label {
    position: relative;
    padding-left: 1.8rem;
  }


  .fn {
    color: #ccc;
    text-align: center;
  }


  /*Fun begins*/
  .tab_container {
    width: 90%;
    margin: 0 auto;
    padding-top: 70px;
    position: relative;
  }

  .fnn {
    clear: both;
    padding-top: 10px;
    display: none;
  }

  .fnnn {
    font-weight: 700;
    font-size: 18px;
    display: block;
    float: left;
    /*width: 25%;*/
    padding: 1.5em;
    color: #757575;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    background: #f0f0f0;
  }

  #tab1:checked~#content1,
  #tab2:checked~#content2,
  #tab3:checked~#content3,
  #tab4:checked~#content4,
  #tab5:checked~#content5 {
    display: block;
    padding: 20px;
    background: #fff;
    color: #999;
    border-bottom: 2px solid #f0f0f0;
  }

  .tab_container .tab-content p,
  .tab_container .tab-content h3 {
    -webkit-animation: fadeInScale 0.7s ease-in-out;
    -moz-animation: fadeInScale 0.7s ease-in-out;
    animation: fadeInScale 0.7s ease-in-out;
  }

  .tab_container .tab-content h3 {
    text-align: center;
  }

  .tab_container [id^="tab"]:checked+label {
    background: #fff;
    box-shadow: inset 0 3px #0CE;
  }

  .tab_container [id^="tab"]:checked+label .fa {
    color: #0CE;
  }

  label .fa {
    font-size: 1.3em;
    margin: 0 0.4em 0 0;
  }

  /*Media query*/
  @media only screen and (max-width: 930px) {
    label span {
      font-size: 14px;
    }

    label .fa {
      font-size: 14px;
    }
  }

  @media only screen and (max-width: 768px) {
    label span {
      display: none;
    }

    label .fa {
      font-size: 16px;
    }

    .tab_container {
      width: 98%;
    }
  }

  /*Content Animation*/
  @keyframes fadeInScale {
    0% {
      transform: scale(0.9);
      opacity: 0;
    }

    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  .progressbar {
    counter-reset: step;
  }

  .progressbar li {
    list-style-type: none;
    width: 25%;
    float: left;
    font-size: 13px;
    position: relative;
    text-align: center;
    text-transform: uppercase;
    color: #7d7d7d;
  }

  .progressbar li:before {
    width: 30px;
    height: 30px;
    content: counter(step);
    counter-increment: step;
    line-height: 30px;
    border: 2px solid #7d7d7d;
    display: block;
    text-align: center;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    background-color: white;
  }

  .progressbar li:after {
    width: 100%;
    height: 3px;
    content: '';
    position: absolute;
    background-color: #7d7d7d;
    top: 15px;
    left: -50%;
    z-index: -1;
  }

  .progressbar li:first-child:after {
    content: none;
  }

  .progressbar li.active {
    color: #256789;
  }

  .progressbar li.active:before {
    border-color: #1D6A96;
  }

  .progressbar li.active+li:after {
    background-color: #1D6A96;
  }

  .vertical-align {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row;
  }


  .afnan .fa {
    display: inline-block;
    border-radius: 60px;
    /*box-shadow: 0px 0px 2px;*/
    padding: 0.5em 0.5em;
    width: 60px;
    font-size: 30px;
    text-align: center;
    text-decoration: none;
    margin: 5px 8px;
  }

  .afnan .fa:hover {
    opacity: 0.8;
  }

  .fa-star {
    background: #e6e600;
    color: white;
  }

  .fa-trash {
    background: #bfbfbf;
    color: white;
  }

  .fa-times {
    background: #ff3333;
    color: white;
  }

  .fa-refresh {
    background: #0077b3;
    color: white;
  }

  .disabled {
    pointer-events: none;
  }

  .sticky {
    position: fixed;
    top: 70px;
    left: 5px;
    Width: 80%;
    z-index: 99 !important;
    margin: 0 auto;

  }

  /* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
</style>
@endsection

@section('customer')


<div id="msg2" class="alert alert-dismissible" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="msg3" class="alert alert-dismissible" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div class="alert alert-danger" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

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

@if(session()->has('message3'))
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  {{ session()->get('message3') }}
</div>
@endif

@if(session()->has('message4'))
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  {{ session()->get('message4') }}
</div>
@endif


@if (count($errors) > 0)
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif



<input type="hidden" value="{{$purchaseCustomer-> is_canceled}}" id="is_canceled">
<input type="hidden" value="{{$purchaseCustomer-> type}}" id="typeReq">
<input type="hidden" value="{{$id}}" id="reqId">

<div class="tab_container" style=" padding-top: 8px;">
  <label class="fnnn" style="width: 100%; display: block;padding: 20px;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;">

    @if ($purchaseCustomer-> type == 'شراء')

    @if ($purchaseCustomer-> is_stared == 1)
    <span> {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }} <a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>

    @elseif ($purchaseCustomer-> is_followed == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}<a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>

    @else
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}</span>
    @endif


    @elseif ($purchaseCustomer-> type == 'رهن')

    @if ($purchaseCustomer-> is_stared == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}<a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>

    @elseif ($purchaseCustomer-> is_followed == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>

    @else
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</span>
    @endif


    @elseif ($purchaseCustomer-> type == 'تساهيل')

    @if ($purchaseCustomer-> is_stared == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'tasheil') }}<a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>

    @elseif ($purchaseCustomer-> is_followed == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'tasheil') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>

    @else
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'tasheil') }}</span>
    @endif


    @elseif ($purchaseCustomer-> type == 'شراء-دفعة')

    @if ($purchaseCustomer-> is_stared == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} <a href="#" class="fa fa-star" style=" background:white; color:#e6e600"></a></span>
    @elseif ($purchaseCustomer-> is_canceled == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} <a href="#" class="fa fa-times" style=" background:white; color:#ff3333"></a></span>
    @elseif ($purchaseCustomer-> is_followed == 1)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} <a href="#" class="fa fa-refresh" style=" background:white; color:#0077b3"></a></span>
    @else
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purshase-Prepay') }} </span>
    @endif

    @elseif ($purchaseCustomer-> type == null && $purchaseCustomer->source == 2)
    <span>{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Collobreator') }}


      @else
      <span>{{ MyHelpers::admin_trans(auth()->user()->id,'New Request') }}
        @endif



  </label>
</div>




<form action="{{ route('agent.updateFunding')}}" method="post" novalidate="novalidate" id="frm-update">
  @csrf
  



  <div class="tab_container" style=" padding-top: 5px;">

    @if ($purchaseCustomer-> type == 'رهن' || $purchaseCustomer-> type == 'تساهيل')
    <input class="fnn" id="tab1" type="radio" name="tabs" checked>
    <label class="fnnn" style="width: 20%;" for="tab1"><i class="fa fa-user-circle-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }} </span></label>

    <input class="fnn" id="tab2" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab2"><i class="fa fa-building-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }} </span></label>

    <input class="fnn" id="tab3" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab3"><i class="	fa fa-shopping-cart"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }} </span></label>

    <input class="fnn" id="tab4" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab4"><i class="fa fa-paperclip"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }} </span></label>


    <input class="fnn" id="tab5" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab5"><i class="fa fa-credit-card"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }} </span></label>

    @elseif ( ($purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment)) ) || (!empty($paymentForDisplayonly)) )


    <input class="fnn" id="tab1" type="radio" name="tabs" checked>
    <label class="fnnn" style="width: 20%;" for="tab1"><i class="fa fa-user-circle-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }} </span></label>

    <input class="fnn" id="tab2" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab2"><i class="fa fa-building-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }} </span></label>

    <input class="fnn" id="tab3" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab3"><i class="	fa fa-shopping-cart"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }} </span></label>

    <input class="fnn" id="tab4" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab4"><i class="fa fa-paperclip"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }} </span></label>


    <input class="fnn" id="tab5" type="radio" name="tabs">
    <label class="fnnn" style="width: 20%;" for="tab5"><i class="fa fa-credit-card"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }} </span></label>


    @else

    <input class="fnn" id="tab1" type="radio" name="tabs" checked>
    <label class="fnnn" style="width: 25%;" for="tab1"><i class="fa fa-user-circle-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }} </span></label>

    <input class="fnn" id="tab2" type="radio" name="tabs">
    <label class="fnnn" style="width: 25%;" for="tab2"><i class="fa fa-building-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }} </span></label>

    <input class="fnn" id="tab3" type="radio" name="tabs">
    <label class="fnnn" style="width: 25%;" for="tab3"><i class="	fa fa-shopping-cart"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }} </span></label>

    <input class="fnn" id="tab4" type="radio" name="tabs">
    <label class="fnnn" style="width: 25%;" for="tab4"><i class="fa fa-paperclip"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }} </span></label>

    @endif


    @if (!empty ($payment))
    <input value="{{$payment->payStatus}}" id="statusPayment" type="hidden" name="statusPayment"> <!-- To pass prepayment status-->
    @else
    <input value="" id="statusPayment" type="hidden" name="statusPayment">
    @endif


    <input value={{$reqStatus}} id="statusRequest" type="hidden" name="statusRequest"> <!-- To pass request status-->
    <input value={{$id}} id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->

    <section id="content1" class="tab-content fnn">
      <p>
        @include('Accountant.fundingReq.fundingCustomer')
      </p>
    </section>

    <section id="content2" class="tab-content fnn">
      <p>
        @include('Accountant.fundingReq.fundingreal')
      </p>
    </section>

    <section id="content3" class="tab-content fnn">
      <p>
        @include('Accountant.fundingReq.fundingInfo')
      </p>
    </section>

    <section id="content4" class="tab-content fnn">
      <p>
        @include('Accountant.fundingReq.document')
      </p>
    </section>

    @if ($purchaseCustomer-> type == 'رهن' || $purchaseCustomer-> type == 'تساهيل')
    <section id="content5" class="tab-content fnn">
      <p>
        @include('Accountant.fundingReq.tsaheel')
      </p>
    </section>
    @endif


    @if ( ($purchaseCustomer-> type == 'شراء-دفعة' && (!empty ($payment))) || (!empty($paymentForDisplayonly)))
    <section id="content5" class="tab-content fnn">
      <p>
        @include('Accountant.fundingReq.updatePage')
      </p>
    </section>
    @endif


  </div>


  <div class="tab_container" style=" padding-top: 8px;" disabled>
    <input class="fnn" name="tabs" disabled>
    <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
      <span>

        @include('Accountant.fundingReq.fundingReqInfo')

      </span>
    </label>
  </div>




  <div class="tab_container" style=" padding-top: 8px;" disabled>
    <input class="fnn" name="tabs" disabled>
    <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
      <span>

        <div class="row vertical-align">



          <div class="col-3"></div>

          @if ($followdate != null)
          <div class="col-3">
            <div class="form-group">
              <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
              <input disabled id="follow" name="follow" type="date" class="form-control" value="{{old('follow',$followdate->reminder_date)}}" autocomplete="follow">
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
              <input disabled id="follow1" name="follow1" type="time" class="form-control" value="{{ old('follow1',$followtime) }}" autocomplete="follow1">
            </div>
          </div>
          @else
          <div class="col-3">
            <div class="form-group">
              <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
              <input disabled id="follow" name="follow" type="date" class="form-control" autocomplete="follow" >
            </div>
          </div>
          <div class="col-3">
            <div class="form-group">
              <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
              <input disabled id="follow1" name="follow1" type="time" class="form-control"  autocomplete="follow1">
            </div>
          </div>
          @endif

          <div class="col-3"></div>

        </div>


      </span>
    </label>
  </div>




</form>
@endsection

@section('updateModel')
@include('Accountant.fundingReq.req_records')
@endsection

@section('confirmMSG')
@endsection
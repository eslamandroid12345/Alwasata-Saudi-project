@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
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
    width: 33.3%;
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

  .custom-control-label {
    position: relative;
    padding-left: 1.8rem;
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

  .asLink {
    cursor: pointer;
    color: blue;
    text-decoration: underline;
  }

  .asLink:hover {
    text-decoration: none;
    text-shadow: 1px 1px 1px #555;
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
</style>
@endsection

@section('customer')


<div class="tab_container" style=" padding-top: 8px;">
  <label class="fnnn" style="width: 100%; display: block;padding: 20px;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
    <span id="reqtypetitle"></span>
  </label>

</div>

<form action="{{ route('collaborator.addFund')}}" method="post" novalidate="novalidate">
  @csrf

  <div class="tab_container">
    <input class="fnn" id="tab1" type="radio" name="tabs" checked>
    <label class="fnnn" for="tab1"><i class="fa fa-user-circle-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }} </span></label>

    <input class="fnn" id="tab2" type="radio" name="tabs">
    <label class="fnnn" for="tab2"><i class="fa fa-building-o"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }} </span></label>

    <input class="fnn" id="tab3" type="radio" name="tabs">
    <label class="fnnn" for="tab3"><i class="	fa fa-shopping-cart"></i><span> {{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }} </span></label>


    <section id="content1" class="tab-content fnn">
      <p>
        @include('Collaborator.AddFundingReq.customerInfo')
      </p>
    </section>

    <section id="content2" class="tab-content fnn">
      <p>
        @include('Collaborator.AddFundingReq.realestateInfo')
      </p>
    </section>

    <section id="content3" class="tab-content fnn">
      <p>
        @include('Collaborator.AddFundingReq.fundingInfo')
      </p>
    </section>




  </div>

  <div class="tab_container" style=" padding-top: 8px;" disabled>
    <input class="fnn" id="footer" name="tabs" disabled>
    <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
      <span>

        @include('Collaborator.AddFundingReq.reqInfo')

      </span>
    </label>
  </div>

  <div class="tab_container" style=" padding-top: 8px;" disabled>
    <input class="fnn" id="footer" name="tabs" disabled>
    <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
      <span>

        <div class="row">
          <div class="col-4">

          </div>

          <div class="col-4">
            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Save') }}</button>
          </div>

          <div class="col-4">

          </div>

        </div>


      </span>
    </label>
  </div>

</form>
@endsection
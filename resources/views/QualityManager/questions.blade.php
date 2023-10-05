@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
@endsection

@section('css_style')

<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />
<link href="http://icheck.fronteed.com/skins/all.css?v=1.0.1" rel="stylesheet" />

<link rel="stylesheet" href="{{ url('css/loading-bar.css') }}" />
<style>
  .ldBar-label {
    color: #09f;
    font-family: 'varela round';
    font-size: 2.5em;
    font-weight: 900;
    /* height: 150px; */
  }

  .ldBar path.mainline {}

  /* styling of bar omitted */
  .clearfix:before,
  .clearfix:after {
    content: " ";
    display: table;
  }

  .clearfix:after {
    clear: both;
  }

  .afnan {
    color: white;
    width: 125px;
    background-color: #336699;
    text-align: center;
  }

  .afnan:hover {
    background-color: #538cc6
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
    /*width: 25%; */
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
</style>
@endsection

@section('customer')

<style>
  .que .h3 {
    background: linear-gradient(to right, #6699ff -4%, #336699 94%);
    color: #fff;
    padding: 10px;
    border-radius: 0 0 25px 25px;
    font-size: 20px;

  }

  .que {
    border: 2px solid #336699;
  }

  .span {

    /* border-bottom: 2px solid #28a745; */
    padding-bottom: 20px;
  }

  .im-buttons,
  h5 {
    display: inline-block;
  }

  .im-buttons label,
  h5 label {
    display: inline-block;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.65;
  }

  .im-buttons label span,
  h5 label span {
    display: inline-block;
  }

  .radio {
    margin: 16px 0;
    display: inline-block;
    cursor: pointer;
  }

  .radio input {
    display: none;
  }

  .radio input#a-button+span:after {
    border: #009933 2px solid;
  }

  .radio input#b-button+span:after {
    border: #cc0000 2px solid;
  }

  .radio input#c-button+span:after {
    border: #35cc62 2px solid;
  }

  .radio input+span {
    line-height: 22px;
    height: 22px;
    padding-left: 22px;
    position: relative;
  }

  .radio input+span:not(:empty) {
    padding-left: 30px;
    margin-left: 20px;
  }

  .radio input+span:before,
  .radio input+span:after {
    content: '';
    width: 22px;
    height: 22px;
    display: block;
    border-radius: 50%;
    left: 0;
    top: 0;
    position: absolute;
  }

  .radio input+span:before {
    transition: background 0.2s ease, transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 2);
  }

  .radio input+span#a-button:before {
    background: #009933;
  }

  .radio input+span#b-button:before {
    background: #cc0000;
  }

  .radio input+span#c-button:before {
    background: #35cc62;
  }

  .radio input+span:after {
    /* background: #222; */
    transform: scale(0.78);
    transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.4);
  }

  .radio input:checked+span:before {
    transform: scale(1.04);
  }

  .radio input:checked+span:after {
    transform: scale(0.4);
    transition: transform .3s ease;
  }

  .radio input#a-button:checked+span:before {
    /* background: #222; */
    border: 2px solid #009933;
  }

  .radio input#a-button:checked+span:after {
    background: #009933;
  }

  .radio input#b-button:checked+span:before {
    /* background: #222; */
    border: 2px solid #cc0000;
  }

  .radio input#b-button:checked+span:after {
    background: #cc0000;
  }

  .radio input#c-button:checked+span:before {
    /* background: #222; */
    border: 2px solid #35cc62;
  }

  .radio input#c-button:checked+span:after {
    background: #35cc62;
  }

  .radio:hover input+span:before {
    transform: scale(0.92);
  }

  .radio:hover input+span:after {
    transform: scale(0.74);
  }

  .radio:hover input:checked+span:after {
    transform: scale(0.4);
  }
</style>

<br>

<div id="msg2" class="alert alert-dismissible" style="display:none;">
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



<div id="sendingWarning" class="alert alert-info" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="sendingWarning1" class="alert alert-info" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="rejectWarning" class="alert alert-warning" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div id="rejectWarning1" class="alert alert-warning" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="archiveWarning" class="alert alert-dark" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div id="archiveWarning1" class="alert alert-dark" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="appWarning" class="alert alert-success" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="appWarning1" class="alert alert-success" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>





<input type="hidden" value="#" id="is_canceled">

<div class="tab_container" style=" padding-top: 8px;">
  <label class="fnnn" style="width: 100%; display: block;padding: 20px;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;">
    <div class="row">
      <div class="col-md-9 text-left">
        <h2 class="text-center" style="color:#336699"> الأسئلة</h2>
        <h4 class="text-center"> <span style="font-size: 1.17em;font-weight: bolder">{{$customerName}}</span> - {{$customerMobile}}</h4>
        <br>
        <!-- start right div col 9 -->
        <div style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;  overflow: hidden; padding: 0;text-decoration: none; cursor: pointer;">
          <!-- <span> -->


          <div class="row">

            <div class="col-4">
              <button type="button" id="reject" style="cursor: not-allowed" data-id="10" class="btn btn-danger btn-block">
                <h1 style=" color: #fff; margin-bottom: 15px; ">{{ $que_false}}</h1>
                إجابات (لا)
              </button>
            </div>

            <div class="col-4">
              <button type="button" id="approve" style="cursor: not-allowed" data-id="10" class="btn btn-info btn-block">
                <h1 style=" color: #fff; margin-bottom: 15px; ">{{ $que_true }}</h1>
                إجابات (نعم)
              </button>

            </div>


            <div class="col-4">
              <button type="submit" id="update" style="cursor: not-allowed" class="btn btn-success btn-block">
                <h1 style=" color: #fff; margin-bottom: 15px; ">{{ $que_not_answer }}</h1>
                <!-- <i class="fa fa-floppy-o"></i> -->
                اسئلة لم يتم الاجابة عنها
              </button>

            </div>
          </div>
        </div>
        <!-- </div> -->
        <!-- end div -->

        <!-- </span> -->

        <!-- </label> -->

        <br>



        <form method="post" id="form" action="{{ route('quality.manager.questions_post' ,['servID' => $id] )}}">
          @csrf


          <div class="col-12 span" style="z-index: 99999;width: 100%; ">

            @foreach($questions as $question)
            <div class="que">
              <h3 class="h3"> {{ $question->question }}</h3>
              <div class="row">
                <div class="col-2"></div>


                <input name="status" type="hidden" value="{{$status}}" />

                @if (!empty($serv_ques))


                @if($serv_ques->where('ques_id',$question->id)->first() != null)

                @if ($serv_ques->where('ques_id',$question->id)->first()->answer == 2)
                @if (($status == 0 || $status == 1 || $status == 2 || $status == 5  )&& auth()->user()->role != 9)
                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input name="check#{{$question->id}}" type="radio" id="a-button" checked="checked" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input name="check#{{$question->id}}" type="radio" id="b-button" value="1" /><span>لا</span>
                  </label>
                </div>
                @else
                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input disabled name="check#{{$question->id}}" type="radio" id="a-button" checked="checked" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input disabled name="check#{{$question->id}}" type="radio" id="b-button" value="1" /><span>لا</span>
                  </label>
                </div>
                @endif

                @elseif ($serv_ques->where('ques_id',$question->id)->first()->answer == 1)
                @if (($status == 0 || $status == 1 || $status == 2 || $status == 5 ) && auth()->user()->role != 9)
                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input name="check#{{$question->id}}" type="radio" id="a-button" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input name="check#{{$question->id}}" type="radio" id="b-button" checked="checked" value="1" /><span>لا</span>
                  </label>
                </div>
                @else
                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input disabled name="check#{{$question->id}}" type="radio" id="a-button" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input disabled name="check#{{$question->id}}" type="radio" id="b-button" checked="checked" value="1" /><span>لا</span>
                  </label>
                </div>
                @endif
                @endif


                @else
                @if (($status == 0 || $status == 1 || $status == 2 || $status == 5 )&& auth()->user()->role != 9)
                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input name="check#{{$question->id}}" type="radio" id="a-button" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input name="check#{{$question->id}}" type="radio" id="b-button" value="1" /><span>لا</span>
                  </label>
                </div>
                @else
                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input disabled name="check#{{$question->id}}" type="radio" id="a-button" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input disabled name="check#{{$question->id}}" type="radio" id="b-button" value="1" /><span>لا</span>
                  </label>
                </div>
                @endif
                @endif







                @else

                <div class="col-5">
                  <label class="radio" id="a-button">
                    <input name="check#{{$question->id}}" type="radio" id="a-button" value="2" /><span>نعم</span>
                  </label>
                </div>
                <div class="col-5">
                  <label class="radio" id="b-button">
                    <input name="check#{{$question->id}}" type="radio" id="b-button" checked="checked" value="1" /><span>لا</span>
                  </label>
                </div>

                @endif



              </div>
            </div>

            <br>

            @endforeach
            <!-- <span id="line"></span> -->
            <br>

            @if (($status == 0 || $status == 1 || $status == 2 || $status == 5) && auth()->user()->role != 9)

            <h2 class="text-center">
               <button type="submit" class="btn afnan">حفظ</button>
            </h2>
            @endif

          </div>

        </form>

      </div>

      <div class="col-md-3 ">
        <!-- circle -->
        <h2 style="color:#336699">الناتج</h2>
        <br>
        <div id="ldBar" class="ldBar label-center" style="font-size:13px; text-align:center;" data-value="{{ $result }}" data-preset="circle"></div>
      </div>

      <!-- </div> -->

    </div>
</div>







@endsection

@section('scripts')
<script src="http://icheck.fronteed.com/icheck.js?v=1.0.1"></script>
<script src="{{ url('js/loading-bar.js') }}"></script>



@endsection

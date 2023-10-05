@extends('layouts.content')


@section('css_style')

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
        width: 100%;
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
</style>
@endsection

@section('customer')

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

<div id="rejectWarning" class="alert alert-warning" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="archiveWarning" class="alert alert-dark" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>



<form action="{{ route('funding.manager.createPrepayment')}}" method="post" novalidate="novalidate">
    @csrf

    <div class="tab_container">
        <input class="fnn" id="tab1" type="radio" name="tabs" checked>
        <label class="fnnn" for="tab1"><i class="fa fa-credit-card"></i><span>   {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}   </span></label>





        <input value={{$id}} id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->

        <section id="content1" class="tab-content fnn">
            <p>
            @include('FundingManager.prepayement.preForm')
            </p>
        </section>


    </div>

    <div class="tab_container" style=" padding-top: 8px;" disabled>
        <input class="fnn" name="tabs" disabled>
        <label class="fnnn" style="width: 100%; display: block;background: #fff;color: #999;border-bottom: 2px solid #f0f0f0;" for="tab1">
            <span>

                <div class="row">



                    <div class="col-4">

                    </div>

                    <div class="col-4">
                        <button type="submit" id="update" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'create') }}</button>
                    </div>

                    <div class="col-4">

                    </div>

                </div>


            </span>
        </label>
    </div>



</form>
@endsection

@section('scripts')
<script>

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    //--------------------------
    $(document).ready(function() {

      

    });
//------------------------------------
function incresecalculate() {
        var check = document.getElementById("check").value;
        var real = document.getElementById("real").value;

        document.getElementById("incr").value = (check-real);
    }

    //------------------------------------
function preCoscalculate() {
        var prepaymentValue = parseInt (document.getElementById("preval").value);
        var presentage = parseInt (document.getElementById("prepre").value);

        document.getElementById("precos").value = prepaymentValue + (prepaymentValue * (presentage/100));
    }

    //---------------------------------------

    function showTsaheel() {
        var x = document.getElementById("tsaheeldiv");
        if (x.style.display === "none") {
            x.style.display = "block";
            document.getElementById("visa").value = 0;
            document.getElementById("carlo").value = 0;
            document.getElementById("perlo").value = 0;
            document.getElementById("realo").value = 0;
            document.getElementById("credban").value = 0;
            document.getElementById("other").value = 0;
            document.getElementById("debt").value = 0;
        } else {

            x.style.display = "none";
            document.getElementById("visa").value = "";
            document.getElementById("carlo").value = "";
            document.getElementById("perlo").value = "";
            document.getElementById("realo").value = "";
            document.getElementById("credban").value = "";
            document.getElementById("other").value = "";
            document.getElementById("debt").value = "";
            document.getElementById("morpre").value = "";
            document.getElementById("morcos").value = "";
            document.getElementById("propre").value = "";
            document.getElementById("procos").value = "";
            document.getElementById("valadd").value = "";
            document.getElementById("admfe").value = "";

        }
    }

    //-----------------------------------------------
    function debtcalculate() {
        var visa = parseInt (document.getElementById("visa").value);
        var car = parseInt (document.getElementById("carlo").value);

        var personal = parseInt (document.getElementById("perlo").value);
        var realEstat = parseInt (document.getElementById("realo").value);

        var credit = parseInt (document.getElementById("credban").value);
        var other = parseInt (document.getElementById("other").value);

        document.getElementById("debt").value = visa + car + personal + realEstat + credit + other;
        mortcalculate()
        profcalculate()
        
    }

        //-----------------------------------------------
        function mortcalculate() {
        var morpre = parseInt (document.getElementById("morpre").value);
        var debt = parseInt (document.getElementById("debt").value);



        document.getElementById("morcos").value = debt * (morpre/100);
    }

          //-----------------------------------------------
          function profcalculate() {
        var propre = parseInt (document.getElementById("propre").value);
        var debt = parseInt (document.getElementById("debt").value);



        document.getElementById("procos").value = debt * (propre/100);
    }


    ////////////////////////////////////////////
</script>
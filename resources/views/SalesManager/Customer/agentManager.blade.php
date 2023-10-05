@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}
@endsection

@section('css_style')
<style>
    /* Font */
    @import url('https://fonts.googleapis.com/css?family=Quicksand:400,700');

    /* Design */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html {
        background-color: #ecf9ff;
    }

    body {
        color: #272727;
        font-family: 'Quicksand', serif;
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0;
        padding: 1rem;
    }

    .main {
        max-width: 1200px;
        margin: 0 auto;
    }

    h2 {
        font-size: 24px;
        font-weight: 400;
        text-align: center;
    }

    img {
        height: auto;
        max-width: 100%;
        vertical-align: middle;
    }

    .btn {
        color: #ffffff;
        padding: 0.8rem;
        font-size: 14px;
        text-transform: uppercase;
        border-radius: 4px;
        font-weight: 400;
        display: block;
        width: 100%;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: transparent;
    }

    .btn:hover {
        background-color: rgba(255, 255, 255, 0.12);
    }

    .cards {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .cards_item {
        display: flex;
        padding: 1rem;
    }

    @media (min-width: 40rem) {
        .cards_item {
            width: 50%;
        }
    }

    @media (min-width: 56rem) {
        .cards_item {
            width: 33.3333%;
        }
    }

    .card {
        background-color: white;
        border-radius: 0.25rem;
        box-shadow: 0 20px 40px -14px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .card_content {
        padding: 1rem;
        background: linear-gradient(to bottom left, #EF8D9C 40%, #FFC39E 100%);
    }

    .card_title {
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: capitalize;
        margin: 0px;
    }

    .card_text {
        color: #ffffff;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
        font-weight: 400;
    }


    .flip-card {
        background-color: transparent;
        width: 300px;
        height: 300px;
        perspective: 1000px;
    }

    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    }

    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }

    .flip-card-front,
    .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
    }

    .flip-card-front {
        background: linear-gradient(to bottom left, #225977 0%, #3995c6 100%);
        color: black;
    }

    p.flip-card-front{
        font-weight: bold;
    }

    .flip-card-back {
        background-color: whitesmoke;
        color: white;
        transform: rotateY(180deg);
    }
</style>
@endsection



@section('customer')

@if(!empty($message))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ $message }}
</div>
@endif

@if ( session()->has('message') )
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if ( session()->has('message2') )
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>



<div class="main">

    <h2>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}:</h2>

    <br>

    @if ($check == 0)

    @php ($variable = 'no')
    @foreach ($requests as $request)

    @if ($request->count() != 0)
    <!-- each index not empty-->
    @php ($variable = 'yes')
    @endif

    @endforeach

    @if ($variable == 'yes')


    <ul class="cards">

        @foreach($requests as $request => $reqs)
        @foreach($reqs as $req)
        <li class="cards_item">
            <div class="flip-card card">
                <div class="flip-card-inner">

                    <div class="flip-card-front">
                        <br><br>
                        <h3 style="color:whitesmoke;font-size: 170%; font-family: 'Droid Arabic Kufi', serif;">{{$req->name}}</h3>
                        <p style="color:#b0d4e8;font-weight: bold; font-size: 170%;font-family: 'Droid Arabic Kufi', serif;">{{$reqs->count()}}</p>
                        <p style="color:whitesmoke;font-weight: bold; font-size: 150%;font-family: 'Droid Arabic Kufi', serif;">{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }}</p>
                        <img style=" border-radius: 50%;" src="{{ url('interface_style/images/icon/bg_whoweare.png') }}" alt="Wsata" />
                    </div>

                    <div class="flip-card-back">
                        <br><br>
                        <h3 style="color:#235C7A;font-family: 'Droid Arabic Kufi', serif;">{{$req->name}}</h3>
                        <br>
                        <p style="color:#1d4a63;font-size: 130%; font-family: 'Droid Arabic Kufi', serif;">{{ MyHelpers::admin_trans(auth()->user()->id,'Recived Requests') }} : {{ MyHelpers::reciveReqCountSalesAgent($req->user_id)}}</p>
                        <p style="color:#1d4a63;font-size: 130%; font-family: 'Droid Arabic Kufi', serif;">{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Requests') }} : {{ MyHelpers::archReqCountSalesAgent($req->user_id)}}</p>
                        <p style="color:#1d4a63;font-size: 130%; font-family: 'Droid Arabic Kufi', serif;">{{ MyHelpers::admin_trans(auth()->user()->id,'Completed Requests') }} : {{ MyHelpers::compReqCountSalesAgent($req->user_id)}}</p>
                        <br>

                        <a href="{{ route('sales.manager.agentCustomer',$req->user_id)}}">
                        <button type="button" style ="background-color:#225977;" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'More') }}</button>
                        </a>

                        <img src="{{ url('interface_style/images/icon/bg_whoweare.png') }}" alt="Wsata" />
                    </div>
                </div>
            </div>
        </li>
        @break
        @endforeach
        @endforeach

    </ul>

    @else
    <div class="middle-screen">
        <h3 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Sales Agent') }}</h3>
    </div>

    @endif



    @else
    <div class="middle-screen">
        <h3 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Sales Agent') }}</h3>
    </div>

    @endif


</div>

@endsection




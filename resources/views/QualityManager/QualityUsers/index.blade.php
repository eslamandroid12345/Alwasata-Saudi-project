@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Quality Users') }}
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

        <h2>{{ MyHelpers::admin_trans(auth()->user()->id,'Quality Users') }}:</h2>

        <br>

        <ul class="cards">

            @foreach($myusers as $key => $user)
                @php($quality_reqs_count = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('users as others', 'others.id', 'quality_reqs.user_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->where('quality_reqs.user_id',$user->id)
                ->where('quality_reqs.allow_recive', 1)
                ->count())

                @php($quality_reqs_followed_count = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')
                ->join('users as others', 'others.id', 'quality_reqs.user_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->where('quality_reqs.user_id', $user->id)->where('quality_reqs.allow_recive', 1)->whereIn('quality_reqs.status', [0, 1, 2])->where('quality_reqs.is_followed', 1)->count())
                @php($quality_reqs_recevied_count = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')
        ->join('users as others', 'others.id', 'quality_reqs.user_id')
        ->join('customers', 'customers.id', '=', 'requests.customer_id')
        ->where('quality_reqs.user_id', $user->id)->where('quality_reqs.allow_recive', 1)->whereIn('quality_reqs.status', [0, 1, 2])->where('quality_reqs.is_followed', 0)->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')->count())
                @php($quality_reqs_arch_count = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')
        ->join('users as others', 'others.id', 'quality_reqs.user_id')
        ->join('customers', 'customers.id', '=', 'requests.customer_id')
        ->where('quality_reqs.user_id', $user->id)->where('quality_reqs.allow_recive', 1)->where('quality_reqs.status', 5)->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')->count())
                @php($quality_reqs_completed_count = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->where('quality_reqs.allow_recive', 1)->where('quality_reqs.status', 3)->select('quality_reqs.id', 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality', 'quality_reqs.status',
            'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at')->where('quality_reqs.user_id', $user->id)->count())
                <li class="cards_item">
                    <div class="flip-card card">
                        <div class="flip-card-inner">

                            <div class="flip-card-front">
                                <br><br>
                                <h3 style="color:whitesmoke;font-size: 170%;">{{$user->name}}</h3>
                                <p style="color:#b0d4e8;font-weight: bold; font-size:300%;">{{$quality_reqs_count}}</p>
                                <p style="color:whitesmoke;font-weight: bold; font-size: 150%;">{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }}</p>
                                <img style=" border-radius: 50%;" src="{{ url('interface_style/images/icon/bg_whoweare.png') }}" alt="Wsata" />
                            </div>

                            <div class="flip-card-back">
                                <br>
                                <h3 style="color:#235C7A;">{{$user->name}}</h3>

                                <table class="table">

                                    <tbody>

                                    <tr>
                                        <td>الطلبات المتابعه</td>
                                        <td style="font-weight: bold">{{$quality_reqs_followed_count}}</td>
                                    </tr>
                                    <tr>
                                        <td>الطلبات المستلمة</td>
                                        <td style="font-weight: bold">{{$quality_reqs_recevied_count}}</td>
                                    </tr>
                                    <tr>
                                        <td>الطلبات المؤرشفة</td>
                                        <td style="font-weight: bold">{{$quality_reqs_arch_count}}</td>
                                    </tr>
                                    <tr>
                                        <td>الطلبات المكتملة</td>
                                        <td style="font-weight: bold">{{$quality_reqs_completed_count}}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                {{-- <a href="{{ route('sales.manager.agentCustomer',$user->id)}}">
                                     <button type="button" style ="background-color:#225977;" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'More') }}</button>
                                 </a>
--}}
                                <img src="{{ url('interface_style/images/icon/bg_whoweare.png') }}" alt="Wsata" />
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach

        </ul>


    </div>

@endsection




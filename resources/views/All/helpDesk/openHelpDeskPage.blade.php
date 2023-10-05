@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'tasks') }}
@endsection

@section('css_style')

    <style>
        .fnn {
            background-color: rgba(49, 217, 19, 0.2);
        }


        .fnfn {
            background: rgb(64, 84, 123, 0.7);
            color: white;
        }

        .fnfn:hover {
            background: rgb(64, 84, 123);
        }

        .fnfnfn {
            background: rgb(61, 92, 92, 0.7);
            color: white;
        }

        .fnfnfn:hover {
            cursor: no-drop;
        }


        .fn {
            background: rgba(30, 70, 116, 0.5);
            color: white;
        }

        .fn:hover {
            background: rgba(30, 70, 116, 0.8);
        }

        .afnan {
            background: rgba(30, 70, 116, 0.5);
            color: #FFFFFF;
            padding: 25px 0;
            border-style: double;
            border-color: #1e689c;

        p {
            font-family: 'Allura';
            color: rgba(255, 255, 255, .2);
            margin-bottom: 0;
            font-size: 60px;
            margin-top: -30px;

        }

        }

        .cd-container {
            width: 90%;
            max-width: 1080px;
            margin: 0 auto;
        / / background: #2B343A;
            padding: 0 10%;
            border-radius: 2px;
        }

        .cd-container::after {
            content: '';
            display: table;
            clear: both;
        }

        /* --------------------------------

      Main components

      -------------------------------- */


        #cd-timeline {
            position: relative;
            padding: 2em 0;
            margin-top: 2em;
            margin-bottom: 2em;
        }

        #cd-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 25px;
            height: 100%;
            width: 4px;
            background: rgba(30, 70, 116, 0.5);
        }

        @media only screen and (min-width: 1170px) {
            #cd-timeline {
                margin-top: 3em;
                margin-bottom: 3em;
            }

            #cd-timeline::before {
                left: 50%;
                margin-left: -2px;
            }
        }

        .cd-timeline-block {
            position: relative;
            margin: 2em 0;
        }

        .cd-timeline-block:after {
            content: "";
            display: table;
            clear: both;
        }

        .cd-timeline-block:first-child {
            margin-top: 0;
        }

        .cd-timeline-block:last-child {
            margin-bottom: 0;
        }

        @media only screen and (min-width: 1170px) {
            .cd-timeline-block {
                margin: 4em 0;
            }

            .cd-timeline-block:first-child {
                margin-top: 0;
            }

            .cd-timeline-block:last-child {
                margin-bottom: 0;
            }
        }

        .cd-timeline-img {
            position: absolute;
            top: 8px;
            left: 12px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            box-shadow: 0 0 0 2px #40547b, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
        }

        .cd-timeline-img {
            background: #567496;
        }

        @media only screen and (min-width: 1170px) {
            .cd-timeline-img {
                width: 30px;
                height: 30px;
                left: 50%;
                margin-left: -15px;
                margin-top: 15px;
                /* Force Hardware Acceleration in WebKit */
                -webkit-transform: translateZ(0);
                -webkit-backface-visibility: hidden;
            }
        }

        .cd-timeline-content {
            position: relative;
            margin-left: 60px;
            margin-right: 30px;
            border-radius: 4px;
            padding: 1em;


        }

        .cd-timeline-content:after {
            content: "";
            display: table;
            clear: both;
        }

        .cd-timeline-content h2 {
            color: rgba(255, 255, 255, .9);
            margin-top: 0;
            margin-bottom: 5px;
        }

        .cd-timeline-content p,
        .cd-timeline-content .cd-date {
            color: rgba(255, 255, 255, .7);
            font-size: 13px;
            font-size: 0.8125rem;
        }

        .cd-timeline-content .cd-date {
            display: inline-block;
        }

        .cd-timeline-content p {
            margin: 1em 0;
            line-height: 1.6;
        }

        .cd-timeline-content::before {
            content: '';
            position: absolute;
            top: 16px;
            left: 100%;
            height: 0;
            width: 0;
            border: 7px solid transparent;
            border-right: 7px solid #333C42;
        }

        @media only screen and (min-width: 768px) {
            .cd-timeline-content h2 {
                font-size: 22px;
                font-size: 1.35rem;
                color: #1d406e;
            }

            .cd-timeline-content p {
                font-size: 16px;
                font-size: 0.9rem;
                color: white;
            }

            .cd-timeline-content .cd-read-more,
            .cd-timeline-content .cd-date {
                font-size: 14px;
                font-size: 0.875rem;
            }
        }

        @media only screen and (min-width: 1170px) {
            .cd-timeline-content {
                color: white;
                margin-left: 0;
                padding: 1.6em;
                width: 36%;
                float: left;
                margin: 0 5%
            }

            .cd-timeline-content::before {
                top: 24px;
                left: 100%;
                border-color: transparent;
                border-left-color: #40547b;
                /*arrow of left box */
            }

            .cd-timeline-content .cd-date {
                position: absolute;
                color: #40547b;
                width: 100%;
                left: 92%;
                top: 6px;
                font-size: 12px;
                font-size: 1rem;
            }

            .cd-timeline-block:nth-child(even) .cd-timeline-content {
                float: right;
            }

            .cd-timeline-block:nth-child(even) .cd-timeline-content::before {
                top: 24px;
                left: auto;
                right: 100%;
                border-color: transparent;
                border-right-color: #40547b;
                /*arrow of right box */
            }


            .cd-timeline-block:nth-child(even) .cd-timeline-content .cd-date {
                left: auto;
                right: 92%;
                text-align: right;
            }
        }
    </style>
@endsection

@section('customer')


    <br>


    @if (Session::has('errors'))
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br/>
            @endforeach
        </div>
    @endif

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

    <div class="afnan">
        <div class="container text-center" style="text-align:center;">
            <h3>{{$helpDesk->name}}</h3>
            <p> {{$helpDesk->mobile}}</p>
            {{--@if($helpDesk->customer && $helpDesk->customer->request)
                <span class="item pointer" data-id="{{$helpDesk->customer->request->id}}" data-toggle="tooltip" data-placement="top" title="">
                        <a href="{{route('admin.fundingRequest',$helpDesk->customer->request->id)}}" target="_blank">فتح الطلب </a>
          </span>
            @endif--}}
        </div>

        @if($helpDesk->status == 2)
            <p style="background-color: rgb(45, 134, 45,0.5);float:right;"> الطلب مكتمل</p>
        @elseif( $helpDesk->status == 3)
            <p style="background-color:rgb(119, 119, 60,0.5);float:right;">الطلب ملغي</p>
        @endif

    </div>


    <br><br>
    <div style="text-align: center;">

        @if ($reqInfo && $reqInfo != 'طلب معلق')
            @if ($reqInfo->type == null || $reqInfo->type == 'شراء' || $reqInfo->type == 'رهن')
                <a href="{{route('admin.fundingRequest',$reqInfo->id)}}">
                    @else
                        <a href="{{route('admin.morPurRequest',$reqInfo->id)}}">
                            @endif
                            <button type="button" class=" btn fnfn">
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Open') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}
                            </button>
                        </a>
                        @elseif ($reqInfo != null && $reqInfo != false && $reqInfo == 'طلب معلق')
                            <button type="button" class=" btn fnfnfn" style="cursor: no-drop;">
                                طلب مُعلق
                            </button>
            @endif
    </div>

    <section id="cd-timeline" class="cd-container">
        <div class="cd-timeline-block">
            <div class="cd-timeline-img cd-movie"></div>
            <div class="cd-timeline-content" style=" background: rgb(86, 115, 148,0.6);">
                <h2>{{$helpDesk->name}}</h2>
                <p>{{$helpDesk->descrebtion}} </p>
                <div class="row">
                    @foreach ($helpDesk->Image()->get() as $f)
                        @php
                            // try{

                        $file2 = public_path('uploads/'.str_replace(url('/uploads').'/', '',$f->image_path));
                            // dd($file2);
                            $type = exif_imagetype($file2);
                            switch($type) {
                            case IMG_GIF:
                                $type = 'image/gif';
                                break;
                            case IMG_JPG:
                                $type = 'image/jpg';
                                break;
                            case IMG_JPEG:
                                $type = 'image/jpeg';
                                break;
                            case IMG_PNG:
                                $type = 'image/png';
                                break;
                            case IMG_WBMP:
                                $type = 'image/wbmp';
                                break;
                            case IMG_XPM:
                                $type = 'image/xpm';
                                break;
                            default:
                                $type = 'unknown';
                            }
                            $file = url($f->image_path);
                            if(    str_contains($type, 'image')    )
                            {
                                $type = 'image';
                            }
                            if ($type == 'image') {
                                    echo '<div class="col-md-4"><a href="'.$file.'" target="_blank"><img src="'.$file.'" class="image-responsive" style="height:80px; width:80px;" /></a></div>';
                            }else{
                                    echo '<div class="col-md-4"><a  href="'.$file.'" target="_blank"><i class="fa fa-file" aria-hidden="true" style=font-size:54px;></i>
                                        </a></div>';
                            }
                        // }catch(\Exception $e){
                        // }

                        @endphp
                    @endforeach
                </div>
                <span class="cd-date" style="text-align:center">
                    {{Carbon\Carbon::parse($helpDesk->created_at )->format('Y-m-d')}}
                    <br>
                    {{Carbon\Carbon::parse($helpDesk->created_at )->format('H:i:s')}}
                </span>
            </div>
        </div>

        @foreach (\App\helpDesk::where('parent_id', $helpDesk->id)->get() as $help_item)
            @if ($help_item->name != null)
                @include('Admin.helpDesk.left_card',['left_item' => $help_item])
            @else
                @include('Admin.helpDesk.right_card',['right_item' => $help_item])

            @endif
        @endforeach

        @if ($helpDesk->replay != null && false)
            <div class="cd-timeline-block">
                <div class="cd-timeline-img cd-movie"></div> <!-- cd-timeline-img -->

                <div class="cd-timeline-content" style=" background: rgb(30, 101, 149,0.6);">
                    <h2> {{$helpDesk->username}}</h2>

                    <p>
                        {{$helpDesk->replay}}
                    </p>

                    <span class="cd-date" style="text-align:center">
                        {{Carbon\Carbon::parse($helpDesk->date_replay )->format('Y-m-d')}}
                        <br>
                        {{Carbon\Carbon::parse($helpDesk->date_replay )->format('H:i:s')}}
                    </span>
                </div>
            </div>
        @endif
    </section>

    @if (/*$helpDesk->replay == null && */($helpDesk->status==0 || $helpDesk->status==1))
        <form action="{{route('all.postReplayHelpDesk')}}" method="post" class="">
            @csrf
            <input type="hidden" name="reqID" value="{{$helpDesk->id}}">
            <input type="hidden" name="email" value="{{$helpDesk->email}}">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <div class="card text-center">
                        <div class="card-header" style="color: rgba(30, 70, 116,0.8)">
                            محتوى الرد
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <textarea class="form-control" rows="5" id="replay" name="replay"></textarea>
                                @if ($errors->has('replay'))
                                    <span class="help-block">
              <strong style="color:red ;font-size:10pt">{{ $errors->first('replay') }}</strong>
            </span>
                                @endif
                            </p>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-4"></div>

                                <div class="col-4 form-group">
                                    <button type="submit" class="btn fn btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
                                </div>

                                <div class="col-4"></div>
                            </div>
                        </div>

                        <div class="card-footer" style="display: none;">
                            <div class="row">

                                <div class="col-11"></div>

                                @if($helpDesk->status==0 || $helpDesk->status==1 )
                                    <a href="{{route('admin.canceleHelpDesk',$helpDesk->id)}}">
                                        <button type="button" class="btn btn-outline-danger">
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Cancele') }}
                                        </button>
                                    </a>
                                @else
                                    <button style="cursor: not-allowed" disabled type="button" class="btn btn-outline-danger">
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Cancele') }}
                                    </button>

                                @endif

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-2"></div>
            </div>

        </form>
    @endif


@endsection

@section('scripts')


@endsection

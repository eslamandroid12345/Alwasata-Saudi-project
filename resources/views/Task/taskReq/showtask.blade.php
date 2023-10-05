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

        }

        p {
            font-family: 'Allura';
            color: rgba(255, 255, 255, .2);
            margin-bottom: 0;
            font-size: 60px;
            margin-top: -30px;

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

            @if($tasks->sentid == auth()->user()->id)
                <h3>{{$tasks->recname}}</h3>

                @if($tasks->recrole == 0)
                    <p>استشاري المبيعات</p>
                @elseif($tasks->recrole == 1)
                    <p>مدير المبيعات</p>
                @elseif($tasks->recrole == 2)
                    <p>مدير التمويل</p>
                @elseif($tasks->recrole == 3)
                    <p>مدير الرهن</p>
                @elseif($tasks->recrole == 4)
                    <p>المدير العام </p>
                @elseif($tasks->recrole == 5)
                    <p> @if ($tasks->recname =='الجودة') قسم الجودة @else قسم المتابعة @endif </p>
                @elseif($tasks->recrole == 6)
                    <p> متعاون</p>
                @elseif($tasks->recrole == 7)
                    <p> مدير النظام</p>
                @endif


            @else

                @if ($tasks->sentrole == 5 && (auth()->user()->role == 7 || auth()->user()->role == 4))
                    <h2>{{$tasks->sentname}}</h2>
                @elseif ($tasks->sentrole == 5 && (auth()->user()->role != 7 && auth()->user()->role != 4))
                    <h2></h2>
                @else
                    <h2>{{$tasks->sentname}}</h2>
                @endif


                @if($tasks->sentrole == 0)
                    <p>استشاري المبيعات</p>
                @elseif($tasks->sentrole == 1)
                    <p>مدير المبيعات</p>
                @elseif($tasks->sentrole == 2)
                    <p>مدير التمويل</p>
                @elseif($tasks->sentrole == 3)
                    <p>مدير الرهن</p>
                @elseif($tasks->sentrole == 4)
                    <p>المدير العام </p>
                @elseif($tasks->sentrole == 5)
                    <p>  @if ($tasks->sentname =='الجودة') قسم الجودة @else قسم المتابعة @endif </p>
                @elseif($tasks->sentrole == 6)
                    <p> متعاون</p>
                @elseif($tasks->sentrole == 7)
                    <p> مدير النظام</p>
                @endif

            @endif

        </div>

        @if($tasks->status == 3)
            <p style="background-color: rgb(45, 134, 45,0.5);float:right;"> {{ MyHelpers::admin_trans(auth()->user()->id,'The task is completed') }}</p>
        @elseif( $tasks->status == 4)
            <p style="background-color: rgb(255, 51, 51,0.5);float:right;"> {{ MyHelpers::admin_trans(auth()->user()->id,'The task is not completed') }}</p>
        @elseif( $tasks->status == 5)
            <p style="background-color:rgb(119, 119, 60,0.5);float:right;">{{ MyHelpers::admin_trans(auth()->user()->id,'The task is canceled') }}</p>
        @endif

    </div>

    <br>
    <h4 class="text-center" style="color:#1d406e"><span style="font-size: 1.17em;font-weight: bolder">{{$reqInfo->name}}</span> - {{$reqInfo->mobile}}</h4>
    <br>
    <div style="text-align: center;">
        @if ($sentrole ==5)

            @if(auth()->user()->role == 0)
                <a href="{{route('agent.fundingRequest',$reqInfo->req_id)}}"/>
            @elseif(auth()->user()->role == 1)
                <a href="{{route('sales.manager.fundingRequest',$reqInfo->req_id)}}"/>
            @elseif(auth()->user()->role == 2)
                <a href="{{route('funding.manager.fundingRequest',$reqInfo->req_id)}}"/>
            @elseif(auth()->user()->role == 3)
                <a href="{{route('mortgage.manager.fundingRequest',$reqInfo->req_id)}}"/>
            @elseif(auth()->user()->role == 4)
                <a href="{{route('general.manager.fundingRequest',$reqInfo->req_id)}}"/>
            @elseif(auth()->user()->role == 5)
                <a href="{{route('quality.manager.fundingRequest',$tasks->req_id)}}"/>
            @elseif(auth()->user()->role == 7)
                <a href="{{route('admin.fundingRequest',$reqInfo->req_id)}}"/>
            @elseif(auth()->user()->role == 13)
                <a href="{{route('V2.BankDelegate.request.show',$reqInfo->id)}}">
            @elseif(auth()->user()->role == 11)
                <a href="{{route('training.fundingRequest',$reqInfo->req_id)}}"/>
            @endif
        @else

            @if(auth()->user()->role == 0)
                <a href="{{route('agent.fundingRequest',$reqInfo->id)}}"/>
            @elseif(auth()->user()->role == 1)
                <a href="{{route('sales.manager.fundingRequest',$reqInfo->id)}}"/>
            @elseif(auth()->user()->role == 2)
                <a href="{{route('funding.manager.fundingRequest',$reqInfo->id)}}"/>
            @elseif(auth()->user()->role == 3)
                <a href="{{route('mortgage.manager.fundingRequest',$reqInfo->id)}}"/>
            @elseif(auth()->user()->role == 4)
                <a href="{{route('general.manager.fundingRequest',$reqInfo->id)}}"/>
            @elseif(auth()->user()->role == 5)
                <a href="{{route('quality.manager.fundingRequest',$tasks->id)}}"/>
            @elseif(auth()->user()->role == 7)
                <a href="{{route('admin.fundingRequest',$reqInfo->id)}}"/>
            @elseif(auth()->user()->role == 13)
                <a href="{{route('V2.BankDelegate.request.show',$reqInfo->id)}}">
            @elseif(auth()->user()->role == 11)
                <a href="{{route('training.fundingRequest',$reqInfo->id)}}">
                    @endif
                    @endif
                    <button type="button" class=" btn fnfn">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Open') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}
                    </button>
                </a>
    </div>


    <section id="cd-timeline" class="cd-container">

        @foreach($task_contents as $task_content)
            <div class="cd-timeline-block">
                <div class="cd-timeline-img cd-movie"></div> <!-- cd-timeline-img -->

                <div class="cd-timeline-content" style=" background: rgb(86, 115, 148,0.6);">
                    @if ($tasks->sentrole == 5 && (auth()->user()->role == 7 || auth()->user()->role == 4))
                        <h2>{{$tasks->sentname}}</h2>
                    @elseif ($tasks->sentrole == 5 && (auth()->user()->role != 7 && auth()->user()->role != 4))
                        <h2>{{$tasks->sentname}}</h2>
                    @else
                        <h2>{{$tasks->sentname}}</h2>
                    @endif
                    <p>
                        {{$task_content->content}}
                    </p>
                    <span class="cd-date" style="text-align:center">
        {{Carbon\Carbon::parse($task_content->date_of_content )->format('Y-m-d')}}
        <br>
        {{Carbon\Carbon::parse($task_content->date_of_content )->format('H:i:s')}}
      </span>
                </div> <!-- cd-timeline-content -->
            </div> <!-- cd-timeline-block -->


            @if ($task_content->user_note != null)
                <div class="cd-timeline-block">
                    <div class="cd-timeline-img cd-movie"></div> <!-- cd-timeline-img -->

                    <div class="cd-timeline-content" style=" background: rgb(30, 101, 149,0.6);">
                        <h2> {{$tasks->recname}}</h2>

                        <p>
                            {{$task_content->user_note}}
                        </p>

                        <span class="cd-date" style="text-align:center">
        {{Carbon\Carbon::parse($task_content->date_of_note )->format('Y-m-d')}}
        <br>
        {{Carbon\Carbon::parse($task_content->date_of_note )->format('H:i:s')}}
      </span>
                    </div> <!-- cd-timeline-content -->
                </div> <!-- cd-timeline-block -->
            @endif

        @endforeach
    </section> <!-- cd-timeline -->



    <!--For Content-->
    @if ($tasks->sentid == auth()->user()->id)
        <form action="{{ route('all.update_users_task_content')}}" method="post" class="">
        @csrf

        <!--Task ID-->
            <input type="hidden" name="id" value="{{$id}}">

        @endif
        <!--End Content-->

            <!--For user_note-->
            @if ($tasks->recid == auth()->user()->id)
                <form action="{{ route('all.update_users_task_note')}}" method="post" class="">
                @csrf

                <!--Task_content ID-->
                    <input type="hidden" name="taskId" value="{{$id}}">
                    <input type="hidden" name="id" value="{{$task_content_last->id}}">

                @endif
                <!--End user_note-->

                    <div class="row">
                        <div class="col-sm-2"></div>

                        @if($tasks->status==0 || $tasks->status==1 ||$tasks->status==2)

                            <div class="col-sm-8">
                                <div class="card text-center">

                                    @if ($tasks->sentid == auth()->user()->id)
                                        @if ($task_content_last->user_note != null)

                                            <div class="card-header" style="color: rgba(30, 70, 116,0.8)">
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Content') }}
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'the task') }}
                                            </div>

                                            <div class="card-body">
                                                <p class="card-text">
                                                    <textarea class="form-control" rows="5" id="content" name="content"></textarea>
                                                    @if ($errors->has('content'))
                                                        <span class="help-block">
                <strong style="color:red ;font-size:10pt">{{ $errors->first('content') }}</strong>
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

                                        @endif
                                    @endif



                                    @if ($tasks->recid == auth()->user()->id)
                                        @if ($task_content_last->user_note == null)

                                            <div class="card-header" style="color: rgba(30, 70, 116,0.8)">
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Content') }}
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'the replay') }}
                                            </div>

                                            <div class="card-body">
                                                <p class="card-text">
                                                    <textarea class="form-control" rows="5" id="user_note" name="user_note"></textarea>
                                                    @if ($errors->has('user_note'))
                                                        <span class="help-block">
                <strong style="color:red ;font-size:10pt">{{ $errors->first('user_note') }}</strong>
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

                                        @endif
                                    @endif



                                    @if ($tasks->sentid == auth()->user()->id)
                                        <div class="card-footer">
                                            <div class="row">

                                                @if($tasks->status==2)

                                                    <div class="col-3">
                                                        <a href="{{route('all.completeTask',$id)}}" class="btn-block btn-success">
                                                            <button type="button" class="btn" style="color:white;">
                                                                {{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}
                                                            </button>
                                                        </a>
                                                    </div>

                                                    <div class="col-3">
                                                        <a href="{{route('all.notcompleteTask',$id)}}" class="btn-block btn-danger">
                                                            <button type="button" class="btn" style="color:white;">
                                                                {{ MyHelpers::admin_trans(auth()->user()->id,'not completed') }}
                                                            </button>
                                                        </a>
                                                    </div>

                                                @else

                                                    <div class="col-3">

                                                        <button style="cursor: not-allowed" disabled type="button" class="btn btn-block btn-success" style="color:white;">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}
                                                        </button>

                                                    </div>

                                                    <div class="col-3">
                                                        <button style="cursor: not-allowed" disabled type="button" class=" btn btn-block btn-danger" style="color:white;">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'not completed') }}
                                                        </button>
                                                    </div>

                                                @endif

                                                <div class="col-4"></div>

                                                @if($tasks->status==0 || $tasks->status==1)
                                                    <a href="{{route('all.canceleTask',$id)}}">
                                                        <button type="button" class="btn btn-outline-danger">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Cancele') }}
                                                        </button>
                                                    </a>
                                                @else
                                                    <a href="{{route('all.canceleTask',$id)}}">
                                                        <button style="cursor: not-allowed" disabled type="button" class="btn btn-outline-danger">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Cancele') }}
                                                        </button>
                                                    </a>
                                                @endif

                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        @endif

                        <div class="col-sm-2"></div>
                    </div>

                </form>



@endsection

@section('scripts')


@endsection

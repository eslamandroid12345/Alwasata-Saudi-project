@extends('themes.theme1.layouts.content')

@php
    $new_theme = true;
@endphp

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}
@endsection
@section('nav_actions')
    @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
        <div class="table-dropdown">
            <div class="dropdown">
                <button class="btn btn-primary" data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
                    <path
                        id="menu"
                        d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                        transform="translate(-14 -39)"
                        fill="#fff"
                    ></path>
                    </svg>
                </button>
                <ul class="dropdown-menu">
                    @if ($purchaseCustomer->is_stared == 0)
                    <li>
                        <a class="dropdown-item" href="{{ route('agent.manageRequest',['id'=> $id , 'action'=> 'stared'])}}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="21" height="20.02" viewBox="0 0 21 20.02">
                            <path
                                id="Icon_feather-star"
                                data-name="Icon feather-star"
                                d="M13,3l3.09,6.26L23,10.27l-5,4.87,1.18,6.88L13,18.77,6.82,22.02,8,15.14,3,10.27,9.91,9.26Z"
                                transform="translate(-2.5 -2.5)"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                            ></path>
                            </svg>
                            <span class="font-medium">اضفته الى الطلبات المميزة</span>
                        </a>
                    </li>
                    @endif
                    @if ($purchaseCustomer->is_followed == 0)
                    <li>
                        <a class="dropdown-item" href="{{ route('agent.manageRequest',['id'=> $id , 'action'=> 'followed'])}}">
                            <svg class="ms-3" id="Group_11701" data-name="Group 11701" xmlns="http://www.w3.org/2000/svg" width="18.284" height="19.02" viewBox="0 0 18.284 19.02">
                            <path id="Path_4001" data-name="Path 4001" d="M288.5,245.158l-8.952,5.168-.194.112-.191-.115-8.571-5.168.392-.651,8.38,5.053,8.758-5.056Z" transform="translate(-270.411 -244.449)" fill="#212121"></path>
                            <path
                                id="Path_4002"
                                data-name="Path 4002"
                                d="M255.474,240.125h17.9v10.149h-11.5l.174-.174a1.621,1.621,0,0,0,.284-.384,1.66,1.66,0,0,0,.091-.2h10.194v-8.627H255.854v8.627h.357a7,7,0,0,0,.2.761h-1.315V240.125h.38Z"
                                transform="translate(-255.093 -240.125)"
                                fill="#212121"
                            ></path>
                            <path
                                id="Path_4003"
                                data-name="Path 4003"
                                d="M386.083,659.276a1.03,1.03,0,0,1-.046.479,1.072,1.072,0,0,1-.26.407h0l-1.875,1.861a1.308,1.308,0,0,1-.206.184,1.607,1.607,0,0,1-.244.144,1.9,1.9,0,0,1-.255.1,1.632,1.632,0,0,1-.261.06l-.051,0h0s0,0,0,0l-.051,0q-.045,0-.105.007l-.116,0c-2.1,0-4.461-1.358-6.428-3.2-2.17-2.03-3.874-4.667-4.2-6.715q-.031-.2-.047-.36t-.017-.307h0v-.011h0c0-.023,0-.04,0-.054s0-.046,0-.052c.006-.105.009-.171.011-.2s0-.05,0-.067l0-.053a1.631,1.631,0,0,1,.06-.261,1.906,1.906,0,0,1,.1-.255,1.6,1.6,0,0,1,.144-.244,1.312,1.312,0,0,1,.17-.193h0l1.889-1.889a1.074,1.074,0,0,1,.33-.228.971.971,0,0,1,.393-.082h0a.938.938,0,0,1,.291.044.9.9,0,0,1,.263.138,1.341,1.341,0,0,1,.183.165,1.4,1.4,0,0,1,.15.2l.016.027,1.515,2.874h0a1.053,1.053,0,0,1,.126.37,1.115,1.115,0,0,1-.015.39,1.321,1.321,0,0,1-.124.35,1.241,1.241,0,0,1-.22.3h0l-.643.643a1.744,1.744,0,0,0,.055.17,2.98,2.98,0,0,0,.146.321v0a8.073,8.073,0,0,0,1.452,1.875,8.228,8.228,0,0,0,1.881,1.465h0q.159.081.263.123a.887.887,0,0,0,.15.048l.073.015.754-.767.018-.018a1.253,1.253,0,0,1,.849-.324,1.372,1.372,0,0,1,.3.032.942.942,0,0,1,.263.1l.029.008,2.741,1.619.006,0a1.243,1.243,0,0,1,.342.3,1,1,0,0,1,.187.414l0,.025Zm-.763.229a.271.271,0,0,0,.013-.11.254.254,0,0,0-.047-.1.479.479,0,0,0-.128-.112h0l-2.731-1.613-.025-.007a.185.185,0,0,0-.054-.019.651.651,0,0,0-.135-.011.488.488,0,0,0-.341.129l-.794.808h0a.535.535,0,0,1-.126.094.729.729,0,0,1-.116.048q-.057.019-.089.026a.548.548,0,0,1-.122.016l-.074-.007-.177-.035a1.638,1.638,0,0,1-.283-.09q-.151-.061-.326-.15v0a8.983,8.983,0,0,1-2.075-1.6,8.832,8.832,0,0,1-1.59-2.067h0a3.705,3.705,0,0,1-.181-.4,2.332,2.332,0,0,1-.11-.382l-.007-.07a.624.624,0,0,1,.013-.122.915.915,0,0,1,.029-.1.734.734,0,0,1,.048-.116.54.54,0,0,1,.092-.125h0l.7-.7h0a.48.48,0,0,0,.084-.115.558.558,0,0,0,.053-.147.369.369,0,0,0,.006-.128.3.3,0,0,0-.037-.106l0-.009-1.514-2.873a.663.663,0,0,0-.059-.074.583.583,0,0,0-.079-.073.143.143,0,0,0-.043-.022.184.184,0,0,0-.057-.009v0a.222.222,0,0,0-.09.017.326.326,0,0,0-.1.07l-1.889,1.889-.015.013a.549.549,0,0,0-.078.088.84.84,0,0,0-.076.128,1.143,1.143,0,0,0-.061.154.915.915,0,0,0-.03.122l0,.067c0,.07-.007.137-.011.2l0,.031q0,.016,0,.033h0v.011h0q0,.1.014.235t.041.312c.3,1.888,1.91,4.357,3.967,6.281,1.839,1.721,4.02,2.99,5.911,2.99h.09l.058,0,.063-.007.026,0a.917.917,0,0,0,.12-.03,1.154,1.154,0,0,0,.154-.061.845.845,0,0,0,.128-.076.561.561,0,0,0,.088-.078l.015-.015,1.888-1.874h0A.327.327,0,0,0,385.321,659.505Zm-2.437,3.011h0Z"
                                transform="translate(-370.53 -643.511)"
                                fill="#212121"
                            ></path>
                            </svg>
                            <span class="font-medium"> إضافته إلى الطلبات المتابعة</span>
                        </a>
                    </li>
                    @endif
                    @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
                    <li>
                        <a class="dropdown-item" href="{{ route('all.reqHistory',$id)}}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="19.55" height="19.55" viewBox="0 0 19.55 19.55">
                            <g id="Icon_feather-save" data-name="Icon feather-save" transform="translate(-4 -4)">
                                <path
                                id="Path_4006"
                                data-name="Path 4006"
                                d="M20.989,23.05H6.561A2.061,2.061,0,0,1,4.5,20.989V6.561A2.061,2.061,0,0,1,6.561,4.5H17.9L23.05,9.653V20.989A2.061,2.061,0,0,1,20.989,23.05Z"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path
                                id="Path_4007"
                                data-name="Path 4007"
                                d="M20.805,27.744V19.5H10.5v8.244"
                                transform="translate(-1.878 -4.695)"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path id="Path_4008" data-name="Path 4008" d="M10.5,4.5V9.653h8.244" transform="translate(-1.878)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                            </g>
                            </svg>
                            <span class="font-medium">{{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}</span>
                        </a>
                    </li>
                    <li>
                        <button class="dropdown-item" onclick="$('#frm-update').submit()" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="19.55" height="19.55" viewBox="0 0 19.55 19.55">
                            <g id="Icon_feather-save" data-name="Icon feather-save" transform="translate(-4 -4)">
                                <path
                                id="Path_4006"
                                data-name="Path 4006"
                                d="M20.989,23.05H6.561A2.061,2.061,0,0,1,4.5,20.989V6.561A2.061,2.061,0,0,1,6.561,4.5H17.9L23.05,9.653V20.989A2.061,2.061,0,0,1,20.989,23.05Z"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path
                                id="Path_4007"
                                data-name="Path 4007"
                                d="M20.805,27.744V19.5H10.5v8.244"
                                transform="translate(-1.878 -4.695)"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path id="Path_4008" data-name="Path 4008" d="M10.5,4.5V9.653h8.244" transform="translate(-1.878)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                            </g>
                            </svg>
                            <span class="font-medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</span>
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item mov item" type="button" id="send" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="20.227" height="20.227" viewBox="0 0 20.227 20.227">
                            <g id="Icon_feather-send" data-name="Icon feather-send" transform="translate(-2.5 -2.293)">
                                <path id="Path_4004" data-name="Path 4004" d="M26.961,3,16.5,13.461" transform="translate(-4.941)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                                <path id="Path_4005" data-name="Path 4005" d="M22.02,3,15.363,22.02l-3.8-8.559L3,9.657Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                            </g>
                            </svg>
                            <span class="font-medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}</span>
                        </button>
                    </li>
                    @else
                    <li>
                        <a class="dropdown-item" href="{{ route('all.reqHistory',$id)}}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="19.55" height="19.55" viewBox="0 0 19.55 19.55">
                            <g id="Icon_feather-save" data-name="Icon feather-save" transform="translate(-4 -4)">
                                <path
                                id="Path_4006"
                                data-name="Path 4006"
                                d="M20.989,23.05H6.561A2.061,2.061,0,0,1,4.5,20.989V6.561A2.061,2.061,0,0,1,6.561,4.5H17.9L23.05,9.653V20.989A2.061,2.061,0,0,1,20.989,23.05Z"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path
                                id="Path_4007"
                                data-name="Path 4007"
                                d="M20.805,27.744V19.5H10.5v8.244"
                                transform="translate(-1.878 -4.695)"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path id="Path_4008" data-name="Path 4008" d="M10.5,4.5V9.653h8.244" transform="translate(-1.878)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                            </g>
                            </svg>
                            <span class="font-medium">{{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}</span>
                        </a>
                    </li>
                    <li>
                        <button disabled style="cursor: not-allowed" class="dropdown-item" onclick="$('#frm-update').submit()" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="19.55" height="19.55" viewBox="0 0 19.55 19.55">
                            <g id="Icon_feather-save" data-name="Icon feather-save" transform="translate(-4 -4)">
                                <path
                                id="Path_4006"
                                data-name="Path 4006"
                                d="M20.989,23.05H6.561A2.061,2.061,0,0,1,4.5,20.989V6.561A2.061,2.061,0,0,1,6.561,4.5H17.9L23.05,9.653V20.989A2.061,2.061,0,0,1,20.989,23.05Z"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path
                                id="Path_4007"
                                data-name="Path 4007"
                                d="M20.805,27.744V19.5H10.5v8.244"
                                transform="translate(-1.878 -4.695)"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                ></path>
                                <path id="Path_4008" data-name="Path 4008" d="M10.5,4.5V9.653h8.244" transform="translate(-1.878)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                            </g>
                            </svg>
                            <span class="font-medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</span>
                        </button>
                    </li>
                    <li>
                        <button disabled style="cursor: not-allowed" class="dropdown-item mov item" type="button" id="send" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="20.227" height="20.227" viewBox="0 0 20.227 20.227">
                            <g id="Icon_feather-send" data-name="Icon feather-send" transform="translate(-2.5 -2.293)">
                                <path id="Path_4004" data-name="Path 4004" d="M26.961,3,16.5,13.461" transform="translate(-4.941)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                                <path id="Path_4005" data-name="Path 4005" d="M22.02,3,15.363,22.02l-3.8-8.559L3,9.657Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                            </g>
                            </svg>
                            <span class="font-medium">{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}</span>
                        </button>
                    </li>
                    @endif
                    @if(!empty ($payment) && $purchaseCustomer->type == 'شراء-دفعة' && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13))
                        @if ($payment->payStatus == 4 ||$payment->payStatus == 3 )
                        <li>
                            <button role="button" type="button" id="updatePay" data-id="{{$id}}" class="mr-3 Green item">
                                <i class="fa fa-floppy-o"></i>
                                <span class="font-medium">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}
                                </span>
                            </button>
                        </li>
                        <li>
                            <button role="button" type="button" id="sendPay" data-id="{{$id}}" class="mr-3 Pink item">
                                <i class="fa fa-send"></i>
                                <span class="font-medium">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Send') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}
                                </span>
                            </button>
                        </li>
                        @else
                        <li>
                            <button disabled role="button" type="button" id="updatePay" style="cursor: not-allowed" data-id="{{$id}}" class="mr-3 Green item">
                                <i class="fa fa-floppy-o"></i>
                                <span class="font-medium">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}
                                </span>
                            </button>
                        </li>
                        <li>
                            <button disabled role="button" type="button" id="sendPay" style="cursor: not-allowed" data-id="{{$id}}" class="mr-3 Pink item">
                                <i class="fa fa-send"></i>
                                <span class="font-medium">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Send') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayment') }}
                                </span>
                            </button>
                        </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    @endif
  @endsection

@section('css_style')

    <script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
    <link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"/>
    {{-- <link rel="stylesheet" href="{{ asset('themes/theme1/assets/css/plugin.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('themes/theme1/assets/css/main.css')}}" /> --}}

@endsection



@section('customer')
{{-- @include('themes.theme1.Agent.fundingReq.progress') --}}
{{-- <br> --}}

@if($request->customer_want_to_contact_date != null)
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center justify-content-center">
    <h6 class="font-medium">تاريخ اعادة متابعة الطلب</h6>
    <h6 class="px-2 pt-1">{{ Carbon\Carbon::parse($request->customer_want_to_contact_date)->format('Y-m-d H:i') }}</h6>
    </div>
    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
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
        <button type="button" class="close" data-dismiss="alert" id="message">&times;</button>
        {{ session()->get('message') }}
    </div>
@endif

@if(session()->has('message2'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" id="message2">&times;</button>
        {{ session()->get('message2') }}
    </div>
@endif

@if(session()->has('message3'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" id="message3">&times;</button>
        {{ session()->get('message3') }}
    </div>
@endif

@if(session()->has('message4'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session()->get('message4') }}
    </div>
@endif

<!--SEEKING FOR PROPERTY AND CITY OF REAL-->
@if(session()->has('message5'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" id="message5">&times;</button>
        {{ session()->get('message5') }}
    </div>
@endif
<!---->

<!--CALCULATER RECOD IS REQUIRED-->
@if(session()->has('message6'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" id="message6">&times;</button>
        {{ session()->get('message6') }}
    </div>
@endif
<!---->





@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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

<div id="approveWarning" class="alert alert-success" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="appWarning1" class="alert alert-success" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<input type="hidden" value="{{$purchaseCustomer-> is_canceled}}" id="is_canceled">
<input type="hidden" value="{{$purchaseCustomer-> type}}" id="typeReq">
<input type="hidden" value="{{$purchaseClass->class_id_agent}}" id="classAgent">
<input type="hidden" value="{{$is_customer_reopen_request}}" id="is_customer_reopen_request">
<input type="hidden" value="{{$id}}" id="reqId">



{{-- سجل الطلب - حفظ -ارسال الطلب --}}
{{-- <button class="green item" type="submit" id="update" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}">
    <i class="fas fa-edit"></i>
    {{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}
</button>

<button class="mov item" type="button" id="send" data-id="{{$id}}" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}">
    <i class="fas fa-paper-plane"></i>
    {{ MyHelpers::admin_trans(auth()->user()->id,'Send The Request') }}
</button>

<button class="mr-3 Yellow item" role="button" type="button">
    <a href="{{ route('all.reqHistory',$id)}}" target="_blank" class="text-white" style="text-decoration: none">
        <i class="fas fa-calendar mr-2"></i>
        {{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}
    </a>
</button>

@if ($purchaseCustomer-> is_stared == 0)
    <a href="{{ route('agent.manageRequest',['id'=> $id , 'action'=> 'stared'])}}" title="! طلب مميز">
        <button class="mr-3 Green" type="button" role="button">
            <span class="toggle" data-toggle="tooltip" data-placement="top" title="تحويل الطلب للمميز">
                <i class="fas fa-star mr-2"></i>
                <span> تحويل الطلب للمميز</span>
            </span>
        </button>
    </a>
@endif

@if ($purchaseCustomer-> is_followed == 0)
    <a href="{{ route('agent.manageRequest',['id'=> $id , 'action'=> 'followed'])}}" title="إضافته للطلبات المتابعة">
        <button class="mr-3 Cloud" type="button" role="button">
            <span class="toggle" data-toggle="tooltip" data-placement="top" title="تحويل الطلب للمتابعة">
                <i class="fas fa-pen mr-2"></i>
                <span> تحويل الطلب للمتابعة</span>
            </span>
        </button>
    </a>
@endif --}}






    <!-- begin:: main-content-page-grid -->
    <div class="main-content-page-grid">
        <div class="main-content-page">
          <!-- begin::row  -->
          @if (0)
          <div class="row pb-3">
            <!-- begin::col  -->
            <div class="col-12">
              <div class="d-flex align-items-center justify-content-between">
                <!-- begin::breadcrumb  -->
                <ol class="breadcrumb mb-0">
                  <li class="breadcrumb-item"><a href="#">الرئيسية </a></li>
                  <li class="breadcrumb-item"><a href="#">الطلبات المستلمة </a></li>
                  <li class="breadcrumb-item active" aria-current="page">حازم انور</li>
                </ol>
                <!-- end::breadcrumb  -->
                <!-- begin::button  -->
                <div class="table-dropdown">
                    <div class="dropdown">
                      <button class="btn btn-primary" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24.236" height="5.302" viewBox="0 0 24.236 5.302">
                          <path
                            id="menu"
                            d="M16.651,44.3a2.765,2.765,0,0,1-1.893-.8A2.638,2.638,0,0,1,14,41.651a2.988,2.988,0,0,1,.757-1.893A2.661,2.661,0,0,1,16.651,39a2.812,2.812,0,0,1,1.856.757A2.67,2.67,0,0,1,16.651,44.3Zm11.323-.8a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,26.118,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Zm9.467,0a2.676,2.676,0,0,0,.8-1.856,2.838,2.838,0,0,0-.8-1.893A2.638,2.638,0,0,0,35.585,39a2.908,2.908,0,0,0-1.893.757,2.661,2.661,0,0,0-.757,1.893,2.812,2.812,0,0,0,.757,1.856,2.607,2.607,0,0,0,3.749,0Z"
                            transform="translate(-14 -39)"
                            fill="#fff"
                          ></path>
                        </svg>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="21" height="20.02" viewBox="0 0 21 20.02">
                              <path
                                id="Icon_feather-star"
                                data-name="Icon feather-star"
                                d="M13,3l3.09,6.26L23,10.27l-5,4.87,1.18,6.88L13,18.77,6.82,22.02,8,15.14,3,10.27,9.91,9.26Z"
                                transform="translate(-2.5 -2.5)"
                                fill="none"
                                stroke="#000"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                              ></path>
                            </svg>
                            <span class="font-medium">اضفته الى الطلبات المميزة</span>
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg class="ms-3" id="Group_11701" data-name="Group 11701" xmlns="http://www.w3.org/2000/svg" width="18.284" height="19.02" viewBox="0 0 18.284 19.02">
                              <path id="Path_4001" data-name="Path 4001" d="M288.5,245.158l-8.952,5.168-.194.112-.191-.115-8.571-5.168.392-.651,8.38,5.053,8.758-5.056Z" transform="translate(-270.411 -244.449)" fill="#212121"></path>
                              <path
                                id="Path_4002"
                                data-name="Path 4002"
                                d="M255.474,240.125h17.9v10.149h-11.5l.174-.174a1.621,1.621,0,0,0,.284-.384,1.66,1.66,0,0,0,.091-.2h10.194v-8.627H255.854v8.627h.357a7,7,0,0,0,.2.761h-1.315V240.125h.38Z"
                                transform="translate(-255.093 -240.125)"
                                fill="#212121"
                              ></path>
                              <path
                                id="Path_4003"
                                data-name="Path 4003"
                                d="M386.083,659.276a1.03,1.03,0,0,1-.046.479,1.072,1.072,0,0,1-.26.407h0l-1.875,1.861a1.308,1.308,0,0,1-.206.184,1.607,1.607,0,0,1-.244.144,1.9,1.9,0,0,1-.255.1,1.632,1.632,0,0,1-.261.06l-.051,0h0s0,0,0,0l-.051,0q-.045,0-.105.007l-.116,0c-2.1,0-4.461-1.358-6.428-3.2-2.17-2.03-3.874-4.667-4.2-6.715q-.031-.2-.047-.36t-.017-.307h0v-.011h0c0-.023,0-.04,0-.054s0-.046,0-.052c.006-.105.009-.171.011-.2s0-.05,0-.067l0-.053a1.631,1.631,0,0,1,.06-.261,1.906,1.906,0,0,1,.1-.255,1.6,1.6,0,0,1,.144-.244,1.312,1.312,0,0,1,.17-.193h0l1.889-1.889a1.074,1.074,0,0,1,.33-.228.971.971,0,0,1,.393-.082h0a.938.938,0,0,1,.291.044.9.9,0,0,1,.263.138,1.341,1.341,0,0,1,.183.165,1.4,1.4,0,0,1,.15.2l.016.027,1.515,2.874h0a1.053,1.053,0,0,1,.126.37,1.115,1.115,0,0,1-.015.39,1.321,1.321,0,0,1-.124.35,1.241,1.241,0,0,1-.22.3h0l-.643.643a1.744,1.744,0,0,0,.055.17,2.98,2.98,0,0,0,.146.321v0a8.073,8.073,0,0,0,1.452,1.875,8.228,8.228,0,0,0,1.881,1.465h0q.159.081.263.123a.887.887,0,0,0,.15.048l.073.015.754-.767.018-.018a1.253,1.253,0,0,1,.849-.324,1.372,1.372,0,0,1,.3.032.942.942,0,0,1,.263.1l.029.008,2.741,1.619.006,0a1.243,1.243,0,0,1,.342.3,1,1,0,0,1,.187.414l0,.025Zm-.763.229a.271.271,0,0,0,.013-.11.254.254,0,0,0-.047-.1.479.479,0,0,0-.128-.112h0l-2.731-1.613-.025-.007a.185.185,0,0,0-.054-.019.651.651,0,0,0-.135-.011.488.488,0,0,0-.341.129l-.794.808h0a.535.535,0,0,1-.126.094.729.729,0,0,1-.116.048q-.057.019-.089.026a.548.548,0,0,1-.122.016l-.074-.007-.177-.035a1.638,1.638,0,0,1-.283-.09q-.151-.061-.326-.15v0a8.983,8.983,0,0,1-2.075-1.6,8.832,8.832,0,0,1-1.59-2.067h0a3.705,3.705,0,0,1-.181-.4,2.332,2.332,0,0,1-.11-.382l-.007-.07a.624.624,0,0,1,.013-.122.915.915,0,0,1,.029-.1.734.734,0,0,1,.048-.116.54.54,0,0,1,.092-.125h0l.7-.7h0a.48.48,0,0,0,.084-.115.558.558,0,0,0,.053-.147.369.369,0,0,0,.006-.128.3.3,0,0,0-.037-.106l0-.009-1.514-2.873a.663.663,0,0,0-.059-.074.583.583,0,0,0-.079-.073.143.143,0,0,0-.043-.022.184.184,0,0,0-.057-.009v0a.222.222,0,0,0-.09.017.326.326,0,0,0-.1.07l-1.889,1.889-.015.013a.549.549,0,0,0-.078.088.84.84,0,0,0-.076.128,1.143,1.143,0,0,0-.061.154.915.915,0,0,0-.03.122l0,.067c0,.07-.007.137-.011.2l0,.031q0,.016,0,.033h0v.011h0q0,.1.014.235t.041.312c.3,1.888,1.91,4.357,3.967,6.281,1.839,1.721,4.02,2.99,5.911,2.99h.09l.058,0,.063-.007.026,0a.917.917,0,0,0,.12-.03,1.154,1.154,0,0,0,.154-.061.845.845,0,0,0,.128-.076.561.561,0,0,0,.088-.078l.015-.015,1.888-1.874h0A.327.327,0,0,0,385.321,659.505Zm-2.437,3.011h0Z"
                                transform="translate(-370.53 -643.511)"
                                fill="#212121"
                              ></path>
                            </svg>
                            <span class="font-medium"> إضافته إلى الطلبات المتابعة</span>
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="19.55" height="19.55" viewBox="0 0 19.55 19.55">
                              <g id="Icon_feather-save" data-name="Icon feather-save" transform="translate(-4 -4)">
                                <path
                                  id="Path_4006"
                                  data-name="Path 4006"
                                  d="M20.989,23.05H6.561A2.061,2.061,0,0,1,4.5,20.989V6.561A2.061,2.061,0,0,1,6.561,4.5H17.9L23.05,9.653V20.989A2.061,2.061,0,0,1,20.989,23.05Z"
                                  fill="none"
                                  stroke="#000"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1"
                                ></path>
                                <path
                                  id="Path_4007"
                                  data-name="Path 4007"
                                  d="M20.805,27.744V19.5H10.5v8.244"
                                  transform="translate(-1.878 -4.695)"
                                  fill="none"
                                  stroke="#000"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1"
                                ></path>
                                <path id="Path_4008" data-name="Path 4008" d="M10.5,4.5V9.653h8.244" transform="translate(-1.878)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                              </g>
                            </svg>
                            <span class="font-medium">حفظ الطلب</span>
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg class="ms-3" xmlns="http://www.w3.org/2000/svg" width="20.227" height="20.227" viewBox="0 0 20.227 20.227">
                              <g id="Icon_feather-send" data-name="Icon feather-send" transform="translate(-2.5 -2.293)">
                                <path id="Path_4004" data-name="Path 4004" d="M26.961,3,16.5,13.461" transform="translate(-4.941)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                                <path id="Path_4005" data-name="Path 4005" d="M22.02,3,15.363,22.02l-3.8-8.559L3,9.657Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                              </g>
                            </svg>
                            <span class="font-medium">ارسال الطلب</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                <!-- end::button  -->
              </div>
            </div>
            <!-- end::col  -->
          </div>

          @endif
          <!-- end::row  -->
          <!-- begin::row  -->
          <div class="row">
            <!-- begin::col  -->
            <div class="col-12">
              <!-- begin::portlet  -->
              <div class="menu_top"></div>
              <div class="portlet portlet-tabs">
                <!-- begin::portlet__body  -->
                <div class="portlet__body">
                  <ul class="nav nav-pills justify-content-center tab-custom" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" data-bs-target="#tab-1" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24.212" height="24.212" viewBox="0 0 24.212 24.212">
                            <g id="_Group_" data-name="&lt;Group&gt;" transform="translate(0.5 0.5)">
                              <ellipse
                                id="_Path_"
                                data-name="&lt;Path&gt;"
                                cx="6.462"
                                cy="5.816"
                                rx="6.462"
                                ry="5.816"
                                transform="translate(5.117)"
                                fill="none"
                                stroke="#2c2c2c"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                              ></ellipse>
                              <path
                                id="_Path_2"
                                data-name="&lt;Path&gt;"
                                d="M13.106,14.5a20.714,20.714,0,0,0-9.185,2.432A4.421,4.421,0,0,0,1.5,20.878v.807a1.658,1.658,0,0,0,1.658,1.658h19.9a1.658,1.658,0,0,0,1.658-1.658v-.807a4.421,4.421,0,0,0-2.421-3.946A20.714,20.714,0,0,0,13.106,14.5Z"
                                transform="translate(-1.5 -0.131)"
                                fill="none"
                                stroke="#2c2c2c"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                              ></path>
                            </g>
                          </svg>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-2" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="21.891" height="24.212" viewBox="0 0 21.891 24.212">
                            <g id="Icon_feather-home" data-name="Icon feather-home" transform="translate(0.5 0.5)">
                              <path
                                id="Path_3977"
                                data-name="Path 3977"
                                d="M4.5,11.124,14.945,3l10.445,8.124V23.891a2.321,2.321,0,0,1-2.321,2.321H6.821A2.321,2.321,0,0,1,4.5,23.891Z"
                                transform="translate(-4.5 -3)"
                                fill="none"
                                stroke="#2c2c2c"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                              ></path>
                              <path
                                id="Path_3978"
                                data-name="Path 3978"
                                d="M13.5,29.606V18h6.964V29.606"
                                transform="translate(-6.536 -6.394)"
                                fill="none"
                                stroke="#2c2c2c"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                              ></path>
                            </g>
                          </svg>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Real Estate Info') }}</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-3" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20.223" height="23.212" viewBox="0 0 20.223 23.212">
                            <g id="Group_11480" data-name="Group 11480" transform="translate(-111.322 -10.52)">
                              <g id="Group_11393" data-name="Group 11393" transform="translate(111.322 10.52)">
                                <g id="finance-report">
                                  <path
                                    id="Path_2766"
                                    data-name="Path 2766"
                                    d="M73.167,28.241a.356.356,0,0,0-.256-.108H63.947a1.887,1.887,0,0,0-1.885,1.885v1.694h-2.7A1.887,1.887,0,0,0,57.478,33.6V49.46a1.887,1.887,0,0,0,1.885,1.885h11.87a1.887,1.887,0,0,0,1.885-1.885V47.766h2.7A1.887,1.887,0,0,0,77.7,45.882V33.057a.356.356,0,0,0-.1-.248Zm.1,1.128L76.5,32.7h-2.76a.474.474,0,0,1-.474-.474ZM72.4,49.461a1.173,1.173,0,0,1-1.171,1.171H59.363a1.173,1.173,0,0,1-1.171-1.171V33.6a1.173,1.173,0,0,1,1.171-1.171h2.7V45.882a1.887,1.887,0,0,0,1.885,1.885H72.4v1.694Zm3.413-2.408H63.947a1.173,1.173,0,0,1-1.171-1.171V30.018a1.173,1.173,0,0,1,1.171-1.171h8.608v3.38a1.188,1.188,0,0,0,1.187,1.187h3.246V45.882a1.173,1.173,0,0,1-1.171,1.171Z"
                                    transform="translate(-57.478 -28.133)"
                                    fill="#2c2c2c"
                                  ></path>
                                  <path
                                    id="Path_2767"
                                    data-name="Path 2767"
                                    d="M207.593,110.417h-9.556a.357.357,0,0,0,0,.713h9.556a.357.357,0,1,0,0-.713Zm-9.556-1.525h5.721a.357.357,0,1,0,0-.713h-5.721a.357.357,0,0,0,0,.713Zm2.875-3.808h-.03a1.173,1.173,0,0,1-1.157-1.171.357.357,0,0,0-.713,0,1.887,1.887,0,0,0,1.528,1.85v.587a.357.357,0,1,0,.713,0v-.587a1.885,1.885,0,0,0-.357-3.735,1.171,1.171,0,1,1,1.171-1.171.357.357,0,1,0,.713,0,1.887,1.887,0,0,0-1.528-1.85V98.49a.357.357,0,0,0-.713,0v.517a1.885,1.885,0,0,0,.357,3.735,1.171,1.171,0,1,1,.015,2.343Z"
                                    transform="translate(-190.539 -94.568)"
                                    fill="#2c2c2c"
                                  ></path>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="content6" data-bs-target="#tab-4" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="23.212" height="23.212" viewBox="0 0 23.212 23.212">
                            <path
                              id="calculator"
                              d="M5.748,7.858H6.8V8.913h0a.527.527,0,0,0,.528.527h0a.527.527,0,0,0,.527-.528V7.858H8.913a.528.528,0,1,0,0-1.055H7.858V5.748a.528.528,0,0,0-1.055,0V6.8H5.748a.528.528,0,0,0,0,1.055Zm.837,10.023L5.466,19a.527.527,0,1,0,.746.746l1.119-1.119L8.45,19.746A.527.527,0,0,0,9.2,19L8.076,17.881,9.2,16.762l.008-.008a.527.527,0,0,0-.754-.738L7.331,17.135,6.212,16.017,6.2,16.009a.527.527,0,1,0-.738.754ZM19.464,6.8H16.3a.528.528,0,0,0,0,1.055h3.165a.528.528,0,0,0,0-1.055ZM16.3,16.826h3.165a.528.528,0,0,0,0-1.055H16.3a.528.528,0,0,0,0,1.055ZM21.047,1H4.165A3.169,3.169,0,0,0,1,4.165V21.047a3.169,3.169,0,0,0,3.165,3.165H21.047a3.169,3.169,0,0,0,3.165-3.165V4.165A3.169,3.169,0,0,0,21.047,1ZM12.078,23.157H4.165a2.113,2.113,0,0,1-2.11-2.11V13.134H12.078Zm0-11.078H2.055V4.165a2.113,2.113,0,0,1,2.11-2.11h7.913Zm11.078,8.968a2.113,2.113,0,0,1-2.11,2.11H13.134V13.134H23.157Zm0-8.968H13.134V2.055h7.913a2.113,2.113,0,0,1,2.11,2.11ZM16.3,19.992h3.165a.528.528,0,0,0,0-1.055H16.3a.528.528,0,1,0,0,1.055Z"
                              transform="translate(-1 -1)"
                              fill="#2c2c2c"
                            ></path>
                          </svg>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Calculater') }}</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-5" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24.554" height="24.212" viewBox="0 0 24.554 24.212">
                            <g id="Group_11393" data-name="Group 11393" transform="translate(-96.85 -4.02)">
                              <g id="Icon_feather-layers" data-name="Icon feather-layers" transform="translate(97.52 4.52)">
                                <path
                                  id="Path_2763"
                                  data-name="Path 2763"
                                  d="M14.606,3,3,8.8l11.606,5.8L26.212,8.8Z"
                                  transform="translate(-3 -3)"
                                  fill="none"
                                  stroke="#2c2c2c"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1"
                                ></path>
                                <path
                                  id="Path_2764"
                                  data-name="Path 2764"
                                  d="M3,25.5l11.606,5.8,11.606-5.8"
                                  transform="translate(-3 -8.091)"
                                  fill="none"
                                  stroke="#2c2c2c"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1"
                                ></path>
                                <path
                                  id="Path_2765"
                                  data-name="Path 2765"
                                  d="M3,18l11.606,5.8L26.212,18"
                                  transform="translate(-3 -6.394)"
                                  fill="none"
                                  stroke="#2c2c2c"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1"
                                ></path>
                              </g>
                            </g>
                          </svg>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}</span>
                      </button>
                    </li>
                    @if ($purchaseCustomer->type == 'رهن' || $purchaseCustomer->type == 'تساهيل')
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-8" data-bs-toggle="pill" type="button">
                        <span class="icon">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}</span>
                      </button>
                    </li>
                    @elseif ( ($purchaseCustomer->type == 'شراء-دفعة' && (!empty ($payment)) ) || (!empty($paymentForDisplayonly)) )
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-8" data-bs-toggle="pill" type="button">
                        <span class="icon">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        <span class="text">{{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}</span>
                      </button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-6" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg id="history_2_" data-name="history (2)" xmlns="http://www.w3.org/2000/svg" width="22.654" height="23.212" viewBox="0 0 22.654 23.212">
                            <path
                              id="Path_2774"
                              data-name="Path 2774"
                              d="M29.292,16.287H16.2a.454.454,0,1,1,0-.907H29.292a.454.454,0,1,1,0,.907Zm0,3.95H16.2a.454.454,0,0,1,0-.907H29.292a.454.454,0,1,1,0,.907Zm-8.309,3.955H16.194a.454.454,0,0,1,0-.907h4.789a.454.454,0,0,1,0,.907Zm-1.261,3.95H16.194a.454.454,0,0,1,0-.907h3.529a.454.454,0,1,1,0,.907Zm8.767,6.381A5.946,5.946,0,1,1,32.7,32.79,5.941,5.941,0,0,1,28.489,34.524Zm0-10.976a5.039,5.039,0,1,0,3.573,1.468A5.034,5.034,0,0,0,28.489,23.548Z"
                              transform="translate(-11.799 -11.312)"
                              fill="#2c2c2c"
                            ></path>
                            <path
                              id="Path_2775"
                              data-name="Path 2775"
                              d="M43.3,40.448a.454.454,0,0,1-.454-.454V37.274a.454.454,0,1,1,.907,0v2.721A.454.454,0,0,1,43.3,40.448Z"
                              transform="translate(-26.613 -23.028)"
                              fill="#2c2c2c"
                            ></path>
                            <path id="Path_2776" data-name="Path 2776" d="M40.243,44.4H37.7a.454.454,0,1,1,0-.907h2.54a.454.454,0,1,1,0,.907Z" transform="translate(-23.553 -26.673)" fill="#2c2c2c"></path>
                            <path
                              id="Path_2777"
                              data-name="Path 2777"
                              d="M19.5,27.617H9.318A2.268,2.268,0,0,1,7.05,25.35V8.678A2.268,2.268,0,0,1,9.318,6.41H26.67a2.268,2.268,0,0,1,2.268,2.268v8.617a.454.454,0,1,1-.907,0V8.678A1.361,1.361,0,0,0,26.67,7.317H9.318A1.361,1.361,0,0,0,7.957,8.678V25.35A1.361,1.361,0,0,0,9.318,26.71H19.5a.454.454,0,1,1,0,.907Z"
                              transform="translate(-7.05 -6.41)"
                              fill="#2c2c2c"
                            ></path>
                          </svg>
                        </span>
                        <span class="text">سجل الطلب</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-target="#tab-7" data-bs-toggle="pill" type="button">
                        <span class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="23.194" height="23.212" viewBox="0 0 23.194 23.212">
                            <g id="ticket" transform="translate(-72.187 -72)">
                              <path
                                id="Path_2758"
                                data-name="Path 2758"
                                d="M93.931,77.839a.631.631,0,0,0-.8-.076,2.521,2.521,0,0,1-3.493-3.506.638.638,0,0,0-.076-.795l-.725-.725A2.525,2.525,0,0,0,87.051,72h0a2.525,2.525,0,0,0-1.785.738L72.92,85.123a2.525,2.525,0,0,0,.006,3.563l.486.479a.636.636,0,0,0,.845.044,2.5,2.5,0,0,1,3.525,3.519.631.631,0,0,0,.038.851l.9.9a2.527,2.527,0,0,0,3.569,0h0l12.36-12.36a2.527,2.527,0,0,0,0-3.569ZM81.382,93.579a1.264,1.264,0,0,1-1.785,0l-.517-.517A3.758,3.758,0,0,0,73.929,87.9l-.107-.107a1.264,1.264,0,0,1,0-1.785l8.406-8.437,7.58,7.58Zm12.36-12.36L90.69,84.271l-7.58-7.586,3.039-3.052a1.24,1.24,0,0,1,.889-.372h0a1.26,1.26,0,0,1,.889.366l.385.385a3.788,3.788,0,0,0,5.064,5.064l.359.359a1.258,1.258,0,0,1,.006,1.785Z"
                                transform="translate(0 0)"
                                fill="#2c2c2c"
                              ></path>
                              <path
                                id="Path_2759"
                                data-name="Path 2759"
                                d="M171.445,217.177l-3.058-3.058a1.264,1.264,0,0,0-1.785,0h0l-2.983,2.983a1.264,1.264,0,0,0,0,1.785l3.058,3.058a1.264,1.264,0,0,0,1.785,0l2.983-2.983a1.249,1.249,0,0,0,.032-1.772C171.47,217.177,171.463,217.171,171.445,217.177Zm-3.872,3.872-3.065-3.058,2.983-2.983h0l3.058,3.058Z"
                                transform="translate(-85.32 -132.811)"
                                fill="#2c2c2c"
                              ></path>
                            </g>
                          </svg>
                        </span>
                        <span class="text">تذاكر الطلب</span>
                      </button>
                    </li>
                  </ul>
                </div>
                <!-- end::portlet__body  -->
              </div>
              <!-- end::portlet  -->
            </div>
            <!-- end::col  -->
          </div>
          <!-- end::row  -->
          <!-- begin::row  -->
          <div class="row">
            <!-- begin::col  -->
            <div class="col-12">
              <div class="note-student note-student-fixed">
                <div class="d-flex align-items-center">
                  <div class="note-item">{{\Carbon\Carbon::parse($purchaseCustomer->created_at)->format('d-m-Y / H:i')}}</div>
                  <div class="note-item d-flex align-items-baseline">
                    <div class="font-medium">تصنيف المعاملة :</div>
                    <div>
                        @if($purchaseCustomer->source != null)
                            @foreach ($request_sources as $request_source )
                            @if ((int)$purchaseCustomer->source == $request_source->id || (old('reqsour') == $request_source->id) )
                            {{$request_source->value}}
                            @endif
                            @endforeach
                            @endif
                    </div>
                  </div>
                  <div class="note-item d-flex align-items-baseline">
                    <div class="font-medium">الملاحظة :</div>
                    <div>{{$purchaseCustomer->comment}}</div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end::col  -->
          </div>
          <!-- end::row  -->
          <!-- begin::row  -->
          <div class="row">
            <!-- begin::col  -->
            <form action="{{ route('agent.updateFunding')}}" method="post" novalidate="novalidate" id="frm-update">
                @csrf
                @if (!empty ($payment))
                                    <input value="{{$payment->payStatus}}" id="statusPayment" type="hidden" name="statusPayment"> <!-- To pass prepayment status-->
                                @else
                                    <input value="" id="statusPayment" type="hidden" name="statusPayment">
                                @endif

                                <input value={{$reqStatus}} id="statusRequest" type="hidden" name="statusRequest"> <!-- To pass request status-->
                                <input value={{$id}} id="reqID" type="hidden" name="reqID"> <!-- To pass request ID-->

                <div class="col-12">
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.customer-information')
                    </div>

                    <div class="tab-pane fade" id="tab-2" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.real-estate-information')
                    </div>

                    <div class="tab-pane fade" id="tab-3" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.funding-information')
                    </div>

                    <div class="tab-pane fade" id="tab-4" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.funding-calculator')

                    </div>

                    <div class="tab-pane fade" id="tab-5" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.request-documents')
                    </div>

                    <div class="tab-pane fade" id="tab-6" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.request-history')
                    </div>

                    <div class="tab-pane fade" id="tab-7" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.request-tickets')
                    </div>
                    <div class="tab-pane fade" id="tab-8" role="tabpanel">
                        @include('themes.theme1.Agent.Funding-request.request-tsahil')
                    </div>
                </div>
                @include('themes.theme1.Agent.Funding-request.request-information')
                </div>
            </form>
            <!-- end::col  -->
          </div>
          <!-- end::row  -->

          <!-- begin::modal  -->
          {{-- <div class="modal fade" id="modalAddPhone" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">التحكم في الارقام</h5>
                  <button class="btn-close ms-0 shadow-none" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <ul class="nav nav-pills mb-4 tab-custom-2" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-1" type="button" role="tab">إضافة جوال جديد</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-2" type="button" role="tab">الجوالات المضافة</button>
                    </li>
                  </ul>
                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-1" role="tabpanel">
                      <form action="">
                        <div class="form-group">
                          <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="mb-0">جوال العميل </label>
                            <h6 class="text-success">تحقق</h6>
                          </div>
                          <input class="form-control" type="text" value="123456798" />
                        </div>
                        <div class="form-group text-center row">
                          <div class="col-lg-5 mx-auto">
                            <button class="btn btn-primary w-100 py-2">تحديث</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="tab-pane fade" id="pills-2" role="tabpanel">
                      <div class="row mb-4">
                        <div class="col-lg-4">
                          <div class="border rounded-5 p-2 mb-3">123456798</div>
                        </div>
                        <div class="col-lg-4">
                          <div class="border rounded-5 p-2 mb-3">123456798</div>
                        </div>
                        <div class="col-lg-4">
                          <div class="border rounded-5 p-2 mb-3">123456798</div>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-lg-5 mx-auto">
                          <button class="btn btn-primary w-100 py-2">تحديث</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> --}}
          <!-- end::modal  -->
        </div>
    </div>
      <!-- end:: main-content-page-grid -->

@endsection



@section('updateModel')
    @include('themes.theme1.Agent.fundingReq.req_records')
    @include('themes.theme1.Agent.fundingReq.documentModel')
    @include('Helpers.new_addPhone')
@endsection

@section('confirmMSG')
    @include('themes.theme1.Agent.fundingReq.confirmationMsg')
    @include('themes.theme1.Agent.fundingReq.confirmSendingMsg')
    @include('themes.theme1.Agent.fundingReq.confirmSendingMsgPay')
@endsection


@section('scripts')
    @include('FundingCalculater.new_calculaterJS')
    <script>
        $(document).on('click', '#sendPay', function (e) {
            var id = $(this).attr('data-id');
            var modalConfirm = function (callback) {
                $("#mi-modal7").modal('show');
                $("#modal-btn-si7").on("click", function () {
                    callback(true);
                    $("#mi-modal7").modal('hide');
                });
                $("#modal-btn-no7").on("click", function () {
                    callback(false);
                    $("#mi-modal7").modal('hide');
                });
            };
            modalConfirm(function (confirm) {
                if (confirm) {
                    $.get("{{ route('agent.sendPrepayment')}}", {
                        id: id
                    }, function (data) {
                        var url = '{{ route("agent.fundingRequest", ":reqID") }}';
                        url = url.replace(':reqID', data.id);

                        if (data.status == 1) {
                            window.location.href = url; //using a named route

                        } else {

                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        }

                    });

                } else {
                    //No send
                }
            });
        });
    </script>
    <script>
        //---------------------to show wraning msg---------------
        $(document).ready(function () {

            var status = document.getElementById("statusRequest").value;
            var requestID = document.getElementById("reqId").value;
            var statusPay = document.getElementById("statusPayment").value;
            var classAgent = document.getElementById("classAgent").value;
            var is_customer_reopen_request = document.getElementById("is_customer_reopen_request").value;


            //alert(status);

            if (status == 0)
                updateNewReq(requestID);

            var checkCanceled = document.getElementById("is_canceled").value;
            var type = document.getElementById("typeReq").value;


            if (checkCanceled == 1) {
                document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is canceled, you cannot edit anything until restore it') }}";
                document.getElementById('archiveWarning1').style.display = "block";
            }
            /* if (status == 1) { //in sales agent
                     document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request with Sales Agent, you cannot edit anything1') }}";
             document.getElementById('sendingWarning1').style.display = "block";
         } */

            if (type == 'تساهيل') {
                if (status == 30) { //reject from general manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Mortgage Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }
                if (status == 31) { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to sales agent') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 32) { //sending to general manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to General Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }
                if (status == 33) { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>   {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Mortgage Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 34) { //canceled from general manager
                    document.getElementById('archiveWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled') }} ";
                    document.getElementById('archiveWarning1').style.display = "block";
                }
                if (status == 35) { //APProved
                    document.getElementById('appWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has Completed') }}";
                    document.getElementById('appWarning1').style.display = "block";
                }

            }

            if ((status != 6 && status != 13) || statusPay == '' || statusPay == 2 || statusPay == 8 || statusPay == 9) {
                if (status == 5) { //in sales manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by Sales Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if ((status == 0 || status == 1) && is_customer_reopen_request) { //archived in funding manager
                    document.getElementById('sendingWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> العميل أعاد فتح الطلب";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 8) { //archived in funding manager
                    document.getElementById('sendingWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by Funding Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }


                if (status == 15) { //canceled from general manager
                    document.getElementById('archiveWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled') }} ";
                    document.getElementById('archiveWarning1').style.display = "block";
                }


                if (status == 3) { //sending to sales manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Sales Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 4) { //reject from sales manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected from Sales Manager and redirect to Sales Agent') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 2) {

                    if (classAgent != 65) {
                        document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived, you cannot edit anything until restore it!') }}";
                        document.getElementById('archiveWarning1').style.display = "block";

                    } else {
                        document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>   العميل قام بإلغاء الطلب - {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived, you cannot edit anything until restore it!') }}";
                        document.getElementById('archiveWarning1').style.display = "block";

                    }
                }
                if (status == 6) { //wating for funding manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Funding Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 9) { //sending to mortgage manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Mortgage Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }

                if (status == 16) { //APProved
                    document.getElementById('appWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has Completed') }}";
                    document.getElementById('appWarning1').style.display = "block";
                }


                if (status == 7) { //reject and back to sales manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to sales manager,  you cannot edit anything') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }


                if (status == 13 && type == 'شراء') { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Funding Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 13 && type == 'رهن') { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>   {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Mortgage Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 13 && type == 'شراء-دفعة') { //reject from general manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to Funding Manager') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }
                if (status == 14) { //archived in general manager
                    document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by General Manager, you cannot edit anything') }}";
                    document.getElementById('archiveWarning1').style.display = "block";
                }
                if (status == 10) { //reject and back to sales manager
                    document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected and back to sales manager,  you cannot edit anything') }}";
                    document.getElementById('rejectWarning1').style.display = "block";
                }

                if (status == 11) { //archived in mortgage manager
                    document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>  The request is archived by Mortgage Manager, you cannot edit anything until restore it!";
                    document.getElementById('archiveWarning1').style.display = "block";
                }

                if (status == 12) { //sending to general manager
                    document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to General Manager, you cannot edit anything') }}";
                    document.getElementById('sendingWarning1').style.display = "block";
                }
            }


            if ($("#message6").length != 0) {

                swal({
                    title: "خطأ!",
                    text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Calculater record is required') }}",
                    icon: 'error',
                    button: 'موافق',
                });

            } else if ($("#message5").length != 0) {

                swal({
                    title: "خطأ!",
                    text: "{{ session()->get('message5') }}",
                    icon: 'error',
                    button: 'موافق',
                });

            } else if ($("#message").length != 0) {

                swal({
                    title: "تم!",
                    text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Update Succesffuly') }}",
                    icon: 'success',
                });

            } else if ($("#message2").length != 0) {


                swal({
                    title: "خطأ!",
                    text: "{{ session()->get('message2') }}",
                    icon: 'danger',
                });

            }

        });

        //------------------------------------

        $(document).ready(function () {

            var today = new Date().toISOString().split("T")[0];

            $('#jointbirth').attr("max", today);
            $('#birth').attr("max", today);
            $('#jointbirth').attr("max", today);
            $('#follow').attr("min", today);


            var customer_birth = document.getElementById('birth').value;
            var joint_birth = document.getElementById('jointbirth')?.value;

            if (customer_birth != '')
                calculate();

            if (joint_birth != '')
                calculate1();


        });

        //-----------------------------------
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //-----------------------------------------------
        function updateNewReq(id) {
            //console.log (id);

            $.post("{{ route('agent.updateNewReq') }}", {
                id: id
            }, function (data) {
            });

        }

        //------------------------------------------


        function checkWork(that) {

            if (that.value == 1) {


                if ((document.getElementById("madany1")) != null) {
                    document.getElementById("madany").style.display = "none";
                    document.getElementById("madany1").style.display = "none";
                }

                if ((document.getElementById("madany2")) != null) {
                    document.getElementById("madany2").style.display = "none";
                    document.getElementById("madany3").style.display = "none";
                }

                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";

                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";

                if ((document.getElementById("askary2")) != null) {
                    document.getElementById("askary2").style.display = "block";
                    document.getElementById("askary3").style.display = "block";
                }

                if ((document.getElementById("askary")) != null) {
                    document.getElementById("askary1").style.display = "block";
                    document.getElementById("askary").style.display = "block";
                }

            } else if (that.value == 2) {

                if ((document.getElementById("askary1")) != null) {
                    document.getElementById("askary1").style.display = "none";
                    document.getElementById("askary").style.display = "none";

                }

                if ((document.getElementById("askary2")) != null) {
                    document.getElementById("askary2").style.display = "none";
                    document.getElementById("askary3").style.display = "none";
                }

                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";

                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";


                if ((document.getElementById("madany2")) != null) {
                    document.getElementById("madany2").style.display = "block";
                    document.getElementById("madany3").style.display = "block";
                }

                if ((document.getElementById("madany1")) != null) {
                    document.getElementById("madany").style.display = "block";
                    document.getElementById("madany1").style.display = "block";
                }

            } else {

                if ((document.getElementById("askary2")) != null) {
                    document.getElementById("askary2").style.display = "none";
                    document.getElementById("askary3").style.display = "none";
                }

                if ((document.getElementById("madany2")) != null) {
                    document.getElementById("madany2").style.display = "none";
                    document.getElementById("madany3").style.display = "none";
                }


                if ((document.getElementById("madany1")) != null) {
                    document.getElementById("madany").style.display = "none";
                    document.getElementById("madany1").style.display = "none";
                }

                if ((document.getElementById("askary1")) != null) {
                    document.getElementById("askary1").style.display = "none";
                    document.getElementById("askary").style.display = "none";

                }


                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";
                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";


            }
        }


        //------------------------------------------


        function checkWork_caculater(that) {

            if (that.value == 1) {

                document.getElementById("askary_caculater").style.display = "block";

            } else {

                document.getElementById("askary_caculater").style.display = "none";
                document.getElementById("rank_caculater").value = "";
            }
        }

        function checkWork_joint_caculater(that) {

            if (that.value == 1) {

                document.getElementById("joint_askary").style.display = "block";

            } else {

                document.getElementById("joint_askary").style.display = "none";
                document.getElementById("joint_rank_caculater").value = "";
            }
        }

        //----------------------------
        function checkObligation(that) {
            if (that.value == "yes")
                document.getElementById("obligations_value").readOnly = false;
            else {
                document.getElementById("obligations_value").readOnly = true;
                document.getElementById("obligations_value").value = '';
            }
        }

        //----------------------------

        function checkDistress(that) {
            if (that.value == "yes")
                document.getElementById("financial_distress_value").readOnly = false;
            else {
                document.getElementById("financial_distress_value").readOnly = true;
                document.getElementById("financial_distress_value").value = '';
            }
        }

        //----------------------------

        /////////////////////////////////////////////

        function checkWork2(that) {
            if (that.value == 1) {


                if ((document.getElementById("jointmadany1")) != null) {
                    document.getElementById("jointmadany").style.display = "none";
                    document.getElementById("jointmadany1").style.display = "none";
                }

                if ((document.getElementById("jointmadany2")) != null) {
                    document.getElementById("jointmadany2").style.display = "none";
                    document.getElementById("jointmadany3").style.display = "none";
                }

                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";

                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";

                if ((document.getElementById("jointaskary2")) != null) {
                    document.getElementById("jointaskary2").style.display = "block";
                    document.getElementById("jointaskary3").style.display = "block";
                }

                if ((document.getElementById("jointaskary")) != null) {
                    document.getElementById("jointaskary1").style.display = "block";
                    document.getElementById("jointaskary").style.display = "block";
                }

            } else if (that.value == 2) {

                if ((document.getElementById("jointaskary1")) != null) {
                    document.getElementById("jointaskary1").style.display = "none";
                    document.getElementById("jointaskary").style.display = "none";

                }

                if ((document.getElementById("jointaskary2")) != null) {
                    document.getElementById("jointaskary2").style.display = "none";
                    document.getElementById("jointaskary3").style.display = "none";
                }

                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";

                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";


                if ((document.getElementById("jointmadany2")) != null) {
                    document.getElementById("jointmadany2").style.display = "block";
                    document.getElementById("jointmadany3").style.display = "block";
                }

                if ((document.getElementById("jointmadany1")) != null) {
                    document.getElementById("jointmadany").style.display = "block";
                    document.getElementById("jointmadany1").style.display = "block";
                }

            } else {

                if ((document.getElementById("jointaskary2")) != null) {
                    document.getElementById("jointaskary2").style.display = "none";
                    document.getElementById("jointaskary3").style.display = "none";
                }

                if ((document.getElementById("jointmadany2")) != null) {
                    document.getElementById("jointmadany2").style.display = "none";
                    document.getElementById("jointmadany3").style.display = "none";
                }


                if ((document.getElementById("jointmadany1")) != null) {
                    document.getElementById("jointmadany").style.display = "none";
                    document.getElementById("jointmadany1").style.display = "none";
                }

                if ((document.getElementById("jointaskary1")) != null) {
                    document.getElementById("jointaskary1").style.display = "none";
                    document.getElementById("jointaskary").style.display = "none";

                }


                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";
                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";


            }
        }

        //----------------------------

        function showJoint() {
            var x = document.getElementById("jointdiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {

                x.style.display = "none";
                document.getElementById("jointfunding_source").value = "";
                document.getElementById("jointsalary_source").value = "";
                document.getElementById("jointwork").value = "";
                document.getElementById("jointbirth").value = "";
                document.getElementById("jointage").value = "";
                document.getElementById("jointmobile").value = "";
                document.getElementById("jointname").value = "";
                document.getElementById("jointmadany_work").value = "";
                document.getElementById("jointjob_title").value = "";
                document.getElementById("jointaskary_work").value = "";
                document.getElementById("jointrank").value = "";
                /*
                            document.getElementById("jointmadany").style.display = "none";
                            document.getElementById("jointmadany1").style.display = "none";
                            document.getElementById("jointaskary").style.display = "none";
                            document.getElementById("jointaskary1").style.display = "none";

                */

            }
        }

        //----------------------------
        function changeCustomer(that) {
            var id = that.value; //to pass customer id

            if (id != "") { // if not select any customer

                $.get("{{ route('agent.getCustomerInfo')}}", {
                    id: id
                }, function (data) {

                    document.getElementById("mobile").value = data[0].mobile;
                    document.getElementById("birth").value = data[0].birth_date;
                    document.getElementById("age").value = data[0].age;
                    document.getElementById("hijri-date").value = data[0].birth_date_higri;
                    document.getElementById("work").value = data[0].work;
                    document.getElementById("salary").value = data[0].salary;
                    //document.getElementById("salary1").value = data[0].salary;
                    document.getElementById("salary_source").value = data[0].salary_id;
                    document.getElementById("is_support").value = data[0].is_supported;

                })
            } else {

                document.getElementById("mobile").value = "";
                document.getElementById("birth").value = "";
                document.getElementById("age").value = "";
                document.getElementById("hijri-date").value = "";
                document.getElementById("work").value = "";
                document.getElementById("salary").value = "";
                document.getElementById("salary_source").value = "";
                document.getElementById("is_support").value = "";
                document.getElementById("askary_work").value = "";
                document.getElementById("rank").value = "";
                document.getElementById("madany_work").value = "";
                document.getElementById("job_title").value = "";


            }
        }

        //----------------------------
        function calculate() {
            var date = new Date(document.getElementById('birth').value);
            var dateString = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());


            var now = new Date();
            var today = new Date(now.getYear(), now.getMonth(), now.getDate());

            var yearNow = now.getYear();
            var monthNow = now.getMonth();
            var dateNow = now.getDate();

            var dob = new Date(dateString.substring(6, 10),
                dateString.substring(0, 2) - 1,
                dateString.substring(3, 5)
            );

            var yearDob = dob.getYear();
            var monthDob = dob.getMonth();
            var dateDob = dob.getDate();
            var age = {};
            var ageString = "";
            var yearString = "";
            var monthString = "";
            var dayString = "";


            yearAge = yearNow - yearDob;

            if (monthNow >= monthDob)
                var monthAge = monthNow - monthDob;
            else {
                yearAge--;
                var monthAge = 12 + monthNow - monthDob;
            }

            if (dateNow >= dateDob)
                var dateAge = dateNow - dateDob;
            else {
                monthAge--;
                var dateAge = 31 + dateNow - dateDob;

                if (monthAge < 0) {
                    monthAge = 11;
                    yearAge--;
                }
            }

            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };

            if (age.years > 1) yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'years') }}";
            else yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'year') }}";
            if (age.months > 1) monthString = "  {{ MyHelpers::admin_trans(auth()->user()->id,'months') }}";
            else monthString = " {{ MyHelpers::admin_trans(auth()->user()->id,'month') }}";
            if (age.days > 1) dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'days') }}";
            else dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'day') }}";


            if ((age.years > 0) && (age.months > 0) && (age.days > 0))
                ageString = age.years + yearString + ", " + age.months + monthString + ", {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
                ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Only') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}";
            else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}. ";
            else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Could not calculate age') }}!";


            document.getElementById('age').value = ageString;
        }

        //-------------------------------
        function calculate1() {
            var date = new Date($('#jointbirth').val());
            var dateString = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());


            var now = new Date();
            var today = new Date(now.getYear(), now.getMonth(), now.getDate());

            var yearNow = now.getYear();
            var monthNow = now.getMonth();
            var dateNow = now.getDate();

            var dob = new Date(dateString.substring(6, 10),
                dateString.substring(0, 2) - 1,
                dateString.substring(3, 5)
            );

            var yearDob = dob.getYear();
            var monthDob = dob.getMonth();
            var dateDob = dob.getDate();
            var age = {};
            var ageString = "";
            var yearString = "";
            var monthString = "";
            var dayString = "";


            yearAge = yearNow - yearDob;

            if (monthNow >= monthDob)
                var monthAge = monthNow - monthDob;
            else {
                yearAge--;
                var monthAge = 12 + monthNow - monthDob;
            }

            if (dateNow >= dateDob)
                var dateAge = dateNow - dateDob;
            else {
                monthAge--;
                var dateAge = 31 + dateNow - dateDob;

                if (monthAge < 0) {
                    monthAge = 11;
                    yearAge--;
                }
            }

            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };

            if (age.years > 1) yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'years') }}";
            else yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'year') }}";
            if (age.months > 1) monthString = "  {{ MyHelpers::admin_trans(auth()->user()->id,'months') }}";
            else monthString = " {{ MyHelpers::admin_trans(auth()->user()->id,'month') }}";
            if (age.days > 1) dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'days') }}";
            else dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'day') }}";


            if ((age.years > 0) && (age.months > 0) && (age.days > 0))
                ageString = age.years + yearString + ", " + age.months + monthString + ", {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
                ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Only') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}";
            else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}. ";
            else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
                ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
                ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
            else ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Could not calculate age') }}!";


            // document.getElementById('jointage').value = ageString;
            $('#jointage').val(ageString);
        }


        //----------------------------

        //--------------------------------------------------

        $(document).ready(function () {
            $('input[name="realtype"]').click(function () {
                if ($(this).attr('id') == 'other') {
                    document.getElementById("othervalue").style.display = "block";
                } else {
                    document.getElementById("othervalue").style.display = "none";
                    document.getElementById("otherinput").value = "";
                }
            });
        });

        //////////////////////////////////

        function monthlycalculate() {
            var pres = document.getElementById("dedp").value;
            var salary = document.getElementById("salary").value;

            document.getElementById("monthIn").value = ((pres * salary) / 100);
        }

        //////////////////////////////////////

        $(document).on('click', '#record', function (e) {

            var coloum = $(this).attr('data-id');
            var reqID = document.getElementById("reqID").value;

            var hide_negative_comment = "{{$hide_negative_comment}}";
            var negative_agent = "{{$history_negative_agent}}";
            var to_count_historiy = 0;

            // var body = document.getElementById("records");

            $.get("{{ route('all.reqRecords') }}", {
                coloum: coloum,
                reqID: reqID
            }, function (data) {

                $('#records').empty();


                //console.log(data);

                if (data.status == 1) {

                    $.each(data.histories, function (i, value) {

                        if (coloum == 'class_agent' || coloum == 'comment') {
                            if (hide_negative_comment == 1) {
                                if (value.user_id != negative_agent) {
                                    to_count_historiy++;
                                    var fn = $("<tr/>").attr('id', value.id);

                                    var name = '';

                                    if (value.comment == null) {

                                        if (value.switch != null)
                                            name = value.switch + ' / ' + value.name;
                                        else
                                            name = value.name;

                                    } else
                                        name = value.name + ' / ' + value.comment;


                                    fn.append($("<td/>", {
                                        text: name
                                    })).append($("<td/>", {
                                        text: value.value
                                    })).append($("<td/>", {
                                        text: value.updateValue_at
                                    }));

                                    $('#records').append(fn);
                                }
                            } else {
                                to_count_historiy++;
                                var fn = $("<tr/>").attr('id', value.id);

                                var name = '';

                                if (value.comment == null) {

                                    if (value.switch != null)
                                        name = value.switch + ' / ' + value.name;
                                    else
                                        name = value.name;

                                } else
                                    name = value.name + ' / ' + value.comment;


                                fn.append($("<td/>", {
                                    text: name
                                })).append($("<td/>", {
                                    text: value.value
                                })).append($("<td/>", {
                                    text: value.updateValue_at
                                }));

                                $('#records').append(fn);
                            }
                        } else {
                            to_count_historiy++;
                            var fn = $("<tr/>").attr('id', value.id);

                            var name = '';

                            if (value.comment == null) {

                                if (value.switch != null)
                                    name = value.switch + ' / ' + value.name;
                                else
                                    name = value.name;

                            } else
                                name = value.name + ' / ' + value.comment;


                            fn.append($("<td/>", {
                                text: name
                            })).append($("<td/>", {
                                text: value.value
                            })).append($("<td/>", {
                                text: value.updateValue_at
                            }));

                            $('#records').append(fn);
                        }


                    });


                    // body.append(fn)

                    if (to_count_historiy == 0) {

                        var fn = $("<tr/>");

                        fn.append($("<td/>", {
                            text: ""
                        })).append($("<td/>", {
                            text: 'لايوجد تحديثات'
                        })).append($("<td/>", {
                            text: ""
                        }));

                        $('#records').append(fn);
                    }

                    $('#myModal').modal('show');

                }
                if (data.status == 0) {

                    var fn = $("<tr/>");

                    fn.append($("<td/>", {
                        text: ""
                    })).append($("<td/>", {
                        text: data.message
                    })).append($("<td/>", {
                        text: ""
                    }));


                    $('#records').append(fn);
                    $('#myModal').modal('show');

                }


            }).fail(function (data) {


                document.getElementById('archiveWarning').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}!";
                document.getElementById('archiveWarning').style.display = "block";


            });


        })

        /////////////////////////////////

        $(document).on('click', '#upload', function (e) {

            $('#nameError').text('');
            $('#fileError').text('');
            document.getElementById("filename").value = "";
            document.getElementById("file").value = "";
            //alert('h');
            $('#myModal1').modal('show');

        })

        //////////////////////////////

        $('#file-form').submit(function (event) {

            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{ route('agent.uploadFile')}}",
                data: formData,
                type: 'post',
                async: false,
                processData: false,
                contentType: false,
                success: function (response) {

                    // console.log(response);
                    $('#myModal1').modal('hide');

                    $('#st').empty(); // to prevent dublicate of same data in each click , so i will empty before start the loop!
                    $.each(response, function (i, value) { //for loop , I put data as value

                        var docID = value.id;

                        var url = '{{ route("agent.openFile", ":docID") }}';
                        url = url.replace(':docID', docID);
                        var url2 = '{{ route("agent.downFile", ":docID") }}';
                        url2 = url2.replace(':docID', docID);


                        //  alert(docID);
                        var fn = $("<tr/>").attr('id', value.id); // if i want to add html tag or anything in html i have to define as verible $(); <tr/> : that mean create start and close tage; att (..,..) : mean add attrubite to this tage , here i add id attribute to use it in watever i want
                        fn.append($("<td/>", {
                            text: value.name
                        })).append($("<td/>", {
                            text: value.filename
                        })).append($("<td/>", {
                            text: value.upload_date
                        })).append($("<td/>", {
                            html: " <div class='tableAdminOption'><span  class='item pointer' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}'> <a href='" + url + "' target='_blank'> <i class='fa fa-eye'></i></a></span><span  class='item pointer' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}'><a href='" + url2 + "' target='_blank'><i class='fa fa-download'></i></a></span><span id='delete' data-id=" + docID + " class='item pointer'  data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}'><i class='fa fa-trash'></i></span></div>"
                        }))
                        $('#st').append(fn);
                    })


                },
                error: function (xhr) {

                    var errors = xhr.responseJSON;

                    if ($.isEmptyObject(errors) == false) {

                        $.each(errors.errors, function (key, value) {

                            var ErrorID = '#' + key + 'Error';
                            // $(ErrorID).removeClass("d-none");
                            $(ErrorID).text(value);

                        })

                    }

                }
            });

        });
        ///////////////////////////////////

        $(document).on('click', '#delete', function (e) {

            var id = $(this).attr('data-id');

            var modalConfirm = function (callback) {


                $("#mi-modal").modal('show');


                $("#modal-btn-si").on("click", function () {
                    callback(true);
                    $("#mi-modal").modal('hide');
                });

                $("#modal-btn-no").on("click", function () {
                    callback(false);
                    $("#mi-modal").modal('hide');
                });
            };

            modalConfirm(function (confirm) {
                if (confirm) {

                    $.post("{{ route('agent.deleFile') }}", {
                        id: id
                    }, function (data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

                        if (data.status == 1) {

                            var d = ' # ' + id;
                            var test = d.replace(/\s/g, ''); // to remove all spaces in var d , to find the <tr/> that i deleted and reomve it
                            $(test).remove(); // remove by #id


                            var rowCount = document.querySelectorAll('#docTable tbody tr').length;
                            //alert(rowCount);
                            if (rowCount == 0) { //if table become empty

                                var fn = $("<tr/>");
                                fn.append($("<td/>", {
                                    html: "<h3 class='text-center text-secondary'>{{ MyHelpers::admin_trans(auth()->user()->id,'No Attached') }}</h3>"
                                }).attr('colspan', '4'));

                                $('#st').append(fn);

                            }


                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        } else {
                            $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                        }


                    })


                } else {
                    //No delete
                }
            });


        });

        /////////////////////////////////////////
        $(document).on('click', '#send', function (e) {

            document.getElementById('msg2').style.display = "none";
            $('.missedFileds').addClass("d-none");
            $(".FiledInput").css({'background-color': '', 'border-radius': '', 'border': ''});

            var id = $(this).attr('data-id');
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{csrf_token()}}"
                },
                type: "POST",
                url: "{{route('agent.checkSendFunding')}}",
                data: $('#frm-update').serialize(),
                success: function (data) {
                    if (data.missed_filed.length > 0) {
                        // $('#myModal11').modal('hide');
                        var myArray = data.names;

                        var myList = '<b>حقول الطلب مطلوبة : </b> ';
                        myList = myList + "<ul>";

                        for (var key in myArray) {
                            myList = myList + '<li data-key="'+key+'">' + myArray[key] + '</li>';
                        }
                        myList = myList + "</ul>";
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + myList);
                        document.getElementById('msg2').style.display = "block";

                        var column_names = data.missed_filed;
                        for (var key in column_names) {
                            var ErrorID = '.' + column_names[key] + '_missedFileds';
                            $(ErrorID).removeClass("d-none");
                            var ErrorInputID = '.' + column_names[key] + '_missedFiledInput';
                            // $(ErrorInputID).addClass("missedFiledInput");

                            $(ErrorInputID).css("border", "3px solid #e67681");
                            $(ErrorInputID).css("border-radius", "4px");
                            $(ErrorInputID).css("background-color", "#ffe6e6");

                        }
                        /*
                        var url = '{{ route("agent.fundingRequest", ":reqID") }}';
                    url = url.replace(':reqID', id);
                    window.location.href = url; //using a named route
                    */

                    } else
                        sendFunding(id); // no required fileds are missed
                    // $('#myModal11').modal('hide');
                    // swal({
                    //     title: 'تم الأرسال ',
                    //     text: 'تم الارسال بنجاح',
                    //     type: 'success',
                    //     timer: '750'
                    // })
                },
                error: function (data) {
                    // $('#myModal11').modal('hide');
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> حاول مرة أخرى");
                    document.getElementById('msg2').style.display = "block";
                }


            });

            /*
             var agent_identity_number = $("#agent_identity_number").val();
                if(agent_identity_number == ""|| agent_identity_number.trim().length != 10 ){

             } else {
                 if (agent_identity_number.trim() == "") {
                     $('#agent_identity_number_error').html("من فضلك أدخل رقم الهوية ")
                 } else if (agent_identity_number.trim().length != 11) {
                     $('#agent_identity_number_error').html("صيغة رقم الهوية غير صحيحة من فضلك أدخل 11 رقم ")
                 }
             }*/
        });
        // $('#agent_identity_number').val("")
        // $('#send').prop("disabled", true)
        document.querySelector("#agent_identity_number").addEventListener("keypress", function (evt) {

            if (evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }

        });
        document.querySelector("#agent_identity_number").addEventListener("keyup", function (evt) {

            var agent_identity_number = $("#agent_identity_number").val();

            if (agent_identity_number.trim().length != 1) {
                if (agent_identity_number == ""/* || agent_identity_number.trim().length != 10*/) {
                    $('#send').prop("disabled", true)
                } else {
                    $('#send').prop("disabled", false)
                }
            } else {
                $('#send').prop("disabled", true)
            }
        });

        //////////////////////////////////////////

        function sendFunding(id) {


            var checktype = document.getElementById("reqtyp").value;
            var checkSource = document.getElementById("reqsour").value;

            if (document.getElementById("collaborator") != null)
                var checkColl = document.getElementById("collaborator").value;
            else
                var checkColl = null;


            if (checktype != '' && checkSource != '') {

                if (checkSource != 2 || (checkSource == 2 && checkColl != '')) {

                    var modalConfirm = function (callback) {


                        $("#mi-modal2").modal('show');


                        $("#modal-btn-si2").on("click", function () {
                            callback(true);
                            $("#mi-modal2").modal('hide');

                        });

                        $("#modal-btn-no2").on("click", function () {
                            callback(false);
                            $("#mi-modal2").modal('hide');


                        });
                    };

                    modalConfirm(function (confirm) {
                        if (confirm) {
                            var comment = document.getElementById("comment").value;
                            $.post("{{ route('agent.sendFunding')}}", {
                                id: id,
                                comment: comment,
                                checktype: checktype,
                                checkSource: checkSource,
                                checkColl: checkColl,
                            }, function (data) {

                                var url = '{{ route("agent.fundingRequest", ":reqID") }}';
                                url = url.replace(':reqID', data.id);

                                if (data.status == 1) {
                                    window.location.href = url; //using a named route
                                } else {
                                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                                }

                            })


                        } else {
                            //No send
                        }
                    });

                } else {
                    if (checkColl == '') {
                        document.getElementById('msg2').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'The coll is required')}}";
                        document.getElementById('msg2').style.display = "block";
                        $('#msg2').addClass('alert-danger');
                        document.getElementById("collaboratorError").textContent = "{{MyHelpers::admin_trans(auth()->user()->id, 'The filed is required')}}";
                    }


                }

            } else {

                if (checktype == '') {
                    document.getElementById('msg2').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'The request type is required')}}";
                    document.getElementById('msg2').style.display = "block";
                    $('#msg2').addClass('alert-danger');
                    document.getElementById("reqtypError").textContent = "{{MyHelpers::admin_trans(auth()->user()->id, 'The filed is required')}}";

                }

                if (checkSource == '') {
                    document.getElementById('msg3').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'The request source is required')}}";
                    document.getElementById('msg3').style.display = "block";
                    $('#msg3').addClass('alert-danger');
                    document.getElementById("reqsourceError").textContent = "{{MyHelpers::admin_trans(auth()->user()->id, 'The filed is required')}}";

                }


            }


        }


        ////////////////////////////////////

        function checktype() {
            document.getElementById('msg2').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{MyHelpers::admin_trans(auth()->user()->id, 'The request type is required')}}";
            document.getElementById('msg2').style.display = "block";
            $('#msg2').addClass('alert-danger');
            document.getElementById("reqtypError").textContent = "{{MyHelpers::admin_trans(auth()->user()->id, 'The filed is required')}}";

        }

        //--------Tsaheel Page functiones---------------------

        function incresecalculate() {
            var check = document.getElementById("check").value;
            var real = document.getElementById("real").value;

            document.getElementById("incr").value = (check - real);
        }

        //------------------------------------
        function preCoscalculate() {
            var prepaymentValue = parseInt(document.getElementById("preval").value);
            var presentage = parseInt(document.getElementById("prepre").value);

            document.getElementById("precos").value = prepaymentValue + (prepaymentValue * (presentage / 100));
        }

        //---------------------------------------

        function showPrepay() {
            var x = document.getElementById("prepaydiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {

                x.style.display = "none";
                document.getElementById("check").value = "";
                document.getElementById("real").value = "";
                document.getElementById("incr").value = "";
                document.getElementById("preval").value = "";
                document.getElementById("prepre").value = "";
                document.getElementById("precos").value = "";
                document.getElementById("net").value = "";
                document.getElementById("deficit").value = "";


            }
        }

        //-----------------------------------------------
        function debtcalculate() {
            var visa = parseInt(document.getElementById("visa").value);
            var car = parseInt(document.getElementById("carlo").value);

            var personal = parseInt(document.getElementById("perlo").value);
            var realEstat = parseInt(document.getElementById("realo").value);

            var credit = parseInt(document.getElementById("credban").value);
            var other = parseInt(document.getElementById("other1").value);

            var debt = document.getElementById("debt");

            debt.value = visa + car + personal + realEstat + credit + other;

            mortcalculate()
            profcalculate()

        }

        //-----------------------------------------------
        function mortcalculate() {
            var morpre = parseInt(document.getElementById("morpre").value);
            var debt = parseInt(document.getElementById("debt").value);


            document.getElementById("morcos").value = debt * (morpre / 100);
        }


        //--------------------------------------------------

        function setCheckCost() {
            var realCost = parseInt(document.getElementById("realcost").value);

            document.getElementById("check").value = realCost;

            incresecalculate();
        }

        //-----------------------------------------------
        function profcalculate() {
            var propre = parseInt(document.getElementById("propre").value);
            var debt = parseInt(document.getElementById("debt").value);


            document.getElementById("procos").value = debt * (propre / 100);
        }


        ////////////////////////////////////////////

        //-------------End tsaheel function -------------------

        //--------------CHECK MOBILE------------------------
        function changeMobile() {
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').removeClass('btn-success');
            $('#checkMobile').removeClass('btn-danger');
            $('#checkMobile').addClass('btn-info');

        }

        $(document).on('click', '#checkMobile', function (e) {

            $('#checkMobile').attr("disabled", true);
            document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


            var mobile = document.getElementById('mobile').value;
            /*var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

            console.log(regex.test(mobile));*/

            if (mobile != null /*&& regex.test(mobile)*/) {
                document.getElementById('error').innerHTML = "";

                $.post("{{ route('all.checkMobile') }}", {
                    mobile: mobile
                }, function (data) {
                    if (data == "error") {
                        document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                        document.getElementById('error').display = "block";
                        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                        $('#checkMobile').attr("disabled", false);
                    }
                    if ($.trim(data) == "no") {
                        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                        $('#checkMobile').removeClass('btn-info');
                        $('#checkMobile').addClass('btn-success');
                        $('#checkMobile').attr("disabled", false);
                    } else {
                        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                        $('#checkMobile').removeClass('btn-info');
                        $('#checkMobile').addClass('btn-danger');
                        $('#checkMobile').attr("disabled", false);
                    }


                }).fail(function (data) {


                });


            } else {

                document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                document.getElementById('error').display = "block";
                document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                $('#checkMobile').attr("disabled", false);

            }


        });

        //--------------END CHECK MOBILE------------------------


        //------------PREPAYMENT---------------------------

        $(document).ready(function () {

            var status = document.getElementById("statusPayment").value;
            var statusReq = document.getElementById("statusRequest").value;


            if (statusReq == 6 || statusReq == 13) {
                if (status == 5) { //send to mortgage
                    document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to mortgage manager') }}";
                    document.getElementById('sendingWarning').style.display = "block";
                }


                if (status == 4) { //wating sales agent
                    document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales agent') }}";
                    document.getElementById('sendingWarning').style.display = "block";
                }


                if (status == 1) { //send to sales maanger
                    document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales manager') }}";
                    document.getElementById('sendingWarning').style.display = "block";
                }


                if (status == 2) { //canceled payment
                    document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment canceled from funding manager') }}";
                    document.getElementById('archiveWarning').style.display = "block";
                }

                if (status == 3) { //rejected payment
                    document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment has rejected and back to sales agent') }}";
                    document.getElementById('rejectWarning').style.display = "block";
                }

                if (status == 7) { //approved
                    document.getElementById('approveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment approved and redirect to funding maanger') }}";
                    document.getElementById('approveWarning').style.display = "block";
                }

                if (status == 10) { //rejected payment
                    document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment has rejected and back to mortgage manager') }}";
                    document.getElementById('rejectWarning').style.display = "block";
                }

                if (status == 6) {
                    document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment rejected from mortgage manager') }}";
                    document.getElementById('rejectWarning').style.display = "block";
                }

                if (status == 8) { //canceled payment
                    document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment canceled from mortgage manager') }}";
                    document.getElementById('archiveWarning').style.display = "block";
                }

                if (status == 9) { //approved
                    document.getElementById('approveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment is completed') }}";
                    document.getElementById('approveWarning').style.display = "block";
                }

            }

        });


        //--------------------------------------
        //--------------------------------------
        function incresecalculate() {
            var check = document.getElementById("check").value;
            var real = document.getElementById("real").value;
            document.getElementById("incr").value = (check - real);
        }

        //------------------------------------
        function preCoscalculate() {
            var prepaymentValue = parseInt(document.getElementById("preval").value);
            var presentage = parseInt(document.getElementById("prepre").value);
            document.getElementById("precos").value = prepaymentValue + (prepaymentValue * (presentage / 100));
        }

        //---------------------------------------
        function showTsaheel() {
            var x = document.getElementById("tsaheeldiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
                /*
                document.getElementById("visa").value = "";
                document.getElementById("carlo").value = "";
                document.getElementById("perlo").value = "";
                document.getElementById("realo").value = "";
                document.getElementById("credban").value = "";
                document.getElementById("other1").value = "";
                document.getElementById("debt").value = "";
                document.getElementById("morpre").value = "";
                document.getElementById("morcos").value = "";
                document.getElementById("propre").value = "";
                document.getElementById("procos").value = "";
                document.getElementById("valadd").value = "";
                document.getElementById("admfe").value = "";
                */
            }
        }

        //----------------------------------------
        $('#frm-update').on('click', '#updatePay', function (e) {
            var reqID = $('#updatePay').attr('data-id');
            var real = document.getElementById("real").value;
            var incr = document.getElementById("incr").value;
            var preval = document.getElementById("preval").value;
            var prepre = document.getElementById("prepre").value;
            var precos = document.getElementById("precos").value;
            var net = document.getElementById("net").value;
            var deficit = document.getElementById("deficit").value;
            var visa = document.getElementById("visa").value;
            var carlo = document.getElementById("personal_mortgage").value;
            var perlo = document.getElementById("perlo").value;
            var realo = document.getElementById("realo").value;
            var credban = document.getElementById("credban").value;
            var other = document.getElementById("other1").value;
            var debt = document.getElementById("debt").value;
            var morpre = document.getElementById("morpre").value;
            var morcos = document.getElementById("morcos").value;
            var propre = document.getElementById("propre").value;
            var procos = document.getElementById("procos").value;
            var valadd = document.getElementById("valadd").value;
            var admfe = document.getElementById("admfe").value;
            var disposition_tsaheel = parseInt(document.getElementById("Real_estate_disposition_value_tsaheel").value);
            var purchase_tax_value_tsaheel = parseInt(document.getElementById("purchase_tax_value_tsaheel").value);
            $.post("{{ route('agent.updatePrepayment')}}", {
                reqID: reqID,
                real: real,
                incr: incr,
                preval: preval,
                prepre: prepre,
                precos: precos,
                net: net,
                deficit: deficit,
                visa: visa,
                carlo: carlo,
                perlo: perlo,
                realo: realo,
                credban: credban,
                other: other,
                debt: debt,
                morpre: morpre,
                morcos: morcos,
                propre: propre,
                procos: procos,
                valadd: valadd,
                admfe: admfe,
                disposition_tsaheel: disposition_tsaheel,
                purchase_tax_value_tsaheel: purchase_tax_value_tsaheel,
            }, function (data) {
                var url = '{{ route("agent.fundingRequest", ":reqID") }}';
                url = url.replace(':reqID', data.id);
                if (data.status == 1) {
                    window.location.href = url; //using a named route
                } else {
                    $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'Nothing Change') }}");
                }
            });
        });

        //--------------------------------------

        function checkCollaborator(that) {


            if (that.value == 2) {


                $('#collaboratorDiv2').removeAttr('hidden');


            } else {

                $('#collaboratorDiv2').attr('hidden', true);
                document.getElementById("collaborator").value = "";
            }
        }

        //----------------------------


        function debtcalculate() {
            var visa = parseInt(document.getElementById("visa").value);
            var car = parseInt(document.getElementById("carlo").value);


            var personal = parseInt(document.getElementById("perlo").value);
            var realEstat = parseInt(document.getElementById("realo").value);

            var credit = parseInt(document.getElementById("credban").value);
            var other = parseInt(document.getElementById("other1").value);


            document.getElementById("debt").value = visa + car + personal + realEstat + credit + other;
            var disposition_tsaheel = parseInt(document.getElementById("Real_estate_disposition_value_tsaheel").value);
            var purchaseTaxTsaheel = parseInt(document.getElementById("purchase_tax_value_tsaheel").value);
            document.getElementById("debt").value = visa + car + personal + realEstat + credit + other + disposition_tsaheel + purchaseTaxTsaheel;

            mortcalculate()
            profcalculate()

        }

        //-----------------------------------------------
        function mortcalculate() {
            var morpre = parseInt(document.getElementById("morpre").value);
            var debt = parseInt(document.getElementById("debt").value);


            document.getElementById("morcos").value = debt * (morpre / 100);
        }

        //-----------------------------------------------
        function profcalculate() {
            var propre = parseInt(document.getElementById("propre").value);
            var debt = parseInt(document.getElementById("debt").value);


            document.getElementById("procos").value = debt * (propre / 100);
        }

        //--------------End PREPAYMENT-----------------
    </script>

    <!--  NEW 2/2/2020 hijri datepicker  -->
    <script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
        <script type="text/javascript">
            $(function () {
                $("#hijri-date").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });

                $("#hijri-date1").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });

                $("#hijri_date_caculater").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });

                $("#joint_hijri_date_caculater").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });

                $("#job_tenure_caculater").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });

                $("#joint_job_tenure_caculater").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });


                $("#hiring_date").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });
                $("#joint_hiring_date").hijriDatePicker({
                    hijri: true,
                    format: "YYYY/MM/DD",
                    hijriFormat: 'iYYYY-iMM-iDD',
                    showSwitcher: false,
                    showTodayButton: true,
                    showClose: true
                });
            });
        </script>


    <script type="text/javascript">
        $("#convertToHij").click(function () {
            // alert($("#birth").val());
            if ($("#birth").val() == "") {
                alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
            } else {
                $.ajax({
                    url: "{{ URL('all/convertToHijri') }}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "gregorian": $("#birth").val(),
                    },
                    success: function (response) {
                        // alert(response);
                        $("#hijri-date").val($.trim(response));
                    },
                    error: function () {
                        swal({
                            title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                            text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                            html: true,
                            type: "error",
                        });
                    }
                });
            }
        });

        $("#convertToGreg").click(function () {

            if ($("#hijri-date").val() == "") {
                alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
            } else {
                $.ajax({
                    url: "{{ URL('all/convertToGregorian') }}",
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "hijri": $("#hijri-date").val(),
                    },
                    success: function (response) {

                        // alert(response);
                        $("#birth").val($.trim(response));
                        calculate();
                    },
                    error: function () {
                        swal({
                            title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                            text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                            html: true,
                            type: "error",
                        });
                    }
                });
            }
        });
        $('body').on('click', '#msg2 ul li', function(){
            var liCont = $(this).attr('data-key');
            var text = $('[name="'+liCont+'"]').closest('.hdie-show').attr('id');
            var liBtn = text.replace(/-.*/,'');
            $('#'+liBtn).click();
            $('[name="'+liCont+'"]').parent().find('input').focus();
        })
    </script>
    @include('Helpers.autocomplete-districts')
    @include('Helpers.new_addPhoneScript')
    @include('MortgageCalculator.mortgageCalculatorScript')

    <script>
      initFileUploader("#zdrop");

      function initFileUploader(target) {
        var previewNode = document.querySelector("#zdrop-template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);
        var eleName = $(".inputFileDropzone").val();
        console.log("eleName:", eleName);
        var zdrop = new Dropzone(target, {
          url: "/agent/uploadfile",
          previewTemplate: previewTemplate,
          previewsContainer: "#previews",
          params: {'id':'{{$id}}', '_token':'{{csrf_token()}}'},
          clickable: "#upload-label",
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                formData.append("name", $("#inputFileDropzone").val());
                console.log(document.querySelector("#inputFileDropzone").value)
                });
            },
          success: function(file, response) {
            console.log(response);
            $('#inputFileDropzone').val('');
            $('#st').html('');
            $.each(response, function (i, value) { //for loop , I put data as value

                        var docID = value.id;

                        var url = '{{ route("agent.openFile", ":docID") }}';
                        url = url.replace(':docID', docID);
                        var url2 = '{{ route("agent.downFile", ":docID") }}';
                        url2 = url2.replace(':docID', docID);


                        //  alert(docID);
                        var fn = $("<tr/>").attr('id', value.id); // if i want to add html tag or anything in html i have to define as verible $(); <tr/> : that mean create start and close tage; att (..,..) : mean add attrubite to this tage , here i add id attribute to use it in watever i want
                        fn.append($("<td/>", {
                            text: value.name
                        })).append($("<td/>", {
                            text: value.filename
                        })).append($("<td/>", {
                            text: value.upload_date
                        })).append($("<td/>", {
                            html: " <div class='tableAdminOption'><span  class='item pointer' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}'> <a href='" + url + "' target='_blank'> <i class='fa fa-eye'></i></a></span><span  class='item pointer' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}'><a href='" + url2 + "' target='_blank'><i class='fa fa-download'></i></a></span><span id='delete' data-id=" + docID + " class='item pointer'  data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}'><i class='fa fa-trash'></i></span></div>"
                        }))
                        $('#st').append(fn);
                    })
          }
        });

        zdrop.on("addedfile", function (file) {
            // $('#inputFileDropzone').val('');

        //   $(".preview-container").fadeIn();
        //   // let today = new Date().toLocaleDateString()
        //   $(".usernameFileDropzone").text("احمد محمد");
        //   $(".nameFileDropzone").text($(".inputFileDropzone").val());
        //   $(".dateFileDropzone").text(new Date().toLocaleDateString());
        });
      }
    </script>

    <script>
        // khaled
        $(document).ready(function(){
            var has_property_value = $('input[type=radio][name=realeva]:checked').val();
            if(has_property_value == 'نعم'){
                $('.real_estate_is_evaluated').css('display','block')
            }else{
                $('.real_estate_is_evaluated').css('display','none')
            }
        })

        $('input[type=radio][name=realeva]').change(function() {
            if (this.value == 'نعم') {
                $('.real_estate_is_evaluated').css('display','block')
            }
            else {
                // $('input[type=radio][name=financing_or_tsaheel]').val('');
                // $('#evaluation_amount').val('');
                $('.real_estate_is_evaluated').css('display','none')
            }
        });
    </script>

@endsection


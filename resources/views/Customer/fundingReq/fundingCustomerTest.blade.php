<div class="col-lg-12   mb-md-0">
    <div class="userFormsInfo  ">
        <div class="headER topRow text-center">
            <i class="fas fa-user"></i>
            <h4>بيانات العميل</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">

                @php
                $workValue='';
                @endphp

                <div class="row">
              
                    @foreach( (DB::table('settings')->where('option_name','LIKE','customerReq_customer'.'%')->where('option_value', 'show')->get()) as $filed )


                    @if (in_array( $filed->option_name , $arrFields))

                    @php
                    $coulmnName =App\Http\Controllers\CustomerController::fileds($filed->option_name);
                    $coulmnValue= $purchaseCustomer->$coulmnName;
                    @endphp

                    <!--TO STORE WORK VALUE-->
                    @if(($coulmnName == 'work' ))
                    @php
                    $workValue=$coulmnValue;
                    @endphp
                    @endif
                    <!-- END TO STORE WORK VALUE-->


                    @if(($workValue == 'مدني' && $coulmnName == 'madany_id' ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach ($madany_works as $madany_work )
                            @if ( $coulmnValue == $madany_work->id )
                            <input readonly type="text" class="form-control" value="{{$madany_work->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach

                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
                            @endif


                        </div>
                    </div>




                    @elseif(($workValue == 'عسكري' && $coulmnName == 'askary_id' ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach ($askary_works as $askary_work )
                            @if ( $coulmnValue== $askary_work->id )
                            <input readonly type="text" class="form-control" value="{{$askary_work->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach


                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
                            @endif


                        </div>
                    </div>


                    @elseif(($workValue == 'عسكري' && $coulmnName == 'military_rank' ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach ($ranks as $rank)
                            @if ($coulmnValue == $rank->id )
                            <input readonly type="text" class="form-control" value="{{$rank->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach

                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
                            @endif


                        </div>
                    </div>


                    @elseif(($workValue != 'عسكري' && $coulmnName == 'askary_id' ))

                    <!--break ;-->

                    @elseif(($workValue != 'عسكري' && $coulmnName == 'military_rank' ))

                    <!--break ;-->


                    @elseif(($workValue != 'مدني' && $coulmnName == 'madany_id' ))

                    <!--break ;-->

                    @elseif(($workValue != 'مدني' && $coulmnName == 'job_title' ))

                    <!--break ;-->

                    @elseif(($coulmnName == 'salary_id' && ($coulmnValue != null || $coulmnValue != '') ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach ($salary_sources as $salary_source )
                            @if ($coulmnValue == $salary_source->id)
                            <input readonly type="text" class="form-control" value="{{$salary_source->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach

                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
                            @endif


                        </div>
                    </div>


                    @elseif(( ($coulmnName == 'has_financial_distress' || $coulmnName == 'has_obligations' || $coulmnName == 'is_supported') && ($coulmnValue != null || $coulmnValue != '') ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>
                            @if ($coulmnValue == 'no')
                            <input readonly type="text" class="form-control" value="لا">
                            @else
                            <input readonly type="text" class="form-control" value="نعم">
                            @endif

                        </div>
                    </div>

                    @else
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>
                            <input readonly type="text" class="form-control" value="{{$coulmnValue}}">
                        </div>
                    </div>

                    @endif

                    @endif

                    @endforeach

                </div>
            </div>


            @if($purchaseJoint->name != null)

            @include('Customer.fundingReq.fundingJointTest')

            @endif



        </div>
    </div>
</div>
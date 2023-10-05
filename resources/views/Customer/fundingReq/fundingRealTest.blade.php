<div class="col-lg-12 mb-5 mb-md-0">
    <div class="userFormsInfo  ">
        <div class="headER topRow text-center">
            <i class="fas fa-home"></i>
            <h4>بيانات العقار</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">
                <div class="row">


                

                    @foreach( (DB::table('settings')->where('option_name','LIKE','customerReq_real'.'%')->where('option_value', 'show')->get()) as $filed )


                    @if (in_array( $filed->option_name , $arrFields))

                    @php
                    $coulmnName =App\Http\Controllers\CustomerController::fileds($filed->option_name);
                    $coulmnValue= $purchaseReal->$coulmnName;
                    @endphp


                    @if(($coulmnName == 'city' && ($coulmnValue != null || $coulmnValue != '') ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach ($cities as $citiy)
                            @if ($coulmnValue == $citiy->id )
                            <input readonly type="text" class="form-control" value="{{$citiy->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach

                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
                            @endif


                        </div>
                    </div>

                    @elseif(($coulmnName == 'type' && ($coulmnValue != null || $coulmnValue != '') ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach($realTypes as $realType)
                            @if ($coulmnValue == $realType->id )
                            <input readonly type="text" class="form-control" value="{{$realType->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach

                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
                            @endif


                        </div>
                    </div>

                    @elseif((  ($coulmnName == 'owning_property' || $coulmnName == 'has_property' || $coulmnName == 'evaluated' || $coulmnName == 'tenant' || $coulmnName == 'mortgage' ) && ($coulmnValue != null || $coulmnValue != '') ))
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

        </div>
    </div>
</div>
<div class="col-lg-12 mb-5 mb-md-0">
    <div class="userFormsInfo  ">
        <div class="headER topRow text-center ">
            <i class="fas fa-briefcase"></i>
            <h4>بيانات التمويل</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">
                <div class="row">


                    @foreach( (DB::table('settings')->where('option_name','LIKE','customerReq_funding'.'%')->where('option_value', 'show')->get()) as $filed )


                    @if (in_array( $filed->option_name , $arrFields))

                    @php
                    $coulmnName =App\Http\Controllers\CustomerController::fileds($filed->option_name);
                    $coulmnValue= $purchaseFun->$coulmnName;
                    @endphp


                    @if(($coulmnName == 'funding_source' && ($coulmnValue != null || $coulmnValue != '') ))
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>

                            @php $check= false; @endphp

                            @foreach ($funding_sources as $funding_source )
                            @if ($coulmnValue == $funding_source->id )
                            <input readonly type="text" class="form-control" value="{{$funding_source->value}}">
                            @php $check= true; @endphp
                            @endif
                            @endforeach

                            @if(!$check)
                            <input readonly type="text" class="form-control" value="">
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
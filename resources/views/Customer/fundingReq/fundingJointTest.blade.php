<div class="infoKafil topRow mt-5">
    <div class="kafilHeader">
        <div class="addBtn">
            <button class="w-100">

                <i class="fas fa-plus-circle"></i>
                بيانات المتضامن

            </button>
        </div>
    </div>
    <div class="userFormsDetails  mt-3">

        <div class="row">


            @foreach( (DB::table('settings')->where('option_name','LIKE','customerReq_joint'.'%')->where('option_value', 'show')->get()) as $filed )


            @if (in_array( $filed->option_name , $arrFields))

            @php
            $coulmnName =App\Http\Controllers\CustomerController::fileds($filed->option_name);
            $coulmnValue= $purchaseJoint->$coulmnName;
            @endphp


            @if(($coulmnName == 'salary_id' && ($coulmnValue != null || $coulmnValue != '') ))
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

            @elseif(($coulmnName == 'funding_id' && ($coulmnValue != null || $coulmnValue != '') ))
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
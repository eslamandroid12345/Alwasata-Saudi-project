<div class="col-lg-12 mb-5 mb-md-0">
    <div class="userFormsInfo  ">
        <div class="headER topRow text-center ">
            <i class="fas fa-briefcase"></i>
            <h4>بيانات الدفعة وتساهيل</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">
                <div class="row">


                    @foreach( (DB::table('settings')->where('option_name','LIKE','customerReq_prepayment'.'%')->where('option_value', 'show')->get()) as $filed )


                    @if (in_array( $filed->option_name , $arrFields))

                    @php
                    $coulmnName =App\Http\Controllers\CustomerController::fileds($filed->option_name);
                    $coulmnValue= $payment->$coulmnName;
                    @endphp


                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>{{$filed->display_name}}</label>
                            <input readonly type="text" class="form-control" value="{{$coulmnValue}}">
                        </div>
                    </div>


                    @endif

                    @endforeach


                </div>
            </div>

        </div>
    </div>
</div>
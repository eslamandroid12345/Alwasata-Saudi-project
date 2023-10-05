<div class="order_status text-center mt-3">
    @if(\App\AskAnswer::where(['batch' => 0,'request_id' => $request->id,'customer_id' => auth('customer')->user()->id])->count() ==0)


    @if ($classID == 9)
    <div class="col-md-8 mt-4 offset-md-2 text-center">
        <h5> تريد إرفاقها؟</h5>
        <form method="post" action="{{route('CustomernewChat')}}">
            @csrf
            <input type="hidden" name="receivers[]" value=" {{App\Http\Controllers\CustomerController::salesAgent()}}" />
            <input type="hidden" name="redirect" value="1" />
            <div class="mess__item" onclick="$(this).closest('form').submit();">
                <span class="find-own" id="wantToUplad">نعم ، لدي الأوراق المطلوبة </span>
            </div>
        </form>
    </div>
    @elseif ($classID == 15)
    <div class="col-md-8 mt-4 offset-md-2 text-center" id="foundPropertyDiv">
        @if ($request->customer_found_property == 0)
        <h5> هل وجدت عقار؟</h5>
        <button class="find-own" id="foundProperty">نعم ، وجدت عقار </button>
        <br>
        <span style="color:crimson;" id="foundPropertyError"></span>
        @else
        <h5> سيتم التواصل معك قريبا</h5>
        @endif
    </div>
    @elseif ($classID == 13)
    @if(\App\AskAnswer::where(['batch' => 0,'request_id' => $request->id,'customer_id' => auth('customer')->user()->id])->count() ==0)
    <div class="col-md-8 mt-4 offset-md-2 text-center">
        @if ($request->customer_want_to_reject_req === null)

        <h5> هل أنت راغب فعلًا من إلغاء الطلب؟</h5>
        <a href="{{route('customer.survey.index',$request->id)}}" class="find-own" style="background-color: #ff3333;">نعم ، أرغب </a>
        <button class="find-own" id="noIwant"> لا ، أريد إكمال طلبي </button>

        <br><br>
        <div id="rejectReason" style="display: none;">
            <div class="form-group">
                <label>من فضلك ، أذكر السبب :</label>
                <input type="text" class="form-control" name="reason" id="reason">
                <span id="sendReasonError"></span>
                <br><br>
                <button id="sendReason" class="btn btn-primary">إرسال</button>
            </div>
        </div>

        <span id="updateRejectStatus"></span>

        @elseif ($request->customer_want_to_reject_req === 0)
        <h5> سيتم التواصل معك قريبا</h5>
        @elseif ($request->customer_want_to_reject_req == 1 && $request->customer_reason_for_rejected != null)
        <h5> شكرا لك</h5>
        @endif
        @else

        @endif

    </div>

    @elseif ($classID == 16)
    <div class="col-md-8 mt-4 offset-md-2 text-center">
        @if ($request->customer_resolve_problem === null)
        <h5>هل تم حل المشكلة؟</h5>
        <button class="find-own" id="yesResolveProblem">نعم ، حللت المشكلة </button>
        <br>
        <span style="color:crimson;" id="ResolveProblemError"></span>
        @else
        <h5> سيتم التواصل معك قريبا</h5>
        @endif
    </div>

    @endif
    @endif
</div>
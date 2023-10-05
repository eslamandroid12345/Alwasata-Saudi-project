<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h3>
                </div>
                <hr>


                <div class="form-group">
                    <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</label>
                    <select id="nameid" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); changeCustomer(this);' value="{{ old('customer') }}" class="form-control @error('customer') is-invalid @enderror" name="customer">

                        <option value="">---</option>
                        @if ($customer != null)

                        @foreach ($mycustomers as $mycustomer )

                        @if ($customer->id == $mycustomer->id )
                        <option value="{{$mycustomer->id}}" selected>{{$mycustomer->name}}</option>
                        @else
                        <option value="{{$mycustomer->id}}">{{$mycustomer->name}}</option>
                        @endif

                        @endforeach


                        @else

                        @foreach ($mycustomers as $mycustomer )

                        <option value="{{$mycustomer->id}}">{{$mycustomer->name}}</option>

                        @endforeach

                        @endif
                    </select>

                    @error('customer')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>


                @if ($customer != null)
                <div class="row">

                    <div class="col-4">
                        <div class="form-group">
                            <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                            <input readonly id="name" name="name" type="text" class="form-control" value="{{ $customer->name }}" autocomplete="name">

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="gender" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>
                            <input readonly id="gender" name="gender" type="text" class="form-control" value="{{ $customer->sex }}" autocomplete="gender" autofocus readonly>

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="mobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                            <input id="mobile" name="mobile" type="tel" class="form-control" value="{{ $customer->mobile }}" autocomplete="mobile" autofocus readonly>

                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-6">

                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input readonly id="birth" style="text-align: right" value="{{ $customer->birth_date }}" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" autocomplete="birth" onblur="calculate()" autofocus>
                                <span class="input-group-btn">
                                    <button disabled type="button" id="convertToHij" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">

                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input readonly type='text' name="birth_hijri" value="{{ $customer->birth_date_higri }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                                <span class="input-group-btn">
                                    <button disabled type="button" id="convertToGreg" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                            <input readonly id="age" value="{{ $customer->age }}" name="age" type="text" class="form-control @error('age') is-invalid @enderror" autocomplete="age" autofocus readonly>
                            @error('age')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>


                @if(!empty ( $customer->madany_id ))
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                            <input id="work" name="work" type="text" class="form-control" value="{{ $customer->work }}" autocomplete="work" autofocus readonly>
                        </div>

                    </div>
                    <div class="col-4">
                        <div id="madany" class="form-group">
                            <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                            <select disabled id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('madany_work') is-invalid @enderror" name="madany_work">

                                @foreach ($madany_works as $madany_work )

                                @if ($customer->madany_id == $madany_work->id)
                                <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                                @else
                                <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                                @endif

                                @endforeach



                            </select>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group" id="madany1">
                            <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input id="job_title" name="job_title" type="text" class="form-control" value="{{ $customer->job_title }}" autocomplete="job_title" readonly>
                        </div>
                    </div>
                </div>


                @elseif(!empty ($customer->askary_id))
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                            <input id="work" name="work" type="text" class="form-control" value="{{ $customer->work }}" autocomplete="work" autofocus readonly>
                        </div>

                    </div>

                    <div class="col-4">
                        <div id="askary" class="form-group">
                            <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                            <select disabled id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('askary_work') is-invalid @enderror" name="askary_work">

                                @foreach ($askary_works as $askary_work )

                                @if ($customer->askary_id == $askary_work->id)
                                <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                                @else
                                <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                                @endif

                                @endforeach



                            </select>

                        </div>
                    </div>

                    <div class="col-4">
                        <div id="askary1" class="form-group">
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>
                            <input id="rank" name="rank" type="text" class="form-control" value="{{ $customer->military_rank }}" autocomplete="rank" autofocus readonly>


                        </div>
                    </div>
                </div>

                @else
                <div class="form-group">
                    <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                    <input id="work" name="work" type="text" class="form-control" value="{{ $customer->work }}" autocomplete="work" autofocus readonly>
                </div>

                @endif


                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                            <input id="salary" name="salary" type="number" class="form-control" value="{{ $customer->salary }}" autocomplete="salary" readonly>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                            <select id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source" disabled>

                                @foreach ($salary_sources as $salary_source )

                                @if ($customer->salary_id == $salary_source->id)
                                <option value="{{$salary_source->id}}" selected>{{$salary_source->value}}</option>
                                @else
                                <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                                @endif

                                @endforeach



                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>
                            <input id="is_support" name="is_support" type="text" class="form-control" value="{{ $customer->is_supported }}" autocomplete="is_support" readonly>

                        </div>
                    </div>
                </div>

                <div class="row">
                <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="obligations" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>

                            <select id="has_obligations" name="has_obligations" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkObligation(this);' class="form-control @error('has_obligations') is-invalid @enderror">



                                <option value="">---</option>
                                <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected"  @endif>نعم</option>
                                <option value="no" @if (old('has_obligations')=='no' ) selected="selected"  @endif>لا</option>


                            </select>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="obligations_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="obligations_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                            <input id="obligations_value" name="obligations_value" type="number" class="form-control" @if (old('has_obligations')=='no') readonly @endif value="{{ old('obligations_value') }}" autocomplete="obligations_value">
                        </div>

                    </div>
                </div>

                <div class="row">
                <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="distress" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>

                            <select id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control @error('has_financial_distress') is-invalid @enderror">



                                <option value="">---</option>
                                <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected"  @endif>نعم</option>
                                <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected" @endif>لا</option>


                            </select>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="financial_distress_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="financial_distress_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                            <input id="financial_distress_value" name="financial_distress_value" type="number" class="form-control" @if (old('has_financial_distress')=='no'  || $purchaseCustomer->has_financial_distress == 'no' || $purchaseCustomer->has_financial_distress == null ) readonly @endif value="{{ old('financial_distress_value') }}" autocomplete="financial_distress_value">
                        </div>

                    </div>
                </div>


                @else

                <div class="row">

                    <div class="col-4">
                        <div class="form-group">
                            <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                            <input readonly id="name" name="name" type="text" class="form-control" autocomplete="name">

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="gender" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>
                            <input readonly id="gender" name="gender" type="text" class="form-control" autocomplete="gender" autofocus readonly>

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="mobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                            <input id="mobile" name="mobile" type="tel" class="form-control" autocomplete="mobile" autofocus readonly>

                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-6">

                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input readonly id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" autocomplete="birth" onblur="calculate()" autofocus>
                                <span class="input-group-btn">
                                    <button disabled type="button" id="convertToHij" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">

                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input readonly type='text' name="birth_hijri" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                                <span class="input-group-btn">
                                    <button disabled type="button" id="convertToGreg" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                            <input readonly id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" autocomplete="age" autofocus readonly>
                            @error('age')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                    <input id="work" name="work" type="text" class="form-control" autocomplete="work" autofocus readonly>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div id="madany" class="form-group">
                            <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                            <select disabled id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('madany_work') is-invalid @enderror" name="madany_work">


                                <option value="">-----</option>

                                @foreach ($madany_works as $madany_work )


                                <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>


                                @endforeach



                            </select>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group" id="madany1">
                            <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input id="job_title" name="job_title" type="text" class="form-control" autocomplete="job_title" readonly>
                        </div>
                    </div>
                </div>



                <div class="row">


                    <div class="col-6">
                        <div id="askary" class="form-group">
                            <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                            <select disabled id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('askary_work') is-invalid @enderror" name="askary_work">


                                <option value="">-----</option>
                                @foreach ($askary_works as $askary_work )

                                <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>


                                @endforeach



                            </select>

                        </div>
                    </div>

                    <div class="col-6">
                        <div id="askary1" class="form-group">
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>
                            <input id="rank" name="rank" type="text" class="form-control" autocomplete="rank" autofocus readonly>


                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                            <input id="salary" name="salary" type="number" class="form-control" autocomplete="salary" readonly>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                            <select id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source" disabled>


                                <option value="">-----</option>
                                @foreach ($salary_sources as $salary_source )

                                <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>


                                @endforeach



                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>
                            <input id="is_support" name="is_support" type="text" class="form-control" autocomplete="is_support" readonly>

                        </div>
                    </div>
                </div>

                <div class="row">
                <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="obligations" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>

                            <select disabled id="has_obligations" name="has_obligations" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('has_obligations') is-invalid @enderror">



                                <option value="">---</option>
                                <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected"  @endif>نعم</option>
                                <option value="no" @if (old('has_obligations')=='no' ) selected="selected" @endif>لا</option>


                            </select>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="obligations_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="obligations_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                            <input readonly id="obligations_value" name="obligations_value" type="number" class="form-control" value="{{ old('obligations_value') }}" autocomplete="obligations_value">
                        </div>

                    </div>
                </div>

                <div class="row">
                <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="distress" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>

                            <select disabled id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control @error('has_financial_distress') is-invalid @enderror">



                                <option value="">---</option>
                                <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected" @endif>نعم</option>
                                <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected"  @endif>لا</option>


                            </select>

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="financial_distress_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="financial_distress_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                            <input readonly id="financial_distress_value" name="financial_distress_value" type="number" class="form-control" value="{{ old('financial_distress_value') }}" autocomplete="financial_distress_value">
                        </div>

                    </div>
                </div>
                @endif

                <br><br>




                @include('Agent.AddFundingReq.jointInfo')

            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {

        var today = new Date().toISOString().split("T")[0];

        $('#jointbirth').attr("max", today);
        $('#birth1').attr("max", today);
        $('#hijri-date1').attr("max", today);

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

            document.getElementById("jointmadany").style.display = "none";
            document.getElementById("jointmadany1").style.display = "none";
            document.getElementById("jointaskary").style.display = "none";
            document.getElementById("jointaskary1").style.display = "none";

        }
    }
    //----------------------------
    function changeCustomer(that) {

        var id = that.value; //to pass customer id

        //alert(id);

        if (id != "") { // if not select any customer

            $.get("{{route('agent.getCustomerInfo')}}", {
                id: id
            }, function(data) {

                //console.log(data);
                document.getElementById("name").value = data[0].name;
                document.getElementById("mobile").value = data[0].mobile;
                document.getElementById("birth").value = data[0].birth_date;
                document.getElementById("hijri-date").value = data[0].birth_date_higri;
                document.getElementById("age").value = data[0].age;
                document.getElementById("gender").value = data[0].sex;
                document.getElementById("work").value = data[0].work;
                document.getElementById("salary").value = data[0].salary;
                document.getElementById("salary1").value = data[0].salary;
                document.getElementById("askary_work").value = data[0].askary_id;
                document.getElementById("rank").value = data[0].military_rank;
                document.getElementById("madany_work").value = data[0].madany_id;
                document.getElementById("job_title").value = data[0].job_title;
                document.getElementById("salary_source").value = data[0].salary_id;

                if (data[0].is_supported == 'yes')
                    document.getElementById("is_support").value = "{{ MyHelpers::admin_trans(auth()->user()->id,'yes') }}";
                else if (data[0].is_supported == 'no')
                    document.getElementById("is_support").value = "{{ MyHelpers::admin_trans(auth()->user()->id,'no') }}";
                else
                    document.getElementById("is_support").value = "";

            })
        } else {

            document.getElementById("name").value = "";
            document.getElementById("mobile").value = "";
            document.getElementById("birth").value = "";
            document.getElementById("hijri-date").value = "";
            document.getElementById("age").value = "";
            document.getElementById("gender").value = "";
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


    //------------------------------------
    function calculate1() {
        var date = new Date(document.getElementById('jointbirth').value);
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



        document.getElementById('jointage').value = ageString;
    }



    //----------------------------
    function check(that) {

        if (that.value == "عسكري") {

            document.getElementById("jointaskary").style.display = "block";
            document.getElementById("jointaskary1").style.display = "block";

            document.getElementById("jointmadany_work").value = "";
            document.getElementById("jointjob_title").value = "";
            document.getElementById("jointmadany").style.display = "none";
            document.getElementById("jointmadany1").style.display = "none";

        } else if (that.value == "مدني") {

            document.getElementById("jointaskary_work").value = "";
            document.getElementById("jointrank").value = "";
            document.getElementById("jointaskary").style.display = "none";
            document.getElementById("jointaskary1").style.display = "none";


            document.getElementById("jointmadany").style.display = "block";
            document.getElementById("jointmadany1").style.display = "block";
        } else {
            document.getElementById("jointaskary_work").value = "";
            document.getElementById("jointrank").value = "";

            document.getElementById("jointaskary").style.display = "none";
            document.getElementById("jointaskary1").style.display = "none";
            document.getElementById("jointmadany_work").value = "";
            document.getElementById("jointjob_title").value = "";
            document.getElementById("jointmadany").style.display = "none";
            document.getElementById("jointmadany1").style.display = "none";
        }
    }
    //----------------------------

    //----------------------------
    function check1(that) {


        if (that.value == "عسكري") {

            document.getElementById("askary3").style.display = "block";
            document.getElementById("askary4").style.display = "block";

            document.getElementById("madany_work3").value = "";
            document.getElementById("job_title3").value = "";
            document.getElementById("madany3").style.display = "none";
            document.getElementById("madany4").style.display = "none";

        } else if (that.value == "مدني") {

            document.getElementById("askary_work3").value = "";
            document.getElementById("rank3").value = "";
            document.getElementById("askary3").style.display = "none";
            document.getElementById("askary4").style.display = "none";


            document.getElementById("madany3").style.display = "block";
            document.getElementById("madany4").style.display = "block";
        } else {
            document.getElementById("askary_work3").value = "";
            document.getElementById("rank3").value = "";

            document.getElementById("askary3").style.display = "none";
            document.getElementById("askary4").style.display = "none";
            document.getElementById("madany_work3").value = "";
            document.getElementById("job_title3").value = "";
            document.getElementById("madany3").style.display = "none";
            document.getElementById("madany4").style.display = "none";
        }
    }
    //----------------------------

 //----------------------------
 function checkObligation(that) {
        if (that.value == "yes")
                document.getElementById("obligations_value").readOnly = false;
        else
        document.getElementById("obligations_value").readOnly = true;
    }

    //----------------------------

    function checkDistress(that) {
        if (that.value == "yes")
                document.getElementById("financial_distress_value").readOnly = false;
        else
        document.getElementById("financial_distress_value").readOnly = true;
    }

    //----------------------------

    //----------------------------
    function checkCollaborator(that) {


        if (that.value == 2) {


            document.getElementById("collaborator").disabled = false;


        } else {

            document.getElementById("collaborator").disabled = true;
            document.getElementById("collaborator").value = "";
        }
    }
    //----------------------------


    $(document).ready(function() {


        var ty = document.getElementById("reqtyp").value;


        if (ty == "شراء")
            document.getElementById('reqtypetitle').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}";
        if (ty == "رهن")
            document.getElementById('reqtypetitle').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}";


        $('input[name="realtype"]').click(function() {
            if ($(this).attr('id') == 'other') {
                document.getElementById("othervalue").style.display = "block";
            } else {
                document.getElementById("othervalue").style.display = "none";
            }
        });
    });

    //////////////////////////////////

    function monthlycalculate() {
        var pres = document.getElementById("dedp").value;
        var salary = document.getElementById("salary").value;

        document.getElementById("monthIn").value = ((pres * salary) / 100);
    }



    /////////////////////////////
    function checkType(that) {

        if (that.value == "شراء") {

            document.getElementById('reqtypetitle').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Funding') }}";;

        } else if (that.value == "رهن") {
            document.getElementById('reqtypetitle').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Request') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}";
        }
    }



    //////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////

    $('#frm-update').on('submit', function(e) {




        $('#nameError').addClass("d-none");
        $('#mobileError').addClass("d-none");
        $('#birthError').addClass("d-none");
        $('#workError').addClass("d-none");
        $('#salary_sourceError').addClass("d-none");
        $('#salaryError').addClass("d-none");
        document.getElementById('error').innerHTML = "";



        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr('action');


        $.post(url, data, function(data) { //data is array with two veribles (request[], ss)

            if (data.ss != 'error') {

                var o = new Option(data.request.name, data.ss);
                $(o).html(data.request.name);
                $("#nameid").append(o);


                changeCustomer(o);

                document.getElementById("nameid").value = data.ss;


                $('#myModal').modal('hide');


            } else if (data.ss == false) {

                document.getElementById('errorSubmit').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}";
                document.getElementById('errorSubmit').display = "block";
            } else {
                document.getElementById('errorSubmit').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Mobile Already Existed') }}";
                document.getElementById('errorSubmit').display = "block";
            }



        }).fail(function(data) {

            var errors = data.responseJSON;

            if ($.isEmptyObject(errors) == false) {

                $.each(errors.errors, function(key, value) {

                    var ErrorID = '#' + key + 'Error';
                    $(ErrorID).removeClass("d-none");
                    $(ErrorID).text(value);

                })

            }
        });

    });
</script>









<!--  NEW 2/2/2020 hijri datepicker  -->
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function() {
        $("#hijri-date1").hijriDatePicker({
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
    $("#convertToHij1").click(function() {
        // alert($("#birth1").val());
        if ($("#birth1").val() == "") {
            alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
        } else {
            $.ajax({
                url: "{{ URL('all/convertToHijri') }}",
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "gregorian": $("#birth1").val(),
                },
                success: function(response) {
                    // alert(response);
                    $("#hijri-date1").val(response);
                },
                error: function() {
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

    $("#convertToGreg1").click(function() {

        if ($("#hijri-date1").val() == "") {
            alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
        } else {
            $.ajax({
                url: "{{ URL('all/convertToGregorian') }}",
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "hijri": $("#hijri-date1").val(),
                },
                success: function(response) {
                    // alert(response);
                    $("#birth1").val(response);
                    calculate();
                },
                error: function() {
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



    ////////////////////////////////////////////////
    //--------------CHECK MOBILE------------------------
    function changeMobile() {
        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile').removeClass('btn-success');
        $('#checkMobile').removeClass('btn-danger');
        $('#checkMobile').addClass('btn-info');

    }

    $(document).on('click', '#checkMobile', function(e) {



        $('#checkMobile').attr("disabled", true);
        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


        var mobile = document.getElementById('mobile1').value;

        /*var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

        console.log(regex.test(mobile));*/

        if (mobile != null /*&& regex.test(mobile)*/) {
            document.getElementById('error').innerHTML = "";

            $.post("{{ route('all.checkMobile') }}", {
                mobile: mobile
            }, function(data) {
                if (data.errors) {
                    if (data.errors.mobile) {
                        $('#mobile-error').html(data.errors.mobile[0])
                    }
                } if (data == "no") {
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


            }).fail(function(data) {


            });



        } else {
            document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
            document.getElementById('error').display = "block";
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').attr("disabled", false);

        }



    });

    //--------------END CHECK MOBILE------------------------


    function calculate() {
        var date = new Date(document.getElementById('birth1').value);
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


        document.getElementById('age1').value = ageString;


    }

    //----------------------------

    $(document).on('click', '#addCustomer', function(e) {

        $("#nameid").select2("close"); // because  noResults msg not close list after click on
        test(); // need to call other function to closing the list and after preview Model

    });

    function test() {

        $('#nameError').addClass("d-none");
        $('#mobileError').addClass("d-none");
        $('#birthError').addClass("d-none");
        $('#sexError').addClass("d-none");
        $('#workError').addClass("d-none");
        $('#salary_sourceError').addClass("d-none");
        $('#salaryError').addClass("d-none");
        document.getElementById('error').innerHTML = "";


        $('#myModal').modal('show');
    }
</script>






<script type="text/javascript">
    $("#nameid").select2({
        placeholder: "{{ MyHelpers::admin_trans(auth()->user()->id,'Select a Customer') }}",
        allowClear: true,
        // closeOnSelect: false,

        language: {
            noResults: function() {
                return " <p>{{ MyHelpers::admin_trans(auth()->user()->id,'No Result Found') }}</p> <p class='asLink' id='addCustomer' > {{ MyHelpers::admin_trans(auth()->user()->id,'Add a Customer') }} </p>";
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }

    });

    //---------------------------------------
</script>


@endsection

@section('updateModel')
@include('Agent.AddFundingReq.addCustomer')
@endsection

<div class="userFormsInfo  ">
    <div class="headER topRow text-center">
        <i class="fas fa-user"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
    </div>

    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div  id="tableAdminOption" class="row">
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="customerName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                        <input readonly id="name" name="name" type="text" class="form-control" value="{{$purchaseCustomer->name}}" autocomplete="name">

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="sex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select disabled id="sex" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('sex') is-invalid @enderror" name="sex">

                            @if($purchaseCustomer->sex == 'ذكر')
                                <option value="">---</option>
                                <option value="ذكر" selected>ذكر</option>
                                <option value="أنثى">أنثى</option>

                            @elseif ($purchaseCustomer->sex == 'أنثى')
                                <option value="">---</option>
                                <option value="ذكر">ذكر</option>
                                <option value="أنثى" selected>أنثى</option>

                            @else
                                <option value="">---</option>
                                <option value="ذكر">ذكر</option>
                                <option value="أنثى">أنثى</option>
                            @endif


                        </select>

                        @error('sex')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror


                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="mobile" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20"></i></span>
                        <label for="Customer" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}
                            <small disabled style="cursor: not-allowed" id="checkMobile" role="button" type="button" class="item badge badge-info pointer has-tooltip disabled"  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                <i class="fas fa-question i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                            </small>
                        </label>
                        <input readonly id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ $purchaseCustomer->mobile }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                        @error('mobile')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                        <small class="text-danger" id="error" role="alert"> </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="birth_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20"></i></span>
                        <label for="birth" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small disabled style="cursor: not-allowed" id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip disabled"  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                            </small>
                        </label>
                        <input readonly id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ $purchaseCustomer->birth_date }}" autocomplete="birth" onblur="calculate()" autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="birth_hijri" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20"></i></span>
                        <label for="birth_hijri" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small  id="convertToGreg" role="button" type="button"  class="item badge badge-info pointer has-tooltip disabled"  disabled style="cursor: not-allowed"  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                            </small>
                        </label>
                        <input readonly type='text' name="birth_hijri" value="{{ $purchaseCustomer->birth_date_higri }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                        <input readonly id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ $purchaseCustomer->age }}" autocomplete="age" autofocus readonly>
                        @error('age')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="work" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                        <select disabled id="work" onchange="this.size=1; this.blur(); checkWork(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2-request @error('work') is-invalid @enderror" value="{{ old('work') }}" name="work">

                            <option value="">---</option>
                            @foreach ($madany_works as $madany_work )
                            @if ($purchaseCustomer->madany_id == $madany_work->id || (old('madany_work') == $madany_work->id) )
                            <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                            @else
                            <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                            @endif
                            @endforeach

                        </select>
                    </div>
                </div>
                @if(($purchaseCustomer->work == 2 ))
                <div class="col-6">
                    <div id="madany2" class="form-group">
                        <span class="item pointer span-20" id="record" data-id="madanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                        <select disabled id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

                            <option value="">---</option>
                            @foreach ($madany_works as $madany_work )
                                @if ($purchaseCustomer->madany_id == $madany_work->id )
                                    <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                                @else
                                    <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group" id="madany3">
                        <span class="item pointer span-20" id="record" data-id="jobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                        <input readonly id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->job_title }}" autocomplete="job_title">
                    </div>
                </div>
                @elseif($purchaseCustomer->work != 2 )
                <div class="col-6" id="madany" style="display: none;">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="madanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                        <select disabled id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

                            <option value="">---</option>
                            @foreach ($madany_works as $madany_work )
                                @if ($purchaseCustomer->madany_id == $madany_work->id )
                                    <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                                @else
                                    <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6" id="madany1" style="display: none;">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="jobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                                <input readonly id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->job_title }}" autocomplete="job_title">
                            </div>
                        </div>
                @endif
                @if(($purchaseCustomer->work ==1))
                <div class="col-6" id="askary2">

                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="askaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                        <select disabled id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

                            <option value="">---</option>
                            @foreach ($askary_works as $askary_work )
                                @if ($purchaseCustomer->askary_id == $askary_work->id )
                                    <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                                @else
                                    <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>

                </div>
                <div class="col-6" id="askary3">

                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="rank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                        <select disabled id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">


                            <option value="" selected>---</option>
                            @foreach ($ranks as $rank)
                                @if ($purchaseCustomer->military_rank == $rank->id || (old('rank') == $rank->id) )
                                    <option value="{{$rank->id}}" selected>{{$rank->value}}</option>
                                @else
                                    <option value="{{$rank->id}}">{{$rank->value}}</option>
                                @endif
                            @endforeach


                        </select>


                    </div>

                </div>
                @elseif($purchaseCustomer->work !=1)
                <div class="col-6" id="askary" style="display:none;">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="askaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                        <select disabled id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

                            <option value="">---</option>
                            @foreach ($askary_works as $askary_work )
                                @if ($purchaseCustomer->askary_id == $askary_work->id )
                                    <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                                @else
                                    <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>

                </div>
                <div class="col-6" id="askary1" style="display:none;">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="rank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                        <select disabled id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">


                            <option value="" selected>---</option>
                            @foreach ($ranks as $rank)
                                @if ($purchaseCustomer->military_rank == $rank->id || (old('rank') == $rank->id) )
                                    <option value="{{$rank->id}}" selected>{{$rank->value}}</option>
                                @else
                                    <option value="{{$rank->id}}">{{$rank->value}}</option>
                                @endif
                            @endforeach


                        </select>


                    </div>

                </div>
                @endif
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        <input readonly id="salary" name="salary" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary">
                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="salary_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select disabled id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source">
                            <option selected>---</option>
                            @foreach ($salary_sources as $salary_source )
                                <option value="{{$salary_source->id}}" {{$purchaseCustomer->salary_id == $salary_source->id ? "selected" : ""}}> {{$salary_source->value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                        <select disabled id="is_support" name="is_support" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('is_support') is-invalid @enderror">

                            @if( $purchaseCustomer->is_supported == 'yes')
                                <option value="">---</option>
                                <option value="yes" selected>نعم</option>
                                <option value="no">لا</option>

                            @elseif ($purchaseCustomer->sex == 'no')
                                <option value="">---</option>
                                <option value="yes">نعم</option>
                                <option value="no" selected>لا</option>

                            @else
                                <option value="">---</option>
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            @endif


                        </select>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="obligations" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>

                        <select disabled id="has_obligations" name="has_obligations" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('has_obligations') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected" @elseif ($purchaseCustomer->has_obligations == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_obligations')=='no' ) selected="selected" @elseif ($purchaseCustomer->has_obligations == 'no') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="obligations_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="obligations_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                        <input readonly id="obligations_value" name="obligations_value" type="number" class="form-control" value="{{ old('obligations_value',$purchaseCustomer->obligations_value) }}" autocomplete="obligations_value">
                    </div>

                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="distress" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>

                        <select disabled id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control @error('has_financial_distress') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected" @elseif ($purchaseCustomer->has_financial_distress == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected" @elseif ($purchaseCustomer->has_financial_distress == 'no') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="financial_distress_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="financial_distress_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                        <input readonly id="financial_distress_value" name="financial_distress_value" type="number" class="form-control" value="{{ old('financial_distress_value',$purchaseCustomer->financial_distress_value) }}" autocomplete="financial_distress_value">
                    </div>

                </div>
            </div>
        </div>
        @include('MortgageManager.fundingWithoutEdit.fundingJoint')
    </div>


</div>


@section('scripts')

<script>

    $(document).ready(function() {

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
        }, function(data) {});

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

        } else if (that.value == "مدني") {

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

        } else if (that.value == "مدني") {

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
            }, function(data) {

                document.getElementById("mobile").value = data[0].mobile;
                document.getElementById("birth").value = data[0].birth_date;
                document.getElementById("age").value = data[0].age;
                document.getElementById("hijri-date").value = data[0].birth_date_higri;
                document.getElementById("work").value = data[0].work;
                document.getElementById("salary").value = data[0].salary;
                document.getElementById("salary1").value = data[0].salary;
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

    //--------------------------------------------------

    $(document).ready(function() {
        $('input[name="realtype"]').click(function() {
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

    $(document).on('click', '#record', function(e) {

        var coloum = $(this).attr('data-id');
        var reqID = document.getElementById("reqID").value;

        // var body = document.getElementById("records");

        $.get("{{ route('all.reqRecords') }}", {
            coloum: coloum,
            reqID: reqID
        }, function(data) {

            $('#records').empty();



            //console.log(data);

            if (data.status == 1) {




                $.each(data.histories, function(i, value) {

                    var fn = $("<tr/>").attr('id', value.id);

                      var name = '';

                    if (value.comment == null) {

                        if (value.switch != null)
                            name = value.switch+' / ' + value.name;
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
                });



                // body.append(fn)

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



        }).fail(function(data) {


            document.getElementById('archiveWarning').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}!";
            document.getElementById('archiveWarning').style.display = "block";


        });


    })

    /////////////////////////////////

    $(document).on('click', '#upload', function(e) {

        $('#nameError').text('');
        $('#fileError').text('');
        document.getElementById("filename").value = "";
        document.getElementById("file").value = "";
        //alert('h');
        $('#myModal1').modal('show');

    })

    //////////////////////////////

    $('#file-form').submit(function(event) {

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
            success: function(response) {

                // console.log(response);
                $('#myModal1').modal('hide');

                $('#st').empty(); // to prevent dublicate of same data in each click , so i will empty before start the loop!
                $.each(response, function(i, value) { //for loop , I put data as value

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
                        html: " <div class='table-data-feature'><button type='button' class='item' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}'> <a href='" + url + "' target='_blank'> <i class='zmdi zmdi-eye'></i></a></button><button type='button' class='item' data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}'><a href='" + url2 + "' target='_blank'><i class='zmdi zmdi-download'></i></a></button><button id='delete' type='button' data-id=" + docID + " class='item'  data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}'><i class='zmdi zmdi-delete'></i></button></div>"
                    }))
                    $('#st').append(fn);
                })


            },
            error: function(xhr) {

                var errors = xhr.responseJSON;

                if ($.isEmptyObject(errors) == false) {

                    $.each(errors.errors, function(key, value) {

                        var ErrorID = '#' + key + 'Error';
                        // $(ErrorID).removeClass("d-none");
                        $(ErrorID).text(value);

                    })

                }

            }
        });

    });
    ///////////////////////////////////

    $(document).on('click', '#delete', function(e) {

        var id = $(this).attr('data-id');

        var modalConfirm = function(callback) {


            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {
                callback(true);
                $("#mi-modal").modal('hide');
            });

            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                $.post("{{ route('agent.deleFile') }}", {
                    id: id
                }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response

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
    $(document).on('click', '#send', function(e) {


        var checktype = document.getElementById("reqtyp").value;
        var checkSource = document.getElementById("reqsour").value;

        if (document.getElementById("collaborator") != null)
            var checkColl = document.getElementById("collaborator").value;
        else
            var checkColl = null;


        if (checktype != '' && checkSource != '') {

            if (checkSource != 2 || (checkSource == 2 && checkColl != '')) {

                var id = $(this).attr('data-id');

                var modalConfirm = function(callback) {


                    $("#mi-modal2").modal('show');


                    $("#modal-btn-si2").on("click", function() {
                        callback(true);
                        $("#mi-modal2").modal('hide');

                    });

                    $("#modal-btn-no2").on("click", function() {
                        callback(false);
                        $("#mi-modal2").modal('hide');


                    });
                };

                modalConfirm(function(confirm) {
                    if (confirm) {
                        var comment = document.getElementById("comment").value;
                        $.post("{{ route('agent.sendFunding')}}", {
                            id: id,
                            comment: comment,
                            checktype: checktype,
                            checkSource: checkSource,
                            checkColl: checkColl,
                        }, function(data) {

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


    });

    //////////////////////////////////////////

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

    $(document).on('click', '#checkMobile', function(e) {

        $('#checkMobile').attr("disabled", true);
        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


        var mobile = document.getElementById('mobile').value;
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


    //------------PREPAYMENT---------------------------

    $(document).ready(function() {

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

        }
    }

    //----------------------------------------

    $('#frm-update').on('click', '#updatePay', function(e) {

        var reqID = $('#updatePay').attr('data-id');



        var real = document.getElementById("real").value;
        var incr = document.getElementById("incr").value;
        var preval = document.getElementById("preval").value;
        var prepre = document.getElementById("prepre").value;
        var precos = document.getElementById("precos").value;
        var net = document.getElementById("net").value;
        var deficit = document.getElementById("deficit").value;


        var visa = document.getElementById("visa").value;
        var carlo = document.getElementById("carlo").value;
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
        }, function(data) {

            var url = '{{ route("agent.fundingRequest", ":reqID") }}';
            url = url.replace(':reqID', data.id);

            if (data.status == 1) {
                window.location.href = url; //using a named route

            } else {

                $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'Nothing Change') }}");

            }

        });


        // console.log(data);

    });

    //--------------------------------------

    function checkCollaborator(that) {


        if (that.value == 2) {


            document.getElementById("collaboratorDiv2").style.display = "block";


        } else {

            document.getElementById("collaboratorDiv2").style.display = "none";
            document.getElementById("collaborator").value = "";
        }
    }
    //----------------------------



    //-----------------------------------------------
    function debtcalculate() {
        var visa = parseInt(document.getElementById("visa").value);
        var car = parseInt(document.getElementById("carlo").value);


        var personal = parseInt(document.getElementById("perlo").value);
        var realEstat = parseInt(document.getElementById("realo").value);

        var credit = parseInt(document.getElementById("credban").value);
        var other = parseInt(document.getElementById("other1").value);


        document.getElementById("debt").value = visa + car + personal + realEstat + credit + other;

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


    //-----------------------------------------------

    $(document).on('click', '#sendPay', function(e) {



        var id = $(this).attr('data-id');


        var modalConfirm = function(callback) {


            $("#mi-modal7").modal('show');


            $("#modal-btn-si7").on("click", function() {
                callback(true);
                $("#mi-modal7").modal('hide');
            });

            $("#modal-btn-no7").on("click", function() {
                callback(false);
                $("#mi-modal7").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                $.get("{{ route('agent.sendPrepayment')}}", {
                    id: id
                }, function(data) {
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



    //--------------End PREPAYMENT-----------------
</script>

<!--  NEW 2/2/2020 hijri datepicker  -->
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function() {
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
    });
</script>

<script type="text/javascript">
    $("#convertToHij").click(function() {
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
                success: function(response) {
                    // alert(response);
                    $("#hijri-date").val($.trim(response));
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

    $("#convertToGreg").click(function() {

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
                success: function(response) {
                    // alert(response);
                     $("#birth").val($.trim(response));
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
</script>



@endsection

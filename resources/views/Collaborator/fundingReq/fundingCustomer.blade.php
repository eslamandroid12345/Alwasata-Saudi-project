<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">


            @if ($purchaseCustomer-> is_canceled == 0 && ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 4))
            <!-- Status req of Sales agent-->
            <div class="card-body">

                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h3>
                </div>
                <hr>


                <div class="form-group">
                    <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</label>
                    <select disabled onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); changeCustomer(this);' class="form-control @error('Customer') is-invalid @enderror" name="customer">
                        <option>{{$purchaseCustomer->name}}</option>

                    </select>



                </div>

                <div class="row">

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="customerName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{$purchaseCustomer->name}}" autocomplete="name">

                        </div>
                    </div>

                    <div class="col-6">

                        <div class="form-group">
                            <button class="item" id="record" data-id="sex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                            <select id="sex" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('sex') is-invalid @enderror" name="sex">

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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror


                        </div>
                    </div>

                </div>

                <button class="item" id="record" data-id="mobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                <div class="input-group form-group">

                    <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ $purchaseCustomer->mobile }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <span class="input-group-btn">
                        <button type="button" id="checkMobile" class="btn btn-info btn-block">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                        </button>
                    </span>
                </div>

                <span class="text-danger" id="error" role="alert"> </span>



                <div class="row">
                    <div class="col-6">
                    <button class="item" id="record" data-id="birth_date" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ $purchaseCustomer->birth_date }}" autocomplete="birth" onblur="calculate()" autofocus>
                                <span class="input-group-btn">
                                    <button type="button" id="convertToHij" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                    <button class="item" id="record" data-id="birth_hijri" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input type='text' name="birth_hijri" value="{{ $purchaseCustomer->birth_date_higri }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                                <span class="input-group-btn">
                                    <button type="button" id="convertToGreg" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                            <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ $purchaseCustomer->age }}" autocomplete="age" autofocus readonly>
                            @error('age')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="form-group">
                    <button class="item" id="record" data-id="work" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                    <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                    <select id="work" onchange="this.size=1; this.blur(); checkWork(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2-request @error('work') is-invalid @enderror" value="{{ old('work') }}" name="work">

                        @if ($purchaseCustomer->work == 'عسكري')
                        <option value="">---</option>
                        <option value="عسكري" selected>عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @elseif ($purchaseCustomer->work == 'مدني')
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني" selected>مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @elseif ($purchaseCustomer->work == 'قطاع خاص')
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص" selected>قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @elseif ($purchaseCustomer->work == 'متقاعد')
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد" selected>متقاعد</option>
                        @else
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @endif
                    </select>
                </div>


                @if(!empty ( $purchaseCustomer->madany_id ))
                <div class="row">
                    <div class="col-6">
                        <div id="madany2" class="form-group">
                            <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                            <select id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

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
                            <button class="item" id="record" data-id="jobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->job_title }}" autocomplete="job_title">
                        </div>
                    </div>
                </div>

                @elseif(empty ( $purchaseCustomer->madany_id ))
                <div class="row">


                    <div class="col-6" id="madany" style="display: none;">
                        <div class="form-group">
                            <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                            <select id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

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
                            <button class="item" id="record" data-id="jobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->job_title }}" autocomplete="job_title">
                        </div>
                    </div>
                </div>

                @endif


                @if(!empty ($purchaseCustomer->askary_id))
                <div class="row">


                    <div class="col-6" id="askary2">

                        <div class="form-group">
                            <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                            <select id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

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
                            <button class="item" id="record" data-id="rank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                            <select id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">

                                @if ($purchaseCustomer->military_rank == 'جندي')
                                <option value="">---</option>
                                <option value="جندي" selected>جندي</option>
                                <option value="رقيب">رقيب</option>
                                @elseif ($purchaseCustomer->military_rank == 'رقيب')
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب" selected>رقيب</option>
                                @else
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب">رقيب</option>
                                @endif


                            </select>


                        </div>

                    </div>
                </div>


                @elseif(empty ($purchaseCustomer->askary_id))
                <div class="row">

                    <div class="col-6" id="askary" style="display:none;">
                        <div class="form-group">
                            <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                            <select id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

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
                            <button class="item" id="record" data-id="rank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                            <select id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">

                                @if ($purchaseCustomer->military_rank == 'جندي')
                                <option value="">---</option>
                                <option value="جندي" selected>جندي</option>
                                <option value="رقيب">رقيب</option>
                                @elseif ($purchaseCustomer->military_rank == 'رقيب')
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب" selected>رقيب</option>
                                @else
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب">رقيب</option>
                                @endif


                            </select>


                        </div>

                    </div>
                </div>

                @endif


                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <button class="item" id="record" data-id="salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}<</label>
                            <input id="salary" name="salary" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary">
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                            <select id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source">
                                <option selected>---</option>
                                @foreach ($salary_sources as $salary_source )
                                    <option value="{{$salary_source->id}}" {{$purchaseCustomer->salary_id == $salary_source->id ? "selected" : ""}}> {{$salary_source->value}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <button class="item" id="record" data-id="support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                            <select id="is_support" name="is_support" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('is_support') is-invalid @enderror">

                                @if( $purchaseCustomer->is_supported == 'yes')
                                <option value="">---</option>
                                <option value="yes" selected>نعم</option>
                                <option value="no">لا</option>

                                @elseif ($purchaseCustomer->is_supported == 'no')
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
                </div>

                <br><br>

                @include('Collaborator.fundingReq.fundingJoint')




            </div>

            @else

            <div class="card-body">

                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h3>
                </div>
                <hr>


                <div class="form-group">
                    <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</label>
                    <select disabled onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); changeCustomer(this);' class="form-control @error('Customer') is-invalid @enderror" name="customer">
                        <option>{{$purchaseCustomer->name}}</option>

                    </select>



                </div>

                <div class="row">

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="customerName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                            <input readonly id="name" name="name" type="text" class="form-control" value="{{$purchaseCustomer->name}}" autocomplete="name">

                        </div>
                    </div>

                    <div class="col-6">

                        <div class="form-group">
                            <button class="item" id="record" data-id="sex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror


                        </div>
                    </div>

                </div>

                <button class="item" id="record" data-id="mobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                <div class="input-group form-group">

                    <input readonly id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ $purchaseCustomer->mobile }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <span class="input-group-btn">
                        <button disabled type="button" id="checkMobile" class="btn btn-info btn-block">
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                        </button>
                    </span>
                </div>

                <span class="text-danger" id="error" role="alert"> </span>



                <div class="row">
                    <div class="col-6">
                    <button class="item" id="record" data-id="birth_date" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input readonly id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ $purchaseCustomer->birth_date }}" autocomplete="birth" onblur="calculate()" autofocus>
                                <span class="input-group-btn">
                                    <button disabled type="button" id="convertToHij" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                    <button class="item" id="record" data-id="birth_hijri" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input readonly type='text' name="birth_hijri" value="{{ $purchaseCustomer->birth_date_higri }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
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
                            <input readonly id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ $purchaseCustomer->age }}" autocomplete="age" autofocus readonly>
                            @error('age')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="form-group">
                    <button class="item" id="record" data-id="work" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history" style="font-size: medium;"></i></button>
                    <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                    <select disabled id="work" onchange="this.size=1; this.blur(); checkWork(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2-request @error('work') is-invalid @enderror" value="{{ old('work') }}" name="work">

                        @if ($purchaseCustomer->work == 'عسكري')
                        <option value="">---</option>
                        <option value="عسكري" selected>عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @elseif ($purchaseCustomer->work == 'مدني')
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني" selected>مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @elseif ($purchaseCustomer->work == 'قطاع خاص')
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص" selected>قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @elseif ($purchaseCustomer->work == 'متقاعد')
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد" selected>متقاعد</option>
                        @else
                        <option value="">---</option>
                        <option value="عسكري">عسكري</option>
                        <option value="مدني">مدني</option>
                        <option value="قطاع خاص">قطاع خاص</option>
                        <option value="متقاعد">متقاعد</option>
                        @endif
                    </select>
                </div>


                @if(!empty ( $purchaseCustomer->madany_id ))
                <div class="row">
                    <div class="col-6">
                        <div id="madany2" class="form-group">
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
                            <button class="item" id="record" data-id="jobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input readonly id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->job_title }}" autocomplete="job_title">
                        </div>
                    </div>
                </div>

                @elseif(empty ( $purchaseCustomer->madany_id ))
                <div class="row">


                    <div class="col-6" id="madany" style="display: none;">
                        <div class="form-group">
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
                            <button class="item" id="record" data-id="jobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input readonly id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->job_title }}" autocomplete="job_title">
                        </div>
                    </div>
                </div>

                @endif


                @if(!empty ($purchaseCustomer->askary_id))
                <div class="row">


                    <div class="col-6" id="askary2">

                        <div class="form-group">
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
                            <button class="item" id="record" data-id="rank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                            <select disabled id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">

                                @if ($purchaseCustomer->military_rank == 'جندي')
                                <option value="">---</option>
                                <option value="جندي" selected>جندي</option>
                                <option value="رقيب">رقيب</option>
                                @elseif ($purchaseCustomer->military_rank == 'رقيب')
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب" selected>رقيب</option>
                                @else
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب">رقيب</option>
                                @endif


                            </select>


                        </div>

                    </div>
                </div>


                @elseif(empty ($purchaseCustomer->askary_id))
                <div class="row">

                    <div class="col-6" id="askary" style="display:none;">
                        <div class="form-group">
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
                            <button class="item" id="record" data-id="rank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                            <select disabled id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">

                                @if ($purchaseCustomer->military_rank == 'جندي')
                                <option value="">---</option>
                                <option value="جندي" selected>جندي</option>
                                <option value="رقيب">رقيب</option>
                                @elseif ($purchaseCustomer->military_rank == 'رقيب')
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب" selected>رقيب</option>
                                @else
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب">رقيب</option>
                                @endif


                            </select>


                        </div>

                    </div>
                </div>

                @endif


                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <button class="item" id="record" data-id="salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                            <input readonly id="salary" name="salary" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary">
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                            <select disabled id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source">

                                @foreach ($salary_sources as $salary_source )

                                @if ($purchaseCustomer->salary_id == $salary_source->id)
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
                            <button class="item" id="record" data-id="support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                </div>

                <br><br>

                @include('Collaborator.fundingReq.fundingJoint')




            </div>

            @endif

        </div>
    </div>
</div>

@section('scripts')

<script>
    //---------------------to show wraning msg---------------
    $(document).ready(function() {

        var status = document.getElementById("statusRequest").value;
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

        if (status == 5) { //in sales manager
            document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by Sales Manager, you cannot edit anything') }}";
            document.getElementById('sendingWarning1').style.display = "block";
        }


        if (status == 8) { //archived in funding manager
            document.getElementById('sendingWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived by Funding Manager, you cannot edit anything') }}";
            document.getElementById('sendingWarning1').style.display = "block";
        }


     /*   if (status == 15) { //canceled from general manager
            document.getElementById('archiveWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has canceled') }}  , <a href='{{ route('general.manager.reCancelFunding',$id)}}'> {{ MyHelpers::admin_trans(auth()->user()->id,'Recancel?') }} </a>";
            document.getElementById('archiveWarning1').style.display = "block";
        }*/


        if (status == 3) { //sending to sales manager
            document.getElementById('sendingWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has sent to Sales Manager, you cannot edit anything') }}";
            document.getElementById('sendingWarning1').style.display = "block";
        }

        if (status == 4) { //reject from sales manager
            document.getElementById('rejectWarning1').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request has rejected from Sales Manager and redirect to Sales Agent') }}";
            document.getElementById('rejectWarning1').style.display = "block";
        }
        if (status == 2) { //archived in sales agent
            document.getElementById('archiveWarning1').innerHTML = "<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The request is archived, you cannot edit anything until restore it!') }}";
            document.getElementById('archiveWarning1').style.display = "block";
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


    });

    //------------------------------------

    $(document).ready(function() {

        var today = new Date().toISOString().split("T")[0];

        $('#jointbirth').attr("max", today);
        $('#birth').attr("max", today);
        $('#jointbirth').attr("max", today);
        $('#follow').attr("min", today + 'T00:00:00');



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

    function checkWork(that) {
        if (that.value == "عسكري") {


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

        if (id != "") { // if not select any customer

            $.get("{{ route('collaborator.getCustomerInfo')}}", {
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
            document.getElementById("hijri-date").value ="";
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



            if (data.status == 1) {



                $.each(data.histories, function(i, value) {

                    var fn = $("<tr/>").attr('id', value.id);

                    fn.append($("<td/>", {
                        text: value.name
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
        document.getElementById("name").value = "";
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
            url: "{{ route('collaborator.uploadFile')}}",
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

                    var url = '{{ route("collaborator.openFile", ":docID") }}';
                    url = url.replace(':docID', docID);
                    var url2 = '{{ route("collaborator.downFile", ":docID") }}';
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

                $.post("{{ route('collaborator.deleFile') }}", {
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
                $.post("{{ route('collaborator.sendFunding')}}", {
                    id: id,
                    comment: comment
                }, function(data) {

                    var url = '{{ route("collaborator.fundingRequest", ":reqID") }}';
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


    });


    ////////////////////////////////////


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
        var regex = new RegExp(/^(05)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

        console.log(regex.test(mobile));

        if (mobile != null && regex.test(mobile)) {
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
            document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (10 digits) and starts 05') }} ";
            document.getElementById('error').display = "block";
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').attr("disabled", false);

        }



    });

    //--------------END CHECK MOBILE------------------------


    //------------PREPAYMENT---------------------------

    $(document).ready(function() {

        var status = document.getElementById("statusPayment").value;


        if (status == 5) { //send to mortgage
            document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to mortgage manager') }}";
            document.getElementById('sendingWarning').style.display = "block";
        }

        if (status == 4) { //wating sales collaborator
            document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales agent') }}";
            document.getElementById('sendingWarning').style.display = "block";
        }

        if (status == 1) { //send to mortgage
            document.getElementById('sendingWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment send to sales manager') }}";
            document.getElementById('sendingWarning').style.display = "block";
        }


        if (status == 2) { //canceled payment
            document.getElementById('archiveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment canceled') }}";
            document.getElementById('archiveWarning').style.display = "block";
        }

        if (status == 3) { //rejected payment
            document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button>  {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment has rejected and back to funding manager') }}";
            document.getElementById('rejectWarning').style.display = "block";
        }

        if (status == 7) { //approved
            document.getElementById('approveWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment approved') }}";
            document.getElementById('approveWarning').style.display = "block";
        }

        if (status == 6) {
            document.getElementById('rejectWarning').innerHTML = " <button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'The prepayment rejected from mortgage manager') }}";
            document.getElementById('rejectWarning').style.display = "block";
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
        var admfe = document.getElementById("admfe").value = "";



        $.post("{{ route('collaborator.updatePrepayment')}}", {
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

            var url = '{{ route("collaborator.fundingRequest", ":reqID") }}';
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

                $.get("{{ route('collaborator.sendPrepayment')}}", {
                    id: id
                }, function(data) {
                    var url = '{{ route("collaborator.fundingRequest", ":reqID") }}';
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


    //-----------------------------------------------


    //--------------End PREPAYMENT-----------------


    /////////////////////////////////////////
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

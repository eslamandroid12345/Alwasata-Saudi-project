<div class="userFormsInfo  ">
    <div class="headER topRow text-center">
        <i class="fas fa-user"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
            <div id="tableAdminOption" class=" row">
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="customerName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                        <input id="name" name="name" type="text" class="form-control name_missedFiledInput FiledInput" value="{{  old('name', $purchaseCustomer->customer->name) }}" autocomplete="name" >


                        <small style="color:#e60000" class="d-none name_missedFileds missedFileds">الحقل مطلوب</small>


                    </div>
                </div>
                <div class="col-6">

                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="sex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select id="sex" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control sex_missedFiledInput FiledInput @error('sex') is-invalid @enderror" name="sex" >


                            <option value="">---</option>
                            <option value="ذكر" @if (old('sex')=='ذكر' ) selected="selected" @elseif ($purchaseCustomer->customer->sex == 'ذكر') selected="selected" @endif>ذكر</option>
                            <option value="أنثى" @if (old('sex')=='أنثى' ) selected="selected" @elseif ($purchaseCustomer->customer->sex == 'أنثى') selected="selected" @endif>أنثى</option>



                        </select>

                        <small style="color:#e60000" class="d-none sex_missedFileds missedFileds">الحقل مطلوب</small>


                        @error('sex')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror




                    </div>
                </div>
                <div class="col-11">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="mobile" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="Customer">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}
                        </label>
                        <input id="mobile" name="mobile" readonly type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ $purchaseCustomer->customer->mobile }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                        @error('mobile')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                        <small class="text-danger" id="error" role="alert"> </small>
                    </div>
                </div>
                <div class="col-md-1 mb-0">
                    <label for="Customer" >
                        إضافة
                    </label>
                    <div class="btn-group">
                        <a onclick="addForm()" data-toggle="modal"  class="btn text-white btn-primary btn-sm  p-1"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="birth_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip " title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                            </small>
                        </label>
                        <input id="birth" style="text-align: right" name="birth" type="date" class="form-control birth_missedFiledInput FiledInput @error('birth') is-invalid @enderror" value="{{ $purchaseCustomer->customer->birth_date }}" autocomplete="birth" onblur="calculate()" autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="birth_hijri" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth_hijri">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small id="convertToGreg" role="button" type="button" class="item badge badge-info pointer has-tooltip " title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                            </small>
                        </label>
                        <input type='text' name="birth_hijri" value="{{ $purchaseCustomer->customer->birth_date_higri }}" style="text-align: right;" class="form-control birth_hijri_missedFiledInput FiledInput" placeholder="يوم/شهر/سنة" id="hijri-date" />
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                        <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age',$purchaseCustomer->age) }}" autocomplete="age" autofocus readonly>
                        @error('age')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="age_years" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="age_years" class="control-label mb-1">العمر بالسنوات</label>
                        <input id="age_years" name="age_years" type="number" class="form-control @error('age_years') is-invalid @enderror" value="{{ old('age_years',$purchaseCustomer->customer->age_years) }}" autocomplete="age_years" autofocus>
                        @error('age_years')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="work" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                        <select id="work" onchange="this.size=1; this.blur(); checkWork(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control work_missedFiledInput FiledInput select2-request @error('work') is-invalid @enderror" name="work" >


                            <option value="">---</option>

                            @foreach ($worke_sources as $worke_source )
                            @if ($purchaseCustomer->customer->work == $worke_source->id || (old('work') == $worke_source->id) )
                            <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                            @else
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                            @endif
                            @endforeach

                        </select>

                        <small style="color:#e60000" class="d-none work_missedFileds missedFileds">الحقل مطلوب</small>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="hiring_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                            <label for="hiring_date" class="control-label mb-1">تاريخ التعيين</label>
                        <input id="hiring_date" style="text-align: right" name="hiring_date" type="text" class="form-control @error('hiring_date') is-invalid @enderror" value="{{ $purchaseCustomer->customer->hiring_date }}" autocomplete="hiring_date" >
                    </div>
                </div>
                @if($purchaseCustomer->customer->work == 2)

                <div class="col-6">
                    <div id="madany2" class="form-group">
                        <span class="item pointer span-20" id="record" data-id="madanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                        <select id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" name="madany_work">

                            <option value="">---</option>

                            @foreach ($madany_works as $madany_work )
                            @if ($purchaseCustomer->customer->madany_id == $madany_work->id || (old('madany_work') == $madany_work->id) )
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
                        <input id="job_title" name="job_title" type="text" class="form-control" value="{{ old('job_title',$purchaseCustomer->customer->job_title) }}" autocomplete="job_title">
                    </div>
                </div>

                @elseif($purchaseCustomer->customer->work != 2 )

                <div class="col-6" id="madany" style="display: none;">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="madanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                        <select id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" name="madany_work">

                            <option value="">---</option>

                            @foreach ($madany_works as $madany_work )
                            @if ($purchaseCustomer->customer->madany_id == $madany_work->id || (old('madany_work') == $madany_work->id) )
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
                        <input id="job_title" name="job_title" type="text" class="form-control" value="{{ old('job_title',$purchaseCustomer->customer->job_title) }}" autocomplete="job_title">
                    </div>
                </div>

                @endif
                @if( ($purchaseCustomer->customer->work == 1))
                <div class="col-6" id="askary2">

                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="askaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                        <select id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

                            <option value="">---</option>
                            @foreach ($askary_works as $askary_work )
                            @if ($purchaseCustomer->customer->askary_id == $askary_work->id || (old('askary_work') == $askary_work->id))
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

                        <select id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">


                            <option value="" selected>---</option>
                            @foreach ($ranks as $rank)
                            @if ($purchaseCustomer->customer->military_rank == $rank->id || (old('rank') == $rank->id) )
                            <option value="{{$rank->id}}" selected>{{$rank->value}}</option>
                            @else
                            <option value="{{$rank->id}}">{{$rank->value}}</option>
                            @endif
                            @endforeach

                        </select>



                    </div>

                </div>
                @elseif($purchaseCustomer->customer->work != 1)

                <div class="col-6" id="askary" style="display:none;">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="askaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                        <select id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

                            <option value="">---</option>
                            @foreach ($askary_works as $askary_work )
                            @if ($purchaseCustomer->customer->askary_id == $askary_work->id || (old('askary_work') == $askary_work->id))
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

                        <select id="rank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">


                            <option value="" selected>---</option>
                            @foreach ($ranks as $rank)
                            @if ($purchaseCustomer->customer->military_rank == $rank->id || (old('rank') == $rank->id) )
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
                        <label for="salary" class="control-label mb-1">صافي الراتب
                        </label>
                        <input id="salary" name="salary" type="number" class="form-control salary_missedFiledInput FiledInput" value="{{ old('salary',$purchaseCustomer->customer->salary) }}" autocomplete="salary" >

                        <small style="color:#e60000" class="d-none salary_missedFileds missedFileds">الحقل مطلوب</small>


                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="salary_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control salary_source_missedFiledInput FiledInput select2-request @error('salary_source') is-invalid @enderror" name="salary_source">
                            <option selected>---</option>
                            @foreach ($salary_sources as $salary_source )
                                <option value="{{$salary_source->id}}" {{$purchaseCustomer->salary_id == $salary_source->id ? "selected" : ""}}> {{$salary_source->value}}</option>
                            @endforeach
                        </select>

                        <small style="color:#e60000" class="d-none salary_source_missedFileds missedFileds">الحقل مطلوب</small>


                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>

                        <select id="is_support" name="is_support" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control is_support_missedFiledInput FiledInput @error('is_support') is-invalid @enderror" >



                            <option value="">---</option>
                            <option value="yes" @if (old('is_support')=='yes' ) selected="selected" @elseif ($purchaseCustomer->customer->is_supported == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('is_support')=='no' ) selected="selected" @elseif ($purchaseCustomer->customer->is_supported == 'no') selected="selected" @endif>لا</option>


                        </select>

                        <small style="color:#e60000" class="d-none is_support_missedFileds missedFileds">الحقل مطلوب</small>


                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="basic_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="basic_salary" class="control-label mb-1">الراتب الأساسي
                        </label>
                        <input id="basic_salary" name="basic_salary" type="number" class="form-control" value="{{ old('basic_salary',$purchaseCustomer->customer->basic_salary) }}" autocomplete="basic_salary">

                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="without_transfer_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="without_transfer_salary" class="control-label mb-1">بدون تحويل الراتب</label>

                        <select id="without_transfer_salary" name="without_transfer_salary" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('without_transfer_salary') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('without_transfer_salary')=='1' ) selected="selected" @elseif ($purchaseCustomer->customer->without_transfer_salary == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('without_transfer_salary')=='0' ) selected="selected" @elseif ($purchaseCustomer->customer->without_transfer_salary == '0') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="add_support_installment_to_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="add_support_installment_to_salary" class="control-label mb-1">إضافة قسط الدعم إلى الراتب</label>

                        <select id="add_support_installment_to_salary" name="add_support_installment_to_salary" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('add_support_installment_to_salary') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('add_support_installment_to_salary')=='1' ) selected="selected" @elseif ($purchaseCustomer->add_support_installment_to_salary == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('add_support_installment_to_salary')=='0' ) selected="selected" @elseif ($purchaseCustomer->add_support_installment_to_salary == '0') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="guarantees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="guarantees" class="control-label mb-1">الضمانات</label>

                        <select id="guarantees" name="guarantees" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('guarantees') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('guarantees')=='1' ) selected="selected" @elseif ($purchaseCustomer->customer->guarantees == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('guarantees')=='0' ) selected="selected" @elseif ($purchaseCustomer->customer->guarantees == '0') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="obligations" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>

                        <select id="has_obligations" name="has_obligations" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkObligation(this);' class="form-control has_obligations_missedFiledInput FiledInput @error('has_obligations') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected" @elseif ($purchaseCustomer->customer->has_obligations == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_obligations')=='no' ) selected="selected" @elseif ($purchaseCustomer->customer->has_obligations == 'no') selected="selected" @endif>لا</option>


                        </select>

                        <small style="color:#e60000" class="d-none has_obligations_missedFileds missedFileds">الحقل مطلوب</small>


                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="obligations_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="obligations_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                        <input id="obligations_value" name="obligations_value" type="number" class="form-control obligations_value_missedFiledInput FiledInput" @if (old('has_obligations')=='no' || $purchaseCustomer->customer->has_obligations == 'no' || $purchaseCustomer->customer->has_obligations == null ) readonly @endif value="{{ old('obligations_value',$purchaseCustomer->customer->obligations_value) }}" autocomplete="obligations_value" >

                        <small style="color:#e60000" class="d-none obligations_value_missedFileds missedFileds">الحقل مطلوب</small>

                    </div>

                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="distress" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>

                        <select id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control has_financial_distress_missedFiledInput FiledInput @error('has_financial_distress') is-invalid @enderror" >



                            <option value="">---</option>
                            <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected" @elseif ($purchaseCustomer->customer->has_financial_distress == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected" @elseif ($purchaseCustomer->customer->has_financial_distress == 'no') selected="selected" @endif>لا</option>


                        </select>


                        <small style="color:#e60000" class="d-none has_financial_distress_missedFileds missedFileds">الحقل مطلوب</small>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="financial_distress_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="financial_distress_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                        <input id="financial_distress_value" name="financial_distress_value" type="number" class="form-control has_financial_distress_missedFiledInput FiledInput" @if (old('has_financial_distress')=='no' || $purchaseCustomer->has_financial_distress == 'no' || $purchaseCustomer->has_financial_distress == null ) readonly @endif value="{{ old('financial_distress_value',$purchaseCustomer->financial_distress_value) }}" autocomplete="financial_distress_value" >


                        <small style="color:#e60000" class="d-none financial_distress_value_missedFileds missedFileds">الحقل مطلوب</small>

                    </div>

                </div>
            </div>
            @else
            <div id="tableAdminOption" class="row">
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="customerName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="Customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                        <input readonly id="name" name="name" type="text" class="form-control" value="{{$purchaseCustomer->customer->name}}" autocomplete="name">

                    </div>
                </div>
                <div class="col-6">

                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="sex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select disabled id="sex" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('sex') is-invalid @enderror" name="sex">

                            @if($purchaseCustomer->customer->sex == 'ذكر')
                            <option value="">---</option>
                            <option value="ذكر" selected>ذكر</option>
                            <option value="أنثى">أنثى</option>

                            @elseif ($purchaseCustomer->customer>sex == 'أنثى')
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
                        <span id="record" role="button" type="button" class="item span-20" data-id="mobile" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="Customer">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}
                        </label>
                        <input readonly id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ $purchaseCustomer->customer->mobile }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
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
                        <span id="record" role="button" type="button" class="item span-20" data-id="birth_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small disabled id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip " title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                            </small>
                        </label>
                        <input readonly id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ $purchaseCustomer->customer->birth_date }}" autocomplete="birth" onblur="calculate()" autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="birth_hijri" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth_hijri">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small disabled="" id="convertToGreg" role="button" type="button" class="item badge badge-info pointer has-tooltip " title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                            </small>
                        </label>
                        <input readonly type='text' name="birth_hijri" value="{{ $purchaseCustomer->birth_date_higri }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                        <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age',$purchaseCustomer->customer->age) }}" autocomplete="age" autofocus readonly>
                        @error('age')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="age_years" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="age_years" class="control-label mb-1">العمر بالسنوات</label>
                        <input id="age_years" name="age_years" type="number" class="form-control @error('age_years') is-invalid @enderror" value="{{ old('age_years',$purchaseCustomer->customer->age_years) }}" autocomplete="age_years" autofocus readonly>
                        @error('age_years')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="work" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                        <select disabled id="work" onchange="this.size=1; this.blur(); checkWork(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control select2-request @error('work') is-invalid @enderror" value="{{ old('work') }}" name="work">


                            <option value="">---</option>
                            @foreach ($askary_works as $askary_work )
                            @if ($purchaseCustomer->customer->askary_id == $askary_work->id || (old('askary_work') == $askary_work->id))
                            <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                            @else
                            <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                            @endif
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="hiring_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                            <label for="hiring_date" class="control-label mb-1">تاريخ التعيين</label>
                        <input  readonly id="hiring_date" style="text-align: right" name="hiring_date" type="text" class="form-control @error('hiring_date') is-invalid @enderror" value="{{ $purchaseCustomer->customer->hiring_date }}" autocomplete="hiring_date" >
                    </div>
                </div>
                @if(($purchaseCustomer->customer->work == 2 ))

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
                            <input readonly id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->customer->job_title }}" autocomplete="job_title">
                        </div>
                    </div>

                @elseif($purchaseCustomer->customer->work != 2 )

                    <div class="col-6" id="madany" style="display: none;">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="madanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                            <select disabled id="madany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

                                <option value="">---</option>
                                @foreach ($madany_works as $madany_work )
                                @if ($purchaseCustomer->customer->madany_id == $madany_work->id )
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
                            <input readonly id="job_title" name="job_title" type="text" class="form-control" value="{{ $purchaseCustomer->customer->job_title }}" autocomplete="job_title">
                        </div>
                    </div>

                @endif
                @if(($purchaseCustomer->customer->work == 1))



                    <div class="col-6" id="askary2">

                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="askaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                            <select disabled id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

                                <option value="">---</option>
                                @foreach ($askary_works as $askary_work )
                                @if ($purchaseCustomer->customer->askary_id == $askary_work->id )
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
                                @if ($purchaseCustomer->customer->military_rank == $rank->id || (old('rank') == $rank->id) )
                                <option value="{{$rank->id}}" selected>{{$rank->value}}</option>
                                @else
                                <option value="{{$rank->id}}">{{$rank->value}}</option>
                                @endif
                                @endforeach


                            </select>


                        </div>

                    </div>

                @elseif($purchaseCustomer->customer->work != 1)


                    <div class="col-6" id="askary" style="display:none;">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="askaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                            <select disabled id="askary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">

                                <option value="">---</option>
                                @foreach ($askary_works as $askary_work )
                                @if ($purchaseCustomer->customer->askary_id == $askary_work->id )
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
                                @if ($purchaseCustomer->customer->military_rank == $rank->id || (old('rank') == $rank->id) )
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
                        <label for="salary" class="control-label mb-1">صافي الراتب</label>
                        <input readonly id="salary" name="salary" type="number" class="form-control" value="{{ $purchaseCustomer->customer->salary }}" autocomplete="salary">
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

                            @if( $purchaseCustomer->customer->is_supported == 'yes')
                            <option value="">---</option>
                            <option value="yes" selected>نعم</option>
                            <option value="no">لا</option>

                            @elseif ($purchaseCustomer->customer->sex == 'no')
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
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="basic_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="basic_salary" class="control-label mb-1">الراتب الأساسي
                        </label>
                        <input readonly id="basic_salary" name="basic_salary" type="number" class="form-control" value="{{ old('basic_salary',$purchaseCustomer->customer->basic_salary) }}" autocomplete="basic_salary">

                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="without_transfer_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="without_transfer_salary" class="control-label mb-1">بدون تحويل الراتب</label>

                        <select disabled  id="without_transfer_salary" name="without_transfer_salary" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('without_transfer_salary') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('without_transfer_salary')=='1' ) selected="selected" @elseif ($purchaseCustomer->customer->without_transfer_salary == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('without_transfer_salary')=='0' ) selected="selected" @elseif ($purchaseCustomer->customer->without_transfer_salary == '0') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="add_support_installment_to_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="add_support_installment_to_salary" class="control-label mb-1">إضافة قسط الدعم إلى الراتب</label>

                        <select disabled id="add_support_installment_to_salary" name="add_support_installment_to_salary" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('add_support_installment_to_salary') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('add_support_installment_to_salary')== 1 ) selected="selected" @elseif ($purchaseCustomer->customer->add_support_installment_to_salary == 1) selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('add_support_installment_to_salary')== 0 ) selected="selected" @elseif ($purchaseCustomer->customer->add_support_installment_to_salary == 0) selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="guarantees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="guarantees" class="control-label mb-1">الضمانات</label>

                        <select disabled id="guarantees" name="guarantees" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('guarantees') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('guarantees')=='1' ) selected="selected" @elseif ($purchaseCustomer->customer->guarantees == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('guarantees')=='0' ) selected="selected" @elseif ($purchaseCustomer->customer->guarantees == '0') selected="selected" @endif>لا</option>


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
                            <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected" @elseif ($purchaseCustomer->customer->has_obligations == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_obligations')=='no' ) selected="selected" @elseif ($purchaseCustomer->customer->has_obligations == 'no') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="obligations_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="obligations_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                        <input readonly id="obligations_value" name="obligations_value" type="number" class="form-control" value="{{ old('obligations_value',$purchaseCustomer->customer->obligations_value) }}" autocomplete="obligations_value">
                    </div>

                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="distress" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>

                        <select disabled id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control @error('has_financial_distress') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected" @elseif ($purchaseCustomer->customer->has_financial_distress == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected" @elseif ($purchaseCustomer->customer->has_financial_distress == 'no') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="financial_distress_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="financial_distress_value" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                        <input readonly id="financial_distress_value" name="financial_distress_value" type="number" class="form-control" value="{{ old('financial_distress_value',$purchaseCustomer->customer->financial_distress_value) }}" autocomplete="financial_distress_value">
                    </div>

                </div>

            </div>
            @endif
        </div>
{{--        @include('Agent.fundingReq.fundingJoint')--}}
    </div>
</div>

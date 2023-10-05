<div class="userFormsInfo  ">
    <div class="headER topRow text-center">
        <i class="fas fa-user"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div  id="tableAdminOption" class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="customerName" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="Customer" >{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{  old('name', $purchaseCustomer->name) }}" autocomplete="name">
                    </div>
                </div>
                @php($numbers_count = \App\CustomersPhone::where('request_id',$id)->count())
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="mobile" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="Customer" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}
                            <small id="checkMobile" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                <i class="fas fa-question i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                            </small>
                        </label>
                        @if ($numbers_count > 0)
                            <span class="badge badge-dark">{{$numbers_count+1}}</span>
                        @else
                            <span class="badge badge-dark" id="ShowFormNumber">1</span>
                        @endif

                        <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile',$purchaseCustomer->mobile) }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                        @error('mobile')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                        <small class="text-danger" id="error" role="alert"> </small>
                    </div>
                </div>

                <div class="col-md-1 mb-0">
                    <label for="Customer" class="w-100">
                        تحكم
                    </label>
                    <div class="btn-group">
                        <a onclick="addForm()" data-toggle="modal"  class="btn text-white btn-primary btn-sm  p-1"><i class="fa fa-plus"></i></a>
                        <a onclick="showForm()" data-toggle="modal" id="showForm" style="display:{{$numbers_count==0 ? 'none' : 'block'}}" class="btn text-white btn-success btn-sm  p-1 mr-1 ml-1"><i class="fa fa-list"></i></a>
                    </div>

                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="sex" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="sex" >{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select id="sex" class="form-control @error('sex') is-invalid @enderror" name="sex">
                            <option value="">---</option>
                            <option value="ذكر" @if (old('sex')=='ذكر' ) selected="selected" @elseif ($purchaseCustomer->sex == 'ذكر') selected="selected" @endif>ذكر</option>
                            <option value="أنثى" @if (old('sex')=='أنثى' ) selected="selected" @elseif ($purchaseCustomer->sex == 'أنثى') selected="selected" @endif>أنثى</option>
                        </select>
                        @error('sex')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="birth_date" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                            <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                            </small>
                        </label>
                        <input id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ old('birth',$purchaseCustomer->birth_date) }}" autocomplete="birth" onblur="calculate()" autofocus>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="birth_hijri" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth_hijri" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small id="convertToGreg" role="button" type="button"  class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}">
                            <i class="fas fa-calendar i-20"></i>
                               {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                            </small>
                        </label>
                        <input type='text' name="birth_hijri" value="{{ old('birth_hijri',$purchaseCustomer->birth_date_higri) }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="age" >{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                        <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age',$purchaseCustomer->age) }}" autocomplete="age" autofocus readonly>
                        @error('age')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="age_years" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="age_years" class="control-label mb-1">العمر بالسنوات</label>
                        <input id="age_years" name="age_years" type="number" class="form-control @error('age_years') is-invalid @enderror" value="{{ old('age_years',$purchaseCustomer->age_years) }}" autocomplete="age_years" autofocus>
                        @error('age_years')
                        <small class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="work" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="work" >{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                        <select id="work" onchange="checkWork(this);"  class="form-control select2-request @error('work') is-invalid @enderror" name="work">


                        <option value="">---</option>

                            @foreach ($worke_sources as $worke_source )
                            @if ($purchaseCustomer->work == $worke_source->id || (old('work') == $worke_source->id) )
                            <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                            @else
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                            @endif
                            @endforeach

                        </select>

                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="regionip" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="regionip" >منطقة العميل</label>
                        <select id="regionip"  class="form-control @error('regionip') is-invalid @enderror" name="regionip">

                            <option value="">---</option>

                            @foreach ($regions as $region )

                                @if ( $region->region_ip != null)
                                    @if ($purchaseCustomer->region_ip == $region->region_ip || (old('regionip') == $region->region_ip ) )
                                        <option value="{{$region->region_ip}}" selected>{{$region->region_ip}}</option>
                                    @else
                                        <option value="{{$region->region_ip}}">{{$region->region_ip}}</option>
                                    @endif
                                @endif

                            @endforeach

                        </select>
                    </div>
                </div>
                @if($purchaseCustomer->work == 2)

                        <div class="col-md-6 mb-3">
                            <div class="form-group" id="madany2">
                                <span id="record" role="button" type="button" class="item span-20"  data-id="madanyWork" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="madany_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                                <select id="madany_work" class="form-control select2-request @error('madany_work') is-invalid @enderror" name="madany_work">

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
                        <div class="col-md-6 mb-3">
                            <div class="form-group" id="madany3">
                                <span id="record" role="button" type="button" class="item span-20"  data-id="jobTitle" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                                <label for="job_title" >{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                                <input id="job_title" name="job_title" type="text" class="form-control" value="{{ old('job_title',$purchaseCustomer->job_title) }}" autocomplete="job_title">
                            </div>
                        </div>

                @elseif($purchaseCustomer->work != 2 )

                        <div id="madany"  class="col-md-6 mb-3">
                            <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="madanyWork" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                                <label for="madany_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                                <select id="madany_work" class="form-control select2-request @error('madany_work') is-invalid @enderror" name="madany_work">

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
                        <div id="madany1" class="col-md-5 mb-3">
                        <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="jobTitle" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                            <label for="job_title" >{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                            <input id="job_title" name="job_title" type="text" class="form-control" value="{{ old('job_title',$purchaseCustomer->job_title) }}" autocomplete="job_title">
                        </div>
                    </div>

                @endif

                @if( ($purchaseCustomer->work ==1))

                        <div class="col-6" id="askary2">
                            <div class="form-group">
                                <span id="record" role="button" type="button" class="item span-20"  data-id="askaryWork" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                                <label for="askary_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                                <select id="askary_work"  class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">
                                    <option value="">---</option>
                                    @foreach ($askary_works as $askary_work )
                                        @if ($purchaseCustomer->askary_id == $askary_work->id || (old('askary_work') == $askary_work->id))
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="rank" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="rank" >{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                                <select id="rank" class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="askaryWork" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="askary_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                                <select id="askary_work"  class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">
                                    <option value="">---</option>
                                    @foreach ($askary_works as $askary_work )
                                        @if ($purchaseCustomer->askary_id == $askary_work->id || (old('askary_work') == $askary_work->id))
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="rank" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="rank" >{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                                <select id="rank"  class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">
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


                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="salary" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="salary" >{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        <input id="salary" name="salary" type="number" class="form-control" value="{{ old('salary',$purchaseCustomer->salary) }}" autocomplete="salary">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="salary_source" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="salary_source" >{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select id="salary_source" class="form-control select2-request @error('salary_source') is-invalid @enderror" name="salary_source">

                            <option selected>---</option>
                            @foreach ($salary_sources as $salary_source )
                                <option value="{{$salary_source->id}}" {{$purchaseCustomer->salary_id == $salary_source->id ? "selected" : ""}}> {{$salary_source->value}}</option>
                            @endforeach



                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="support" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="is_support" >{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>
                        <select id="is_support" name="is_support"  class="form-control @error('is_support') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="yes" @if (old('is_support')=='yes' ) selected="selected" @elseif ($purchaseCustomer->is_supported == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('is_support')=='no' ) selected="selected" @elseif ($purchaseCustomer->is_supported == 'no') selected="selected" @endif>لا</option>


                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="basic_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="basic_salary" class="control-label mb-1">الراتب الأساسي
                        </label>
                        <input id="basic_salary" name="basic_salary" type="number" class="form-control" value="{{ old('basic_salary',$purchaseCustomer->basic_salary) }}" autocomplete="basic_salary">

                    </div>

                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="without_transfer_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="without_transfer_salary" class="control-label mb-1">بدون تحويل الراتب</label>

                        <select id="without_transfer_salary" name="without_transfer_salary" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('without_transfer_salary') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('without_transfer_salary')=='1' ) selected="selected" @elseif ($purchaseCustomer->without_transfer_salary == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('without_transfer_salary')=='0' ) selected="selected" @elseif ($purchaseCustomer->without_transfer_salary == '0') selected="selected" @endif>لا</option>


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
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="guarantees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="guarantees" class="control-label mb-1">الضمانات</label>

                        <select id="guarantees" name="guarantees" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('guarantees') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="1" @if (old('guarantees')=='1' ) selected="selected" @elseif ($purchaseCustomer->guarantees == '1') selected="selected" @endif>نعم</option>
                            <option value="0" @if (old('guarantees')=='0' ) selected="selected" @elseif ($purchaseCustomer->guarantees == '0') selected="selected" @endif>لا</option>


                        </select>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20" data-id="hiring_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                            <label for="hiring_date" class="control-label mb-1">تاريخ التعيين</label>
                        <input id="hiring_date" style="text-align: right" name="hiring_date" type="text" class="form-control @error('hiring_date') is-invalid @enderror" value="{{ $purchaseCustomer->hiring_date }}" autocomplete="hiring_date" >
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="obligations" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="obligations" >{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>
                        <select id="has_obligations" name="has_obligations"  onchange='checkObligation(this);' class="form-control @error('has_obligations') is-invalid @enderror">



                            <option value="">---</option>
                            <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected" @elseif ($purchaseCustomer->has_obligations == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_obligations')=='no' ) selected="selected" @elseif ($purchaseCustomer->has_obligations == 'no') selected="selected" @endif>لا</option>


                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="obligations_value" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="obligations_value" >{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                        <input id="obligations_value" name="obligations_value" type="number" class="form-control" @if (old('has_obligations')=='no' || $purchaseCustomer->has_obligations == 'no' || $purchaseCustomer->has_obligations == null ) readonly @endif value="{{ old('obligations_value',$purchaseCustomer->obligations_value) }}" autocomplete="obligations_value">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="distress" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="distress" >{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>
                        <select id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control @error('has_financial_distress') is-invalid @enderror">
                            <option value="">---</option>
                            <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected" @elseif ($purchaseCustomer->has_financial_distress == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected" @elseif ($purchaseCustomer->has_financial_distress == 'no') selected="selected" @endif>لا</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="financial_distress_value" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="financial_distress_value" >{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                        <input id="financial_distress_value" name="financial_distress_value" type="number" class="form-control" @if (old('has_financial_distress')=='no' || $purchaseCustomer->has_financial_distress == 'no' || $purchaseCustomer->has_financial_distress == null ) readonly @endif value="{{ old('financial_distress_value',$purchaseCustomer->financial_distress_value) }}" autocomplete="financial_distress_value">
                    </div>
                </div>
            </div>
        </div>

        @include('Admin.fundingReq.fundingJoint')
    </div>
</div>


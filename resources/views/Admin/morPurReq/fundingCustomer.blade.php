<div class="userFormsInfo">
    <div class="headER topRow text-center">
        <i class="fas fa-user"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">
            <div  id="tableAdminOption" class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="customerName" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="Customer" >{{ MyHelpers::admin_trans(auth()->user()->id,'customer name') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="name" name="name" type="text" class="form-control" value="{{  old('name', $purchaseCustomer->name) }}" autocomplete="name">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="mobile" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="Customer" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}
                            <small  {{$reqStatus == 26 ? 'disabled' : ''}} id="checkMobile" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                <i class="fas fa-question i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                            </small>
                        </label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile',$purchaseCustomer->mobile) }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="sex" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="sex" >{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select {{$reqStatus == 26 ? 'disabled' : ''}} id="sex" class="form-control @error('sex') is-invalid @enderror" name="sex">
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
                        <span id="record" role="button" type="button" class="item span-20"  data-id="birth_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small {{$reqStatus == 26 ? 'disabled' : ''}} id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                            </small>
                        </label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="birth" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ old('birth',$purchaseCustomer->birth_date) }}" autocomplete="birth" onblur="calculate()" autofocus>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="birth_hijri" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="birth_hijri" >
                            {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                            <small  {{$reqStatus == 26 ? 'disabled' : ''}}  id="convertToGreg" role="button" type="button"  class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}">
                                <i class="fas fa-calendar i-20"></i>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                            </small>
                        </label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} type='text' name="birth_hijri" value="{{ old('birth_hijri',$purchaseCustomer->birth_date_higri) }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="age" >{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}} id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age',$purchaseCustomer->age) }}" autocomplete="age" autofocus readonly>
                        @error('age')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="work" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="work" >{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>
                        <select {{$reqStatus == 26 ? 'disabled' : ''}} id="work" onchange="checkWork(this);"  class="form-control select2-request @error('work') is-invalid @enderror" name="work">
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
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <span id="record" role="button" type="button" class="item span-20"  data-id="regionip" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                        <label for="regionip" >منطقة العميل</label>
                        <select {{$reqStatus == 26 ? 'disabled' : ''}} id="regionip"  class="form-control @error('regionip') is-invalid @enderror" name="regionip">
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="madanyWork" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="madany_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}} id="madany_work" class="form-control select2-request @error('madany_work') is-invalid @enderror" name="madany_work">

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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="jobTitle" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                                <label for="job_title" >{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                                <input {{$reqStatus == 26 ? 'readonly' : ''}} id="job_title" name="job_title" type="text" class="form-control" value="{{ old('job_title',$purchaseCustomer->job_title) }}" autocomplete="job_title">
                            </div>
                        </div>

                @elseif($purchaseCustomer->work != 2 )

                        <div id="madany"  class="col-md-6 mb-3">
                            <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="madanyWork" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                                <label for="madany_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}} id="madany_work" class="form-control select2-request @error('madany_work') is-invalid @enderror" name="madany_work">
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
                            <span id="record" role="button" type="button" class="item span-20"  data-id="jobTitle" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20"></i></span>
                                <label for="job_title" >{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                                <input {{$reqStatus == 26 ? 'readonly' : ''}} id="job_title" name="job_title" type="text" class="form-control" value="{{ old('job_title',$purchaseCustomer->job_title) }}" autocomplete="job_title">
                            </div>
                        </div>

                @endif

                @if( ($purchaseCustomer->work ==1))

                        <div class="col-6" id="askary2">
                            <div class="form-group">
                                <span id="record" role="button" type="button" class="item span-20"  data-id="askaryWork" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                                <label for="askary_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}}  id="askary_work"  class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="rank" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="rank" >{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}}  id="rank" class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="askaryWork" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="askary_work" >{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}}  id="askary_work"  class="form-control select2-request @error('askary_work') is-invalid @enderror" name="askary_work">
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
                                <span id="record" role="button" type="button" class="item span-20"  data-id="rank" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                                <label for="rank" >{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                                <select {{$reqStatus == 26 ? 'disabled' : ''}}  id="rank"  class="form-control select2-request @error('rank') is-invalid @enderror" name="rank">
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
                         <span id="record" role="button" type="button" class="item span-20"  data-id="salary" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="salary" >{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        <input {{$reqStatus == 26 ? 'readonly' : ''}}  id="salary" name="salary" type="number" class="form-control" value="{{ old('salary',$purchaseCustomer->salary) }}" autocomplete="salary">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="salary_source" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="salary_source" >{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select {{$reqStatus == 26 ? 'disabled' : ''}}  id="salary_source" class="form-control select2-request @error('salary_source') is-invalid @enderror" name="salary_source">
                            <option selected>---</option>
                            @foreach ($salary_sources as $salary_source )
                                <option value="{{$salary_source->id}}" {{$purchaseCustomer->salary_id == $salary_source->id ? "selected" : ""}}> {{$salary_source->value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="support" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="is_support" >{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>
                        <select {{$reqStatus == 26 ? 'disabled' : ''}}  id="is_support" name="is_support"  class="form-control @error('is_support') is-invalid @enderror">
                            <option value="">---</option>
                            <option value="yes" @if (old('is_support')=='yes' ) selected="selected" @elseif ($purchaseCustomer->is_supported == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('is_support')=='no' ) selected="selected" @elseif ($purchaseCustomer->is_supported == 'no') selected="selected" @endif>لا</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3" {{$reqStatus == 26 ? 'hidden' : ''}} >
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="obligations" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="obligations" >{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>
                        <select id="has_obligations" name="has_obligations"  onchange='checkObligation(this);' class="form-control @error('has_obligations') is-invalid @enderror">
                            <option value="">---</option>
                            <option value="yes" @if (old('has_obligations')=='yes' ) selected="selected" @elseif ($purchaseCustomer->has_obligations == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_obligations')=='no' ) selected="selected" @elseif ($purchaseCustomer->has_obligations == 'no') selected="selected" @endif>لا</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3" {{$reqStatus == 26 ? 'hidden' : ''}}>
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="obligations_value" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="obligations_value" >{{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }} </label>
                        <input id="obligations_value" name="obligations_value" type="number" class="form-control" @if (old('has_obligations')=='no' || $purchaseCustomer->has_obligations == 'no' || $purchaseCustomer->has_obligations == null ) readonly @endif value="{{ old('obligations_value',$purchaseCustomer->obligations_value) }}" autocomplete="obligations_value">
                    </div>
                </div>
                <div class="col-md-4 mb-3" {{$reqStatus == 26 ? 'hidden' : ''}}>
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="distress" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="distress" >{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>
                        <select id="has_financial_distress" name="has_financial_distress" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;checkDistress(this);' class="form-control @error('has_financial_distress') is-invalid @enderror">
                            <option value="">---</option>
                            <option value="yes" @if (old('has_financial_distress')=='yes' ) selected="selected" @elseif ($purchaseCustomer->has_financial_distress == 'yes') selected="selected" @endif>نعم</option>
                            <option value="no" @if (old('has_financial_distress')=='no' ) selected="selected" @elseif ($purchaseCustomer->has_financial_distress == 'no') selected="selected" @endif>لا</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3" {{$reqStatus == 26 ? 'hidden' : ''}}>
                    <div class="form-group">
                         <span id="record" role="button" type="button" class="item span-20"  data-id="financial_distress_value" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20"></i></span>
                        <label for="financial_distress_value" >{{ MyHelpers::admin_trans(auth()->user()->id,'financial distress value') }} </label>
                        <input id="financial_distress_value" name="financial_distress_value" type="number" class="form-control" @if (old('has_financial_distress')=='no' || $purchaseCustomer->has_financial_distress == 'no' || $purchaseCustomer->has_financial_distress == null ) readonly @endif value="{{ old('financial_distress_value',$purchaseCustomer->financial_distress_value) }}" autocomplete="financial_distress_value">
                    </div>
                </div>
            </div>
        </div>

        @include('Admin.morPurReq.fundingJoint')
    </div>
</div>

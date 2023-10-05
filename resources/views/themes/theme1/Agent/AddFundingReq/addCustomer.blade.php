<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <br>
            <span class="text-danger" style="text-align:center;" id="errorSubmit" role="alert"> </span>

            <form action="{{ route('agent.addCustomer2')}}" method="POST" id="frm-update">
                <div class="modal-body">

                    @csrf

                    <input type="hidden" name="id" class="form-control" id="id">

                    <div class="form-group">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                        <input id="name1" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="">

                        <span class="text-danger" id="nameError" role="alert"> </span>
                    </div>

                    <div class="form-group">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select id="sex1" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('sex') is-invalid @enderror" name="sex">

                            <option value="">---</option>
                            <option value="ذكر">{{ MyHelpers::admin_trans(auth()->user()->id,'male') }}</option>
                            <option value="أنثى">{{ MyHelpers::admin_trans(auth()->user()->id,'female') }}</option>


                        </select>

                        <span class="text-danger" id="sexError" role="alert"> </span>


                    </div>


                    <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</label>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group form-group">

                                <input id="mobile1" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">

                                <span class="input-group-btn">
                                    <button type="button" id="checkMobile" class="btn btn-info btn-block">
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger" id="mobileError" role="alert"> </span>
                            <span class="text-danger" id="error" role="alert"> </span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">

                            <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                            <div class="input-group form-group">
                                <div class="input-group">
                                    <input id="birth1" style="text-align: right" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ old('birth') }}" autocomplete="birth" onblur="calculate()" autofocus>
                                    <span class="input-group-btn">
                                        <button type="button" id="convertToHij1" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</label>
                            <div class="input-group form-group">
                                <div class="input-group">
                                    <input type='text' name="birth_hijri" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date1" />
                                    <span class="input-group-btn">
                                        <button type="button" id="convertToGreg1" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                                <input id="age1" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}" autocomplete="age" autofocus readonly>
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

                        <select id="work1" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check1(this);' class="form-control @error('work') is-invalid @enderror" value="{{ old('work') }}" name="work">

                            <option value="">---</option>
                            <option value="عسكري">عسكري</option>
                            <option value="مدني">مدني</option>
                            <option value="قطاع خاص">قطاع خاص</option>
                            <option value="متقاعد">متقاعد</option>

                        </select>

                        <span class="text-danger" id="workError" role="alert"> </span>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div id="madany3" class="form-group" style="display: none;">
                                <label for="madany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                                <select id="madany_work3" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('madany_work') is-invalid @enderror" value="{{ old('madany_work') }}" name="madany_work">

                                    <option value="">---</option>
                                    @foreach ($madany_works as $madany_work )
                                    <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                                    @endforeach
                                </select>

                                @error('madany_work')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group" id="madany4" style="display: none;">
                                <label for="job_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                                <input id="job_title3" name="job_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" autocomplete="job_title" autofocus placeholder="">
                                @error('job_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div id="askary3" class="form-group" style="display: none;">
                                <label for="askary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                                <select id="askary_work3" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('askary_work') is-invalid @enderror" value="{{ old('askary_work') }}" name="askary_work">

                                    <option value="">---</option>
                                    @foreach ($askary_works as $askary_work )
                                    <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                                    @endforeach
                                </select>

                                @error('askary_work')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div id="askary4" class="form-group" style="display: none;">
                                <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                                <select id="rank3" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('rank') is-invalid @enderror" name="rank">

                                    <option value="">---</option>
                                    <option value="جندي">جندي</option>
                                    <option value="رقيب">رقيب</option>


                                </select>

                                @error('rank')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>

                    </div>


                    <div class="form-group">
                        <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select id="salary_source1" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source">

                            <option value="">---</option>
                            @foreach ($salary_sources as $salary_source )
                            <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                            @endforeach
                        </select>

                        <span class="text-danger" id="salary_sourceError" role="alert"> </span>
                    </div>

                    <div class="form-group">
                        <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        <input id="salary2" name="salary" min="0" type="number" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}" autocomplete="salary" autofocus>

                        <span class="text-danger" id="salaryError" role="alert"> </span>
                    </div>

                    <div class="form-group">
                        <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}?</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="yes" id="yes" name="support" checked>
                                    <label style=" position: relative; padding-left: 1.8rem;" class="custom-control-label" for="yes">نعم</label>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="no" id="no" name="support">
                                    <label style=" position: relative; padding-left: 1.8rem;" class="custom-control-label" for="no">لا</label>
                                </div>
                            </div>
                        </div>

                    </div>





                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
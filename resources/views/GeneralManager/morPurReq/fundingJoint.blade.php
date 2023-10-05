<div class="infoKafil topRow mt-5">
    <div class="kafilHeader">
        <div class="addBtn">
            @if ($reqStatus == 23 )
                <button class="w-100" role="button" type="button">
                    <i class="fas fa-plus-circle"></i>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Joint Info') }}
                </button>
            @else
                <button class="w-100" role="button" type="button" disabled style="cursor: not-allowed">
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Joint Info') }}
                </button>
            @endif
        </div>
    </div>
    @if ($reqStatus == 23 )
    <div id="jointdiv"  class="userFormsDetails  mt-3" style="display: block">
        <div id="tableAdminOption" class="row">
            <div class="col-4">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="jointName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</label>
                    <input id="jointname" name="jointname" type="text" class="form-control @error('jointname') is-invalid @enderror" value="{{ old('jointname',$purchaseJoint->name )}}" autocomplete="jointname" autofocus placeholder="">


                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="jointMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</label>
                    <input id="jointmobile" name="jointmobile" type="tel" class="form-control @error('jointmobile') is-invalid @enderror" value="{{ old('jointmobile',$purchaseJoint->mobile)}}" autocomplete="jointmobile" autofocus placeholder="5xxxxxxxxx">
                    @error('jointmobile')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="jointSalary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointsalary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</label>
                    <input id="jointsalary" name="jointsalary" type="number" class="form-control @error('jointsalary') is-invalid @enderror" value="{{ old('jointsalary',$purchaseJoint->salary)}}" autocomplete="jointsalary" autofocus>
                    @error('jointsalary')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="jointBirth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointbirth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                    <input id="jointbirth" name="jointbirth" type="date" class="form-control @error('jointbirth') is-invalid @enderror" value="{{ $purchaseJoint->birth_date}}" autocomplete="jointbirth" onblur="calculate1()" autofocus>



                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="jointage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                    <input id="jointage" name="jointage" type="text" class="form-control @error('jointage') is-invalid @enderror" value="{{ $purchaseJoint->age }}" autocomplete="jointage" autofocus readonly>
                    @error('age')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <span class="item pointer span-20" id="record" data-id="jointWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointwork" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                    <select id="jointwork" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check(this);' class="form-control @error('jointwork') is-invalid @enderror" name="jointwork">

                            <option value="">---</option>
                            @foreach ($worke_sources as $worke_source )
                            @if ($purchaseJoint->work == $worke_source->id || (old('jointwork') == $worke_source->id) )
                            <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                            @else
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                            @endif
                            @endforeach


                    </select>
                </div>
            </div>


            <div class="col-6">
                <div id="jointmadany" class="form-group">
                    <label for="jointmadany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                    <select id="jointmadany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('jointmadany_work') }}" name="jointmadany_work">

                        <option value="">---</option>
                        @foreach ($madany_works as $madany_work )
                            @if ($purchaseJoint->madany_id == $madany_work->id)
                                <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                            @else
                                <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                            @endif
                        @endforeach
                    </select>

                    @error('jointmadany_work')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group" id="jointmadany1">
                    <span class="item pointer span-20" id="record" data-id="jointJobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointjob_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                    <input id="jointjob_title" name="jointjob_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ $purchaseJoint->job_title }}" autocomplete="jointjob_title" autofocus placeholder="">
                    @error('job_title')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
            <div class="col-6">
                <div id="jointaskary" class="form-group">
                    <label for="jointaskary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                    <select id="jointaskary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" value="{{ old('jointaskary_work') }}" name="jointaskary_work">

                        <option value="">---</option>
                        @foreach ($askary_works as $askary_work )

                            @if ($purchaseJoint->askary_id == $askary_work->id)
                                <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                            @else
                                <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                            @endif

                        @endforeach
                    </select>

                    @error('askary_work')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>

            <div class="col-6">
                <div id="jointaskary1" class="form-group">
                    <span class="item pointer span-20" id="record" data-id="jointRank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                    <label for="jointrank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                    <select id="jointrank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="jointrank">

                        @if ($purchaseJoint->military_rank == 'جندي')
                            <option value="">---</option>
                            <option value="جندي" selected>جندي</option>
                            <option value="رقيب">رقيب</option>
                        @elseif ($purchaseJoint->military_rank == 'رقيب')
                            <option value="">---</option>
                            <option value="جندي">جندي</option>
                            <option value="رقيب" selected>رقيب</option>
                        @else
                            <option value="">---</option>
                            <option value="جندي">جندي</option>
                            <option value="رقيب">رقيب</option>
                        @endif


                    </select>

                    @error('rank')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror

                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="jointsalary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                    <select id="jointsalary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('jointsalary_source') is-invalid @enderror" value="{{ old('jointsalary_source') }}" name="jointsalary_source">

                        <option value="">---</option>
                        @foreach ($salary_sources as $salary_source )
                            @if ($purchaseJoint->salary_id == $salary_source->id )
                                <option value="{{$salary_source->id}}" selected>{{$salary_source->value}}</option>
                            @else
                                <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                            @endif
                        @endforeach
                    </select>

                    @error('jointsalary_source')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="jointfunding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                    <select id="jointfunding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('jointfunding_source') }}" name="jointfunding_source">

                        <option value="">---</option>
                        @foreach ($funding_sources as $funding_source )
                            @if ($purchaseJoint->funding_id == $funding_source->id )
                                <option value="{{$funding_source->id}}" selected>{{$funding_source->value}}</option>
                            @else
                                <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
                            @endif
                        @endforeach
                    </select>

                    @error('funding_source')
                    <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                </div>
            </div>
        </div>
    </div>
    @else
        <div id="jointdiv"  class="userFormsDetails  mt-3" style="display: block">
            <div id="tableAdminOption" class="row">
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="jointName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</label>
                        <input readonly id="jointname" name="jointname" type="text" class="form-control @error('jointname') is-invalid @enderror" value="{{ $purchaseJoint->name}}" autocomplete="jointname" autofocus placeholder="">


                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="jointMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</label>
                        <input readonly id="jointmobile" name="jointmobile" type="tel" class="form-control @error('jointmobile') is-invalid @enderror" value="{{ $purchaseJoint->mobile}}" autocomplete="jointmobile" autofocus placeholder="5xxxxxxxxx">
                        @error('jointmobile')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="jointSalary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointsalary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</label>
                        <input readonly id="jointsalary" name="jointsalary" type="number" class="form-control @error('jointsalary') is-invalid @enderror" value="{{ old('jointsalary',$purchaseJoint->salary)}}" autocomplete="jointsalary" autofocus>
                        @error('jointsalary')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="jointBirth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointbirth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <input readonly id="jointbirth" name="jointbirth" type="date" class="form-control @error('jointbirth') is-invalid @enderror" value="{{ $purchaseJoint->birth_date}}" autocomplete="jointbirth" onblur="calculate1()" autofocus>



                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jointage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                        <input readonly id="jointage" name="jointage" type="text" class="form-control @error('jointage') is-invalid @enderror" value="{{ $purchaseJoint->age }}" autocomplete="jointage" autofocus readonly>
                        @error('age')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="jointWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointwork" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                        <select disabled id="jointwork" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check(this);' class="form-control @error('jointwork') is-invalid @enderror" name="jointwork">

                            <option value="">---</option>
                            @foreach ($worke_sources as $worke_source )
                            @if ($purchaseJoint->work == $worke_source->id || (old('jointwork') == $worke_source->id) )
                            <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                            @else
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                            @endif
                            @endforeach


                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div id="jointmadany" class="form-group">
                        <label for="jointmadany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                        <select disabled id="jointmadany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" value="{{ old('jointmadany_work') }}" name="jointmadany_work">

                            <option value="">---</option>
                            @foreach ($madany_works as $madany_work )
                                @if ($purchaseJoint->madany_id == $madany_work->id)
                                    <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                                @else
                                    <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                                @endif
                            @endforeach
                        </select>

                        @error('jointmadany_work')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group" id="jointmadany1">
                        <span class="item pointer span-20" id="record" data-id="jointJobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointjob_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                        <input readonly id="jointjob_title" name="jointjob_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ $purchaseJoint->job_title }}" autocomplete="jointjob_title" autofocus placeholder="">
                        @error('job_title')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div id="jointaskary" class="form-group">
                        <label for="jointaskary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                        <select disabled id="jointaskary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" value="{{ old('jointaskary_work') }}" name="jointaskary_work">

                            <option value="">---</option>
                            @foreach ($askary_works as $askary_work )

                                @if ($purchaseJoint->askary_id == $askary_work->id)
                                    <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                                @else
                                    <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                                @endif

                            @endforeach
                        </select>

                        @error('askary_work')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>

                <div class="col-6">
                    <div id="jointaskary1" class="form-group">
                        <span class="item pointer span-20" id="record" data-id="jointRank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                        <label for="jointrank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                        <select disabled id="jointrank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="jointrank">

                            @if ($purchaseJoint->military_rank == 'جندي')
                                <option value="">---</option>
                                <option value="جندي" selected>جندي</option>
                                <option value="رقيب">رقيب</option>
                            @elseif ($purchaseJoint->military_rank == 'رقيب')
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب" selected>رقيب</option>
                            @else
                                <option value="">---</option>
                                <option value="جندي">جندي</option>
                                <option value="رقيب">رقيب</option>
                            @endif


                        </select>

                        @error('rank')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror

                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jointsalary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select disabled id="jointsalary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('jointsalary_source') is-invalid @enderror" value="{{ old('jointsalary_source') }}" name="jointsalary_source">

                            <option value="">---</option>
                            @foreach ($salary_sources as $salary_source )
                                @if ($purchaseJoint->salary_id == $salary_source->id )
                                    <option value="{{$salary_source->id}}" selected>{{$salary_source->value}}</option>
                                @else
                                    <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                                @endif
                            @endforeach
                        </select>

                        @error('jointsalary_source')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jointfunding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                        <select disabled id="jointfunding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('jointfunding_source') }}" name="jointfunding_source">

                            <option value="">---</option>
                            @foreach ($funding_sources as $funding_source )
                                @if ($purchaseJoint->funding_id == $funding_source->id )
                                    <option value="{{$funding_source->id}}" selected>{{$funding_source->value}}</option>
                                @else
                                    <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
                                @endif
                            @endforeach
                        </select>

                        @error('funding_source')
                        <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>


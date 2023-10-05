<div class="card-title">
    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Joint Info') }} <i onclick="showJoint()" class="fa fa-plus-circle text-info"></i> </h3>
</div>

<hr>

@if($purchaseJoint->name == null)
<div id="jointdiv" style="display:none;">
@else
<div id="jointdiv" >
@endif
    <div class="row">
    <div class="col-4">
            <div class="form-group">
                <button class="item" id="record" data-id="jointName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</label>
                <input readonly id="jointname" name="jointname" type="text" class="form-control @error('jointname') is-invalid @enderror" value="{{ $purchaseJoint->name}}" autocomplete="jointname" autofocus placeholder="">


            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <button class="item" id="record" data-id="jointMobile" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</label>
                <input readonly id="jointmobile" name="jointmobile" type="tel" class="form-control @error('jointmobile') is-invalid @enderror" value="{{ $purchaseJoint->mobile}}" autocomplete="jointmobile" autofocus placeholder="5xxxxxxxxx">
                @error('jointmobile')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <button class="item" id="record" data-id="jointSalary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointsalary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint salary') }}</label>
                <input readonly id="jointsalary" name="jointsalary" type="number" class="form-control @error('jointsalary') is-invalid @enderror" value="{{ old('jointsalary',$purchaseJoint->salary)}}" autocomplete="jointsalary" autofocus>
                @error('jointsalary')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

    </div>

 <!---->

 <div class="row">
        <div class="col-6">
            <div class="form-group">
                <button class="item" id="record" data-id="jointBirth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointbirth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                <input  readonly id="jointbirth" name="jointbirth" type="date" class="form-control @error('jointbirth') is-invalid @enderror" value="{{ old('jointbirth',$purchaseJoint->birth_date)}}" autocomplete="jointbirth" onblur="calculate1()" autofocus>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <button class="item" id="record" data-id="jointBirth_higri" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})</label>
                <div class="input-group form-group">
                    <div class="input-group">
                        <input readonly type='text' name="jointbirth_hijri" value="{{ old('jointbirth_hijri',$purchaseJoint->birth_date_higri) }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date1" />

                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="jointage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                <input id="jointage" name="jointage" type="text" class="form-control @error('jointage') is-invalid @enderror" value="{{ old('jointage',$purchaseJoint->age) }}" autocomplete="jointage" autofocus readonly>
                @error('age')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>

    <!---->

    <div class="form-group">
        <button class="item" id="record" data-id="jointWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
            <i class="fa fa-history" style="font-size: medium;"></i></button>
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


    @if($purchaseJoint->work == 2)
    <div class="row">
        <div class="col-6">
            <div id="jointmadany2" class="form-group">
            <button class="item" id="record" data-id="jointmadanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointmadany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                <select disabled id="jointmadany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" name="jointmadany_work">

                    <option value="">---</option>
                    @foreach ($madany_works as $madany_work )
                    @if ($purchaseJoint->madany_id == $madany_work->id || (old('jointmadany_work') == $madany_work->id))
                    <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                    @else
                    <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                    @endif
                    @endforeach
                </select>

                @error('jointmadany_work')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-6">
            <div class="form-group" id="jointmadany3">
                <button class="item" id="record" data-id="jointJobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointjob_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                <input readonly id="jointjob_title" name="jointjob_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('jointjob_title',$purchaseJoint->job_title) }}" autocomplete="jointjob_title" autofocus placeholder="">
                @error('job_title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>


    @elseif($purchaseJoint->work != 2 )
    <div class="row">

        <div class="col-6" id="jointmadany" style="display: none;">
            <div class="form-group">
            <button class="item" id="record" data-id="jointmadanyWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointmadany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                <select disabled id="jointmadany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('madany_work') is-invalid @enderror" name="jointmadany_work">

                    <option value="">---</option>
                    @foreach ($madany_works as $madany_work )
                    @if ($purchaseJoint->madany_id == $madany_work->id || (old('jointmadany_work') == $madany_work->id))
                    <option value="{{$madany_work->id}}" selected>{{$madany_work->value}}</option>
                    @else
                    <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
                    @endif
                    @endforeach
                </select>

                @error('jointmadany_work')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-6" id="jointmadany1" style="display: none;">
            <div class="form-group">
                <button class="item" id="record" data-id="jointJobTitle" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointjob_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                <input readonly id="jointjob_title" name="jointjob_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('jointjob_title',$purchaseJoint->job_title) }}" autocomplete="jointjob_title" autofocus placeholder="">
                @error('job_title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>

    @endif


    @if( ($purchaseJoint->work ==1))
    <div class="row">


        <div class="col-6" id="jointaskary2">
            <div class="form-group">
            <button class="item" id="record" data-id="jointaskaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointaskary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                <select disabled id="jointaskary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="jointaskary_work">

                    <option value="">---</option>
                    @foreach ($askary_works as $askary_work )

                    @if ($purchaseJoint->askary_id == $askary_work->id || (old('jointaskary_work') == $askary_work->id))
                    <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                    @else
                    <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                    @endif

                    @endforeach
                </select>

                @error('askary_work')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-6" id="jointaskary3">

            <div class="form-group">
                <button class="item" id="record" data-id="jointRank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointrank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                <select disabled id="jointrank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="jointrank">


                    <option value="" selected>---</option>
                    @foreach ($ranks as $rank)
                    @if ($purchaseJoint->military_rank == $rank->id || (old('rank') == $rank->id) )
                    <option value="{{$rank->id}}" selected>{{$rank->value}}</option>
                    @else
                    <option value="{{$rank->id}}">{{$rank->value}}</option>
                    @endif
                    @endforeach

                </select>

                @error('jointrank')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>

        </div>
    </div>


    @elseif($purchaseJoint->work != 1)
    <div class="row">

        <div class="col-6" id="jointaskary" style="display:none;">
            <div class="form-group">
            <button class="item" id="record" data-id="jointaskaryWork" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointaskary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                <select  disabled id="jointaskary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" name="jointaskary_work">

                    <option value="">---</option>
                    @foreach ($askary_works as $askary_work )

                    @if ($purchaseJoint->askary_id == $askary_work->id || (old('jointaskary_work') == $askary_work->id))
                    <option value="{{$askary_work->id}}" selected>{{$askary_work->value}}</option>
                    @else
                    <option value="{{$askary_work->id}}">{{$askary_work->value}}</option>
                    @endif

                    @endforeach
                </select>

                @error('askary_work')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

        </div>

        <div class="col-6" id="jointaskary1" style="display:none;">
            <div class="form-group">
                <button class="item" id="record" data-id="jointRank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointrank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                <select disabled id="jointrank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('rank') is-invalid @enderror" name="jointrank">


                    <option value="" selected>---</option>
                    @foreach ($ranks as $rank)
                    @if ($purchaseJoint->military_rank == $rank->id || (old('rank') == $rank->id) )
                    <option value="{{$rank->id}}" selected>{{$rank->value}}</option>
                    @else
                    <option value="{{$rank->id}}">{{$rank->value}}</option>
                    @endif
                    @endforeach

                </select>

                @error('jointrank')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>

        </div>
    </div>

    @endif


    <div class="row">
        <div class="col-6">
            <div class="form-group">
            <button class="item" id="record" data-id="jointsalary_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                <label for="jointsalary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                <select disabled id="jointsalary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('jointsalary_source') is-invalid @enderror" value="{{ old('jointsalary_source') }}" name="jointsalary_source">

                    <option value="" selected>---</option>
                    @foreach ($salary_sources as $salary_source )
                    @if ($purchaseJoint->salary_id == $salary_source->id )
                    <option value="{{$salary_source->id}}" selected>{{$salary_source->value}}</option>
                    @else
                    <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                    @endif
                    @endforeach
                </select>

                @error('jointsalary_source')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
            <button class="item" id="record" data-id="jointfunding_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>




</div>




<div class="card-title">
    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Joint Info') }} <i onclick="showJoint()" class="fa fa-plus-circle text-info"></i> </h3>
</div>

<hr>

    <div id="jointdiv" style="display:block;">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="jointname" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint name') }}</label>
                    <input id="jointname" name="jointname" type="text" class="form-control @error('jointname') is-invalid @enderror" value="{{ old('jointname') }}" autocomplete="jointname" autofocus placeholder="">

                    <span class="text-danger" id="jointnameError" role="alert"> </span>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="jointmobile" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'joint mobile') }}</label>
                    <input id="jointmobile" name="jointmobile" type="tel" class="form-control @error('jointmobile') is-invalid @enderror" value="{{ old('jointmobile') }}" autocomplete="jointmobile" autofocus placeholder="05xxxxxxxx">
                    @error('jointmobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="jointbirth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                    <input id="jointbirth" name="jointbirth" type="date" class="form-control @error('jointbirth') is-invalid @enderror" value="{{ old('jointbirth') }}" autocomplete="jointbirth" onblur="calculate1()" autofocus>

                    <span class="text-danger" id="jointbirthError" role="alert"> </span>

                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="jointage" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                    <input id="jointage" name="jointage" type="text" class="form-control @error('jointage') is-invalid @enderror" value="{{ old('jointage') }}" autocomplete="jointage" autofocus readonly>
                    @error('age')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="jointwork" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

            <select id="jointwork" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check(this);' value="{{ old('jointwork') }}" class="form-control @error('jointwork') is-invalid @enderror" name="jointwork">

                <option value="">---</option>
                <option value="عسكري">عسكري</option>
                <option value="مدني">مدني</option>
                <option value="قطاع خاص">قطاع خاص</option>
                <option value="متقاعد">متقاعد</option>

            </select>

            <span class="text-danger" id="jointworkError" role="alert"> </span>

        </div>

        <div class="row">
            <div class="col-6">
                <div id="jointmadany" class="form-group" style="display: none;">
                    <label for="jointmadany_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'madany') }}</label>
                    <select id="jointmadany_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('madany_work') is-invalid @enderror" value="{{ old('jointmadany_work') }}" name="jointmadany_work">

                        <option value="">---</option>
                        @foreach ($madany_works as $madany_work )
                        <option value="{{$madany_work->id}}">{{$madany_work->value}}</option>
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
                <div class="form-group" id="jointmadany1" style="display: none;">
                    <label for="jointjob_title" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'job title') }}</label>
                    <input id="jointjob_title" name="jointjob_title" type="text" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" autocomplete="jointjob_title" autofocus placeholder="">
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
                <div id="jointaskary" class="form-group" style="display: none;">
                    <label for="jointaskary_work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'askary') }}</label>
                    <select id="jointaskary_work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('askary_work') is-invalid @enderror" value="{{ old('jointaskary_work') }}" name="jointaskary_work">

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
                <div id="jointaskary1" class="form-group" style="display: none;">
                    <label for="jointrank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                    <select id="jointrank" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' value="{{ old('rank') }}" class="form-control select2-request @error('rank') is-invalid @enderror" name="jointrank">

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


        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="jointsalary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                    <select id="jointsalary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('jointsalary_source') is-invalid @enderror" value="{{ old('jointsalary_source') }}" name="jointsalary_source">

                        <option value="">---</option>
                        @foreach ($salary_sources as $salary_source )
                        <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
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
                    <label for="jointfunding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                    <select id="jointfunding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('jointfunding_source') }}" name="jointfunding_source">

                        <option value="">---</option>
                        @foreach ($funding_sources as $funding_source )
                        <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
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

        <!--

        <div class="row">
            <div class="col text-center">
                <button type="submit" class="btn btn-lg btn-info ">
                    Save
                </button>
            </div>
        </div> -->



    </div>


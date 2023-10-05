<div class="userFormsInfo">
    <div class="userFormsContainer mb-3">
        <div class="dataFrom topRow ">
            <div class="dataFromHeader">
                <div class="addBtn">
                    <button class="w-100" role="button" type="button">
                        <i class="fas fa-plus-circle"></i>
                        بيانات الطلب
                    </button>
                </div>
            </div>
            <!-- Status req of Sales manager-->
            <div class="userFormsDetails  mt-3">
                <div id="tableAdminOption" class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>


                            <input readonly id="reqtyp" name="reqtyp" type="text" class="form-control @error('reqtyp') is-invalid @enderror" value="{{ $purchaseCustomer->type }}" autocomplete="reqtyp" autofocus placeholder="">


                            @error('reqtyp')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="reqsour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>




                            @foreach ($request_sources as $request_source )
                                @if ((int)$purchaseCustomer->source == $request_source->id || (old('reqsour') == $request_source->id) )
                                <input readonly id="reqsour"  type="text" class="form-control @error('reqsour') is-invalid @enderror" value="{{$request_source->value}}" autocomplete="reqsour" autofocus placeholder="">
                                @endif
                                @endforeach

                            @error('reqsour')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @if (!empty($collaborator))
                    <div class="col-12" id="collaboratorDiv">
                        <div class="form-group">
                            <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>


                            <select disabled id="collaborator" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('collaborator') is-invalid @enderror" name="collaborator">

                                <option value="{{$collaborator->collaborator_id}}" selected>{{$collaborator->name}}</option>

                            </select>

                            @error('collaborator')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="class_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</label>

                            <select disabled id="" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('') is-invalid @enderror" name="">

                                <option value="" selected>---</option>
                                @foreach ($classifcations as $classifcation )
                                <option value="{{$classifcation->id}}" {{$purchaseClass->class_id_agent== $classifcation->id ?  'selected' : '' }}>{{$classifcation->value}}</option>
                                @endforeach


                            </select>

                            @error('reqclass')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="class_quality" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification quality') }}</label>

                            @if ($status == 0 || $status == 1 || $status == 2 || $status == 5)
                            <select id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="" selected>---</option>
                                @foreach ($classifcations->where('user_role',5) as $classifcation )
                                <option value="{{$classifcation->id}}" {{$purchaseClass2->class_id_quality== $classifcation->id ?  'selected' : '' }}>{{$classifcation->value}}</option>
                                @endforeach


                            </select>
                            @else
                            <select disabled id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="" selected>---</option>
                                @foreach ($classifcations->where('user_role',5) as $classifcation )
                                <option value="{{$classifcation->id}}" {{$purchaseClass2->class_id_quality== $classifcation->id ?  'selected' : '' }}>{{$classifcation->value}}</option>
                                @endforeach


                            </select>
                            @endif

                            @error('reqclass')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @if ($status == 0 || $status == 1 || $status == 2 || $status == 5)
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="quacomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'quality comment') }}</label>
                            <input id="quacomm" style="padding: 30px 10px; " name="quacomm" type="text" class="form-control @error('quacomm') is-invalid @enderror" value="{{ $purchaseCustomer->quacomment }}" autocomplete="reqcomm" autofocus placeholder="">

                            @error('quacomm')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @else
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="quacomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'quality comment') }}</label>
                            <input readonly id="quacomm" style="padding: 30px 10px; " name="quacomm" type="text" class="form-control @error('quacomm') is-invalid @enderror" value="{{ $purchaseCustomer->quacomment }}" autocomplete="reqcomm" autofocus placeholder="">

                            @error('quacomm')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @endif
{{--                    <div class="col-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <span class="item pointer span-20" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
{{--                            <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                            <input readonly id="webcomm" style="padding: 30px 10px; " name="webcomm" type="text" class="form-control @error('webcomm') is-invalid @enderror" value="{{ $purchaseCustomer->noteWebsite }}" autocomplete="webcomm" autofocus placeholder="">--}}

{{--                            @error('reqcomm')--}}
{{--                            <small class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </small>--}}
{{--                            @enderror--}}

{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

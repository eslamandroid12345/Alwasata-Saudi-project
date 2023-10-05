<div class="userFormsInfo  ">
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
            <div class="userFormsDetails">
                @if ($reqStatus == 19 )

                <div id="tableAdminOption" class=" row">
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="reqtyp" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                            <input readonly id="reqtyp" name="reqtyp" type="text" class="form-control @error('reqtyp') is-invalid @enderror" value="{{ $purchaseCustomer-> type }}" autocomplete="reqtyp" autofocus placeholder="">


                            @error('reqtyp')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label for="reqsour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>

                                @foreach ($request_sources as $request_source )
                                @if ($purchaseCustomer->source == $request_source->id || (old('reqsour') == $request_source->id) )
                                <input readonly id="reqsour"  type="text" class="form-control @error('reqsour') is-invalid @enderror" value="{{$request_source->value}}" autocomplete="reqsour" autofocus placeholder="" >
                                @endif
                                @endforeach


                            @error('reqsour')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="class_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                            <select id="reqclass"  onchange="check_rejection(this)" @if ($purchaseClass->class_id_agent != null) @if ($purchaseClass->class_id_agent == 57 || $purchaseClass->class_id_agent == 58) disabled @endif @endif onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="">---</option>
                                @foreach ($classifcations as $classifcation )

                                @if ($purchaseClass->class_id_agent == $classifcation->id)
                                <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                @elseif ($classifcation->id != 57 && $classifcation->id != 58 && $classifcation->id != 65)
                                <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                @endif

                                @endforeach
                            </select>

                            @error('reqclass')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-lg-12" id="reasonOfCancelation" style="display: {{$purchaseClass->class_id_agent ==16 ? 'block' :'none'}}">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="rejection_id_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                {{--<i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
                            <label for="rejection_id_agent" class="control-label mb-1">سبب الرفض</label>

                            <select id="rejection_id_agent" class="form-control select2-request @error('rejection_id_agent') is-invalid @enderror" name="rejection_id_agent" {{$purchaseClass->class_id_agent ==16 ? 'required' :''}}>

                            <option value="" selected >---</option>
                                @foreach ($rejections as $rejection )
                                    <option value="{{$rejection->id}}" {{$rejection->id == $purchaseClass->rejection_id_agent ? 'selected' : ''}}>{{$rejection->title}}</option>
                                @endforeach
                            </select>

                            @error('rejection_id')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>

                    <div id="collaboratorDiv" class="col-12">
                        @if (!empty($collaborator))
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
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
                        @endif
                    </div>


                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">{{ old('reqcomm') }}</textarea>
                            @error('reqcomm')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
{{--                    <div class="col-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <span class="item pointer span-20" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
{{--                            <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                            <textarea id="webcomm" name="webcomm" rows="3" cols="50" class="form-control @error('webcomm') is-invalid @enderror" autocomplete="webcomm" autofocus placeholder="">--}}
{{--                            {{ old('webcomm',$purchaseCustomer->noteWebsite) }}--}}
{{--                            </textarea>--}}
{{--                            @error('webcomm')--}}
{{--                            <small class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </small>--}}
{{--                            @enderror--}}

{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                @else
                <div id="tableAdminOption" class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="reqtyp" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>


                            <input readonly id="reqtyp" name="reqtyp" type="text" class="form-control @error('reqtyp') is-invalid @enderror" value="{{ $purchaseCustomer-> type }}" autocomplete="reqtyp" autofocus placeholder="">


                            @error('reqtyp')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="reqsour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                            @foreach ($request_sources as $request_source )
                                @if ($purchaseCustomer->source == $request_source->id || (old('reqsour') == $request_source->id) )
                                <input readonly id="reqsour"  type="text" class="form-control @error('reqsour') is-invalid @enderror" value="{{$request_source->value}}" autocomplete="reqsour" autofocus placeholder="" >
                                @endif
                                @endforeach

                            @error('reqsour')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="class_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                            <select disabled id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="">---</option>
                                @foreach ($classifcations as $classifcation )

                                @if ($purchaseClass->class_id_agent == $classifcation->id)
                                <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                @else
                                <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                @endif

                                @endforeach
                            </select>

                            @error('reqclass')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-lg-12" id="reasonOfCancelation" style="display: {{$purchaseClass->class_id_agent ==16 ? 'block' :'none'}}">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="rejection_id_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                {{--<i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
                            <label for="rejection_id_agent" class="control-label mb-1">سبب الرفض</label>

                            <select  disabled id="rejection_id_agent" class="form-control select2-request @error('rejection_id_agent') is-invalid @enderror" name="rejection_id_agent" {{$purchaseClass->class_id_agent ==16 ? 'required' :''}}>

                            <option value="" selected >---</option>
                                @foreach ($rejections as $rejection )
                                    <option value="{{$rejection->id}}" {{$rejection->id == $purchaseClass->rejection_id_agent ? 'selected' : ''}}>{{$rejection->title}}</option>
                                @endforeach
                            </select>

                            @error('rejection_id')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    @if (!empty($collaborator))
                    <div id="collaboratorDiv" class="col-12">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
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
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea readonly id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">
                            {{ old('reqcomm', $purchaseCustomer->comment) }}
                            </textarea>
                            @error('reqcomm')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
{{--                    <div class="col-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <span class="item pointer span-20" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
{{--                            <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                            <textarea readonly id="webcomm" name="webcomm" rows="3" cols="50" class="form-control @error('webcomm') is-invalid @enderror" autocomplete="webcomm" autofocus placeholder="">--}}
{{--                            {{ old('webcomm',$purchaseCustomer->noteWebsite) }}--}}
{{--                            </textarea>--}}
{{--                            @error('webcomm')--}}
{{--                            <small class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </small>--}}
{{--                            @enderror--}}

{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

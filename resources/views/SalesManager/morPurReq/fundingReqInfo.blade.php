
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
                @if ($reqStatus == 18 || $reqStatus == 22 )
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

                            <input readonly id="reqsour"  type="text" class="form-control  @error('reqsour') is-invalid @enderror" value="{{ $purchaseCustomer->source }}" autocomplete="reqsour" autofocus placeholder="" >



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

                            <select id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="">---</option>
                                @foreach ($classifcations as $classifcation )

                                @if ($purchaseClass->class_id_sm == $classifcation->id)
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
                    @if (!empty($collaborator))
                    <div id="collaboratorDiv">
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
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">
                            {{ old('reqcomm', $purchaseCustomer->sm_comment) }}
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
                            <input readonly id="reqsour"  type="text" class="form-control  @error('reqsour') is-invalid @enderror" value="{{ $purchaseCustomer->source }}" autocomplete="reqsour" autofocus placeholder="" >


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

                                @if ($purchaseClass->class_id_sm == $classifcation->id)
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
                    @if (!empty($collaborator))
                    <div id="collaboratorDiv">
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
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea readonly id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">
                            {{ old('reqcomm', $purchaseCustomer->sm_comment) }}
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

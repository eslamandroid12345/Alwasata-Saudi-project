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
                @if ($reqStatus == 23 )
                    <div  id="tableAdminOption" class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                                <select disabled id="reqtyp" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('reqtyp') is-invalid @enderror" name="reqtyp">

                                    @if ($purchaseCustomer-> type == 'شراء')
                                        <option value="">---</option>
                                        <option value="شراء" selected>شراء</option>
                                        <option value="رهن">رهن</option>

                                    @elseif ($purchaseCustomer-> type == 'رهن')
                                        <option value="">---</option>
                                        <option value="شراء">شراء</option>
                                        <option value="رهن" selected>رهن</option>

                                    @elseif ($purchaseCustomer-> type == 'رهن-شراء')
                                        <option value="رهن-شراء" selected>رهن-شراء</option>

                                    @else
                                        <option value="">---</option>
                                        <option value="شراء">شراء</option>
                                        <option value="رهن">رهن</option>
                                    @endif



                                </select>

                                @error('reqtyp')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="reqsour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>

                                <input readonly id="reqsour"  type="text" class="form-control select2-request @error('reqsour') is-invalid @enderror" value="{{ $purchaseCustomer->source }}" autocomplete="reqsour" autofocus placeholder="" >

                                @error('reqsour')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                                <select id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                    <option value="">---</option>
                                    @foreach ($classifcations as $classifcation )

                                        @if ($purchaseClass->class_id_gm == $classifcation->id)
                                            <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                        @else
                                            <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                        @endif

                                    @endforeach
                                </select>

                                @error('reqclass')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
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
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                    @enderror

                                </div>
                            </div>
                        @endif
                        <div class="col-6">
                            <div class="form-group">
                                <button class="item" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                                <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                                <input id="reqcomm" style="padding: 30px 10px; " name="reqcomm" type="text" class="form-control @error('reqcomm') is-invalid @enderror" value="{{ $purchaseCustomer->comment }}" autocomplete="reqcomm" autofocus placeholder="">

                                @error('reqcomm')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                            </div>
                        </div>
{{--                        <div class="col-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <button class="item" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                    <i class="fa fa-history" style="font-size: medium;"></i></button>--}}
{{--                                <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                                <input id="webcomm" style="padding: 30px 10px; " name="webcomm" type="text" class="form-control @error('webcomm') is-invalid @enderror" value="{{ $purchaseCustomer->noteWebsite }}" autocomplete="webcomm" autofocus placeholder="">--}}

{{--                                @error('reqcomm')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </span>--}}
{{--                                @enderror--}}

{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                @else
                    <div  id="tableAdminOption" class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                                <select disabled id="reqtyp" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('reqtyp') is-invalid @enderror" name="reqtyp">

                                    @if ($purchaseCustomer-> type == 'شراء')
                                        <option value="">---</option>
                                        <option value="شراء" selected>شراء</option>
                                        <option value="رهن">رهن</option>

                                    @elseif ($purchaseCustomer-> type == 'رهن')
                                        <option value="">---</option>
                                        <option value="شراء">شراء</option>
                                        <option value="رهن" selected>رهن</option>
                                    @else
                                        <option value="">---</option>
                                        <option value="شراء">شراء</option>
                                        <option value="رهن">رهن</option>
                                    @endif



                                </select>

                                @error('reqtyp')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="reqsour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>

                                <input readonly id="reqsour"  type="text" class="form-control select2-request @error('reqsour') is-invalid @enderror" value="{{ $purchaseCustomer->source }}" autocomplete="reqsour" autofocus placeholder="" >
                                @error('reqsour')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                                <select disabled id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                    <option value="">---</option>
                                    @foreach ($classifcations as $classifcation )

                                        @if ($purchaseClass->class_id_gm == $classifcation->id)
                                            <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                        @else
                                            <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                        @endif

                                    @endforeach
                                </select>

                                @error('reqclass')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
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
                                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                                    @enderror

                                </div>
                            </div>
                        @endif
                        <div class="col-6">
                            <div class="form-group">
                                <button class="item" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history" style="font-size: medium;"></i></button>
                                <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                                <input readonly id="reqcomm" style="padding: 30px 10px; " name="reqcomm" type="text" class="form-control @error('reqcomm') is-invalid @enderror" value="{{ $purchaseCustomer->comment }}" autocomplete="reqcomm" autofocus placeholder="">

                                @error('reqcomm')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror

                            </div>
                        </div>
{{--                        <div class="col-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <button class="item" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                    <i class="fa fa-history" style="font-size: medium;"></i></button>--}}
{{--                                <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                                <input readonly id="webcomm" style="padding: 30px 10px; " name="webcomm" type="text" class="form-control @error('webcomm') is-invalid @enderror" value="{{ $purchaseCustomer->noteWebsite }}" autocomplete="webcomm" autofocus placeholder="">--}}

{{--                                @error('reqcomm')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </span>--}}
{{--                                @enderror--}}

{{--                            </div>--}}
{{--                        </div>--}}
                    </div>

                @endif

            </div>
        </div>
    </div>
</div>

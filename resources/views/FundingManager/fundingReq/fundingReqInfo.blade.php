
<div class="userFormsInfo" >
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
                @if ( ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13 ) )
                    <div id="tableAdminOption" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="reqNoBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="reqNoBank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'bank order num') }}</label>
                                <input id="reqNoBank" name="reqNoBank" type="text" class="form-control @error('reqNoBank') is-invalid @enderror" value="{{ old('reqNoBank', $purchaseCustomer->reqNoBank) }}" autocomplete="reqNoBank" autofocus placeholder="">

                                @error('reqNoBank')
                                <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="empBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="empBank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'bank employee') }}</label>
                                <input id="empBank"  name="empBank" type="text" class="form-control @error('empBank') is-invalid @enderror" value="{{ old('empBank',$purchaseCustomer->empBank) }}" autocomplete="empBank" autofocus placeholder="">

                                @error('empBank')
                                <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                            </div>
                        </div>
                        @if($purchaseCustomer->recived_date_report != null)
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="recivedDate" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date') }}</label>
                                        <input readonly id="recivedDate" name="recivedDate" type="text" class="form-control @error('recivedDate') is-invalid @enderror" value="{{ old('recivedDate', $purchaseCustomer->recived_date_report) }}" autocomplete="recivedDate" autofocus placeholder="">

                                        @error('recivedDate')
                                        <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">

                                        <label for="recDateCounter" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date counter') }}</label>
                                        <input id="recDateCounter" readonly  name="recDateCounter" type="text" class="form-control @error('recDateCounter') is-invalid @enderror" value="{{ old('recDateCounter',$purchaseCustomer->counter_report) }} يوم" autocomplete="recDateCounter" autofocus placeholder="">

                                        @error('recDateCounter')
                                        <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-4">
                            <div class="form-group">
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
                        <div class="col-4">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="class_id_fm" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                                <select id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                    <option value="">---</option>
                                    @foreach ($classifcations as $classifcation )

                                        @if ($purchaseClass->class_id_fm == $classifcation->id || (old('reqclass') == $classifcation->id))
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
                            {{ old('reqcomm', $purchaseCustomer->fm_comment) }}
                                </textarea>
                                @error('reqcomm')
                                <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                            </div>
                        </div>
                        {{-- show box to write note for bank user if this req send to bank --}}
                        @if (!app()->environment('production') && \App\Models\Request::find($id)->checkBankAccountExists())
                        <div class="col-6" >
                            <div class="form-group ">
                                <span class="item pointer span-20" id="record" data-id="bank_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="bank_notes" class="control-label mb-1">ملاحظة موظف البنك</label>
                                <textarea  rows="3" cols="50" name="bank_notes" class="form-control @error('bank_notes') is-invalid @enderror" autocomplete="bank_notes" autofocus
                                placeholder="ملاحظة موظف البنك">{{ old('bank_notes',$purchaseCustomer->bank_notes ??'-') }}</textarea>
                                @error('bank_notes')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                                @enderror

                            </div>
                        </div>
                        @endif
{{--                        <div class="col-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <span class="item pointer span-20" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
{{--                                <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                                <textarea id="webcomm" name="webcomm" rows="3" cols="50" class="form-control @error('webcomm') is-invalid @enderror" autocomplete="webcomm" autofocus placeholder="">--}}
{{--                            {{ old('webcomm',$purchaseCustomer->noteWebsite) }}--}}
{{--                            </textarea>--}}
{{--                                @error('webcomm')--}}
{{--                                <small class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </small>--}}
{{--                                @enderror--}}

{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                @else
                    <div id="tableAdminOption" class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="reqNoBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="reqNoBank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'bank order num') }}</label>
                                <input readonly id="reqNoBank" name="reqNoBank" type="text" class="form-control @error('reqNoBank') is-invalid @enderror" value="{{ old('reqNoBank', $purchaseCustomer->reqNoBank) }}" autocomplete="reqNoBank" autofocus placeholder="">

                                @error('reqNoBank')
                                <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="empBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="empBank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'bank employee') }}</label>
                                <input readonly  id="empBank" name="empBank" type="text" class="form-control @error('empBank') is-invalid @enderror" value="{{ old('empBank',$purchaseCustomer->empBank) }}" autocomplete="empBank" autofocus placeholder="">

                                @error('empBank')
                                <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                            </div>
                        </div>
                        @if($purchaseCustomer->recived_date_report != null)
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="recivedDate" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date') }}</label>
                                        <input readonly id="recivedDate" name="recivedDate" type="text" class="form-control @error('recivedDate') is-invalid @enderror" value="{{ old('recivedDate', $purchaseCustomer->recived_date_report) }}" autocomplete="recivedDate" autofocus placeholder="">

                                        @error('reqNoBank')
                                        <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">

                                        <label for="recDateCounter" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'recived date counter') }}</label>
                                        <input id="recDateCounter" readonly  name="recDateCounter" type="text" class="form-control @error('recDateCounter') is-invalid @enderror" value="{{ old('recDateCounter',$purchaseCustomer->counter_report) }} يوم" autocomplete="recDateCounter" autofocus placeholder="">

                                        @error('recDateCounter')
                                        <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-4">
                            <div class="form-group">
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
                        <div class="col-4">
                            <div class="form-group">
                                <span class="item pointer span-20" id="record" data-id="class_id_fm" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                                <select disabled id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                    <option value="">---</option>
                                    @foreach ($classifcations as $classifcation )

                                        @if ($purchaseClass->class_id_fm== $classifcation->id)
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
                            {{ old('reqcomm', $purchaseCustomer->fm_comment) }}
                            </textarea>
                                @error('reqcomm')
                                <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                                @enderror

                            </div>
                        </div>
                        {{-- show box to write note for bank user if this req send to bank --}}
                        @if (!app()->environment('production') && \App\Models\Request::find($id)->checkBankAccountExists())
                        <div class="col-6" >
                            <div class="form-group ">
                                <span class="item pointer span-20" id="record" data-id="bank_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="bank_notes" class="control-label mb-1">ملاحظة موظف البنك</label>
                                <textarea readonly rows="3" cols="50" name="bank_notes" class="form-control @error('bank_notes') is-invalid @enderror" autocomplete="bank_notes" autofocus
                                placeholder="ملاحظة موظف البنك">{{ old('bank_notes',$purchaseCustomer->bank_notes ??'-') }}</textarea>
                                @error('bank_notes')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                                @enderror

                            </div>
                        </div>
                        @endif
{{--                        <div class="col-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <span class="item pointer span-20" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
{{--                                <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                                <textarea  readonly id="webcomm" name="webcomm" rows="3" cols="50" class="form-control @error('webcomm') is-invalid @enderror" autocomplete="webcomm" autofocus placeholder="">--}}
{{--                            {{ old('webcomm',$purchaseCustomer->noteWebsite) }}--}}
{{--                            </textarea>--}}
{{--                                @error('webcomm')--}}
{{--                                <small class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </small>--}}
{{--                                @enderror--}}

{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

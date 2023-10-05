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
            <div class="userFormsDetails  mt-3">
                @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
                <div id="tableAdminOption" class=" row">
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="reqtyp" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                            <select id="reqtyp" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control reqtyp_missedFiledInput @error('reqtyp') is-invalid @enderror" name="reqtyp" >

                                <option value="" selected>---</option>
                                <option value="شراء" {{ $purchaseCustomer-> type == 'شراء' || (old('reqtyp') =='شراء' ) ? 'selected' : '' }}>شراء</option>
                                <option value="رهن" {{ $purchaseCustomer-> type == 'رهن' || (old('reqtyp') =='رهن' ) ? 'selected' : '' }}>رهن</option>
                                <option value="شراء-دفعة" disabled {{ $purchaseCustomer-> type == 'شراء-دفعة' || (old('reqtyp') =='شراء-دفعة' ) ? 'selected' : '' }}>شراء-دفعة</option>
                                <option value="رهن-شراء" disabled {{ $purchaseCustomer-> type == 'رهن-شراء' || (old('reqtyp') =='رهن-شراء' ) ? 'selected' : '' }}>رهن-شراء</option>

                                @if ($agentuser->isTsaheel == 1)
                                <option {{ $purchaseCustomer-> type == 'تساهيل' || (old('reqtyp') =='تساهيل' ) ? 'selected' : '' }} value="تساهيل">تساهيل</option>
                                @endif





                            </select>


                            <small style="color:#e60000" class="d-none reqtyp_missedFileds missedFileds">الحقل مطلوب</small>


                            <p class="text-danger" id="reqtypError" role="alert"> </p>


                            @error('reqtyp')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @if($purchaseCustomer->source != null)
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="reqSource" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
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
                    @else
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="reqSource" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqsour" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>

                            <select id="reqsour" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); checkCollaborator(this);' class="form-control select2-request @error('reqsour') is-invalid @enderror" >

                            <option value="" selected>---</option>

                                @foreach ($request_sources as $request_source )
                                @if ($purchaseCustomer->source == $request_source->id || (old('reqsour') == $request_source->id) )
                                <option value="{{$request_source->id}}" selected>{{$request_source->value}}</option>
                                @else
                                <option value="{{$request_source->id}}">{{$request_source->value}}</option>
                                @endif
                                @endforeach
                            </select>

                            <p class="text-danger" id="reqsourceError" role="alert"> </p>

                            @error('reqsour')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @endif
                    <div class="col-4">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="class_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqclass" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}</label>

                            <select id="reqclass" onchange="check_rejection(this)" class="form-control reqclass_missedFiledInput select2-request @error('reqclass') is-invalid @enderror" name="reqclass"  @if ($purchaseClass->class_id_agent != null) @if ($purchaseClass->class_id_agent == 57 || $purchaseClass->class_id_agent == 58)  disabled @endif @endif  >

                                <option value="">---</option>
                                @foreach ($classifcations as $classifcation )

                                @if ( ((!$hide_negative_comment) && $purchaseClass->class_id_agent == $classifcation->id) || (old('reqclass') == $classifcation->id))
                                <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>

                                @elseif ($classifcation->id != 57 && $classifcation->id != 58 && $classifcation->id != 65)
                                <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>

                                @endif

                                @endforeach
                            </select>

                            <small style="color:#e60000" class="d-none reqclass_missedFileds missedFileds">الحقل مطلوب</small>


                            @error('reqclass')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-12" id="reasonOfCancelation" style="display: {{$purchaseClass->class_id_agent ==16 ? 'block' :'none'}}">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="rejection_id_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                             <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="rejection_id_agent" class="control-label mb-1">سبب الرفض</label>

                            <select id="rejection_id_agent" class="form-control @error('rejection_id_agent') is-invalid @enderror" name="rejection_id_agent" {{$purchaseClass->class_id_agent ==16 ? 'required' :''}}>

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
                    @if (!empty($collaborator) || $purchaseCustomer->source == 2)
                    <div id="collaboratorDiv"  class="col-12">
                        <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>

                            @if (!empty($collaborator))
                            <input readonly id="collaborator" name="collaborator" type="text" class="form-control @error('collaborator') is-invalid @enderror" value="{{ $collaborator->name }}" autocomplete="collaborator" autofocus placeholder="" name="collaborator">

                            @else

                            <select id="collaborator" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('collaborator') is-invalid @enderror" name="collaborator">
                                <option value="">---</option>
                                @foreach ($collaborators as $collaborator )
                                <option value="{{$collaborator->collaborato_id}}">{{$collaborator->name}}</option>
                                @endforeach
                            </select>

                            @endif

                            @error('collaborator')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>

                    </div>
                    @endif

                    @if ($purchaseCustomer->source == null && (empty($collaborator)) )
                    <div id="collaboratorDiv2" class="col-12" {{(old('reqsour') !=2) ? 'hidden' : ''}}>

                            <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>


                                <select id="collaborator" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control select2-request @error('collaborator') is-invalid @enderror" name="collaborator">

                                    <option value="">---</option>

                                    @if (!empty($collaborators[0]))

                                    @foreach ($collaborators as $collaborator )
                                    <option value="{{$collaborator->collaborato_id}}">{{$collaborator->name}}</option>
                                    @endforeach

                                    @else
                                    <option disabled="disabled" value="">{{ MyHelpers::admin_trans(auth()->user()->id,'No Collaborator') }}</option>
                                    @endif

                                </select>

                                <p class="text-danger" id="collaboratorError" role="alert"> </p>

                                @error('collaborator')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                                @enderror

                            </div>

                    </div>
                    @endif
                    <div class="{{$purchaseCustomer->source == 2 ? "col-6" :"col-12"}}">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control reqcomm_missedFiledInput @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">{{ old('reqcomm')}}</textarea>


                            <small style="color:#e60000" class="d-none reqcomm_missedFileds missedFileds">الحقل مطلوب</small>


                            @error('reqcomm')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                        <div class="{{$purchaseCustomer->source == 2 ? "col-6" : ""}}" >
                            <div class="form-group  {{$purchaseCustomer->source == 2 ? "d-block" :"d-none"}}">
                                <span class="item pointer span-20" id="record" data-id="collaborator_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="collaborator_notes" class="control-label mb-1">ملاحظة المتعاون</label>
                                <textarea  rows="3" disabled cols="50" class="form-control @error('collaborator_notes') is-invalid @enderror" autocomplete="collaborator_notes" autofocus placeholder="">
                                {{ old('collaborator_notes',$purchaseCustomer->collaborator_notes ??'-') }}
                                </textarea>
                                @error('collaborator_notes')
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
                    <div class="col-12" id="reasonOfCancelation" style="display: {{$purchaseClass->class_id_agent ==16 ? 'block' :'none'}}">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="rejection_id_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                {{--<i class="fa fa-history i-20" style="font-size: medium;"></i></span>--}}
                            <label for="rejection_id_agent" class="control-label mb-1">سبب الرفض</label>

                            <select disabled id="rejection_id_agent" class="form-control select2-request @error('rejection_id_agent') is-invalid @enderror" name="rejection_id_agent" {{$purchaseClass->class_id_agent ==16 ? 'required' :''}}>
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
                    <div class="{{$purchaseCustomer->source == 2 ? "col-6" :"col-12"}}">
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

                    <div class="{{$purchaseCustomer->source == 2 ? "col-6" : ""}}">
                        <div class="form-group {{$purchaseCustomer->source == 2 ? "d-block" :"d-none"}}">
                            <span class="item pointer span-20" id="record" data-id="collaborator_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="collaborator_notes" class="control-label mb-1">ملاحظة المتعاون</label>
                            <textarea  rows="3" disabled cols="50" class="form-control @error('collaborator_notes') is-invalid @enderror" autocomplete="collaborator_notes" autofocus placeholder="">
                            {{ old('collaborator_notes',$purchaseCustomer->collaborator_notes ??'-') }}
                            </textarea>
                            @error('collaborator_notes')
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

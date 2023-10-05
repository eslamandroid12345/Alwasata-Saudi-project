
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
                <div  id="tableAdminOption" class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="reqtyp" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                            <label for="reqtyp" >{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>
                            <select {{$reqStatus == 26 ? 'disabled' : ''}} id="reqtyp" class="form-control @error('reqtyp') is-invalid @enderror" name="reqtyp">
                                <option value="شراء" {{ $purchaseCustomer-> type == 'شراء' || (old('reqtyp') =='شراء' ) ? 'selected' : '' }}>شراء</option>
                                <option value="رهن" {{ $purchaseCustomer-> type == 'رهن' || (old('reqtyp') =='رهن' ) ? 'selected' : '' }}>رهن</option>
                                <option value="تساهيل" {{ $purchaseCustomer-> type == 'تساهيل' || (old('reqtyp') =='تساهيل' ) ? 'selected' : '' }}>تساهيل</option>
                                <option value="شراء-دفعة" {{ $purchaseCustomer-> type == 'شراء-دفعة' || (old('reqtyp') =='شراء-دفعة' ) ? 'selected' : '' }}>شراء-دفعة</option>
                                <option value="رهن-شراء" {{ $purchaseCustomer-> type == 'رهن-شراء' || (old('reqtyp') =='رهن-شراء' ) ? 'selected' : '' }}>رهن-شراء</option>
                            </select>
                            <strong class="text-danger" id="reqtypError" role="alert"> </strong>
                            @error('reqtyp')
                            <strong class="invalid-feedback" role="alert">
                                <small>{{ $message }}</small>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                        <span class="item pointer span-20" id="record" data-id="reqSource" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqsour" >{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                            <select {{$reqStatus == 26 ? 'disabled' : ''}} id="reqsour" onchange='checkCollaborator(this); check_rejection(this);' class="form-control select2-request @error('reqsour') is-invalid @enderror" name="reqsour">
                            <option value="" selected>---</option>

                                @foreach ($request_sources as $request_source )
                                @if ($purchaseCustomer->source == $request_source->id || (old('reqsour') == $request_source->id) )
                                <option value="{{$request_source->id}}" selected>{{$request_source->value}}</option>
                                @else
                                <option value="{{$request_source->id}}">{{$request_source->value}}</option>
                                @endif
                                @endforeach
                                </select>
                            <strong class="text-danger" id="reqsourceError" role="alert"> </strong>
                            @error('reqsour')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror
                        </div>
                    </div>
                    @if ( $purchaseCustomer->source == 2)
                        <div class="col-md-4 mb-3" id="collaboratorDiv">
                            <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="collaborator" >{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}} id="collaborator" class="form-control select2-request @error('collaborator') is-invalid @enderror" name="collaborator">
                                    <option value="">---</option>
                                    @if (!empty($collaborator))
                                        @foreach ($collaborators as $coll )
                                            @if ($collaborator->id == $coll->collaborato_id)
                                                <option selected value="{{$coll->collaborato_id}}">{{$coll->name}}</option>
                                            @else
                                                <option value="{{$coll->collaborato_id}}">{{$coll->name}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach ($collaborators as $coll )
                                            <option value="{{$coll->collaborato_id}}">{{$coll->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('collaborator')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                                @enderror
                            </div>
                        </div>
                    @endif
                    @if ( $purchaseCustomer->source != 2)
                        <div class="col-md-4 mb-3" id="collaboratorDiv2">
                            <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                <label for="collaborator" >{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>
                                <select {{$reqStatus == 26 ? 'disabled' : ''}} id="collaborator2"  class="form-control select2-request @error('collaborator') is-invalid @enderror" name="collaborator">
                                    <option value="">---</option>
                                    @foreach ($collaborators as $collaborator )
                                        <option value="{{$collaborator->collaborato_id}}">{{$collaborator->name}}</option>
                                    @endforeach
                                </select>
                                <strong class="text-danger" id="collaboratorError" role="alert"> </strong>
                                @error('collaborator')
                                <small class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </small>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="class_agent" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                            <label for="reqclass" >{{ MyHelpers::admin_trans(auth()->user()->id,'req classification agent') }}</label>
                            <select  onchange="check_rejection(this)" {{$reqStatus == 26 ? 'disabled' : ''}} id="reqclass" class="form-control select2-request @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="">---</option>
                                @foreach ($classifcations as $classifcation )

                                    @if ($purchaseClass->class_id_agent == $classifcation->id || (old('reqclass') == $classifcation->id))
                                        <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                    @else
                                        <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                    @endif

                                @endforeach
                            </select>
                            @error('reqclass')
                            <strong class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="class_id_sm" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                            <label for="reqclass_m" >{{ MyHelpers::admin_trans(auth()->user()->id,'req classification SM') }}</label>
                            <select {{$reqStatus == 26 ? 'disabled' : ''}} id="reqclass_m" class="form-control select2-request @error('reqclass_m') is-invalid @enderror" name="reqclass_m">
                                <option value="">---</option>
                                @foreach ($classifcations2 as $classifcation )
                                    @if ($purchaseClass2->class_id_sm == $classifcation->id || (old('reqclass_m') == $classifcation->id))
                                        <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                    @else
                                        <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('reqclass_m')
                            <strong class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">

                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="class_id_fm" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                            <label for="reqclass_fm" >{{ MyHelpers::admin_trans(auth()->user()->id,'req classification FM') }}</label>
                            <select {{$reqStatus == 26 ? 'disabled' : ''}} id="reqclass_fm" class="form-control select2-request @error('reqclass_fm') is-invalid @enderror" name="reqclass_fm">
                                <option value="">---</option>
                                @foreach ($classifcations3 as $classifcation )
                                    @if ($purchaseClass3->class_id_fm == $classifcation->id || (old('reqclass') == $classifcation->id))
                                        <option value="{{$classifcation->id}}" selected>{{$classifcation->value}}</option>
                                    @else
                                        <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('reqclass_fm')
                            <strong class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </strong>
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
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20"  data-id="comment" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20"></i></span>
                            <label for="reqcomm" >{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea {{$reqStatus == 26 ? 'readonly' : ''}} id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">
                            {{ old('reqcomm', $purchaseCustomer->comment) }}
                            </textarea>
                            @error('reqcomm')
                            <strong class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </strong>
                            @enderror
                        </div>
                    </div>
{{--                    <div class="col-md-6 mb-3">--}}
{{--                        <div class="form-group">--}}
{{--                            <span id="record" role="button" type="button" class="item span-20"  data-id="commentWeb" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                <i class="fa fa-history i-20"></i></span>--}}
{{--                            <label for="webcomm" >{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                            <textarea {{$reqStatus == 26 ? 'readonly' : ''}} id="webcomm" name="webcomm" rows="3" cols="50" class="form-control @error('webcomm') is-invalid @enderror" autocomplete="webcomm" autofocus placeholder="">--}}
{{--                            {{ old('webcomm',$purchaseCustomer->noteWebsite) }}--}}
{{--                            </textarea>--}}
{{--                            @error('webcomm')--}}
{{--                            <strong class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </strong>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}


                </div>
            </div>
        </div>
    </div>
</div>

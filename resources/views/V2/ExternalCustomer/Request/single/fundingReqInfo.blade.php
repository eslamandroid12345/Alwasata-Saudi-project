<div class="userFormsInfo">
    <div class="userFormsContainer mb-3">
        <div class="dataFrom topRow ">

            <div class="userFormsDetails  mt-3">

                <div id="tableAdminOption" class=" row">

                    {{--<div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="reqcomm" class="control-label mb-1">ملاحظة الإستشاري</label>
                            <textarea id="reqcomm" disabled rows="3" cols="50" class="form-control reqcomm_missedFiledInput @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">{{$purchaseCustomer->comment ?? old('reqcomm')}}</textarea>
                        </div>
                    </div>--}}

                    @if (!app()->environment('production'))
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="private_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="private_notes" class="control-label mb-1">ملاحظتي</label>
                            <textarea id="private_notes" name="private_notes"  rows="3" cols="50" class="form-control @error('private_notes') is-invalid @enderror"
                            autocomplete="private_notes" autofocus placeholder="">{{ old('private_notes',$purchaseCustomer->private_notes ??'') }}</textarea>
                            @error('private_notes')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="bank_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="bank_notes" class="control-label mb-1">ملاحظة الوساطة</label>
                            <textarea id="bank_notes" name="bank_notes"  rows="3" cols="50" class="form-control @error('bank_notes') is-invalid @enderror" autocomplete="bank_notes" autofocus placeholder="">
                            {{ old('bank_notes',$purchaseCustomer->bank_notes ??'-') }}
                            </textarea>
                            @error('bank_notes')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @else
                    <div class="col-12">
                        <div class="form-group">
                            <span class="item pointer span-20" id="record" data-id="collaborator_notes" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="collaborator_notes" class="control-label mb-1">ملاحظة موظف البنك</label>
                            <textarea id="collaborator_notes" name="collaborator_notes"  rows="3" cols="50" class="form-control @error('collaborator_notes') is-invalid @enderror" autocomplete="collaborator_notes" autofocus placeholder="">
                            {{ old('collaborator_notes',$purchaseCustomer->collaborator_notes ??'-') }}
                            </textarea>
                            @error('collaborator_notes')
                            <small class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </small>
                            @enderror

                        </div>
                    </div>
                    @endif
                </div>
                <div class="userFormsInfo">
                    <label style="width: 100%; display: block;" for="tab1">
                    <span>
                        <div class="userFormsContainer mb-3">
                            <div class="userFormsDetails topRow">
                                @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
                                    <div class="row">
                                    @if ($followdate != null)
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                            <input id="follow" name="follow" type="date" class="form-control" value="{{ old('follow',$followdate->reminder_date) }}" autocomplete="follow">
                                        </div>
                                    </div>
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                            <input id="follow1" name="follow1" type="time" class="form-control" value="{{ old('follow1',$followtime) }}" autocomplete="follow1">
                                        </div>
                                    </div>
                                        @else
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                            <input id="follow" name="follow" type="date" class="form-control" autocomplete="follow">
                                        </div>
                                    </div>
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                            <input id="follow1" name="follow1" type="time" class="form-control" autocomplete="follow1">
                                        </div>
                                    </div>
                                        @endif
                                </div>
                                @else
                                    <div class="row">
                                    @if ($followdate != null)
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                            <input readonly id="follow" name="follow" type="date" class="form-control" value="{{ old('follow',$followdate->reminder_date) }}" autocomplete="follow">
                                        </div>
                                    </div>
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow1" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                            <input readonly id="follow1" name="follow1" type="time" class="form-control" value="{{ old('follow1',$followtime) }}" autocomplete="follow1">
                                        </div>
                                    </div>
                                        @else
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Date') }}</label>
                                            <input readonly id="follow" name="follow" type="date" class="form-control" autocomplete="follow">
                                        </div>
                                    </div>
                                            <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="follow" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Following Time') }}</label>
                                            <input readonly id="follow1" name="follow1" type="time" class="form-control" autocomplete="follow1">
                                        </div>
                                    </div>
                                        @endif
                                </div>
                                @endif

                            </div>
                        </div>
                    </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">




            <div class="card-body">

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                            <select disabled id="reqtyp" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('reqtyp') is-invalid @enderror" name="reqtyp">

                            <option value="شراء" {{ $purchaseCustomer-> type == 'شراء' || (old('reqtyp') =='شراء' ) ? 'selected' : '' }}>شراء</option>
                                    <option value="رهن" {{ $purchaseCustomer-> type == 'رهن' || (old('reqtyp') =='رهن' ) ? 'selected' : '' }}>رهن</option>
                                    <option value="تساهيل" {{ $purchaseCustomer-> type == 'تساهيل' || (old('reqtyp') =='تساهيل' ) ? 'selected' : '' }}>تساهيل</option>
                                    <option value="شراء-دفعة" {{ $purchaseCustomer-> type == 'شراء-دفعة' || (old('reqtyp') =='شراء-دفعة' ) ? 'selected' : '' }}>شراء-دفعة</option>
                                    <option value="رهن-شراء" {{ $purchaseCustomer-> type == 'رهن-شراء' || (old('reqtyp') =='رهن-شراء' ) ? 'selected' : '' }}>رهن-شراء</option>




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
                            <button class="item" id="record" data-id="class_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
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
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
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

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <textarea readonly id="reqcomm" name="reqcomm" rows="3" cols="50" class="form-control @error('reqcomm') is-invalid @enderror" autocomplete="reqcomm" autofocus placeholder="">
                            {{ old('reqcomm', $purchaseCustomer->comment) }}
                            </textarea>
                            @error('reqcomm')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>

{{--                    <div class="col-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <button class="item" id="record" data-id="commentWeb" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">--}}
{{--                                <i class="fa fa-history" style="font-size: medium;"></i></button>--}}
{{--                            <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                            <textarea readonly id="webcomm" name="webcomm" rows="3" cols="50" class="form-control @error('webcomm') is-invalid @enderror" autocomplete="webcomm" autofocus placeholder="">--}}
{{--                            {{ old('webcomm',$purchaseCustomer->noteWebsite) }}--}}
{{--                            </textarea>--}}
{{--                            @error('webcomm')--}}
{{--                            <span class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </span>--}}
{{--                            @enderror--}}

{{--                        </div>--}}
{{--                    </div>--}}
                </div>


            </div>

        </div>
    </div>
</div>

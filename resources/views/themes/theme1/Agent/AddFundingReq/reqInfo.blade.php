<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="reqtyp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>

                            <select id="reqtyp" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); checkType(this); ' value="{{ old('reqtyp') }}" class="form-control @error('reqtyp') is-invalid @enderror" name="reqtyp">

                                <option value="">---</option>
                                @if ($title == 'funding')
                                <option value="شراء" selected>شراء</option>
                                <option value="رهن">رهن</option>
                                @elseif ($title == 'mortgage')
                                <option value="شراء">شراء</option>
                                <option value="رهن" selected>رهن</option>
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

                            <select id="reqsour" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); checkCollaborator(this);' value="{{ old('reqsour') }}" class="form-control @error('reqsour') is-invalid @enderror" >

                                <option value="">---</option>
                                <option value="مكالمة فائتة">مكالمة فائتة</option>
                                <option value="متعاون">متعاون</option>
                                <option value="صديق">صديق</option>
                                <option value="مدير النظام">مدير النظام</option>
                                <option value="تلفون ثابت">تلفون ثابت</option>
                                <option value="Eskan">Eskan</option>

                            </select>

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

                            <select id="reqclass" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' value="{{ old('reqclass') }}" class="form-control @error('reqclass') is-invalid @enderror" name="reqclass">

                                <option value="">---</option>
                                @foreach ($classifcations as $classifcation )
                                <option value="{{$classifcation->id}}">{{$classifcation->value}}</option>
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

                <div id="collaboratorDiv">
                    <div class="form-group">
                        <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>


                        <select disabled id="collaborator" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('collaborator') is-invalid @enderror" name="collaborator">

                            <option value="">---</option>

                            @if (!empty($collaborators[0]))

                            @foreach ($collaborators as $collaborator )
                            <option value="{{$collaborator->collaborato_id}}">{{$collaborator->name}}</option>
                            @endforeach

                            @else
                            <option disabled="disabled" value="">{{ MyHelpers::admin_trans(auth()->user()->id,'No Collaborator') }}</option>
                            @endif

                        </select>

                        @error('collaborator')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                </div>




                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="reqcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</label>
                            <input id="reqcomm" style="padding: 30px 10px; " name="reqcomm" type="text" class="form-control @error('reqcomm') is-invalid @enderror" value="{{ old('reqcomm') }}" autocomplete="reqcomm" autofocus placeholder="">

                            @error('reqcomm')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>

{{--                    <div class="col-6">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="webcomm" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'comment on website') }}</label>--}}
{{--                            <input id="webcomm" style="padding: 30px 10px; " name="webcomm" type="text" class="form-control @error('webcomm') is-invalid @enderror" value="{{ old('webcomm') }}" autocomplete="webcomm" autofocus placeholder="">--}}

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

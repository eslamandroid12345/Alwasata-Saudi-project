  <!-- begin::portlet  -->
  <div class="portlet portlet-student">
    <!-- begin::portlet__body  -->
    <div class="portlet__body">
      <h5 class="mb-4 font-medium text-white text-center bg-primary p-2 rounded">بيانات الطلب</h5>
      <div class="row">
        <div class="col-lg-4 col-sm-6">
          <div class="form-group">
            <label>
                <span class="item pointer span-20" id="record" data-id="reqtyp" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalClassification">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</label>
            {{-- <select class="selectpicker" data-live-search="true">
              <option value="1">شراء </option>
              <option value="1">شراء </option>
              <option value="1">شراء </option>
            </select> --}}
            <select id="reqtyp" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="selectpicker  @error('reqtyp') is-invalid @enderror" name="reqtyp" data-live-search="true">

                <option value="" selected>---</option>
                <option value="شراء" {{ $purchaseCustomer->type == 'شراء' || (old('reqtyp') =='شراء' ) ? 'selected' : '' }}>شراء</option>
                <option value="رهن" {{ $purchaseCustomer->type == 'رهن' || (old('reqtyp') =='رهن' ) ? 'selected' : '' }}>رهن</option>
                <option value="شراء-دفعة" disabled {{ $purchaseCustomer->type == 'شراء-دفعة' || (old('reqtyp') =='شراء-دفعة' ) ? 'selected' : '' }}>شراء-دفعة</option>
                <option value="رهن-شراء" disabled {{ $purchaseCustomer->type == 'رهن-شراء' || (old('reqtyp') =='رهن-شراء' ) ? 'selected' : '' }}>رهن-شراء</option>

                @if ($agentuser->isTsaheel == 1)
                <option {{ $purchaseCustomer-> type == 'تساهيل' || (old('reqtyp') =='تساهيل' ) ? 'selected' : '' }} value="تساهيل">تساهيل</option>
                @endif
            </select>
            @error('reqtyp')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
          </div>
        </div>

        {{-- khaled --}}
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>مصدر التمويل</label>
              <select id="funding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="selectpicker  @error('funding_source') is-invalid @enderror" name="funding_source" data-live-search="true">
                    <option value="">--</option>
                    <option value="رهن مباشر" {{ $purchaseCustomer->funding_source == 'رهن مباشر' || (old('funding_source') =='رهن مباشر' ) ? 'selected' : '' }}>رهن مباشر</option>
                    <option value="إعادة تمويل" {{ $purchaseCustomer->funding_source == 'إعادة تمويل' || (old('funding_source') =='إعادة تمويل' ) ? 'selected' : '' }}> إعادة تمويل</option>
              </select>
              @error('funding_source')
                  <small class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </small>
              @enderror
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="form-group">
            <label>
                <span class="item pointer span-20" id="record" data-id="reqSource" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalClassification">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                 مصدر المعاملة</label>
            @if($purchaseCustomer->source != null)
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

            @else
                <select id="reqsour" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); checkCollaborator(this);' class=" @error('reqsour') is-invalid @enderror selectpicker" data-live-search="true" >

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

            @endif
            {{-- <input class="form-control" type="text" value="صديق" /> --}}
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="form-group">
            <label>
              <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalClassification">
                <path
                  id="Icon_material-history"
                  data-name="Icon material-history"
                  d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                  transform="translate(-1.5 -4.5)"
                ></path>
              </svg>
              {{ MyHelpers::admin_trans(auth()->user()->id,'req classification') }}
            </label>
            <select id="reqclass" onchange="check_rejection(this)" class=" @error('reqclass') is-invalid @enderror selectpicker" data-live-search="true" name="reqclass"  @if ($purchaseClass->class_id_agent != null) @if ($purchaseClass->class_id_agent == 57 || $purchaseClass->class_id_agent == 58)  disabled @endif @endif  >

                <option value="">---</option>
                @foreach ($classifcations as $classifcation )

                @if ( ((!$hide_negative_comment) && $purchaseClass->class_id_agent == $classifcation->id) || (old('reqclass') == $classifcation->id))
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
        {{-- additional data --}}
        <div class="col-lg-4 col-sm-6" id="reasonOfCancelation" style="display: {{$purchaseClass->class_id_agent ==16 ? 'block' :'none'}}">
            <div class="form-group">
                <label>
                    <span class="item pointer span-20" id="record" data-id="rejection_id_agent" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalClassification">
                        <path
                            id="Icon_material-history"
                            data-name="Icon material-history"
                            d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                            transform="translate(-1.5 -4.5)"
                        ></path>
                        </svg>
                    </span>
                    سبب الرفض
                  </label>

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
        <div id="collaboratorDiv"  class="col-lg-4 col-sm-6">
            <div class="form-group">
                <label>
                    <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                        <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalClassification">
                        <path
                            id="Icon_material-history"
                            data-name="Icon material-history"
                            d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                            transform="translate(-1.5 -4.5)"
                        ></path>
                        </svg>
                    </span>
                    {{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}
                  </label>

                @if (!empty($collaborator))
                <input readonly id="collaborator" name="collaborator" type="text" class="form-control @error('collaborator') is-invalid @enderror" value="{{ $collaborator->name }}" autocomplete="collaborator" autofocus placeholder="" name="collaborator">

                @else

                <select id="collaborator" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class=" @error('collaborator') is-invalid @enderror selectpicker" data-live-search="true" name="collaborator">
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
        <div id="collaboratorDiv2" class="col-lg-4 col-sm-6" {{(old('reqsour') !=2) ? 'hidden' : ''}}>

                <div class="form-group">
                    <label>
                        <span class="item pointer span-20" id="record" data-id="collaborator_name" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalClassification">
                            <path
                                id="Icon_material-history"
                                data-name="Icon material-history"
                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                transform="translate(-1.5 -4.5)"
                            ></path>
                            </svg>
                        </span>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}
                      </label>

                    <select id="collaborator" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class=" @error('collaborator') is-invalid @enderror selectpicker" data-live-search="true" name="collaborator">

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
        {{-- additional data end --}}
        <div class="col-lg-4 col-sm-6">
          <div class="form-group">
            <label>
                <span class="item pointer span-20" id="record" data-id="comment" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalNote">
                        <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                        ></path>
                    </svg>
                </span>
              {{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}
            </label>
            <input class="form-control @error('reqcomm') is-invalid @enderror" type="text" id="reqcomm" name="reqcomm" value="{{ old('reqcomm')}}"/>
            <small style="color:#e60000" class="d-none reqcomm_missedFileds missedFileds">الحقل مطلوب</small>
            @error('reqcomm')
            <small class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </small>
            @enderror
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="form-group">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <label class="mb-0">
                <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                تاريخ الطلب
              </label>
            </div>
            <div class="input-icon">
              <input class="form-control datetimepicker_1" type="text" disabled value="{{\Carbon\Carbon::parse($purchaseCustomer->created_at)->format('d-m-Y')}}" />
              <div class="icon">
                <svg id="calendar" xmlns="http://www.w3.org/2000/svg" width="17.396" height="16.989" viewBox="0 0 17.396 16.989">
                  <path
                    id="Path_2784"
                    data-name="Path 2784"
                    d="M19.1,24.906H6.714a2.5,2.5,0,0,1-2.5-2.5V16.412a.626.626,0,0,1,1.252,0V22.4a1.252,1.252,0,0,0,1.252,1.252H19.1A1.252,1.252,0,0,0,20.354,22.4V11.814A1.252,1.252,0,0,0,19.1,10.562H6.714a1.252,1.252,0,0,0-1.252,1.252V14.28a.626.626,0,0,1-1.252,0V11.814a2.5,2.5,0,0,1,2.5-2.5H19.1a2.5,2.5,0,0,1,2.5,2.5V22.4A2.5,2.5,0,0,1,19.1,24.906Z"
                    transform="translate(-4.21 -7.917)"
                    fill="#6c757d"
                  ></path>
                  <path
                    id="Path_2785"
                    data-name="Path 2785"
                    d="M18.476,11.849H4.836a.626.626,0,1,1,0-1.252h13.64a.626.626,0,0,1,0,1.252ZM9.23,8.9A.626.626,0,0,1,8.6,8.275V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,9.23,8.9Zm7.355,0a.626.626,0,0,1-.626-.626V5.486a.626.626,0,1,1,1.252,0V8.275A.626.626,0,0,1,16.585,8.9Z"
                    transform="translate(-4.21 -4.86)"
                    fill="#6c757d"
                  ></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="form-group">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <label class="mb-0">
                <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                توقيت الطلب
              </label>
            </div>
            <div class="input-icon">
              <input class="form-control datetimeclock" type="text" disabled value="{{\Carbon\Carbon::parse($purchaseCustomer->created_at)->format('H:i')}}" />
              <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="17.989" height="17.989" viewBox="0 0 17.989 17.989">
                  <g id="Icon_feather-clock" data-name="Icon feather-clock" transform="translate(-2.5 -2.5)">
                    <path
                      id="Path_4042"
                      data-name="Path 4042"
                      d="M19.989,11.494A8.494,8.494,0,1,1,11.494,3,8.494,8.494,0,0,1,19.989,11.494Z"
                      fill="none"
                      stroke="#6c757d"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1"
                    ></path>
                    <path
                      id="Path_4043"
                      data-name="Path 4043"
                      d="M18,9v5.1l3.4,1.7"
                      transform="translate(-6.506 -2.602)"
                      fill="none"
                      stroke="#6c757d"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1"
                    ></path>
                  </g>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end::portlet__body  -->
  </div>
  <!-- end::portlet  -->

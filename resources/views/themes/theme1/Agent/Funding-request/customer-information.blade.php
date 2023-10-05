 <!-- begin::portlet  -->
 <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head">
      <!-- begin::portlet__head-label  -->
      <div class="portlet__head-label">بيانات العميل</div>
      {{-- <a class="btn btn-primary" href="">
        <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.325" height="15.325" viewBox="0 0 15.325 15.325">
          <g id="Icon_feather-printer" data-name="Icon feather-printer" transform="translate(-2.5 -2.5)">
            <path id="Path_3985" data-name="Path 3985" d="M9,8.014V3h8.6V8.014" transform="translate(-3.135)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
            <path
              id="Path_3986"
              data-name="Path 3986"
              d="M5.865,19.946H4.433A1.433,1.433,0,0,1,3,18.514V14.933A1.433,1.433,0,0,1,4.433,13.5h11.46a1.433,1.433,0,0,1,1.433,1.433v3.581a1.433,1.433,0,0,1-1.433,1.433H14.46"
              transform="translate(0 -5.486)"
              fill="none"
              stroke="#fff"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1"
            ></path>
            <path id="Path_3987" data-name="Path 3987" d="M9,21h8.6v5.73H9Z" transform="translate(-3.135 -9.405)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
          </g>
        </svg>
        طباعة استمارة إفراغ عقار
      </a> --}}
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    <div class="portlet__body pt-0">
      <div class="border rounded-5 p-3">
        <div class="row">
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="customerName" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                        <path
                          id="Icon_material-history"
                          data-name="Icon material-history"
                          d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                          transform="translate(-1.5 -4.5)"
                        ></path>
                    </svg>
                </span>

                إسم العميل
              </label>
              <input id="name" name="name" type="text" class="form-control name_missedFiledInput FiledInput" value="{{  old('name', $purchaseCustomer->name) }}" autocomplete="name" >
              <small style="color:#e60000" class="d-none name_missedFileds missedFileds">الحقل مطلوب</small>

            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="agent_identity_number" type="button" data-toggle="tooltip" data-placement="top" title="@lang('language.records')">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                رقم الهوية
              </label>
              <input id="agent_identity_number" name="agent_identity_number" type="number" class="form-control agent_identity_number_missedFiledInput FiledInput" value="{{  old('agent_identity_number', $purchaseCustomer->agent_identity_number) }}" autocomplete="agent_identity_number" >
              <small style="color:#e60000" class="d-none agent_identity_number_missedFileds missedFileds">الحقل مطلوب</small>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="sex" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                الجنس
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input type="radio" name="sex" value="ذكر" @if (old('sex')=='ذكر' ) checked @elseif ($purchaseCustomer->sex == 'ذكر') checked @endif /><span class="checkmark"></span>ذكر </label>
                <label class="m-radio ms-5"> <input type="radio" name="sex" value="أنثى" @if (old('sex')=='أنثى' ) checked @elseif ($purchaseCustomer->sex == 'أنثى') checked @endif /><span class="checkmark"></span>انثى </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="mb-0">
                <span id="record" role="button" type="button" class="item span-20" data-id="mobile" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                  <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                      id="Icon_material-history"
                      data-name="Icon material-history"
                      d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                      transform="translate(-1.5 -4.5)"
                    ></path>
                  </svg>
                </span>
                  جوال العميل
                </label>
                <div class="btn btn-primary btn-icon btn-icon-small" type="button" onclick="addForm()">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="10px">
                    <path
                      d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"
                      fill="#FFF"
                    ></path>
                  </svg>
                </div>
              </div>
              <input id="mobile" name="mobile" readonly type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ $purchaseCustomer->mobile }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                @error('mobile')
                <small class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
                @enderror
                <small class="text-danger" id="error" role="alert"> </small>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="mb-0">
                <span id="record" role="button" type="button" class="item span-20" data-id="birth_date" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                  <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                      id="Icon_material-history"
                      data-name="Icon material-history"
                      d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                      transform="translate(-1.5 -4.5)"
                    ></path>
                  </svg>
                </span>
                  تاريخ الميلاد
                </label>
              </div>
              <div class="input-icon">
                <input id="birth" style="text-align: right" name="birth" type="date" class="form-control birth_missedFiledInput FiledInput @error('birth') is-invalid @enderror" value="{{ $purchaseCustomer->birth_date }}" autocomplete="birth" onblur="calculate()" autofocus>
                {{-- <input class="form-control datetimepicker_1" type="text" value="18-12-1999" /> --}}
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
                <span id="record" role="button" type="button" class="item span-20" data-id="birth_hijri" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                  <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                      id="Icon_material-history"
                      data-name="Icon material-history"
                      d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                      transform="translate(-1.5 -4.5)"
                    ></path>
                  </svg>
                </span>
                  تاريخ الميلاد (مـ)
                </label>
              </div>
              <div class="input-icon">
                {{-- <input class="form-control hijri-date-input" type="text" value="18-12-1999" /> --}}
                <input type='text' class="form-control hijri-date-input" name="birth_hijri" value="{{ $purchaseCustomer->birth_date_higri }}" style="text-align: right;" class="form-control birth_hijri_missedFiledInput FiledInput" placeholder="يوم/شهر/سنة" id="hijri-date" />
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
              <label>
                <span class="item pointer span-20" id="record" data-id="age_years" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                العمر
              </label>
              {{-- <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age',$purchaseCustomer->age) }}" autocomplete="age" autofocus readonly>
              @error('age')
              <small class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </small>
              @enderror --}}
              <input class="form-control"  name="age" type="text" value="{{ old('age',$purchaseCustomer->age) }}"/>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="work" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                جهة العمل
              </label>
              <select id="work" onchange="this.size=1; this.blur(); checkWork(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control work_missedFiledInput FiledInput selectpicker @error('work') is-invalid @enderror" data-live-search="true" name="work" >
                    <option value="">---</option>
                @foreach ($worke_sources as $worke_source )
                    @if ($purchaseCustomer->work == $worke_source->id || (old('work') == $worke_source->id) )
                    <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                    @else
                    <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                    @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                صافي الراتب
              </label>
              <input id="salary" name="salary" type="number" class="form-control salary_missedFiledInput FiledInput" value="{{ old('salary',$purchaseCustomer->salary) }}" autocomplete="salary" >
              <small style="color:#e60000" class="d-none salary_missedFileds missedFileds">الحقل مطلوب</small>
              {{-- <input class="form-control" type="text" value="25" /> --}}
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="salary_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                جهة نزول الراتب
              </label>
              <select id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control salary_source_missedFiledInput FiledInput selectpicker @error('salary_source') is-invalid @enderror" data-live-search="true" name="salary_source">
                @foreach ($salary_sources as $salary_source )
                    @if ($purchaseCustomer->salary_id == $salary_source->id || (old('salary_source') == $salary_source->id))
                    <option value="{{$salary_source->id}}" selected>{{$salary_source->value}}</option>
                    @else
                    <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                    @endif
                @endforeach
            </select>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="support" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                    <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                    <path
                        id="Icon_material-history"
                        data-name="Icon material-history"
                        d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                        transform="translate(-1.5 -4.5)"
                    ></path>
                    </svg>
                </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input type="radio"  id="is_support" name="is_support" value="yes" @if (old('is_support')=='yes' ) checked @elseif ($purchaseCustomer->is_supported == 'yes') checked @endif /><span class="checkmark"></span>نعم </label>
                <label class="m-radio ms-5"> <input type="radio"  id="is_support_1" name="is_support" value="no" @if (old('is_support')=='no' ) checked @elseif ($purchaseCustomer->sex == 'no') checked @endif /><span class="checkmark"></span>لا </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="basic_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                الراتب الأساسي
              </label>
              <input readonly id="basic_salary" name="basic_salary" type="number" class="form-control" value="{{ old('basic_salary',$purchaseCustomer->basic_salary) }}" autocomplete="basic_salary"/>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="without_transfer_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                بدون تحويل الراتب
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input type="radio"  id="without_transfer_salary" name="without_transfer_salary" value="1" @if (old('without_transfer_salary')=='1' ) checked @elseif ($purchaseCustomer->without_transfer_salary == '1') checked @endif /><span class="checkmark"></span>نعم </label>
                <label class="m-radio ms-5"> <input type="radio"  id="without_transfer_salary_1" name="without_transfer_salary" value="0" @if (old('without_transfer_salary')=='0' ) checked @elseif ($purchaseCustomer->without_transfer_salary == '0') checked @endif /><span class="checkmark"></span>لا </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="add_support_installment_to_salary" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                إضافة قسط الدعم إلى الراتب
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input type="radio"  id="add_support_installment_to_salary" name="add_support_installment_to_salary" value="1" @if (old('add_support_installment_to_salary')=='1' ) checked @elseif ($purchaseCustomer->add_support_installment_to_salary == '1') checked @endif /><span class="checkmark"></span>نعم </label>
                <label class="m-radio ms-5"> <input type="radio"  id="add_support_installment_to_salary_1" name="add_support_installment_to_salary" value="0" @if (old('add_support_installment_to_salary')=='0' ) checked @elseif ($purchaseCustomer->add_support_installment_to_salary == '0') checked @endif /><span class="checkmark"></span>لا </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="guarantees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                الضمانات
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input type="radio"  id="guarantees" name="guarantees" value="1" @if (old('guarantees')=='1' ) checked @elseif ($purchaseCustomer->guarantees == '1') checked @endif /><span class="checkmark"></span>نعم </label>
                <label class="m-radio ms-5"> <input type="radio"  id="guarantees_1" name="guarantees" value="0" @if (old('guarantees')=='0' ) checked @elseif ($purchaseCustomer->guarantees == '0') checked @endif/><span class="checkmark"></span>لا </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="obligations" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input  id="has_obligations" name="has_obligations" type="radio" value="yes" onchange='this.size=1;checkObligation(this);' @if (old('has_obligations')=='yes' ) checked @elseif ($purchaseCustomer->has_obligations == 'yes') checked @endif/><span class="checkmark"></span>نعم </label>
                <label class="m-radio ms-5"> <input  name="has_obligations" type="radio" value="no" onchange='this.size=1;checkObligation(this);' @if (old('has_obligations')=='no' ) checked @elseif ($purchaseCustomer->has_obligations == 'no') checked @endif/><span class="checkmark"></span>لا </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="obligations_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                {{ MyHelpers::admin_trans(auth()->user()->id,'obligations value') }}
              </label>
              <input readonly id="obligations_value" name="obligations_value" type="number" class="form-control" value="{{ old('obligations_value',$purchaseCustomer->obligations_value) }}" autocomplete="obligations_value" />
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="distress" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                هل لديه تعثرات
              </label>
              <div class="d-flex pt-2">
                <label class="m-radio ms-5"> <input type="radio" name="has_financial_distress" value="yes" onchange='this.size=1;checkDistress(this);' @if (old('has_financial_distress')=='yes' ) checked @elseif ($purchaseCustomer->has_financial_distress == 'yes') checked @endif /><span class="checkmark"></span>نعم </label>
                <label class="m-radio ms-5"> <input type="radio" name="has_financial_distress" value="no" onchange='this.size=1;checkDistress(this);' @if (old('has_financial_distress')=='no' ) checked @elseif ($purchaseCustomer->has_financial_distress == 'no') checked @endif /><span class="checkmark"></span>لا </label>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-6">
            <div class="form-group">
              <label>
                <span class="item pointer span-20" id="record" data-id="financial_distress_value" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">

                <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964" data-bs-toggle="modal" data-bs-target="#modalOrderRecord">
                  <path
                    id="Icon_material-history"
                    data-name="Icon material-history"
                    d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                    transform="translate(-1.5 -4.5)"
                  ></path>
                </svg>
                </span>
                قيمة التعثرات
              </label>
              <input class="form-control" id="financial_distress_value" name="financial_distress_value" type="text" @if (old('has_financial_distress')=='no' || $purchaseCustomer->has_financial_distress == 'no' || $purchaseCustomer->has_financial_distress == null ) readonly @endif value="{{ old('financial_distress_value',$purchaseCustomer->financial_distress_value) }}" autocomplete="financial_distress_value" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end::portlet__body  -->
  </div>
  <!-- end::portlet  -->

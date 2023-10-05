
  <!-- begin::portlet  -->
  <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head">
      <!-- begin::portlet__head-label  -->
      <div class="portlet__head-label"> {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}</div>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    <div class="portlet__body pt-0">
      <div class="border rounded-5 p-3">
        <div class="row">
            <div class="userFormsInfo  ">
                <div class="userFormsContainer mb-3">
                    <div class="tsaheelInfo topRow ">
                        <div class="tsaheelHeader">
                            <div class="addBtn">
                                <button class="w-100" role="button" type="button">
                                    <i class="fas fa-plus-circle"></i>
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }}
                                </button>
                            </div>
                        </div>
                        <div class="tsaheeldiv  mt-3">
                        @if ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)
                            <div class="row tableAdminOption22" id="tableAdminOption">
                                <div class="col-12 mb-4">
                                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                            <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                                <button type="button" class="text-center mr-3 green item" role="button" >
                                                    <i class="fas fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="visa" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="preVisa" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                                        <input id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control visa_m" value="{{  old('visa',$purchaseTsa->visa) }}" autocomplete="visa" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="carlo" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                                        <input id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control car_m" value="{{ old('carlo',$purchaseTsa->carLo) }}" autocomplete="carlo" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="perlo" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                                        <input id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control perlo_m" value="{{ old('perlo',$purchaseTsa->personalLo) }}" autocomplete="perlo" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="realo" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                                        <input id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control  realo_missedFiledInput realo_m" value="{{ old('rreal estate loanealo',$purchaseTsa->realLo) }}" autocomplete="realo" autofocus >


                                        <small style="color:#e60000" class="d-none realo_missedFileds missedFileds">الحقل مطلوب</small>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="credban" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                                        <input id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('credban',$purchaseTsa->credit) }}" autocomplete="credban" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="credban" class="control-label mb-1">
                                        <span class="item pointer span-20 " id="record" data-id="realDisposition" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                             {{ MyHelpers::admin_trans(auth()->user()->id,'Real estate disposition') }}</label>
                                        <input id="Real_estate_disposition_value_tsaheel" name="Real_estate_disposition_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_disposition_value" value="{{  $purchaseTsa->Real_estate_disposition_value }}" autocomplete="Real_estate_disposition_value_tsaheel" autofocus>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="credban" class="control-label mb-1">
                                        <span class="item pointer span-20 " id="record" data-id="purchaseTax" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                             {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase tax') }}</label>
                                        <input id="purchase_tax_value_tsaheel" name="purchase_tax_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_purchase_tax_value" value="{{  $purchaseTsa->purchase_tax_value }}" autocomplete="purchase_tax_value_tsaheel" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="other" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                                        <input id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control other1_m" value="{{ old('other1',$purchaseTsa->other) }}" autocomplete="other" autofocus>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="debt" class="control-label mb-1">
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                                        <input id="debt" name="debt" type="number" class="form-control" value="{{ old('debt',$purchaseTsa->debt) }}" autocomplete="debt" autofocus readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="morpre" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                                        <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ old('morpre',$purchaseTsa->mortPre) }}" autocomplete="morpre" autofocus>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <label for="morcos" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                        <input id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ old('morcos',$purchaseTsa->mortCost) }}" autocomplete="morcos" autofocus>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="propre" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                                        <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ old('propre',$purchaseTsa->proftPre) }}" onblur="profcalculate()" autocomplete="propre" autofocus>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <label for="procos" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                        <input id="" name="procos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->profCost }}" autocomplete="procos" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="valadd" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                                        <input id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ old('valadd',$purchaseTsa->addedVal) }}" autocomplete="valadd" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="admfe" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                                        <input id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ old('admfe',$purchaseTsa->adminFee) }}" autocomplete="admfe" autofocus>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row tableAdminOption22" id="tableAdminOption">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="visa" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="preVisa" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                                        <input readonly id="visa" name="visa" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->visa }}" autocomplete="visa" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="carlo" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="carLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                                        <input readonly id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control car_m" value="{{ $purchaseTsa->carLo }}" autocomplete="carlo" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="perlo" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="personalLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                                        <input readonly id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control perlo_m" value="{{ $purchaseTsa->personalLo }}" autocomplete="perlo" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="realo" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="realLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                                        <input readonly id="realo" name="realo" type="number" min="0" onblur="debtcalculate()" class="form-control realo_m" value="{{ $purchaseTsa->realLo }}" autocomplete="realo" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="credban" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="credBank" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                                        <input readonly id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ $purchaseTsa->credit }}" autocomplete="credban" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="credban" class="control-label mb-1">
                                        <span class="item pointer span-20 " id="record" data-id="realDisposition" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                             {{ MyHelpers::admin_trans(auth()->user()->id,'Real estate disposition') }}</label>
                                        <input readonly id="Real_estate_disposition_value_tsaheel" name="Real_estate_disposition_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_disposition_value" value="{{  $purchaseTsa->Real_estate_disposition_value }}" autocomplete="Real_estate_disposition_value_tsaheel" autofocus>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="credban" class="control-label mb-1">
                                        <span class="item pointer span-20 " id="record" data-id="purchaseTax" data-comment="بيانات التساهيل" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                             {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase tax') }}</label>
                                        <input readonly id="purchase_tax_value_tsaheel" name="purchase_tax_value_tsaheel" min="0" type="number" onblur="debtcalculate()" class="form-control m_purchase_tax_value" value="{{  $purchaseTsa->purchase_tax_value }}" autocomplete="purchase_tax_value_tsaheel" autofocus>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="other" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="otherLo" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'other 111') }}</label>
                                        <input readonly id="other1" name="other1" type="number" onblur="debtcalculate()" min="0" class="form-control other1_m" value="{{ $purchaseTsa->other }}" autocomplete="other" autofocus>

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="debt" class="control-label mb-1">
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                                        <input readonly id="debt" name="debt" type="number" class="form-control" value="{{ $purchaseTsa->debt }}" autocomplete="debt" autofocus readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="morpre" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="morPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                                        <input readonly id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->mortPre }}" onblur="mortcalculate()" autocomplete="morpre" autofocus>

                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <label for="morcos" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="mortCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                        <input readonly id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->mortCost }}" autocomplete="morcos" autofocus readonly>

                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="propre" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="pursitPresnt" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                                        <input readonly id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ $purchaseTsa->proftPre }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="form-group">
                                        <label for="procos" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="profCost" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                                        <input id="procos" name="procos" type="number" min="0" class="form-control" value="{{ old('procos',$purchaseTsa->profCost) }}" autocomplete="procos" autofocus readonly>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="valadd" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="addedValue" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                                        <input readonly id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ $purchaseTsa->addedVal }}" autocomplete="valadd" autofocus>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="admfe" class="control-label mb-1">
                                        <span class="item pointer span-20" id="record" data-id="adminFees" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                            <svg class="ms-2 pointer" xmlns="http://www.w3.org/2000/svg" width="15.125" height="12.964" viewBox="0 0 15.125 12.964">
                                            <path
                                                id="Icon_material-history"
                                                data-name="Icon material-history"
                                                d="M10.143,4.5a6.482,6.482,0,0,0-6.482,6.482H1.5l2.8,2.8.05.1,2.91-2.9H5.1A5.066,5.066,0,1,1,6.585,14.54L5.562,15.563A6.48,6.48,0,1,0,10.143,4.5Zm-.72,3.6v3.6L12.5,13.532l.519-.871-2.521-1.5V8.1Z"
                                                transform="translate(-1.5 -4.5)"
                                            ></path>
                                            </svg>
                                        </span>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                                        <input readonly id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ $purchaseTsa->adminFee }}" autocomplete="admfe" autofocus>

                                    </div>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('Agent.fundingReq.prepayment')
            @include('MortgageCalculator.mortgage_info')
        </div>
      </div>
    </div>
    <!-- end::portlet__body  -->
  </div>
  <!-- end::portlet  -->


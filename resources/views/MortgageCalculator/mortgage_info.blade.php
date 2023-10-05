<div class="userFormsInfo  ">
    <div class="userFormsContainer mb-3">
        <div class="mortgageInfo topRow ">
            <div class="mortgageInfoHeader">
                <div class="addBtn">
                    <button class="w-100" role="button" type="button">
                        <i class="fas fa-plus-circle"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Calculator') }}
                    </button>
                </div>
            </div>
            <div class="mortgageDiv  mt-3">
                <input id="request_id" name="request_id" type="hidden" value="{{ $purchaseCustomer->id }}">
                @if ( (($purchaseCustomer->type == 'رهن' || $purchaseCustomer->type == 'تساهيل') && ($reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31)) || (($purchaseCustomer->type == 'شراء-دفعة' ) && ($purchaseTsa->payStatus == 4 || $purchaseTsa->payStatus == 3 ) && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13)) )
                <div class="row tableAdminOption" id="tableAdminOption">
                    <input id="mortgage_calculator_status" name="mortgage_calculator_status" type="hidden" value="1">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="real_property_cost" class="control-label mb-1">
                                <span class="item pointer span-20 " id="record" data-id="realCost" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                عرض السعر ( {{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }} )</label>
                            <input id="real_property_cost" name="real_property_cost" type="number" class="form-control realcost_m " onblur="mortgageDebtCalculate(); purchaseTaxCalculate(); firstBatchCalculate2();" value="{{ $purchaseReal->cost }}" autocomplete="realcost" autofocus>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fundingpersonal" class="control-label mb-1">
                                <span class="item pointer span-20 " id="record" data-id="fundPers" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                            <input id="funding_personal" name="funding_personal" type="number" class="form-control funding_personal_m" onblur="updatePersonalFunding();" value="{{ $purchaseFun->personalFun_cost }}" autofocus>
                        </div>
                    </div>
                    <div class="col-12 ml-5" style="text-align: center; font-weight:bold; padding-bottom: 3%;">
                        <p>الوضع الإئتماني للعميل</p>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <p class="col-4">نوع الالتزام</p>
                            <p class="col-4">المبلغ</p>
                            <p class="col-4">النسبة</p>
                        </div>
                        <div class="row">
                            <label for="mortgaged" class="control-label mb-1 col-4">
                                <span class="item pointer span-20 " id="record" data-id="realLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'mortgaged') }}
                            </label>
                            <div class="col-4">
                                <input id="mortgaged_value" name="mortgaged_value" min="0" type="number" onblur="mortgageDebtCalculate(); realEstateDispositionCalculate();" class="form-control realo_m" value="{{ $purchaseTsa->realLo }}" autocomplete="realo" autofocus>
                            </div>
                            <div class="col-4">
                                @include('MortgageCalculator.percentageDropDown.mortgaged_percentage')
                            </div>

                            <label for="Real_estate_disposition_value" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="realDisposition" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Real estate disposition') }}
                            </label>
                            <div class="col-4 mt-2">
                                <input id="Real_estate_disposition_value" name="Real_estate_disposition_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control m_disposition_value" value="{{  $purchaseTsa->Real_estate_disposition_value }}" autocomplete="Real_estate_disposition_value" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.Real_estate_disposition_percentage')
                            </div>

                            <label for="purchase_tax_value" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="purchaseTax" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase tax') }}
                            </label>
                            <div class="col-4 mt-2">
                                <input id="purchase_tax_value" name="purchase_tax_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control m_purchase_tax_value" value="{{ $purchaseTsa->purchase_tax_value }}" autocomplete="Real_estate_disposition_value" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.purchase_tax_percentage')
                            </div>

                            <hr>
                            <label for="first_batch_value" class="control-label mb-1 col-2 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="preValue" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'first batch') }}
                            </label>
                            <div class="col-2 mt-2">
                                <input class="form-check-input" id="presonal_funding_discount" name="presonal_funding_discount" onchange="personalFundingDiscount()" type="checkbox" title="خصم التمويل الشخصي">

                                <div class="item pointer" style="padding-right: 7%;" type="button" data-toggle="tooltip" data-placement="top" title="تغيير نسبة الدفعة الأولى">
                                    @include('MortgageCalculator.percentageDropDown.first_batch_from_realValue')
                                </div>
                            </div>
                            <div class="col-4 mt-2">
                                <input id="first_batch_value" name="first_batch_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control preval_m" value="0" autocomplete="preval" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.first_batch_percentage')
                            </div>

                            <hr>

                            <label for="personal_mortgage" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="personalLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'personal') }}
                            </label>
                            <div class="col-4 mt-2">
                                <input id="personal_mortgage" name="personal_mortgage" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control perlo_m" value="{{ $purchaseTsa->personalLo }}" autocomplete="personal_mortgage" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.perlo_percentage')
                            </div>

                            <label for="car" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="carLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'car') }}
                            </label>
                            <div class="col-4 mt-2">
                                <input id="car_mortgage" name="car_mortgage" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control car_m" value="{{ $purchaseTsa->carLo }}" autocomplete="carlo" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.car_percentage')
                            </div>


                            <label for="visa" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="preVisa" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'visa') }}
                            </label>
                            <div class="col-4 mt-2">
                                <input id="visa_mortgage" name="visa_mortgage" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control visa_m" value="{{ $purchaseTsa->visa }}" autocomplete="visa_mortgage" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.visa_percentage')
                            </div>

                            <label for="beside_percentage" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="otherLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'beside') }}
                            </label>
                            <div class="col-4 mt-2">
                                <input id="beside_value" name="beside_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control other1_m" value="{{ $purchaseTsa->other }}" autocomplete="beside_value" autofocus>
                            </div>
                            <div class="col-4 mt-2">
                                @include('MortgageCalculator.percentageDropDown.beside_percentage')
                            </div>

                            <label for="visa" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="otherFees" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Other fees') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input id="other_fees" name="other_fees" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control" value="{{ $purchaseTsa->other_fees }}" autocomplete="other_fees" autofocus>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mortgage_debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                                    <input id="mortgage_debt" name="mortgage_debt" type="number" class="form-control" value="{{ $purchaseTsa->mortgage_debt }}" autocomplete="mortgage_debt" autofocus readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mortgage_debt_with_tax" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt with tax') }}</label>
                                    <input id="mortgage_debt_with_tax" name="mortgage_debt_with_tax" type="number" class="form-control" value="{{ $purchaseTsa->mortgage_debt_with_tax }}" autocomplete="mortgage_debt_with_tax" autofocus readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="net_to_customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'The net to the customer') }}</label>
                                    <input id="net_to_customer" name="net_to_customer" type="number" class="form-control" value="{{ $purchaseTsa->net_to_customer }}" autocomplete="net_to_customer" autofocus readonly>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 mb-4">
                        <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                <button type="button" id="save_mortgage" class="text-center mr-3 green" role="button">
                                    <i class="fas fa-save"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'select mortgage result') }}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                @else
                <div class="row tableAdminOption" id="tableAdminOption">
                    <input id="mortgage_calculator_status" name="mortgage_calculator_status" type="hidden" value="0">

                    <div class="col-12">
                        <div class="form-group">
                            <label for="real_property_cost" class="control-label mb-1">
                                <span class="item pointer span-20 " id="record" data-id="realCost" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'real estate cost') }}</label>
                            <input readonly id="real_property_cost" name="real_property_cost" type="number" class="form-control realcost_m " onblur="mortgageDebtCalculate()" value="{{ $purchaseReal->cost }}" autocomplete="realcost" autofocus>
                        </div>
                    </div>
                    <div class="col-12 ml-5">
                        <p>الوضع الإئتماني للعميل</p>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <p class="col-4">نوع الالتزام</p>
                            <p class="col-4">المبلغ</p>
                        </div>
                        <div class="row">
                            <label for="mortgaged" class="control-label mb-1 col-4">
                                <span class="item pointer span-20 " id="record" data-id="realLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'mortgaged') }}
                            </label>
                            <div class="col-8">
                                <input readonly id="mortgaged_value" name="mortgaged_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control realo_m" value="{{ $purchaseTsa->realLo }}" autocomplete="realo" autofocus>
                            </div>


                            <label for="Real_estate_disposition_value" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="realDisposition" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Real estate disposition') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="Real_estate_disposition_value" name="Real_estate_disposition_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control" value="{{  $purchaseTsa->Real_estate_disposition_value }}" autocomplete="Real_estate_disposition_value" autofocus>
                            </div>


                            <label for="purchase_tax_value" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="purchaseTax" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase tax') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="purchase_tax_value" name="purchase_tax_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control" value="{{ $purchaseTsa->purchase_tax_value }}" autocomplete="Real_estate_disposition_value" autofocus>
                            </div>


                            <label for="first_batch_value" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="preValue" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'first batch') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="first_batch_value" name="first_batch_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control preval_m" value="0" autocomplete="preval" autofocus>
                            </div>


                            <label for="personal_mortgage" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="personalLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'personal') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="personal_mortgage" name="personal_mortgage" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control perlo_m" value="{{ $purchaseTsa->personalLo }}" autocomplete="personal_mortgage" autofocus>
                            </div>


                            <label for="car" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="carLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'car') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="car_mortgage" name="car_mortgage" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control car_m" value="{{ $purchaseTsa->carLo }}" autocomplete="carlo" autofocus>
                            </div>



                            <label for="visa" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="preVisa" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'visa') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="visa_mortgage" name="visa_mortgage" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control visa_m" value="{{ $purchaseTsa->visa }}" autocomplete="visa_mortgage" autofocus>
                            </div>


                            <label for="beside_percentage" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="otherLo" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'beside') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="beside_value" name="beside_value" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control other1_m" value="{{ $purchaseTsa->other }}" autocomplete="beside_value" autofocus>
                            </div>


                            <label for="visa" class="control-label mb-1 col-4 mt-2">
                                <span class="item pointer span-20 " id="record" data-id="otherFees" data-comment="حاسبة الرهن العقاري" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                    <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Other fees') }}
                            </label>
                            <div class="col-8 mt-2">
                                <input readonly id="other_fees" name="other_fees" min="0" type="number" onblur="mortgageDebtCalculate()" class="form-control" value="{{ $purchaseTsa->other_fees }}" autocomplete="other_fees" autofocus>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mortgage_debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                                    <input readonly id="mortgage_debt" name="mortgage_debt" type="number" class="form-control" value="{{ $purchaseTsa->mortgage_debt }}" autocomplete="mortgage_debt" autofocus readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="mortgage_debt_with_tax" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt with tax') }}</label>
                                    <input id="mortgage_debt_with_tax" name="mortgage_debt_with_tax" type="number" class="form-control" value="{{ $purchaseTsa->mortgage_debt_with_tax }}" autocomplete="mortgage_debt_with_tax" autofocus readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="net_to_customer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'The net to the customer') }}</label>
                                    <input readonly id="net_to_customer" name="net_to_customer" type="number" class="form-control" value="{{ $purchaseTsa->net_to_customer }}" autocomplete="net_to_customer" autofocus readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @endif
            </div>
        </div>
    </div>
</div>

<div class="userFormsInfo">
    <div class="headER topRow text-center">
        <i class="fas fa-calculator"></i>
        <h4>التمويل المرن (2*1)</h4>
    </div>

    <div class="addUser my-4 topRow ">
        <div class="userBlock d-flex align-items-center justify-content-center flex-wrap">

            <div class="addBtn">
                <button class="print text-white Cloud" type="button">
                    {{-- <a href="{{ route('calculater.calculaterHistory',['id'=>$purchaseCustomer->id])}}" class="text-white" target="_blank" style="text-decoration: none"> --}}
                    <a href="" class="text-white" target="_blank" style="text-decoration: none">
                        <i class="fas fa-history"></i>
                        سجل حاسبة التمويل</a>
                </button>
            </div>
        </div>
    </div>
    <div class="userFormsContainer mb-3">

        <!-- Loading div -->
        <div class="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
        <!-- End : Loading div -->


        <div class="userFormsDetails topRow">

            <div class="home py-3 d-block">

                <h5>نوع المنتج</h5>
            </div>

            <div class="form-group">

                <select class="form-control" id="product_type_id_caculater" name="product_type_id_caculater">

                </select>

                <span class="text-danger" id="product_type_id_caculaterError" role="alert"> </span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>تاريخ الميلاد</label>
                        <input type='text' name="birth_hijri_caculater" value="{{ old('birth_hijri_caculater') }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri_date_caculater" />

                        <span class="text-danger" id="birth_hijri_caculaterError" role="alert"> </span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>صافي الراتب</label>
                        <input id="salary_caculater" name="salary_caculater" class="form-control  numberWithComma " value="{{ old('salary_caculater') }}" autocomplete="salary">

                        <span class="text-danger" id="salary_caculaterError" role="alert"> </span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>جهة العمل</label>
                        <select id="work_caculater" onchange="this.size=1; this.blur(); checkWork_caculater(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('work') is-invalid @enderror" name="work_caculater">


                            <option value="">---</option>

                            @foreach ($worke_sources as $worke_source )
                                @if ((old('work_caculater') == $worke_source->id) )
                                    <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                                @else
                                    <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                                @endif
                            @endforeach

                        </select>
                        <span class="text-danger" id="work_caculaterError" role="alert"> </span>
                    </div>
                </div>
                <div class="col-12" id="askary_caculater" style="display:none;">
                    <div class="form-group">
                        <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                        <select id="rank_caculater" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('military_rank') is-invalid @enderror" name="military_rank_caculater">


                            <option value="" selected>---</option>
                            @foreach ($ranks as $rank)

                                <option value="{{$rank->id}}" @if (old('rank_caculater')==$rank->id ) selected="selected" @endif>{{$rank->value}}</option>

                            @endforeach

                        </select>

                        <span class="text-danger" id="military_rank_caculaterError" role="alert"> </span>

                    </div>

                </div>
                <div class="col-md-4 mb-3 ">
                    <div class="form-group ">
                        <label>الراتب الاساسي</label>
                        <input id="basic_salary_caculater" name="basic_salary_caculater" class="form-control numberWithComma " value="{{ old('basic_salary_caculater') }}" autocomplete="basic_salary">
                        <span class="text-danger" role="alert">  حسبة برنامج ممتد تتطلب هذا الحقل</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>جهة نزول الراتب</label>
                        <select id="salary_source_caculater" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('salary_bank_id') is-invalid @enderror" name="salary_bank_id_caculater">

                            <option value="" selected>---</option>

                            @foreach ($salary_sources as $salary_source )

                                <option value="{{$salary_source->id}}" @if (old('salary_bank_id_caculater')==$salary_source->id ) selected="selected" @endif>{{$salary_source->value}}</option>


                            @endforeach



                        </select>

                        <span class="text-danger" id="salary_bank_id_caculaterError" role="alert"> </span>

                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>تاريخ التعيين </label>
                        <input type="text" class="form-control extended_input" id="job_tenure_caculater" placeholder="يوم/شهر/سنة" value="{{ old('job_tenure_caculater') }}" name="job_tenure_caculater">
                        <span class="text-danger" role="alert">  حسبة برنامج ممتد تتطلب هذا الحقل</span>
                    </div>
                </div>
            </div>


            {{--
                <hr>
            <div class="row">

                <div class="col-md-4 my-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input extended" id="extended_caculater" name="extended_caculater" onchange="extendedChanged()" type="checkbox" value='1'>
                        <label class="form-check-label" for="extended">
                            ممتد</label>
                        <span class="text-danger" id="extended_caculaterError" role="alert"> </span>
                    </div>
                </div>

            </div>

            <div class="extended_section" style="display: none;">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label>تاريخ التعيين </label>
                            <input type="text" class="form-control extended_input" id="job_tenure_caculater" placeholder="يوم/شهر/سنة" value="{{ old('job_tenure_caculater') }}" name="job_tenure_caculater">
               <span class="text-danger" role="alert">  حسبة برنامج ممتد تتطلب هذا الحقل</span>
        </div>
    </div>
</div>
</div>
--}}

            <hr>
            <div class="row">
                <div class="col-md-4 my-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input toggleMine" id="residential_support_caculater" name="residential_support_caculater" type="checkbox" onchange="valueChanged()" value='1'>
                        <label class="form-check-label" for="residential_support">
                            الدعم السكني</label>
                        <span class="text-danger" id="residential_support_caculaterError" role="alert"> </span>
                    </div>
                </div>
                <div class="col-md-4 my-3 secondToggle">
                    <div class="">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input " id="add_support_installment_to_salary_caculater" name="add_support_installment_to_salary_caculater" type="checkbox" value='1'>
                            <label class="form-check-label" for="add_support_installment_to_salary">اضافة قسط الدعم الى الراتب</label>

                            <span class="text-danger" id="add_support_installment_to_salary_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 my-3 secondToggle">
                    <div class="">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input extension_support_installment" id="extension_support_installment_caculater" name="extension_support_installment_caculater" type="checkbox" value='1'>
                            <label class="form-check-label" for="extension_support_installment">تمديد الدعم 25 سنة</label>
                            <span class="text-danger" id="extension_support_installment_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 my-3 secondToggle">
                    <div class="">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input secondChecked" id="guarantees_caculater" name="guarantees_caculater" type="checkbox" value='1'>
                            <label class="form-check-label" for="guarantees">الضمانات</label>
                            <span class="text-danger" id="guarantees_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                </div>


            </div>

            {{--

                         <hr>
                        <div class="row">

                            <div class="col-md-4 my-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input toggleMine" id="without_transfer_salary_caculater" name="without_transfer_salary_caculater" type="checkbox" value='1'>
                                    <label class="form-check-label" for="without_transfer_salary">
                                        بدون تحويل الراتب</label>
                                    <span class="text-danger" id="without_transfer_salary_caculaterError" role="alert"> </span>
                                </div>
                            </div>

                        </div>
                            --}}


            <hr>

            <div class="row">

                <div class="col-md-4 my-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input toggleMine detectionChecked" id="show_detection_filds" onchange="valueChanged2()" type="checkbox">
                        <label class="form-check-label" for="show_detection_filds">
                            إظهار نسبة الاستقطاع</label>
                    </div>
                </div>

            </div>
            <br>

            <div class="detectionInput">

                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label>نسبة استقطاع صافي الراتب (شخصي)</label>
                            <input max="33" type="number" class="form-control" id="personal_salary_deduction_caculater" value="{{ old('personal_salary_deduction_caculater') }}" name="personal_salary_deduction_caculater" placeholder="نسبة استقطاع صافي الراتب (شخصي) %">
                            <span class="text-danger" id="personal_salary_deduction_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>مدة التمويل بالسنوات(شخصي)</label>
                            <input type="number" value="{{ old('personal_funding_months_caculater') }}" class="form-control" id="personal_funding_months_calculater" name="personal_funding_months_caculater" placeholder="مدة التمويل بالسنوات (شخصي)">
                            <span class="text-danger" id="personal_funding_months_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-md-6  mb-3">
                        <div class="form-group">
                            <label>نسبة الاستقطاع </label>
                            <input type="number" class="form-control" id="salary_deduction_calculater" value="{{ old('salary_deduction_caculater') }}" name="salary_deduction_caculater" placeholder="نسبة الاستقطاع %" min="40" max="50">
                            <span class="text-danger" id="salary_deduction_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>مدة التمويل بالسنوات </label>
                            <input type="number" class="form-control" id="funding_months_calculater" value="{{ old('funding_months_caculater') }}" name="funding_months_caculater" placeholder="مدة التمويل بالسنوات ">
                            <span class="text-danger" id="funding_months_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                </div>
            </div>

            <hr>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label>قيمة العقار </label>
                        <input class="form-control  numberWithComma " id="property_amount_caculater" value="{{ old('property_amount_caculater') }}" name="property_amount_caculater" placeholder="قيمة العقار ">
                        <span class="text-danger" id="property_amount_caculaterError" role="alert"> </span>
                    </div>
                </div>



                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label> حالة العقار</label>
                        <select class="form-control" name="property_completed_caculater" id="property_completed_caculater">
                            <option value="" selected>---</option>
                            <option value="1">عقار مكتمل </option>
                            <option value="0">عقار غير مكتمل </option>

                        </select>
                        <span class="text-danger" id="property_completed_caculaterError" role="alert"> </span>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label> المسكن</label>
                        <select class="form-control" id="residence_type_caculater" name="residence_type_caculater">
                            <option value="" selected>---</option>
                            <option value="1" @if (old('residence_type_caculater')==1 ) selected="selected" @endif>مسكن أول</option>
                            <option value="2" @if (old('residence_type_caculater')==2 ) selected="selected" @endif>مسكن ثاني </option>
                        </select>
                        <span class="text-danger" id="residence_type_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>

            {{--



            <div class="home py-3 d-block">

                <h5> <i class="fas fa-home mr-2"> </i>
                    المسكن</h5>
            </div>

            <div class="form-group">
                <select class="form-control" id="residence_type_caculater" name="residence_type_caculater">
                    <option value="" selected>---</option>
                    <option value="1" @if (old('residence_type_caculater')==1 ) selected="selected" @endif>مسكن أول</option>
                    <option value="2" @if (old('residence_type_caculater')==2 ) selected="selected" @endif>مسكن ثاني </option>
                </select>
                <span class="text-danger" id="residence_type_caculaterError" role="alert"> </span>
            </div>

            --}}


            <hr>
        <!--

<div class="row">

    <div class="col-md-4 my-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input sahal" id="sahal_caculater" name="sahal_caculater" onchange="sahalChanged()" type="checkbox" value='1'>
            <label class="form-check-label" for="sahal">
                سهل</label>
            <span class="text-danger" id="sahal_caculaterError" role="alert"> </span>
        </div>
    </div>

</div>


<div class="sahal_section" style="display: none;">

    <div class="home py-3 d-block">
        <h5>البدلات</h5>
    </div>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label>بدل السكن </label>
                <input type="number" class="form-control sahal_input" id="housing_allowance_caculater" value="{{ old('housing_allowance_caculater') }}" name="housing_allowance_caculater">
                <span class="text-danger" id="housing_allowance_caculaterError" role="alert"> </span>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label>بدل النقل </label>
                <input type="number" class="form-control sahal_input" id="transfer_allowance_caculater" value="{{ old('transfer_allowance_caculater') }}" name="transfer_allowance_caculater">
                <span class="text-danger" id="transfer_allowance_caculaterError" role="alert"> </span>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label>بدلات أخرى </label>
                <input type="number" class="form-control sahal_input" id="other_allowance_caculater" value="{{ old('other_allowance_caculater') }}" name="other_allowance_caculater">
                <span class="text-danger" id="other_allowance_caculaterError" role="alert"> </span>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="form-group">
                <label>دخل إضافي (التقاعد)</label>
                <input type="number" class="form-control sahal_input" id="retirement_income_caculater" value="{{ old('retirement_income_caculater') }}" name="retirement_income_caculater">
                <span class="text-danger" id="retirement_income_caculaterError" role="alert"> </span>
            </div>
        </div>

    </div>

</div>
<hr>-->


            <div class="row">
                <div class="col-md-4 my-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input  " value='1' id="inlineearlly" name="has_obligations_caculater" type="checkbox" onchange="valueEarly()">
                        <label class="form-check-label" for="early_repayment_caculater">الالتزامات</label>

                        <span class="text-danger" id="has_obligations_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3 early">
                    <div class="form-group">
                        <label>قسط بنك التسليف</label>
                        <input type="number" class="form-control early_input" value="{{ old('credit_installment_caculater') }}" name="credit_installment_caculater" id="credit_installment_calculater">
                        <span class="text-danger" id="credit_installment_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 early">
                    <div class="form-group">
                        <label>قسط الإلتزامات</label>
                        <input type="number" class="form-control early_input" value="{{ old('obligations_installment_caculater') }}" name="obligations_installment_caculater" id="obligations_installment_calculater">
                        <span class="text-danger" id="obligations_installment_caculaterError" role="alert"> </span>
                    </div>
                </div>
                <div class="col-md-6 mb-3 early">
                    <div class="form-group">
                        <label>أشهر الإلتزامات المتبقية</label>
                        <input type="number" class="form-control early_input" value="{{ old('remaining_obligations_months_caculater') }}" name="remaining_obligations_months_caculater" id="remaining_obligations_months_calculater">
                        <span class="text-danger" id="remaining_obligations_months_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3 early">
                    <div class="form-group">
                        <label>مبلغ السداد المبكر</label>
                        <input type="number" class="form-control early_input" value="{{ old('early_repayment_caculater') }}" name="early_repayment_caculater" id="early_repayment_calculater">
                        <span class="text-danger" id="early_repayment_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>


            <hr>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input  " id="first_batch_mode" value='1' type="checkbox" value="{{ old('first_batch_mode_caculater') }}" name="first_batch_mode_caculater" onchange="first_batch_mode_inputs() ">
                        <label class="form-check-label" for="first_batch_mode">
                            يوجد دفعة</label>
                        <span class="text-danger" id="first_batch_mode_caculaterError" role="alert"> </span>
                    </div>
                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input  " id="valueOn" value='1' type="checkbox" value="{{ old('provide_first_batch_caculater') }}" name="provide_first_batch_caculater" onchange="first_batch_inputs() ">
                        <label class="form-check-label" for="valueOn">
                            توفير الدفعة الاولى</label>
                        <span class="text-danger" id="provide_first_batch_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>


            <div class="valueOn">


                <div class="row ">

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label> نسبة الدفعة الاولى (%)
                            </label>
                            <input max="100" type="number" class="form-control" value="{{ old('first_batch_percentage_caculater') }}" name="first_batch_percentage_caculater" id="first_batch_percentage_caculater" placeholder="نسبة الدفعة الاولى (%) ">
                            <span class="text-danger" id="first_batch_percentage_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <div class="form-group">
                                <label>نسبة ربح الدفعة الاولى (%) </label>
                                <input max="100" type="number" class="form-control" id="first_batch_profit_caculater" value="{{ old('first_batch_profit_caculater') }}" name="first_batch_profit_caculater" placeholder="نسبة ربح الدفعة الاولى (%)">
                                <span class="text-danger" id="first_batch_profit_caculaterError" role="alert"> </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>رسوم ادارية</label>
                            <input type="number" class="form-control" name="fees_caculater" value="{{ old('fees_caculater') }}" id="fees_caculater" placeholder="رسوم ادارية">
                            <span class="text-danger" id="fees_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>خصم العميل</label>
                            <input type="number" class="form-control" name="discount_caculater" value="{{ old('discount_caculater') }}" id="discount_caculater" placeholder="خصم العميل">
                            <span class="text-danger" id="discount_caculaterError" role="alert"> </span>
                        </div>
                    </div>




                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input  " id="save" type="checkbox" name="have_joint_caculater" onchange="valueSave()" value='1'>
                        <label class="form-check-label" for="save">يوجد متضامن</label>
                        <span class="text-danger" id="have_joint_caculaterError" role="alert"> </span>
                    </div>
                </div>
            </div>
            <div class="save mt-3">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>تاريخ الميلاد</label>
                            <input type='text' name="joint_birth_hijri_caculater" value="{{ old('joint_birth_hijri_caculater') }}" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="joint_hijri_date_caculater" />
                            <span class="text-danger" id="joint_birth_hijri_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>صافي الراتب</label>
                            <input id="joint_salary_caculater" name="joint_salary_caculater" value="{{ old('joint_salary_caculater') }}" class="form-control  numberWithComma " autocomplete="salary">
                            <span class="text-danger" id="joint_salary_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>جهة العمل</label>
                            <select id="joint_work_caculater" onchange="this.size=1; this.blur(); checkWork_joint_caculater(this);" onfocus='this.size=3;' onblur='this.size=1;' class="form-control @error('joint_work') is-invalid @enderror" name="joint_work_caculater">

                                <option value="">---</option>

                                @foreach ($worke_sources as $worke_source )
                                    @if ((old('joint_work_caculater') == $worke_source->id) )
                                        <option value="{{$worke_source->id}}" selected>{{$worke_source->value}}</option>
                                    @else
                                        <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                                    @endif
                                @endforeach


                            </select>
                            <span class="text-danger" id="joint_work_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-12" id="joint_askary" style="display:none;">
                        <div class="form-group">
                            <label for="rank" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'miliarty rank') }}</label>

                            <select id="joint_rank_caculater" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); ' class="form-control @error('joint_military_rank') is-invalid @enderror" name="joint_military_rank_caculater">


                                <option value="" selected>---</option>
                                @foreach ($ranks as $rank)

                                    <option value="{{$rank->id}}" @if (old('joint_rank_caculater')==$rank->id) selected="selected" @endif>{{$rank->value}}</option>

                                @endforeach

                            </select>

                            <span class="text-danger" id="joint_military_rank_caculaterError" role="alert"> </span>

                        </div>

                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label>جهة نزول الراتب</label>
                            <select id="joint_salary_source_caculater" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('joint_salary_bank_id') is-invalid @enderror" name="joint_salary_bank_id_caculater">

                                <option value="" selected>---</option>

                                @foreach ($salary_sources as $salary_source )

                                    <option value="{{$salary_source->id}}" @if (old('joint_salary_source_caculater')==$salary_source->id) selected="selected" @endif>{{$salary_source->value}}</option>


                                @endforeach



                            </select>
                            <span class="text-danger" id="joint_salary_bank_id_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 my-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input toggleMine" id="jointToggleMine" name="joint_residential_support_caculater" type="checkbox" onchange="valueChanged5()" value='1'>
                            <label class="form-check-label" for="joint_residential_support">
                                الدعم السكني</label>
                            <span class="text-danger" id="joint_residential_support_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                    <div class="col-md-4 my-3 jointToggle">

                        <div class="">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input " id="joint_add_support_installment_to_salary_caculater" name="joint_add_support_installment_to_salary_caculater" type="checkbox" value='1'>
                                <label class="form-check-label" for="joint_add_support_installment_to_salary_caculater">اضافة قسط الدعم الى الراتب</label>
                                <span class="text-danger" id="joint_add_support_installment_to_salary_caculaterError" role="alert"> </span>
                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 my-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input  " value='1' id="jointInlineearlly" name="joint_has_obligations_caculater" type="checkbox" onchange="jointValueEarly()">
                            <label class="form-check-label" for="joint_early_repayment_caculater">الالتزامات</label>
                            <span class="text-danger" id="joint_has_obligations_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3 jointearly">
                    <div class="form-group">
                        <label>مبلغ السداد المبكر</label>
                        <input class="form-control  numberWithComma " placeholder="مبلغ السداد المبكر" value="{{ old('joint_early_repayment_caculater') }}" name="joint_early_repayment_caculater" id="joint_early_repayment_caculater">
                        <span class="text-danger" id="joint_early_repayment_caculaterError" role="alert"> </span>
                    </div>
                </div>


                <hr>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label>نسبة استقطاع صافي الراتب (شخصي)</label>
                            <input max="33" type="number" class="form-control" id="joint_personal_salary_deduction_caculater" value="{{ old('joint_personal_salary_deduction_caculater') }}" name="joint_personal_salary_deduction_caculater" placeholder="نسبة استقطاع صافي الراتب (شخصي) %">
                            <span class="text-danger" id="joint_personal_salary_deduction_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>مدة التمويل بالسنوات(شخصي)</label>
                            <input type="number" value="{{ old('joint_personal_funding_months_caculater') }}" class="form-control" id="joint_personal_funding_months_calculater" name="joint_personal_funding_months_caculater" placeholder="مدة التمويل بالسنوات (شخصي)">
                            <span class="text-danger" id="joint_personal_funding_months_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-md-6  mb-3">
                        <div class="form-group">
                            <label>نسبة الاستقطاع </label>
                            <input type="number" class="form-control" id="joint_salary_deduction_calculater" value="{{ old('joint_salary_deduction_caculater') }}" name="joint_salary_deduction_caculater" placeholder="نسبة الاستقطاع %" min="40" max="50">
                            <span class="text-danger" id="joint_salary_deduction_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>مدة التمويل بالسنوات </label>
                            <input type="number" class="form-control" id="joint_funding_months_calculater" value="{{ old('joint_funding_months_caculater') }}" name="joint_funding_months_caculater" placeholder="مدة التمويل بالسنوات ">
                            <span class="text-danger" id="joint_funding_months_caculaterError" role="alert"> </span>
                        </div>
                    </div>

                </div>


                <hr>

            <!--    <div class="home py-3 d-block">
        <h5>البدلات</h5>
    </div>
    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label>بدل السكن </label>
                <input class="form-control  numberWithComma " id="joint_housing_allowance_caculater" value="{{ old('joint_housing_allowance_caculater') }}" name="joint_housing_allowance_caculater">
                <span class="text-danger" id="joint_housing_allowance_caculaterError" role="alert"> </span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label>بدل النقل </label>
                <input class="form-control  numberWithComma " id="joint_transfer_allowance_caculater" value="{{ old('joint_transfer_allowance_caculater') }}" name="joint_transfer_allowance_caculater">
                <span class="text-danger" id="joint_transfer_allowance_caculaterError" role="alert"> </span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label>بدلات أخرى </label>
                <input class="form-control  numberWithComma " id="joint_other_allowance_caculater" value="{{ old('joint_other_allowance_caculater') }}" name="joint_other_allowance_caculater">
                <span class="text-danger" id="joint_other_allowance_caculaterError" role="alert"> </span>
            </div>
        </div>

    </div>
    <hr>-->

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>دخل إضافي (التقاعد)</label>
                            <input class="form-control  numberWithComma " id="joint_retirement_income_caculater" value="{{ old('joint_retirement_income_caculater') }}" name="joint_retirement_income_caculater">
                            <span class="text-danger" id="joint_retirement_income_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label>تاريخ التعيين </label>
                            <input type="text" class="form-control" id="joint_job_tenure_caculater" placeholder="يوم/شهر/سنة" value="{{ old('joint_job_tenure_caculater') }}" name="joint_job_tenure_caculater">
                            <span class="text-danger" id="joint_job_tenure_caculaterError" role="alert"> </span>
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            <div class="submitBtnCalc text-center">
                <button class="btn btn-primary px-5  " id="calculateSubmit">احسب</button>
            </div>

        </div>




    </div>
</div>


@include('V2.ExternalCustomer.FundingCalculater.caculaterResult')

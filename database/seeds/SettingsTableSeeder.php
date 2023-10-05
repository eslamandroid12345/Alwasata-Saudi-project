<?php

use App\Setting;
use App\realType;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*
        Setting::create(
            [
                'option_name' => 'request_validation_from_birth_date',
                'option_value' => '',
                'display_name' => 'from birth date'
            ]
        );
        Setting::create(
            [
                'option_name' => 'request_validation_to_birth_date',
                'option_value' => '',
                'display_name' => 'to birth date'
            ]
        );
        Setting::create(
            [
                'option_name' => 'request_validation_from_birth_hijri',
                'option_value' => '',
                'display_name' => 'from birth date hijri'
            ]
        );
        Setting::create(
            [
                'option_name' => 'request_validation_to_birth_hijri',
                'option_value' => '',
                'display_name' => 'to birth date hijri'
            ]
        );
        Setting::create(
            [
                'option_name' => 'request_validation_from_salary',
                'option_value' => '',
                'display_name' => 'from salary'
            ]
        );
        Setting::create(
            [
                'option_name' => 'request_validation_to_salary',
                'option_value' => '',
                'display_name' => 'to salary'
            ]
        );
        Setting::create(
            [
                'option_name' => 'request_validation_to_work',
                'option_value' => '',
                'display_name' => 'Work'
            ]
        );

        Setting::create(
            [
                'option_name' => 'request_validation_to_support',
                'option_value' => '',
                'display_name' => 'is support'
            ]
        );

        Setting::create(
            [
                'option_name' => 'request_validation_to_hasProperty',
                'option_value' => '',
                'display_name' => 'has property'
            ]
        );

        Setting::create(
            [
                'option_name' => 'request_validation_to_hasJoint',
                'option_value' => '',
                'display_name' => 'has joint'
            ]
        );

        Setting::create(
            [
                'option_name' => 'statusQualityReq_newReq',
                'option_value' => '',
                'display_name' => 'new req'
            ]
        );

        Setting::create(
            [
                'option_name' => 'statusQualityReq_openReq',
                'option_value' => '',
                'display_name' => 'open req'
            ]
        );

        Setting::create(
            [
                'option_name' => 'statusQualityReq_archiveInSalesAgent',
                'option_value' => '',
                'display_name' => 'archive in sales agent req'
            ]
        );

        Setting::create(
            [
                'option_name' => 'statusQualityReq_watingSalesManager',
                'option_value' => '',
                'display_name' => 'wating sales manager req'
            ]
        );


        Setting::create(
            [
                'option_name' => 'statusQualityReq_rejectedSalesManager',
                'option_value' => '',
                'display_name' => 'rejected sales manager req'
            ]
        );

        Setting::create(
            [
                'option_name' => 'statusQualityReq_watingFundingManager',
                'option_value' => '',
                'display_name' => 'wating funding manager req'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_rejectedFundingManager',
                'option_value' => '',
                'display_name' => 'rejected funding manager req'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_watingMortgageManager',
                'option_value' => '',
                'display_name' => 'wating mortgage manager req'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_rejectedMortgageManager',
                'option_value' => '',
                'display_name' => 'rejected mortgage manager req'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_watingGeneralManager',
                'option_value' => '',
                'display_name' => 'wating general manager req'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_rejectedGeneralManager',
                'option_value' => '',
                'display_name' => 'rejected general manager req'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_canceled',
                'option_value' => '',
                'display_name' => 'Canceled'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_completed',
                'option_value' => '',
                'display_name' => 'Completed'
            ]
        );
        Setting::create(
            [
                'option_name' => 'statusQualityReq_archiveAndRejected',
                'option_value' => '',
                'display_name' => 'Rejected and archived'
            ]
        );
        

        Setting::create(
            [
                'option_name' => 'askRequest_active',
                'option_value' => 'false',
                'display_name' => 'is condition active'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askRequest_noRequest',
                'option_value' => null,
                'display_name' => 'number of request that allowed to be moved'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askRequest_hours',
                'option_value' => null,
                'display_name' => 'total hours of the request in new status'
            ]
        );
       
        

        realType::create(
            [
                'value' => 'فيلا',
            ]
        );

        realType::create(
            [
                'value' => 'أرض',
            ]
        );

        realType::create(
            [
                'value' => 'مبنى',
            ]
        );

        realType::create(
            [
                'value' => 'شقة',
            ]
        );

        realType::create(
            [
                'value' => 'استراحة',
            ]
        );

        realType::create(
            [
                'value' => 'عمارة',
            ]
        );

        realType::create(
            [
                'value' => 'دور',
            ]
        );

        realType::create(
            [
                'value' => 'ديبلوكس',
            ]
        );

        realType::create(
            [
                'value' => 'تاون هاوس',
            ]
        );

        realType::create(
            [
                'value' => 'بناء ذاتي',
            ]
        );

         

         
        realType::create(
            [
                'value' => 'آخر',
            ]
        );
        

        Setting::create(
            [
                'option_name' => 'request_validation_to_has_obligations',
                'option_value' => '',
                'display_name' => 'has obligations'
            ]
        );

        

        Setting::create(
            [
                'option_name' => 'request_validation_to_has_financial_distress',
                'option_value' => '',
                'display_name' => 'has financial distress'
            ]
        );

       

        Setting::create(
            [
                'option_name' => 'askforconsultant_has_obligations',
                'option_value' => 'show',
                'display_name' => 'هل لديه الترامات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askforconsultant_has_financial_distress',
                'option_value' => 'show',
                'display_name' => 'هل لديه تعثرات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askforfunding_has_obligations',
                'option_value' => 'show',
                'display_name' => 'هل لديه الترامات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askforfunding_has_financial_distress',
                'option_value' => 'show',
                'display_name' => 'هل لديه تعثرات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'realEstateCalculator_has_obligations',
                'option_value' => 'show',
                'display_name' => 'هل لديه الترامات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'realEstateCalculator_has_financial_distress',
                'option_value' => 'show',
                'display_name' => 'هل لديه تعثرات'
            ]
        );

        

        Setting::create(
            [
                'option_name' => 'askRequest_eachDay',
                'option_value' => null,
                'display_name' => 'how much time that user can ask request of each day'
            ]
        );
         

        Setting::create(
            [
                'option_name' => 'qualityRequest_active',
                'option_value' => 'false',
                'display_name' => 'is quality active'
            ]
        );

        Setting::create(
            [
                'option_name' => 'qualityRequest_startDate',
                'option_value' => null,
                'display_name' => 'quality start date'
            ]
        );

        Setting::create(
            [
                'option_name' => 'qualityRequest_endDate',
                'option_value' => null,
                'display_name' => 'quality end date'
            ]
        );

        Setting::create(
            [
                'option_name' => 'qualityRequest_counterDay',
                'option_value' => 0,
                'display_name' => 'quality end date'
            ]
        );
        

        Setting::create(
            [
                'option_name' => 'request_validation_to_owningProperty',
                'option_value' => '',
                'display_name' => 'هل تمتلك عقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askforconsultant_owning_property',
                'option_value' => 'show',
                'display_name' => 'هل تمتلك عقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askforfunding_owning_property',
                'option_value' => 'show',
                'display_name' => 'هل تمتلك عقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'realEstateCalculator_owning_property',
                'option_value' => 'show',
                'display_name' => 'هل تمتلك عقار' 
            ]
        );
        

        Setting::create(
            [
                'option_name' => 'askforconsultant_email',
                'option_value' => 'show',
                'display_name' => 'البريد الإلكتروني'
            ]
        );

        Setting::create(
            [
                'option_name' => 'askforfunding_email',
                'option_value' => 'show',
                'display_name' => 'البريد الإلكتروني'
            ]
        );

        Setting::create(
            [
                'option_name' => 'realEstateCalculator_email',
                'option_value' => 'show',
                'display_name' => 'البريد الإلكتروني'
            ]
        );

        ///CUSTOMER

        Setting::create(
            [
                'option_name' => 'customerReq_customerName',
                'option_value' => 'show',
                'display_name' => 'اسم العميل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerSex',
                'option_value' => 'show',
                'display_name' => 'النوع'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerMobile',
                'option_value' => 'show',
                'display_name' => 'جوال العميل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerDOB',
                'option_value' => 'show',
                'display_name' => 'تاريخ الميلاد'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerWork',
                'option_value' => 'show',
                'display_name' => 'جهة العمل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerRegion',
                'option_value' => 'show',
                'display_name' => 'منطقة العميل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerWorkMadanySource',
                'option_value' => 'show',
                'display_name' => 'الجهة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerWorkMadany',
                'option_value' => 'show',
                'display_name' => 'المنصب الوظيفي'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerWorkAskarySource',
                'option_value' => 'show',
                'display_name' => 'الجهة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerWorkAskaryRank',
                'option_value' => 'show',
                'display_name' => 'الرتبة العسكرية'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerSalary',
                'option_value' => 'show',
                'display_name' => 'راتب العميل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerSalarySource',
                'option_value' => 'show',
                'display_name' => 'جهة نزول الراتب'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerSupport',
                'option_value' => 'show',
                'display_name' => 'هل هو مدعوم'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerObligations',
                'option_value' => 'show',
                'display_name' => 'هل لديه التزامات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerFinancialDistress',
                'option_value' => 'show',
                'display_name' => 'هل لديه تعثرات'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_customerFinancialDistressCost',
                'option_value' => 'show',
                'display_name' => 'قيمة التعثرات'
            ]
        );

        ///JOINT

        Setting::create(
            [
                'option_name' => 'customerReq_jointName',
                'option_value' => 'show',
                'display_name' => 'إسم المتضامن'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_jointMobile',
                'option_value' => 'show',
                'display_name' => 'جوال المتضامن'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_jointSalary',
                'option_value' => 'show',
                'display_name' => 'راتب المتضامن'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_jointDOB',
                'option_value' => 'show',
                'display_name' => 'تاريخ الميلاد المتضامن'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_jointWork',
                'option_value' => 'show',
                'display_name' => 'جهة عمل المتضامن'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_jointSalarySource',
                'option_value' => 'show',
                'display_name' => 'جهة نزول الراتب للمتضامن'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_jointFundingSource',
                'option_value' => 'show',
                'display_name' => 'جهة التمويل للمتضامن'
            ]
        );

        ///REAL ESTATE

        Setting::create(
            [
                'option_name' => 'customerReq_realName',
                'option_value' => 'show',
                'display_name' => 'اسم المالك'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realMobile',
                'option_value' => 'show',
                'display_name' => 'جوال المالك'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realCity',
                'option_value' => 'show',
                'display_name' => 'مدينة العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realDistrict',
                'option_value' => 'show',
                'display_name' => 'حي العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realFundingProfit',
                'option_value' => 'show',
                'display_name' => 'سعي التمويل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realStatus',
                'option_value' => 'show',
                'display_name' => 'حالة العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realAge',
                'option_value' => 'show',
                'display_name' => 'عمر العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realFundingMortgage',
                'option_value' => 'show',
                'display_name' => 'رهن التمويل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realType',
                'option_value' => 'show',
                'display_name' => 'نوع العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realCost',
                'option_value' => 'show',
                'display_name' => 'قيمة العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realCustomerHasReal',
                'option_value' => 'show',
                'display_name' => 'هل يمتلك العميل عقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realCustomerFoundReal',
                'option_value' => 'show',
                'display_name' => 'هل وجد العميل عقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realAssment',
                'option_value' => 'show',
                'display_name' => 'هل تم تقييم العقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realTenants',
                'option_value' => 'show',
                'display_name' => 'هل يوجد مستأجرين'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_realMortgaged',
                'option_value' => 'show',
                'display_name' => 'هل العقار مرهون'
            ]
        );

        //FUNDING

        Setting::create(
            [
                'option_name' => 'customerReq_fundingSource',
                'option_value' => 'show',
                'display_name' => 'جهة التمويل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingDuration',
                'option_value' => 'show',
                'display_name' => 'مدة التمويل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingPersonalCost',
                'option_value' => 'show',
                'display_name' => 'مبلغ التمويل الشخصي'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingPersonalPresentage',
                'option_value' => 'show',
                'display_name' => 'نسبة التمويل الشخصي'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingRealCost',
                'option_value' => 'show',
                'display_name' => 'مبلغ التمويل العقاري'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingRealPresentage',
                'option_value' => 'show',
                'display_name' => 'نسبة التمويل العقاري'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingDeductionRate',
                'option_value' => 'show',
                'display_name' => 'نسبة الإستقطاع'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_fundingMonthlyInstallment',
                'option_value' => 'show',
                'display_name' => 'القسط الشهري'
            ]
        );


        //Prepayment

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentRealCost',
                'option_value' => 'show',
                'display_name' => 'القيمة الفعلية للعقار'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentRealIncreaseCost',
                'option_value' => 'show',
                'display_name' => 'قيمة الزيادة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentCost',
                'option_value' => 'show',
                'display_name' => 'قيمة الدفعة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentPresentage',
                'option_value' => 'show',
                'display_name' => 'نسبة الدفعة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentCostAfterPresentage',
                'option_value' => 'show',
                'display_name' => 'مبلغ الدفعة بعد النسبة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentCustomerNet',
                'option_value' => 'show',
                'display_name' => 'الصافي للعميل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentCustomerDeficit',
                'option_value' => 'show',
                'display_name' => 'عجز العميل'
            ]
        );

        //TSAHEEL

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentVisa',
                'option_value' => 'show',
                'display_name' => 'بطاقة الفيزا'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentCar',
                'option_value' => 'show',
                'display_name' => 'قرض سيارة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentPersonal',
                'option_value' => 'show',
                'display_name' => 'قرض شخصي'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentReal',
                'option_value' => 'show',
                'display_name' => 'قرض عقاري'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentBank',
                'option_value' => 'show',
                'display_name' => 'بنك التسليف'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentOther',
                'option_value' => 'show',
                'display_name' => 'بنك التسليف'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentTotalDebt',
                'option_value' => 'show',
                'display_name' => 'إجمالية المديونية'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentMortgagePresantage',
                'option_value' => 'show',
                'display_name' => 'نسبة الرهن لتساهيل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentMortgageCost',
                'option_value' => 'show',
                'display_name' => 'مبلغ الرهن لتساهيل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentProfitPresantage',
                'option_value' => 'show',
                'display_name' => 'نسبة السعي لتساهيل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentProfitCost',
                'option_value' => 'show',
                'display_name' => 'مبلغ السعي لتساهيل'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentValueAdded',
                'option_value' => 'show',
                'display_name' => 'القيمة المضافة'
            ]
        );

        Setting::create(
            [
                'option_name' => 'customerReq_prepaymentAdminFees',
                'option_value' => 'show',
                'display_name' => 'الرسوم الإدارية'
            ]
        );

        //ATTACHMENT

        Setting::create(
            [
                'option_name' => 'customerReq_attachments',
                'option_value' => 'show',
                'display_name' => 'مرفقات الطلب'
            ]
        );


        Setting::create(
            [
                'option_name' => 'customerReq_customerObligationsCost',
                'option_value' => 'show',
                'display_name' => 'قيمةالتزامات'
            ]
        );
       

        Setting::create(
            [
                'option_name' => 'time_requestWithoutUpdate',
                'option_value' => 'null',
                'display_name' => 'الوقت المتاح لترك الطلب بدون تحديث'
            ]
        );
         */
    }
    
}

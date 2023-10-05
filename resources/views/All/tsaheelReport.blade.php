<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: separate;
            width: 100%;
        }

        td {
            border: 1px solid #808080;
            text-align: right;
            padding: 4px;
            font-size: 11px;
        }

        th {
            background-color: #d9d9d9;
            border: 1px solid #808080;
            font-size: 10px;
            padding: 4px;
        }

        caption {
            display: table-caption;
            text-align: center;
            background-color: #a6a6a6;
            padding: 3px;
            font-weight: bold;
            font-size: 10px;
        }

        hr.new4 {
            border: 0.5px solid red;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans;
        }

        input[type=checkbox] {
            display: inline;
        }

        .checkboxes label {
            margin: 55px 20px 0px 3px;
        }

        .checkboxes {
            text-align: center;
        }

        .checkboxes input {
            margin: 55px 20px 5px 10px;
        }
    </style>
</head>

<body>



@if (false)

<htmlpageheader name="page-header">

    <!--  <img src="https://www.google.pl/images/srpr/logo11w.png"/> -->
    <!--  <img src="{{ url('interface_style/images/tsaheel-header.jpg') }}" alt="Tsaheel Header" /> -->
    <img src="{{ public_path('interface_style/images/tsaheel-header.jpg') }}" alt="Tsaheel Header">

</htmlpageheader>
@endif

    <br>

    <h3 style="text-align:center; font-family:'Droid Arabic Naskh';">عرض سعر مبدئي</h3>
    <p style="text-align:right; font-size: 10px;"> {{$todaydate}} : تاريخ اليوم</p>

    <table dir="rtl">


        <!-- Customer Info-->
        <caption>البيانات الشخصية</caption>

        <tr>
            <th width="20%">اسم العميل</th>
            <td width="30%">{{$purchaseCustomer->name}}</td>

            <th width="20%">جوال العميل</th>
            <td width="30%">{{$purchaseCustomer->mobile}}</td>



        </tr>

        <tr>
            <th width="20%">عمر العميل</th>
            <td>{{$purchaseCustomer->age}}</td>

            <th width="20%">وظيفة العميل</th>
            <td>{{$purchaseCustomer->value}}</td>

        </tr>



    </table>


    <table dir="rtl">
        <tr>
            <th width="18%">راتب العميل</th>
            <td>{{$purchaseCustomer->salary != null ? $purchaseCustomer->salary.' ريال' : ''}}</td>

            <th width="18%">بنك التمويل </th>
            <td>{{$purchaseFun->value}}</td>

            <th width="18%">البنك الحالي </th>
            <td>{{$salaryBank != null ? $salaryBank->value : ''}}</td>

        </tr>
    </table>

    <!-- End Customer Info-->


    <table dir="rtl">

        <!--  Joint Info-->

        <caption>بيانات المتضامن</caption>

        <tr>
            <th width="20%">اسم المتضامن</th>
            <td>{{$purchaseJoint->name}}</td>

            <th width="20%">جوال المتضامن</th>
            <td>{{$purchaseJoint->mobile}}</td>

        </tr>

        <tr>
            <th width="20%">عمر المتضامن</th>
            <td style="">{{$purchaseJoint->age}}</td>

            <th width="20%">وظيفة المتضامن</th>
            <td>{{$purchaseJoint->work}}</td>
        </tr>

        <tr>
            <th width="20%">راتب المتضامن</th>
            <td>{{$purchaseJoint->salary != null ? $purchaseJoint->salary .' ريال' : ''}}</td>

            <th width="20%">البنك الحالي</th>
            <td>{{$purchaseJoint->value}}</td>
        </tr>

        <!-- End Joint Info-->

    </table>

    <table dir="rtl">

        <!--  Real Estat Info-->

        <caption>بيانات العقار</caption>

        <tr>
            <th width="15%">موقع العقار</th>
            <td width="10%">{{$city != null ? $city->value : ''}}</td>

            <th width="15%">قيمة العقار</th>
            <td width="10%">{{$purchaseReal->cost != null ? $purchaseReal->cost .' ريال' : ''}}</td>

            <th width="15%">العرض المرفوع للبنك </th>
            <td width="10%">{{$payment != null ? $payment->realCost : ''}}</td>

        </tr>

        <tr>
            <th width="15%">عمر العقار</th>
            <td width="10%">{{$purchaseReal != null ? $purchaseReal->age : ''}}</td>

            <th width="15%">حالة العقار</th>
            <td width="10%">{{$purchaseReal != null ? $purchaseReal->status : ''}}</td>

            <th width="15%">نوع العقار</th>
            <td width="10%">{{$realType!= null ? $realType->value : ''}}</td>

        </tr>

        <tr>


            <th width="15%">هل تم التقييم؟</th>
            <td width="10%">{{$purchaseReal != null ? $purchaseReal->evaluated : ''}}</td>

            <th width="15%">هل يوجد مستأجرون؟</th>
            <td width="10%">{{$purchaseReal != null ? $purchaseReal->tenant : ''}}</td>

            <th width="15%">هل العقار مرهون؟</th>
            <td width="10%">{{$purchaseReal != null ? $purchaseReal->mortgage : ''}}</td>
        </tr>

        <!-- End  Real Estat Info-->

    </table>



    <table dir="rtl">

        <!--  Tsaheel Info-->

        <caption>(الوضع الإئتماني (الإلتزامات</caption>

        <tr>
            <th>بطاقة فيزا</th>
            <td>{{$payment != null ? $payment->visa : ''}}</td>

            <th>قرض السيارة</th>
            <td>{{$payment != null ? $payment->carLo : ''}}</td>

            <th>قرض شخصي</th>
            <td>{{$payment != null ? $payment->personalLo : ''}}</td>

        </tr>

        {{-- <tr>
            <th>قرض عقاري</th>
            <td>{{$payment != null ? $payment->realLo : ''}}</td>

            <th>بطاقة ائتمانية</th>
            <td>{{$payment != null ? $payment->credit : ''}}</td>

            <th>أخرى</th>
            <td>{{$payment != null ? $payment->other : ''}}</td>

        </tr> --}}

        <!-- End Tsaheel Info-->

    </table>

    <table dir="rtl">
        <tr>
            <th>قرض عقاري</th>
            <td>{{$payment != null ? $payment->realLo : ''}}</td>

            {{-- <th>بطاقة ائتمانية</th>
            <td>{{$payment != null ? $payment->credit : ''}}</td> --}}

            <th>أخرى</th>
            <td>{{$payment != null ? $payment->other : ''}}</td>

        </tr>
    </table>


    <table dir="rtl">

        <!--  Complete Tsaheel Info-->

        <tr>
            <th width="50%">إجمالي المديونية</th>
            <td>{{ $payment->debt == null ?  0 : $payment->debt }}</td>
        </tr>



        <!-- End Complete Tsaheel Info-->

    </table>


    <table dir="rtl">

        <!--  Tsaheel Info-->



        <tr>
            <th>نسبة رسوم الرهن</th>
            <td>{{$payment->mortPre != null ? $payment->mortPre .'%' : ''}}</td>

            <th>مبلغ رسوم الرهن</th>
            <td>{{ $payment != null ? $payment->mortCost :''}}</td>
        </tr>

        <tr>
            <th>نسبة رسوم السعي</th>
            <td>{{$payment->proftPre != null ? $payment->proftPre .'%' : ''}}</td>

            <th>مبلغ رسوم السعي</th>
            <td>{{$payment != null ? $payment->profCost : ''}}</td>
        </tr>
        <tr>
            <th>نسبة الدفعة</th>
            <td>{{$payment->proftPre != null ? $payment->prepaymentPre .'%' : ''}}</td>

            <th>مبلغ الدفعة</th>
            <td>{{$payment != null ? $payment->prepaymentVal : ''}}</td>
        </tr>

        <tr>
            <th>القيمة المضافة</th>
            <td>{{$payment != null ? $payment->addedVal : ''}}</td>

            <th>رسوم إدارية</th>
            <td>{{$payment != null ? $payment->adminFee : ''}}</td>
        </tr>


        <!-- End Tsaheel Info-->

    </table>

    <table dir="rtl">

        <!--  Complete Tsaheel Info-->

        <tr>
            <th width="50%">صافي المبلغ</th>
            <td>{{$payment != null ? $payment->netCustomer : ''}}</td>
        </tr>



        <!-- End Complete Tsaheel Info-->

    </table>



    <table dir="rtl">

        <!--  Funding Info-->

        <caption>الموافقة من جهة التمويل</caption>

        <tr style="font-size:small;">
            <th width="3%">م</th>
            <th width="7%">عقاري</th>
            <th>المبلغ</th>
            <th width="3%">م</th>
            <th width="7%">شخصي</th>
            <th>المبلغ</th>
            <th width="3%">م</th>
            <th width="30%">ماهي الحلول الأخرى</th>

        </tr>

        <tr>
            <th>1</th>
            <th>القسط</th>
            <td width="15%">{{$purchaseFun != null ?  $purchaseFun->monthly_in : ''}}</td>

            <th>1</th>
            <th>القسط</th>
            <td width="15%"></td>

            <th>1</th>
            <td width="15%"></td>

        </tr>

        <tr>
            <th>2</th>
            <th>المدة بالسنوات</th>
            <td width="15%">{{$purchaseFun != null ? $purchaseFun->funding_duration : ''}}</td>

            <th>2</th>
            <th>المدة بالسنوات</th>
            <td width="15%"></td>

            <th>2</th>
            <td width="15%"></td>

        </tr>

        <tr>
            <th>3</th>
            <th>الدفعة</th>
            <td width="15%">{{$payment != null ? $payment->prepaymentVal : ''}}</td>

            <th>3</th>
            <th>النسبة</th>
            <td width="15%">{{ $purchaseFun != null ? $purchaseFun->personalFun_pre : ''}}</td>

            <th>3</th>
            <td width="15%"></td>

        </tr>

        <tr>
            <th>4</th>
            <th>النسبة</th>
            <td width="15%">{{ $purchaseFun != null ? $purchaseFun->realFun_pre : ''}}</td>

            <th>4</th>
            <th>البنك</th>
            <td width="15%">{{ $fundingSource != null ? $fundingSource->value : ''}}</td>


            <th>4</th>
            <td width="15%"></td>

        </tr>

        <tr>
            <th>5</th>
            <th>التمويل</th>
            <td width="15%">{{ $purchaseFun != null ? $purchaseFun->realFun_cost : ''}}</td>

            <th>5</th>
            <th>التمويل</th>
            <td width="15%">{{ $purchaseFun != null ? $purchaseFun->personalFun_cost : ''}}</td>

            <th>5</th>
            <td width="15%"></td>

        </tr>


        <!-- End Funding Info-->

    </table>

    <br>

    <table dir="rtl">

        <!--  Wsata Info-->

        <tr>
            {{-- <th width="12%">الشركة المسوقة</th>
            <td width="12%">شركة الوساطة</td>

            <th width="12%"> مسؤول المبيعات</th>
            <td width="14%">{{$salesagent != null ? $salesagent->name : ''}}</td> --}}

            <th width="12%">توقيع مسؤول المبيعات</th>
            <td></td>

        </tr>



        <!-- End Wsata Info-->

    </table>


    <table dir="rtl">

        <!--  Wsata Info-->

        <tr>

            <th width="12%"> أقر أنا / </th>
            <td width="25%"></td>

            <td width="13%">أن البيانات والبنود المذكورة صحيحة وموافق عليها</td>

            <th width="12%"> التوقيع</th>
            <td></td>

        </tr>



        <!-- End Wsata Info-->

    </table>


    <table dir="rtl">


        @if ($requestInfo->is_approved_by_salesManager == 1)
        <tr>
            <th width="12%">اعتمد من مدير المبيعات</th>
            <td width="20%">
                {{$salesmaanger != null ? $salesmaanger->name : ''}}
            </td>

            <th width="8%">بتاريخ</th>
            <td width="20%">
                {{$requestInfo->approved_date_salesManager}}
            </td>

            <th width="12%">توقيع مدير المبيعات</th>
            <td></td>


        </tr>

        @endif


        @if ($requestInfo->is_approved_by_mortgageManager == 1)
        <tr>
            <th width="12%">اعتمد من مدير الرهن</th>
            <td width="20%">
                {{$mortgagemaanger->name}}
            </td>

            <th width="8%">بتاريخ</th>
            <td width="20%">
                {{$requestInfo->approved_date_mortgageManager}}
            </td>

            <th width="12%">توقيع مدير الرهن</th>
            <td></td>


        </tr>
        @endif


        @if ($requestInfo->is_approved_by_generalManager == 1)
        <tr>
            <th width="12%">اعتمد من المدير العام</th>
            <td width="20%">
                نايف المطيري
            </td>

            <th width="8%">بتاريخ</th>
            <td width="20%">
                {{$requestInfo->approved_date_generalManager}}
            </td>

            <th width="12%">توقيع المدير العام</th>
            <td></td>


        </tr>
        @endif

    </table>


    <!--
    <table dir="rtl">



        <caption>خاص بموافقة شركة تساهيل العقار</caption>


        <tr>
            <td class="checkboxes">
                <input type="checkbox" />&nbsp; <label>موافق</label>

                <input type="checkbox" />&nbsp; <label>غير موافق</label>

                <input type="checkbox" />&nbsp; <label>موافق بشرط:</label>
            </td>

        </tr>




    </table>




    <table dir="rtl">


        <tr>
            <th width="20%">الإسم</th>
            <td>مدير التسويق والتحصيل</td>

            <th width="20%">التوقيع</th>
            <td></td>
        </tr>


    </table>

        -->




    <htmlpagefooter name="page-footer">
        <img src="{{ public_path('interface_style/images/tsaheel-footer.jpg') }}" alt="Tsaheel Footer">

    </htmlpagefooter>

</body>

</html>

<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
            font-size: 12px;
            padding: 5px;
        }

        caption {
            display: table-caption;
            text-align: center;
            background-color: #a6a6a6;
            padding: 6px;
            font-weight: bold;
            font-size: 16px;
        }

        hr.new4 {
            border: 1px solid red;
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

<htmlpageheader name="page-header">

    <!--  <img src="https://www.google.pl/images/srpr/logo11w.png"/> -->
<!--  <img src="{{ url('interface_style/images/tsaheel-header.jpg') }}" alt="Tsaheel Header" /> -->
    <img src="{{ public_path('interface_style/images/aqar-header.jpg') }}" alt="Tsaheel Header">

</htmlpageheader>

<br><br>

<h3 style="text-align:center; font-family:'Droid Arabic Naskh';">استمارة إفراغ عقار</h3>
<p style="text-align:right; font-size: 15px;"> {{$todaydate}} : تاريخ اليوم</p>

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
        <td>{{$purchaseCustomer->work}}</td>

    </tr>

</table>

<table dir="rtl">
    <tr>
        <th width="20%">راتب العميل</th>
        <td>{{$purchaseCustomer->salary != null ? $purchaseCustomer->salary.' ريال' : ''}}</td>

        <th width="20%">الراتب على بنك</th>
        <td>{{$salaryBank ? $salaryBank->value : ''}}</td>

    </tr>
</table>

<!-- End Customer Info-->

<table dir="rtl">

    <!--  Real Estat Info-->

    <caption>بيانات العقار</caption>

    <tr>
        <th width="12%">موقع العقار</th>
        <td>{{$city ? $city->value : ''}}</td>

        <th width="12%">الحي</th>
        <td>{{$purchaseReal->region}}</td>

        <th width="12%">قيمة العقار</th>
        <td>{{$purchaseReal->cost != null ? $purchaseReal->cost .' ريال' : ''}}</td>

    </tr>

    <tr>
        <th width="12%">اسم المالك</th>
        <td>{{$purchaseReal->name}}</td>

        <th width="12%">جوال المالك</th>
        <td>{{$purchaseReal->mobile}}</td>

        <th width="12%">مبلغ المتعاون</th>
        <td></td>

    </tr>

    <tr>

        <th width="12%">السعي</th>
        <td>{{$purchaseReal->pursuit}}</td>

        <th width="12%">الرهن</th>
        <td>{{$purchaseReal->mortgage_value}}</td>

        <th width="12%">القيمة المضافة</th>
        <td></td>
    </tr>

    <!-- End  Real Estat Info-->

</table>

<table dir="rtl">

    <!--  Tsaheel Info-->

    <caption>طريقة الدفع</caption>

    <tr>
        <th width="20%">الرقم</th>
        <td></td>

        <th width="20%">التاريخ</th>
        <td></td>

    </tr>

    <tr>
        <th>تاريخ دخول المعاملة</th>
        <td>{{ $requestInfo->recived_date_report}}</td>

        <th>تاريخ إفراغ المعاملة</th>
        <td></td>

    </tr>

    <tr>
        <th>عدد الأيام في التمويل</th>
        <td>{{ $requestInfo->counter_report}}</td>
        <th>مصدر المعاملة</th>
        <td>{{ $requestInfo ? ($requestInfo->value??'') : ''}}</td>
    </tr>
    <!-- End Tsaheel Info-->
</table>

<table dir="rtl">

    <caption>بيانات المبيعات</caption>

    <tr>
        <th>مشرف المبيعات</th>
        <td>{{$salesmaanger != null ? $salesmaanger->name : ''}}</td>

        <th>مسؤول المبيعات</th>
        <td>{{$salesagent->name}}</td>

    </tr>

    <tr>

        <th>اسم المتعاون</th>
        <td>{{$colloberator != null ? $colloberator->name : ''}}</td>

        <th>جوال المتعاون</th>
        <td></td>

    </tr>

    <tr>

        <th>اسم صاحب الحساب</th>
        <td></td>

        <th>رقم حساب المتعاون</th>
        <td></td>

    </tr>

    <tr>
        <th>رقم المعاملة</th>
        <td>{{$requestInfo->reqNoBank}}</td>

        <th>جهة التمويل</th>
        <td>{{$fundingSource ? $fundingSource->value : ''}}</td>
    </tr>

    <!-- End Tsaheel Info-->

</table>

<hr class="new4">

<!--
<table dir="rtl">



    <tr>
        <th width="30%">توقيع مشرف التمويل</th>
        <td></td>

    </tr>

    <tr>

        <th width="30%">توقيع المحاسب</th>
        <td></td>

    </tr>

    <tr>

        <th width="30%">توقيع المدير العام</th>
        <td></td>

    </tr>



</table>
-->

<table dir="rtl">

    @if ($requestInfo->is_aqar_approved_by_salesManager == 1)
        <tr>
            <th width="12%">اعتمد من مدير المبيعات</th>
            <td width="20%">
                {{$salesmaanger != null ? $salesmaanger->name : ''}}
            </td>

            <th width="8%">بتاريخ</th>
            <td width="20%">
                {{$requestInfo->approved_aqar_date_salesManager}}
            </td>

            <th width="12%">توقيع مدير المبيعات</th>
            <td></td>

        </tr>

    @endif


    @if ($requestInfo->is_approved_by_fundingManager == 1)
        <tr>
            <th width="12%">اعتمد من مشرف التمويل</th>
            <td width="20%">
                {{$fundingmaanger->name}}
            </td>

            <th width="8%">بتاريخ</th>
            <td width="20%">
                {{$requestInfo->approved_date_fundingManager}}
            </td>

            <th width="12%">توقيع مشرف التمويل</th>
            <td></td>

        </tr>
    @endif


    @if ($requestInfo->is_aqar_approved_by_generalManager == 1)
        <tr>
            <th width="12%">اعتمد من المدير العام</th>
            <td width="20%">
                نايف المطيري
            </td>

            <th width="8%">بتاريخ</th>
            <td width="20%">
                {{$requestInfo->approved_aqar_date_generalManager}}
            </td>

            <th width="12%">توقيع المدير العام</th>
            <td></td>

        </tr>
    @endif

</table>

<br>

<table dir="rtl">

    <tr>
        <td>
            1- تعبأ بواسطة مسؤول المبيعات
        </td>

        <td>
            2- صورة بملف المعاملة
        </td>

        <td>
            3- الأصل للحسابات
        </td>

    </tr>

</table>

<htmlpagefooter name="page-footer">
    <img src="{{ public_path('interface_style/images/tsaheel-footer.jpg') }}" alt="Tsaheel Footer">

</htmlpagefooter>

</body>

</html>

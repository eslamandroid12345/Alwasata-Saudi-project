<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>{{$user->name}} | {{date("Y-m-d",strtotime(now()))}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>



<body>
<style>
    body ,h4,h6{
        font-family: 'examplefont', sans-serif;
    }
    #customers *{

        direction: rtl;
        text-align: right;
    }
    #customers {
        font-family: 'examplefont', sans-serif;
        border-collapse: collapse;
        border: 2px solid #ddd;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        width: 100%;
    }

    #customers td, #customers th {
        border: 2px solid #eee;
        padding: 10px;
    }

    #customers .head {
        padding-top: 12px;
        padding-bottom: 12px;
        font-weight: bold;
        text-align: center;
        border: 2px solid #eee;
        background-color: #ddd;
        color: #333;
    }
    #customers .header {
        padding-top: 12px;
        padding-bottom: 12px;
        font-weight: bold;
        text-align: center;
        color: #222;
    }
</style>
<div class="container">

    <div class="grid-container text-center" style="text-align: center">
        <img src="{{asset('/images/icons/mipmap-xxxhdpi.png')}}" alt="" style="margin-top: -50px;width: 50px;height: 50px;">
    </div>
    <div style="text-align: center">
        الملف الشخصى للموظف
        <br>
        <b class="text-red" style="font-weight: bold"> {{optional($user->profile)->name}} </b>
    </div>
    <hr>
    <h6 style="font-weight: bold;text-align: center"> المعلومات الشخصية</h6>
    <table id="customers" class=" w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="35%">إسم الموظف كاملا</td>
            <td>{{optional($user->profile)->name ?? '-'}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">الوظيفة </td>
            <td>{{ optional($user->profile)->job ??'-' }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">البريد الإلكترونى </td>
            <td>{{ optional($user->profile)->email ?? '-' }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">رقم الجوال</td>
            <td>{{ optional($user->profile)->mobile ?? '-' }}
                @foreach($user->phones as $phone)
                    , {{$phone->mobile}}
                @endforeach
            </td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">الجنسية</td>
            <td>{{@optional($user->profile)->nationality->value ?? '-'}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">النوع</td>
            <td>{{optional($user->profile)->gender == "male" ?'ذكر' :"أنثى"}}</td>
        </tr>


        <tr class="form-group">
            <td class="head" width="35%">الحالة الإجتماعية  </td>
            <td>{{ optional($user->profile)->marital }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> عدد أفراد الأسرة</td>
            <td>{{ optional($user->profile)->family_count }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">تاريخ الميلاد </td>
            <td>{{ optional($user->profile)->birth_date ?? '-' }} {{$user->profile->birth_date ? "(".($user->profile->birth_date_m).")" :''}}</td>
        </tr>
    </table>
    <h6 style="font-weight: bold;text-align: center"> معلومات التواصل</h6>
    <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">

        <tr class="form-group">
            <td class="head" width="35%">العنوان المختصر</td>
            <td>{{@optional($user->profile)->title ?? '-'}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">المنطقة / المدينه / الحى </td>
            <td>{{@optional($user->profile)->area->value ?? 'لا يوجد'}}
                / {{@optional($user->profile)->city->value ?? 'لا يوجد'}}
                / {{@optional($user->profile)->district->value ?? 'لا يوجد'}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">الشارع / رقم المبنى / رقم الوحدة </td>
            <td>{{optional($user->profile)->street_name ?? 'لا يوجد'}} /
                {{optional($user->profile)->building_number ?? 'لا يوجد'}} /
                {{optional($user->profile)->unit_number ?? 'لا يوجد'}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">إسم شخص قريب</td>
            <td>{{@optional($user->profile)->contact_person_name ?? 'لا يوجد'}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">صلة القرابة</td>
            <td>{{@optional($user->profile)->contact_person_relation ?? 'لا يوجد'}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">رقم الشخص القريب</td>
            <td>{{@optional($user->profile)->contact_person_number ??'لا يوجد'}}</td>
        </tr>
    </table>
    <h6 style="font-weight: bold;text-align: center">  المؤهلات والتخصص</h6>
    <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">

        <tr class="form-group">
            <td class="head" width="35%"> المؤهلات </td>
            <td>{{ optional($user->profile)->qualification }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> التخصص </td>
            <td>{{ optional($user->profile)->specialization }}</td>
        </tr>
    </table>
    <h6 style="font-weight: bold;text-align: center"> معلومات الوظيفة </h6>
    <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="35%">الرقم الوظيفي </td>
            <td>{{ optional($user->profile)->job_number }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> الشركه</td>
            <td>{{ @optional($user->profile)->company->value}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> القسم </td>
            <td>{{ @optional($user->profile)->section->value}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> القسم الفرعى</td>
            <td>{{ @optional($user->profile)->subsection->value}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> طبيعه العمل </td>
            <td>{{ @optional($user->profile)->control_work_id}}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%"> التأمينات </td>
            <td>{{ @optional($user->profile)->insurances->value}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%"> التأمين الطبي </td>
            <td>{{ @optional($user->profile)->medical->value}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">تاريخ العقد   </td>
            <td>{{ optional($user->profile)->work_date ??'-' }} {{optional($user->profile)->work_date ? "(".($user->profile->work_date_m).")" :''}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">تاريخ العقد 2  </td>
            <td>{{ optional($user->profile)->work_date_2 ??'-' }} {{optional($user->profile)->work_date_2 ? "(".($user->profile->work_date_2_m).")" : ''}}</td>
        </tr>


        <tr class="form-group">
            <td class="head" width="35%">تاريخ نهاية العقد  </td>
            <td>{{ optional($user->profile)->work_end_date ?? '-'}} {{optional($user->profile)->work_end_date  ? "(".($user->profile->work_end_date_m).")" : ''}}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">تاريخ مباشرة العمل </td>
            <td>{{ optional($user->profile)->direct_date ?? '-' }} {{optional($user->profile)->direct_date ?"(".($user->profile->direct_date_m).")" : ''}}</td>
        </tr>
    </table>
    @if(!is_numeric(@optional($user->profile)->nationality->parent_id))
        <h6 style="font-weight: bold;text-align: center">  الكفالة </h6>
        <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">
            <tr class="form-group">
                <td class="head" width="35%">الكفالة</td>
                <td>{{ @optional($user->profile)->guaranty->value ??'-' }}</td>
            </tr>
            <tr class="form-group">
                <td class="head" width="35%"> الشركة الكافلة </td>
                <td>{{ @optional($user->profile)->guaranty_company->value ??'-' }}</td>
            </tr>
            <tr class="form-group">
                <td class="head" width="35%"> إسم الكفيل   </td>
                <td>{{ optional($user->profile)->guaranty_name ?? '-' }}</td>
            </tr>

        </table>
    @endif

    <h6 style="font-weight: bold;text-align: center">  الهوية </h6>
    <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="35%"> نوع الهوية  </td>
            <td>{{ @optional($user->profile)->identity->value ??'-' }}</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%"> رقم الهوية  </td>
            <td>{{ optional($user->profile)->residence_number ?? '-' }}</td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">تاريخ إنتهاء الهوية </td>
            <td>{{ optional($user->profile)->residence_end_date ?? '-' }} {{optional($user->profile)->residence_end_date ? "(".($user->profile->residence_end_date_m).")" : ''}}</td>
        </tr>
    </table>
    <pagebreak></pagebreak>
    <h6 style="font-weight: bold;text-align: center">  ملاحظات </h6>
    <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="35%">ملاحظات </td>

            <td>{{ optional($user->profile)->notes }}</td>
        </tr>
    </table>
    <h6 style="font-weight: bold;text-align: center">  العهدة </h6>
    <table id="customers" class="mt-5 w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="35%">العهدة </td>
            <td>
                @if(optional($user->profile)->custodies)
                    <ul>
                        @foreach(optional($user->profile)->custodies as $data)
                            <li>{{$data->control->value }}
                                <br>
                                <small style="margin-top: 50px">
                                    {{$data->description}}
                                </small>
                            </li>

                        @endforeach

                        @if(!@$loop->last)
                            <hr style="margin: 5px;padding: 0;color: #eee;background: #eee">
                        @endif
                    </ul>
                @endif
            </td>
        </tr>
    </table>
    @if(count($files) != 0)
        <h6 style="font-weight: bold;text-align: center">مرفقات الموظف</h6>
        <table id="customers" class=" w3-round-xlarge" style="direction: rtl">
            @foreach($files as $key=>$file)
                <tr class="form-group">
                    <td class="header" width="35%" colspan="2"> {{$file->filename}} </td>
                </tr>
            @endforeach
        </table>
    @endif

</div>
</body>
</html>



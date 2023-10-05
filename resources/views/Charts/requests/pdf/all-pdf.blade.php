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
        تقرير  الطلبات المجمع لللإستشاري
        <br>
        <b class="text-red" style="font-weight: bold"> {{$user->name}} </b>

    </div>
    <hr>
    <h6 style="font-weight: bold;text-align: center">
        @if($start!=null && $end!=null)
            فى المدة من
            {{$start}}
            إلى
            {{$end}}
        @elseif($start!=null && $end==null)
            فى المدة من
            {{$start }}
        @elseif($start==null && $end!=null)
            فى المدة إلى
            {{$end }}
        @else
            منذ بداية النظام
        @endif

    </h6>
    <h6 style="text-align: center;font-weight: bold">تقرير حالات الطلب</h6>
    <table id="customers" class=" w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="65%">الحالة</td>
            <td style="text-align: center">العدد</td>
        </tr>
        <tr class="form-group">
            <td class="head" width="65%">جديد</td>
            <td style="text-align: center">{{$user->newStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">مفتوح</td>
            <td style="text-align: center">{{$user->openStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">مؤرشف عند  استشاري المبيعات</td>
            <td style="text-align: center">{{$user->archiveStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">بإنتظار موافقة  مدير المبيعات</td>
            <td style="text-align: center">{{$user->watingSMStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">رفض من قبل مدير المبيعات</td>
            <td style="text-align: center">{{$user->rejectedSMStatus??0 }}</td>
        </tr>
        <tr>
            {{--<td class="head" width="65%">مؤرشف عند مدير المبيعات</td>
              <td style="text-align: center">{{$user->archiveSMStatus??0 }}</td>--}}
            <td class="head" width="65%"> بإنتظار موافقة  مدير التمويل</td>
            <td style="text-align: center">{{$user->watingFMStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">رفض من قبل  مدير المبيعات</td>
            <td style="text-align: center">{{$user->rejectedFMStatus??0 }}</td>
            {{--<td class="head" width="65%"> رفض من قبل  مدير المبيعات</td>
            <td style="text-align: center">{{$user->archiveFMStatus??0 }}</td>--}}
        </tr>
        <tr>
            <td class="head" width="65%"> بإنتظار موافقة  مدير الرهن</td>
            <td style="text-align: center">{{$user->watingMMStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">رفض من قبل  مدير الرهن </td>
            <td style="text-align: center">{{$user->rejectedMMStatus??0 }}</td>
        </tr>
        <tr>
            {{--   <td class="head" width="65%"> رفض من قبل  مدير الرهن</td>
               <td style="text-align: center">{{$user->archiveMMStatus??0 }}</td>--}}
            <td class="head" width="65%"> بإنتظار موافقة  المدير العام</td>
            <td style="text-align: center">{{$user->watingGMStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">رفض من قبل المدير العام</td>
            <td style="text-align: center">{{$user->rejectedGMStatus??0 }}</td>
            {{--  <td class="head" width="65%">رفض من قبل المدير العام</td>
              <td style="text-align: center">{{$user->archiveGMStatus??0 }}</td>--}}
        </tr>
        <tr>
            <td class="head" width="65%">ملغي</td>
            <td style="text-align: center">{{$user->canceledStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">مكتمل</td>
            <td style="text-align: center">{{$user->completedStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">في تقرير التمويل</td>
            <td style="text-align: center">{{$user->fundingReportStatus??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">في تقرير الرهن</td>
            <td style="text-align: center">{{$user->mortgageReportStatus??0 }}</td>
        </tr>
    </table>
    <pagebreak></pagebreak>
    <h6 style="text-align: center;font-weight: bold">تقرير تصنيفات الطلب</h6>
    <table id="customers" class=" w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="65%">التصنيف</td>
            <td style="text-align: center">العدد</td>
        </tr>

        @foreach($classifications as $class)
            <tr>
                <td class="head" width="65%">{{$class->value}}</td>
                <td style="text-align: center">{{$user->classifications->where("class_id_agent",$class->id)->first()->counts ?? 0}}</td>
            </tr>
        @endforeach
    </table>
    <pagebreak></pagebreak>
    <h6 style="text-align: center;font-weight: bold">تقرير سلال الطلب</h6>
    <table id="customers" class=" w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="65%">الحالة</td>
            <td style="text-align: center">العدد</td>
        </tr>
        <tr>
            <td class="head" width="65%">مكتملة</td>
            <td style="text-align: center">{{$user->complete??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">مؤرشفة</td>
            <td style="text-align: center">{{$user->archived??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">متابعة</td>
            <td style="text-align: center">{{$user->following??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">مميزة</td>
            <td style="text-align: center">{{$user->star??0 }}</td>
        </tr>
        <tr>
            <td class="head" width="65%">مستلمة</td>
            <td style="text-align: center">{{$user->received??0 }}</td>
        </tr>
    </table>
</div>
</body>
</html>



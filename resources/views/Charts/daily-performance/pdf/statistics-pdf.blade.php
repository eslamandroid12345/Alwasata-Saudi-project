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
        تقرير اداء الإستشاري
        <br>
        <b class="text-red" style="font-weight: bold"> {{$user->name}} </b>

    </div>
    <hr>
    <h6 style="font-weight: bold;text-align: center"> فى المدة من
        {{$start}}
    إلى
         {{$end}}
    </h6>
    <table id="customers" class=" w3-round-xlarge" style="direction: rtl">
        <tr class="form-group">
            <td class="head" width="35%"> طلبات مستقبلة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("received_basket")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%"> طلبات جديدة (تلقائي)</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("move_request_to")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">طلبات مميزة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("star_basket")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">طلبات متابعة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("followed_basket")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">طلبات مؤرشفة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("archived_basket")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">طلبات مرفوعة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("completed_request")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%" {{auth()->user()->role == 1 ?"colspan='3'" : ""}}>طلبات مفرغة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("sent_basket")}} </td>
        </tr>


        <tr class="form-group">
            <td class="head" width="35%">طلبات مُحدث عليها</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("updated_request")}} </td>
        </tr>

        <tr class="form-group">
            <td class="head" width="35%">طلبات تم فتحها</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("opened_request")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">مهام مستلمة</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("received_task")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">مهام تم الرد عليها</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("replayed_task")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">طلبات محولٌة منه</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("move_request_from")}} </td>
        </tr>
        <tr class="form-group">
            <td class="head" width="35%">طلبات محولٌة إليه</td>
            <td style="text-align: center">{{$user->performances()->whereBetween('today_date', [$start, $end])->sum("move_request_to")}} </td>

        </tr>


    </table>
</div>
</body>
</html>



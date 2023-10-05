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
        تقرير تصنيفات طلب الإستشاري
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
</div>
</body>
</html>



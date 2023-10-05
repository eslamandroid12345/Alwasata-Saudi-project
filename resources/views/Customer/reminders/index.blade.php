@extends('Customer.fundingReq.customerReqLayout')


@section('title') الشكاوي والاقتراحات @endsection
<style>
    .data td{
        color: #333;
    }
</style>

@section('content')

    <div style="text-align: left; padding: 2% ; font-size:large">
        <a href="{{url('/customer') }}">
            الرئيسية
            <i class="fa fa-home"> </i>
        </a>
        |
        <a href="{{ url()->previous() }}">
            رجوع
            <i class="fa fa-arrow-circle-left"> </i>
        </a>

    </div>

    <div class="container">

        <div class="asks-form mt-5">
            <div class="head-div text-center wow fadeInUp">
                <h1>التذكيرات</h1>

            </div>

            @if(session()->has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('success') }}
                </div>
            @endif

            @if(session()->has('errorSugg'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('errorSugg') }}
                </div>
            @endif


            <div class="add-new">
                <div class="">
                    @if(isset($editMode))
                    <form action="{{route('customer.customer-reminders.update',$edit->id)}}" method="post" id="form">
                        @csrf
                        {{method_field('PATCH')}}
                    @else
                    <form action="{{route('customer.customer-reminders.store')}}" method="post" id="form">
                        @csrf
                        {{method_field('POST')}}
                    @endif
                        <strong style="color:darkred; font-size: 100%"> {{ $errors->first('type') }}</strong>
                        <div class="ask-body mt-4">

                            <div class="askText mt-2">
                                <div class="form-group">
                                    <label for="body">الوصف</label>
                                    <textarea class="form-control" name="body" id="body" rows="3">{{ isset($editMode) ? $edit->body :  old('body')}}</textarea>

                                    <strong style="color:darkred; font-size: 100%"> {{ $errors->first('body') }}</strong>

                                </div>
                                <div class="form-group">
                                  <div class="row">
                                      <div class="col-lg-8">
                                          <label for="date">التاريخ</label>
                                          <input class="d-block form-control" type="date" name="date" min="{{$today}}" id="" value="{{  isset($editMode) ? $edit->date :  old('date')}}">
                                          <strong style="color:darkred; font-size: 100%"> {{ $errors->first('date') }}</strong>
                                      </div>
                                      <div class="col-lg-4">
                                          <label for="time" style="display: block">الوقت</label>

                                          <input class="d-block form-control" type="time" name="time" id="time" value="{{ isset($editMode) ? $edit->time : old('time')}}">
                                          <strong style="color:darkred; font-size: 100%"> {{ $errors->first('time') }}</strong>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($editMode))
                        <div class=" send-btn my-2">
                            <button type="submit" class="btn btn-info"> تعديل </button>
                        </div>
                        @else
                        <div class=" send-btn my-2">
                            <button type="submit" class="btn btn-success"> إضافة </button>
                        </div>
                        @endif
                    </form>
                </div>

            </div>

            <div class="asksAll mt-5">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">التذكيرات </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">التقويم</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h6 class=" mt-5">التحكم فى التذكيرات </h6>
                        <table id="example"  class="table table-stripe table-bordered mt-3">
                            <thead>
                            <tr>
                                <td width="10">م</td>
                                <td>التذكير</td>
                                <td>التاريخ</td>
                                <td>الوقت</td>
                                <td width="115">مسح / تعديل</td>
                            </tr>
                            </thead>
                            <tbody>
                            @if($reminders->count() == 0)
                                <tr>
                                    <td colspan="5" class="text-center alert alert-info">لا يوجد تذكيرات</td>
                                </tr>
                            @else
                                @foreach($reminders as $remind)
                                    <tr class="data">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$remind->body}} </td>
                                        <td>{{\App\Http\Controllers\RemindersController::ArabicDate($remind->date)}}</td>
                                        <td>{{date(($remind->time < 11) ? 'h:i \صباحاً' : 'h:i \مساءً',strtotime($remind->time))}}</td>
                                        <td>

                                            @if ($today <= $remind->date)
                                                @if ($today == $remind->date)
                                                    @if ($current < $remind->time)
                                                        <a href="{{route('customer.customer-reminders.edit',$remind->id)}}" class="btn btn-info btn-sm "><i class="fa fa-edit"></i></a>
                                                    @else
                                                        <span class="badge badge-success " style="padding: 6px 10px;padding-top: 5px"><i class="fa fa-history"></i></span>
                                                    @endif
                                                @else
                                                    <a href="{{route('customer.customer-reminders.edit',$remind->id)}}" class="btn btn-info btn-sm "><i class="fa fa-edit"></i></a>
                                                @endif
                                            @else
                                                <span class="badge badge-success " style="padding: 6px 10px;padding-top: 5px"><i class="fa fa-history"></i></span>

                                            @endif
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal{{$remind->id}}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <div class="modal fade" id="exampleModal{{$remind->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">مسح التذكير</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{route('customer.customer-reminders.destroy',$remind->id)}}" method="POST">
                                                            {{csrf_field()}}
                                                            {{method_field('DELETE')}}
                                                            <div class="modal-body">
                                                                هل انت متأكد من مسح التذكير ؟
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                                <button  type="submit" class="btn btn-danger">نعم , امسح</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="container" ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('calender/dist/simple-calendar.css')}}">
    <link rel="stylesheet" href="{{asset('calender/dist/demo.css')}}">
    <script type="text/javascript" src="javascript/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="http://ericjgagnon.github.io/wickedpicker/wickedpicker/wickedpicker.min.css">

    <style>
        .calendar table {
            width: 100%;
            margin: 135px 0 !important;
            border-spacing: 0px;
        }
        .calendar .day {
            position: relative;
            display: inline-block;
            width: 2.5em;
            height: 2.5em;
            line-height: 2.5em;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            text-align: center;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <style>
        .dataTables_filter label{
            direction: rtl;
            float: left;
        }
    </style>
@endsection
@section('scripts')



    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script src="{{asset('calender/dist/jquery.simple-calendar.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#container").simpleCalendar({
                fixedStartDay: 0, // begin weeks by sunday
                disableEmptyDetails: true,
                events: [
                    // generate new event after tomorrow for one hour
                   @foreach($reminders as $remind)
                    {
                        startDate: '{{$remind->date}}',
                        endDate: '{{$remind->date}}',
                        summary: "{{$remind->body}}",
                        time: "{{date("A h:m",strtotime($remind->time))}}"
                    },
                    @endforeach
                ],

            });
        });

    </script>
    <script type="text/javascript" src="http://ericjgagnon.github.io/wickedpicker/javascript/smooth_scroll.js"></script>

    <script type="text/javascript" src="http://ericjgagnon.github.io/wickedpicker/wickedpicker/wickedpicker.min.js"></script>
    <script type="text/javascript">

    </script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}"}
            });
        } );
    </script>
@endsection

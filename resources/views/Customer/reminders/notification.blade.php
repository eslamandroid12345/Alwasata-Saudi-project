@extends('Customer.fundingReq.customerReqLayout')


@section('title') الإشعارات @endsection
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
                <h1>الإشعارات </h1>

            </div>

            @if(session()->has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="asksAll mt-5">
                <table id="example"  class="table table-stripe table-bordered mt-3">
                    <thead>
                    <tr>
                        <td width="10">م</td>
                        <td>التنبيه</td>
                        <td>الحالة</td>
                        <td>التاريخ</td>
                        <td>الوقت</td>
                        <td width="115">قراءة / مسح</td>
                    </tr>
                    </thead>
                    <tbody>
                    @if($reminders->count() == 0)
                        <tr>
                            <td colspan="5" class="text-center alert alert-info">لا يوجد اشعارات</td>
                        </tr>
                    @else
                        @foreach($reminders as $remind)
                            <tr class="data">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$remind->value}}</td>
                                <td><span class="badge badge-{{$remind->status ==0 ? 'danger' : 'success'}}">
                                         {{$remind->status == 0 ? 'غير مقروء' : 'مقروء'}}
                                    </span>

                                </td>
                                <td>
                                    @if(now('Asia/Riyadh')->toDateString() == date("Y-m-d",strtotime($remind->reminder_date)))
                                        <span class="badge badge-danger">
                                        اليوم
                                    </span>
                                    @else
                                        {{date("Y-M-d",strtotime($remind->reminder_date))}}
                                    @endif
                                </td>
                                <td>{{ date("h:i A",strtotime($remind->reminder_date))}}</td>
                                <td>
                                    <a href="{{route('customer.notifications.read',$remind->id)}}" class="btn btn-outline-info btn-sm "><i class="fa fa-eye"></i></a>

                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#exampleModal{{$remind->id}}">
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

                                                <div class="modal-body">
                                                    هل انت متأكد من مسح الإشعار ؟
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                    <a href="{{route('customer.notifications.delete',$remind->id)}}"  type="submit" class="btn btn-danger">نعم , امسح</a>
                                                </div>


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
        </div>
    </div>

@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <style>
        .dataTables_filter label{
            direction: rtl;
            float: left;
        }
    </style>
@endsection
@section('scripts')
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

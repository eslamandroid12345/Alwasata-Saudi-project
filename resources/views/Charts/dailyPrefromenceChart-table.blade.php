<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 text-md-right mt-lg-0 mt-3">
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div id="dt-btns" class="tableAdminOption">
                </div>
            </div>
        </div>
    </div>


    <table id="" class="table table-bordred table-striped data-table">
        <thead>
            <tr style="text-align: center;">

                <th>التاريخ</th>

                <th> الاستشاري</th>



                <th>المجموع</th>
                <th> طلبات جديدة (تلقائي)</th>


                <th>طلبات مميزة</th>

                <th>طلبات متابعة</th>

                <th>طلبات مؤرشفة</th>

                <th>طلبات مرفوعة</th>

                <th>طلبات مفرغة</th>

                @if (auth()->user()->role != 1)
                <th>طلبات مُحدث عليها</th>
                @endif
                <th>طلبات تم فتحها</th>


                <th>مهام مستلمة</th>
                <th>مهام تم الرد عليها</th>


                <th>تذكيرات فائتة</th>


                <th>طلبات محولٌة منه</th>
                <th>طلبات محولٌة إليه</th>

            </tr>
        </thead>
        <tbody style="text-align: center;" align="center">

            @foreach($users as $data)

            <tr>
                <td>{{--{{$data-}}--}} </td>
                <td>{{$data->name}}</td>

                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("total_recived_request")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("received_basket")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("star_basket")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("followed_basket")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("archived_basket")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("sent_basket")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("completed_request")}}</td>
                @if (auth()->user()->role != 1)
                    <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("updated_request")}}</td>
                @endif
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("opened_request")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("received_task")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("replayed_task")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("missed_reminders")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("move_request_from")}}</td>
                <td>{{$data->performances()->whereBetween('today_date', [$start, $end])->sum("move_request_to")}}</td>


            </tr>
            @endforeach


        </tbody>

    </table>
</div>

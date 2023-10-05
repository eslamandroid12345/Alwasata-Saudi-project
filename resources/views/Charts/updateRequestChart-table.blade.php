

<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>تحديث الطلب:</h4>
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div  id="dt-btns" class="tableAdminOption">
                </div>
            </div>
        </div>
    </div>
    <table id="" class="table table-bordred table-striped data-table">
        <thead>
        <tr style="text-align: center;">

            @foreach($data_for_chart as $data)

                <th>استشاري المبيعات</th>

                <th>عدد الطلبات</th>

                <th>الطلبات المحدث <br> عليها</th>

                <th>متوسط التحديث <br> على الطلب</th>

                <th>النسبة المئوية</th>

                @break
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                <td>{{$data['noReqs']}}</td>

                <td>{{$data['updateReqs']}}</td>

                <td>{{$data['avgvalue']}}</td>

                <td>{{$data['present']}} % </td>

            </tr>
        @endforeach

        </tbody>
    </table>
</div>

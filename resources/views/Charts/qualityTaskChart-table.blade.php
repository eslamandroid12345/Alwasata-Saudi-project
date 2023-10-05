
<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>مهام الجودة :</h4>
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

                <th>عدد التذاكر</th>

                <th>عدد التذاكر المكتملة</th>

                <th>عدد التذاكر الغير مكتملة</th>

                <th>نسبة التذاكر المكتملة</th>


                <th> نسبة متوسط سرعة <br> الرد على التذاكر</th>

                @break
            @endforeach
        </tr>
        </thead>

        <tbody style="text-align: center;">


        @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                <td>{{$data['answredTask']}}</td>
                <td>{{$data['completedTask']}}</td>
                <td>{{$data['notcompletedTask']}}</td>
                <td>{{$data['presentComplete']}} % </td>
                <td>{{$data['presentAverage']}} % </td>


            </tr>
        @endforeach

        </tbody>
    </table>
</div>

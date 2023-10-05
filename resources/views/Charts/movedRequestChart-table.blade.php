<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>الطلبات المحولة :</h4>
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

                <th>محولة من <br> الاستشاري</th>

                <th>محولة إلى <br> الاستشاري</th>

                <th>النسبة المئوية</th>

                @break
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                <td>{{$data['movedFrom']}}</td>
                <td>{{$data['movedTo']}}</td>

                <td>{{$data['present']}} % </td>

            </tr>

        @endforeach

        </tbody>
    </table>
</div>

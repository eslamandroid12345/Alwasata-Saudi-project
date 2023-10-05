<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>النتيجة النهائية :</h4>
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

                <th> سرعة الاتصال</th>

                <th> تقديم الحسبة</th>

                <th> المتابعة مع العميل</th>

                <th>صحة الملاحظة</th>

                <th>الطلبات المحولة</th>

                <th> التجاوب مع الجودة</th>
                <th> التذاكر المكتملة</th>

                <th>التحديث على <br> الطلب</th>



                <th>النتيجة النهائية</th>

                @break
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>


                <td>{{$data['q1']}} % </td>

                <td>{{$data['q2']}} % </td>

                <td>{{$data['q3']}} % </td>

                <td>{{$data['q4']}} % </td>


                <td>{{$data['move_present']}} % </td>

                <td>{{$data['updateTask_present']}} % </td>

                <td>{{$data['completeTask_present']}} % </td>

                <td>{{$data['updateReq_present']}} % </td>


                <td>{{$data['finalResult']}} % </td>

            </tr>
        @endforeach

        </tbody>
    </table>
</div>

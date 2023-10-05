
<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>الطلبات المعلقة :</h4>
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div  id="dt-btns2" class="tableAdminOption">
                </div>
            </div>
        </div>
    </div>
    <table id="" class="table table-bordred table-striped data-table2">
        <thead>
        <tr style="text-align: center;">

            <th>التاريخ</th>

            <th>ويب - اطلب تمويل</th>

            <th>موقع الحاسبة <br> العقارية - مكتمل</th> 
            <th>موقع الحاسبة <br> العقارية - غير مكتمل</th> 

            <th>ويب - اطلب استشارة</th>
            <th>تطبيق - اطلب إستشارة</th>
            <th>تطبيق - حاسبة التمويل</th>

<th>ويب - الحاسبة العقارية</th>

<th>عطارد</th>

<th>تمويلك</th>
           

        </tr>
        </thead>
        <tbody style="text-align: center;" align="center">

        @foreach($data_for_pending as $data)

            <tr>
                <td >{{$data['dateRange']}}</td>
                <td>{{$data['webAskFunding']}} </td>
                <td>{{$data['hasbah_net_completed']}} </td>
                <td>{{$data['hasbah_net_notcompleted']}} </td>
                <td>{{$data['webAskCons']}} </td>
                <td>{{$data['app_askcons']}} </td>
                <td>{{$data['app_calc']}} </td>
                <td>{{$data['webCal']}} </td>
                <td>{{$data['otared']}} </td>
                <td>{{$data['tamweelk']}} </td>

            </tr>
        @endforeach


        </tbody>
        <tfoot align="center">
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>
        </tfoot>
    </table>
</div>

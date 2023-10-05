<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>سلال الطلب:</h4>
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div  id="dt-btns3" class="tableAdminOption">
                </div>
            </div>
        </div>
    </div>
    <table id="" class="table table-bordred table-striped data-table3">
        <thead>
        <tr style="text-align: center;">

            @foreach($data_for_basket_chart as $data)

                <th>استشاري المبيعات</th>


                @if(in_array("allBaskets",$baskets) || in_array("complete",$baskets))
                    <th>مكتملة</th>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("archived",$baskets))
                    <th>مؤرشفة</th>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("following",$baskets))
                    <th>متابعة</th>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("star",$baskets))
                    <th>مميزة</th>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("received",$baskets))
                    <th>مستلمة</th>
                @endif


                @break
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_basket_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                @if(in_array("allBaskets",$baskets) || in_array("complete",$baskets))
                    <td>{{$data['complete']}}</td>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("archived",$baskets))
                    <td>{{$data['archived']}}</td>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("following",$baskets))
                    <td>{{$data['following']}}</td>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("star",$baskets))
                    <td>{{$data['star']}}</td>
                @endif

                @if(in_array("allBaskets",$baskets) || in_array("received",$baskets))
                    <td>{{$data['received']}}</td>
                @endif

            </tr>
        @endforeach

        </tbody>
    </table>
</div>

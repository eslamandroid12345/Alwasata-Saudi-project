<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>سلال الطلب:</h4>
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div id="dt-btns3" class="tableAdminOption"></div>
            </div>
        </div>
    </div>
    <table id="" class="table table-bordred table-striped data-table3">
        <thead>
        <tr style="text-align: center;">
            <th>@lang('attributes.quality_id')</th>
            @foreach($QualityBasketsSelect as $basket)
                <th>{{$basket['name']}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">
        @foreach($data_for_basket_chart as $data)
            <tr>
                <td>{{$data['name']}}</td>
                @foreach($QualityBasketsSelect as $value)
                    <th>{{$data[$value['id']] ?? 0}}</th>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

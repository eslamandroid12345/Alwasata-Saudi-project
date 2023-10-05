<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>تصنيف الطلب:</h4>
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

            @foreach($data_for_status_chart as $data)

                <th>استشاري المبيعات</th>

                @foreach($agent_class as $class)
                    @if(in_array("allClass",$classes) || in_array('class-'.$class->id,$classes))
                        <th>{{$class->value}}</th>
                    @endif
                @endforeach

                @break
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_class_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                @foreach($agent_class as $class)

                    @if(in_array("allClass",$classes) || in_array('class-'.$class->id,$classes))
                        <td>{{ $data['class-'.$class->id] }}</td>
                    @endif

                @endforeach



            </tr>
        @endforeach

        </tbody>
    </table>
</div>


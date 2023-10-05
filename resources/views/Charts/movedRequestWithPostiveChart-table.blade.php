<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>طلبات (مرفوع ، مكتمل) :</h4>
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

                @foreach($data_for_chart as $data)

                <th>استشاري المبيعات</th>

                <th>محولة من <br> الاستشاري - مرفوعة</th>

                <th>محولة من <br> الاستشاري - مكتملة</th>

                <th>المجموع</th>
                <th>تفاصيل</th>

                @break
                @endforeach
            </tr>
        </thead>
        <tbody style="text-align: center;">


            @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                <td>{{$data['movedSent']}}</td>
                <td>{{$data['movedComplete']}}</td>

                <td>{{$data['total']}}</td>
                <td><a href="{{route('admin.histories.details',$data['id'])}}" class="btn btn-success btn-sm">
                        <i class="fa fa-eye"></i>
                    </a></td>

            </tr>

            @endforeach

        </tbody>
    </table>
</div>

<hr>

<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>نوع نقل طلبات (مرفوع ، مكتمل) :</h4>
            </div>
            <div class="col-lg-4 text-md-right mt-lg-0 mt-3">
                <div id="dt-btns2" class="tableAdminOption">
                </div>
            </div>
        </div>
    </div>


    <table id="" class="table table-bordred table-striped data-table2">
        <thead>
            <tr style="text-align: center;">

                @foreach($data_for_chart as $data)

                <th>استشاري المبيعات</th>

                <th>اطلب عملاء</th>
                <th>مدير النظام</th>
                <th>سلة طلبات <br> بحاجة للتحويل</th>
                <th>سلة الأرشيف</th>
                <th>استشاري مؤرشف</th>
                <th>الطلبات المعلقة</th>
                <th>غير محدد</th>

                @break
                @endforeach
            </tr>
        </thead>
        <tbody style="text-align: center;">


            @foreach($data_for_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                <td>{{$data['moved_AskReq']}}</td>
                <td>{{$data['moved_Admin']}}</td>
                <td>{{$data['moved_NeedActionTable']}}</td>
                <td>{{$data['moved_ArchiveBacket']}}</td>
                <td>{{$data['moved_ArchiveAgent']}}</td>
                <td>{{$data['moved_Pending']}}</td>
                <td>{{$data['moved_Undefined']}}</td>

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
            </tr>
        </tfoot>
    </table>
</div>
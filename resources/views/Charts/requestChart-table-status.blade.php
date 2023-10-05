<div class="dashTable" style="padding: 20px">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-8 ">
                <h4>حالات الطلب:</h4>
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

            @foreach($data_for_status_chart as $data)

                <th>استشاري المبيعات</th>


                @if(in_array('allStatus', $statuses) || in_array('newStatus', $statuses))
                    <th>جديد</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('openStatus', $statuses))
                    <th>مفتوح</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('archiveStatus', $statuses))
                    <th>مؤرشف عند <br> استشاري المبيعات</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('watingSMStatus', $statuses))
                    <th>بإنتظار موافقة <br> مدير المبيعات</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedSMStatus', $statuses))
                    <th> رفض من قبل <br> مدير المبيعات</th>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveSMStatus', $statuses))
                <th> مؤرشف عند <br> مدير المبيعات</th>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('watingFMStatus', $statuses))
                    <th> بإنتظار موافقة <br> مدير التمويل</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedFMStatus', $statuses))
                    <th>رفض من قبل <br> مدير التمويل</th>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveFMStatus', $statuses))
                <th>مؤرشف عند <br> مدير التمويل</th>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('watingMMStatus', $statuses))
                    <th>بإنتظار موافقة <br> مدير الرهن</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedMMStatus', $statuses))
                    <th>رفض من قبل <br> مدير الرهن</th>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveMMStatus', $statuses))
                <th>مؤرشف عند <br> مدير الرهن</th>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('watingGMStatus', $statuses))
                    <th>بإنتظار موافقة <br> المدير العام</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedGMStatus', $statuses))
                    <th>رفض من قبل <br> المدير العام</th>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveGMStatus', $statuses))
                <th>مؤرشف عند <br> المدير العام</th>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('canceledStatus', $statuses))
                    <th>ملغي</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('completedStatus', $statuses))
                    <th>مكتمل</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('fundingReportStatus', $statuses))
                    <th>في تقرير التمويل</th>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('mortgageReportStatus', $statuses))
                    <th>في تقرير الرهن</th>
                @endif

                @break
            @endforeach
        </tr>
        </thead>
        <tbody style="text-align: center;">


        @foreach($data_for_status_chart as $data)

            <tr>

                <td>{{$data['name']}}</td>

                @if(in_array('allStatus', $statuses) || in_array('newStatus', $statuses))
                    <td>{{$data['newStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('openStatus', $statuses))
                    <td>{{$data['openStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('archiveStatus', $statuses))
                    <td>{{$data['archiveStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('watingSMStatus', $statuses))
                    <td>{{$data['watingSMStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedSMStatus', $statuses))
                    <td>{{$data['rejectedSMStatus']}}</td>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveSMStatus', $statuses))
                <td>{{$data['archiveSMStatus']}}</td>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('watingFMStatus', $statuses))
                    <td>{{$data['watingFMStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedFMStatus', $statuses))
                    <td>{{$data['rejectedFMStatus']}}</td>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveFMStatus', $statuses))
                <td>{{$data['archiveFMStatus']}}</td>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('watingMMStatus', $statuses))
                    <td>{{$data['watingMMStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedMMStatus', $statuses))
                    <td>{{$data['rejectedMMStatus']}}</td>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveMMStatus', $statuses))
                <td>{{$data['archiveMMStatus']}}</td>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('watingGMStatus', $statuses))
                    <td>{{$data['watingGMStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('rejectedGMStatus', $statuses))
                    <td>{{$data['rejectedGMStatus']}}</td>
                @endif

                {{--@if(in_array('allStatus', $statuses) || in_array('archiveGMStatus', $statuses))
                <td>{{$data['archiveGMStatus']}}</td>
                @endif--}}

                @if(in_array('allStatus', $statuses) || in_array('canceledStatus', $statuses))
                    <td>{{$data['canceledStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('completedStatus', $statuses))
                    <td>{{$data['completedStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('fundingReportStatus', $statuses))
                    <td>{{$data['fundingReportStatus']}}</td>
                @endif

                @if(in_array('allStatus', $statuses) || in_array('mortgageReportStatus', $statuses))
                    <td>{{$data['mortgageReportStatus']}}</td>
                @endif

            </tr>
        @endforeach

        </tbody>
    </table>
</div>


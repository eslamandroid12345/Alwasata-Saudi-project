@php
    $req_histories = DB::table('request_histories')->where('request_histories.req_id', $purchaseClass->id)->leftjoin('users as user', 'user.id', '=', 'request_histories.user_id') // name join if u will join same table twic
            ->leftjoin('users as rec', 'rec.id', '=', 'request_histories.recive_id')->leftjoin('users as switch', 'switch.id', '=', 'request_histories.user_switch_id')->select('request_histories.*', 'user.name as sentname', 'rec.name as recname',
                'switch.name as swname')->orderBy('request_histories.id', 'ASC')->get();
@endphp
<!-- begin::portlet  -->
<div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head">
      <!-- begin::portlet__head-label  -->
      <div class="portlet__head-label">سجل الطالب</div>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    <div class="portlet__body pt-0">
        <table class="table table-custom table-striped">
          <thead>
            <tr>
              <th>المحتوى</th>
              <th>من</th>
              <th>الى</th>
              <th>الملاحظة</th>
              <th>التاريخ</th>
              <th>التقييم</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($req_histories as $req_historie)
                <tr>

                @if ($req_historie->title == 'نقل الطلب' && (auth()->user()->role ==7 || auth()->user()->role ==4))
                    <td style="white-space:unset;">{{$req_historie->title}} - {{$req_historie->content}}</td>
                    @else
                    <td style="white-space:unset;">{{$req_historie->title}}</td>
                @endif

                    @if($req_historie->sentname != null)

                    @if($req_historie->swname != null)
                    <td>{{$req_historie->sentname}} / {{$req_historie->swname}}</td>
                    @else
                    <td>{{$req_historie->sentname}}</td>
                    @endif

                    @else
                    <td>---</td>
                    @endif

                    @if($req_historie->recname != null)
                    <td>{{$req_historie->recname}}</td>
                    @else
                    <td>---</td>
                    @endif

                    @if ($req_historie->title == 'نقل الطلب' )
                    <td></td>
                    @else
                    <td style="width: 200px !important;word-wrap: break-word;white-space: unset;">{{$req_historie->content}}</td>
                    @endif

                    <td>{{$req_historie->history_date}}</td>

                    @if (isset($customer->app_rate_starts))
                        <td>{{$customer->app_rate_starts}}</td>
                    @else
                        <td>---</td>
                    @endif

                </tr>
                @endforeach
            </tbody>
        </table>
      </div>
    <!-- end::portlet__body  -->
  </div>
  <!-- end::portlet  -->

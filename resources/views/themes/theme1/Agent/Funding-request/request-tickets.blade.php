@php
    $role = auth()->user()->role;
        if ($role != 7 && $role != 4) {
            $requests = DB::table('requests')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->where('requests.id', $request->id)
                ->join('tasks', 'tasks.req_id', 'requests.id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->select('tasks.*', 'user.name as user_name', 'user.role as user_role', 'recive.name as recive_name');
        }
        else {
            $reqid = $request->id;
            $getQualityReqsIDS = DB::table('quality_reqs')
                ->where('req_id', $reqid)
                ->pluck('id')->toArray();
            //dd(count($getQualityReqsIDS));
            if (count($getQualityReqsIDS) == 0) {
                $requests = DB::table('requests')
                    ->join('tasks', 'tasks.req_id', 'requests.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->where('requests.id', $reqid)
                    ->where('recive.role', '!=', 5)
                    ->where('user.role', '!=', 5)
                    ->select('tasks.*', 'user.name as user_name', 'user.role as user_role', 'recive.name as recive_name');
            }
            else {
                //dd(count($getQualityReqsIDS));
                $taskReqs = DB::table('requests')
                    ->join('tasks', 'tasks.req_id', 'requests.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->where('requests.id', $reqid)
                    ->where('recive.role', '!=', 5)
                    ->where('user.role', '!=', 5)
                    ->pluck('tasks.id')->toArray();

                $taskQuality = DB::table('quality_reqs')
                    ->join('tasks', 'tasks.req_id', 'quality_reqs.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->whereIn('quality_reqs.id', $getQualityReqsIDS)
                    ->pluck('tasks.id')->toArray();

                $mergedArray = array_merge($taskQuality, $taskReqs);
                //dd($mergedArray);
                $requests = DB::table('tasks')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->whereIn('tasks.id', $mergedArray)
                    ->select('tasks.*', 'user.name as user_name', 'user.role as user_role', 'recive.name as recive_name')
                    ->get();
            }
        }
@endphp
<h5>تذاكر الطلب</h5>
<div class="row">
<div class="col-lg-4">
    <!-- begin::portlet  -->
    <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head border-bottom">
        <!-- begin::portlet__head-label  -->
        <div class="portlet__head-label">التذاكر المرسلة</div>
        <div class="portlet__head-label text-primary">{{ $requests->whereIn('tasks.status', [0,1])->count()}}</div>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    @foreach ($requests->whereIn('tasks.status', [0])->get() as $item)
    <div class="portlet__body">
        <div class="border rounded-5 p-3 pb-0">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{$item->user_name ?? ''}}</h6>
            <div class="label label-primary font-medium">مرسلة</div>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{$item->recive_name ?? ''}}</h6>
            <h6>
                @if ($item->user_role == 0)
                استشاري
                @elseif ($item->user_role == 1)
                مدير مبيعات
                @elseif ($item->user_role == 7)
                الادمن
                @elseif ($item->user_role == 5)
                الجوده
                @else
                اخري
                @endif
            </h6>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="13.191" height="13.19" viewBox="0 0 13.191 13.19">
                <path
                id="Icon_ionic-md-calendar"
                data-name="Icon ionic-md-calendar"
                d="M14.942,11.645h-3.3v3.3h3.3ZM13.843,4.5V5.6h-5.5V4.5H6.7V5.6H5.874A1.378,1.378,0,0,0,4.5,6.973v9.343A1.378,1.378,0,0,0,5.874,17.69H16.316a1.378,1.378,0,0,0,1.374-1.374V6.973A1.378,1.378,0,0,0,16.316,5.6h-.824V4.5Zm2.473,11.816H5.874V9.034H16.316Z"
                transform="translate(-4.5 -4.5)"
                fill="#6c757d"
                ></path>
            </svg>
            <h6 class="pt-1">{{\Carbon\Carbon::parse($item->created_at)->format('d-m-Y')}}</h6>
            </div>
            <a class="d-flex align-items-center" href="{{route('all.show_users_task', $item->id)}}">
            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="16.1" height="11.982" viewBox="0 0 16.1 11.982">
                <g id="Icon_feather-eye" data-name="Icon feather-eye" transform="translate(0.5 0.5)">
                <path
                    id="Path_3993"
                    data-name="Path 3993"
                    d="M1.5,11.491S4.246,6,9.05,6s7.55,5.491,7.55,5.491-2.746,5.491-7.55,5.491S1.5,11.491,1.5,11.491Z"
                    transform="translate(-1.5 -6)"
                    fill="none"
                    stroke="#00acf1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                ></path>
                <path
                    id="Path_3994"
                    data-name="Path 3994"
                    d="M17.618,15.559A2.059,2.059,0,1,1,15.559,13.5,2.059,2.059,0,0,1,17.618,15.559Z"
                    transform="translate(-8.009 -10.068)"
                    fill="none"
                    stroke="#00acf1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                ></path>
                </g>
            </svg>
            <h6>عرض التذكرة</h6>
            </a>
        </div>
        </div>
    </div>
    @endforeach

    </div>
</div>
<div class="col-lg-4">
    <!-- begin::portlet  -->
    <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head border-bottom">
        <!-- begin::portlet__head-label  -->
        <div class="portlet__head-label">التذاكر المستلمة</div>
        <div class="portlet__head-label text-primary">{{ $requests->where('tasks.status', 2)->count()}}</div>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    @foreach ($requests->whereIn('tasks.status', [1,2])->get() as $item)
    <div class="portlet__body">
        <div class="border rounded-5 p-3 pb-0">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{$item->user_name ?? ''}}</h6>
            <div class="label label-danger font-medium">مستلمة</div>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{$item->recive_name ?? ''}}</h6>
            <h6>العميل</h6>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="13.191" height="13.19" viewBox="0 0 13.191 13.19">
                <path
                id="Icon_ionic-md-calendar"
                data-name="Icon ionic-md-calendar"
                d="M14.942,11.645h-3.3v3.3h3.3ZM13.843,4.5V5.6h-5.5V4.5H6.7V5.6H5.874A1.378,1.378,0,0,0,4.5,6.973v9.343A1.378,1.378,0,0,0,5.874,17.69H16.316a1.378,1.378,0,0,0,1.374-1.374V6.973A1.378,1.378,0,0,0,16.316,5.6h-.824V4.5Zm2.473,11.816H5.874V9.034H16.316Z"
                transform="translate(-4.5 -4.5)"
                fill="#6c757d"
                ></path>
            </svg>
            <h6 class="pt-1">{{\Carbon\Carbon::parse($item->created_at)->format('d-m-Y')}}</h6>
            </div>
            <a class="d-flex align-items-center" href="{{route('all.show_users_task', $item->id)}}">
            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="16.1" height="11.982" viewBox="0 0 16.1 11.982">
                <g id="Icon_feather-eye" data-name="Icon feather-eye" transform="translate(0.5 0.5)">
                <path
                    id="Path_3993"
                    data-name="Path 3993"
                    d="M1.5,11.491S4.246,6,9.05,6s7.55,5.491,7.55,5.491-2.746,5.491-7.55,5.491S1.5,11.491,1.5,11.491Z"
                    transform="translate(-1.5 -6)"
                    fill="none"
                    stroke="#00acf1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                ></path>
                <path
                    id="Path_3994"
                    data-name="Path 3994"
                    d="M17.618,15.559A2.059,2.059,0,1,1,15.559,13.5,2.059,2.059,0,0,1,17.618,15.559Z"
                    transform="translate(-8.009 -10.068)"
                    fill="none"
                    stroke="#00acf1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                ></path>
                </g>
            </svg>
            <h6>عرض التذكرة</h6>
            </a>
        </div>
        </div>
    </div>
    @endforeach
    </div>
</div>
<div class="col-lg-4">
    <!-- begin::portlet  -->
    <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head border-bottom">
        <!-- begin::portlet__head-label  -->
        <div class="portlet__head-label">التذاكر المكتملة</div>
        <div class="portlet__head-label text-primary">{{ $requests->where('tasks.status', 3)->count()}}</div>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    @foreach ($requests->whereIn('tasks.status', [3])->get() as $item)
    <div class="portlet__body">
        <div class="border rounded-5 p-3 pb-0">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{$item->user_name ?? ''}}</h6>
            <div class="label label-success font-medium">مكتملة</div>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6>{{$item->recive_name ?? ''}}</h6>
            <h6>العميل</h6>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="13.191" height="13.19" viewBox="0 0 13.191 13.19">
                <path
                id="Icon_ionic-md-calendar"
                data-name="Icon ionic-md-calendar"
                d="M14.942,11.645h-3.3v3.3h3.3ZM13.843,4.5V5.6h-5.5V4.5H6.7V5.6H5.874A1.378,1.378,0,0,0,4.5,6.973v9.343A1.378,1.378,0,0,0,5.874,17.69H16.316a1.378,1.378,0,0,0,1.374-1.374V6.973A1.378,1.378,0,0,0,16.316,5.6h-.824V4.5Zm2.473,11.816H5.874V9.034H16.316Z"
                transform="translate(-4.5 -4.5)"
                fill="#6c757d"
                ></path>
            </svg>
            <h6 class="pt-1">{{\Carbon\Carbon::parse($item->created_at)->format('d-m-Y')}}</h6>
            </div>
            <a class="d-flex align-items-center" href="{{route('all.show_users_task', $item->id)}}">
            <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="16.1" height="11.982" viewBox="0 0 16.1 11.982">
                <g id="Icon_feather-eye" data-name="Icon feather-eye" transform="translate(0.5 0.5)">
                <path
                    id="Path_3993"
                    data-name="Path 3993"
                    d="M1.5,11.491S4.246,6,9.05,6s7.55,5.491,7.55,5.491-2.746,5.491-7.55,5.491S1.5,11.491,1.5,11.491Z"
                    transform="translate(-1.5 -6)"
                    fill="none"
                    stroke="#00acf1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                ></path>
                <path
                    id="Path_3994"
                    data-name="Path 3994"
                    d="M17.618,15.559A2.059,2.059,0,1,1,15.559,13.5,2.059,2.059,0,0,1,17.618,15.559Z"
                    transform="translate(-8.009 -10.068)"
                    fill="none"
                    stroke="#00acf1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                ></path>
                </g>
            </svg>
            <h6>عرض التذكرة</h6>
            </a>
        </div>
        </div>
    </div>
    @endforeach
    <!-- end::portlet__body  -->
    </div>
    <!-- end::portlet  -->
</div>
</div>


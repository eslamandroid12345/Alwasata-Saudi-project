<div class="dashTable">
    <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
        <thead>
        <tr>
            <th>#</th>
            <th>الإسم</th>
            <th>البريد الإلكترونى</th>
            <th>رقم الجوال</th>
            <th>جهه العمل</th>
            <th>المرتب</th>
            <th>هل أكمل الطلب</th>
            <th>هل لديه طلب</th>
            <th>التحكم</th>
        </tr>
        </thead>
        <tbody>
        @foreach($guests as $guest)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$guest->name}}</td>
                <td>{{$guest->email}}</td>
                <td>{{$guest->mobile}}</td>
                <td>{{$guest->work}}
                    {{$guest->work == 'عسكري' ? '- '.$guest->military_rank : '' }}
                </td>
                <td>{{$guest->salary}}  </td>
                <td>
                    @if($guest->status == 0)
                        <span class="badge badge-warning">لم يكمل</span>
                    @else
                        <span class="badge badge-primary">أكمل الطلب</span>
                    @endif
                </td>
                <td>
                    @if($guest->has_request == 0)
                        <span class="badge badge-info">ليس لديه طلب سابق</span>
                    @else
                        <span class="badge badge-success">لديه طلب سابق</span>
                    @endif
                </td>
                <td>
                    <a href="" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                    <a href="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
</div>

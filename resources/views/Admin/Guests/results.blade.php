@section('main')
<div class="dashTable">
        <table id="pendingReqs-2-table" class="table table-bordred table-striped data-table">
            <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>الإسم</th>
                <th>البريد الإلكترونى</th>
                <th>رقم الجوال</th>
                <th>جهه العمل</th>
                <th>الراتب</th>
                <th>هل أكمل الطلب</th>
                <th>هل لديه طلب</th>
                <th>التحكم</th>
            </tr>
            </thead>
            <tbody>
            @foreach($guests as $guest)
                <tr>
                <td><input type="checkbox" id="chbx" name="chbx[]" onchange="disabledButton()" value="{{$guest->id}}"/></td>
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
                        <a onclick="deleteData({{$guest->id}})" class="btn btn-danger btn-sm" style="color:white;"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            @if(count($guests) == 0)
                <tr>
                    <td colspan="9" class="text-center">
                        لا يوجد نتائج
                    </td>
                </tr>
            @endif
            </tbody>

        </table>
    </div>
<script>
    var dt = $('#pendingReqs-2-table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}",
            buttons: {
                excelHtml5: "اكسل",
                print: "طباعة",
                pageLength: "عرض",

            }
        },
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "الكل"]
        ],
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            // 'csvHtml5',
            // 'pdfHtml5' ,
            'print',
            'pageLength'
        ],
        initComplete: function() {
            $('#dt-btns').html("");

            dt.buttons().container()
                .appendTo('#dt-btns');

            $(".dt-button").last().html('<i class="fas fa-search"></i>').attr('title', 'بحث');
            $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title', 'تصدير');
            $('.buttons-print').html('<i class="fas fa-print"></i>').attr('title', 'طباعة');
            $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title', 'عرض');

            $('.buttons-excel').addClass('no-transition custom-btn');
            $('.buttons-print').addClass('no-transition custom-btn');
            $('.buttons-collection').addClass('no-transition custom-btn');

            $('.tableAdminOption span').tooltip(top)
            $('button.dt-button').tooltip(top)

            /* To Adaptive with New Design */

        },
    });
</script>
@endsection

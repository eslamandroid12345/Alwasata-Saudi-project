@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'Properties Request') }}
@endsection
@section('css_style')
@endsection

@section('customer')

    <div id="alertDiv" class="alertDiv alr">

    </div>
<div>
  @if (session('msg'))
  <div id="msg" class="alert @if (session('type')) alert-{{ session('type') }} @endif ">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('msg') }}
  </div>
  @endif

</div>


<div class="row">
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <table class="table table-borderless table-striped table-earning data-table">
                <thead>
                <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'property num') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'boss') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'contact boss') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'status') }}</th>
                    <th>{{ MyHelpers::admin_trans(auth()->guard('customer')->user()->id,'date') }}</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {

            $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}"
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
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
                processing: true,
                serverSide: true,
                ajax: "{{route('customer.propertyRequests')}}",
                columns: [
                    { data: 'property_id', name: 'property_id' },
                    { data: 'boss', name: 'boss' },
                    { data: 'contact_boss', name: 'contact_boss' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });

        });
    </script>
@endsection

@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}
@endsection

@section('css_style')

<style>
    .middle-screen {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }
</style>
@endsection

@section('customer')



@if(!empty($message))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ $message }}
</div>
@endif

@if ( session()->has('message') )
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<h3>{{ MyHelpers::admin_trans(auth()->user()->id,'All Requests') }}:</h3>
<br>

<div class="table-data__tool">
    <div class="table-data__tool-right">
        <a href="{{ route('collaborator.purchasePage', ['title' => 'funding', 'id' => 'null'])}}">
            <button class="au-btn au-btn-icon au-btn--blue au-btn--small">
                <i class="zmdi zmdi-plus"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Request') }}</button></a>
    </div>
</div>

<div class="row">

    @if (!empty($requests[0]))
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <table class="table table-borderless table-striped table-earning data-table">
                <thead>
                    <tr>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                        <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
    </div>

    @endif

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
            ajax: "{{ url('collaborator/myreqs-datatable') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'req_date',
                    name: 'req_date'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'source',
                    name: 'source'
                },
                {
                    data: 'comment',
                    name: 'comment'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });
    });
</script>
@endsection

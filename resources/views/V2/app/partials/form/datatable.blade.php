@php
    $INCLUDES_BEFORE = $INCLUDES_BEFORE ?? [];
    if(!is_array($INCLUDES_BEFORE)) $INCLUDES_BEFORE = [$INCLUDES_BEFORE];

    $INCLUDES_AFTER = $INCLUDES_AFTER ?? [];
    if(!is_array($INCLUDES_AFTER)) $INCLUDES_AFTER = [$INCLUDES_AFTER];
@endphp
<div class="container">
    @foreach($INCLUDES_BEFORE as $INCLUDE_BEFORE)
        @if($INCLUDE_BEFORE && view()->exists($INCLUDE_BEFORE)) @include($INCLUDE_BEFORE) @endif
    @endforeach

    <div class="card">
        <div class="card-body">
            <div class="table-responsive p-1">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>

    @foreach($INCLUDES_AFTER as $INCLUDE_AFTER)
        @if($INCLUDE_AFTER && view()->exists($INCLUDE_AFTER)) @include($INCLUDE_AFTER) @endif
    @endforeach
</div>

@push('scripts')
    <script src="{{ asset('myth-plugins/laravel/datatables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('myth-plugins/laravel/datatables/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('myth-plugins/laravel/datatables/js/buttons.server-side.js') }}"></script>

    {!! $dataTable->scripts() !!}
@endpush

@push('styles')
    <link href="{{ asset('myth-plugins/laravel/datatables/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('myth-plugins/laravel/datatables/css/buttons.dataTables.css') }}" rel="stylesheet">
    <style>
        table.dataTable tbody th,
        table.dataTable tbody td{
            /*max-width: 200px;*/
        }

        .data-tables-action{
            /*width: 200px;*/
            /*max-width: 200px;*/
        }

        .dataTables_wrapper .dataTables_filter input{
            margin: 0 0.5em;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 0 0 rgba(0, 0, 0, 0);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .dataTables_wrapper .dataTables_length select {
            margin: 0 0.5em;
            padding: 0 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-shadow: inset 0 0 0 rgba(0, 0, 0, 0);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .dataTables_wrapper > * {
            margin-bottom: 0.5em;
        }
    </style>
@endpush

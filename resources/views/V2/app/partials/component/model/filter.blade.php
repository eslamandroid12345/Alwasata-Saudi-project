@php
$HIDE_FILTER = $HIDE_FILTER ?? !1;
@endphp
<div class="container">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">@lang("global.filter")</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool btn-filter-collapse" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row DataTable-container-filter">
                    {!! $slot !!}
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-primary datatable-btn-filter">@lang("global.search")</button>
            <button class="btn btn-sm btn-secondary datatable-btn-filter-clear">@lang("global.clear")</button>
        </div>
    </div>
</div>
@push('scripts')
    @if($HIDE_FILTER)
        <script>
            $(() => $(".btn-filter-collapse").click());
        </script>
    @endif
@endpush

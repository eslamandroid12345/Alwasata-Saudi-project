<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="recordColom" type="hidden">
                <div class="table-responsivee table--no-card m-b-30 text-left">
                    <table class="table table-borderless table-striped table-earning" style="table-layout: auto;">
                        <thead>
                            <tr>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                                <th style="max-width: 300px">{{ MyHelpers::admin_trans(auth()->user()->id,'The Update') }}</th>
                                <th style="width:30%">{{ MyHelpers::admin_trans(auth()->user()->id,'Update At') }}</th>
                            </tr>
                        </thead>
                        <tbody id="records">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Close') }}</button>
            </div>
{{--            </form>--}}
        </div>
    </div>
</div>

@push('styles')

    <style>
        .table th, .table td{
            white-space: unset !important;
        }
    </style>
@endpush

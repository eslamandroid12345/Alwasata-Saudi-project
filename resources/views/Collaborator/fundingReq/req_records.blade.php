<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">


        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">


                <input id="recordColom" type="hidden">

                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning" style="table-layout: auto;">
                        <thead>
                            <tr>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'The Update') }}</th>
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
            </form>
        </div>

    </div>
</div>

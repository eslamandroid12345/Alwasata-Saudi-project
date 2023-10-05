<!-- Modal -->
<div class="modal fade" id="myModal5" role="dialog">
    <div class="modal-dialog">
 

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('sales.manager.sendMorPur')}}" method="POST" id="frm-sending">
                <div class="modal-body">

                    @csrf

                    <input type="hidden" value= "{{$id}}" name="id" class="form-control" id="id">

                    <div class="form-group">
                        <label for="sendTo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }} {{ MyHelpers::admin_trans(auth()->user()->id,'To_') }}:</label>
                        <br><br>
                 
                            <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="funding" id="funding" name="sendTo" checked>
                                    <label class="custom-control-label" for="funding">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</label>
                                </div>
                            </div>
                            </div>
                            <br>
                            <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="agent" id="agent" name="sendTo">
                                    <label class="custom-control-label" for="agent">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="comment" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Comment') }}</label>
                            <input id="comment" name="comment" type="text" class="form-control" autocomplete="comment">
                        </div>
                        <br>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
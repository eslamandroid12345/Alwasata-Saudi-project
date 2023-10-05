<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">


        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Send Prepayment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('sales.manager.sendPrepayment')}}" method="POST" id="frm-sending">
                <div class="modal-body">

                    @csrf

                    <input type="hidden" value="{{$id}}" name="id" class="form-control" id="id">

                    <div class="form-group">
                        <label for="sendTo" class="control-label mb-1">Send To:</label>
                        <br><br>

                        <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="mortgage" id="mortgage" name="sendTo" checked>
                                    <label class="custom-control-label" for="mortgage">Mortgage Manager</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="agent" id="agent" name="sendTo">
                                    <label class="custom-control-label" for="agent">Sales Agent</label>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="form-group">
                            <label for="comment" class="control-label mb-1">Comment</label>
                            <input id="comment" name="comment" type="text" class="form-control" autocomplete="comment">
                        </div>
                        <br>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>

    </div>
</div>
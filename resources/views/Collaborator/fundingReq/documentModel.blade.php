<!-- Modal -->
<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">


        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'file') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>



            <form action="{{ route('collaborator.uploadFile')}}" enctype="multipart/form-data" method="POST" id="file-form">
                @csrf
                <div class="modal-body">



                    <input type="hidden" name="id" value="{{$id}}" class="form-control" id="id">

                    <div class="form-group">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'File Name') }}</label>
                        <input id="name" name="name" type="text" class="form-control" autocomplete="name" autofocus placeholder="">

                        <span class="text-danger" id="nameError" role="alert"> </span>

                    </div>
                    <br>

                    <div class="form-group">
                        <label for="file" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'File') }}</label>
                        <input type="file" name="file" id="file" class="form-control">
                       
                        <span class="text-danger" id="fileError" role="alert"> </span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-info">{{ MyHelpers::admin_trans(auth()->user()->id,'upload') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
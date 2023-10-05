<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.updaterealtype')}}" method="POST" id="frm-update">

                <div class="modal-body">

                    @csrf

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">


                    <!--here past addUserPage-->

                    <div class="form-group">
                        <label for="realtype" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate type') }}</label>

                        <input type="text" id="realtype" name="realtype"  class="form-control" value="{{ old('realtype') }}">

                        <span class="text-danger" id="realtypeError" role="alert"> </span>

                        @if ($errors->has('realtype'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('realtype') }}</strong>
                        </span>
                        @endif
                    </div>








                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
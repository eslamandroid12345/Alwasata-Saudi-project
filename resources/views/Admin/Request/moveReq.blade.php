<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal7">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">
                    {{ MyHelpers::admin_trans(auth()->user()->id,'Move Req') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.moveReqToAnother')}}" method="get" id="frm-update2">

                <div class="modal-body">

                    @csrf

                    <input type="hidden" name="id" class="form-control" id="id1">
                    <input type="hidden" name="moveid" class="form-control" id="movedReqID">
                    <input type="hidden" name="needid" class="form-control" id="needReqID">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">


                    <!--here past addUserPage-->



                    <div id="salesagentDiv" class="form-group">
                        <label for="salesagent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>

                        <select id="salesagent" name="salesagents" onfocus='this.size=3;' onblur='this.size=1;' class="form-control">

                        <option value="">---</option>

                            @foreach ($salesAgents as $salesAgent)

                            <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>

                            @endforeach

                        </select>

                        <div class="text-danger" id="salesagentsError" role="alert"> </div>

                        @if ($errors->has('salesagents'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                        </span>
                        @endif
                    </div>

                    <br>


                </div>

                <div class="modal-footer">
                    <button type="button" id="modal-btn-no7" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="button" id="submitMove" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Move') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

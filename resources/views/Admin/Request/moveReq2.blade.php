<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal8">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">@lang('language.Move Req')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.moveReqToAnother')}}" method="get" id="frm-update1">

                <div class="modal-body">

                    @csrf

                    <input type="hidden" name="id" class="form-control" id="id2">
                    <input type="hidden" name="moveid2[]" class="form-control" id="movedReqID2">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">


                    <!--here past addUserPage-->



                    <div id="salesagentDiv" class="form-group">
                        <label for="salesagent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>

                        <select id="salesagent2" name="salesagents" onfocus='this.size=3;' onblur='this.size=1;' class="form-control">

                        <option value="">---</option>

                            @foreach ($salesAgents as $salesAgent)

                            <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>

                            @endforeach

                        </select>

                        <div class="text-danger" id="salesagentsError2" role="alert"> </div>

                        @if ($errors->has('salesagents'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                        </span>
                        @endif
                    </div>

                    <br>


                </div>

                <div class="modal-footer">
                    <button type="button" id="modal-btn-no8" class="btn btn-secondary" data-dismiss="modal">@lang('language.Cancel')</button>
                    <button type="button" id="submitMove2" class="btn btn-primary">@lang('language.Move')</button>
                </div>
            </form>
        </div>
    </div>
</div>

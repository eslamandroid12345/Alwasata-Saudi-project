<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal9">
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

            <form action="{{ route('admin.moveReqToAnother')}}" method="get" id="frm-update3">

                <div class="modal-body">

                    @csrf

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">


                    <!--here past addUserPage-->

                    <div class="row">
                        <div class="form-group col-6 col-md-6">
                            {{-- <div class="row">
                                <div class="col-8 col-lg-8">
                                    <label for="sex" class="control-label mb-1">مديري المبيعات</label>
                                </div>
                                <div class="col-4 col-lg-4">
                                    <input type="checkbox" class="form-check-input" name="allow-recived" id="allow-recived-sales-managers" style="height: 15px"> غير نشط
                                </div>
                            </div> --}}


                            <label for="sex" class="control-label mb-1">مديري المبيعات</label>
                            <div class="" style="float:left">
                                <input type="checkbox" class="form-check-input" name="allow-recived" id="allow-recived-sales-managers-transfer-request" style="height: 15px"> غير نشط
                            </div>



                            @if (isset($salesManagers))
                            <div class="rs-select2 js-select-simple select--no-search" style="height: auto">
                                <select class="form-control py-5"  multiple id="multiple-sales-managers-transfer-request">
                                    @foreach($salesManagers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                                <div class="select-dropdown"></div>
                            </div>
                            @endif

                        </div>

                        <div class="form-group col-6 col-md-6">
                            <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                            <div class="" style="float:left">
                                <input type="checkbox" class="form-check-input" name="allow-recived-sales-agents" id="allow-recived-sales-agents-transfer-request" style="height: 15px;">  نشط
                            </div>
                            <select class="tokenizeable form-control" id="salesagent3" name="salesagents" multiple>
                                @foreach ($salesAgents as $salesAgent)
                                    <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>
                                @endforeach
                            </select>

                            <div class="text-danger" id="salesagentsError3" role="alert"> </div>
                            @if ($errors->has('salesagents'))
                                <span class="help-block col-md-12">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>




















                    {{-- <div id="salesagentDiv" class="form-group">
                        <label for="salesagent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>

                        <select id="salesagent3" class="tokenizeable form-control" name="salesagents" multiple>

                            @foreach ($salesAgents as $salesAgent)

                            <option value="{{$salesAgent->id}}">{{$salesAgent->name}}</option>

                            @endforeach

                        </select>

                        <div class="text-danger" id="salesagentsError3" role="alert"> </div>

                        @if ($errors->has('salesagents'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('salesagents') }}</strong>
                        </span>
                        @endif
                    </div> --}}

                    <br>


                </div>

                <div class="modal-footer">
                    <button type="button" id="modal-btn-no9" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="button" id="submitMove3" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Move') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

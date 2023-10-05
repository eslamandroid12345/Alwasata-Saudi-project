<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="#" method="POST" id="frm-update">
                <div class="modal-body row">

                    @csrf
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">


                    <div class="form-group col-12 col-md-12">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="tokenizeable form-control" id="agents_ids" multiple>
                            @foreach($salesAgents as $salesAgent)
                            <option value="{{ $salesAgent->id }}">{{ $salesAgent->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of sales agent') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_sa" multiple>
                            @foreach($classifcations_sa as $classifcation)
                            <option value="{{ $classifcation->id }}">{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}</label>
                        <input name="name" type="text" class="form-control" autocomplete="name" id="customer-phone" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}">
                    </div>



                    <div class="form-group col-6 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'From') }})</label>
                        <input name="name" type="text" class="form-control" autocomplete="name" id="customer-salary" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }}">
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'To_') }})</label>
                        <input name="name" type="text" class="form-control" autocomplete="name" id="customer-salary-to" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }}">
                    </div>




                    <div class="form-group col-12 col-md-12">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                        <select class="form-control tokenizeable" id="source" multiple>
                        @foreach ($request_sources as $request_source )
                        <option value="{{$request_source->id}}">{{$request_source->value}}</option>
                        @endforeach
                        </select>
                    </div>



                    <div id="collaboratorDiv" style="display:none;" class="form-group col-12 col-md-12">
                        <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>

                        <select id="collaborator" class="form-control tokenizeable" multiple>

                            @if (!empty($collaborators[0]))

                            @foreach ($collaborators as $collaborator )
                            <option value="{{$collaborator->id}}">{{$collaborator->name}}</option>
                            @endforeach

                            @else
                            <option disabled="disabled" value="">{{ MyHelpers::admin_trans(auth()->user()->id,'No Collaborator') }}</option>
                            @endif

                        </select>

                        @error('collaborator')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>




                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'from req date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="request-date-from" style="text-align: right" name="request-date-from" type="date" class="form-control" autocomplete="birth" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'to req date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="request-date-to" style="text-align: right" name="request-date-to" type="date" class="form-control" autocomplete="birth" autofocus>
                            </div>
                        </div>
                    </div>



                    <div class="form-group col-6 col-md-6">
                        <label for="notes_status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'app_downloaded') }}</label>
                        <select class="form-control tokenizeable" id="app_downloaded" multiple>
                            <option value="0">لا</option>
                            <option value="1">نعم</option>
                        </select>
                    </div>


                    <div class="form-group col-6 col-md-6">
                        <label for="work_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work_source') }}</label>
                        <select class="form-control tokenizeable" id="work_source" multiple>

                             @foreach ($worke_sources as $worke_source )
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                            @endforeach

                        </select>
                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="filter-search-req">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>

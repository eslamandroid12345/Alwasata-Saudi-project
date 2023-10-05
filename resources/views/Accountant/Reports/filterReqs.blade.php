<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
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


                    <div class="form-group col-4 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="tokenizeable form-control" id="agents_ids" multiple>
                            @foreach($salesAgents as $salesAgent)
                            <option value="{{ $salesAgent->id }}">{{ $salesAgent->name }}</option>
                            @endforeach

                        </select>
                    </div>




                    <div class="form-group col-4 col-md-4">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}</label>
                        <input name="name" type="text" class="form-control" autocomplete="name" id="customer-phone" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}">
                    </div>


                    <div class="form-group col-4 col-md-4">
                        <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>


                        <select id="collaborator" class="form-control tokenizeable" multiple>

                            @foreach ($collaborators as $collaborator )
                            <option value="{{$collaborator->id}}">{{$collaborator->name}}</option>
                            @endforeach


                        </select>

                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="notes_status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status') }}</label>
                        <select class="form-control tokenizeable" id="notes_status" multiple>
                            <option value="0">الفارغة</option>
                            <option value="1">تحتوي على محتوى</option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'request_type') }}</label>
                        <select class="form-control tokenizeable" id="request_type" multiple>
                            <option value="رهن-شراء">رهن-شراء</option>
                            <option value="شراء-دفعة">شراء-دفعة</option>
                            <option value="شراء">شراء</option>
                            <option value="رهن">رهن</option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="founding_sources" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'founding_sources') }}</label>
                        <select class="form-control tokenizeable" id="founding_sources" multiple>
                            @foreach($founding_sources as $founding_source)
                            <option value="{{ $founding_source->id }}">{{ $founding_source->value }}</option>
                            @endforeach
                        </select>
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



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="filter-search-req">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
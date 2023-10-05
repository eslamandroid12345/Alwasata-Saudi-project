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

                    <div class="form-group col-12 col-md-6">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</label>
                        <select class="form-control tokenizeable" id="request_status" multiple>
                            @foreach($all_status as $key => $status)

                            @if ($key != "5" && $key != "18" && $key != "20" && $key != "22" && $key != "21" && $key != "8" && $key != "25" && $key != "26"  && $key != "27" && $key != "23" &&  $key != "11" && $key != "14" && $key != "30" && $key != "31" && $key != "32" && $key != "33" && $key != "34" && $key != "35") <!-- Remove dublicate-->
                            <option value="{{ $key }}">{{ $status }}</option>
                            @endif

                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label  class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="tokenizeable form-control" id="agents_ids" multiple>
                        @foreach($salesAgents as $salesAgent)
                            <option value="{{ $salesAgent->id }}">{{ $salesAgent->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="work_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work_source') }}</label>
                        <select class="form-control tokenizeable" id="work_source" multiple>
                        @foreach ($worke_sources as $worke_source )
                            <option value="{{$worke_source->id}}">{{$worke_source->value}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }}</label>
                        <input name="name" type="text" class="form-control" autocomplete="name" id="customer-salary" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }}">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                        <select class="form-control tokenizeable" id="source" multiple>
                        @foreach ($request_sources as $request_source )
                        <option value="{{$request_source->id}}">{{$request_source->value}}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}</label>
                        <input name="name" type="text" class="form-control" autocomplete="name" id="customer-phone" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}">
                    </div>




                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="customer-birth" style="text-align: right" name="birth" type="date" class="form-control" autocomplete="birth" autofocus>
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'request_type') }}</label>
                        <select class="form-control tokenizeable" id="request_type" multiple>
                            <option value="رهن-شراء">رهن-شراء</option>
                            <option value="شراء-دفعة">شراء-دفعة</option>
                            <option value="شراء">شراء</option>
                            <option value="رهن">رهن</option>
                            <option value="تساهيل">تساهيل</option>
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
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="#" method="POST" id="filter-pending">
                <div class="modal-body row">

                    @csrf

                    <div class="form-group col-6 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }} (من)</label>
                        <input name="name" type="number" class="form-control" autocomplete="name" id="customer-salary-from" autofocus>
                    </div>
                    <div class="form-group col-6 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }} (إلى)</label>
                        <input name="name" type="number" class="form-control" autocomplete="name" id="customer-salary-to" autofocus>
                    </div>



                    <div class="form-group col-4 col-md-4">
                        <label for="is_supported" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</label>
                        <select class="form-control" id="is_supported">
                            <option value="">---</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group col-4 col-md-4">
                        <label for="has_property" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_property') }}</label>
                        <select class="form-control" id="has_property">
                            <option value="">---</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group col-4 col-md-4">
                        <label for="has_joint" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_joint') }}</label>
                        <select class="form-control" id="has_joint">
                            <option value="">---</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group col-4 col-md-4">
                        <label for="has_obligations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has obligations') }}</label>
                        <select class="form-control" id="has_obligations">
                            <option value="">---</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group col-4 col-md-4">
                        <label for="has_financial_distress" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has financial distress') }}</label>
                        <select class="form-control" id="has_financial_distress">
                            <option value="">---</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>

                    <div class="form-group col-4 col-md-4">
                        <label for="has_owning_property" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'has_owning_property') }}</label>
                        <select class="form-control" id="has_owning_property">
                            <option value="">---</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>

                        </select>
                    </div>




                    <div class="form-group col-12 col-md-12">
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
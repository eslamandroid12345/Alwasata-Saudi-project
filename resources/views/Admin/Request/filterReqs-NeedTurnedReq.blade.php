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

                    <div class="form-group col-6 col-md-6">
                        <div class="row">
                            <div class="col-8 col-lg-8">
                                <label for="sex" class="control-label mb-1">مديري المبيعات</label>
                            </div>
                            <div class="col-4 col-lg-4">
                                <input type="checkbox" class="form-check-input" name="allow-recived" id="allow-recived-sales-managers" style="height: 15px"> غير نشط
                            </div>
                        </div>


                        <div class="rs-select2 js-select-simple select--no-search" style="height: auto">
                            <select class="form-control py-5"  multiple id="multiple-sales-managers">
                                @foreach($salesManagers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            <div class="select-dropdown"></div>
                        </div>

                    </div>


                    <div class="form-group col-6 col-md-6">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="tokenizeable form-control" id="agents_ids" multiple>
                            @foreach($salesAgents2 as $salesAgent)
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
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Quality User') }}</label>
                        <select class="tokenizeable form-control" id="qualityUser" multiple>
                            @foreach($qualityUser as $quality_user)
                            <option value="{{ $quality_user->id }}">{{ $quality_user->name }}</option>
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

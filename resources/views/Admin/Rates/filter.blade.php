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
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="form-control" id="agent_id">
                           <option value="">--</option>
                            @foreach($salesAgents as $salesAgent)
                            <option value="{{ $salesAgent->id }}">{{ $salesAgent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="date" class="control-label mb-1">تاريخ التقييم</label>
                        <input type="date" name="" id="date-of-rate" class="form-control">
                    </div>


                    <div class="form-group col-6 col-md-6">
                        <label for="stars" class="control-label mb-1">عدد النجوم</label>
                        <select class="form-control" id="stars">
                           <option value="">--</option>
                           <option value="1">1</option>
                           <option value="2">2</option>
                           <option value="3">3</option>
                           <option value="4">4</option>
                           <option value="5">5</option>
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

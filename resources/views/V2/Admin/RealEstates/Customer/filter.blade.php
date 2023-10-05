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
                        <label for="sex" class="control-label mb-1">نوع العقار</label>
                        <select name="uniqueRealTypes" id="uniqueRealTypes" class="form-control">
                            <option value="">نوع العقار</option>
                            @foreach ($uniqueRealTypes as $realType)
                                <option value="{{ $realType->id }}">{{ $realType->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="sex" class="control-label mb-1">استشاري المبيعات</label>
                        <select name="uniqueAgents" id="uniqueAgents" class="form-control">
                            <option value="">استشاري المبيعات</option>
                            @foreach ($uniqueAgents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-6">
                        <label for="sex" class="control-label mb-1">المدينة</label>
                        <select name="uniqueCities" id="uniqueCities" class="form-control">
                            <option value="">المدينة</option>
                            @foreach ($uniqueCities as $city)
                                <option value="{{ $city->id }}">{{ $city->value }}</option>
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

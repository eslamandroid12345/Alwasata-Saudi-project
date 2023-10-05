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


                    <div class="form-group col-12 col-md-12">
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
                        <label for="name" class="control-label mb-1">العملية</label>
                        <select class="form-control tokenizeable" id="action" multiple>
                            <option value="عميل مكرر لإستشاري مؤرشف">عميل مكرر لإستشاري مؤرشف</option>
                            <option value="طلب بدون تحديث">طلب بدون تحديث</option>
                            <option value="مهمة جديدة">مهمة جديدة</option>
                            <option value="رسالة جديدة من العميل">رسالة جديدة من العميل</option>
                            <option value="طلب مضاف من قبل مدير النظام">طلب مضاف من قبل مدير النظام</option>
                            <option value="طلب مضاف من قبل الجودة">طلب مضاف من قبل الجودة</option>
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
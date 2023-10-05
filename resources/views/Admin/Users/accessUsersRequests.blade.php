<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="AccessModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- <form action="#" method="POST"> -->
                <div class="modal-body row">

                    @csrf                    
                  

                    <div class="form-group col-6 col-md-4">
                        <label for="duration" class="control-label mb-1">المستخدمين</label>
                        <select class="form-control py-2" style="height: 55px" name="duration" id="duration_type">
                            <option value="0">الكل</option><!--كل المستخدمين -->
                            <option value="1">مدير المبيعات</option><!--هياخد الفريق بتاعه كله-->
                            <option value="2"> الاستشاريين</option><!--الاستشاريين-->
                            <option value="3"> تحديد مستخدم</option><!--يختار اكتر من مستخدم -->
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="filter-search-job">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</button>
                </div>
            <!-- </form> -->
        </div>

    </div>
</div>

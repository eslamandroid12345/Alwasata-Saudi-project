<div class="modal fade bd-example-modal-lg"  id="add-form" style="margin-top: 60px" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form-contact" method="POST" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <input type="hidden"  id="id"   required name="id">

                    <div class="form-group row pr-3 pl-3">
                        <div class="col-lg-8">
                            <label for="name" class="col-form-label">إسم الأجازة : </label>
                            <input type="text" class="form-control" id="name"  autofocus name="name">
                            <span class="text-danger">
                                <strong id="name-error"></strong>
                            </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="gender" class="col-form-label">الجنس  : </label>
                            <select class="form-control" id="gender"  autofocus name="gender">
                                <option disabled selected>أختار..</option>
                                <option value="male">ذكر</option>
                                <option value="female"> أنثي</option>
                                <option value="both"> ذكر / أنثي</option>
                            </select>
                            <span class="text-danger">
                                <strong id="gender-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row pr-3 pl-3">
                        <div class="col-lg-4">
                            <label for="type" class="col-form-label">نوع الأجازة : </label>
                            <select class="form-control" id="type"  autofocus name="type">
                                <option disabled selected>أختار..</option>
                                <option value="official">رسمية</option>
                                <option value="unofficial"> غير رسمية</option>
                            </select>
                            <span class="text-danger">
                            <strong id="is_vacations_deduction-error"></strong>
                            </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="is_salary_deduction" class="col-form-label">هل تخصم من الراتب : </label>
                            <select  class="form-control"  id="is_salary_deduction"  autofocus name="is_salary_deduction">
                                <option disabled selected>أختار..</option>
                                <option value="0">لا تخصم من الراتب</option>
                                <option value="1"> تخصم من الراتب</option>
                            </select>
                            <span class="text-danger">
                            <strong id="is_salary_deduction-error"></strong>
                        </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="is_vacations_deduction" class="col-form-label">هل تخصم من رصيد الأجازات : </label>
                            <select class="form-control" id="is_vacations_deduction"  autofocus name="is_vacations_deduction">
                                <option disabled selected>أختار..</option>
                                <option value="0">لا تخصم من رصيد الأجازات</option>
                                <option value="1"> تخصم من رصيد الأجازات</option>
                            </select>
                            <span class="text-danger">
                            <strong id="is_vacations_deduction-error"></strong>
                        </span>
                        </div>

                    </div>
                    <div class="form-group row pr-3 pl-3">

                        <div class="col-lg-4">
                            <label for="days" class="col-form-label">عدد الأيام  : </label>
                            <input type="number"  class="form-control" id="days"  autofocus name="days">

                            <span class="text-danger">
                                <strong id="days-error"></strong>
                            </span>
                        </div>

                        <div class="col-lg-4">
                            <label for="days_commitment" class="col-form-label">الإلتزام بالأيام  : </label>
                            <select class="form-control" id="days_commitment"  autofocus name="days_commitment">
                                <option disabled selected>أختار..</option>
                                <option value="equal">يساوي</option>
                                <option value="min"> أقل من </option>
                                <option value="max"> أكبر من </option>
                            </select>
                            <span class="text-danger">
                                <strong id="days_commitment-error"></strong>
                            </span>
                        </div>
                        <div class="col-lg-4">
                            <label for="count" class="col-form-label">عدد مرات طلب الأجازة  : </label>
                            <input type="number"  class="form-control" id="count"  autofocus name="count">

                            <span class="text-danger">
                                <strong id="count-error"></strong>
                            </span>
                        </div>

                    </div>
                    <div class="form-group row pr-3 pl-3">
                        <div class="col-lg-6 typeHide">
                            <label for="date_from" class="col-form-label"> التاريخ من : </label>
                            <input type="date"  class="form-control" id="date_from"  autofocus name="date_from">

                            <span class="text-danger">
                                <strong id="date_from-error"></strong>
                            </span>
                        </div>
                        <div class="col-lg-6 typeHide">
                            <label for="date_to" class="col-form-label">التاريخ إلى : </label>
                            <input type="date"  class="form-control" id="date_to"  autofocus name="date_to">

                            <span class="text-danger">
                                <strong id="date_to-error"></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

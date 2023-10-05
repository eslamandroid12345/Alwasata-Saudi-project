<div class="modal fade" id="add-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">التحكم في الارقام</h5>
          <button class="btn-close ms-0 shadow-none" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
            <ul class="nav nav-pills mb-4 tab-custom-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-1" type="button" role="tab">إضافة جوال جديد</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-2" type="button" role="tab">
                        @if (auth()->user()->role == 7)
                        تعديل الجولات المضافة
                        @else
                        الجوالات المضافة
                        @endif
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-1" role="tabpanel">
                    <form id="contactData" method="POST"  data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden"  id="id"   required name="id">

                        <div class="form-group">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="mb-0">{{ MyHelpers::admin_trans(auth()->user()->id,'customer mobile') }}</label>
                                <h6 id="checkMobile2" class="text-success">{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}</h6>
                            </div>
                            <input class="form-control" type="number" id="add_mobile"  autofocus name="mobileNumber" required />
                            <span class="text-danger">
                                <strong id="mobile-error"></strong>
                                <strong id="add_mobile_error"></strong>
                            </span>

                            <input type="hidden" name="request_id" id="add_request_id" value="{{$id}}">
                            <input type="hidden" name="customer_id" id="add_customer_id" value="{{$purchaseCustomer->customer_id}}">
                        </div>
                        <div class="form-group text-center row">
                            <div class="col-lg-5 mx-auto">
                                <button type="button" id="submit" class="btn btn-primary w-100 py-2">إضافة</button>
                            </div>
                        </div>
                </form>
                </div>
                <div class="tab-pane fade" id="pills-2" role="tabpanel">
                <div class="row mb-4">
                        @if (auth()->user()->role == 7)
                            <form id="contactEditData" method="POST" class="form-horizontal" data-toggle="validator">
                                {{ csrf_field() }} {{ method_field('POST') }}
                                <input type="hidden"  id="id"   required name="id">
                                <input type="hidden" name="request_id" id="add_request_id" value="{{$id}}">
                                <input type="hidden" name="customer_id" id="add_customer_id" value="{{$purchaseCustomer->customer_id}}">
                                <div class="form-group" id="edit-data">

                                </div>
                                <p id="empty"></p>
                                <div class="form-group" id="emptyBTN">
                                    <button type="button" class="btn btn-primary" id="savePhones">حفظ</button>
                                </div>
                            </form>
                            @else
                                <div class="form-group" id="edit-data">

                                </div>
                                <p id="empty"></p>
                        @endif
                </div>
                </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade mt-5"  id="confirm-form" style="margin-top: 60px" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width: 29%;margin-top: 12%;background: #eee" >
        <div class="modal-content " style="background: #eee">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel1"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <p>هل انت متأكد من مسح الرقم لن تكون قادر على الرجوع ؟</p>
                <input type="hidden"  id="id_delete"   required name="id_delete">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="button" onclick="submitDelete()" class="btn btn-danger">نعم ,إمسح</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade mt-5"  id="show-form" style="margin-top: 0px" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel11"> عرض الأرقام</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <div class="card card-body">
                    <div class="form-group" id="show-data">

                    </div>
                    <p id="empty"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

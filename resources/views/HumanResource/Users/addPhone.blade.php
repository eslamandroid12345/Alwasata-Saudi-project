<div class="modal fade"  id="add-form" style="margin-top: 60px" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <div id="accordion">
                    <p>
                        <button class="btn btn-light " type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa fa-plus"></i>
                            إضافة جوال جديد
                        </button>
                        @if (auth()->user()->role == 7)
                            <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                                <i class="fa fa-edit"></i>
                                تعديل الجولات المضافة
                            </button>
                        @else
                            <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                                <i class="fa fa-list"></i>
                                الجولات المضافة
                            </button>
                        @endif
                    </p>
                    <div class="collapse show" id="collapseExample"  data-parent="#accordion">
                        <div class="card card-body">
                            <form id="contactData" method="POST" class="form-horizontal" data-toggle="validator">
                                {{ csrf_field() }} {{ method_field('POST') }}
                                <input type="hidden"  id="id"   required name="id">

                                <div class="form-group">
                                    <label for="Customer" >
                                       الجوال
                                        <small id="checkMobile2" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                            <i class="fas fa-question i-20"></i>
                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                        </small>
                                    </label>
                                    <input type="number" class="form-control" id="add_mobile"  autofocus name="mobileNumber" required>
                                    <span class="text-danger">
                                    <strong id="mobile-error"></strong>
                                    <strong id="add_mobile_error"></strong>
                                </span>

                                    <input type="hidden" name="user_id" id="add_user_id" value="{{$user->id}}">
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="submit">إضافة</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="collapse" id="collapseExample1"  data-parent="#accordion">
                        <div class="card card-body">
                            @if (auth()->user()->role == 7)
                                <form id="contactEditData" method="POST" class="form-horizontal" data-toggle="validator">
                                    {{ csrf_field() }} {{ method_field('POST') }}
                                    <input type="hidden"  id="id"   required name="id">
                                    <input type="hidden" name="user_id" id="add_user_id" value="{{$user->id}}">
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
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>

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

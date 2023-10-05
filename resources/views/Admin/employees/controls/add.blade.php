<div class="modal fade bd-example-modal-lg"  id="add-form" style="margin-top: 60px"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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

                    <div class="form-group">
                        <label for="value" class="col-form-label">اسم ال{{MyHelpers::getControlTypeName($type)[1]}} : </label>
                        <input type="text" class="form-control" id="value"  autofocus name="value">
                        <span class="text-danger">
                            <strong id="value-error"></strong>
                        </span>
                    </div>
                    <input type="hidden" name="type" value="{{$type}}">
                    @if($type == 'guaranty')
                        <div class="form-group">
                            <label for="parent_id" class="col-form-label">أختار الشركة: </label>
                            <select class="form-control" id="parent_id"  autofocus name="parent_id">
                                <option disabled selected>أختار  الشركة</option>
                                @foreach($companies as $section)
                                    <option value="{{$section->id}}">{{$section->value}}</option>
                                @endforeach
                            </select>

                            <span class="text-danger">
                            <strong id="parent_id-error"></strong>
                        </span>
                        </div>
                        {{--<div class="form-group">
                            <label for="guaranty_name" class="col-form-label">{{MyHelpers::getControlTypeName('guaranty_name')[1]}} : </label>
                            <input type="text" class="form-control" id="guaranty_name"  autofocus name="guaranty_name">
                            <span class="text-danger">
                            <strong id="guaranty_name-error"></strong>
                        </span>
                        </div>--}}
                    @endif
                    @if($type == 'subsection')
                        <div class="form-group">
                            <label for="parent_id" class="col-form-label">أختار القسم الرئيسى : </label>
                            <select class="form-control" id="parent_id"  autofocus name="parent_id">
                                <option disabled selected>أختار القسم الرئيسي</option>
                                @foreach($sections as $section)
                                    <option value="{{$section->id}}">{{$section->value}}</option>
                                @endforeach
                            </select>

                            <span class="text-danger">
                            <strong id="parent_id-error"></strong>
                        </span>
                        </div>
                    @elseif($type == 'nationality')
                        <div class="custom-control custom-checkbox" style="line-height: 20px;">
                            <input type="checkbox" name="parent_id" value="0" class="custom-control-input customers_checkbox" id="parent_id">
                            <label class="custom-control-label text-xs" for="parent_id">الجنسية الرئيسية (بدون كفالة ...الخ)</label>
                        </div>';
                    @endif

                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

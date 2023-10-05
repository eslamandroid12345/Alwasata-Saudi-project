<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Classification') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.updateclass')}}" method="POST" id="frm-update">

                <div class="modal-body">

                    @csrf

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">


                    <!--here past addUserPage-->

                    <div class="form-group">
                        <label for="class" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Classification') }}</label>

                        <input type="text" id="class" name="class" placeholder="اكتب محتوى التصنيف هنا" class="form-control" value="{{ old('class') }}">

                        <span class="text-danger" id="classError" role="alert"> </span>

                        @if ($errors->has('class'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('class') }}</strong>
                        </span>
                        @endif
                    </div>




                    <div class="form-group">
                        <label for="role" class="control-label mb-1">المستخدم</label>

                        <select class="form-control" id="role" name="role">

                            <option value="" selected>---</option>

                            @foreach($user_roles as $key => $user_role)
                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $user_role }}</option>
                            @endforeach

                        </select>


                        <span class="text-danger" id="roleError" role="alert"> </span>

                        @if ($errors->has('role'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="type" class="control-label mb-1">نوع التصنيف</label>

                        <select class="form-control" id="type" name="type">

                            <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>إيجابي</option>
                            <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>سلبي</option>


                        </select>

                        @if ($errors->has('type'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('type') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="is_required_in_calculater" class="control-label mb-1">متطلب للحسبة؟</label>

                        <select class="form-control" id="is_required_in_calculater" name="is_required_in_calculater">

                            <option value="1" {{ old('is_required_in_calculater') == 1 ? 'selected' : '' }}>نعم</option>
                            <option value="0" {{ old('is_required_in_calculater') == 1 ? 'selected' : '' }}>لا</option>


                        </select>

                        @if ($errors->has('is_required_in_calculater'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('is_required_in_calculater') }}</strong>
                        </span>
                        @endif
                    </div>







                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
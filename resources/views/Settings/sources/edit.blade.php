<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} مصدر المعاملة </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.update.source')}}" method="POST" id="frm-update">

                <div class="modal-body">

                    @csrf

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">


                    <!--here past addUserPage-->

                    <div class="form-group">
                        <label for="source" class="control-label mb-1">مصدر المعالملة</label>

                        <input type="text" id="source" name="source" placeholder="اكتب محتوى مصدر المعاملة هنا" class="form-control" value="{{ old('class') }}">

                        <span class="text-danger" id="classError" role="alert"> </span>

                        @if ($errors->has('class'))
                        <span class="help-block col-md-12">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('class') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="role">@lang('language.role')</label>
                        <select id="role" name="role" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check(this);' placeholder="@lang('language.role')">
                            <option selected >لا يوجد</option>
                            @foreach($RoleSelected as $key =>$role)
                                <option value="{{$key}}">{!!$role!!}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block col-md-12">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>

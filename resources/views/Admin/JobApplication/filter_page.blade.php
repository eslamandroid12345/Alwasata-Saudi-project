<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
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
                        <label for="job" class="control-label mb-1"> المُسمى الوظيفى</label>
                        <select class="form-control py-2" style="height: 50px" name="job" id="job_title">
                            <option value="0">اختر المسمى الوظيفى</option>
                            @foreach($jobs as $job)
                                <option value="{{$job->id}}">{{$job->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-4">
                        <label for="nationality" class="control-label mb-1">الجنسيه</label>
                        <select class="form-control py-2" style="height: 50px" name="nationality" id="nationality_id">
                            <option value="0">اختر الجنسيه</option>
                            @foreach($nationalitys as $nationality)
                                <option value="{{$nationality->id}}">{{$nationality->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-4">
                        <label for="type" class="control-label mb-1">التصنيفات</label>
                        <select class="form-control py-2" style="height: 50px" name="type" id="type_id">
                            <option value="0">اختر التصنيف</option>
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{$type->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-4">
                        <label for="duration" class="control-label mb-1">طبيعه الدوام</label>
                        <select class="form-control py-2" style="height: 55px" name="duration" id="duration_type">
                            <option value="0">اختر طبيعه الدوام</option>
                            <option value="online">عن بعد </option>
                            <option value="full_time">دوام كلى  </option>
                            <option value="part_time">دوام جزئى  </option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="salary_from" class="control-label mb-1">الراتب المتوقع({{ MyHelpers::admin_trans(auth()->user()->id,'From') }})</label>
                        <input name="salary_from" type="number" class="form-control" id="salary_from" autofocus placeholder="الراتب المتوقع({{ MyHelpers::admin_trans(auth()->user()->id,'From') }})">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="salary_to" class="control-label mb-1">الراتب المتوقع ({{ MyHelpers::admin_trans(auth()->user()->id,'To_') }})</label>
                        <input name="salary_to" type="number" class="form-control" id="salary_to" autofocus placeholder="الراتب المتوقع({{ MyHelpers::admin_trans(auth()->user()->id,'To_') }})">
                    </div>
                    
                    <div class="form-group col-6 col-md-4">
                        <label for="specialization" class="control-label mb-1">التخصص المرغوب</label>
                        <input type="text" class="form-control py-2" style="height: 50px" name="specialization" id="specialization">
                    </div>

                    <div class="form-group col-6 col-md-4">
                        <label for="need_traning"> ارغب فى تدريب جامعى</label>
                        <select class="form-control py-2" style="height: 45px" name="need_traning" id="need_traning">
                            <option value="">--</option>
                            <option value="0">لا</option>
                            <option value="1">نعم</option>
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

<div class="modal fade" style="background: rgba(238, 238, 238, 0.44) none repeat scroll 0% 0%" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form-data" class="p-5" method="POST">
                <div class="modal-body row">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <input type="hidden" id="id" required name="id">
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="start_date" class="col-form-label"> جزء من الإسم / الإيميل /رقم الجوال : </label>
                            <input type="text" class="form-control" id="searches" autofocus name="searches">
                            <span class="text-danger">
                                        <strong id="search-error"></strong>
                                    </span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="start_date" class="col-form-label"> تاريخ البداية : </label>
                            <input type="date" class="form-control" id="start_date" autofocus name="start_date">
                            <span class="text-danger">
                                        <strong id="start_date-error"></strong>
                                    </span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="end_date" class="col-form-label"> تاريخ النهاية : </label>
                            <input type="date" class="form-control" id="end_date" autofocus name="end_date">
                            <span class="text-danger">
                                        <strong id="end_date-error"></strong>
                                    </span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="from_salary" class="col-form-label"> الراتب من : </label>
                            <input type="number" min="0" class="form-control" id="from_salary" autofocus name="from_salary">
                            <span class="text-danger">
                                        <strong id="from_salary-error"></strong>
                                    </span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="to_salary" class="col-form-label"> الراتب إلى : </label>
                            <input type="number" min="0" class="form-control" id="to_salary" autofocus name="to_salary">
                            <span class="text-danger">
                                        <strong id="to_salary-error"></strong>
                                    </span>
                        </div>
                        <div class="col-lg-12">
                            <hr style="padding:0;margin:12px 0">
                        </div>
                        <div class="form-group col-lg-6">
                            <div class="form-check">
                                <input class="form-check-input" style="height:20px" name="has_request[]" type="checkbox" value="1" id="request">
                                <label class="form-check-label" style="margin-right: 8px;" for="request">
                                    لديه طلب سابق
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" style="height:20px" name="has_request[]" type="checkbox" value="0" id="norequest">
                                <label class="form-check-label" style="margin-right: 8px;" for="norequest">
                                    ليس لديه طلب سابق
                                </label>
                            </div>
                            <span class="text-danger">
                                         <strong id="has_request-error"></strong>
                                    </span>
                        </div>
                        {{--<div class="form-group col-lg-6">
                            <div class="form-check">
                                <input class="form-check-input" checked style="height:20px" name="status[]" type="checkbox" value="1" id="completed">
                                <label class="form-check-label" style="margin-right: 8px;" for="completed">
                                    تم إكمال الطلب
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" style="height:20px" name="status[]" type="checkbox" value="0" id="uncompleted">
                                <label class="form-check-label" style="margin-right: 8px;" for="uncompleted">
                                    لم يكمل الطلب
                                </label>
                            </div>
                            <span class="text-danger">
                             <strong id="status-error"></strong>
                        </span>
                        </div>--}}
                        <div class="col-lg-12">
                            <hr style="padding:0;margin:12px 0">
                        </div>
                        <div class="form-group col-lg-12">
                            <h6><b>تحديد جهه العمل</b></h6>
                            <span class="text-danger">
                                        <strong id="works-error"></strong>
                                    </span>
                            <div class="row pr-3 pl-3">
                                <div class="form-check col-lg-12">
                                    <input class="form-check-input" style="height:20px" type="checkbox" value="" id="checkAllWorks">
                                    <label class="form-check-label" style="margin-right: 8px;" for="checkAllWorks">
                                        تحديد الكل
                                    </label>
                                </div>
                                @foreach($works as $work)
                                    <div class="form-check col-lg-4 works">
                                        <input class="form-check-input work" onclick="check()" name="works[]" style="height:20px" type="checkbox" value="{{$work->value}}" id="work{{$work->id}}">
                                        <label class="form-check-label" style="margin-right: 8px;" for="work{{$work->id}}">
                                            {{$work->value}}
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="form-group col-lg-12" id="militiaryService" style="display:none">
                            <hr style="padding:0;margin:12px 0">

                            <h6><b>تحديد الرتبه </b></h6>
                            <span class="text-danger">
                            <strong id="ranks-error"></strong>
                        </span>
                            <div class="row pr-3 pl-3">
                                <div class="form-check col-lg-12">
                                    <input class="form-check-input" style="height:20px" type="checkbox" value="" id="checkAllMillitary">
                                    <label class="form-check-label" style="margin-right: 8px;" for="checkAllMillitary">
                                        تحديد الكل
                                    </label>
                                </div>
                                @foreach($militaries as $military)
                                    <div class="form-check col-lg-4 military">
                                        <input class="form-check-input" name="ranks[]" style="height:20px" type="checkbox" value="{{$military->value}}" id="military{{$military->id}}">
                                        <label class="form-check-label" style="margin-right: 8px;" for="military{{$military->id}}">
                                            {{$military->value}}
                                        </label>
                                    </div>
                                @endforeach

                            </div>

                        </div>

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

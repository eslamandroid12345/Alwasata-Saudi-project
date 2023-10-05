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

                    <div class="form-group col-4 col-md-4">
                        <label for="sex" class="control-label mb-1">مديري المبيعات</label>
                        <select class="form-control py-2" style="height: 60px" id="sales-managers">
                            <option value="">--</option>
                            @if (isset($salesManagers))
                                @foreach($salesManagers as $salesManager)
                                    <option value="{{ $salesManager->id }}" >{{ $salesManager->name }}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>

                    <div class="form-group col-4 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="tokenizeable form-control" id="agents_ids" multiple>
                            @foreach($salesAgents as $salesAgent)
                                <option value="{{ $salesAgent->id }}"  @if ( Session::get('agents_ids') != null) {{ in_array( $salesAgent->id, Session::get('agents_ids')) ? 'selected' : '' }}  @endif>{{ $salesAgent->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-6 col-md-4">
                        <label for="user_status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</label>
                        <select class="form-control py-2" style="height: 60px" name="user_status"  id="user_status">
                            <option value="2" selected>الكل</option>
                            <option value="1">نشط</option>
                            <option value="0">مؤرشف</option>
                        </select>
                    </div>

                    <div class="form-group col-6 col-md-4">
                        <label for="type_of_classification" class="control-label mb-1">جميع التصنيفات</label>
                        <select class="form-control py-2" style="height: 60px" name="type_of_classification"  id="type_of_classification">
                            <option value="" selected>جميع التصنيفات</option>
                            <option value="1" @if ( Session::get('type_of_classification') != null) {{ Session::get('type_of_classification') == '1' ? 'selected' : '' }}  @endif>ايجابي</option>
                            <option value="0" @if ( Session::get('type_of_classification') != null) {{ Session::get('type_of_classification') == '0' ? 'selected' : '' }}  @endif>سلبي</option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of sales agent') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_sa" multiple>
                            @foreach($classifcations_sa as $classifcation)
                                <option value="{{ $classifcation->id }}" @if ( Session::get('class_id_agent') != null) {{ in_array( $classifcation->id, Session::get('class_id_agent')) ? 'selected' : '' }}  @endif>{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of sales manager') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_sm" multiple>
                            @foreach($classifcations_sm as $classifcation)
                                <option value="{{ $classifcation->id }}" @if ( Session::get('class_id_sm') != null) {{ in_array( $classifcation->id, Session::get('class_id_sm')) ? 'selected' : '' }}  @endif>{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of funding manager') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_fm" multiple>
                            @foreach($classifcations_fm as $classifcation)
                                <option value="{{ $classifcation->id }}"  @if ( Session::get('class_id_fm') != null) {{ in_array( $classifcation->id, Session::get('class_id_fm')) ? 'selected' : '' }}  @endif>{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of mortgage manager') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_mm" multiple>
                            @foreach($classifcations_mm as $classifcation)
                                <option value="{{ $classifcation->id }}" @if ( Session::get('class_id_mm') != null) {{ in_array( $classifcation->id, Session::get('class_id_mm')) ? 'selected' : '' }}  @endif>{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of general manager') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_gm" multiple>
                            @foreach($classifcations_gm as $classifcation)
                                <option value="{{ $classifcation->id }}" @if ( Session::get('class_id_gm') != null) {{ in_array( $classifcation->id, Session::get('class_id_gm')) ? 'selected' : '' }}  @endif>{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="form-group col-12 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'From') }})</label>
                        <input name="name" type="text" value="{{ (Session::get('customer_salary') != null) ?  Session::get('customer_salary') : '' }}" class="form-control" autocomplete="name" id="customer-salary" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }}">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'To_') }})</label>
                        <input name="name" type="text" value="{{ (Session::get('customer_salary_to') != null) ?  Session::get('customer_salary_to') : '' }}" class="form-control" autocomplete="name" id="customer-salary-to" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'customer_salary') }}">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</label>
                        <select class="form-control tokenizeable" id="request_status" multiple>
                        @foreach($all_status as $key => $status)

                            @if ($key != "5" && $key != "18" && $key != "20" && $key != "22" && $key != "21" && $key != "8" && $key != "25" && $key != "26" && $key != "27" && $key != "23" && $key != "11" && $key != "14")
                                <!-- Remove dublicate-->
                                    <option value="{{ $key }}"  @if ( Session::get('req_status') != null) {{ in_array(  $key, Session::get('req_status')) ? 'selected' : '' }}  @endif>{{ $status }}</option>
                                @endif

                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</label>
                        <select class="form-control tokenizeable" id="source" multiple>
                            @foreach ($request_sources as $request_source )
                                <option value="{{$request_source->id}}"  @if ( Session::get('source') != null) {{ in_array( $request_source->id, Session::get('source')) ? 'selected' : '' }}  @endif>{{$request_source->value}}</option>
                            @endforeach
                        </select>
                    </div>



                    <div id="collaboratorDiv" style="display:none;" class="form-group col-12 col-md-12">
                        <label for="collaborator" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'collaborator name') }}</label>

                        <select id="collaborator" class="form-control tokenizeable" multiple>

                            @if (!empty($collaborators[0]))

                                @foreach ($collaborators as $collaborator )
                                    <option value="{{$collaborator->id}}" @if ( Session::get('collaborator') != null) {{ in_array( $collaborator->id, Session::get('collaborator')) ? 'selected' : '' }}  @endif>{{$collaborator->name}}</option>
                                @endforeach

                            @else
                                <option disabled="disabled" value="">{{ MyHelpers::admin_trans(auth()->user()->id,'No Collaborator') }}</option>
                            @endif

                        </select>

                        @error('collaborator')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>



                    <div class="form-group col-12 col-md-4">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}</label>
                        <input name="name" type="text"  value="{{ (Session::get('customer_phone') != null) ?  Session::get('customer_phone') : '' }}" class="form-control" autocomplete="name" id="customer-phone" autofocus placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'phone') }}">
                    </div>


                    <div class="col-4">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="customer-birth" style="text-align: right" name="birth" type="date" value="{{ (Session::get('customer_birth') != null) ?  Session::get('customer_birth') : '' }}"  class="form-control" autocomplete="birth" autofocus>
                            </div>
                        </div>
                    </div>



                    <div class="form-group col-12 col-md-4">
                        <label for="notes_status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'notes_status') }}</label>
                        <select class="form-control tokenizeable" id="notes_status" multiple>
                            <option value="0" @if ( Session::get('notes_status') != null) {{ Session::get('notes_status') == '0' ? 'selected' : '' }}  @endif>الفارغة</option>
                            <option value="1" @if ( Session::get('notes_status') != null) {{ Session::get('notes_status') == '1' ? 'selected' : '' }}  @endif>تحتوي على محتوى</option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="notes_status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'app_downloaded') }}</label>
                        <select class="form-control tokenizeable" id="app_downloaded" multiple>
                            <option value="0" @if ( Session::get('app_downloaded') != null) {{ Session::get('app_downloaded') == '0' ? 'selected' : '' }}  @endif>لا</option>
                            <option value="1" @if ( Session::get('app_downloaded') != null) {{ Session::get('app_downloaded') == '1' ? 'selected' : '' }}  @endif>نعم</option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="region_ip" class="control-label mb-1">منطقة العميل</label>
                        <select class="form-control tokenizeable" id="region_ip" multiple>
                            @foreach($regions as $region_ip)
                                <option value="{{ $region_ip->region_ip }}" @if ( Session::get('region_ip') != null) {{ in_array($region_ip->region_ip, Session::get('region_ip')) ? 'selected' : '' }}  @endif>{{ $region_ip->region_ip }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'request_type') }}</label>
                        <select class="form-control tokenizeable" id="request_type" multiple>
                            <option value="رهن-شراء"  @if ( Session::get('reqTypes') != null) {{ Session::get('reqTypes') == 'رهن-شراء' ? 'selected' : '' }}  @endif>رهن-شراء</option>
                            <option value="شراء-دفعة" @if ( Session::get('reqTypes') != null) {{ Session::get('reqTypes') == 'شراء-دفعة' ? 'selected' : '' }}  @endif>شراء-دفعة</option>
                            <option value="شراء"  @if ( Session::get('reqTypes') != null) {{ Session::get('reqTypes') == 'شراء' ? 'selected' : '' }}  @endif>شراء</option>
                            <option value="رهن"  @if ( Session::get('reqTypes') != null) {{ Session::get('reqTypes') == 'رهن' ? 'selected' : '' }}  @endif>رهن</option>
                            <option value="تساهيل" @if ( Session::get('reqTypes') != null) {{ Session::get('reqTypes') == 'تساهيل' ? 'selected' : '' }}  @endif>تساهيل</option>
                        </select>
                    </div>




                    <div id="paystatusDiv" style="display:none;" class="form-group col-12 col-md-12">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'pay status') }}</label>
                        <select class="form-control tokenizeable" id="pay_status" multiple>
                            @foreach($pay_status as $key => $status)
                                <option value="{{ $key }}" @if ( Session::get('pay_status') != null) {{ in_array($key, Session::get('pay_status')) ? 'selected' : '' }}  @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'from req date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="request-date-from" style="text-align: right" name="request-date-from" type="date" value="{{ (Session::get('req_date_from') != null) ?  Session::get('req_date_from') : '' }}"  class="form-control" autocomplete="birth" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'to req date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="request-date-to" style="text-align: right" name="request-date-to" type="date" value="{{ (Session::get('req_date_to') != null) ?  Session::get('req_date_to') : '' }}" class="form-control" autocomplete="birth" autofocus>
                            </div>
                        </div>
                    </div>



                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'from complete date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="complete-date-from" style="text-align: right" name="complete-date-from" type="date"  value="{{ (Session::get('complete_date_from') != null) ?  Session::get('complete_date_from') : '' }}" class="form-control" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'to complete date') }}</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="complete-date-to" style="text-align: right" name="complete-date-to" type="date"  value="{{ (Session::get('complete_date_to') != null) ?  Session::get('complete_date_to') : '' }}"  class="form-control" autofocus>
                            </div>
                        </div>
                    </div>


                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">تاريخ التحديث من</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="updated_at_from" style="text-align: right" name="updated_at_from" value="{{ (Session::get('updated_at_from') != null) ?  Session::get('updated_at_from') : '' }}" type="datetime-local" class="form-control" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="birth" class="control-label mb-1">تاريخ التحديث إلي</label>
                        <div class="input-group form-group">
                            <div class="input-group">
                                <input id="updated_at_to" style="text-align: right" name="updated_at_to" type="datetime-local"   value="{{ (Session::get('updated_at_to') != null) ?  Session::get('updated_at_to') : '' }}"  class="form-control" autofocus>
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="work_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work_source') }}</label>
                        <select class="form-control tokenizeable" id="work_source" multiple>

                            @foreach ($worke_sources as $worke_source )
                                <option value="{{$worke_source->id}}" @if ( Session::get('work_source') != null) {{ in_array($worke_source->id, Session::get('work_source')) ? 'selected' : '' }}  @endif>{{$worke_source->value}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary_source') }}</label>
                        <select class="form-control tokenizeable" id="salary_source" multiple>
                            @foreach($all_salaries as $salary)
                                <option value="{{ $salary->id }}"  @if ( Session::get('salary_source') != null) {{ in_array($salary->id, Session::get('salary_source')) ? 'selected' : '' }}  @endif>{{ $salary->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="founding_sources" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'founding_sources') }}</label>
                        <select class="form-control tokenizeable" id="founding_sources" multiple>
                            @foreach($founding_sources as $founding_source)
                                <option value="{{ $founding_source->id }}" @if ( Session::get('founding_sources') != null) {{ in_array( $founding_source->id, Session::get('founding_sources')) ? 'selected' : '' }}  @endif>{{ $founding_source->value }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-12 col-md-12" style="border-top: 6px solid  #002b80">
                         <b>الجودة</b>  --------------------------------------------------------------------------------
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">الجودة</label>
                        <select class="form-control py-2 tokenizeable" style="height: 60px" id="quality-users">
                            <option value="">--</option>
                            @if (isset($qualityUsers))
                                @foreach($qualityUsers as $qualityUser)
                                    <option value="{{ $qualityUser->id }}"  @if ( Session::get('quality_users') != null) {{ in_array(  $qualityUser->id, Session::get('quality_users')) ? 'selected' : '' }}  @endif>{{ $qualityUser->name_for_admin != null ? $qualityUser->name_for_admin :  $qualityUser->name }}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="quality_recived" class="control-label mb-1">استلامه من قبل الجودة</label>
                        <select class="form-control tokenizeable" id="quality_recived" multiple>
                            <option value="yes" @if ( Session::get('quality_recived') != null) {{ Session::get('quality_recived') == 1 ? 'selected' : '' }}  @endif>نعم</option>
                            <option value="no" @if ( Session::get('quality_recived') != null) {{ Session::get('quality_recived') == 2 ? 'selected' : '' }}  @endif>لا</option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-4">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classification of quality') }}</label>
                        <select class="tokenizeable form-control" id="classifcation_qu" multiple>)
                            @foreach($classifcations_qu as $classifcation)
                                <option value="{{ $classifcation->id }}" @if ( Session::get('class_id_quality') != null) {{ in_array( $classifcation->id, Session::get('class_id_quality')) ? 'selected' : '' }}  @endif>{{ $classifcation->value }}</option>
                            @endforeach
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto d-none" id="session_remove_button" >حذف الفلتر</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="filter-search-req">{{ MyHelpers::admin_trans(auth()->user()->id,'Search') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>

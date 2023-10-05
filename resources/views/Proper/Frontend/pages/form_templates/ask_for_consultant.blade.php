<div class="ask-conslutant consultancy_request_wrapper">

    @if($page->ar_heading)
    <div class="topcontent">
        <h2 style="font-weight: bold; font-size: larger;">{!! $page->ar_heading !!}</h2>
    </div>
    @endif
    <div class="whiteBx">
        <div class="form-container">
            <div class="formInner-container">
                @if($page->en_sub_heading)
                <div class="topcontent">
                    <p>{!! $page->ar_sub_heading !!}</p>
                </div>
                @endif
                <div class="clearfix"></div>
                <form dir="rtl">
                    <p class="message-box alert"></p>
                    {{ csrf_field() }}
                    <input type="hidden" name="source" value="8" />
                    <div class="formRow">
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_name') == 'show')
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12">
                            <div class="form-group">
                                <label for="name">{{ MyHelpers::guest_trans('Full name') }}<small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small></label>
                                <div class="col-md-12">
                                    <input type="text" name="name" class="form-control" placeholder="{{ MyHelpers::guest_trans('Full name') }}">
                                    <span class="text-danger" style="color:red;" id="nameError" role="alert"> </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>


                    <div class="formRow">
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_birthDate') == 'show')
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="display: none;" id="birth_gerous">
                            <div class="form-group">
                                <label for="birth_date">{{ MyHelpers::guest_trans('birth date') }} </label>
                                <div class="col-md-12">
                                    <input type="date" id="birth" name="birth_date" class="form-control" placeholder="Birthday">
                                </div>
                            </div>
                        </div>
                        @endif

                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_birthDate') == 'show')
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="birth_hijri">
                            <div class="form-group">
                                <label for="birth_date">{{ MyHelpers::guest_trans('birth date') }} </label>
                                <div class="col-md-12">
                                    <input type='text' name="birth_hijri" style="text-align: right;" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                                </div>
                            </div>
                        </div>
                        @endif

                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_birthDate') == 'show')
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="birth_date">{{ MyHelpers::guest_trans('birth date type') }} </label>
                                <div class="col-md-12">

                                    <select class="form-control @error('birthType') is-invalid @enderror" onchange=' check(this);' id="birthType" name="birth_date_type">

                                        <option value="هجري" selected>هجري</option>
                                        <option value="ميلادي">ميلادي</option>

                                    </select>

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="formRow">
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_work') == 'show')
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="work" class="control-label mb-1">{{ MyHelpers::guest_trans('work') }}

                                    @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_work') != null )
                                    <small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small>
                                    @endif

                                </label>

                                <select class="form-control @error('work') is-invalid @enderror" name="work">

                                    <option value="">---</option>
                                    <option value="عسكري">عسكري</option>
                                    <option value="مدني">مدني</option>
                                    <option value="متقاعد">متقاعد</option>
                                    <option value="شبه حكومي">شبه حكومي</option>
                                    <option value="قطاع خاص">قطاع خاص</option>
                                    <option value="قطاع خاص غير معتمد">قطاع خاص غير معتمد</option>
                                    <option value="قطاع خاص معتمد">قطاع خاص معتمد</option>





                                </select>
                                <span class="text-danger" style="color:red;" id="workError" role="alert"> </span>

                            </div>

                        </div>
                        @endif
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_mobile') == 'show')
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="mobile">{{ MyHelpers::guest_trans('mobile') }}<small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small></label>
                                <div class="col-md-12">
                                    <input id="mobile" name="mobile" type="number" class="form-control phone @error('mobile') is-invalid @enderror" autocomplete="mobile" placeholder="5xxxxxxxxx">
                                    <span class="text-danger" style="color:red;" id="mobileError" role="alert"> </span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="formRow">
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_salary') == 'show')
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="salary">{{ MyHelpers::guest_trans('salary') }}
                                    @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_from_salary') != null || App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_salary') != null )
                                    <small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small>
                                    @endif
                                </label>
                                <div class="col-md-12">
                                    <input type="text" name="salary" class="form-control" placeholder="{{ MyHelpers::guest_trans('salary') }}">
                                    <span class="text-danger" style="color:red;" id="salaryError" role="alert"> </span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_salaryId') == 'show')
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="salary_id">{{ MyHelpers::guest_trans('salary source') }}</label>
                                <div class="col-md-12">
                                    @php
                                    $sources = \DB::table('salary_sources')->get();
                                    @endphp
                                    <select name="salary_id" class="form-control">
                                        <option value="" disabled selected>{{ MyHelpers::guest_trans('salary source') }} *</option>
                                        @foreach($sources as $source)
                                        <option value="{{$source->id}}">{{@$source->value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>


                    <br><br>

                    <div class="formRow" style="text-align: center;">
                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_isSupported') == 'show')

                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_obligations') == 'show' && App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_financial_distress') == 'show')
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            @elseif( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_obligations') == 'show' || App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_financial_distress') == 'show')
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                @else
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    @endif


                                    <label for="supported" class="control-label mb-1">{{ MyHelpers::guest_trans('are you belong to supported') }}

                                        @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_support') != null)
                                        <small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small>
                                        @endif

                                    </label>

                                    <select class="form-control @error('is_supported') is-invalid @enderror" name="is_supported">

                                        <option value="" selected>---</option>
                                        <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                        <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                    </select>
                                    <span class="text-danger" style="color:red;" id="is_supportedError" role="alert"> </span>

                                </div>
                                @endif

                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_obligations') == 'show')

                                @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_isSupported') == 'show' && App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_financial_distress') == 'show')
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    @elseif( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_isSupported') == 'show' || App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_financial_distress') == 'show')
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        @else
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            @endif

                                            <label for="has_obligations" class="control-label mb-1">{{ MyHelpers::guest_trans('has you obligations') }}

                                                @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_has_obligations') != null)
                                                <small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small>
                                                @endif

                                            </label>

                                            <select class="form-control @error('has_obligations') is-invalid @enderror" name="has_obligations">

                                                <option value="" selected>---</option>
                                                <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                            </select>
                                            <span class="text-danger" style="color:red;" id="has_obligationsError" role="alert"> </span>

                                        </div>


                                        @endif

                                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_financial_distress') == 'show')

                                        @if( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_obligations') == 'show' && App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_isSupported') == 'show')
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                            @elseif( App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_has_obligations') == 'show' || App\Http\Controllers\SettingsController::getOptionValue('askforconsultant_isSupported') == 'show')
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                @else
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    @endif

                                                    <label for="has_financial_distress" class="control-label mb-1">{{ MyHelpers::guest_trans('has you financial distress') }}
                                                        @if( App\Http\Controllers\SettingsController::getValidationValue('request_validation_to_has_financial_distress') != null )
                                                        <small>({{ MyHelpers::guest_trans('Mandatory') }})</small><small style="color:red;">*</small>
                                                        @endif
                                                    </label>
                                                    <select class="form-control @error('has_financial_distress') is-invalid @enderror" name="has_financial_distress">

                                                        <option value="" selected>---</option>
                                                        <option value="yes">{{ MyHelpers::guest_trans('Yes') }}</option>
                                                        <option value="no">{{ MyHelpers::guest_trans('No') }}</option>

                                                    </select>
                                                    <span class="text-danger" style="color:red;" id="has_financial_distressError" role="alert"> </span>

                                                </div>

                                                @endif
                                            </div>

                                            <br><br>

                </form>
                <div class="formRow">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="sumitbtn">
                            <button id="saveBtn" class="srchbtn" type="button">{{ MyHelpers::guest_trans('Send') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function() {
        $("#hijri-date").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        var today = new Date().toISOString().split("T")[0];
        $('#birth').attr("max", today);
    });


    function check(that) {

        if (that.value == "ميلادي") {

            document.getElementById("birth_gerous").style.display = "block";

            document.getElementById("hijri-date").value = "";
            document.getElementById("birth_hijri").style.display = "none";

        } else {
            document.getElementById("birth_hijri").style.display = "block";

            document.getElementById("birth_gerous").style.display = "none";
            document.getElementById("birth").value = "";

        }
    }

    jQuery(function(e) {

        $(document).on('click', '#saveBtn', function(e) {
            e.preventDefault();
            var loader = $('.message-box');
            $('#nameError').addClass("d-none");
            $('#mobileError').addClass("d-none");
            var btn = $(this);

            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: "{{ route('frontend.page.consultancy_request') }}",
                data: $('.consultancy_request_wrapper form').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading Send') }}").removeClass('hide alert-danger').addClass('alert-success');
                },

                error: function(data) {


                    if (data.status == 2) {
                        loader.html('').removeClass('alert-danger').addClass('hide');
                        $('.consultancy_request_wrapper form')[0].reset();
                        let slug = "{{ route('duplicateCustomer') }}/" + data.request.searching_id;
                        window.location.replace(slug);
                    } 
                
                        loader.html('').removeClass('alert-success');

                        btn.attr('disabled', false);

                        var errors = data.responseJSON;

                        if ($.isEmptyObject(errors) == false) {

                            $.each(errors.errors, function(key, value) {

                                var ErrorID = '#' + key + 'Error';
                                $(ErrorID).removeClass("d-none");
                                $(ErrorID).text(value);

                            })

                        }
                   

                },

                success: function(data) {

                    btn.attr('disabled', false);
                    if (data.status == 1) {
                        loader.html(data.msg).removeClass('alert-danger').addClass('hide');
                        $('.consultancy_request_wrapper form')[0].reset();
                        let slug = "{{ route('thankyou') }}/" + data.data.id;
                        window.location.replace(slug);
                    } else if (data.status == 2) {
                        loader.html(data.msg).removeClass('alert-danger').addClass('hide');
                        $('.consultancy_request_wrapper form')[0].reset();
                        let slug = "{{ route('duplicateCustomer') }}/" + data.request.searching_id;
                        window.location.replace(slug);
                    } else {
                        var message = formatErrorMessageFromJSON(data.errors);
                        loader.html(message).removeClass('alert-success').addClass('alert-danger');
                    }

                }
            });
        });
    });
</script>
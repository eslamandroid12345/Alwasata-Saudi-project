@extends('Customer.fundingReq.customerReqLayout')

@section('title') {{ MyHelpers::guest_trans('Alwasata') }} @endsection

@section('content')
    <style>


        /* Create two unequal columns that floats next to each other */
        /* Left column */
        .leftcolumn {
            float: right;
            width: 75%;
        }

        /* Right column */
        .rightcolumn {
            float: right;
            width: 25%;
            padding-left: 20px;
        }

        /* Fake image */
        .fakeimg {
            background-color: #aaa;
            width: 100%;
            padding: 20px;
        }

        /* Add a card effect for articles */
        .card {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            background: #ddd;
            margin-top: 20px;
        }

        /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 800px) {
            .leftcolumn, .rightcolumn {
                width: 100%;
                padding: 0;
            }
        }
    </style>




    <section class=" wow fadeInUp">
        <div class="container">
            <div class="row" dir="rtl">

                <div class="col-xs-12 col-md-12 col-lg-12 service-heading wow fadeInUp">
                    <br>
                    <h3 class="pb-2 pt-15">تفاصيل العقار</h3>
                </div>
                @if(!empty($property))
                    <div class="wow fadeInUp">
                        <div class="row">
                            <div class="col-lg-8 pull-right">
                                <div class="card">
                                    {{--                                    <h2>TITLE HEADING</h2>--}}
                                    <h5>{{  \Carbon\Carbon::createFromTimeStamp(strtotime($property->created_at))->locale('ar_AR')->diffForHumans() }}</h5>
                                    <div class="p-3"><img width="100%" height="500px"
                                              src="{{asset($property->image()->first()->image_path)}}"></div>

                                    <div class="pt-5">
                                        <table width="750" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td> اضف بواسطة: <a href="#"> {{ @$property->creator->name}}</a></td>
                                                <td>
                                                    السعر:{{$property->price_type === 'fixed' ?$property->fixed_price : $property->max_price . ' -- ' . $property->min_price}}
                                                    / {{ MyHelpers::guest_trans('sar') }}</td>
                                            </tr>
                                            <tr>
                                                <td>المدينة: {{$property->city->value}}</td>
                                                <td>المنطقة:{{$property->region}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">العنوان:{{$property->address}}</td>
                                            </tr>
                                            <tr>
                                                <td> {{ MyHelpers::guest_trans('property num of rooms') }}
                                                    : {{$property->num_of_rooms}} </td>
                                                <td> {{ MyHelpers::guest_trans('property num of salons') }}
                                                    : {{$property->num_of_salons}}</td>
                                            </tr>
                                            <tr>
                                                <td> {{ MyHelpers::guest_trans('property num of kitchens') }}
                                                    : {{$property->num_of_kitchens}} </td>
                                                <td> {{ MyHelpers::guest_trans('property num of bathrooms') }}
                                                    : {{$property->num_of_bathrooms}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <p>  {!! $property->description !!}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                @if(auth()->guard('customer')->check())
                                    <div class="card">
                                        <form class="col-12" action="{{route('requestProperty')}}" method="post" autocomplete="off">
                                            @csrf
                                            <input type="hidden" name="property_id" value="{{$property->id}}">
                                            @if(! auth()->guard('customer')->check())
                                                <div class="form-group">
                                                    <input type="email" class="form-control" name="email" id="email"
                                                           aria-describedby="" autocomplete="false"
                                                           placeholder="{{ MyHelpers::guest_trans('Email') }}">
                                                    <span class="help-block col-md-12">
                                                <strong class="text-danger" style="color:red" id="emailRequestError"
                                                        role="alert"></strong>
                                            </span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="name" id="username"
                                                           aria-describedby="" autocomplete="false"
                                                           placeholder="{{ MyHelpers::guest_trans('name') }}">
                                                    <span class="help-block col-md-12">
                                                <strong class="text-danger" style="color:red" id="nameRequestError"
                                                        role="alert"></strong>
                                            </span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" name="mobile_num" id="mobile_num"
                                                           aria-describedby="" autocomplete="false"
                                                           onchange="checkCustomerMobile(this.value)"
                                                           onkeypress="return isNumber(event)" maxlength="9"
                                                           onsubmit="checkCustomerMobile(this.value)"
                                                           placeholder="{{ MyHelpers::guest_trans('Mobile') }}" >
                                                    <span class="help-block col-md-12">
                                                <strong class="text-danger" style="color:red"
                                                        id="mobile_numRequestError" role="alert"></strong>
                                            </span>
                                                    <div class="mobile-check-filter">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" class="form-control" name="password" id="password"
                                                           autocomplete="false"
                                                           placeholder="{{ MyHelpers::guest_trans('Password') }}">
                                                    <span class="help-block col-md-12">
                                                <strong class="text-danger" style="color:red" id="passwordRequestError"
                                                        role="alert"></strong>
                                            </span>
                                                </div>
                                            @else
                                                <input type="hidden" name="customer_id"
                                                       value="{{auth()->guard('customer')->id()}}">
                                            @endif
                                            <button type="submit"
                                                    class="btn btn-primary btn-block btn-send-request">{{ MyHelpers::guest_trans('Request Property') }}</button>
                                        </form>
                                    </div>
                                @endif
                                @if(sizeof($property->image) > 1)
                                <div class="card">
                                    <h3 class="text-center">صور اخرى</h3>
                                    <br>
                                    @foreach($property->image as $img)
                                        @if (! $loop->first)
                                        <div class="img img-thumbnail">
                                            <a data-toggle="modal" data-target="#zoom_image" data-id="{{$img->id}}" data-img="{{$img->image_path}}" href="">
                                                <img width="320" height="200" src="{{asset($img->image_path)}}" id="image_{{$img->id}}" >
                                            </a>
                                        </div>
                                        <br>
                                        <br>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>


                @endif

            </div>
            <div class="row">
                <div class="col-lg-12 pt-5">
                    <div id="map" style="height:400px;background:grey"></div>
                </div>
            </div>
        </div>
    </section>


    <!--begin::Modal-->
    <div id="zoom_image"  class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" style="margin-top: 60px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::guest_trans('Zoom') }} {{ MyHelpers::guest_trans('image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img class="modal-content" id="zoomImage" style="display: block ;width: 100%">
                </div>
            </div>
        </div>
    </div>


    <!--end::Modal-->


@endsection

@section('scripts')
<script>
    /////////////////////////////////////////////////////////////////
    $('#zoom_image').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        let id = button.data('id'); // Extract info from data-* attributes
        /* SET DATA TO MODAL */
        console.log(id);
        var imgID = 'image_'+id ;
        // Get the modal
        var modal = document.getElementById("zoom_image");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById(imgID);
        var modalImg = document.getElementById("zoomImage");
        img.onclick = function(){

        }
        modal.style.display = "block";
        modalImg.src = img.src;

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
    });
    /////////////////////////////////////////////////////////////////

</script>

    <script type="text/javascript">
        $(document).ready(function () {

            var today = new Date().toISOString().split("T")[0];
            $('#birth').attr("max", today);
        });

        jQuery(function (e) {
            function customInitTooltip(target, step) {
                var tooltip = $(`<div id="${target}_tooltip" class="slider-tooltip" />`).css({
                    position: 'absolute',
                    top: 0,
                    left: 40
                }).hide();
                var value = $(`#${target}_text`).val();
                if (step == 1) {
                    var range = 0;
                    var sym = ',';
                } else {
                    var range = 1;
                    var sym = '.';
                }
                value = number_format(value, range, sym, ',');
                tooltip.text(value);
                return tooltip;
            }

            function customInitSlider(target, min, max, value, step) {
                $(`#${target}_text`).val(value);
                var tooltip = customInitTooltip(target, step);
                setMinMaxValidation(`#${target}_text`, min, max);

                $(`#${target}`).slider({
                    range: "max",
                    min: min,
                    step: step,
                    max: max,
                    value: value,
                    slide: function (event, ui) {

                        $(`#${target}_text`).val(+ui.value);

                        var val = $(`#${target}_text`).val();
                        if (step == 1) {
                            var range = 0;
                            var sym = ',';
                        } else {
                            var range = 1;
                            var sym = '.';
                        }
                        val = number_format(val, range, sym, ',');

                        tooltip.text(val);
                        $(ui.value).val(val);
                    },
                    change: function (event, ui) {
                    }
                }).find(".ui-slider-handle").append(tooltip).hover(function () {
                    tooltip.show()
                }, function () {
                    tooltip.hide()
                })
                $(`#${target}_text`).keyup(function () {
                    $(`#${target}`).slider("value", $(this).val())
                });
            }

            customInitSlider('property_value_slider', 0, 10000000, 5000000, 1);
            customInitSlider('monthly_salary_slider', 0, 50000, 25000, 1);
            customInitSlider('funding_duration_slider', 0, 30, 15, 1);
            customInitSlider('annual_interest_slider', 0, 10, 5, 0.10);
            customInitSlider('first_batch_slider', 0, 2000000, 1000000, 1);

            function number_format(number, decimals, dec_point, thousands_sep) {
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }

            $(document).on('click', '#inquirySaveBtn', function (e) {
                e.preventDefault();
                var loader = $('.inquiry-message-box');
                var btn = $(this);
                $('p.err_msg').remove();
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    url: "#",
                    data: $('#inquiry_form').serialize(),
                    beforeSend: function () {
                        btn.attr('disabled', true);
                        loader.html('<i class="fa fa-spinner fa-spin fa-lg"></i>').removeClass('hide alert-danger').addClass('alert-success');
                    },
                    error: function (jqXHR, exception) {
                        btn.attr('disabled', false);

                        var msg = formatErrorMessage(jqXHR, exception);
                        loader.html(msg).removeClass('alert-success').addClass('alert-danger');
                    },
                    success: function (data) {
                        btn.attr('disabled', false);
                        if (data.status == 1) {
                            loader.html('').addClass('hide');
                            $('#inquiry_form')[0].reset();
                            $('.registerFormInner').prepend('<p class="err_msg">' + data.msg + '</p>');
                        } else {
                            var message = formatErrorMessageFromJSON(data.errors);
                            loader.html(message).removeClass('alert-success').addClass('alert-danger');
                        }

                    }
                });
            });

            $('.partnerList').slick({
                dots: false,
                infinite: true,
                autoplay: true,
                arrows: false,
                speed: 150,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }

                ]
            });

            $(document).on('click', '#calculate_fund', function (e) {
                e.preventDefault();
                var btn = $('#loanRequestBtn');

                // calculate the monthly payment:
                var user_salary = $('[name="salary"]').val();

                var monthly_payment = 0;
                if (user_salary >= 5000 && user_salary <= 10000) {
                    monthly_payment = user_salary * .55;
                } else if (user_salary > 10000 && user_salary <= 15000) {
                    monthly_payment = user_salary * .6;
                } else if (user_salary > 15000) {
                    monthly_payment = user_salary * .65;
                }

                monthly_payment = monthly_payment.toString();
                if (monthly_payment.indexOf('.') > -1) {
                    monthly_payment = monthly_payment.substring(0, monthly_payment.indexOf('.') + 4);
                }
                monthly_payment = parseInt(monthly_payment).toFixed(0);

                var disp_monthly_payment = number_format(monthly_payment, 0, ',', ',');
                $("#monthly_payment_calculated").html(disp_monthly_payment);


                // now caculate the fund amount:
                var years = $('[name="funding_duration"]').val();
                var months = years * 12;
                var ratio = $('[name="annual_interest"]').val();

                var makam = ratio * years;

                makam = makam.toString();
                if (makam.indexOf('.') > -1) {
                    makam = makam.replace('.', '');
                }

                makam = '1.' + makam;

                var bast = months * monthly_payment;

                var fund = bast / makam;
                fund = fund.toString();

                if (fund.indexOf('.') > -1) {
                    fund = fund.substring(0, fund.indexOf('.') + 4);
                }
                fund = parseInt(fund).toFixed(0);
                var disp_fund = number_format(fund, 0, ',', ',');
                $("#payment_calculated").html(disp_fund);

                if (monthly_payment != 0 && fund != 0) {
                    btn.prop('disabled', false);
                }
            });

            $(document).on('focus', '.positive-integer, .positive-decimal', function () {
                $(this).val('');
            });

            $(document).on('focusout', '.positive-integer, .positive-decimal', function (e) {
                var fieldName = $(this).attr('name');
                let value = $.trim($(`#${fieldName}_slider_tooltip`).text()).replace(/,/g, '');
                if ($(this).val() == '') {
                    $(this).val(value);
                }
            });

            $(document).on('click', '#loanRequestBtn', function (e) {
                e.preventDefault();
                var loader = $('.message-box');
                var actualBtn = $(this).clone();
                var btn = $(this);

                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    url: "{{ route('frontend.page.save_loan_request') }}",
                    data: $('#calculator-customer-form').serialize(),
                    beforeSend: function () {
                        $('.registerFormInner').find('p.err_msg').remove();
                        btn.attr('disabled', true).html('<center><i class="fa fa-spinner fa-spin fa-2x"></i></center>');
                    },
                    error: function (jqXHR, exception) {

                        btn.replaceWith(actualBtn);
                        var msg = formatErrorMessage(jqXHR, exception);
                        msg = msg.replace(/<p>/g, '<p class="err_msg">');
                        $('.calculator-form .registerFormInner').append(msg);
                    },


                    success: function (data) {
                        btn.replaceWith(actualBtn);
                        if (data.status == 1) {
                            let slug = "{{ route('thankyou') }}/" + data.data.id;
                            window.location.replace(slug);
                        } else if (data.status == 2) {
                            let slug = "{{ route('duplicateCustomer') }}/" + data.request.searching_id;
                            window.location.replace(slug);
                        } else {
                            var message = formatErrorMessageFromJSON(data.errors);
                            message = message.replace(/<p>/g, '<p class="err_msg">');
                            $('.calculator-form .registerFormInner').append(message);
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                var mobile = $('#mobile_num').val();
                console.log(mobile);
                return checkCustomerMobile(mobile);
            }
        });
        function isNumber(evt) {
            $('.mobile-check-filter').html('');
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function checkCustomerMobile(mobile){
            if(! (mobile.toString().startsWith(5) && mobile.toString().length == 9) ){
                $('.mobile-check-filter').html('');
                $('.mobile-check-filter').append('<span class="text-danger"> <i class="fa fa-times-circle-o"></i> '+ " {{MyHelpers::guest_trans( 'Must Start with 5 and include 9 digits')}} " +' </span>');
                $('.btn-send-request').attr('disabled',true);
            }else{
                $.ajax({
                    type: "GET",
                    url: "/check-mobile-availability/"+ mobile ,
                    data: '',
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $('.mobile-check-filter').html('');
                        $('.mobile-check-filter').html('<span><i class="fa fa-spinner fa-spin fa-lg"></i> '+ " {{MyHelpers::guest_trans( 'Phone number verification in progress')}} " +'</span>');
                    },
                    success: function(result){
                        console.log(result);
                        if(result.status == 'available'){
                            $('.mobile-check-filter').html('');
                            $('.mobile-check-filter').append('<span class="text-success"> <i class="fa fa-check"></i> '+ result.msg +' </span>');
                            $('.btn-send-request').attr('disabled',false);
                        }else if(result.status == 'found'){
                            $('.mobile-check-filter').html('');
                            $('.mobile-check-filter').append('<span class="text-danger"> <i class="fa fa-times-circle-o"></i> '+ result.msg +' </span>');
                            $('.btn-send-request').attr('disabled',true);

                        }
                    },
                    error: function(result) {
                    }
                });
            }

        }

        $('#askWsata').submit(function (event) {
            event.preventDefault();
            var btn = $(this);
            var loader = $('.btn-send');
            var btn_trans = "{{ MyHelpers::guest_trans('ask employee') }}";
            var sending = "{{ MyHelpers::guest_trans('sending') }}"
            console.log($('#askWsata').attr("action"));
            $.ajax({
                type: "POST",
                url: "{{url('/sendMessage')}}",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> " + sending);
                },
                success: function (data) {
                    swal.fire({
                        title: "{{ MyHelpers::guest_trans('Will Reply Soon') }}",
                        type: data.status,
                        text: "{{ MyHelpers::guest_trans('You can ask more') }}",
                    }).then((result) => {
                        if (result.value) {
                            loader.html(btn_trans);
                            btn.attr('disabled', false);
                            window.location.href = data.redirect;
                        }
                    });

                },
                error: function (data) {
                    loader.html(btn_trans);
                    btn.attr('disabled', false);
                    var errors = data.responseJSON;
                },
                complete: function () {
                    $("body").css("padding-right", "0px !important");
                }
            });
        });

        $('#requestProperty').submit(function (event) {
            event.preventDefault();
            var btn = $(this);
            var loader = $('.btn-send-request');
            var btn_trans = "{{ MyHelpers::guest_trans('Request Property') }}";
            var sending = "{{ MyHelpers::guest_trans('sending') }}"
            var auth = "{{auth('customer')->check()}}" ? "{{auth('customer')->id()}}" : undefined ;
            console.log(auth)
            {{--var auth = {{auth('customer')->user()}}--}}
            if(auth){
                var instruction =   "{{ MyHelpers::guest_trans('follow instructions auth') }}" ;
            }else{
                var instruction =   "{{ MyHelpers::guest_trans('follow instructions') }}" ;
            }
            console.log($('#requestProperty').attr("action"));
            $.ajax({
                type: "POST",
                url: "{{route('requestProperty')}}",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> " + sending);
                },
                success: function (data) {
                    swal.fire({
                        title: "{{ MyHelpers::guest_trans('request received') }}",
                        type: data.status,
                        text: instruction,
                    }).then((result) => {
                        if (result.value) {
                            loader.html(btn_trans);
                            btn.attr('disabled', false);
                            window.location.href = data.redirect;
                        }
                    });
                },
                error: function (data) {
                    loader.html(btn_trans);
                    btn.attr('disabled', false);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $.each(errors.errors, function (key, value) {
                            var ErrorID = '#' + key + 'RequestError';
                            $(ErrorID).text(value);
                            console.log(value);
                            console.log(ErrorID);
                        })
                    }
                },
                complete: function () {
                    $("body").css("padding-right", "0px !important");
                }
            });
        });
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: {{$property->lat}},
                    lng: {{$property->lng}}},
                zoom: 17,
                mapTypeId: 'roadmap'
            });
            infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();

            marker = new google.maps.Marker({
                position: { lat: {{$property->lat}},
                    lng: {{$property->lng}}},
                map: map,
                title: '{{ $property->addresss }}'

            });
            // move pin and current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(pos),
                        map: map,
                        title: 'موقعك الحالي'
                    });
                    markers.push(marker);
                    marker.addListener('click', function() {
                        geocodeLatLng(geocoder, map, infoWindow,marker);
                    });
                    // to get current position address on load
                    google.maps.event.trigger(marker, 'click');
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                console.log('dsdsdsdsddsd');
                handleLocationError(false, infoWindow, map.getCenter());
            }

            var geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(event) {
                SelectedLatLng = event.latLng;
                geocoder.geocode({
                    'latLng': event.latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            deleteMarkers();
                            addMarkerRunTime(event.latLng);
                            SelectedLocation = results[0].formatted_address;
                            console.log( results[0].formatted_address);
                            splitLatLng(String(event.latLng));
                            $("#pac-input").css('display','block').val(results[0].formatted_address);
                        }
                    }
                });
            });
            function geocodeLatLng(geocoder, map, infowindow,markerCurrent) {
                var latlng = {lat: markerCurrent.position.lat(), lng: markerCurrent.position.lng()};
                /* $('#branch-latLng').val("("+markerCurrent.position.lat() +","+markerCurrent.position.lng()+")");*/
                $('#latitude').val(markerCurrent.position.lat());
                $('#longitude').val(markerCurrent.position.lng());

                geocoder.geocode({'location': latlng}, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            map.setZoom(8);
                            var marker = new google.maps.Marker({
                                position: latlng,
                                map: map
                            });
                            markers.push(marker);
                            infowindow.setContent(results[0].formatted_address);
                            SelectedLocation = results[0].formatted_address;
                            $("#pac-input").val(results[0].formatted_address);

                            infowindow.open(map, marker);
                        } else {
                            window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
                SelectedLatLng =(markerCurrent.position.lat(),markerCurrent.position.lng());
            }
            function addMarkerRunTime(location) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
                markers.push(marker);
            }
            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }
            function clearMarkers() {
                setMapOnAll(null);
            }
            function deleteMarkers() {
                clearMarkers();
                markers = [];
            }

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            $("#pac-input").val("أبحث هنا ");
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });

            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(100, 100),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };

                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location
                    }));


                    $('#latitude').val(place.geometry.location.lat());
                    $('#longitude').val(place.geometry.location.lng());

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
        function splitLatLng(latLng){
            var newString = latLng.substring(0, latLng.length-1);
            var newString2 = newString.substring(1);
            var trainindIdArray = newString2.split(',');
            var lat = trainindIdArray[0];
            var Lng  = trainindIdArray[1];

            $("#latitude").val(lat);
            $("#longitude").val(Lng);
        }

    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK6qwaZ7qTw2m755D9dC-jqhR7hoTKkm8&libraries=places&callback=initAutocomplete&language=ar&region=EG
         async defer"></script>
@endsection

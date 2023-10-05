@extends('testWebsite.layouts.master')

@section('title') طلباتي @endsection


@section('pageMenu')
@include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
<div class="myOrders">
    <div class="container">
        <div class="head-div text-center">
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
                                <div class="p-3">
                                    @if($property->image()->first())
                                    <img width="100%" height="500px" src="{{asset($property->image()->first()->image_path)}}">
                                    @else
                                    <img width="100%" height="500px" src="">
                                    @endif
                                </div>

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
                            @else
                                <a href="{{url('/ar/request_service')}}?source=property&id={{$property->id}}" class="btn btn-success mb-1 btn-block">طلب هذا العقار</a>
                            @endif
                            @if(sizeof($property->image) > 1)
                                <div class="card">
                                    <h3 class="text-center">صور اخرى</h3>
                                    <br>
                                    @foreach($property->image as $imgKey=> $img)
                                        @if (! $loop->first)
                                            <div class="img img-thumbnail">
                                                <a data-toggle="modal" data-target="#zoom_image" data-id="{{$img->id}}" data-img="{{$img->image_path}}" href="">
    @if($img)
    <img width="320" height="200" src="{{asset($img->image_path)}}" id="image_{{$img->id}}" >
    @else
<img width="320" height="200" id="image_--{{$imgKey}}" >
                            @endif
                                                </a>
                                            </div>
                                            <br>
                                            <br>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-12 pt-5">
                            <div id="map" style="height:400px;background:grey"></div>
                        </div>
                    </div>

                </div>


            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function(e) {

        $(document).on('keypress', '[name=order_number]', function(e) {
            if (e.which == 10 || e.which == 13) {
                e.preventDefault();
                $('#sendBtn').click();
            }
        });

        $(document).on('click', '#sendBtn', function(e) {

            e.preventDefault();
            var dublicate = $('.dublicate-alert');
            var loader = $('.message-box');
            var btn = $(this);

            dublicate.addClass('hide');


            $.ajax({
                type: 'GET',
                url: "{{ route('frontend.page.check_order_status') }}",
                data: $('.requstform form').serialize(),
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> {{ MyHelpers::guest_trans('loading') }}").removeClass('hide alert-danger').addClass('alert-success');
                },
                error: function(jqXHR, exception) {
                    btn.attr('disabled', false);
                    loader.html("{{MyHelpers::guest_trans('The selected order number is invalid.') }}").removeClass('alert-success').addClass('alert-danger');
                },
                success: function(data) {
                    btn.attr('disabled', false);
                    if (data.status == 11) { //pending request
                        loader.html(data.msg).removeClass('alert-danger').addClass('alert-success');
                    } else if (data.status == 1) { // request
                        if (data.customer.isVerified == 1) {
                            /*let slug = " route('customer.login') ";
                            window.location.replace(slug);*/

                        } else {
                            var mobile = data.customer.mobile;
                            $.get("{{ route('setSessionMobileNumber')}}", {
                                mobile: mobile,
                            }, function(data) {});

                            $.get("{{ route('mobileOTP')}}", {}, function(data) {
                                if (data.status == 1) {
                                    let slug = "{{ route('mobileOTPPage') }}";
                                    window.location.replace(slug);
                                } else {
                                    $("#mi-modal").modal('hide');
                                    alert('لاتستطيع إكمال العملية.. نعتذر على ذلك');
                                    let slug = "{{ url('/') }}";
                                    window.location.replace(slug);

                                }
                            });
                        }
                    } else {
                        loader.html(data.msg).removeClass('alert-success').addClass('alert-danger');
                    }

                }
            });

        })
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

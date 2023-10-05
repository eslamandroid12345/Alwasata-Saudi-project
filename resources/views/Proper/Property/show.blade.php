@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Show') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Property') }}
@endsection
@section('css_style')
    <style>
        tr:hover td {
            background: unset;
        }
    </style>
@endsection

@section('customer')


<div>
  @if (session('msg'))
  <div id="msg" class="alert @if (session('type')) alert-{{ session('type') }} @endif ">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('msg') }}
  </div>
  @endif
</div>
<div class="addUser my-4 {{auth()->user()->role == '7' ? 'hidden' : ''}}">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>  تفاصيل العقار :</h3>
    </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
        <div class="card-header bg-primary text-center text-white">
            <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Property Info') }}</h3>
        </div>
      <div class="card-body">

        <table class="">
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>#</th>
                <td>{{$property->id}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property source') }}</th>
                <td>
                    @switch($property->creator->role)
                        @case('6')
                        {{ MyHelpers::admin_trans(auth()->user()->id,'collaborator') }} - {{$property->creator->name}}
                        @break
                        @case('9')
                        {{ MyHelpers::admin_trans(auth()->user()->id,'property agent') }} - {{$property->creator->name}}
                        @break
                        @case('10')
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Propertor') }} - {{$property->creator->name}}
                        @break
                        @default
                        {{ MyHelpers::admin_trans(auth()->user()->id,'undefined') }}
                    @endswitch

                </td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property type') }}</th>
                <td>{{@$property->type->value}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property price') }}</th>
                <td>
{{--                    @if($property->price_type == 'range')--}}
{{--                        {{MyHelpers::admin_trans(auth()->user()->id, 'range price')}} - ( {{$property->min_price}} - {{$property->max_price}} )--}}
{{--                    @else--}}
                         {{$property->fixed_price}}
{{--                    @endif--}}
                </td>
            </tr>

           {{-- <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property status') }}</th>
                <td>
                    @if($property->is_published == 1)
                        {{ MyHelpers::admin_trans(auth()->user()->id,'property published') }}
                    @else
                        {{ MyHelpers::admin_trans(auth()->user()->id,'property unpublished') }}
                    @endif
                </td>
            </tr>--}}

{{--            <tr>--}}
{{--                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property has offer') }}</th>--}}
{{--                <td>--}}
{{--                    @if($property->has_offer == 1)--}}
{{--                        {{ MyHelpers::admin_trans(auth()->user()->id,'true') }}--}}
{{--                    @else--}}
{{--                        {{ MyHelpers::admin_trans(auth()->user()->id,'false') }}--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property offer_price') }}</th>--}}
{{--                <td>{{$property->offer_price}}</td>--}}
{{--            </tr>--}}
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}/{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</th>
                <td>{{@$property->district->value}}
                    {{@$property->city->value}} - {{@$property->areaName->value}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property address') }}</th>
                <td>{{$property->address}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of rooms') }}</th>
                <td>{{$property->num_of_rooms}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of salons') }}</th>
                <td>{{$property->num_of_salons}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of kitchens') }}</th>
                <td>{{$property->num_of_kitchens}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of bathrooms') }}</th>
                <td>{{$property->num_of_bathrooms}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'number of streets the property overlooks') }}</th>
                <td>{{$property->number_of_streets}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>إسم المالك</th>
                <td>{{$property->owner_name ?? "-"}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>رقم جوال المالك</th>
                <td>{{$property->owner_number ?? "-"}}</td>
            </tr>
            <tr>
            <th>إسم المطور</th>
            <td>{{$property->dev_name ?? "-"}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>رقم جوال المطور</th>
                <td>{{$property->dev_number ?? "-"}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
            <th>إسم المسوق</th>
            <td>{{$property->mark_name ?? "-"}}</td>
            </tr>
            <tr><td colspan="2"><hr style="padding: 0;margin: 5px"></td></tr>
            <tr>
                <th>رقم جوال المسوق</th>
                <td>{{$property->mark_number ?? "-"}}</td>
            </tr>
            {{--@php
                $key = 'AIzaSyA5Wy8NcVi3h7B2P7wThSCzSkQHF_MQJfI';
                //$key = 'AIzaSyD66IL8Ml8_iGkRB_mD_5OqSKfG-taA5lQ';
            @endphp
            <script>
                function initMap() {
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: {
                            lat: {{$property->lat}},
                            lng: {{$property->lng}}
                        },
                        zoom: 8
                    });
                }
            </script>--}}
        </table>
          <hr>
         <b> {{ MyHelpers::admin_trans(auth()->user()->id,'property description') }}</b><br>
          {!! $property->description !!}
          <hr>
         <b>{{ MyHelpers::admin_trans(auth()->user()->id,'property zone') }}</b>
          <br>
          @if($property->lng && $property->lat)
              {{$property->lng}} * {{$property->lat}} <br> <hr>
           <b>   المكان على الخريطة :</b>
              <div id="map" style="direction:rtl;height:600px;width:100%;float:right;background:grey"></div>
          @else
              {{ MyHelpers::admin_trans(auth()->user()->id,'property zone error') }}
          @endif

        <hr>
        <b>  صور العقار :</b>
        <div class="row">
            @foreach($property->image as $img)
                <div class="col-lg-3">
                    <a data-toggle="modal" data-target="#zoom_image" data-id="{{$img->id}}" data-img="{{$img->image_path}}" href="">
                        <img src="{{asset($img->image_path)}}" class="img-fluid img-thumbnail" id="image_{{$img->id}}" >
                    </a>

                </div>
            @endforeach
        </div>
          <br> <hr>
          فيديو العقار :
        <div class="row">
            @if(!empty($property->video_url))
                <iframe id="ytplayer" type="text/html" width="100%" height="600"
                        src="{{$property->video_url}}">
                </iframe>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@section('updateModel')
    @include('image.zoom')
@endsection

@section('scripts')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function() {
      $('#type_id').select2();
  });
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
<script>


    $("#pac-input").focusin(function() {
        $(this).val('');
    });
    // This example adds a search box to a map, using the Google Place Autocomplete
    // feature. People can enter geographical searches. The search box will return a
    // pick list containing a mix of places and predicted search terms.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

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

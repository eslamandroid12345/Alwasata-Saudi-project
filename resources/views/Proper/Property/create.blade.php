@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Property') }}
@endsection
@section('css_style')

    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .loadingButton {
            background-color: #0088cc;
            color: azure;
            cursor: not-allowed;
        }

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
        .autocomplete {
            position: relative;
            display: inline-block;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 9999;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
    </style>

    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
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
        <h3> إضافة عقار :</h3>

    </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-primary text-center text-white">
        <strong>{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Property') }}</strong>
      </div>
      <div class="card-body">
        <div class="card-title">
          <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Property Info') }}</h3>
        </div>
        <hr>

        <form action="{{ route('property.store')}}" method="post" enctype="multipart/form-data" id="create_property">
          @csrf

          <input name="_token" value="{{ csrf_token() }}" type="hidden">
            <input type="hidden"  value="" id="latitude" name="latitude">
            <input type="hidden" value="" id="longitude"  name="longitude">
          <div>
              <div class="row">
                  <div class="col-6">
                        <div class="form-group">
                            <label for="type_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property type') }}</label>
                            <select id="type_id" onfocus='this.size=3;' onblur='this.size=1;' value="{{ old('type_id') }}" class="form-control @error('type_id') is-invalid @enderror" name="type_id">
                                <option value="">---</option>
                                @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->value}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="typeError" role="alert"> </span>
                            <span class="text-danger">
                            <strong id="type_id-error"></strong>
                          </span>
                        </div>
                  </div>
{{--                  <div class="col-4">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="price_type" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property price type') }}</label>--}}
{{--                            <select id="price_type" value="{{ old('price_type') }}" class="form-control @error('price_type') is-invalid @enderror" name="price_type">--}}
{{--                                <option value="fixed" selected>{{ MyHelpers::admin_trans(auth()->user()->id,'fixed price') }}</option>--}}
{{--                                <option value="range">{{ MyHelpers::admin_trans(auth()->user()->id,'range price') }}</option>--}}
{{--                            </select>--}}
{{--                            <span class="text-danger">--}}
{{--                            <strong id="price_type-error"></strong>--}}
{{--                          </span>--}}
{{--                        </div>--}}
{{--                  </div>--}}
                  <div class="col-6" id="fixed_price_div">
                        <div class="form-group">
                            <label for="fixed_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property price') }}</label>
                            <input id="fixed_price" step="any" name="fixed_price" type="number" class="form-control @error('fixed_price') is-invalid @enderror" value="{{ old('fixed_price') }}" autocomplete="type" autofocus placeholder="">
                            <span class="text-danger">
                            <strong id="fixed_price-error"></strong>
                          </span>
                        </div>
                  </div>
                  <div class="col-6" id="fixed_price_div">
                      <div class="form-group">
                          <label for="area" class="control-label mb-1">المساحة</label>
                          <input id="area" name="area" type="text" class="form-control @error('area') is-invalid @enderror" value="{{ old('area') }}" autocomplete="type" autofocus placeholder="">
                          <span class="text-danger">
                            <strong id="area-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-6" id="fixed_price_div">
                      <div class="form-group">
                          <label for="number_of_flats" class="control-label mb-1">عدد الشقق</label>
                          <input id="number_of_flats" name="number_of_flats" type="text" class="form-control @error('number_of_flats') is-invalid @enderror" value="{{ old('number_of_flats') }}" autocomplete="type" autofocus placeholder="">
                          <span class="text-danger">
                            <strong id="number_of_flats-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-4" id="min_price_div" style="display: none" >
                      <div class="form-group">
                          <label for="min_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property min price') }}</label>
                          <input id="min_price" step=".01" name="min_price" type="number" class="form-control @error('min_price') is-invalid @enderror" value="{{ old('min_price') }}" >
                          <span class="text-danger">
                            <strong id="min_price-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-4" id="max_price_div"  style="display: none" >
                        <div class="form-group">
                            <label for="max_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property max price') }}</label>
                            <input id="max_price" name="max_price" type="text" class="form-control @error('max_price') is-invalid @enderror" value="{{ old('max_price') }}" autocomplete="type" autofocus placeholder="">
                            <span class="text-danger">
                            <strong id="max_price-error"></strong>
                          </span>
                        </div>
                  </div>
              </div>
             {{-- <div class="row">
              <input  id="pac-input"  name="address" type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" >
              </div>--}}
              <div class="row">
                  <div class="col-4">
                      <div class="form-group">
                          <label for="area_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property region') }}</label>
                          <select id="area_id" name="area_id" class=" form-control @error('region') is-invalid @enderror">
                              <option disabled selected>أختار المنطقة ..</option>
                              @foreach($areas as $area)
                                <option value="{{$area->id}}">{{$area->value}}</option>
                              @endforeach
                          </select>
                          <span class="text-danger">
                            <strong id="area_id-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="form-group">
                          <label for="city_id" class="control-label mb-1">المدينه</label>
                          <select id="city_id" name="city_id" class=" form-control @error('city_id') is-invalid @enderror">
                          </select>
                          <span class="text-danger">
                            <strong id="city_id-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="form-group">
                          <label for="district_id" class="control-label mb-1">الحى </label>
                          <select id="district_id" name="district_id" class=" form-control @error('district_id') is-invalid @enderror">
                          </select>

                          <span class="text-danger">
                            <strong id="district_id-error"></strong>
                          </span>
                      </div>
                  </div>

              </div>
              <div class="row">
                  <input type="hidden" name="is_published" value="1">
                 {{-- <div class="col-12">
                      <div class="form-group">
                          <label for="is_published" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property status') }}</label>
                          <select id="is_published" value="{{ old('is_published') }}" class="form-control @error('is_published') is-invalid @enderror" name="is_published">
                              <option value="1" selected>{{ MyHelpers::admin_trans(auth()->user()->id,'property published') }}</option>
                              <option value="0">{{ MyHelpers::admin_trans(auth()->user()->id,'property unpublished') }}</option>
                          </select>
                          <span class="text-danger">
                            <strong id="is_published-error"></strong>
                          </span>
                      </div>
                  </div>--}}

{{--                  <div class="col-4">--}}
{{--                      <div class="form-group">--}}
{{--                          <label for="has_offer" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property has offer') }}</label>--}}
{{--                          <select id="has_offer" value="{{ old('has_offer') }}" class="form-control @error('has_offer') is-invalid @enderror" name="has_offer">--}}
{{--                              <option value="1" >{{ MyHelpers::admin_trans(auth()->user()->id,'true') }}</option>--}}
{{--                              <option value="0" selected>{{ MyHelpers::admin_trans(auth()->user()->id,'false') }}</option>--}}
{{--                          </select>--}}
{{--                          <span class="text-danger">--}}
{{--                            <strong id="has_offer-error"></strong>--}}
{{--                          </span>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--                  <div class="col-4" id="has_offer_div">--}}
{{--                      <div class="form-group">--}}
{{--                          <label for="offer_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property offer_price') }}</label>--}}
{{--                          <input id="offer_price" name="offer_price" type="number" class="form-control @error('offer_price') is-invalid @enderror" value="{{ old('offer_price') }}" >--}}
{{--                          <span class="text-danger">--}}
{{--                            <strong id="offer_price-error"></strong>--}}
{{--                          </span>--}}
{{--                      </div>--}}
{{--                  </div>--}}
              </div>
              <div class="row">
                  <div class="col-3">
                      <div class="form-group">
                          <label for="num_of_rooms" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of rooms') }}</label>
                          <input id="num_of_rooms" name="num_of_rooms" type="number" class="form-control @error('num_of_rooms') is-invalid @enderror" value="{{ old('num_of_rooms') }}" >
                          <span class="text-danger">
                            <strong id="num_of_rooms-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-3">
                      <div class="form-group">
                          <label for="num_of_salons" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of salons') }}</label>
                          <input id="num_of_salons" name="num_of_salons" type="number" class="form-control @error('num_of_salons') is-invalid @enderror" value="{{ old('num_of_salons') }}" >
                          <span class="text-danger">
                            <strong id="num_of_salons-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-3">
                      <div class="form-group">
                          <label for="num_of_kitchens" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of kitchens') }}</label>
                          <input id="num_of_kitchens" name="num_of_kitchens" type="number" class="form-control @error('num_of_kitchens') is-invalid @enderror" value="{{ old('num_of_kitchens') }}" >
                          <span class="text-danger">
                            <strong id="num_of_kitchens-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-3">
                      <div class="form-group">
                          <label for="num_of_bathrooms" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of bathrooms') }}</label>
                          <input id="num_of_bathrooms" name="num_of_bathrooms" type="number" class="form-control @error('num_of_bathrooms') is-invalid @enderror" value="{{ old('num_of_bathrooms') }}" >
                          <span class="text-danger">
                            <strong id="num_of_bathrooms-error"></strong>
                          </span>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="num_of_streets" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'number of streets the property overlooks') }}</label>
                          <input id="num_of_streets" name="number_of_streets" type="number" class="form-control @error('number_of_streets') is-invalid @enderror" value="{{ old('number_of_streets') }}" >
                          <span class="text-danger">
                            <strong id="num_of_streets-error"></strong>
                          </span>
                      </div>
                  </div>
              </div>


              <div  class="container mb-3">
                  <label  class="control-label street_view d-none">{{ MyHelpers::admin_trans(auth()->user()->id,'Street view in order') }}</label>
                  <div id="num_of_streets_fields" class="row">

                  </div>
              </div>

              <br/>

              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="video_url" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property video url') }}</label>
                          <input id="video_url" name="video_url" type="url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url') }}" >
                          <span class="text-danger">
                            <strong id="video_url-error"></strong>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-6">
                      <div class="form-group">
                          <label for="num_of_streets" class="control-label mb-1">
                              إسم المالك
                          </label>
                          <input id="owner_name" name="owner_name" type="text" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name') }}" >
                          <span class="text-danger">
                            <strong id="owner_name-error"></strong>
                          </span>
                      </div>
                  </div>

                  <div class="col-lg-6">
                      <div class="form-group">
                          <label for="owner_number" class="control-label mb-1">
                              رقم جوال المالك
                          </label>
                          <input id="owner_number" name="owner_number" type="number" class="form-control @error('owner_number') is-invalid @enderror" value="{{ old('owner_number') }}" >
                          <span class="text-danger">
                            <strong id="owner_number-error"></strong>
                          </span>
                      </div>
                  </div>

                  <div class="col-lg-6">
                      <div class="form-group">
                          <label for="dev_name" class="control-label mb-1">
                              إسم المطور
                          </label>
                          <input id="dev_name" name="dev_name" type="text" class="form-control @error('dev_name') is-invalid @enderror" value="{{ old('dev_name') }}" >
                          <span class="text-danger">
                            <strong id="dev_name-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                          <label for="dev_number" class="control-label mb-1">
                              رقم جوال المطور
                          </label>
                          <input id="dev_number" name="dev_number" type="number" class="form-control @error('dev_number') is-invalid @enderror" value="{{ old('dev_number') }}" >
                          <span class="text-danger">
                            <strong id="dev_number-error"></strong>
                          </span>
                      </div>
                  </div>

                  <div class="col-lg-6">
                      <div class="form-group">
                          <label for="mark_name" class="control-label mb-1">
                              إسم المسوق
                          </label>
                          <input id="mark_name" name="mark_name" type="text" class="form-control @error('mark_name') is-invalid @enderror" value="{{ old('mark_name') }}" >
                          <span class="text-danger">
                            <strong id="mark_name-error"></strong>
                          </span>
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="form-group">
                          <label for="mark_number" class="control-label mb-1">
                              رقم جوال المسوق
                          </label>
                          <input id="mark_number" name="mark_number" type="number" class="form-control @error('mark_number') is-invalid @enderror" value="{{ old('mark_number') }}" >
                          <span class="text-danger">
                            <strong id="mark_number-error"></strong>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="description" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property description') }} </label>
                          <textarea id="description" name="description" rows="7" class="form-control @error('description') is-invalid @enderror" value="" >{{ old('description') }}</textarea>
                          <span class="text-danger">
                            <strong id="description-error"></strong>
                          </span>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property other images') }}</label>
                          <br>
                          <label for="images" class="btn btn-success btn-xs mb-1">أضافة صور للعقار <span class="fa fa-plus"></span></label>
                          <hr>
                          <input id="images" name="images[]" multiple="multiple" style="opacity: 0;position: absolute;z-index: -1;" type="file" class="form-control @error('images') is-invalid @enderror"  >
                          <div class="row">
                              <span class="text-danger">
                                <strong id="images-error"></strong>
                              </span>
                          </div>
                          <div class="row" id="data-images">

                          </div>
                      </div>
                  </div>
              </div>
              <label for="">مكان العقار على الخريطة : </label>
              <div id="map" style="height: 500px;width: 100%;"></div>
              <span class="text-danger">
                <strong id="latitude-error"></strong>
              </span>
          <div>
          <br>
            <button type="submit" class="btn btn-primary btn-info btn">
              {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}
            </button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function (){
            $('#area_id').change(function (){
                var city_id = $('#city_id');
                // console.log(area_id)
                $.ajax({
                    url: 'get-cities',
                    data:{
                        id:$(this).val()
                    },
                    success:function (data){
                            city_id.html(data);
                    }
                });
            });

            $('#city_id').change(function (){
                var district_id = $('#district_id');
                // console.log(area_id)
                $.ajax({
                    url: 'get-district',
                    data:{
                        id:$(this).val()
                    },
                    success:function (data){
                        district_id.html(data);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function (){
            $('.street_view').addClass('none');
            var input  = '<div class="col-md-4"><input class="form-control type="text" name="street_width[]"/></div>'; //input html
            var container = $('#num_of_streets_fields'); //cache selector for better performance
            var i = 0; // variable used in loop
            $("#num_of_streets").change(function(){ //when the main select changes
                var numRows = $(this).val(); //get its value
                container.html(""); //empty the container
                for(i=1; i<= numRows; i++){
                    $('.street_view').removeClass('d-none');
                   container.append(input); //and append a row from 1 to the numRows
                }
            });

        });
      /*  $('#fixed_price,#min_price,#max_price').keyup(function(event) {

            // skip for arrow keys

            // format number
            $(this).val(function(index, value) {
                return value
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });
        });*/

    </script>
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function() {
      $('#type_id').select2();

      var count =0;
      if (window.File && window.FileList && window.FileReader) {
          $("#images").on("change", function(e) {
              var images = e.target.files,
                  imagesLength = images.length;
              count++;
              for (var i = 0; i < imagesLength; i++) {
                  var index = 'image_'+ i ;
                  $('<input type="file" id="'+index+'" hidden  value=""/>').insertAfter("#images");
                  $("#"+index).prop("files",$("#images").prop("files"));
                  var f = images[i]
                  var fileReader = new FileReader();
                  fileReader.onload = (function(e) {
                      var file = e.target;
                      $("<div class=\"pip float-sm-left col-2 mt-1 text-center\" style='margin-top: 20px'>" +
                          "<img class=\"imageThumb img-fluid\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                          "<br/><span class=\"removeImg btn btn-danger btn-block btn-sm \" style='cursor: pointer'><i class='fa fa-times-circle-o'></i>مسح </span>" +
                          "</div>").appendTo("#data-images");
                      $(".removeImg").click(function(){
                          $(this).parent(".pip").remove();
                      });

                  });
                  fileReader.readAsDataURL(f);

              }
          });


      } else {
          alert("Your browser doesn't support to File API")
      }
  });
  $(function() {
      $('#create_property').on('submit', function(e) {
          // get all the inputs into an array.
          var $inputs = $('#create_property :input');

          // not sure if you wanted this, but I thought I'd add it.
          // get an associative array of just the values.
          var values = {};
          $inputs.each(function() {
              values[this.name] = $(this).val();
          });
          $('#description-error').html("");
          $('#description-error').html("");
          $('#images-error').html("");
          $('#num_of_bathrooms-error').html("");
          $('#num_of_kitchens-error').html("");
          $('#num_of_salons-error').html("");
          $('#num_of_rooms-error').html("");
          $('#type_id-error').html("");
          $('#price_type-error').html("");
          $('#fixed_price-error').html("");
          $('#area_id-error').html("");
          $('#video_url-error').html("");
          $('#has_offer-error').html("");
          $('#is_published-error').html("");
          $('#latitude-error').html("");
          $('#min_price-error').html("");
          $('#city_id-error').html("");
          $('#max_price-error').html("");
          if (!e.isDefaultPrevented()) {
              url = "{{ url('property/store') }}";
              $.ajax({
                  url: url,
                  type: "POST",
                  data: new FormData($("#create_property")[0]),
                  contentType: false,
                  processData: false,
                  success: function(data) {
                      if (data.errors) {
                          if (data.errors.description) {
                              $('#description-error').html(data.errors.description[0]);
                          }
                          if (data.errors.images) {
                              $('#images-error').html(data.errors.images[0]);
                          }
                          if (data.errors.num_of_bathrooms) {
                              $('#num_of_bathrooms-error').html(data.errors.num_of_bathrooms[0]);
                          }
                          if (data.errors.num_of_kitchens) {
                              $('#num_of_kitchens-error').html(data.errors.num_of_kitchens[0]);
                          }
                          if (data.errors.num_of_salons) {
                              $('#num_of_salons-error').html(data.errors.num_of_salons[0]);
                          }
                          if (data.errors.num_of_rooms) {
                              $('#num_of_rooms-error').html(data.errors.num_of_rooms[0]);
                          }

                          if (data.errors.city_id) {
                              $('#city_id-error').html(data.errors.city_id[0]);
                          }
                          if (data.errors.max_price) {
                              $('#max_price-error').html(data.errors.max_price[0]);
                          }
                          if (data.errors.min_price) {
                              $('#min_price-error').html(data.errors.min_price[0]);
                          }
                          if (data.errors.latitude) {
                              $('#latitude-error').html(data.errors.latitude[0]);
                          }
                          if (data.errors.district_id) {
                              $('#district_id-error').html(data.errors.district_id[0]);
                          }
                          if (data.errors.is_published) {
                              $('#is_published-error').html(data.errors.is_published[0]);
                          }
                          if (data.errors.has_offer) {
                              $('#has_offer-error').html(data.errors.has_offer[0]);
                          }
                          if (data.errors.video_url) {
                              $('#video_url-error').html(data.errors.video_url[0]);
                          }
                          if (data.errors.area_id) {
                              $('#area_id-error').html(data.errors.area_id[0]);
                          }
                          if (data.errors.fixed_price) {
                              $('#fixed_price-error').html(data.errors.fixed_price[0]);
                          }
                          if (data.errors.price_type) {
                              $('#price_type-error').html(data.errors.price_type[0]);
                          }
                          if (data.errors.type_id) {
                              $('#type_id-error').html(data.errors.type_id[0]);
                          }

                          swal({
                              title: 'خطأ...',
                              text: data.message,
                              type: 'error',
                              timer: '750'
                          })


                      }
                      if (data.success) {
                          $('#create_property')[0].reset();
                          $('#data-images').html("");
                          location.replace("{{route('property.list')}}")
                          swal({
                              title: 'تم!',
                              text: data.message,
                              type: 'success',
                              timer: '750'
                          })
                      }
                  },
                  error: function(data) {

                      swal({
                          title: 'خطأ',
                          text: data.message,
                          type: 'error',
                          timer: '750'
                      })

                  }
              });
              return false;
          }
      });
  });
  $('#price_type').change(function () {
        var value = $('#price_type').val();
        if(value =='fixed'){
            $('#fixed_price_div').css('display','block');
            $('#min_price_div').css('display','none');
            $('#max_price_div').css('display','none');
        }else{
            $('#fixed_price_div').css('display','none');
            $('#min_price_div').css('display','block');
            $('#max_price_div').css('display','block');
        }
  })
  $('#has_offer').change(function () {
        var value = $('#has_offer').val();
        if(value == 0){
            $('#has_offer_div').css('display','none');
        }else{
            $('#has_offer_div').css('display','block');
        }
  })
  /////////////////////////////////////////////////////////////////
/*  $('#create_property').submit(function( e ) {
     // e.preventDefault();

      // get all the inputs into an array.
      var $inputs = $('#create_property :input');

      // not sure if you wanted this, but I thought I'd add it.
      // get an associative array of just the values.
      var values = {};
      $inputs.each(function() {
          values[this.name] = $(this).val();
      });
      console.log(values);

  })*/

</script>
<script>

    $('.area').change(function(){
        if($(this).val() != '')
        {
            var select = $(this).attr("id");
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('cities.fetch') }}",
                method:"POST",
                data:{select:select, value:value, _token:_token},
                success:function(result)
                {
                    $('#city_id').html(result);
                }

            })
        }
    });

    $('.city').change(function(){
        if($(this).val() != '')
        {
            var select = $(this).attr("id");
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('districts.fetch') }}",
                method:"POST",
                data:{select:select, value:value, _token:_token},
                success:function(result)
                {
                    $('#district_id').html(result);
                }

            })
        }
    });

    $("#pac-input").focusin(function() {
        $(this).val('');
    });

    $('#latitude').val('');
    $('#longitude').val('');


    // This example adds a search box to a map, using the Google Place Autocomplete
    // feature. People can enter geographical searches. The search box will return a
    // pick list containing a mix of places and predicted search terms.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 24.740691, lng: 46.6528521 },
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        // move pin and current location
        infoWindow = new google.maps.InfoWindow;
        geocoder = new google.maps.Geocoder();
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
<script>
    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    /*An array containing all the country names in the world:*/
    var cities = [];
    var districts = [];
    @foreach($cities as $name)
    cities.push("{{$name->value}}")
    @endforeach
    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
    autocomplete(document.getElementById("city_id"), cities);
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK6qwaZ7qTw2m755D9dC-jqhR7hoTKkm8&libraries=places&callback=initAutocomplete&language=ar&region=EG
         async defer"></script>
@endsection

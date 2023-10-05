@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Property') }}
@endsection
@section('css_style')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        div.modal-backdrop.fade.show{
            position: inherit !important;
        }
    </style>

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
            <h3> تعديل عقار :</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary text-center text-white">
                    <strong>{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Property') }}</strong>
                </div>
                <div class="card-body">
                    <div class="card-title">
                        <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Property Info') }}</h3>
                    </div>
                    <hr>

                    <form action="{{ route('property.update',$property->id)}}" method="post" enctype="multipart/form-data" id="create_property">
                        @csrf

                        <input name="_token" value="{{ csrf_token() }}" type="hidden">
                            <div class="row">
                                <div class="row col-12">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="type_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property type') }}</label>
                                            <select id="type_id" onfocus='this.size=3;' onblur='this.size=1;' value="{{ old('type_id') }}" class="form-control @error('type_id') is-invalid @enderror" name="type_id">
                                                <option value="">---</option>
                                                @foreach($types as $type)
                                                    <option value="{{$type->id}}" {{ $property->type_id == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="typeError" role="alert"> </span>
                                            <span class="text-danger">
                                            <strong id="type_id-error"></strong>
                                          </span>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="fixed_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property price') }}</label>
                                            <input id="fixed_price" name="fixed_price" type="text" class="form-control @error('fixed_price') is-invalid @enderror" value="{{ old('fixed_price',$property->fixed_price) }}" autocomplete="type" autofocus placeholder="">
                                            <span class="text-danger">
                                            <strong id="fixed_price-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                    <div class="col-6" id="fixed_price_div">
                                        <div class="form-group">
                                            <label for="area" class="control-label mb-1">المساحة</label>
                                            <input id="area" name="area" type="text" class="form-control @error('area') is-invalid @enderror" value="{{ old('area',$property->area) }}" autocomplete="type" autofocus placeholder="">
                                            <span class="text-danger">
                                            <strong id="area-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                    <div class="col-6" id="fixed_price_div">
                                        <div class="form-group">
                                            <label for="number_of_flats" class="control-label mb-1">عدد الشقق</label>
                                            <input id="number_of_flats" name="number_of_flats" type="text" class="form-control @error('number_of_flats') is-invalid @enderror" value="{{ old('number_of_flats',$property->number_of_flats) }}" autocomplete="type" autofocus placeholder="">
                                            <span class="text-danger">
                                            <strong id="number_of_flats-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row col-12">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="area_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property region') }}</label>
                                            <select id="area_id" name="area_id" class="area form-control @error('region') is-invalid @enderror">
                                                <option disabled selected>أختار المنطقة ..</option>
                                                @foreach($areas as $area)
                                                    <option value="{{$area->id}}" {{$property->area_id == $area->id ? 'selected' : ''}}>{{$area->value}}</option>
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
                                            <input type="text" id="city_id" name="city_id" value="{{$property->city_id}}" class="city form-control @error('city_id') is-invalid @enderror">
                                            <span class="text-danger">
                                            <strong id="city_id-error"></strong>
                                          </span>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="district_id" class="control-label mb-1">الحى </label>
                                            <input type="text" id="district_id" name="district_id" value="{{$property->district_id}}" class="district form-control @error('district_id') is-invalid @enderror">
                                            <span class="text-danger">
                                            <strong id="district_id-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="cityId" id="old_city" value="{{$property->city_id}}">
                                    <input type="hidden" name="districtId" id="old_city" value="{{$property->district_id}}">
                                </div>
                                <input type="hidden" name="is_published" value="1">
                                {{--<div class="row col-12">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="is_published" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property status') }}</label>
                                            <select id="is_published" value="{{ old('is_published') }}" class="form-control @error('is_published') is-invalid @enderror" name="is_published">
                                                <option value="1" {{ $property->is_published == 1 ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'property published') }}</option>
                                                <option value="0" {{ $property->is_published == 0 ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'property unpublished') }}</option>
                                            </select>
                                            <span class="text-danger">
                                            <strong id="is_published-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                </div>--}}

                                <div class="row col-12">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_rooms" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of rooms') }}</label>
                                            <input id="num_of_rooms" name="num_of_rooms" type="number" class="form-control @error('num_of_rooms') is-invalid @enderror" value="{{ old('num_of_rooms',$property->num_of_rooms) }}" >
                                            <span class="text-danger">
                                            <strong id="num_of_rooms-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_salons" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of salons') }}</label>
                                            <input id="num_of_salons" name="num_of_salons" type="number" class="form-control @error('num_of_salons') is-invalid @enderror" value="{{ old('num_of_salons',$property->num_of_salons) }}" >
                                            <span class="text-danger">
                                            <strong id="num_of_salons-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_kitchens" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of kitchens') }}</label>
                                            <input id="num_of_kitchens" name="num_of_kitchens" type="number" class="form-control @error('num_of_kitchens') is-invalid @enderror" value="{{ old('num_of_kitchens',$property->num_of_kitchens) }}" >
                                            <span class="text-danger">
                                            <strong id="num_of_kitchens-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_bathrooms" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property num of bathrooms') }}</label>
                                            <input id="num_of_bathrooms" name="num_of_bathrooms" type="number" class="form-control @error('num_of_bathrooms') is-invalid @enderror" value="{{ old('num_of_bathrooms',$property->num_of_bathrooms) }}" >
                                            <span class="text-danger">
                                            <strong id="num_of_bathrooms-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="num_of_streets" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'number of streets the property overlooks') }}</label>
                                            <input id="num_of_streets" name="number_of_streets" type="number" class="form-control @error('number_of_streets') is-invalid @enderror" value="{{ old('number_of_streets',$property->number_of_streets) }}" >
                                            <span class="text-danger">
                                            <strong id="num_of_streets-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                </div>
--}}
                              {{--  <div  class="container mb-3">
                                    <label  class="control-label street_view d-none">{{ MyHelpers::admin_trans(auth()->user()->id,'Street view in order') }}</label>
                                    <div id="num_of_streets_fields" class="row">

                                    </div>
                                </div>--}}
                                <div class="row col-12">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="video_url" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property video url') }}</label>
                                            <input id="video_url" name="video_url" type="url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url',$property->video_url) }}" >
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
                                            <input id="owner_name" name="owner_name" type="text" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name',$property->owner_name) }}" >
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
                                            <input id="owner_number" name="owner_number" type="number" class="form-control @error('owner_number') is-invalid @enderror" value="{{ old('owner_number',$property->owner_number) }}" >
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
                                            <input id="dev_name" name="dev_name" type="text" class="form-control @error('dev_name') is-invalid @enderror" value="{{ old('dev_name',$property->dev_name) }}" >
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
                                            <input id="dev_number" name="dev_number" type="number" class="form-control @error('dev_number') is-invalid @enderror" value="{{ old('dev_number',$property->dev_number) }}" >
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
                                            <input id="mark_name" name="mark_name" type="text" class="form-control @error('mark_name') is-invalid @enderror" value="{{ old('mark_name',$property->mark_name) }}" >
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
                                            <input id="mark_number" name="mark_number" type="number" class="form-control @error('mark_number') is-invalid @enderror" value="{{ old('mark_number',$property->mark_number) }}" >
                                            <span class="text-danger">
                            <strong id="mark_number-error"></strong>
                          </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property description') }}</label>
                                            <textarea id="description" name="description" rows="7" class="form-control @error('description') is-invalid @enderror" value="" >{{ old('description',$property->description) }}</textarea>
                                            <span class="text-danger">
                                            <strong id="description-error"></strong>
                                          </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property other images') }}</label>
                                            <br>
                                            <label for="images" class="btn btn-success btn-xs mb-1">أضافة صور للعقار <span class="fa fa-plus"></span></label>
                                            <hr>
                                            <input id="images" style="opacity: 0;position: absolute;z-index: -1;" type="file" class="form-control @error('images') is-invalid @enderror"  >
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
                                <div class="row col-12">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="map">المكان على الخريطة</label>
                                            @if($property->lng && $property->lat)
                                                {{$property->lng}} * {{$property->lat}} <br> <hr>
                                                <div id="map" style="height:400px;background:grey"></div>
                                            @else
                                                {{ MyHelpers::admin_trans(auth()->user()->id,'property zone error') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="row">--}}
                                    <input type="hidden"  value="{{$property->lat}}" id="latitude" name="latitude">
                                    <input type="hidden" value="{{$property->lng}}" id="longitude"  name="longitude">
                                    <input type="hidden" value="{{ old('address',$property->address) }}"  id="address"  name="address">
{{--                                    <div class="col-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="address"  class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property address') }}</label>--}}
{{--                                            <input id="pac-input"  type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address',$property->address) }}" >--}}
{{--                                            <span class="text-danger">--}}
{{--                                                <strong id="address-error"></strong>--}}
{{--                                              </span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}







{{--                                <div class="col-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="price_type" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property price type') }}</label>--}}
{{--                                        <select id="price_type" value="{{ old('price_type') }}" class="form-control @error('price_type') is-invalid @enderror" name="price_type">--}}
{{--                                            <option value="fixed" {{ $property->price_type == 'fixed' ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'fixed price') }}</option>--}}
{{--                                            <option value="range" {{ $property->price_type == 'range' ? 'selected' : '' }}>{{ MyHelpers::admin_trans(auth()->user()->id,'range price') }}</option>--}}
{{--                                        </select>--}}
{{--                                        <span class="text-danger">--}}
{{--                                            <strong id="price_type-error"></strong>--}}
{{--                                          </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-2" id="min_price_div" style="display: {{$property->price_type == 'fixed' ? 'none' :'block'}}" >--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="min_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property min price') }}</label>--}}
{{--                                        <input id="min_price" name="min_price" type="number" class="form-control @error('min_price') is-invalid @enderror" value="{{ old('min_price',$property->min_price) }}" >--}}
{{--                                        <span class="text-danger">--}}
{{--                                            <strong id="min_price-error"></strong>--}}
{{--                                          </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-2" id="max_price_div" style="display: {{$property->price_type == 'fixed' ? 'none' :'block'}}" >--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="max_price" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property max price') }}</label>--}}
{{--                                        <input id="max_price" name="max_price" type="number" class="form-control @error('max_price') is-invalid @enderror" value="{{ old('max_price',$property->max_price) }}" autocomplete="type" autofocus placeholder="">--}}
{{--                                        <span class="text-danger">--}}
{{--                                            <strong id="max_price-error"></strong>--}}
{{--                                          </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                                {{--<div class="col-4">
                                    <div class="form-group">
                                        <label for="lng" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property langtude') }}</label>
                                        <input id="lng" name="lng" type="text" class="form-control @error('lng') is-invalid @enderror" value="{{ old('lng',$property->lng) }}" >
                                        @error('lng')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                          </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="lat" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property latitude') }}</label>
                                        <input id="lat" name="lat" type="text" class="form-control @error('lat') is-invalid @enderror" value="{{ old('lat',$property->lat) }}" >
                                        @error('lat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                          </span>
                                        @enderror
                                    </div>
                                </div>--}}

                            <div>
                                <br>
                                <button type="submit" class="btn btn-primary btn-info btn-block">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}
                                </button>
                            </div>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong>{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }} {{ MyHelpers::admin_trans(auth()->user()->id,'property other images') }}</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(\App\Image::where('imageable_type', 'App\Property')->where('imageable_id',$property->id)->get() as $image)
                            <div class="col-lg-3" id="image_{{$image->id}}" data-toggle="modal" data-target="#exampleModal{{$image->id}}">
                                <img  src="{{ asset($image->image_path)}}" class="img-thumbnail img-fluid" alt="">
                                <button onclick="deleteData('{{$image->id}}')" class="btn btn-danger btn-sm btn-block">مسح</button>
                            </div>

                            <div class="modal fade" id="exampleModal{{$image->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <img  src="{{ asset($image->image_path)}}" class="img-thumbnail img-fluid" alt="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{--<div class="table-responsive table--no-card m-b-30 data-table-parent">
                        <table class="table table-borderless table-striped table-earning data-table">
                            <thead>
                            <tr>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'image') }}</th>
                                <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>

                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>--}}
                </div> {{-- card-body div--}}
            </div>{{-- card div--}}
        </div>
    </div>

@endsection
@section('updateModel')
    @include('image.edit')
    @include('image.zoom')
    @include('image.confirmDelMsg')
@endsection


@section('scripts')
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

        $('#fixed_price,#min_price,#max_price').keyup(function(event) {

            // skip for arrow keys
            if (event.which >= 37 && event.which <= 40) return;

            // format number
            $(this).val(function(index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });
        });
        $('#fixed_price,#min_price,#max_price').val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    </script>
    <script>
        function deleteData(id) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'هل انت متأكد',
                text: "لن تكون قادر على التراجع فى هذا الأمر ؟",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                buttons: ["إلغاء","نعم , احذف !"],
            }).then(function(inputValue) {
                if (inputValue != null) {
                    $.ajax({
                        url: "{{ url('/image/delete') }}" + '/' + id,
                        type: "GET",
                        success: function(data) {
                            $('#image_'+id).remove();
                            swal({
                                title: 'تم!',
                                text: 'تم حذف ألصورة',
                                type: 'success',
                                timer: '750'
                            })
                        },
                        error: function() {
                            swal({
                                title: 'خطأ',
                                text: data.message,
                                type: 'error',
                                timer: '750'
                            })
                        }
                    });
                } else {

                }

            });
        }
        $(document).ready(function() {
            $('.data-table').DataTable({
                "language": {
                    "url": "{{route('datatableLanguage')}}"
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    // 'copyHtml5',
                    'excelHtml5',
                    // 'csvHtml5',
                    // 'pdfHtml5' ,
                    'print',
                    'pageLength'
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('image.list',['type'=>'Property' ,'id'=> $property->id ]) }}",
                columns: [
                    { data: 'path', name: 'path' },
                    { data: 'action', name: 'action' },
                ]
            });
        });
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
                        $('<input type="file" name="images[]"  id="'+index+'" hidden  value=""/>').insertAfter("#images");
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
        $('#description-error').html("");
        $('#description-error').html("");
        $('#images-error').html("");
        $('#num_of_bathrooms-error').html("");
        $('#num_of_kitchens-error').html("");
        $('#num_of_salons-error').html("");
        $('#num_of_rooms-error').html("");
        $('#type_id-error').html("");
        $('#area_id-error').html("");
        $('#video_url-error').html("");
        $('#is_published-error').html("");
        $('#latitude-error').html("");
        $('#city_id-error').html("");
        $(function() {
            $('#create_property').on('submit', function(e) {

                var $inputs = $('#create_property :input');
                var values = {};
                $inputs.each(function() {
                    values[this.name] = $(this).val();
                });
                if (!e.isDefaultPrevented()) {
                    url = "{{ route('property.update',$property->id) }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: new FormData($("#create_property")[0]),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            console.log(data)
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

                                if (data.errors.latitude) {
                                    $('#latitude-error').html(data.errors.latitude[0]);
                                }
                                if (data.errors.district_id) {
                                    $('#district_id-error').html(data.errors.district_id[0]);
                                }
                                if (data.errors.is_published) {
                                    $('#is_published-error').html(data.errors.is_published[0]);
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
                                swal({
                                    title: 'تم!',
                                    text: data.message,
                                    type: 'success',
                                    timer: '750'
                                })
                                location.replace("{{route('property.list')}}")
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

        $('.update_image_form').submit(function(event) {
            event.preventDefault();
            console.log($('.update_image_form').attr("action"));
            $.ajax({
                type: "POST",
                url: $('.update_image_form').attr("action") ,
                data: new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                    swal.fire({
                        title: data.msg,
                        type: data.type
                    }).then((result) => {
                        if (result.value) {
                            $('.modal').trigger("click");
                            if($(".data-table").length == 1) {
                                $('.data-table').DataTable().ajax.reload();
                            }
                        }
                    });

                },

                error: function(data) {
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $.each(errors.errors, function(key, value) {
                            var ErrorID = '#' + key + 'UpdateError';
                            $(ErrorID).text(value);
                            console.log(value);
                            console.log(ErrorID);
                        })
                    }
                }
            });
        });
        // Execute something when the modal window is shown.
        $('#edit_image').on('show.bs.modal', function (event) {
            /* Clear DATA Froml MODAL */
            document.getElementById("EditImage").reset();
            $('.error-note').html('');
            var button = $(event.relatedTarget); // Button that triggered the modal
            let id = button.data('id'); // Extract info from data-* attributes
            /* SET DATA TO MODAL */
            console.log(id);
            $('#image_id').val(id);
        });



        //////////////////////////////////////////////////////////////

        $(document).on("click", ".deleteBtn", function () {
            $("#Confirm").modal("show");
            $("#Confirm .deleteForm").attr("action",$("#Confirm .deleteForm").attr("href"));
            return false;
        })
        function deleteDate(id){

            $("#Confirm").modal("show");

        }
        $('#Confirm').on('show.bs.modal', function (event) {
            var btn_trans = "{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}" ;
            var loader = $('.btn-send');
            loader.html(btn_trans);
        });
        $('.delete_form').submit(function(event) {
            var btn = $(this);
            alert($(this).attr('id'))
            var loader = $('.btn-delete-send');
            var btn_trans ="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}"  ;
            var sending = "{{ MyHelpers::admin_trans(auth()->user()->id,'sending') }}"
            console.log($('.delete_form').attr("action"));
            $.ajax({
                type: "GET",
                url: $('.delete_form').attr("action") ,
                data: '',
                beforeSend: function() {
                    btn.attr('disabled', true);
                    loader.html("<i class='fa fa-spinner fa-spin fa-lg'></i> " + sending);
                },
                success: function(data){
                    swal.fire({
                        title: data.msg,
                        type: data.type
                    }).then((result) => {
                        if (result.value) {
                            loader.html(btn_trans);
                            btn.attr('disabled', false);
                            $('.modal').trigger("click");
                            if($(".data-table").length == 1) {
                                $('.data-table').DataTable().ajax.reload();
                            }
                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                        }
                    });

                },
                error: function(data) {
                    loader.html(btn_trans);
                    btn.attr('disabled', false);
                    var errors = data.responseJSON;
                },
                complete: function() {
                    $("body").css("padding-right", "0px !important");
                }
            });
        });

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
                zoom: 20,
                mapTypeId: 'roadmap'
            });
            infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();

            marker = new google.maps.Marker({
                position: { lat: {{$property->lat}},
                    lng: {{$property->lng}}},
                map: map,
                title: '{{ $property->addresss }}',
                icon: 'https://cdn2.iconfinder.com/data/icons/picons-basic-2/57/basic2-059_pin_location-128.png'

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
                            $('#address').val(results[0].formatted_address)
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

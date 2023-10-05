@extends('Customer.fundingReq.customerReqLayout')


@section('title') العقارات  @endsection
<style>
    .data td{
        color: #333;
    }
</style>

@section('content')
    <input name="_token" value="{{ csrf_token() }}" type="hidden">

    <div style="text-align: left; padding: 2% ; font-size:large">
        <a href="{{url('/customer') }}">
            الرئيسية
            <i class="fa fa-home"> </i>
        </a>
        |
        <a href="{{ url()->previous() }}">
            رجوع
            <i class="fa fa-arrow-circle-left"> </i>
        </a>

    </div>

    <div class="container">

        <div class="asks-form mt-5">
            <div class="head-div text-center wow fadeInUp">
                <h1>العقارات</h1>

            </div>

            @if(session()->has('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('success') }}
                </div>
            @endif

            @if(session()->has('errorSugg'))
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session()->get('errorSugg') }}
                </div>
            @endif


            <div class="add-new">
                <div class="">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-12 service-heading wow fadeInUp"  style="margin-top: 50px">
                            <span>{{ MyHelpers::guest_trans('Real Estate offers') }}</span>

                            <h3>{{ MyHelpers::guest_trans('handicraft_properties_for_you') }}</h3>
                        </div>
                        <div class="col-lg-12">
                            <form action="">
                                <div class="row pt-5 pb-3" >
                                    <div class="col-lg-12">
                                        إبحث
                                        <hr>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="type" class="control-label mb-1">نوع العقار</label>
                                            <select id="type" name="type" class="type form-control @error('type') is-invalid @enderror" style="height: 45px">
                                                <option disabled selected>أختار نوع العقار ..</option>
                                                @foreach($types as $type)
                                                    <option value="{{$type->id}}">{{$type->value}}</option>
                                                @endforeach
                                            </select>
                                            @error('area_id')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="area_id" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'property region') }}</label>
                                            <select id="area_id" name="area_id" class="area form-control @error('region') is-invalid @enderror" style="height: 45px">
                                                <option disabled selected>أختار المنطقة ..</option>
                                                @foreach($areas as $area)
                                                    <option value="{{$area->id}}">{{$area->value}}</option>
                                                @endforeach
                                            </select>
                                            @error('area_id')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="city_id" class="control-label mb-1">المدينه</label>
                                            <select id="city_id" name="city_id" class="city form-control @error('city_id') is-invalid @enderror" style="height: 45px">
                                                <option disabled selected>أختار المدينه ..</option>
                                            </select>
                                            @error('city_id')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="district_id" class="control-label mb-1">الحى </label>
                                            <select id="district_id" name="district_id" class="district form-control @error('district_id') is-invalid @enderror" style="height: 45px">
                                                <option disabled selected>أختار الحى ..</option>
                                            </select>
                                            @error('district_id')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="price_type" class="control-label mb-1"> نوع السعر</label>
                                            <select id="price_type" name="price_type" class="price_type form-control @error('region') is-invalid @enderror" style="height: 45px">
                                                <option disabled selected>أختار نوع السعر ..</option>
                                                <option value="fixed">سعر ثابت</option>
                                                <option value="range">سعر متغير</option>
                                            </select>
                                            @error('area_id')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_rooms" class="control-label mb-1">عدد الغرف</label>
                                            <input id="num_of_rooms" name="num_of_rooms" class="num_of_rooms form-control @error('num_of_rooms') is-invalid @enderror" style="height: 45px">
                                            @error('area_id')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_bathrooms" class="control-label mb-1">عدد دورات المياه </label>
                                            <input id="num_of_bathrooms" name="num_of_bathrooms" class="num_of_bathrooms form-control @error('num_of_bathrooms') is-invalid @enderror" style="height: 45px">
                                            @error('num_of_bathrooms')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_kitchens" class="control-label mb-1">عدد المطابخ</label>
                                            <input id="num_of_kitchens" name="num_of_kitchens" class="num_of_kitchens form-control @error('num_of_kitchens') is-invalid @enderror" style="height: 45px">
                                            @error('num_of_kitchens')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="num_of_salons" class="control-label mb-1">عدد الصالونات</label>
                                            <input id="num_of_salons" name="area_id" class="num_of_salons form-control @error('num_of_salons') is-invalid @enderror" style="height: 45px">
                                            @error('num_of_salons')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="price" class="control-label mb-1">السعر  </label>
                                            <input id="price" name="price" class="price form-control @error('price') is-invalid @enderror" style="height: 45px">
                                            @error('price')
                                            <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                       <div class=" col-lg-12" id="areaOfDefault">
                           @if(!empty($properties))
                               <div class="row fadeInUp" style="padding-top: 20px;">
                                   @foreach($properties as $Property)
                                       <div class=" col-xs-12 col-md-4 col-sm-6 col-lg-4 p-1">
                                           <div class="card">
                                               <div class="card-body text-center">
                                                   <img src="{{asset(@$Property->image()->first()->image_path)}}" alt="" class="img-fluid" style="height: 200px;">
                                                   <hr>
                                                   <h3 class="text-center"><b>تفاصيل العقار</b></h3>
                                                   <strong>{{ $Property->type->value }}</strong>
                                                   <br>
                                                   <span>{{ MyHelpers::guest_trans('sar') }}</span>
                                                   <strong>{{ $Property->price_type === 'fixed' ?$Property->fixed_price : $Property->max_price . '-' . $Property->min_price  }}</strong>
                                                   <br>
                                                   <span class="fa fa-map-marker"></span>
                                                   <span class="loc">{{$Property->district ? $Property->district->value.' /' :'' }}</span>
                                                   <span class="loc">{{$Property->city->value }} / </span>
                                                   <span class="loc">{{$Property->area->value }}</span>
                                                   <h6 class="pt-3"><a href="{{route('propertyDetails',$Property->id)}}" class="btn btn-primary" title="">تفاصيل العقار</a>
                                                   </h6>
                                               </div>
                                           </div>

                                       </div>
                                   @endforeach
                               </div>
                           @endif
                           <div class="col-lg-12 pt-3 text-center">
                               {{$properties->render()}}
                           </div>

                       </div>

                        <div class="col-lg-12" id="areaOfFilter">

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
@section('styles')
    <style>
        .card-1 {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            width: 40%;
            border-radius: 5px;
        }

        .card-1:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        .card-1 img {
            border-radius: 5px 5px 0 0;
        }

        .container-1 {
            padding: 2px 16px;
        }
    </style>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
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
                        get()
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
                        get()
                    }

                })
            }
        });

        $('.district').change(function(){
            get()
        });
        $('.price_type').change(function(){
            get()
        });
        $('.num_of_salons').keydown(function(){
            get()
        });
        $('.num_of_rooms').keydown(function(){

            get()
        });
        $('.num_of_bathrooms').keydown(function(){

            get()
        });
        $('.num_of_kitchens').keydown(function(){

            get()
        });
        $('.price').keydown(function(){
            get()
        });

        $('.type').change(function(){
            get();
        });

        function get() {
            var type = $('#type').val();
            var num_of_bathrooms = $('#num_of_bathrooms').val();
            var num_of_kitchens = $('#num_of_kitchens').val();
            var num_of_rooms = $('#num_of_rooms').val();
            var num_of_salons = $('#num_of_salons').val();
            var price_type = $('#price_type').val();
            var price = $('#price').val();
            var area_id = $('#area_id').val();
            var city_id = $('#city_id').val();
            var district_id = $('#district_id').val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('get.properties') }}",
                method:"POST",
                data:{ num_of_bathrooms:num_of_bathrooms, num_of_kitchens:num_of_kitchens,
                    num_of_rooms:num_of_rooms, num_of_salons:num_of_salons,
                    price_type:price_type, price:price, area_id:area_id, city_id:city_id,
                    district_id:district_id, type:type, _token:_token},
                success:function(result)
                {
                    $('#areaOfDefault').css('display','none');
                    $('#areaOfFilter').html(result);
                }

            })
        }


    </script>

    <script src="{{asset('calender/dist/jquery.simple-calendar.js')}}"></script>
    <script type="text/javascript" src="http://ericjgagnon.github.io/wickedpicker/javascript/smooth_scroll.js"></script>
    <script type="text/javascript" src="http://ericjgagnon.github.io/wickedpicker/wickedpicker/wickedpicker.min.js"></script>
@endsection

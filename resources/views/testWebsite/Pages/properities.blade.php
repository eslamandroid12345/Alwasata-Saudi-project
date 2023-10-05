@extends('testWebsite.layouts.master')

@section('title') العقارات @endsection


@section('pageMenu')
    @include('testWebsite.layouts.menuOfPages')
@endsection

@section('content')
    <input name="_token" value="{{ csrf_token() }}" type="hidden">

    <div class="myOrders">
        <div class="container">
            <div class="head-div text-center">
                <div class="order-my requstform">
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
                                                            <label for="area_id" class="control-label mb-1">المنطقة</label>
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
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="address" class="control-label">أدخل جزء من العنوان</label>
                                                            <input type="text" id="address" name="address" class="address form-control @error('address') is-invalid @enderror" style="height: 45px;width: 100%;margin: 0">
                                                            @error('address')
                                                            <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label for="price_type" class="control-label mb-1"> نوع السعر</label>
                                                            <select id="price_type" name="price_type" class="price_type form-control @error('region') is-invalid @enderror" style="height: 45px;">
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
                                                            <label for="num_of_rooms" class="control-label ">عدد الغرف</label>
                                                            <input id="num_of_rooms" name="num_of_rooms" class="num_of_rooms form-control @error('num_of_rooms') is-invalid @enderror" style="height: 43px;max-width: 100%;margin: 0">
                                                            @error('num_of_rooms')
                                                            <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label for="num_of_bathrooms" class="control-label mb-1">عدد دورات المياه </label>
                                                            <input id="num_of_bathrooms" name="num_of_bathrooms" class="num_of_bathrooms form-control @error('num_of_bathrooms') is-invalid @enderror"style="height: 43px;max-width: 100%;margin: 0">
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
                                                            <input id="num_of_kitchens" name="num_of_kitchens" class="num_of_kitchens form-control @error('num_of_kitchens') is-invalid @enderror"style="height: 43px;max-width: 100%;margin: 0">
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
                                                            <input id="num_of_salons" name="area_id" class="num_of_salons form-control @error('num_of_salons') is-invalid @enderror"style="height: 43px;max-width: 100%;margin: 0">
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
                                                            <input id="price" name="price" class="price form-control @error('price') is-invalid @enderror" style="height: 43px;max-width: 100%;margin: 0">
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
                                                                    <span class="loc">{{$Property->address}}</span>
                                                                    <h6 class="pt-3"><a href="{{route('propertyDetails.guest',$Property->id)}}" class="btn btn-primary" title="">تفاصيل العقار</a>
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
    </script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script>


        $('.address').keydown(function(){
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
            var address = $('#address').val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('get.properties') }}",
                method:"POST",
                data:{ num_of_bathrooms:num_of_bathrooms, num_of_kitchens:num_of_kitchens,
                    num_of_rooms:num_of_rooms, num_of_salons:num_of_salons,
                    price_type:price_type, price:price, area_id:area_id, address:address, type:type, _token:_token},
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

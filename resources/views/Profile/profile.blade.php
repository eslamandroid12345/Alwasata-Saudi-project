@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Information') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Account') }}
@endsection


@section('css_style')


@endsection

@section('customer')
    <div class="col-md-12">
        @if (session('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{!! session('success') !!}</li>
                </ul>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{!! session('error') !!}</li>
                </ul>
            </div>
        @endif
    </div>

    <div class="container-fluid px-lg-5">
        <div class="ContTabelPage">
            <section class="new-content mt-5">
                <div class="container-fluid">

                    <div class="row "  >
                        <div class="col-md-6 offset-md-3">
                            <div class="row">
                                <div class="col-lg-12   mb-md-0">
                                    <div class="userFormsInfo  ">
                                        <div class="headER topRow text-center">
                                            <i class="fas fa-user"></i>
                                            <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Information') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Account') }}</h4>
                                        </div>
                                        <form action="{{route('updateProfile')}}" method="post" class="">
                                            @csrf
                                            <div class="userFormsContainer mb-3">
                                                <div class="userFormsDetails topRow">
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <div class="form-group">
                                                                <label for="username2">{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</label>
                                                                <input type="text" id="username2" name="name" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}"
                                                                       class="form-control" value="{{$user->name}}">
                                                                @if ($errors->has('name'))
                                                                    <small class="help-block col-md-12">
                                                                      <strong style="color:red ;font-size:10pt">{{ $errors->first('name') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <div class="form-group">
                                                                <label for="email2">{{ MyHelpers::admin_trans(auth()->user()->id,'Email') }}</label>
                                                                <input type="email" id="email2" name="email" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Email') }}"
                                                                       class="form-control" value="{{$user->email}}">
                                                                @if ($errors->has('email'))
                                                                    <small class="help-block col-md-12">
                                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('email') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <div class="form-group">
                                                                <label for="password">{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}</label>
                                                                <input type="password" id="password" name="password" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}"
                                                                       class="form-control" value="" >
                                                                <input type="checkbox" onclick="myFunction()" style="float: right ;margin-top: -8px;">
                                                                <small  style="margin-top: 15px; padding-top: 15px ; margin-right: 5px">{{ MyHelpers::admin_trans(auth()->user()->id,'Show') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Password') }} </small>

                                                                @if ($errors->has('password'))
                                                                    <small class="help-block col-md-12">
                                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('password') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <div class="form-group">
                                                                <label for="locale">{{ MyHelpers::admin_trans(auth()->user()->id,'Language') }}</label>
                                                                <select id="locale" name="locale" placeholder="{{ MyHelpers::admin_trans(auth()->user()->id,'Language') }}"
                                                                        class="form-control">
                                                                    <option value="en" {{ ($user->locale == 'en') ? 'selected' : '' }}> {{ MyHelpers::admin_trans(auth()->user()->id,'English') }} </option>
                                                                    <option value="ar" {{ ($user->locale == 'ar') ? 'selected' : '' }}> {{ MyHelpers::admin_trans(auth()->user()->id,'Arabic') }}   </option>
                                                                </select>
                                                                @if ($errors->has('locale'))
                                                                    <small class="help-block col-md-12">
                                                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('locale') }}</strong>
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        @if($user->role == 6)
                                                            <div class="col-12 mb-3">
                                                                <label for=""><b>أين يخدم ؟</b></label>
                                                                <div class="form-group">
                                                                    <label for="locale">{{ MyHelpers::admin_trans(auth()->user()->id,'area') }}</label>
                                                                    <select id="area_id" name="area_id[]" multiple class="area  select2-request form-control @error('region') is-invalid @enderror">
                                                                        @foreach($areas as $area)
                                                                            @if($user->areas()->count() > 0)
                                                                                <option value="{{$area->id}}" {{ in_array($area->id,$user->areas->pluck("value")->toArray()) ? 'selected' : '' }}>{{$area->value}}</option>
                                                                            @else
                                                                                <option value="{{$area->id}}" >{{$area->value}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                    @if ($errors->has('locale'))
                                                                        <small class="help-block col-md-12">
                                                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('locale') }}</strong>
                                                                        </small>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="city_id" class="control-label mb-1">المدينه</label>
                                                                    <select id="city_id" name="city_id[]" multiple class="city  select2-request  form-control @error('city_id') is-invalid @enderror">

                                                                    </select>
                                                                    @error('city_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="district_id" class="control-label mb-1">الحى </label>
                                                                    <select id="district_id" name="district_id[]" class="district  select2-request  form-control @error('district_id') is-invalid @enderror" multiple>
                                                                    </select>
                                                                    @error('district_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="direction" class="control-label mb-1">الإتجاه</label>
                                                                    <select id="direction" name="direction" class="city  select2-request  form-control @error('city_id') is-invalid @enderror" style="height: 45px">
                                                                        <option disabled selected>أختار الإتجاه ..</option>
                                                                        <option value="west" {{optional($user->direction)->value =='west' ? 'selected' : '' }}>شمالي</option>
                                                                        <option value="south" {{optional($user->direction)->value =='south' ? 'selected' : '' }}>جنوبي</option>
                                                                        <option value="east" {{optional($user->direction)->value =='east' ? 'selected' : '' }}>شرقي</option>
                                                                        <option value="north" {{optional($user->direction)->value =='north' ? 'selected' : '' }}>غربي</option>
                                                                    </select>
                                                                    @error('city_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                      <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="col-12">
                                                            <button type="submit" class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient">{{ MyHelpers::admin_trans(auth()->user()->id,'Submit Profile') }}</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </section>
        </div>
    </div>


@endsection

@section('scripts')

<script>

function myFunction() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
$(document).ready(function (){
    AjaxArea()
    AjaxCity()
    $('#area_id').change(function (){
        var city_id = $('#city_id');
        city_id.html("");
        // console.log(area_id)
        AjaxArea();

    });
    $('#city_id').change(function (){
        var district_id = $('#district_id');
        district_id.html("");
        AjaxCity()
    });
});
function AjaxCity(){

        var district_id = $('#district_id');

        // console.log(area_id)
        $.ajax({
            url:'{{route("all.gets.districts")}}',
            data:{
                id:$("#city_id").val(),
                profile :"profile"
            },
            success:function (data){
                district_id.append(data);
            }
        });

}

function AjaxArea(){
    var city_id = $('#city_id');
    $.ajax({
        url: '{{route("all.gets.cities")}}',
        data:{
            id:$("#area_id").val(),
            profile :"profile"
        },
        success:function (data){
            city_id.append(data);
        }
    });
}
</script>
@endsection

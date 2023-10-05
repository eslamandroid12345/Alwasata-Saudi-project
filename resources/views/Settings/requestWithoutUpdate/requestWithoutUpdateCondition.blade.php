@extends('layouts.content')

@section('title')
شروط نقل الطلبات الغيرمحدثة
@endsection

@section('css_style')

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .activeColor {
        color: green;
    }

    .notactiveColor {
        color: red;
    }
</style>

@endsection

@section('customer')
<!-- MAIN CONTENT-->



<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! session('success') !!}</li>
                        </ul>
                    </div>
                    @elseif(session('error'))
                    <div class="alert alert-error">
                        <ul>
                            <li>{!! session('error') !!}</li>
                        </ul>
                    </div>
                    @endif


                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session()->get('message') }}
                    </div>
                    @endif


                    <div id="msg2" class="alert alert-dismissible" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    طلبات بدون تحديث
                </div>
                <div class="card-body card-block">

                    <hr>


                    <form action="{{route('admin.updateRequestWithoutUpdate')}}" method="post" class="">
                        @csrf

                        <div class="row d-flex">


                            <div class="form-group col-lg-1">
                            </div>


                            <div id="" class="form-group col-lg-6">
                                <label for="classifcations" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'classifcations') }} {{ MyHelpers::admin_trans(auth()->user()->id,'The Request') }}</label>

                                <select id="classifcations" name="classifcations[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                    @if($classRequestWithoutUpdate->get()->count() > 0)

                                    @foreach($getAgentClass as $item)
                                    <option value="{{ $item->id }}" {{ DB::table('classification_for_request_without_update')->where('class_id',$item->id)->get()->count() > 0 ? 'selected' : '' }}>{{ $item->value }}</option>
                                    @endforeach

                                    @else
                                    @foreach($getAgentClass as $item)
                                    <option value="{{ $item->id }}">{{ $item->value }}</option>
                                    @endforeach
                                    @endif


                                </select>

                                @if ($errors->has('classifcations'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('classifcations') }}</strong>
                                </span>
                                @endif


                            </div>

                            <div class="form-group col-lg-4">
                                <label for="hourToLeave" class="control-label mb-1">الأيام</label>

                                <input type="number" name="hourToLeave" id="hourToLeave" value="{{old('timeToLeave',$allwoedHourToLeaveToLeaveRequestWithoutUpdate->option_value) }}" class="form-control" placeholder="مثال : 3 أيام ">

                                @if ($errors->has('hourToLeave'))
                                <span class="help-block">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('hourToLeave') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-1">
                            </div>

                        </div>

                        <div style="text-align:left">

                            <button type="submit" class="btn btn-info">حفظ التعديلات</button>

                        </div>

                    </form>



                </div>
            </div>


        </div>
    </div>
</div>

@endsection



@section('scripts')


<script>
    ////////////////////////////////////////////////////

    $(document).ready(function() {

        $('#classifcations').select2();

    });

    /////////////////////////////////////////
</script>
@endsection
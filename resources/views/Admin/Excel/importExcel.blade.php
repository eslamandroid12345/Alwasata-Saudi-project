@extends('layouts.content')


@section('title')
استيراد الطلبات
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


                    @if ( session()->has('excelCount') )
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        تم اضافة {{ session()->get('excelCount') }} ، من أصل {{ session()->get('countRow') }}
                    </div>
                    @endif




                    <div id="msg2" class="alert alert-dismissible" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-4">

                </div>

                <div class="col-4">



                    <form action="{{ route('admin.importExcel') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <input style="text-align: center;" type="file" name="file">


                        <button class="btn btn-info btn-block" id="upload" type="submit" style="padding: 2%;">
                            <i class="fa fa-download"></i> استيراد الطلبات </button>

                        @if ($errors->has('file'))
                        <span class="help-block">
                            <strong style="color:red ;font-size:10pt">{{ $errors->first('file') }}</strong>
                        </span>
                        @endif

                    </form>


                </div>


                <div class="col-4">

                </div>
            </div>

            <br><br>


            <div class="card">
                <div class="card-header">
                    استيراد الطلبات
                </div>

                <div class="card-body card-block">

                    <form action="{{route('admin.updateAgentExcel')}}" method="post" class="">
                        @csrf


                        <div class="row d-flex">


                            <div class="form-group col-lg-4">
                            </div>

                            <div id="agnetdiv" class="form-group col-lg-4">
                                <label for="agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>

                                <select id="agents" name="agents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                    @if($excel_agents_count > 0 )

                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ in_array( $agent->id, $excel_agents->pluck('user_id')->toArray() ) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                    @endforeach

                                    @else

                                    @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach

                                    @endif

                                </select>
                            </div>

                            <div class="form-group col-lg-4">
                            </div>

                        </div>

                        <div style="text-align:left">

                            <button type="submit" class="btn"><i class="fas fa-save" style="font-size:20px;color:blue" title="حفظ التعديلات"></i></button>

                        </div>

                    </form>

                </div>
            </div>


        </div>
    </div>
</div>

@endsection

@section('confirmMSG')
@endsection

@section('scripts')


<script>
    ////////////////////////////////////////////////////

    $(document).ready(function() {

        $('#agents').select2();

    });

 
    /////////////////////////////////////////
</script>
@endsection
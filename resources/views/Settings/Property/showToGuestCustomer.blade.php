@extends('layouts.content')

@section('title')
إظهار العقارات للعميل الغير مسجل
@endsection
@section('css_style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
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
                   إظهار العقارات للعميل الغير مسجل
                </div>
                <div class="card-body card-block">

                    <div>


                        @if ($showToGuestCustomerStatus->option_value == "false")
                        <span id="toggleText" style="color: red;">غير مفعل</span>
                        @else
                        <span id="toggleText" style="color: green;">مفعل</span>
                        @endif

                        <label class="switch">
                            <input name="isActive" type="checkbox" {{$showToGuestCustomerStatus->option_value == "true"  ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                    </div>

        

                </div>
            </div>


        </div>
    </div>
</div>

@endsection

@section('confirmMSG')
@include('Settings.Forms.confirmationMsg')

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    ////////////////////////////////////////////////////

    var checkbox = document.querySelector("input[name=isActive]");

    var toggleText = document.getElementById("toggleText");
    checkbox.addEventListener('change', function() {

        toggleText.style.color = '';
        toggleText.classList.remove("activeColor");
        toggleText.classList.remove("notactiveColor");

        if (this.checked) {

            $.get("{{route('admin.updateshowToGuestCustomer')}}", {}, function(data) {
                 //console.log(data);
                if (data.status != 0) {
                    toggleText.innerHTML = 'مفعل';
                    toggleText.classList.add("activeColor");
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                } else
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            });

        } else {
            $.get("{{route('admin.updateshowToGuestCustomer')}}", {}, function(data) {
                if (data.status != 0) {
                      //console.log(data);
                    toggleText.innerHTML = 'غير مفعل';
                    toggleText.classList.add("notactiveColor");
                    $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                } else
                    $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
            });
        }
    });

    ///////////////////////////////////

</script>
@endsection
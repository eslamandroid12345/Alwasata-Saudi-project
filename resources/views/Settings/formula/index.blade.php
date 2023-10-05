@extends('layouts.content')
@section('title')
    التعديل على معادلة الحسبة
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

                        @if ($errors->has('trainings'))
                            <div class="alert alert-error">
                                <ul>
                                    <li style="color:red ;">{{ $errors->first('trainings') }}</li>
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
                      التعديل على معادلة الحسبة
                    </div>

                    <div class="card-body card-block">
                        <form action="{{route('admin.formula.agents')}}" id="formPost" method="post" class="">
                            @csrf
                            <div class="row d-flex">


                                <div id="agnetdiv" class="form-group col-lg-12">
                                    <label for="agent" class="control-label mb-1">المستخدمين الذى لابد من موافقة الأدمن على المقترح</label>

                                    <select id="agents" name="agents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                    @foreach($agents_unauth as $agent)
                                        <option value="{{ $agent->id }}" {{ in_array($agent->id, $users->where('user_id',$agent->id)->pluck('user_id')->toArray()) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                    @endforeach

                                    </select>

                                    @if ($errors->has('agents'))
                                        <span class="help-block">
                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('agents') }}</strong>
                                        </span>
                                    @endif

                                    <label for="authorizes" class="control-label mb-1 mt-5">المستخدمين الذى لا يلزم موافقة الأدمن على المقترح</label>

                                    <select id="authorizes" name="authorizes[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                    @foreach($agents_auth as $agent)
                                        <option value="{{ $agent->id }}" {{ in_array($agent->id, $auths->where('user_id',$agent->id)->pluck('user_id')->toArray()) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                    @endforeach

                                    </select>

                                    @if ($errors->has('authorizes'))
                                        <span class="help-block">
                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('authorizes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-2 text-left">
                                    <label for="" class="control-label mb-1"></label>
                                    <button type="submit" id="submit-btn" class="btn btn-success"><i id="updatePremtion" class="fas fa-save p-1 text-white"  style="font-size:20px;color:blue" title="حفظ التعديلات"></i>حفظ التعديلات</button>
                                </div>
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
        $(document).ready(function() {
            $('#types,#agents,.type,#authorizes').select2();
            $('#submit-btn').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('admin.formula.agents')}}",
                    type: "POST",
                    data: new FormData($("#formPost")[0]),
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        swal({
                            title: 'تم!',
                            text: 'تم تحديث المستخدمين بنجاح',
                            type: 'success',
                            timer: '750'
                        })

                    },
                    error: function (data) {
                        swal({
                            title: 'خطأ',
                            text: data.message,
                            type: 'error',
                            timer: '750'
                        })
                    }
                });
            });

        });
		$("#agents").on("select2:select", function (evt) {
            $.each( $('#agents').select2('val'), function( key, value ) {
                detachedMember = $('#authorizes option[value="'+value+'"]').detach();
            });
		}).on("select2:unselect", function (evt) {
            console.log(evt.params.data.id)
            // Create a DOM Option and pre-select by default
            var newOption = new Option(evt.params.data.text,evt.params.data.id, false, false);
            // Append it to the select
            $('#authorizes').append(newOption);
        });

        $("#authorizes").on("select2:select", function (evt) {
            $.each( $('#authorizes').select2('val'), function( key, value ) {
                $('#agents option[value="'+value+'"]').detach();
            });
        }).on("select2:unselect", function (evt) {
            console.log(evt.params.data.id)
            // Create a DOM Option and pre-select by default
            var newOption = new Option(evt.params.data.text,evt.params.data.id, false, false);
            // Append it to the select
            $('#agents').append(newOption);
        });

		/*
		<input type="checkbox" id="orders_lives_in_ccs" name="orders[lives_in_ccs]" class="lives_in_ccs">
<select id="orders_shipping_from" name="orders[shipping_from]" required="required" class="shipping_from toSelect2">
    <option value="" selected="selected">-- SELECCIONAR --</option>
    <option value="MRW">MRW - COBRO EN DESTINO</option>
    <option value="DOMESA">DOMESA - COBRO EN DESTINO</option>
    <option value="ZOOM">GRUPO ZOOM - COBRO EN DESTINO</option>
</select>

$(function () {
    $('.toSelect2').select2();
    var detachedMember;
    $('.lives_in_ccs').click(function () {
        if (this.checked) {
            if ($('.toSelect2').select2('val') == 'MRW')
                $('.toSelect2').select2('val','');
            detachedMember = $('.shipping_from option[value="MRW"]').detach();


        } else {
            $('.shipping_from option[value=""]').after(detachedMember);
        }

        $(".secure_shipping").toggle(this.checked);
    });
});


		*/
    </script>
@endsection

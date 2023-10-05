@extends('layouts.content')


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
                        إعدادات الحسبة للسيناريو [{{$scenario->name}}]
                    </div>

                    <div class="card-body card-block">
                        <form action="{{route('admin.scenarios.agents')}}" id="formPost" method="post" class="">
                            @csrf
                            <div class="row d-flex">

                                <div id="traindiv" class="form-group col-lg-12">
                                    <label for="scenario_id" class="control-label mb-1">السيناريو </label>
                                    <input readonly type="text" name="scenario" id="scenario" value="{{$scenario->name}}" class="form-control" disabled>
                                    <input type="hidden" name="scenario_id" value="{{$scenario->id}}">
                                </div>


                                <div id="agnetdiv" class="form-group col-lg-12">
                                    <label for="agent" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agents') }}</label>

                                    <select id="agents" name="agents[]" onfocus='this.size=3;' onblur='this.size=1;' class="form-control" multiple="multiple">

                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ in_array($agent->id, $scenario->users->where('user_id',$agent->id)->pluck('user_id')->toArray()) ? 'selected' : '' }}>{{ $agent->name }}</option>
                                        @endforeach

                                    </select>

                                    @if ($errors->has('agents'))
                                        <span class="help-block">
                                            <strong style="color:red ;font-size:10pt">{{ $errors->first('agents') }}</strong>
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
        ////////////////////////////////////////////////////

        $(document).ready(function() {
        function addUsers(){
            $.ajax({
                url: "{{route('admin.scenarios.agents')}}",
                type: "POST",
                data: new FormData($("#formPost")[0]),
                contentType: false,
                processData: false,
                success: function (data) {
                    swal({
                        title: 'تم!',
                        text: 'تم تحديث الإستشارين بنجاح',
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
        }
            $('#types,#agents,.type').select2();
        $('#submit-btn').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('admin.scenarios.check')}}",
                    type: "POST",
                    //                        data : $('#modal-form form').serialize(),
                    data: new FormData($("#formPost")[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 1) {
                            swal({
                                title: 'هل انت متأكد',
                                text: "يتواجد المستخدمين  [ "+data.names+"] فى سيناريوهات أخرى ، بموافقتك سيتم حذفهم من أي سيناريوهات أخرى ؟ ",
                                type: 'warning',
                                showCancelButton: true,
                                showConfirmButton: true,
                                cancelButtonColor: '#d33',
                                confirmButtonColor: '#3085d6',
                                buttons: ["إلغاء","نعم , احذف !"], 
                            }).then(function(inputValue) {
                                if (inputValue != null) {
                                    addUsers()
                                }
                            });
                        }else {
                            addUsers()
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

              /*  if(arr.length == 0){
                    $('#formPost').submit();
                }*/
            });

        });
        /////////////////////////////////////////
    </script>
@endsection

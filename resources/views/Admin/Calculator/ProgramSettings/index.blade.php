@extends('layouts.content')
@section('title')
    تخصيص نتائج البرامج
@endsection
@section('css_style')
@endsection
@section('customer')
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #0a6ebd; color: white;">
                                تخصيص نتائج البرامج
                            </div>
                            <div class="card-body card-block">
                                
                                <div id="msg2" class="alert alert-dismissible" style="display:none;">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            
                              

                                <form action="{{route('admin.updateCalculatorSetting')}}" method="post" class="">
                                    @csrf
                                    <div class="row mx-1 py-1 contREsultRow">
                                        <div class="col-12 mb-md-0">
                                            <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap">
                                                <p> شخصي </p>
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                            <div class="contREsult" style="display: none;">
                                                <div class="row">
                                                    @foreach($getPersonalSettings as $personalSetting)
                                                        <div class="form-group col-lg-4 col-md-6" style="color: white!important;">
                                                            <input type="checkbox" value="1" {{ $personalSetting->option_value === 1 ? 'checked' : ''}}
                                                            data-id="{{ $personalSetting->id }}" name="checkValue" id="isChecked" class="checkValue">
                                                            <label class="control-label mb-1"> {{ $personalSetting->value_ar }} </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-1 py-1 contREsultRow">
                                            <div class="col-12 mb-md-0">
                                                <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap">
                                                    <p> مرن 2×1 </p>
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="contREsult" style="display: none;">
                                                    <div class="row">
                                                        @foreach($getFlexibleSettings as $flexibleSetting)
                                                            <div class="form-group col-lg-4 col-md-6" style="color: white!important;">
                                                                <input type="checkbox" value="1" {{ $flexibleSetting->option_value === 1 ? 'checked' : ''}}
                                                                data-id="{{ $flexibleSetting->id }}" name="checkValue" id="isChecked" class="checkValue">
                                                                <label class="control-label mb-1"> {{ $flexibleSetting->value_ar }} </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="row mx-1 py-1 contREsultRow">
                                            <div class="col-12 mb-md-0">
                                                <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap">
                                                    <p> عقاري </p>
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>
                                                <div class="contREsult" style="display: none;">
                                                    <div class="row">
                                                        @foreach($getPropertySettings as $getPropertySetting)
                                                            <div class="form-group col-lg-4 col-md-6" style="color: white!important;">
                                                                <input type="checkbox" value="1" {{ $getPropertySetting->option_value === 1 ? 'checked' : ''}}
                                                                data-id="{{ $getPropertySetting->id }}" name="checkValue" id="isChecked" class="checkValue">
                                                                <label class="control-label mb-1"> {{ $getPropertySetting->value_ar }} </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="row mx-1 py-1 contREsultRow">
                                        <div class="col-12 mb-md-0">
                                            <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap">
                                                <p> ممتد </p>
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                            <div class="contREsult" style="display: none;">
                                                <div class="row">
                                                    @foreach($getExtendedSettings as $getExtendedSetting)
                                                        <div class="form-group col-lg-4 col-md-6" style="color: white!important;">
                                                            <input type="checkbox" value="1" {{ $getExtendedSetting->option_value === 1 ? 'checked' : ''}}
                                                            data-id="{{ $getExtendedSetting->id }}" name="checkValue" id="isChecked" class="checkValue">
                                                            <label class="control-label mb-1"> {{ $getExtendedSetting->value_ar }} </label>
                                                        </div>
                                                    @endforeach
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
    </div>
@endsection
@section('scripts')
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
    </script>
    <script>
        let checkValue = Array.prototype.slice.call(document.querySelectorAll('.checkValue'));
        checkValue.forEach(function(html) {
            let switchery = new Switchery(html,  { color: '#186abd', secondaryColor: '#323538', jackColor: '#fff', jackSecondaryColor: '#fff' , size:'small'});
        });
        $(document).ready(function(){
            $('.checkValue').change(function () {
                let checkValue = $(this).prop('checked') === true ? 1 : 0;
                let id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{route('changeFlexible')}}',
                    data: {'checkValue': checkValue, 'id': id},
                    success: function (data) {
{{--                        var banksUrl = '{{ route("admin.FlexibleProgramCustomize") }}';--}}
                        if (data.status == 1) {
                            $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                            // window.location.href = banksUrl;
                        } else if (data.status == 0) {
                            $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection


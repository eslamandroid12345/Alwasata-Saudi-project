@extends('layouts.content')

@section('title')
    التحكم برصيد الأجازات
@endsection
@section('css_style')

<style>
    .middle-screen {
        height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: -2 !important;
        background-color: #000;
    }

    .tooltips {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltips .tooltipstext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -60px;
    }

    .tooltips .tooltipstext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: black transparent transparent transparent;
    }

    .tooltips:hover .tooltipstext {
        visibility: visible;
    }
    .tableUserOption .form-control {
        height: auto;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        border: 1px solid #E1E1E1;
        box-shadow: none;
    }
</style>
{{-- NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
<div>
    @if (session('msg'))
    <div id="msg" class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg') }}
    </div>
    @endif
</div>
@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif
<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>


<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> التحكم برصيد الأجازات :</h3>

    </div>
</div>
<br>





<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-lg-12">
                <div class="tableUserOption  flex-wrap row p-5">
                    <form id="form-contact" method="POST" class="form-horizontal col-lg-12" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="form-group">
                            <label for="AdminVacanciesCount"> التحكم برصيد الأجازات </label>
                            <input type="number" class="form-control" value="{{$AdminVacanciesCount->option_value}}" id="AdminVacanciesCount" name="AdminVacanciesCount">
                            <span id="question-error" class="text-danger"></span>
                        </div>
                        <div class="form-group">
                            <hr>
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('css_style')
{{--Sweet Alert--}}
<script src="{{ asset('backend/sweetalert/sweetalert2.min.js') }}"></script>
<link href="{{ asset('backend/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script>
        $(function() {
            $('#form-contact').on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    url = "{{ route('admin.vacancies.count.post') }}";

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: new FormData($("#form-contact")[0]),
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.errors) {
                                if (data.errors.question) {
                                    $('#question-error').html(data.errors.question[0]);
                                }
                            }
                            if (data.success) {
                                swal({
                                    title: 'تم!',
                                    text: data.message,
                                    type: 'success',
                                    timer: '750'
                                })
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
    </script>
@endsection

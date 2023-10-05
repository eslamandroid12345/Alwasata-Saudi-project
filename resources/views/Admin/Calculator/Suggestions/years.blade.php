@extends('layouts.content')
@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'extra_funding_years_calculator_settings') }}
@endsection
@section('css_style')
<style>
    .middle-screen {
        height: 100%;
        widtd: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .commentStyle {
        max-widtd: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .reqNum {
        widtd: 1%;
    }

    .reqType {
        widtd: 2%;
    }

    .boldRed {
        font-weight: bold !important;
        color: #4c110f !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">
@endsection
@section('customer')
@if(session()->has('message'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@elseif(\Session::has('msg'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {!! \Session::get('msg') !!}
</div>
@else
@endif
@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif
@if(Session::has('errors'))
<script>
    $(document).ready(function() {
        $('#updateJobPositionModal').modal({
            show: true
        });
    });
</script>
@endif
<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> {{$data->text}}</h3>
    </div>
</div>
<br>

<div class="card">
    <div class="card-body">

        <br>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header text-center">
                        معلومات المقترح
                    </div>
                    <div class="card-body pt-4 pb-4">
                        إسم المقترح : {{$data->user->name}}
                        <br>
                        الوظيفة : {{$data->user->roleName}}
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center text-success">
                        عدد الموافقة
                    </div>
                    <div class="card-body text-center">
                        <h1 class="text-success">
                            {{$data->suggests
                                                ->where('vote','yes')
                                                ->count()}}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center text-danger">
                        عدد الرفض
                    </div>

                    <div class="card-body text-center">
                        <h1 class="text-danger">
                            {{$data->suggests
                                                ->where('vote','no')
                                                ->count()}}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tableBar">
    <div class="dashTable">
        <div class="card mt-3">
            <div class="card-body">
                <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                    <thead>
                        <tr>
                            <th> الخاصية </th>
                            <th> الحالى </th>
                            <th> وقت التقديم </th>
                            <th> المقترح </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="{{$data->text != $main['text'] ? 'boldRed' : ''}}">البنك </td>
                            <td> {{$main['text']}} </td>
                            <td> {{$data->text}} </td>
                            <td> {{$data->text}} </td>
                        </tr>
                        <tr>
                            <td class="{{$data->job_position_id_to_string != $main['job_position_id_to_string'] ? 'boldRed' : ''}}">العمل </td>
                            <td> {{$main['job_position_id_to_string']}} </td>
                            <td> {{$data->job_position_id_to_string_api}} </td>
                            <td> {{$data->job_position_id_to_string}} </td>
                        </tr>
                        <tr>
                            <td class="{{$data->residential_support_to_string != $main['residential_support_to_string'] ? 'boldRed' : ''}}">الدعم السكني </td>
                            <td> {{$main['residential_support_to_string']}} </td>
                            <td> {{$data->residential_support_to_string_api}} </td>
                            <td> {{$data->residential_support_to_string}} </td>
                        </tr>
                        <tr>
                            <td class="{{$data->guarantees_to_string != $main['guarantees_to_string'] ? 'boldRed' : ''}}">الضمانات </td>
                            <td> {{$main['guarantees_to_string']}} </td>
                            <td> {{$data->guarantees_to_string_api}} </td>
                            <td> {{$data->guarantees_to_string}} </td>
                        </tr>
                        <tr>
                            <td class="{{$data->years != $main['years'] ? 'boldRed' : ''}}">السنوات </td>
                            <td> {{$main['years']}} </td>
                            <td> {{$data->years_api}} </td>
                            <td> {{$data->years}} </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<div class="tableBar">
    <div class="dashTable">
        <div class="card mt-3">
            <div class="card-body">
                @if($data->suggests->count()!=0)


                <table id="pendingReqs-table" class="table table-bordred table-striped data-table">
                    <thead>
                        <tr>
                            <th> المستخدم </th>
                            <th> المسمى الوظيفى </th>
                            <th> التقييم </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data->suggests as $item)
                        <tr>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->user->roleName}}</td>
                            <td> <span class="badge p-1 badge-{{$item->vote == 'yes' ?'primary':'danger'}}">{{$item->vote == 'yes' ?'مقبول':'مرفوض'}}</span>
                                @if($item->vote == 'no')

                                <hr>

                                <p style="font-weight: bold;color:#4c110f">سبب الرفض : <span style="font-weight:normal;color:black;">{{$item->no_reason}}</span></p>

                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
                @else
                <div class="alert alert-info text-center">
                    لا يوجد تصويتات حتى الآن
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
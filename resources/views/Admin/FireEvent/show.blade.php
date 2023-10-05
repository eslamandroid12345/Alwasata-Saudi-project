@extends('layouts.content')

@section('title')
 ادوات القياس - التفاعل مع العقارات

@endsection


@section('customer')



@if(session()->has('message'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3> ادوات القياس - التفاعل مع العقارات: </h3>

    </div>
</div>
<br>



<div class="tableBar">
    <div class="topRow">
        <div class="row align-items-center text-center text-md-left">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">إسم المتعاون</label>
                    <input type="text" disabled class="form-control" value="{{$property->creator->name}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">نوع العقار</label>
                    <input type="text" disabled class="form-control"  value="{{$property->type->value}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">سعر العقار</label>
                    <input type="text" disabled class="form-control" value="{{$property->fixed_price}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">حالة العقار</label>
                    @if ($property->is_published != 0)
                    <input type="text" disabled class="form-control" value="منشور">
                    @else
                    <input type="text" disabled class="form-control" value="معطل">
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">الوصف</label>
                    <input type="text" disabled class="form-control" value="{{$property->description}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">عنوان العقار</label>
                    <input type="text" disabled class="form-control" value="{{$property->address}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">المدينة</label>
                    <input type="text" disabled class="form-control" value="{{$property->city->value ?? ''}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">المنطقة</label>
                    <input type="text" disabled class="form-control" value="{{$property->areaName->value ?? ''}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">الحي</label>
                    <input type="text" disabled class="form-control" value="{{$property->district->value ?? ''}}">
                </div>
            </div>
        </div>
    </div>
</div>



@endsection


@section('updateModel')
@endsection

@section('scripts')


@endsection

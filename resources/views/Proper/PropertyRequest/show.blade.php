@extends('layouts.content')

@section('title')
    عرض تفاصيل الطلب العقاري
@endsection
@section('css_style')

    <link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">

    <style>
        .middle-screen {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
        }

        td {
            width: 15%;
        }

        .reqNum {
            width: 0.5%;
        }

        .reqDate {
            text-align: center;
        }

        .loadingButton {
            background-color: #0088cc;
            color: azure;
            cursor: not-allowed;
        }

        .reqType {
            width: 2%;
        }

        tr:hover td {
            background: #d1e0e0
        }

        .newReq {
            background: rgba(98, 255, 0, 0.4) ! important;
        }

        .needFollow {
            background: rgba(12, 211, 255, 0.3) ! important;
        }

        .noNeed {
            background: rgba(0, 0, 0, 0.2) ! important;
        }

        .wating {
            background: rgba(255, 255, 0, 0.2) ! important;
        }

        .watingReal {
            background: rgba(0, 255, 42, 0.2) ! important;
        }

        .rejected {
            background: rgba(255, 12, 0, 0.2) ! important;
        }
    </style>

    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

@endsection

@section('customer')
    <div class="tableBar">
        <div class="dashTable">
            <h3 class="p-3">تفاصيل الطلب العقاري</h3>
            <table class="table table-bordered">
                <tr>
                    <td>نوع العقار</td>
                    <td>{{$data->propertyType->value ?? "-"}}</td>
                </tr>

               {{-- <tr>
                    <td>إسم العميل </td>
                    <td>{{$data->customer->name}}</td>
                </tr>--}}
               {{-- <tr>
                    <td></td>
                    <td>{{$data->customer->mobile}}</td>
                </tr>--}}
                <tr>
                    <td>من سعر</td>
                    <td>{{$data->min_price}}</td>
                </tr>
                <tr>
                    <td>إلي سعر</td>
                    <td>{{$data->max_price}}</td>
                </tr>
                <tr>
                    <td>المنطقة</td>
                    <td>{{$data->area->value ?? "-" }}</td>
                </tr>
                <tr>
                    <td>المدينة</td>
                    <td>{{$data->city->value ?? "-" }}</td>
                </tr>
                <tr>
                    <td>الحي</td>
                    <td>{{$data->district->value ?? "-" }}</td>
                </tr>
                <tr>
                    <td>تاريخ الطلب</td>
                    <td>{{$data->created_at}}</td>
                </tr>
            </table>
        </div>
       {{-- <div class="dashTable">
            <h3 class="p-3">العقارات المقترحة :</h3>
        @foreach($values as $property)
                <h3 class="p-3"><td> العقار رقم #</td>
                    <td>{{$property->id}}</td></h3>
                <table class="table table-bordered">
                  --}}{{--  <tr>
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property source') }}</td>
                        <td>
                            @switch($property->creator->role)
                                @case('6')
                                {{ MyHelpers::admin_trans(auth()->user()->id,'collaborator') }} - {{$property->creator->name}}
                                @break
                                @case('9')
                                {{ MyHelpers::admin_trans(auth()->user()->id,'property agent') }} - {{$property->creator->name}}
                                @break
                                @case('10')
                                {{ MyHelpers::admin_trans(auth()->user()->id,'Propertor') }} - {{$property->creator->name}}
                                @break
                                @default
                                {{ MyHelpers::admin_trans(auth()->user()->id,'undefined') }}
                            @endswitch

                        </td>
                    </tr>--}}{{--
                    <tr>
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property type') }}</td>
                        <td>{{@$property->type->value?? "-"}}</td>

                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property price') }}</td>
                        <td>
                            {{$property->fixed_price ?? "-"}} ريال سعودي
                        </td>
                    </tr>
                    <tr>
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'city') }}/{{ MyHelpers::admin_trans(auth()->user()->id,'region') }}</td>
                        <td>{{@$property->district->value}}
                            {{@$property->city->value}} - {{@$property->areaName->value}}</td>

                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property address') }}</td>
                        <td>{{$property->address?? "-"}}</td>
                    </tr>
                    <tr>
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of rooms') }}</td>
                        <td>{{$property->num_of_rooms?? "-"}}</td>

                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of salons') }}</td>
                        <td>{{$property->num_of_salons?? "-"}}</td>
                    </tr>
                    <tr>
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of kitchens') }}</td>
                        <td>{{$property->num_of_kitchens?? "-"}}</td>

                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property num of bathrooms') }}</td>
                        <td>{{$property->num_of_bathrooms?? "-"}}</td>
                    </tr>

                    <tr>
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'number of streets the property overlooks') }}</td>
                        <td>{{$property->number_of_streets?? "-"}}</td>

                        <td>إسم المالك</td>
                        <td>{{$property->owner_name ?? "-"}}</td>
                    </tr>
                    <tr>
                        <td>رقم جوال المالك</td>
                        <td>{{$property->owner_number ?? "-"}}</td>

                        <td>إسم المطور</td>
                        <td>{{$property->dev_name ?? "-"}}</td>
                    </tr>
                    <tr>
                        <td>رقم جوال المطور</td>
                        <td>{{$property->dev_number ?? "-"}}</td>

                        <td>إسم المسوق</td>
                        <td>{{$property->mark_name ?? "-"}}</td>
                    </tr>
                    <tr>
                        <td>رقم جوال المسوق</td>
                        <td>{{$property->mark_number ?? "-"}}</td>

                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'property description') }}</td>
                        <td>{!! $property->description !!}</td>
                    </tr>

                </table>
        @endforeach
        </div>--}}
    </div>

@endsection

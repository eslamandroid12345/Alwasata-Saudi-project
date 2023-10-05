@extends('layouts.content')

@section('title')
    المستخدمين
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

        .reqNum {
            width: 1%;
        }

        .reqType {
            width: 2%;
        }

        table {
            text-align: center;
        }
    </style>
    {{-- NEW STYLE   --}}
    <link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">

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




    <div id="msg2" class="alert alert-dismissible" style="display:none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>


    <div class="addUser my-4">
        <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
            <h3>تم رؤية التعميمات من قبل:</h3>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-body">
            <h6><b>محتوى التعميمات</b></h6>
            {{$announce->content}}
            <br>
            <br>
            @if($announce->attachment != null)
            @if($exists == true)
                {{--@if(in_array($extention,["png","jpeg","jpg","gif","webp"]) )
                        <img src="{{asset('/'.$announce->attachment)}}" alt="">
                    @else--}}
                        <a href="{{route('all.announcement.openFile',$announce->id)}}" target="_blank"  class="mt-2 btn btn-success btn-sm">
                            <i class="fa fa-eye"></i> فتح المرفق
                        </a>
              {{--  @endif
--}}
                <a href="{{route('all.announcement.downFile',$announce->id)}}" target="_blank" class="mt-2 btn btn-primary btn-sm">
                    <i class="fa fa-download"></i> تحميل المرفق
                </a>
            @else
                <div class="alert alert-danger">
                    الملف المطلوب غير موجود
                </div>
            @endif
            @endif
        </div>

    </div>

@endsection

@extends('Customer.fundingReq.customerReqLayout')


@section('title') الصفحة الشخصية @endsection


@section('content')


<div style="text-align: left; padding: 2% ; font-size:large">
        <a href="{{url('/customer') }}">
            الرئيسية
            <i class="fa fa-home"> </i>
        </a>
        |
        <a href="{{ url()->previous() }}">
            رجوع
            <i class="fa fa-arrow-circle-left"> </i>
        </a>

    </div>
    <div class="container mt-5">
            <div class="privateData">
            <div class="head-div text-center wow fadeInUp">
                <h1>بياناتي </h1>

            </div>
            <div class="user-welcome mb-5">
                <h5>  اهلا :   {{auth()->guard('customer')->user()->name}}  </h5>
              
            </div>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">الاسم</th>
                    <th scope="col">  {{auth()->guard('customer')->user()->name}}</th>
                  
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">البريد الالكتروني </th>
                    <td>  {{auth()->guard('customer')->user()->email}}</td>
                    
                  </tr>
                  <tr>
                    <th scope="row">رقم الجوال </th>
                    <td>  {{auth()->guard('customer')->user()->mobile}}</td>
               
                  </tr>
                  <tr>
                    <th scope="row">كلمة المرور</th>
                    <td></td>
            
                  </tr>
                </tbody>
              </table>
              <div class="subAsk send-btn my-2">
                <button> <a href="{{route('customer.editprofile')}}" style="color: #fff; text-decoration: none;"> <i class="fas fa-user ml-2"></i> تعديل بياناتي</a></button>
            </div>
            </div>
        </div>

        @endsection
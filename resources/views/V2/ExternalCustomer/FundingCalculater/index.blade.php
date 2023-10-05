@extends('layouts.content')

@section('title')
حاسبة التمويل
@endsection

@section('css_style')

@endsection

@section('customer')
<section class="HomePage mb-5">

<div class="container-fluid px-lg-5">
    <div class="ContTabelPage">




        <section class="new-content mt-0">
            <div class="">

                <div class="row">
                    <div class="col-lg-12">



                        <div class=" ">

                            <div class="row ">
                                <div class="col-lg-12   mb-md-0">

                                    @include('FundingCalculater.caculater2')
                                    @include('FundingCalculater.caculaterResult2')


                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
    </div>



</div>


</section>

@endsection

@section('scripts')
@include('FundingCalculater.calculaterJS2')

@endsection
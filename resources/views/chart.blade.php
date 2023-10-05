@extends('layouts.content')

@section('customer')

        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $chart->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $pie->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $donut->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $line->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $area->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $areaspline->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $geo->html() !!}
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="panel panel-default au-card m-b-30">
                <div class="panel-body au-card-inner">
                    {!! $percentage->html() !!}
                </div>
            </div>
        </div>

{!! Charts::scripts() !!}
{!! $chart->script() !!}
{!! $pie->script() !!}
{!! $donut->script() !!}
{!! $line->script() !!}
{!! $area->script() !!}
{!! $areaspline->script() !!}
{!! $geo->script() !!}
{!! $percentage->script() !!}
@endsection
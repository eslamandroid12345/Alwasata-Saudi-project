@extends("V2.layouts.app")
@section( "title", page_create_title( $CTRL_NAMES ) )
@section("content")
    <div class="container">
        @include("{$CTRL_VIEW}partials.top_block")
        <div class="card">
            <div class="card-body">
                {!! Form::formGroup($model,[
                    "route"     => ["{$CTRL_ROUTE}.store"],
                    "method"    => "POST",
                ])!!}
                <div class="row">
                    @yield("app_create_content")
                    @include("{$CTRL_VIEW}partials.form")
                    {!! Form::addButton() !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

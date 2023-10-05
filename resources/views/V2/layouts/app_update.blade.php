@extends("V2.layouts.app")

@section( "title", page_update_title( $CTRL_NAMES, $model->name_title_page ) )

@section("content")
    <div class="container">
        @include("{$CTRL_VIEW}partials.top_block")
        <div class="card">
            <div class="card-body">
                {!! Form::formGroup($model,[
                    "route"     => [ "{$CTRL_ROUTE}.update", $model->id ],
                    "method"    => "PUT",
                ])!!}
                <div class="row">
                    @yield("app_update_content")
                    @include("{$CTRL_VIEW}partials.form")
                    {!! Form::updateButton() !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

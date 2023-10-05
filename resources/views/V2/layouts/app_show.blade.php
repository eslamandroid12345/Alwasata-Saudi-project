@extends("V2.layouts.app")

@section("title", page_show_title( $CTRL_NAMES, $model->name_title_page ) )

@section("content")
    <div class="container">
        @include("{$CTRL_VIEW}partials.top_block")
        <div class="card">
            <div class="card-body">
                @yield("app_show_content")
            </div>
        </div>
    </div>
@stop

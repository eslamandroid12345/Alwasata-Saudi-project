@extends("V2.layouts.app")

@section("title", page_index_title( $CTRL_NAMES ))

@section("content")
{{--    <h1>{{ $CTRL_VIEW }}</h1>--}}
    @if(($v="{$CTRL_VIEW}partials.top_block") && view()->exists($v))
        @include($v)
    @endif
    @if(($v="{$CTRL_VIEW}partials.filter") && view()->exists($v))
        @include($v)
    @endif
    @yield("app_index_content")
    @isset($dataTable)
        {!! Form::dataTable( $dataTable ) !!}
    @endisset
@endsection


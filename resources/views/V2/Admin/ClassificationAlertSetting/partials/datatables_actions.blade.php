{{--View Model--}}
{!! Form::link(route("{$CTRL_ROUTE}.show", $id), __("global.details") ) !!}

{{-- Delete Model --}}
{!! Form::linkHasForm("DELETE", route("{$CTRL_ROUTE}.destroy", $id), __("global.delete"), "btn-danger") !!}

@if( Route::has("{$CTRL_ROUTE}.create") && $CURRENT_ROUT_NAME !== "{$CTRL_ROUTE}.create" )
{!! Form::link( route("{$CTRL_ROUTE}.create"), page_create_title( $CTRL_NAMES ), 'btn-success') !!}
@endif

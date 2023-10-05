@if( Route::has("{$CTRL_ROUTE}.index") && $CURRENT_ROUT_NAME !== "{$CTRL_ROUTE}.index" )
    {!! Form::link(route("{$CTRL_ROUTE}.index"), __("replace.index",["name" => trans_choice("choice.$CTRL_NAMES", 2)]) ) !!}
@endif


@if( Route::has("{$CTRL_ROUTE}.trashed") && $CURRENT_ROUT_NAME !== "{$CTRL_ROUTE}.trashed" )
    {!! Form::link(route("{$CTRL_ROUTE}.trashed"), __("replace.trashed_index",[ "name" => trans_choice("choice.$CTRL_NAMES", 2)]) ) !!}
@endif

<div class="container">
    <div class="col-12 mb-3">

        {{--List of Model--}}
        {!! Form::indexButtons() !!}

        {{--Create New Entry--}}
        {!! Form::createButtons() !!}

        {!! $slot !!}
    </div>
</div>

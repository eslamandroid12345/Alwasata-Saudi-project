{!! Form::formGroup($model,[
    "route"     => [ "{$CTRL_ROUTE}.update", $model->id ],
    "method"    => "PUT",
])!!}

<div class="container">
    <div class="row">
{{--        {!! Form::selectGroup("classification_id",  __("attributes.customer_classification_id"),null, $UserBankDelegateClassifications ) !!}--}}
{{--        --}}
        {!!  Form::textGroup("name", __("attributes.name"))  !!}
        {!!  Form::numberGroup("mobile", __("attributes.mobile"))  !!}
        {!!  Form::numberGroup("salary", __("attributes.salary"))  !!}
        {!!  Form::textareaGroup("notes", __("attributes.notes"))  !!}

{{--        {!!  Form::numberGroup("hours_to_send", __("validation.attributes.hours_to_send"))  !!}--}}

        {!! Form::updateButton() !!}
    </div>
</div>

{!! Form::close() !!}

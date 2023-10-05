{!! Form::selectGroup("classification_id",  __("validation.attributes.classification_id"), $UserAgentClassificationsSelect ) !!}

{!! Form::selectGroup("type",  __("validation.attributes.type"), $ClassificationAlertSettingTypesSelect ) !!}

{!!  Form::numberGroup("step", __("validation.attributes.step"))  !!}

{!!  Form::numberGroup("hours_to_send", __("validation.attributes.hours_to_send"))  !!}


<?php

namespace App\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Form components
        Form::component('dataTable', 'V2.app.partials.form.datatable', [
            'dataTable',
            'INCLUDES_BEFORE' => [],
            'INCLUDES_AFTER'  => [],
        ]);
        $autocompleteIArg = [
            "name",
            "url",
            "label"      => null,
            "value"      => null,
            "attributes" => ["required" => "required"],
        ];
        $autocompleteGArg = [
            "name",
            "text",
            "url",
            "label"      => null,
            "value"      => null,
            "attributes" => ["required" => "required"],
            "col"        => "col-md-6",
        ];
        Form::component('autocompleteInput', 'V2.app.partials.form.input.autocomplete', $autocompleteIArg);
        Form::component('autocompleteGroup', 'V2.app.partials.form.group.autocomplete', $autocompleteGArg);
        //Form::autocompleteGroup($name, $text, $url, $label, $value, $attributes = [], $col)

        $selectInput = [
            "name",
            "text",
            "icon"       => "fas fa-list-alt",
            "values"     => [],
            "selected"   => null,
            "attributes" => [
                "required" => "required",
            ],
            "col"        => "col-md-6",
        ];
        $textComponent = [
            "name",
            "text",
            "icon"              => "fas fa-file-signature",
            "attributes"        => [
                "required" => "required",
            ],
            "value"             => null,
            "col"               => "col-md-6",
            "textComponentType" => null,
            "dateType"          => "",
        ];

        //Form::selectGroup($name, $text, $icon = 'fas fa-list-alt', $values = [], $selected = null, $attributes = [])
        //Form::multiSelectGroup($name, $text, $icon = 'fas fa-list-alt', $values = [], $selected = null, $attributes = [])
        //Form::textGroup($name, $text, $icon = 'fas fa-file-signature', $attributes = [], $value = null, $col = "col-md-6")
        //Form::numberGroup($name, $text, $icon = 'fas fa-file-signature', $attributes = [], $value = null, $col = "col-md-6")
        //Form::radioSwitchGroup($name, $text, $attributes = [], $text_enable = null, $text_disable = null)

        Form::component('editor', 'V2.app.partials.form.group.editor_component', [
            "name",
            "text",
            "value"      => null,
            "attributes" => [],
            "col"        => 'col-12',
        ]);
        Form::component('textComponent', 'V2.app.partials.form.input.text_component', $textComponent);

        $imageMIME = "image/*";
        $pdfMIME = ".pdf,.PDF";
        $FormComponentTypes = [
            [
                "component" => "date_hijri",
                "t"         => "dateHijri",
                "merge"     => ["dateType" => "date", "icon" => "far fa-calendar"],
            ],
            [
                "component" => "date",
                "t"         => "time",
                "merge"     => ["dateType" => "time", "icon" => "far fa-clock"],
            ],
            [
                "component" => "date",
                "t"         => "date",
                "merge"     => ["dateType" => "date", "icon" => "far fa-calendar"],
            ],
            [
                "component" => "date",
                "t"         => "dateTime",
                "merge"     => ["dateType" => "datetime", "icon" => "far fa-calendar-alt"],
            ],
            [
                "component" => "text_component",
                "t"         => "text",
            ],
            [
                "component" => "text_component",
                "t"         => "number",
            ],
            [
                "component" => "text_component",
                "t"         => "email",
                "merge"     => ["icon" => "fas fa-envelope"],
            ],
            [
                "component" => "text_component",
                "t"         => "password",
                "merge"     => ["icon" => "fas fa-key"],
            ],
            [
                "component" => "text_component",
                "t"         => "textarea",
                "merge"     => [
                    "attributes" => [
                        // "required" => "required",
                        "rows" => 4,
                    ],
                ],
            ],
            [
                "component" => "text_component",
                "t"         => "imageFile",
                "type"      => "file",
                "merge"     => [
                    "icon"       => "fas fa-file",
                    "attributes" => [
                        "accept" => $imageMIME,
                    ],
                ],
            ],
            [
                "component" => "text_component",
                "t"         => "multiImageFile",
                "type"      => "file",
                "merge"     => [
                    "icon"       => "fas fa-file",
                    "attributes" => [
                        "multiple" => "multiple",
                        "accept"   => $imageMIME,
                    ],
                    "multiple"   => "multiple",
                ],
            ],
            [
                "component" => "text_component",
                "t"         => "pdfFile",
                "type"      => "file",
                "merge"     => [
                    "icon"       => "fas fa-file",
                    "attributes" => [
                        "accept" => $pdfMIME,
                    ],
                ],
            ],
            [
                "component" => "text_component",
                "t"         => "multiPdfFile",
                "type"      => "file",
                "merge"     => [
                    "icon"       => "fas fa-file",
                    "attributes" => [
                        "multiple" => "multiple",
                        "accept"   => $pdfMIME,
                    ],
                    "multiple"   => "multiple",
                ],
            ],
            [
                "component" => "text_component",
                "t"         => "file",
                "type"      => "file",
                "merge"     => [
                    "icon" => "fas fa-file",
                ],
            ],
            [
                "component" => "text_component",
                "t"         => "multiFile",
                "type"      => "file",
                "merge"     => [
                    "icon"       => "fas fa-file",
                    "attributes" => [
                        "multiple" => "multiple",
                    ],
                    "multiple"   => "multiple",
                ],
            ],
            [
                "component" => "select",
                "t"         => "select",
            ],
            [
                "component" => "select",
                "t"         => "multiSelect",
                "merge"     => [
                    "attributes" => [
                        "multiple" => "multiple",
                        "required" => "required",
                    ],
                    "multiple"   => "multiple",
                ],
            ],
        ];
        // dd($FormComponentTypes);

        foreach ($FormComponentTypes as $formComponentType) {
            $t = &$formComponentType['t'];
            $var = [];
            if ($formComponentType['component'] == "text_component") {
                $var = $textComponent;
                $var["textComponentType"] = $formComponentType['type'] ?? $t;
            }
            elseif ($formComponentType['component'] == "select") {
                $var = $selectInput;
            }
            elseif ($formComponentType['component'] == "date") {
                $var = $textComponent;
            }
            elseif ($formComponentType['component'] == "date_hijri") {
                $var = $textComponent;
            }
            elseif ($formComponentType['component'] == "autocomplete") {
                $var = $textComponent;
            }
            $attributes = isset($formComponentType["merge"]) ? array_merge($var, $formComponentType["merge"]) : $var;
            //if ($formComponentType['component'] == "select") {
            //    dd($attributes);
            //}
            Form::component("{$t}Input", "V2.app.partials.form.input.{$formComponentType['component']}", $attributes);
            Form::component("{$t}Group", "V2.app.partials.form.group.{$formComponentType['component']}", $attributes);
        }

        Form::component('checkboxComponent', 'V2.app.partials.form.input.checkbox_component', [
            "input_type",
            "name",
            "id",
            "label",
            "value",
            "attributes" => [],
            "checked"    => null,
        ]);

        $radioAndCheckboxInputAttributes = [
            "name",
            "id",
            "label",
            "value",
            "attributes" => [],
            "checked"    => null,
        ];
        Form::component('checkboxInput', 'V2.app.partials.form.input.checkbox', $radioAndCheckboxInputAttributes);
        Form::component('radioInput', 'V2.app.partials.form.input.radio', $radioAndCheckboxInputAttributes);

        Form::component('checkboxGroupComponent', 'V2.app.partials.form.group.checkbox_component', [
            "name",
            "text",
            "items"              => [],
            "form_display_value" => null,
            "attributes"         => [],
            "col"                => "col-md-12",
            'col_items'          => 'col-md-4',
            "input_type"         => null,
        ]);

        $radioAndCheckboxGroupAttributes = [
            "name",
            "text",
            "items"              => [],
            "form_display_value" => null,
            "attributes"         => ["required" => "required",],
            "col"                => "col-md-12",
            'col_items'          => 'col-md-4',
        ];
        Form::component('checkboxGroup', 'V2.app.partials.form.group.checkbox', $radioAndCheckboxGroupAttributes);
        Form::component('radioGroup', 'V2.app.partials.form.group.radio', $radioAndCheckboxGroupAttributes);

        Form::component('radioSwitchGroup', 'V2.app.partials.form.group.radio_switch', [
            "name",
            "text",
            "attributes"   => [
                "required" => "required",
            ],
            "text_enable"  => null,
            "text_disable" => null,
            "col"          => "col-md-6",
        ]);

        Form::component('formButton', 'V2.app.partials.form.group.form_button', [
            "text"       => null,
            "icon"       => "fa fa-plus-square",
            "attributes" => [],
            "col"        => 'col-12',
        ]);

        $btnComponent = ["text", "col"];
        Form::component('addButton', 'V2.app.partials.form.group.add_button', $btnComponent);
        Form::component('saveButton', 'V2.app.partials.form.group.save_button', $btnComponent);
        Form::component('updateButton', 'V2.app.partials.form.group.update_button', $btnComponent);

        Form::component('deleteButton', 'V2.app.partials.form.group.delete_button', [
            "url"  => null,
            "text" => null,
        ]);

        Form::component('formGroup', 'V2.app.partials.form.group.form', [
            'model'      => collect(),
            "attributes" => [],
        ]);

        Form::component('link', 'V2.app.partials.form.input.link', [
            "url",
            "text",
            "btnClass"    => "btn-primary",
            "targetBlank" => false,
        ]);

        Form::component('linkHasForm', 'V2.app.partials.form.input.link_has_form', [
            "method",
            "url",
            "text",
            "btnClass" => "",
        ]);

        Form::component('indexButtons', 'V2.app.partials.component.model.index_buttons', []);
        Form::component('createButtons', 'V2.app.partials.component.model.create_buttons', []);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

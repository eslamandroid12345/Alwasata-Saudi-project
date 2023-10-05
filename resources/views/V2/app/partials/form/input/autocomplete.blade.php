@php
    if(!isset($attributes["class"])){
        $attributes["class"] = "form-control";
    }

    if( is_array($name) ){
        if( !isset( $attributes["id"] ) && isset($name[1]))
            $attributes["id"] = $name[1];
        $name = $name[0];
    }

    $attributes["class"] = preg_replace("/\s+/"," ",trim($attributes["class"]));
    $attributes["class"] = explode(" ",$attributes["class"]);

    if(!in_array("form-control",$attributes["class"]))
        $attributes["class"][] = "form-control";

    if(!in_array("prompt",$attributes["class"]))
        $attributes["class"][] = "prompt";

    $attributes["class"] = implode(" ",$attributes["class"]);
$callback = "";
if(is_array($url)){
if(isset($url[1]))
$callback = $url[1];
$url = $url[0];
}
if(!isset($autocompleteName))
    $autocompleteName = "{$name}_label";

    if( !isset($attributes["placeholder"]) )
        $attributes["placeholder"] = __( "replace.search",[ "name" => __( "validation.attributes.{$name}") ] ) ;

@endphp
<div class="fluid ui search form-control" id="search-{{$autocompleteName}}">
    <div class="ui icon input form-control">
        {!! Form::text($autocompleteName, $label, array_merge($attributes,['id' => $autocompleteName ]) ) !!}
        <i class="search icon"></i>
    </div>
</div>
{!! Form::hidden($name, $value, $attributes ) !!}

@push('scripts')
    <script type="text/javascript">
        let SETTINGS_AUTOCOMPLETE = {
            setting: typeof SEARCH_SETTING !== 'undefined' ? SEARCH_SETTING : {},
            selectCallback: typeof SELECT_CALLBACK === 'function' ? SELECT_CALLBACK : (result, response) => {
            },
            resultsCallback: typeof RESULTS_CALLBACK === 'function' ? RESULTS_CALLBACK : (response) => {
            },
            onResultsOpenCallback: typeof RESULTS_OPEN === 'function' ? RESULTS_OPEN : () => {
            },
            onResultsCloseCallback: typeof RESULTS_CLOSE === 'function' ? RESULTS_CLOSE : () => {
            },
            onResultsAddCallback: typeof RESULTS_ADD === 'function' ? RESULTS_ADD : null,
        };

        let mainSetting = {
            onSelect: (result, response) => {
                @if($callback)
                    {{$callback}}(result, response);
                    @else
                let name = result.name !== undefined ? result.name : result["{{locale_attribute()}}"];
                $("[name='{{$autocompleteName}}']").val(name);
                $("[name='{{$name}}']").val(result.id);
                @endif
                SETTINGS_AUTOCOMPLETE.selectCallback(result, response);
            },
            onResults: (response) => {
                SETTINGS_AUTOCOMPLETE.resultsCallback(response);
            },
            onResultsOpen: () => SETTINGS_AUTOCOMPLETE.onResultsOpenCallback(),
            onResultsClose: () => SETTINGS_AUTOCOMPLETE.onResultsCloseCallback(),
            onResultsAdd: (html) => {
                return typeof SETTINGS_AUTOCOMPLETE.onResultsAddCallback === 'function' ? SETTINGS_AUTOCOMPLETE.onResultsAddCallback(html) : html;
            },
            apiSettings: {
                url: '{!! rtrim($url,'?&') . (str_contains($url,'?') ? '&' : '?') !!}keywords={query}',
            },
            // minCharacters: 3,
            selectFirstResult: true,
            cache: false,
            performance: false,
        };

        let searchSetting = {
            ...mainSetting,
            ...SETTINGS_AUTOCOMPLETE.setting,
        };

        $('#search-{{$autocompleteName}}')
        .search(searchSetting);

        $(document).on("keydown", '#{{$autocompleteName}}', (e) => {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });
    </script>
@endpush()

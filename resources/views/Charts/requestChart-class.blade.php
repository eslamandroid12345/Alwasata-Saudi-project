
    <label class="label">تصنيف الطلب </label>
    <div class="rs-select2 js-select-simple select--no-search">
        <select class="form-control" name="class[]" multiple>
            <option value="allClass" {{in_array("allClass",$classes)? "selected" :""}}>
                الكل
            </option>


            @foreach($agent_class as $class)
            
            <option value="{{'class-'.$class->id}}" {{in_array('class-'.$class->id,$classes)? "selected" :""}}>
            {{$class->value}}
            </option>

            @endforeach
           
           
        </select>
        <div class="select-dropdown"></div>
    </div>

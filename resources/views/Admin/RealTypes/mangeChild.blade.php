
@foreach($childs as $child)
        <?php
        
        if(isset($parent_id) && $parent_id == $child->id){$select_or_no='selected';}else{ $select_or_no='';}
        
         $extra='';
         for($i=1;$i<=$number;$i++){
            $extra.='-';
         }

         if(isset($parent_id)){$parent_id=$parent_id;}else{$parent_id=0;}
        if(isset($type_id)){$type_id=$type_id;}else{$type_id=0;}
        
        //  $color = array("#4d28de", "#9d1d81", "#7a5114","#0c6a78");
        $colors=array("#4d28de","rgb(0 152 121)","#9d561c","rgb(128 47 143)","rgb(180 191 17)","rgb(207 29 86)","rgb(78 203 209)");
        $new= [
            'childs' => $child->children,
            'color'=>$colors[$number],
            // 'color' =>$color[$number],
            'number'=>$number+1,
            'type_id'=>$type_id,//pramiry key of type we edit on it 
            'parent_id'=>$parent_id,//parent_id of another type
        ];
?>
       @if($child->id!=$type_id)
            <option style="color: {{$color}};" value="{{ $child->id }}" <?php echo $select_or_no;?>> <?php echo $extra;?> {{ $child->value }}</option>
            @if(count($child->children))
                 @include('Admin.RealTypes.mangeChild',$new)
            @endif

        @endif
@endforeach
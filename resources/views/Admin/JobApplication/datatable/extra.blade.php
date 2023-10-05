
 @if(isset($university))
    @if($university->university_id==Null)
        <span style="color:red">{{$university->other_university}}</span>
        @else
        {{($university->university->title)??''}}
        @endif
  @endif              

  @if(isset($nationality))
    @if($nationality->nationality_id==Null)
        <span style="color:red">{{$nationality->other_nationality}}</span>
        @else
        {{($nationality->nationality->title)??''}}
        @endif
  @endif

  @if(isset($type))
    @if($type->type_id==Null)
        <span style="color:red"> - </span>
        @else
        {{($type->type->title)??''}}
        @endif
  @endif 

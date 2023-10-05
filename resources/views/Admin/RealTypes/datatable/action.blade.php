<div class="tableAdminOption">
    <span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="تعديل">
        <a href="{{route('real_types.edit',$real_types->id)}}"> <i class="fa fa-eye"></i></a>
    </span>

    <span class="btn btn-btn btn-danger btn-sm pointer" data-toggle="modal" data-target="#flipInY{{$real_types->id}}" data-placement="top" title="" data-original-title="حذف" aria-describedby="tooltip526429">
    <i class="fas fa-trash-alt"></i>
    </span>
    
</div>



                                    
<!-- id="mi-modal3" -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="flipInY{{$real_types->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Are you sure want to delete it?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form action="{{route('real_types.destroy',$real_types->id)}}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-footer">
                <button type="button" id="modal-btn-no3" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si3" class="btn btn-danger">{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}</button>
            </div>
        </form>
        </div>
    </div>
</div>



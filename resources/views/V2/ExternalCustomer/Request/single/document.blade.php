<div  class="userFormsInfo  ">
    <div class="headER topRow text-center ">
        <i class="fas fa-layer-group"></i>
        <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}</h4>
    </div>
    <div class="userFormsContainer mb-3">
        <div class="userFormsDetails topRow">

            <div class="row">
                <div class="col-12 mb-4">
                    <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                        <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                            <button class="upload text-center mr-3 green item" id="upload" type="button" @if ($reqStatus == 23 )  style="cursor: not-allowed" disabled @endif >
                                <i class="fas fa-cloud-upload-alt"></i>
                                {{MyHelpers::admin_trans(auth()->user()->id, 'file')}}
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-12 mb-4">
                    <div class="dashTable">
                        <table class="table table-bordred table-striped" id="docTable">
                            <thead>
                            <tr>
                                <th><i class="fas fa-user mr-1"></i>   {{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                                <th><i class="fas fa-file mr-1"></i>   {{ MyHelpers::admin_trans(auth()->user()->id,'File Name') }}</th>
                                <th><i class="fas fa-clock mr-1"></i>  {{ MyHelpers::admin_trans(auth()->user()->id,'Uploaded At') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="st">
                            @if (empty($documents[0]))

                                <tr>
                                    <td colspan="4">
                                        <h5 class="text-center text-secondary">{{ MyHelpers::admin_trans(auth()->user()->id,'No Attached') }}</h5>
                                    </td>

                                </tr>

                            @else

                                @foreach($documents as $document)

                                    <tr id="{{$document->id}}">
                                        <td>{{$document->user->name}}</td>
                                        <td>{{$document->filename}}</td>
                                        <td>{{$document->upload_date}}</td>
                                        <td>
                                            <div class="tableAdminOption">
                                            <span class="item pointer"  data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                                <a href="{{ route('proper.open.file',$document->id)}}" target="_blank"> <i class="fa fa-eye"></i></a>
                                            </span>
                                                <span class="item pointer"  data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}">
                                                <a href="{{ route('proper.download.file',$document->id)}}" target="_blank"> <i class="fa fa-download"></i></a>
                                            </span>
                                                <span @if ($reqStatus == 23 )  style="cursor: not-allowed" disabled @endif class="item pointer" id="delete" data-id="{{$document->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </span>


                                            </div>
                                        </td>

                                    </tr>

                                @endforeach

                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

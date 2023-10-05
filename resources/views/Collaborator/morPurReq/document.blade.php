<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            @if ($reqStatus == 19 )
            <!-- Status mor pur of agent-->
            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}</h3>
                </div>
                <hr>

                <br>
                <div class="row">
                    <div class="col-4">

                    </div>

                    <div class="col-4">



                        <button class="btn btn-info btn-block" id="upload" type="button">
                            <i class="fa fa-upload"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'file') }}</button>


                    </div>


                    <div class="col-4">

                    </div>
                </div>



                <br><br>


                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning" id="docTable">
                        <thead>
                            <tr>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'File Name') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Uploaded At') }}</th>
                                <th></th>


                            </tr>
                        </thead>
                        <tbody id="st">

                            @if (empty($documents[0]))

                            <tr>
                                <td colspan="4">
                                    <h3 class="text-center text-secondary">{{ MyHelpers::admin_trans(auth()->user()->id,'No Attached') }}</h3>
                                </td>

                            </tr>

                            @else

                            @foreach($documents as $document)

                            <tr id="{{$document->id}}">
                                <td>{{$document->name}}</td>
                                <td>{{$document->filename}}</td>
                                <td>{{$document->upload_date}}</td>
                                <td>

                                    <div class="table-data-feature">



                                        <button type="button" class="item" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                            <a href="{{ route('collaborator.openFile',$document->id)}}" target="_blank"> <i class="zmdi zmdi-eye"></i></a>
                                        </button>


                                        <button type="button" class="item" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}">
                                            <a href="{{ route('collaborator.downFile',$document->id)}}" target="_blank"> <i class="zmdi zmdi-download"></i></a>
                                        </button>


                                        <button type="button" class="item" id="delete" data-id="{{$document->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>


                                    </div>
                                </td>

                            </tr>

                            @endforeach

                            @endif

                        </tbody>
                    </table>
                </div>

            </div>

            @else

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Documents Info') }}</h3>
                </div>
                <hr>

                <br>
                <div class="row">
                    <div class="col-4">

                    </div>

                    <div class="col-4">



                        <button disabled style="cursor: not-allowed" class="btn btn-info btn-block" id="upload" type="button">
                            <i class="fa fa-upload"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'file') }}</button>


                    </div>


                    <div class="col-4">

                    </div>
                </div>



                <br><br>


                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning" id="docTable">
                        <thead>
                            <tr>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Username') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'File Name') }}</th>
                                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Uploaded At') }}</th>
                                <th></th>


                            </tr>
                        </thead>
                        <tbody id="st">

                            @if (empty($documents[0]))

                            <tr>
                                <td colspan="4">
                                    <h3 class="text-center text-secondary">{{ MyHelpers::admin_trans(auth()->user()->id,'No Attached') }}</h3>
                                </td>

                            </tr>

                            @else

                            @foreach($documents as $document)

                            <tr id="{{$document->id}}">
                                <td>{{$document->name}}</td>
                                <td>{{$document->filename}}</td>
                                <td>{{$document->upload_date}}</td>
                                <td>

                                    <div class="table-data-feature">



                                        <button type="button" class="item" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                            <a href="{{ route('collaborator.openFile',$document->id)}}" target="_blank"> <i class="zmdi zmdi-eye"></i></a>
                                        </button>


                                        <button type="button" class="item" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}">
                                            <a href="{{ route('collaborator.downFile',$document->id)}}" target="_blank"> <i class="zmdi zmdi-download"></i></a>
                                        </button>


                                        <button disabled type="button" class="item" id="delete" data-id="{{$document->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>


                                    </div>
                                </td>

                            </tr>

                            @endforeach

                            @endif

                        </tbody>
                    </table>
                </div>

            </div>
            @endif
        </div>
    </div>
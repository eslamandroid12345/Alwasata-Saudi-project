
  <!-- begin::portlet  -->
  <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head">
      <!-- begin::portlet__head-label  -->
      <div class="portlet__head-label">مرفقات الطلب</div>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    <div class="portlet__body pt-0">
      <div class="border rounded-5 p-3">
        @if ( $reqStatus == 0 || $reqStatus == 1 || $reqStatus == 2 || $reqStatus == 4 || $reqStatus == 31 || ( ($payment != null ) && ($payment->payStatus == 4 || $payment->payStatus == 3 ) && ($reqStatus == 6 || $reqStatus == 8 || $reqStatus == 13) ) )
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label>اسم الملف</label>
              <input class="form-control inputFileDropzone" id="inputFileDropzone" type="text" />
            </div>
          </div>
          <div class="col-12">
            <!-- begin::Uploader Dropzone  -->
            <div class="fileuploader center-align" id="zdrop" action="upload.php">
              <div id="upload-label">
                <div class="upload-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="35.323" height="29.027" viewBox="0 0 35.323 29.027">
                    <g id="Icon_feather-upload-cloud" data-name="Icon feather-upload-cloud" transform="translate(1.225 1.031)">
                      <path
                        id="Path_2780"
                        data-name="Path 2780"
                        d="M24,24l-6-6-6,6"
                        transform="translate(-1.492 -4.503)"
                        fill="none"
                        stroke="#6c757d"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                      ></path>
                      <path id="Path_2781" data-name="Path 2781" d="M18,18V31.5" transform="translate(-1.492 -4.503)" fill="none" stroke="#6c757d" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                      <path
                        id="Path_2782"
                        data-name="Path 2782"
                        d="M30.585,27.585A7.5,7.5,0,0,0,27,13.5H25.11A12,12,0,1,0,4.5,24.45"
                        transform="translate(-1.492 -4.503)"
                        fill="none"
                        stroke="#6c757d"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                      ></path>
                      <path
                        id="Path_2783"
                        data-name="Path 2783"
                        d="M24,24l-6-6-6,6"
                        transform="translate(-1.492 -4.503)"
                        fill="none"
                        stroke="#6c757d"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                      ></path>
                    </g>
                  </svg>
                </div>
                <h5 class="upliad-title">قم بإسقاط الملفات هنا أو انقر للتحميل.</h5>
              </div>
            </div>
            <!-- begin::Preview collection of uploaded documents  -->
            <div class="preview-container" style="display: none;">
                <div class="bg-primary py-2 px-3 rounded-5">
                  <div class="row">
                    <div class="col">
                      <h6 class="text-white">اسم المستخدم</h6>
                    </div>
                    <div class="col">
                      <h6 class="text-white">اسم الملف</h6>
                    </div>
                    <div class="col">
                      <h6 class="text-white">اتاريخ الرفع</h6>
                    </div>
                  </div>
                </div>
                <div class="collection py-2 px-3" id="previews">
                  <div class="row py-2" id="zdrop-template">
                    <div class="col">
                      <div class="usernameFileDropzone"></div>
                    </div>
                    <div class="col">
                      <div class="nameFileDropzone"></div>
                    </div>
                    <div class="col">
                      <div class="dateFileDropzone"></div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="">
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
                                    <td>{{$document->name}}</td>
                                    <td>{{$document->filename}}</td>
                                    <td>{{$document->upload_date}}</td>
                                    <td>
                                        <div class="tableAdminOption">
                                    <span class="item pointer"  data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                        <a href="{{ route('agent.openFile',$document->id)}}" target="_blank"> <i class="fa fa-eye"></i></a>
                                    </span>
                                            <span class="item pointer"  data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}">
                                        <a href="{{ route('agent.downFile',$document->id)}}" target="_blank"> <i class="fa fa-download"></i></a>
                                    </span>
                                            <span class="item pointer" id="delete" data-id="{{$document->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}">
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
        @else
        <div class="row">
          <div class="col-12">
            غير مسموح باضافه ملفات
          </div>
          <div class="col-12">
            <!-- begin::Preview collection of uploaded documents  -->

            <div class="">
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
                                    <td>{{$document->name}}</td>
                                    <td>{{$document->filename}}</td>
                                    <td>{{$document->upload_date}}</td>
                                    <td>
                                        <div class="tableAdminOption">
                                    <span class="item pointer"  data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                        <a href="{{ route('agent.openFile',$document->id)}}" target="_blank"> <i class="fa fa-eye"></i></a>
                                    </span>
                                            <span class="item pointer"  data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Download') }}">
                                        <a href="{{ route('agent.downFile',$document->id)}}" target="_blank"> <i class="fa fa-download"></i></a>
                                    </span>
                                            <span class="item pointer" id="delete" data-id="{{$document->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Delete') }}">
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
        @endif
      </div>
    </div>
    <!-- end::portlet__body  -->
  </div>
  <!-- end::portlet  -->


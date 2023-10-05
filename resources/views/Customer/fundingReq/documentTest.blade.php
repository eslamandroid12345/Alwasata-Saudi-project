<div class="col-lg-12 mb-5 mb-md-0">
    <div class="userFormsInfo  ">
        <div class="headER topRow text-center ">
            <i class="fas fa-layer-group"></i>
            <h4>مرفقات الطلب</h4>
        </div>
        <div class="userFormsContainer mb-3">
            <div class="userFormsDetails topRow">

                <div class="row">

                    @if (empty($documents[0]))

                    <div class="col-md-12">
                        <h3 class="text-center text-secondary">{{ MyHelpers::guest_trans('No Attached') }}</h3>
                    </div>

                    @else

                    @foreach($documents as $document)
                    <div class="col-md-3">
                        <div class="fileDetails">
                            <i class="fas fa-user mr-1"></i>
                            تم رفعه من قبل :<span> {{$document->name}}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="fileDetails">
                            <i class="fas fa-file mr-1"></i>
                            اسم الملف :<span> {{$document->filename}}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="fileDetails">
                            <i class="fas fa-clock mr-1"></i>
                            تم رفعه في :<span> {{$document->upload_date}}</span>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="fileDetails">
                            <a href="{{ url('/customer/openfile/'.$document->id)}}" target="_blank" title="فتح">
                                <i class="fas fa-eye mr-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="fileDetails">
                            <a href="{{ route('customer.downFile',$document->id)}}" title="تنزيل">
                                <i class="fas fa-download mr-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-1">
                
                    </div>


                    @endforeach

                    @endif

                </div>


            </div>
        </div>
    </div>
</div>
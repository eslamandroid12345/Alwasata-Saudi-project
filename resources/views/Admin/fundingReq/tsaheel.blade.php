
<div class="userFormsInfo  ">
    <div class="userFormsContainer mb-3">
        <div class="tsaheelInfo topRow ">
            <div class="tsaheelHeader">
                <div class="addBtn">
                    <button class="w-100" role="button" type="button">
                        <i class="fas fa-plus-circle"></i>
                        {{ MyHelpers::admin_trans(auth()->user()->id,'Tasahil Info') }}
                    </button>
                </div>
            </div>
            <div class="tsaheeldiv  mt-3">
                <div class="row tableAdminOption" id="tableAdminOption">
                    <div class="col-12 mb-4">
                        <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center" style="display: block">
                            <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                                <a href="{{ route('all.printReport',['id'=>$id])}}" target="_blank">
                                    <button type="button" class="text-center mr-3 green item" role="button" >
                                        <i class="fas fa-credit-card"></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Print Report') }}
                                    </button>
                                </a>
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="preVisa" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="visa" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'visa card') }}</label>
                            <input id="visa" name="visa" min="0" type="number"onblur="debtcalculate()" class="form-control" value="{{  old('visa',$purchaseTsa->visa) }}" autocomplete="visa" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="carLo" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="carlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'car loan') }}</label>
                            <input id="carlo" name="carlo" type="number" onblur="debtcalculate()" min="0" class="form-control" value="{{ old('carlo',$purchaseTsa->carLo) }}" autocomplete="carlo" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="personalLo" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="perlo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'personal loan') }}</label>
                            <input id="perlo" name="perlo" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('perlo',$purchaseTsa->personalLo) }}" autocomplete="perlo" autofocus>

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="realLo" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="realo" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'real estate loan') }}</label>
                            <input id="realo" name="realo" type="number" min="0"  onblur="debtcalculate()" class="form-control" value="{{ old('realo',$purchaseTsa->realLo) }}" autocomplete="realo" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="credBank" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="credban" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'credit bank') }}</label>
                            <input id="credban" name="credban" min="0" type="number" onblur="debtcalculate()" class="form-control" value="{{ old('credban',$purchaseTsa->credit) }}" autocomplete="credban" autofocus>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="otherLo" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="other" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'other') }}</label>
                            <input id="other1" name="other1" type="number" onblur="debtcalculate()"  min="0" class="form-control" value="{{ old('other1',$purchaseTsa->other) }}"  autocomplete="other" autofocus>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="debt" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'total debt') }}</label>
                            <input id="debt" name="debt" type="number" class="form-control" value="{{ old('debt',$purchaseTsa->debt) }}"  autocomplete="debt" autofocus readonly>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="morPresnt" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morpre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage %') }}</label>
                            <input id="morpre" name="morpre" min="0" max="100" type="number" class="form-control" value="{{ old('morpre',$purchaseTsa->mortPre) }}" autocomplete="morpre" autofocus>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="mortCost" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="morcos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input id="morcos" name="morcos" type="number" min="0" class="form-control" value="{{ old('morcos',$purchaseTsa->mortCost) }}" autocomplete="morcos" autofocus>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="pursitPresnt" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="propre" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Profit %') }}</label>
                            <input id="propre" name="propre" min="0" max="100" type="number" class="form-control" value="{{ old('propre',$purchaseTsa->proftPre) }}" onblur="profcalculate()" autocomplete="propre" autofocus>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="profCost" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="procos" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost') }}</label>
                            <input  id="" name="procos" type="number" min="0" class="form-control" value="{{ $purchaseTsa->profCost }}" autocomplete="procos" autofocus >

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="addedValue" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="valadd" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'value added') }}</label>
                            <input id="valadd" name="valadd" min="0" type="number" class="form-control" value="{{ old('valadd',$purchaseTsa->addedVal) }}"  autocomplete="valadd" autofocus>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <span id="record" role="button" type="button" class="item span-20" data-id="adminFees" data-toggle="modal" data-target="#myModal" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history i-20" style="font-size: medium;"></i></span>
                            <label for="admfe" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'admin fees') }}</label>
                            <input id="admfe" name="admfe" type="number" min="0" class="form-control" value="{{ old('admfe',$purchaseTsa->adminFee) }}" autocomplete="admfe" autofocus>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('Admin.fundingReq.prepayment')



@section('scripts')
<script>

</script>
@endsection

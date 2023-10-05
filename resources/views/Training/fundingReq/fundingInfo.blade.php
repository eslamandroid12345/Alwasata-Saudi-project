<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">

            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center title-2">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Info') }}</h3>
                </div>
                <hr>


                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="funding_source" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="funding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                            <select disabled id="funding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('funding_source') }}" name="funding_source">

                                <option value="">---</option>
                                @foreach ($funding_sources as $funding_source )
                                @if ($purchaseFun->funding_source == $funding_source->id)
                                <option value="{{$funding_source->id}}" selected>{{$funding_source->value}}</option>
                                @else
                                <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
                                @endif

                                @endforeach
                            </select>

                            @error('funding_source')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundDur" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="fundingdur" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                            <input readonly id="fundingdur" name="fundingdur" type="number" max="30" class="form-control @error('fundingdur') is-invalid @enderror" value="{{ $purchaseFun->funding_duration }}" autocomplete="fundingdur">


                            @error('fundingdur')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundPers" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="fundingpersonal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                            <input readonly id="fundingpersonal" name="fundingpersonal" type="number" class="form-control @error('fundingpersonal') is-invalid @enderror" value="{{ $purchaseFun->personalFun_cost }}" autocomplete="fundingpersonal">


                            @error('fundingpersonal')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundPersPre" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="fundingpersonalp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                            <input readonly id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1" max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ $purchaseFun->personalFun_pre }}" autocomplete="fundingpersonalp">


                            @error('fundingpersonalp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundReal" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="fundingreal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</label>
                            <input readonly id="fundingreal" name="fundingreal" type="number" class="form-control @error('fundingreal') is-invalid @enderror" value="{{ $purchaseFun->realFun_cost }}" autocomplete="fundingreal">


                            @error('fundingreal')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundRealPre" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="fundingrealp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                            <input readonly id="fundingrealp" name="fundingrealp" type="number" min="0.1" max="100" class="form-control @error('fundingrealp') is-invalid @enderror" value="{{ $purchaseFun->realFun_pre }}" autocomplete="fundingrealp">


                            @error('fundingrealp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                            <input readonly id="salary1" name="salary1" type="number" class="form-control" value="{{ $purchaseCustomer->salary }}" autocomplete="salary" readonly>
                        </div>

                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundDed" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="dedp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                            <input readonly id="dedp" name="dedp" type="number" min="0.1" max="100" class="form-control @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ $purchaseFun->ded_pre }}" autocomplete="dedp">


                            @error('dedp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <button class="item" id="record" data-id="fundMonth" type="button" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'records')}}">
                                <i class="fa fa-history" style="font-size: medium;"></i></button>
                            <label for="monthIn" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                            <input readonly id="monthIn" name="monthIn" type="number" class="form-control" value="{{ $purchaseFun->monthly_in }}" autocomplete="monthIn" readonly>
                        </div>

                    </div>
                </div>





            </div>

        </div>
    </div>
</div>

@section('scripts')
<script>


</script>
@endsection
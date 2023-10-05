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
                            <label for="funding_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding source') }}</label>
                            <select id="funding_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control select2-request @error('funding_source') is-invalid @enderror" value="{{ old('funding_source') }}" name="funding_source">

                                <option value="">---</option>
                                @foreach ($funding_sources as $funding_source )
                                <option value="{{$funding_source->id}}">{{$funding_source->value}}</option>
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
                            <label for="fundingdur" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'funding duration') }}</label>
                            <input id="fundingdur" name="fundingdur" type="number" max="30" class="form-control @error('fundingdur') is-invalid @enderror" value="{{ old('fundingdur') }}" autocomplete="fundingdur">


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
                            <label for="fundingpersonal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost personal funding') }}</label>
                            <input id="fundingpersonal" name="fundingpersonal" type="number" class="form-control @error('fundingpersonal') is-invalid @enderror" value="{{ old('fundingpersonal') }}" autocomplete="fundingpersonal">


                            @error('fundingpersonal')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="fundingpersonalp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                            <input id="fundingpersonalp" name="fundingpersonalp" type="number" min="0.1"  max="100" class="form-control @error('fundingpersonalp') is-invalid @enderror" value="{{ old('fundingpersonalp') }}" autocomplete="fundingpersonalp">


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
                            <label for="fundingreal" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'cost real estate funding') }}</label>
                            <input id="fundingreal" name="fundingreal" type="number" class="form-control @error('fundingreal') is-invalid @enderror" value="{{ old('fundingreal') }}" autocomplete="fundingreal">


                            @error('fundingreal')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="fundingrealp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'presentage') }}</label>
                            <input id="fundingrealp" name="fundingrealp" type="number" min="0.1"  max="100" class="form-control @error('fundingrealp') is-invalid @enderror" value="{{ old('fundingrealp') }}" autocomplete="fundingrealp">


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
                            @if ($customer != null)
                            <input id="salary1" name="salary" type="number" class="form-control" value="{{ $customer->salary }}" autocomplete="salary" readonly>
                            @else
                            <input id="salary1" name="salary" type="number" class="form-control" autocomplete="salary" readonly>
                            @endif
                        </div>

                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="dedp" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'deduction presentage') }}</label>
                            <input id="dedp" name="dedp" type="number" min="0.1"  max="100" class="form-control @error('dedp') is-invalid @enderror" onblur="monthlycalculate()" value="{{ old('dedp') }}" autocomplete="dedp">


                            @error('dedp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <label for="monthIn" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'monthly installment') }}</label>
                            <input id="monthIn" name="monthIn" type="number" value="{{ old('monthIn') }}" class="form-control" autocomplete="monthIn" readonly>
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
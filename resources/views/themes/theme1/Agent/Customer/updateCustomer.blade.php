<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Information') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('agent.editCustomer')}}" method="POST" id="frm-update">
                <div class="modal-body">

                    @csrf
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <input type="hidden" name="id" class="form-control" id="id">

                    <div class="form-group">
                        <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="">

                        <span class="text-danger" id="nameError" role="alert"> </span>
                    </div>

                    <div class="form-group">
                        <label for="sex" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'gender') }}</label>

                        <select id="sex" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1;' class="form-control @error('sex') is-invalid @enderror" name="sex">

                            <option value="">---</option>
                            <option value="ذكر">{{ MyHelpers::admin_trans(auth()->user()->id,'male') }}</option>
                            <option value="أنثى">{{ MyHelpers::admin_trans(auth()->user()->id,'female') }}</option>


                        </select>

                        <span class="text-danger" id="sexError" role="alert"> </span>


                    </div>


                    <label for="name" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</label>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="mobile">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}
                                    <small id="checkMobile" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}
                                    </small>
                                </label>
                                <input id="mobile" name="mobile" type="tel" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" onchange="changeMobile()" autocomplete="mobile" autofocus placeholder="05xxxxxxxx">
                            </div>
                            <span class="text-danger" id="error" role="alert"> </span>
                            <span class="text-danger" id="mobileError" role="alert"> </span>

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="birth">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}
                                    <small id="convertToHij" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}">
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'To Hijri') }}
                                    </small>
                                </label>
                                <input id="birth" name="birth" type="date" class="form-control @error('birth') is-invalid @enderror" value="{{ old('birth') }}" autocomplete="birth" onblur="calculate()" autofocus>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="form-group">
                                <label for="mobile">
                                    {{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }} ({{ MyHelpers::admin_trans(auth()->user()->id,'hijri') }})
                                    <small id="convertToGreg" role="button" type="button" class="item badge badge-info pointer has-tooltip "  title="{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}">
                                        {{ MyHelpers::admin_trans(auth()->user()->id,'To Greg') }}
                                    </small>
                                </label>
                                <input type='text' name="birth_hijri" class="form-control" placeholder="يوم/شهر/سنة" id="hijri-date" />
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="age" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'age') }}</label>
                                <input id="age" name="age" type="text" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}" autocomplete="age" autofocus readonly>
                                @error('age')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="work" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'work') }}</label>

                        <select id="work" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('work') is-invalid @enderror" value="{{ old('work') }}" name="work">

                            <option value="">---</option>
                            <option value="عسكري">عسكري</option>
                            <option value="مدني">مدني</option>
                            <option value="قطاع خاص">قطاع خاص</option>
                            <option value="متقاعد">متقاعد</option>

                        </select>

                        <span class="text-danger" id="workError" role="alert"> </span>

                    </div>

                    <div class="form-group">
                        <label for="salary_source" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary source') }}</label>
                        <select id="salary_source" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur();' class="form-control @error('salary_source') is-invalid @enderror" value="{{ old('salary_source') }}" name="salary_source">

                            <option value="">---</option>
                            @foreach ($salary_sources as $salary_source )
                            <option value="{{$salary_source->id}}">{{$salary_source->value}}</option>
                            @endforeach
                        </select>

                        <span class="text-danger" id="salary_sourceError" role="alert"> </span>
                    </div>

                    <div class="form-group">
                        <label for="salary" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</label>
                        <input id="salary" name="salary" min="0" type="number" class="form-control @error('salary') is-invalid @enderror" value="{{ old('salary') }}" autocomplete="salary" autofocus>

                        <span class="text-danger" id="salaryError" role="alert"> </span>
                    </div>

                    <div class="form-group">
                        <label for="is_support" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}?</label>
                        <div class="row">
                            <div class="col-6">

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="yes" id="yes" name="support">
                                    <label style=" position: relative; padding-left: 1.8rem;" class="custom-control-label" for="yes">نعم</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="no" id="no" name="support">
                                    <label style=" position: relative; padding-left: 1.8rem;" class="custom-control-label" for="no">لا</label>
                                </div>
                            </div>
                        </div>

                    </div>





                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Update') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>

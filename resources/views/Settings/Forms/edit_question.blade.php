@extends('layouts.content')


@section('css_style')


@endsection

@section('customer')
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! session('success') !!}</li>
                        </ul>
                    </div>
                    @elseif(session('error'))
                    <div class="alert alert-error">
                        <ul>
                            <li>{!! session('error') !!}</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            {{ MyHelpers::admin_trans(auth()->user()->id,'edit') }} {{ MyHelpers::admin_trans(auth()->user()->id,'question') }} # {{ $questions->id }}
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.settings.form.updatequestions')}}" method="post" class="">
                                @csrf
                                <input type="hidden" name="id" id="" value="{{ $questions->id }}">
                                <div id="stutusdiv" class="form-group">
                                    <label for="stutus" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'question') }}</label>

                                    <input type="text" class="form-control" name="question" id="" placeholder="السؤال" value="{{ $questions->question }}">
                                    @if ($errors->has('question'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('question') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div id="stutusdiv" class="form-group">
                                    <label for="status" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'question status') }}</label>
                                    <select name="status" class="form-control">
                                        <option value="0" {{ $questions->status == 0 ? "selected" : " "}}>مفعل</option>
                                        <option value="1" {{ $questions->status == 1 ? "selected" : " "}}>غير مفعل</option>
                                    </select>
                                </div>


                                <div class="row">
                                    <div class="col-4"></div>

                                    <div class="col-4 form-group">
                                        <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Save') }}</button>
                                    </div>

                                    <div class="col-4"></div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')


<script>
    $(document).ready(function() {

        $('#stutus , #classifcations').select2();

    });
</script>
@endsection
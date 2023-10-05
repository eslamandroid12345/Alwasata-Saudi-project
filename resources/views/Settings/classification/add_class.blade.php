@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Classification') }}
@endsection


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
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Classification') }}
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.saveclass')}}" method="post" class="">
                                @csrf



                                <div class="form-group">
                                    <label for="class" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Classification') }}</label>

                                    <input type="text" class="form-control" value="{{ old('class') }}" name="class" id="" placeholder="اكتب محتوى التصنيف هنا">

                                    @if ($errors->has('class'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label for="role" class="control-label mb-1">المستخدم</label>

                                    <select class="form-control" id="role" name="role">

                                        <option value="" selected>---</option>

                                        @foreach($user_roles as $key => $user_role)
                                       <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $user_role }}</option>
                                        @endforeach

                                    </select>

                                    @if ($errors->has('role'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label for="type" class="control-label mb-1">نوع التصنيف</label>

                                    <select class="form-control" id="type" name="type">

                                       <option value="1" selected>إيجابي</option>
                                       <option value="0">سلبي</option>
                                     

                                    </select>

                                    @if ($errors->has('type'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('type') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label for="is_required_in_calculater" class="control-label mb-1">متطلب للحسبة؟</label>

                                    <select class="form-control" id="is_required_in_calculater" name="is_required_in_calculater">

                                       <option value="1" selected>نعم</option>
                                       <option value="0">لا</option>
                                     

                                    </select>

                                    @if ($errors->has('is_required_in_calculater'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('is_required_in_calculater') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="row">
                                    <div class="col-4"></div>

                                    <div class="col-4 form-group">
                                        <button type="submit" class="btn btn-info btn-block">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
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
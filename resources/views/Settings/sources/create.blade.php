@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} مصدر معاملة
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
                            {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} مصدر معاملة
                        </div>
                        <div class="card-body card-block">
                            <form action="{{route('admin.save.source')}}" method="post" class="">
                                @csrf



                                <div class="form-group">
                                    <label for="source" class="control-label mb-1">مصدر المعاملة</label>

                                    <input type="text" class="form-control" value="{{ old('source') }}" name="source" id="source" placeholder="اكتب محتوى مصدر المعاملة هنا">

                                    @if ($errors->has('source'))
                                    <span class="help-block col-md-12">
                                        <strong style="color:red ;font-size:10pt">{{ $errors->first('source') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="role">@lang('language.role')</label>
                                    <select id="role" name="role" class="form-control" onfocus='this.size=3;' onblur='this.size=1;' onchange='this.size=1; this.blur(); check(this);' placeholder="@lang('language.role')">
                                        <option selected >لا يوجد</option>
                                        @foreach($RoleSelected as $key =>$role)
                                            <option value="{{$key}}">{!!$role!!}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('role'))
                                 <span class="help-block col-md-12">
                                    <strong style="color:red ;font-size:10pt">{{ $errors->first('role') }}</strong>
                                </span>
                                @endif
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

@extends('layouts.content')

@section('title')
    {{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}
@endsection
@section('css_style')
    <!--NEW 2/2/2020 for hijri datepicker-->
    <link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

@endsection

@section('customer')


    <div>
        @if (session('msg'))
            <div id="msg" class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('msg') }}
            </div>
        @endif
    </div>


    <section class="new-content mt-5">
        <div class="container-fluid">

            <div class="row ">
                <div class="col-md-6 offset-md-3">
                    <div class="row">
                        <div class="col-lg-12   mb-md-0">
                            <div class="userFormsInfo  ">
                                <div class="headER topRow text-center">
                                    <i class="fas fa-user"></i>
                                    <h4>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer Info') }}</h4>
                                </div>
                                <form action="{{ route('admin.customerUpdatePassword')}}" method="post" class="">
                                    @csrf
                                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                    <input name="customer_id" value="{{ $customer->id }}" type="hidden">
                                    <div class="userFormsContainer mb-3">
                                        <div class="userFormsDetails topRow">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="name">{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</label>
                                                        <input name="name" type="text" class="form-control" value="{{ $customer->name }}" autofocus disabled>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="mobile">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}
                                                        </label>
                                                        <input id="mobile" name="mobile" type="tel" class="form-control" value="{{$customer->mobile }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="mobile">
                                                            {{ MyHelpers::admin_trans(auth()->user()->id,'Password') }}
                                                        </label>
                                                        <input  name="password" type="text" class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <button type="submit" class="Green d-block border-0 w-100 py-2 rounded text-light addUserClient">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </section>


@endsection
@section('scripts')
@endsection

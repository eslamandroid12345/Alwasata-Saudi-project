@extends('layouts.content')

@section('title')
إضافة جنسيه
@endsection


@section('css_style')

<style>
    .select2-results {
        max-height: 350px;
    }

    .bigdrop {
        width: 600px !important;
    }
</style>
@endsection

@section('customer')

@if(session()->has('message'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif

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
                    <div class="alert alert-danger">
                        <ul>
                            <li>{!! session('error') !!}</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
               <div class="addBtn col-md-5 mt-lg-0 mt-3">
                    <a href="{{ route('nationality.index')}}">
                        <button class="mr-2 Cloud">
                            <i class="fas fa-home"></i>
                            جميع الجنسيات
                        </button>
                    </a>
                </div>

                <div class="col-lg-7 offset-md-3">
                    <div class="card">
                        <div class="card-header"> اضافه جنسيه</div>
                        <div class="card-body card-block">

                            <form action="{{route('nationality.store')}}" method="post" id="form-data"  enctype="multipart/form-data" class="">
                                @csrf
                                <div class="form-group">
                                    <label for="managers" class="control-label mb-1">العنوان</label>
                                    <input type="text" class="form-control" name="title"  placeholder="ادخل الجنسيه " value="{{old('title')}}" required>
                                    @error('title')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                    <br><br>
                                    
                                    <label for="managers" class="control-label mb-1">الترتيب</label>
                                    <input type="number" class="form-control" name="sort"  value="{{old('sort')}}" required>
                                    @error('sort')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                    <br><br>
                                    
                                    <label for="managers" class="control-label mb-1">الحاله</label>
                                    <select class="form-control" name="status" required>
                                         <option value="1" >ظاهر فى فورم التوظيف</option>
                                         <option value="0" >مخفى فى فورم التوظيف</option>
                                    </select>
                                    @error('status')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <br>
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">إضافة</button>
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

@endsection

@extends('layouts.content')

@section('title')
إضافة نوع عقار
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
                    <a href="{{ route('real_types.index')}}">
                        <button class="mr-2 Cloud">
                            <i class="fas fa-home"></i>
                            انواع العقارات
                        </button>
                    </a>
                </div>

                <div class="col-lg-7 offset-md-3">
                    <div class="card">
                        <div class="card-header"> اضافه نوع عقار</div>
                        <div class="card-body card-block">

                            <form action="{{route('real_types.store')}}" method="post" id="form-data"  enctype="multipart/form-data" class="">
                                @csrf
                                <div class="form-group">
                                    <label for="managers" class="control-label mb-1">النوع</label>
                                    <input type="text" class="form-control" name="value"  placeholder="ادخل نوع العقار" value="{{old('value')}}" required>
                                    @error('value')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                    <br><br>

                                    <label for="managers" class="control-label mb-1">التصنيف</label>
                                    <select class="form-control" name="parent_id" required>
                                         <option value="0" selected>نوع رئيسى </option>
                                        @foreach($real_types as $type)
                                            <?php
                                            $color="#c20620";
                                            $new=[
                                                'childs' => $type->children,
                                                'color'=>'#209c41',
                                                'number'=>2,
                                                'type_id'=>'',
                                                'parent_id'=>'',
                                            ];
                                            ?>
                                            <option style="color:<?php echo $color;?>" value="{{$type->id}}">- {{ $type->value }} </option>
                                            @include('Admin.RealTypes.mangeChild',$new)
                                        @endforeach
                                    </select>
                                    @error('parent_id')
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

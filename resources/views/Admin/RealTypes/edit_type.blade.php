@extends('layouts.content')

@section('title')
تعديل {{$real_data->value}}
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
                        <div class="card-header">  تعديل {{$real_data->value}} </div>
                        <div class="card-body card-block">

                            <form action="{{route('real_types.update',$real_data->id)}}" method="post" id="form-data">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="managers" class="control-label mb-1">النوع</label>
                                    <input type="hidden" class="form-control" name="id" value="{{$real_data->id}}">
                                    <input type="text" class="form-control" name="value" value="{{$real_data->value}}" required>
                                    @error('value')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                    <br><br>

                                    <label for="managers" class="control-label mb-1">التصنيف</label>
                                    <select class="form-control" name="parent_id" required>
                                      <option value='0' <?php if($real_data->parent_id == NULL){echo'selected';}?>>نوع رئيسى </option>

                                        @foreach($real_types as $type)
                                            <?php
                                            $color="#c20620";
                                            $new=[
                                                'childs' => $type->children,
                                                'color'=>'#209c41',
                                                'number'=>2,
                                                'type_id'=>$real_data->id,//pramiry key of type we edit on it 
                                                'parent_id'=>$real_data->parent_id,//parent_id of another type
                                                 
                                            ];
                                            ?>
                                            <option style="color:<?php echo $color;?>" value="{{$type->id}}"  <?php if($real_data->parent_id == $type->id){echo'selected';}?>>- {{ $type->value }} </option>
                                            @include('Admin.RealTypes.mangeChild',$new)
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>

                                <br>
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-info btn-block">تعديل</button>
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

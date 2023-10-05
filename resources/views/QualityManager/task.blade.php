@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'task') }}
@endsection

@section('css_style')


@endsection

@section('customer')


<br>

<div id="msg2" class="alert alert-dismissible" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

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



<div id="sendingWarning" class="alert alert-info" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="sendingWarning1" class="alert alert-info" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="rejectWarning" class="alert alert-warning" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div id="rejectWarning1" class="alert alert-warning" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="archiveWarning" class="alert alert-dark" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<div id="archiveWarning1" class="alert alert-dark" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="appWarning" class="alert alert-success" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>

<div id="appWarning1" class="alert alert-success" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>






<div class="tab_container" style=" padding-top: 8px;">
  <div class="card">
    <div class="card-header">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'task') }}</div>
    <div class="card-body card-block">


      <form action="{{ route('quality.manager.task_post')}}" method="post" class="">
        @csrf
        <input type="hidden" name="id" value="{{$id}}">
        <label for="Username" class="control-label mb-1">{{ MyHelpers::admin_trans(auth()->user()->id,'Content') }} {{ MyHelpers::admin_trans(auth()->user()->id,'the task') }}</label>
        <div class="input-group">
          <textarea name="content" id="" cols="30" rows="5" class="form-control"> {{$requestRoot->quacomment}} </textarea>



          <!-- <input type="text" id="name" name="name" placeholder="الإسم" class="form-control" value=""> -->
          <div class="input-group-addon">
            <i class="fa fa-diamond"></i>
          </div>
        </div>

        @if($errors->has('content'))
        <div class="error" style="color: red; font-wight:bold;">{{ $errors->first('content') }}</div>
        @endif



        <br>





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







@endsection

@section('scripts')


@endsection

@extends('layouts.content')


@section('css_style')

<style>
.middle-screen{
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
}
</style>
@endsection

@section('customer')



@if(!empty($message))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ $message }}
</div>
@endif

@if ( session()->has('message') )
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message') }}
</div>
@endif

@if ( session()->has('message2') )
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>


<div class="row">
    @if (!empty($requests[0]))
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30">
            <table class="table table-borderless table-striped table-earning">
                <thead>
                    <tr>
                        <th>Req Num</th>
                        <th>Req Date</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Comment</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                    <tr>
                        <td>{{$request->id}}</td>
                        <td>{{$request->req_date}}</td>
                        <td>{{$request->type}}</td>
                        <td>{{$request->name}}</td>
                        @if($request->statusReq== 0)
                        <td>New</td>
                        @elseif ($request->statusReq== 1)
                        <td>Open</td>
                        @else
                        <td>Other</td>
                        @endif
                        <td>{{$request->source}}</td>
                        <td>{{$request->comment}}</td>
                        <td>
                            <div class="table-data-feature">



                            <button class="item" id="open" data-id="{{$request->id}}" data-toggle="tooltip" data-placement="top" title="Open">
                                <a href="{{ route('agent.fundingRequest',$request->id)}}"> <i class="zmdi zmdi-eye"></i></a>
                            </button>


                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">لايوجد طلبات مرسلة</h2>
    </div>
    @endif

</div>

@endsection

@section('scripts')

<script>


</script>
@endsection
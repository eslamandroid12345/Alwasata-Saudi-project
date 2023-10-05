@extends('layouts.content')


@section('css_style')

<style>
    .middle-screen {
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

@if ($check == 0)
@php ($variable = 'no')
@foreach ($requests as $request)

@if ($request->count() != 0) <!-- each index not empty-->
@php ($variable = 'yes')
@endif
@endforeach

@if ($variable == 'yes')
    <div class="col-12">
    <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <table class="table table-borderless table-striped table-earning" id="myTable">
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

                    @foreach ($requests as $request => $reqs)
                    <!-- nested for each to the arry of list-->
                    @foreach ($reqs as $req)
                    <tr>

                        <td>{{$req->id}}</td>
                        <td>{{$req->req_date}}</td>
                        <td>{{$req->type}}</td>
                        <td>{{$req->name}}</td>
                        @if($req->statusReq== 0)
                        <td>New</td>
                        @elseif ($req->statusReq== 1)
                        <td>Open</td>
                        @else
                        <td>Other</td>
                        @endif
                        <td>{{$req->source}}</td>
                        <td>{{$req->comment}}</td>
                        <td>
                            <button class="item" id="open" data-id="{{$req->id}}" data-toggle="tooltip" data-placement="top" title="Open">
                                <a href="{{ route('sales.manager.fundingRequest',$req->id)}}"> <i class="zmdi zmdi-eye"></i></a>
                            </button>
                        </td>

                    </tr>

                    @endforeach

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

    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">لايوجد طلبات مرسلة</h2>
    </div>

    @endif

</div>

@endsection

@section('scripts')

<script>

$(document).ready( function () {
    $('#myTable').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}"
        }
    });
} );
</script>
@endsection

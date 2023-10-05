@extends('layouts.content')


@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}
@endsection

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


<h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur Requests') }}:</h3>
<br>
<div class="row">

    @if ($check == 0)
    @if ($requests[0]->count() != 0)
    <div class="col-12">
    <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <table class="table table-borderless table-striped table-earning" id="myTable">
                <thead>
                    <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>


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
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'new req') }}</td>
                        @elseif ($req->statusReq== 1)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'open req') }}</td>
                        @elseif ($req->statusReq== 2)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in sales agent req') }}</td>
                        @elseif ($req->statusReq== 3)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales manager req') }}</td>
                        @elseif ($req->statusReq== 4)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected sales manager req') }}</td>
                        @elseif ($req->statusReq== 5)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in sales manager req') }}</td>
                        @elseif ($req->statusReq== 6)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating funding manager req') }}</td>
                        @elseif ($req->statusReq== 7)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected funding manager req') }}</td>
                        @elseif ($req->statusReq== 8)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in funding manager req') }}</td>
                        @elseif ($req->statusReq== 9)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating mortgage manager req') }}</td>
                        @elseif ($req->statusReq== 10)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected mortgage manager req') }}</td>
                        @elseif ($req->statusReq== 11)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in mortgage manager req') }}</td>
                        @elseif ($req->statusReq== 12)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating general manager req') }}</td>
                        @elseif ($req->statusReq== 13)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected general manager req') }}</td>
                        @elseif ($req->statusReq== 14)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'archive in general manager req') }}</td>
                        @elseif ($req->statusReq== 15)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Canceled') }}</td>
                        @elseif ($req->statusReq== 16)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}</td>
                        @elseif ($req->statusReq== 18)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales manager req') }}</td>
                        @elseif ($req->statusReq== 19)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating sales agent req') }}</td>
                        @elseif ($req->statusReq== 20)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected sales manager req') }}</td>
                        @elseif ($req->statusReq== 21)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating funding manager req') }}</td>
                        @elseif ($req->statusReq== 22)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected funding manager req') }}</td>
                        @elseif ($req->statusReq== 23)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'wating general manager req') }}</td>
                        @elseif ($req->statusReq== 24)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'cancel mortgage manager req') }}</td>
                        @elseif ($req->statusReq== 25)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'rejected general manager req') }}</td>
                        @elseif ($req->statusReq== 26)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Completed') }}</td>
                        @elseif ($req->statusReq== 27)
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Canceled') }}</td>
                        @else
                        <td>{{ MyHelpers::admin_trans(auth()->user()->id,'Undefined') }}</td>
                        @endif

                        <td>{{$req->source}}</td>
                        <td>{{$req->comment}}</td>
                        <td>
                            <div class="table-data-feature">



                            <button class="item" id="open" data-id="{{$req->id}}" data-toggle="tooltip" data-placement="top" title="{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}">
                                <a href="{{ route('collaborator.morPurRequest',$req->id)}}"> <i class="zmdi zmdi-eye"></i></a>
                            </button>


                            </div>
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
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} </h2>
    </div>

    @endif

    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }} </h2>
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

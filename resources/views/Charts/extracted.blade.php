@extends('layouts.content')

@section('css_style')
<!-- Main CSS-->

<style>
    svg:not(:root) {
        overflow: hidden;
        direction: ltr;
    }
   </style>

@endsection

@section('customer')
<div class="col-12">
    <div class="panel panel-default au-card m-b-30">
        <div class="panel-body au-card-inner">
            {!! $chart->html() !!}
        </div>
    </div>
</div>

    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30">
            <table class="table table-borderless table-striped table-earning">
                <thead>
                    <!-- This Row will be show on all tables result except avarage of data handling -->
                    <tr>
                        <th># ID</th>
                        <th>Agent Name</th>
                        <th>Type</th>
                    </tr>
                    
                </thead>
                <tbody>
                    @if(count($data) !=0 )
                        @foreach ($data as $request) 
                        <!-- This Row will be show on all tables result except avarage of data handling -->

                        <tr>
                            <td>{{@$request->id}}</td>
                            <td>{{@ App\User::username($request->user_id) }}</td>
                            <td>{{@$request->type}}</td>
                        </tr>

                        <!-- This Row will be only on avarage of data handling -->

                        <tr>
                            <td>{{@$request['id']}}</td>
                            <td>{{@$request['name']}}</td>
                            <td>{{@$request['avg']}}</td>
                        </tr>
                        
                        @endforeach
                    @else
                        <tr>
                            <th colspan="4"><center>No Data To show</center></th>
                        </tr>
                        @endif
                </tbody>
            </table>
        </div>
    </div> 
    

{!! Charts::scripts() !!}
{!! $chart->script() !!}
@endsection

@section('scripts')

@endsection
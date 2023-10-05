@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'req time line') }}
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


<h3>{{ MyHelpers::admin_trans(auth()->user()->id,'req time line') }}:</h3>
<br>


<div class="row">

    @if (!empty($req_histories[0]))
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30">
            <table class="table table-borderless table-striped table-earning">
                <thead>
                    <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'content') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'From') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'To_') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Comment') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Date') }}</th>
                        <th>التقييم</th>

                    </tr>
                </thead>
                <tbody>
                @foreach ($req_histories as $req_historie)
                    <tr>

                    @if ($req_historie->title == 'نقل الطلب' && (auth()->user()->role ==7 || auth()->user()->role ==4))
                        <td style="white-space:unset;">{{$req_historie->title}} - {{$req_historie->content}}</td>
                        @else
                        <td style="white-space:unset;">{{$req_historie->title}}</td>
                    @endif

                        @if($req_historie->sentname != null)

                        @if($req_historie->swname != null)
                        <td>{{$req_historie->sentname}} / {{$req_historie->swname}}</td>
                        @else
                        <td>{{$req_historie->sentname}}</td>
                        @endif

                        @else
                        <td>---</td>
                        @endif

                        @if($req_historie->recname != null)
                        <td>{{$req_historie->recname}}</td>
                        @else
                        <td>---</td>
                        @endif

                        @if ($req_historie->title == 'نقل الطلب' )
                        <td></td>
                        @else
                        <td>{{$req_historie->content}}</td>
                        @endif

                        <td>{{$req_historie->history_date}}</td>

                        @if (isset($customer->app_rate_starts))
                            <td>{{$customer->app_rate_starts}}</td>
                        @else
                            <td>---</td>
                        @endif

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Updates') }}</h2>
    </div>

    @endif

</div>



@endsection

@section('scripts')

<script>


</script>
@endsection

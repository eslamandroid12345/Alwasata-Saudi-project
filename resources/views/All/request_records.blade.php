@extends('layouts.content')

@section('title', __("language.req time line"))

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
    <h3>@lang("language.req time line"):</h3>
    <br>
    <div class="row">
        @if(count($records) > 0)
        <div class="col-12 col-lg-9 mx-auto">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                    <tr>
                        <th style="width: 25%">{{ MyHelpers::admin_trans(auth()->user()->id,'Date') }}</th>
                        <th colspan="2">{{ MyHelpers::admin_trans(auth()->user()->id,'content') }}</th>
                        {{--                            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'From') }}</th>--}}
{{--                        <th style="width: 37.333%">{{ MyHelpers::admin_trans(auth()->user()->id,'Comment') }}</th>--}}
                        {{--                            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Comment') }}</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($records as $record)
                        <tr>
                            <td>{{$record['date']}}</td>
                            <td>{{$record['text']}}</td>
                            <td>{{$record['value']}}</td>
                            {{--                                <td></td>--}}
                            {{--                                <td></td>--}}
                            {{--                                <td></td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="middle-screen">
            <h2 style=" text-align: center;font-size: 20pt;">@lang('language.No Updates')</h2>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>

    </script>
@endsection

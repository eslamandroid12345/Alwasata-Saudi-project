@extends("V2.layouts.print")

@push('styles')
    <style>
        @media print {
            html,body{
                /*margin: 0 !important;*/
                /*padding: 0 !important;*/
            }
        }
    </style>
@endpush
@section('print_content')
    @if( request()->get("action") == "print" && false )
        <div class="btns-div">
            <a href="javascript:void(0)" onclick="window.opener ? window.close() : location.href = '{{ redirect()->back()->getTargetUrl() }}'" class="btn btn-block btn-success"> @lang( "global.back" )</a>
            <a href="javascript:void(0);" class="btn btn-block btn-dark" onclick="print()"> @lang( "global.print" )</a>
        </div>
    @endif
    <table class="table-hover text-center table table-sm table-bordered table-striped table-valign-middle">
        @foreach($data as $keyRow => $row)
            @if($keyRow == 0)
                <thead>
            @endif
            @if(isset($page_title) && $page_title )
                <tr>
                    <th colspan="{{count($row)}}">{{$page_title}}</th>
                </tr>
            @endif
            @if ($row == reset($data))
                <tr>
                    @foreach($row as $key => $value)
                        @if( $key != __("datatable.controller") )
                            <th>{!! $key !!}</th>
                        @endif
                    @endforeach
                </tr>
            @endif

            @if($keyRow == 0)
                </thead>
                <tbody>
            @endif

            <tr>
                @foreach($row as $key => $value)
                    @if( $key != __("datatable.controller") )

                        @if(is_string($value) || is_numeric($value))
                            <td>{!! $value !!}</td>
                        @else
                            <td></td>
                        @endif
                    @endif
                @endforeach
            </tr>

            @if($keyRow == 0)
                </tbody>
            @endif
        @endforeach
    </table>
@stop


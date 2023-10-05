<div id="note" style="background:{{ $announces->color}}">
    {!! \Illuminate\Support\Str::limit($announces->content,30) !!}
    <span id='close' data-id="{{$announces->id}}" class="announce-line">عُـلم</span>
    <a class="announce-line" target="_blank" href="{!! route('all.announcement', $announces->id) !!}">@lang('global.showMore')</a>
    <br>
    @if ($announces->attachment != null)
        <a href="{{ route('openAnnounceFile',$announces->id)}}" id="attach" target="_blank">فتح المرفق</a>
    @endif
</div>

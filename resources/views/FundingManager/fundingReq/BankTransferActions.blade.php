@php
    $bank_req_row = \DB::table('wasata_requestes')->where('user_id', $fund_user->id)->where('funding_user_id', auth()->id())->where('req_id', $id)->first();
@endphp
<ul class="u-bank-action">
    @if(isset($bank_req_row) && $bank_req_row->req_type != 'close' || !isset($bank_req_row))
    <li>
        <button class="ajax-req-bank btn btn-success" {!! $bank_req_row ? 'disabled' : ''  !!} data-route="{{route('funding.manager.sendBank',['user_id' => $fund_user->id,'req_id'=> $id, 'type' => 'send'])}}" data-val="send">
            ارسال
        </button>
    </li>
    @endif
    @if(isset($bank_req_row) && $bank_req_row->req_type != 'close' || !isset($bank_req_row) )
        @if(isset($bank_req_row) && $bank_req_row->req_type == 'send')
        <li>
            <button class="ajax-req-bank btn btn-warning" {!! $bank_req_row ? '' : 'disabled'  !!} data-route="{{route('funding.manager.sendBank',['user_id' => $fund_user->id,'req_id'=> $id, 'type' => 'pull'])}}" data-val="pull">
                سحب
            </button>
        </li>
        @elseif(isset($bank_req_row) && $bank_req_row->req_type == 'pull')
        <li>
            <button class="ajax-req-bank btn btn-warning" {!! $bank_req_row ? '' : 'disabled'  !!} data-route="{{route('funding.manager.sendBank',['user_id' => $fund_user->id,'req_id'=> $id, 'type' => 'push'])}}" data-val="push">
                ارجاع
            </button>
        </li>
        @elseif(isset($bank_req_row) && $bank_req_row->req_type == 'push')
        <li>
            <button class="ajax-req-bank btn btn-warning" {!! $bank_req_row ? '' : 'disabled'  !!} data-route="{{route('funding.manager.sendBank',['user_id' => $fund_user->id,'req_id'=> $id, 'type' => 'pull'])}}" data-val="pull">
                سحب
            </button>
        </li>
        @else
        <li>
            <button class="ajax-req-bank btn btn-warning" {!! $bank_req_row ? '' : 'disabled'  !!} data-route="{{route('funding.manager.sendBank',['user_id' => $fund_user->id,'req_id'=> $id, 'type' => 'pull'])}}" data-val="pull">
                سحب
            </button>
        </li>
        @endif
    @endif

    @if(isset($bank_req_row) && $bank_req_row->req_type != 'close' || !isset($bank_req_row))
    <li>
        <button class="ajax-req-bank btn btn-danger" {!! $bank_req_row ? '' : 'disabled'  !!} data-route="{{route('funding.manager.sendBank',['user_id' => $fund_user->id,'req_id'=> $id, 'type' => 'close'])}}" data-val="close">
            افراغ
        </button>
    </li>
    @else
    <li>
        <button class="ajax-req-bank btn btn-danger" disabled>
            تم الافراغ
        </button>
    </li>
    @endif
    @if (isset($msg) && !empty($msg))
        <li>
            {{$msg}}
        </li>
    @endif

</ul>

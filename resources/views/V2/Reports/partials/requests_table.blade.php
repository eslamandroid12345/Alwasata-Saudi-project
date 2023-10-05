<table class="table table-bordred table-striped data-table" id="myreqs-table">
    <thead>
    <tr>
        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
        <th style="text-align:center">{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</th>
        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
        <th style="text-align:left">{{ MyHelpers::admin_trans(auth()->user()->id,'actions') }}</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td>
                    {{$row->id}}
                </td>
                <td>
                    {{\Carbon\Carbon::parse($row->created_at)->format('Y-m-d g:ia')}}
                </td>
                <td>
                    {{$row->type}}
                </td>
                <td>
                    {{$row->user->name ?? ''}}
                </td>
                <td>
                    {{$row->customer->name ?? ''}}
                </td>
                <td>
                    @php if ($row->type == 'رهن-شراء') {
                        echo '<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                    }
                    else {
                        echo '<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                    }
                    @endphp
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

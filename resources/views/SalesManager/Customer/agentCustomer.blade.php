@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'User Requests') }}
@endsection

@section('css_style')
<link rel="stylesheet" href="{{ asset('css/tokenize2.min.css') }}">
<style>
  .middle-screen {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }
  table {
        width: 100%;
    }

    td {
        width: 15%;
    }

    .reqNum {
        width: 1%;
    }

    .reqType {
        width: 2%;
    }

    .commentStyle {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    tr:hover td {
        background: #d1e0e0
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




<div id="msg2" class="alert alert-dismissible" style="display:none;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<h4>{{ MyHelpers::admin_trans(auth()->user()->id,'User Requests') }}  ( {{ $requests[0]->user_name }} ) :</h4>
<br>

<div class="row">

  <input type="hidden" id="userID" value="{{$id}}">

  @if (!empty($requests[0]))
  <div class="col-12">
    <div class="table-responsive table--no-card m-b-30 data-table-parent">
      <table class="table table-borderless table-striped table-earning data-table">
        <thead>

          <tr>

            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
            <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>

          </tr>

        </thead>

        <tbody>

        </tbody>
      </table>
    </div>
  </div>
  @else
  <div class="middle-screen">
    <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No New Requests') }}</h2>
  </div>

  @endif

</div>

@endsection





@section('scripts')

<script>
  $(document).ready(function() {
    var id = document.getElementById("userID").value;
    $('.data-table').DataTable({
      "language": {
        "url": "{{route('datatableLanguage')}}",
        buttons: {
          excelHtml5: "اكسل",
          print: "طباعة",
          pageLength: "عرض",

        }
      },
      "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "الكل"]
      ],

      dom: 'Bfrtip',
      buttons: [
        // 'copyHtml5',
        'excelHtml5',
        // 'csvHtml5',
        // 'pdfHtml5' ,
        'print',
        'pageLength'
      ],
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('salesManager/agentcustomer-datatable') }}",
        data: {
          id: id,

        },
      },
      columns: [

        {
          data: 'id',
          name: 'id'
        },
        {
          data: 'req_date',
          name: 'req_date'
        },
        {
          data: 'type',
          name: 'type'
        },
        {
          data: 'customer_name',
          name: 'customer_name'
        },
        {
          data: 'status',
          name: 'status'
        },
        {
          data: 'source',
          name: 'source'
        },
        {
          data: 'comment',
          name: 'comment'
        }
      ],
      ,
            createdRow: function(row, data, index) {
                $('td', row).eq(6).addClass('commentStyle'); // 6 is index of column
                $('td', row).eq(6).attr('title', data.comment); // to show other text of comment

                $('td', row).eq(0).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(1).addClass('reqNum'); // 6 is index of column
                $('td', row).eq(2).addClass('reqType'); // 6 is index of column
                $('td', row).eq(3).addClass('reqType'); // 6 is index of column
                $('td', row).eq(5).addClass('reqType'); // 6 is index of column
            },
    });
  });
</script>
@endsection

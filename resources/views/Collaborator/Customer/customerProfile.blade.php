@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Profile Customer') }}
@endsection

@section('css_style')
<link href="  {{ url('interface_style/css/material-dashboard.css?v=2.1.1') }}" rel="stylesheet" />

<style>
  .with-arrow .nav-link.active {
    position: relative;
  }

  .middle-screen {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }

  .with-arrow .nav-link.active::after {
    content: '';
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid #2b90d9;
    position: absolute;
    bottom: -6px;
    left: 50%;
    transform: translateX(-50%);
    display: block;
  }

  /* lined tabs */

  .lined .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
  }

  .lined .nav-link:hover {
    border: none;
    border-bottom: 3px solid transparent;
  }

  .lined .nav-link.active {
    background: none;
    color: #555;
    border-color: #2b90d9;
  }


  .nav-pills .nav-link {
    color: #555;
  }

  .text-uppercase {
    letter-spacing: 0.1em;
  }



  .nav-item>a:hover {
    color: grey;
  }
</style>

@endsection


@section('customer')
<div class="row">
  <div class="col-lg-8 text-white py-4 text-center offset-md-2">
    <h3>{{$customer->name}}</h3>
    <p style="color:grey" class="lead mb-0">{{$customer->mobile}} - {{$customer->work}}</p>

  </div>
</div>

<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-warning card-header-icon">
        <div class="card-icon">
          <i class="fa fa-home"></i>
        </div>
        <p class="card-category">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase') }}</p>
        <h3 class="card-title">{{$purRequests->count()}}</h3>
      </div>
      <div class="card-footer">
        <div class="stats" style="font-size:small;">
          <i class="material-icons text-primary">add_circle_outline</i><a href="{{ route('collaborator.purchasePage', ['title' => 'funding', 'id' => $customer->id])}}">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-info card-header-icon">
        <div class="card-icon">
          <i class="fa fa-unlock-alt"></i>
        </div>
        <p class="card-category" style="font-size:small">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</p>
        <h3 class="card-title">{{$morRequests->count()}}</h3>
      </div>
      <div class="card-footer">
        <div class="stats" style="font-size:small;">
          <i class="material-icons text-primary">add_circle_outline</i><a href="{{ route('collaborator.purchasePage',['title' => 'mortgage', 'id' => $customer->id])}}">{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }}</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-danger card-header-icon">
        <div class="card-icon">
          <i class="fa fa-handshake-o"></i>
        </div>
        <p class="card-category">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</p>
        <h3 class="card-title">{{$morPurRequests->count()}}</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <br>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-success card-header-icon">
        <div class="card-icon">
          <i class="fa fa-usd"></i>
        </div>
        <p class="card-category"> {{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}</p>
        <h3 class="card-title">{{$payRequests->count()}}</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <br>
        </div>

      </div>
    </div>
  </div>
</div>
<div class="container py-5">

  <div class="p-5 bg-white rounded shadow mb-5">


    <!-- Lined tabs-->
    <ul id="myTab2" role="tablist" class="nav nav-tabs nav-pills with-arrow lined flex-column flex-sm-row text-center">
      <li class="nav-item flex-sm-fill">
        <a id="purchase-tab" data-toggle="tab" href="#purchase" role="tab" aria-controls="purchase" aria-selected="true" class="nav-link text-uppercase font-weight-bold mr-sm-3 rounded-0 active">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Purchase') }}</a>
      </li>
      <li class="nav-item flex-sm-fill">
        <a id="mortgage-tab" data-toggle="tab" href="#mortgage" role="tab" aria-controls="mortgage" aria-selected="false" class="nav-link text-uppercase font-weight-bold rounded-0">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage') }}</a>
      </li>
      <li class="nav-item flex-sm-fill">
        <a id="mor-pur-tab" data-toggle="tab" href="#mor-pur" role="tab" aria-controls="mor-pur" aria-selected="false" class="nav-link text-uppercase font-weight-bold mr-sm-3 rounded-0">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Mor-Pur') }}</a>
      </li>
      <li class="nav-item flex-sm-fill">
        <a id="prepayment-tab" data-toggle="tab" href="#prepayment" role="tab" aria-controls="prepayment" aria-selected="false" class="nav-link text-uppercase font-weight-bold rounded-0">{{ MyHelpers::admin_trans(auth()->user()->id,'requests') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Prepayments and Tasahil') }}</a>
      </li>
    </ul>



    <div id="myTab2Content" class="tab-content">



      <!-- Purchase Info-->
      <div id="purchase" role="tabpanel" aria-labelledby="purchase-tab" class="tab-pane fade px-4 py-5 show active">

        @if ($purRequests->count() != 0)
        <div class="table-responsive table--no-card m-b-30 data-table-parent ">
          <table class="table table-borderless table-striped table-earning data-table"  id="purRequests_table">
            <thead>
              <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                <th></th>

              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        @else
        <div class="middle-screen">
          <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Purchase Requests') }}</h2>
        </div>

        @endif

      </div>

      <!-- End Purchase Info-->



      <!-- Mortgage Info-->
      <div id="mortgage" role="tabpanel" aria-labelledby="mortgage-tab" class="tab-pane fade px-4 py-5">


        @if ($morPurRequests->count() != 0)
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
          <table class="table table-borderless table-striped table-earning data-table"   id="morRequests_table">
            <thead>
              <tr>

              <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                <th></th>

              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        @else
        <div class="middle-screen">
          <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Mortgage Requests') }}</h2>
        </div>

        @endif

      </div>

      <!-- End Mortgage Info-->




      <!-- Mor-pur  Info-->
      <div id="mor-pur" role="tabpanel" aria-labelledby="mor-pur-tab" class="tab-pane fade px-4 py-5">


        @if ($morPurRequests->count() != 0)
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
          <table class="table table-borderless table-striped table-earning data-table"  id="morPurRequests_table">
            <thead>
              <tr>

              <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req num') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req date') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'type') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req status') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'req source') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'comment') }}</th>
                <th></th>


              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

        @else
        <div class="middle-screen">
          <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Mor-Pur Requests') }}</h2>
        </div>

        @endif


      </div>

      <!-- End Mor-pur Info-->


      <!-- Prpayment Info-->
      <div id="prepayment" class="tab-pane py-5">


        @if ($payRequests->count() != 0)
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
          <table class="table table-borderless table-striped table-earning data-table"  id="payRequests_table">
            <thead>
              <tr>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'id') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'date') }}</th>
                <th>{{ MyHelpers::admin_trans(auth()->user()->id,'status') }}</th>
                <th> </th>

              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>

        @else
        <div class="middle-screen">
          <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Prepayments and Tasahil Requests') }}</h2>
        </div>

        @endif

        <!-- End Prpayment Info-->


      </div>
      <!-- End lined tabs -->

    </div>
  </div>
  @endsection

  @section('scripts')
<script>
$(document).ready( function () {
    var customer_id = {{$customer->id}} ;
    console.log(customer_id);
    $('#purRequests_table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
        ajax:  "{{ url('collaborator/customerprofile-purRequests-datatable/') }}" +'/'+ customer_id ,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'req_date', name: 'req_date' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'status' },
            { data: 'source', name: 'source' },
            { data: 'comment', name: 'comment' },
            { data: 'action', name: 'action' }
        ]
    });

    $('#morRequests_table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
        ajax: "{{ url('collaborator/customerprofile-morRequests-datatable/') }}" +'/'+ customer_id ,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'req_date', name: 'req_date' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'status' },
            { data: 'source', name: 'source' },
            { data: 'comment', name: 'comment' },
            { data: 'action', name: 'action' }
        ]
    });

    $('#morPurRequests_table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
        ajax:  "{{ url('collaborator/customerprofile-morPurRequests-datatable/') }}" +'/'+ customer_id ,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'req_date', name: 'req_date' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'status' },
            { data: 'source', name: 'source' },
            { data: 'comment', name: 'comment' },
            { data: 'action', name: 'action' }
        ]
    });

    $('#payRequests_table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
        ajax: "{{ url('collaborator/customerprofile-payRequests-datatable/') }}" +'/'+ customer_id ,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'pay_date', name: 'pay_date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' }
        ]
    });

} );
</script>
@endsection

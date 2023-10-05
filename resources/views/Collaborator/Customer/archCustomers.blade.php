@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Customers') }}
@endsection

@section('customer')


<div>
    @if (session('msg'))
    <div id="msg" class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg') }}
    </div>
    @endif
</div>


<div>
    @if (session('msg2'))
    <div id="msg" class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg2') }}
    </div>
    @endif
</div>




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Archived Customers') }}:</h3>

<br>

<div class="row">
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <table class="table table-borderless table-striped table-earning data-table" >
                <thead>
                    <tr>
                    <th>{{ MyHelpers::admin_trans(auth()->user()->id,'id') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'name') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'mobile') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'birth date') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'salary') }}</th>
                        <th>{{ MyHelpers::admin_trans(auth()->user()->id,'is supported') }}</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</div>


@endsection

@section('scripts')
<script>
$(document).ready( function () {
      $('.data-table').DataTable({
        "language": {
            "url": "{{route('datatableLanguage')}}"
        },
        processing: true,
        serverSide: true,
        ajax: "{{ url('collaborator/archcustomerpage-datatable') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'mobile', name: 'mobile' },
            { data: 'birth_date', name: 'birth_date' },
            { data: 'salary', name: 'salary' },
            { data: 'is_supported', name: 'is_supported' },
            { data: 'action', name: 'action' }
        ]
    });
} );
</script>
@endsection

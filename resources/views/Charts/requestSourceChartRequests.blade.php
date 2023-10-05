@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Charts') }} - {{ MyHelpers::admin_trans(auth()->user()->id,'request_sources') }}
@endsection

@section('css_style')

<!-- Vendor CSS-->
<link href="{{ url('interface_style/search/vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
<link href="{{ url('interface_style/search/vendor/datepicker/daterangepicker.css') }}" rel="stylesheet" media="all">

<!-- Main CSS-->

<!-- Main CSS-->

<style>
    svg:not(:root) {
        overflow: hidden;
        direction: ltr;
    }
</style>

{{--    NEW STYLE   --}}
<link rel="stylesheet" href="{{ asset('assest/datatable/style.css') }}">


@endsection

@section('customer')
<!-- MAIN CONTENT-->
<div class="addUser my-4">
    <div class="userBlock d-flex align-items-center justify-content-between flex-wrap">
        <h3>{{ MyHelpers::admin_trans(auth()->user()->id,'request_sources') }}:</h3>
    </div>
</div>

{{-- For Search Parameters   --}}
@include('Charts.requestSourceChart-parameters')


<div class="main-content">
    <div class="section_content section_content--p30">

        <div class="container-fluid">
            @include('Charts.requestSourcePendingChart-table')
        </div>

    </div>
</div>

@endsection

@section('scripts')

<script>

            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
                };

        var dt2 = $('.data-table2').DataTable({
            "language": {
                "url": "{{route('datatableLanguage')}}",
                buttons: {
                    excelHtml5: "اكسل",
                    pageLength: "عرض",

                }
            },
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            scrollX: true,
            scrollY: true,
            paging: true,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pageLength'
            ],
            initComplete: function() {
                $(".paginate_button").addClass("pagination-circle");
                /* To Adaptive with New Design */
                $('#example-search-input').keyup(function(){
                    dt2.search($(this).val()).draw() ;
                })

                dt2.buttons().container()
                    .appendTo( '#dt-btns2' );
                $('.buttons-excel').html('<i class="fas fa-file-import"></i>').attr('title','تصدير') ;
                $('.buttons-collection').html('<i class="fas fa-eye"></i>').attr('title','عرض') ;

                $('.buttons-excel').addClass('no-transition custom-btn');
                $('.buttons-collection').addClass('no-transition custom-btn');

                $('.tableAdminOption span').tooltip(top)
                $('button.dt-button').tooltip(top)
            },

            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };


	            var webAskFunding = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );


                var hasbah_net_completed = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var hasbah_net_notcompleted = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var webAskCons = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var app_askcons = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var app_calc = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var webCal = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var otared = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                var tamweelk = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer by showing the total with the reference of the column index
	        $( api.column( 0 ).footer() ).html('المجموع');

            $( api.column( 1 ).footer() ).html(webAskFunding);
            $( api.column( 2 ).footer() ).html(hasbah_net_completed);
            $( api.column( 3 ).footer() ).html(hasbah_net_notcompleted);
            $( api.column( 4 ).footer() ).html(webAskCons);
            $( api.column( 5 ).footer() ).html(app_askcons);
            $( api.column( 6 ).footer() ).html(app_calc);
            $( api.column( 7 ).footer() ).html(webCal);
            $( api.column( 8 ).footer() ).html(otared);
            $( api.column( 9 ).footer() ).html(tamweelk);
        },




        });



</script>

<!-- Jquery JS-->
<script src="{{ url('interface_style/search/vendor/jquery/jquery.min.js') }}"></script>
<!-- Vendor JS-->
<script src="{{ url('interface_style/search/vendor/select2/select2.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/bootstrap-wizard/bootstrap.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/datepicker/moment.min.js') }}"></script>
<script src="{{ url('interface_style/search/vendor/datepicker/daterangepicker.js') }}"></script>

<!-- Main JS-->
<script src="{{ url('interface_style/search/js/global.js') }}"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    var adviser_ids = ("{{implode(',',$adviser_ids)}}").split(',');
    //console.log(adviser_ids);
    $('#status_user').change(function () {
        reFullAdviser_id()
    })
    function reFullAdviser_id() {
        $this = $('#manager_id');
        $.get(
            '{{route('requestChartRApi')}}', {
                managerId: $this.val(),
                status_user:$('#status_user').val()
            },
            function(response) {
                $data = '<option value="0">الكل</option>';
                response.users.forEach(($user, $index) => {
                    $data += '<option value="' + $user.id + '"' +
                        (adviser_ids.includes('' + $user.id) ? 'selected' : '') +
                        '>' + $user.name + '</option>';

                });
                $('#adviser_id').html($data);
            });
    }

    reFullAdviser_id();
    $('#manager_id').on('change', function() {

        reFullAdviser_id();
    });

</script>

@endsection

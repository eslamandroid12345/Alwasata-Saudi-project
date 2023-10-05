@extends('layouts.content')

@section('title')
{{ MyHelpers::admin_trans(auth()->user()->id,'Active Customers') }}
@endsection

@section('css_style')

<style>

</style>
<!--NEW 2/2/2020 for hijri datepicker-->
<link href="{{url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" />

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


@if(session()->has('message2'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session()->get('message2') }}
</div>
@endif




<div id="msg2" class="alert alert-dismissible" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>

</div>

<h3>{{ MyHelpers::admin_trans(auth()->user()->id,'Active Customers') }}:</h3>

<br>

<div class="table-data__tool">
    <div class="table-data__tool-right">
        <a href="{{ route('collaborator.addPage')}}">
            <button class="au-btn au-btn-icon au-btn--blue au-btn--small ">
                <i class="zmdi zmdi-plus"></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Add') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Customer') }}</button></a>
    </div>
</div>
<div class="row">

    @if (!empty($customers[0]))
    <div class="col-12">
        <div class="table-responsive table--no-card m-b-30 data-table-parent">
            <table class="table table-borderless table-striped table-earning data-table" id="mycustomer-table">
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
    @else
    <div class="middle-screen">
        <h2 style=" text-align: center;font-size: 20pt;">{{ MyHelpers::admin_trans(auth()->user()->id,'No Customers') }}</h2>
    </div>

    @endif

</div>

@endsection

@section('updateModel')
@include('Collaborator.Customer.updateCustomer')
@endsection

@section('confirmMSG')
@include('Collaborator.Customer.confirmationMsg')
@endsection

@section('scripts')

<script>
$(document).ready( function () {
      $('#mycustomer-table').DataTable({
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
        ajax: "{{ url('collaborator/mycustomer-datatable') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'mobile', name: 'mobile' },
            { data: 'birth_date', name: 'birth_date' },
            { data: 'salry', name: 'salry' },
            { data: 'supported', name: 'supported' },
            { data: 'action', name: 'action' }
        ]
    });
} );
</script>

<!--  NEW 2/2/2020 hijri datepicker  -->
<script src="{{url('js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function() {
        $("#hijri-date").hijriDatePicker({
            hijri: true,
            format: "YYYY/MM/DD",
            hijriFormat: 'iYYYY-iMM-iDD',
            showSwitcher: false,
            showTodayButton: true,
            showClose: true
        });
    });
</script>

<script type="text/javascript">

    $("#convertToHij").click(function() {
        // alert($("#birth").val());
        if ($("#birth").val() == "") {
            alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");

        } else {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ URL('all/convertToHijri') }}",
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "gregorian": $("#birth").val(),
                },
                success: function(response) {
                    // alert(response);
                    $("#hijri-date").val($.trim(response));
                },
                error: function() {
                    swal({
                        title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                        text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                        html: true,
                        type: "error",
                    });
                }
            });
        }
    });

    $("#convertToGreg").click(function() {

        if ($("#hijri-date").val() == "") {
            alert("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter a date') }}");
        } else {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ URL('all/convertToGregorian') }}",
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "hijri": $("#hijri-date").val(),
                },
                success: function(response) {
                    // alert(response);
                     $("#birth").val($.trim(response));
                    calculate();
                },
                error: function(response) {
                    console.log(response);
                    swal({
                        title: "{{ MyHelpers::admin_trans(auth()->user()->id,'Failed') }}!",
                        text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Try Again') }}.",
                        html: true,
                        type: "error",
                    });
                }
            });
        }
    });
</script>




<script type="text/javascript">
    $(document).ready(function() {
        var today = new Date().toISOString().split("T")[0];
        // alert (today);
        $('#birth').attr("max", today);

    });

    //-----------------------------------
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //----------------------------



    ///////////////////////////////////////////////////////////
    $(document).on('click', '#edit', function(e) {

        $('#frm-update').find('#yes').removeAttr("checked");
        $('#frm-update').find('#no').removeAttr("checked");

        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile').removeClass('btn-success');
        $('#checkMobile').removeClass('btn-danger');
        $('#checkMobile').addClass('btn-info');

        var id = $(this).attr('data-id');
        $.get("{{route('collaborator.updateCustomer')}}", {
            id: id
        }, function(data) {

            if (data.status != 0) {

                $('#frm-update').find('#id').val(data[0].id);
                $('#frm-update').find('#name').val(data[0].name);
                $('#frm-update').find('#mobile').val(data[0].mobile);
                $('#frm-update').find('#age').val(data[0].age);
                $('#frm-update').find('#birth').val(data[0].birth_date);
                $('#frm-update').find('#hijri-date').val(data[0].birth_date_higri);
                $('#frm-update').find('#work').val(data[0].work);
                $('#frm-update').find('#sex').val(data[0].sex);
                $('#frm-update').find('#salary_source').val(data[0].salary_id);
                $('#frm-update').find('#salary').val(data[0].salary);

                var support = data[0].is_supported;
                if (support == 'yes')
                    $('#frm-update').find('#yes').attr('checked', 'checked');
                else if (support == 'no')
                    $('#frm-update').find('#no').attr('checked', 'checked');
                else {
                    $('#frm-update').find('#yes').removeAttr("checked");
                    $('#frm-update').find('#no').removeAttr("checked");
                }




                $('#myModal').modal('show');
                calculate();
            } else
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);


        })
    })


    ///////////////////////////////////////////

    $(document).on('click', '#archive', function(e) {
        var id = $(this).attr('data-id');

        var modalConfirm = function(callback) {


            $("#mi-modal").modal('show');


            $("#modal-btn-si").on("click", function() {
                callback(true);
                $("#mi-modal").modal('hide');
            });

            $("#modal-btn-no").on("click", function() {
                callback(false);
                $("#mi-modal").modal('hide');
            });
        };

        modalConfirm(function(confirm) {
            if (confirm) {

                $.post("{{ route('collaborator.archCustomer')}}", {
                    id: id
                }, function(data) { // we pass var id in the request with id as name ; we use $.post : because we pass our data to server and got response


                    if (data.status == 1) {
                        var d = ' # ' + id;
                        var test = d.replace(/\s/g, ''); // to remove all spaces in var d , to find the <tr/> that i deleted and reomve it
                        $(test).remove(); // remove by #id

                        $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);
                    } else {
                        $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>" + data.message);

                    }


                })



            } else {
                //No archive
            }
        });


    })


    ///////////////////////////////////////////

    $('#frm-update').on('submit', function(e) {




        $('#nameError').addClass("d-none");
        $('#mobileError').addClass("d-none");
        $('#birthError').addClass("d-none");
        $('#sexError').addClass("d-none");
        $('#workError').addClass("d-none");
        $('#salary_sourceError').addClass("d-none");
        $('#salaryError').addClass("d-none");


        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr('action');





        $.post(url, data, function(data) { //data is array with two veribles (request[], ss)

            if (data == "existed") {

                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> {{ MyHelpers::admin_trans(auth()->user()->id,'This customer already existed') }}");
            } else if (data.ss == 1) {

                var optiones = "<div class='table-data-feature'>  <button class='item' id='open' data-toggle='tooltip' data-id= " + data.request.id + "  data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Open') }}'><a  href='/customerprofile/" + data.request.id + "'> <i class='zmdi zmdi-eye'></i></a> </button> <button class='item'  id='edit' type='button' data-id= " + data.request.id + "  data-toggle='tooltip' data-placement='top' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Edit') }}'><i class='zmdi zmdi-edit'></i> </button> <button class='item' data-id= " + data.request.id + "  data-toggle='tooltip' data-placement='top' id='archive' title='{{ MyHelpers::admin_trans(auth()->user()->id,'Archive') }}'><i class='zmdi zmdi-delete'></i> </button> </div>"


                //now update our row with a new value
                var is_sup = data.request.support;
                if (is_sup == 'yes')
                    is_sup_class = "status--process";
                else
                    is_sup_class = "status--denied";




                var fn = $("<tr/>").attr('id', data.request.id).addClass("tr-shadow");
                fn.append($("<td/>", {
                    html: "<label class='au-checkbox'><input type='checkbox'><span class='au-checkmark'></span></label>"
                })).append($("<td/>", {
                    text: data.request.id
                })).append($("<td/>", {
                    text: data.request.name
                })).append($("<td/>", {
                    text: data.request.mobile
                }).addClass("desc")).append($("<td/>", {
                    text: data.request.birth
                })).append($("<td/>", {
                    text: data.request.salary + " {{ MyHelpers::admin_trans(auth()->user()->id,'SR') }}"
                })).append($("<td/>", {
                    html: "<span class= ' " + is_sup_class + " ' > " + data.request.support + " </span>"
                })).append($("<td/>", {
                    html: optiones
                }))

                var d = ' # ' + data.request.id;
                var test = d.replace(/\s/g, ''); //#data.id

                $(test).replaceWith(fn); //to replace a new row with an old row based on var test

                $('#msg2').addClass("alert-success").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Update Successfully') }}");

            } else if (data.ss == 0) {
                $('#msg2').addClass("alert-warning").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button>{{ MyHelpers::admin_trans(auth()->user()->id,'Nothing Change') }}! ");

            }


            $('#myModal').modal('hide');



        }).fail(function(data) {

            var errors = data.responseJSON;

            if ($.isEmptyObject(errors) == false) {

                $.each(errors.errors, function(key, value) {

                    var ErrorID = '#' + key + 'Error';
                    $(ErrorID).removeClass("d-none");
                    $(ErrorID).text(value);

                })

            }
        });

    });

    ////////////////////////////////////////////////

    function calculate() {
        var date = new Date(document.getElementById('birth').value);
        var dateString = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());


        var now = new Date();
        var today = new Date(now.getYear(), now.getMonth(), now.getDate());

        var yearNow = now.getYear();
        var monthNow = now.getMonth();
        var dateNow = now.getDate();

        var dob = new Date(dateString.substring(6, 10),
            dateString.substring(0, 2) - 1,
            dateString.substring(3, 5)
        );

        var yearDob = dob.getYear();
        var monthDob = dob.getMonth();
        var dateDob = dob.getDate();
        var age = {};
        var ageString = "";
        var yearString = "";
        var monthString = "";
        var dayString = "";


        yearAge = yearNow - yearDob;

        if (monthNow >= monthDob)
            var monthAge = monthNow - monthDob;
        else {
            yearAge--;
            var monthAge = 12 + monthNow - monthDob;
        }

        if (dateNow >= dateDob)
            var dateAge = dateNow - dateDob;
        else {
            monthAge--;
            var dateAge = 31 + dateNow - dateDob;

            if (monthAge < 0) {
                monthAge = 11;
                yearAge--;
            }
        }

        age = {
            years: yearAge,
            months: monthAge,
            days: dateAge
        };

        if (age.years > 1) yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'years') }}";
        else yearString = " {{ MyHelpers::admin_trans(auth()->user()->id,'year') }}";
        if (age.months > 1) monthString = "  {{ MyHelpers::admin_trans(auth()->user()->id,'months') }}";
        else monthString = " {{ MyHelpers::admin_trans(auth()->user()->id,'month') }}";
        if (age.days > 1) dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'days') }}";
        else dayString = " {{ MyHelpers::admin_trans(auth()->user()->id,'day') }}";


        if ((age.years > 0) && (age.months > 0) && (age.days > 0))
            ageString = age.years + yearString + ", " + age.months + monthString + ", {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
        else if ((age.years == 0) && (age.months == 0) && (age.days > 0))
            ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Only') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}";
        else if ((age.years > 0) && (age.months == 0) && (age.days == 0))
            ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}. ";
        else if ((age.years > 0) && (age.months > 0) && (age.days == 0))
            ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
        else if ((age.years == 0) && (age.months > 0) && (age.days > 0))
            ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
        else if ((age.years > 0) && (age.months == 0) && (age.days > 0))
            ageString = age.years + yearString + " {{ MyHelpers::admin_trans(auth()->user()->id,'and') }} " + age.days + dayString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
        else if ((age.years == 0) && (age.months > 0) && (age.days == 0))
            ageString = age.months + monthString + " {{ MyHelpers::admin_trans(auth()->user()->id,'old') }}.";
        else ageString = "{{ MyHelpers::admin_trans(auth()->user()->id,'Could not calculate age') }}!";


        document.getElementById('age').value = ageString;


    }


    //////////////////////////////////////////////////////////////////


    function changeMobile() {
        document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
        $('#checkMobile').removeClass('btn-success');
        $('#checkMobile').removeClass('btn-danger');
        $('#checkMobile').addClass('btn-info');

    }

    $(document).on('click', '#checkMobile', function(e) {



        $('#checkMobile').attr("disabled", true);
        document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";


        var mobile = document.getElementById('mobile').value;
        var regex = new RegExp(/^(05)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);

        console.log(regex.test(mobile));

        if (mobile != null && regex.test(mobile)) {
            document.getElementById('error').innerHTML = "";

            $.post("{{ route('all.checkMobile') }}", {
                mobile: mobile
            }, function(data) {
                if (data.errors) {
                    if (data.errors.mobile) {
                        $('#mobile-error').html(data.errors.mobile[0])
                    }
                } if (data == "no") {
                    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile').removeClass('btn-info');
                    $('#checkMobile').addClass('btn-success');
                    $('#checkMobile').attr("disabled", false);
                } else {
                    document.querySelector('#checkMobile').innerHTML = "<i class='fa fa-times'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile').removeClass('btn-info');
                    $('#checkMobile').addClass('btn-danger');
                    $('#checkMobile').attr("disabled", false);
                }


            }).fail(function(data) {


            });



        } else {
            document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (10 digits) and starts 05') }} ";
            document.getElementById('error').display = "block";
            document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile').attr("disabled", false);

        }



    });
</script>



@endsection

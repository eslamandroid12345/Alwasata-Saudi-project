<script>
    function checkPhone(id) {
        $('#errordata'+id).html('');

       /* var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);*/
        var mobile = $('#mobileNumbers'+id).val();
        /*console.log(regex.test(mobile));*/

        if (mobile != null/* && regex.test(mobile)*/) {
            $('#check'+id).attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> تحميل");
            $.post("{{ route('all.checkMobile') }}", {
                mobile: mobile,
                "_token": "{{csrf_token()}}",
            }, function (data) {

                $('#check' + id).attr('disabled', false).html(" تحقق");
                if (data == "error") {
                    document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                    document.getElementById('error').display = "block";
                    document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    $('#checkMobile').attr("disabled", false);
                }
                if ($.trim(data) == "no") {
                    $('#check' + id).removeClass('btn-danger').removeClass('btn-warning').addClass('btn-success').html("<i class='fa fa-check'></i>  متاح");
                    $('#errorcode' + id).html("")
                    $('#mobileNumbers' + id).removeClass('is-invalid')
                } else {
                    if (data.errors) {
                        $('#mobileNumbers' + id).addClass('is-invalid')
                        if (data.errors.mobile) {
                            $('#errorcode' + id).html(data.errors.mobile[0])
                        }
                        $('#check' + id).removeClass('btn-success').addClass('btn-warning').html("<i class='fa fa-times'></i> خطأ ");
                    } else {
                        $('#errorcode' + id).html("")
                        $('#check' + id).removeClass('btn-success').addClass('btn-danger').html("<i class='fa fa-times'></i> غير متاح");
                    }
                }
            });
        }else{
            $('#mobileNumbers' + id).addClass('is-invalid')
            $('#errorcode' + id).html("{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}")

            $('#check' + id).removeClass('btn-success').removeClass('btn-danger').addClass('btn-warning').html("<i class='fa fa-times'></i> خطأ ");
        }
    }
    function submitDelete() {
        $('.modal-footer #savePhones').attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> تحميل");
        var id = $('#confirm-form #id_delete').val();
        $.ajax({
            url: "{{ url($prefix.'/phones/') }}" + '/' + id,
            type: "POST",
            data: {
                '_method': 'DELETE',
                '_token': "{{csrf_token()}}"
            },
            success: function(data) {
                 $('.modal-footer #savePhones').attr('disabled',false).html("نعم ,إمسح");
                $('#phoneId'+id).remove();
                $('#confirm-form').modal('hide');

                var ShowFormDel =$("#ShowFormNumber");
                ShowFormDel.text(parseInt(ShowFormDel.text()) - 1);
                if(data.count == 0){
                    $('#emptyBTN').css('display','none');
                    $('#empty').html('لا يوجد جولات أخرى ');

                    $('#showForm').css("display","none")
                }else{
                    $('#emptyBTN').css('display','block');
                    $('#empty').html("");
                }
                $('#add-form').modal('hide');
            },
            error: function() {
            }
        });
    }
    function deletePhone(id) {
        $('#confirm-form').modal('show');
        $('#confirm-form #id_delete').val(id);
        $('#confirm-form #myLargeModalLabel1').text('مسح الرقم');

    }
    function addForm() {
        $('.errordata').html("")
        save_method = "add";
        $('#add_mobile').val("")
        $('.form-control').removeClass("is-invalid")
        $('#mobile-error').html("")
        $('#add_mobile_error').html("")
        $('#edit-data').html("<i class='fa fa-spinner fa-spin'></i> تحميل");
        $('#add-form input[name=_method]').val('POST');
        $.ajax({
            url: "{{ url($prefix.'/phones') }}" + '/' + {{$id}} + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var edit = $('#edit-data');
                edit.html("");
                document.querySelector('#checkMobile2').innerHTML = "  {{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                $('#checkMobile2').removeClass('btn-danger');
                // $('#checkMobile2').addClass('badge badge-info pointer has-tooltip');
                $('#checkMobile2').attr("disabled", false);

                if(data != ""){
                    $('#emptyBTN').css('display','block');
                    $('#empty').html("");
                    $.each( data, function( key, value ) {
                        @if (auth()->user()->role == 7)
                        edit.append(
                            '<div class="row" id="phoneId'+value.id+'"><label class="col-lg-12">رقم الجوال</label>'+
                            '<div class="col-lg-9">'
                            + ' <input type="number" onchange="change()" onkeydown="keydown()" class="form-control numbers" value="'+value.mobile+'" id="mobileNumbers'+value.mobile+'"  autofocus name="mobileNumbers[]" required>' +
                            '<input type="hidden"   value="'+value.id+'" name="mobileNumbersIds[]">'+
                            '<span class="text-danger errordata pt-1" id="errordata'+value.id+'"></span>'+
                            '<span class="text-danger errorcode pt-1" id="errorcode'+value.mobile+'"></span>'+
                            '</div>' +
                            '<div class="col-lg-3">' +
                            '<button type="button" class="btn btn-primary btn-sm mr-1" id="check'+value.mobile+'" style="float: right" onclick="checkPhone('+value.mobile+')">' +
                            'تحقق' +
                            '</button>' +
                            '<button type="button" class="btn btn-danger btn-sm mr-1" style="float: right" onclick="deletePhone('+value.id+')">' +
                            '<i class="fa fa-trash"></i>' +
                            '</button>' +
                            '</div><div class="col-lg-12 text-danger error-bug" id="unavalible'+value.mobile+'" style="display: none" >' +
                            '<span class="badge badge-danger">غير متاح</span>' +
                            '</div><div class="col-lg-12  text-success  error-bug" id="avalible'+value.mobile+'" style="display: none"> ' +
                            '<span class="badge badge-success"> متاح</span>' +
                            '</div></div>');
                        @else
                        edit.append(
                            '<div class="row" id="phoneId'+value.id+'"><label class="col-lg-2">رقم الجوال</label>'+
                            '<div class="col-lg-10">'
                            + ' <input type="text" class="form-control numbers" value="'+value.mobile+'" disabled id="mobileNumbers'+value.mobile+'"  autofocus name="mobileNumbers[]" required>' +
                            '<input type="hidden"  value="'+value.id+'" name="mobileNumbersIds[]">'+
                            '<span class="text-danger errordata pt-1" id="errordata'+value.id+'"></span>'+
                            '<span class="text-danger errorcode pt-1" id="errorcode'+value.mobile+'"></span>'+
                            '</div></div>');

                        @endif
                    });
                    edit.append(  '<label class="text-danger" id="p"></label>');
                }else{
                    $('#emptyBTN').css('display','none');
                    $('#empty').html('لا يوجد جولات أخرى ');
                }

            },
            error: function() {
                alert("Nothing Data");
            }
        });
        $('#add-form').modal('show');
        $('#add-form form')[0].reset();
        $('#add-form #myLargeModalLabel').text('التحكم فى الأرقام');

    }
    function showForm() {
        $.ajax({
            url: "{{ url($prefix.'/phones') }}" + '/' + {{$id}} + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var edit = $('#show-data');
                edit.html("");
                if(data != ""){
                    $.each( data, function( key, value ) {
                        edit.append(
                            '<div class="row" id="phoneId'+value.id+'"><label class="col-lg-2">رقم الجوال</label>'+
                            '<div class="col-lg-10">'
                            + ' <input type="text" class="form-control" value="'+value.mobile+'" disabled id="mobileNumbers'+value.mobile+'"  autofocus name="mobileNumbers[]" required>' +
                            '<input type="hidden"  value="'+value.id+'" name="mobileNumbersIds[]">'+
                            '<span class="text-danger errordata pt-1" id="errordata'+value.id+'"></span>'+
                            '<span class="text-danger errorcode pt-1" id="errorcode'+value.mobile+'"></span>'+
                            '</div></div>');
                    });
                    edit.append(  '<label class="text-danger" id="p"></label>');
                }else{
                    $('#emptyBTN1').css('display','none');
                    $('#empty1').html('لا يوجد جولات أخرى ');
                }

            },
            error: function() {
                alert("Nothing Data");
            }
        });
        $('#show-form').modal('show');

    }
    function editForm(id) {
        $('input[name=_method]').val('PATCH');
        $('#edit-form form')[0].reset();
        $('#edit-form .modal-title').text('تعديل الأرقام');
        $.ajax({
            url: "{{ url($prefix.'/phones') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#edit-form').modal('show');
                $('#edit-form #id').val(data.id);
                $('#edit-form #question').val(data.question);
            },
            error: function() {
                alert("Nothing Data");
            }
        });
    }

    $("#add_mobile").on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });

    $(".numbers").on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });

    $('#submit').on('click', function(e) {
        $('#submit').attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> تحميل");
        if (!e.isDefaultPrevented()) {
            url = "{{ url($prefix.'/phones') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "mobile": $("#add_mobile").val(),
                    "request_id": $("#add_request_id").val(),
                    "customer_id": $("#add_customer_id").val(),
                },
                success: function(data) {
                    if (data.errors) {
                        if (data.errors.mobile) {
                            $('#mobile-error').html(data.errors.mobile[0]);
                        }
                        $('#submit').addClass('btn-danger').attr('disabled',false).html("إضافة");
                    }

                    if (data.success) {
                        $('#submit').addClass('btn-primary').attr('disabled',false).html("إضافة");
                        $('#add-form').modal('hide');
                        $('.data-table').DataTable().ajax.reload();
                        var ShowForm =$("#ShowFormNumber");
                        ShowForm.text(parseInt(ShowForm.text()) + 1);
                        $('#showForm').css("display","block")
                        swal({
                            title: 'تم!',
                            text: data.message,
                            type: 'success',
                            timer: '750'
                        })
                    }
                },
                error: function(data) {
                    $('#submit').attr('disabled',false).html("إضافة");
                    swal({
                        title: 'خطأ',
                        text: data.message,
                        type: 'error',
                        timer: '750'
                    })
                }
            });
            return false;
        }
    });
    function checkForDuplicates(array) {
        let valuesAlreadySeen = []

        for (let i = 0; i < array.length; i++) {
            let value = array[i]
            if (valuesAlreadySeen.indexOf(value) !== -1) {
                return true
            }
            valuesAlreadySeen.push(value)
        }
        return false
    }
    function checkForDuplicatesValues(array) {
        let valuesAlreadySeen = []

        for (let i = 0; i < array.length; i++) {
            let value = array[i]
            if (valuesAlreadySeen.indexOf(value) !== -1) {
                return valuesAlreadySeen
            }
            valuesAlreadySeen.push(value)
        }
        return false
    }

    $('#savePhones').on('click', function(e) {

        $('#savePhones').attr('disabled',true).html("<i class='fa fa-spinner fa-spin'></i> حفظ");
        var data =$("input[name='mobileNumbers[]']");
        var ids =$("input[name='mobileNumbersIds[]']");
        var numbers=[];
        var idsData=[];
        $.each( data, function( key, value ) {
            numbers.push(value.value);
        });
        var error = checkForDuplicates(numbers);
        $.each( ids, function( key, value ) {
            idsData.push(value.value);
        });
        if(error==false){
            if (!e.isDefaultPrevented()) {
                url = "{{ url($prefix.'/phones-updates') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        "_token": "{{csrf_token()}}",
                        "mobile":numbers,
                        "ids":idsData,
                        "request_id": $("#add_request_id").val(),
                        "customer_id": $("#add_customer_id").val(),
                    },
                    success: function(data) {
                        $('.errorcode').html("")
                        $('#savePhones').attr('disabled',false).html(" حفظ");
                        if (data.validations) {
                            $.each( data.validations, function( key, value ) {
                                $('#mobileNumbers'+key).addClass('is-invalid');
                                $('#errorcode'+key).html(value)
                            });
                        }
                        if (data.errors) {
                            if (data.errors) {
                                $('#p').html(data.errors);
                            }
                            if (data.numbers) {
                                $.each( data.numbers, function( key, value ) {
                                    $('#mobileNumbers'+value).addClass('is-invalid')
                                });
                            }
                        }
                        if (data.success) {
                            $('#add-form').modal('hide');
                            $('.data-table').DataTable().ajax.reload();
                            swal({
                                title: 'تم!',
                                text: data.message,
                                type: 'success',
                                timer: '750'
                            })
                        }
                    },
                    error: function(data) {
                        $('#savePhones').attr('disabled',false).html(" حفظ");
                    }
                });
                return false;
            }
        }else{
            $('#p').html('يوجد أرقام مكررة ');
            $('#savePhones').attr('disabled',false).html(" حفظ");
            $.each(checkForDuplicatesValues(numbers), function( key, value ) {
                $('#mobileNumbers'+value).addClass('is-invalid');
            });
            return false;
        }

    });

    $(document).on('click', '#checkMobile2', function(e) {
        e.preventDefault();
      /*  $('#add_mobile').attr("disabled", true);*/
        document.querySelector('#checkMobile2').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";
        var mobile = document.getElementById('add_mobile').value;
       /* var regex = new RegExp(/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/);
        console.log(regex.test(mobile));*/
        if (mobile != null /*&& regex.test(mobile)*/) {
            document.getElementById('add_mobile_error').innerHTML = "";
            $.post("{{ route('all.checkMobile') }}", {
                mobile: mobile,
                "_token": "{{csrf_token()}}",
            }, function(data) {
                if (data == "error") {
                    document.getElementById('error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
                    document.getElementById('error').display = "block";
                    document.querySelector('#checkMobile').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
                    $('#checkMobile').attr("disabled", false);
                }
                if ($.trim(data) == "no") {
                    document.querySelector('#checkMobile2').innerHTML = "<i class='fa fa-check'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile2').removeClass('btn-info');
                    $('#checkMobile2').addClass('btn-success');
                    $('#checkMobile2').attr("disabled", false);
                } else {
                    document.querySelector('#checkMobile2').innerHTML = "<i class='fa fa-times'></i> {{ MyHelpers::admin_trans(auth()->user()->id,'Not') }} {{ MyHelpers::admin_trans(auth()->user()->id,'Available') }}";
                    $('#checkMobile2').removeClass('badge-info');
                    $('#checkMobile2').addClass('badge-danger');
                    $('#checkMobile2').attr("disabled", false);
                }
            }).fail(function(data) {
            });
        } else {
            document.getElementById('add_mobile_error').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Enter Valid Mobile Number (9 digits) and starts 5') }}";
            document.getElementById('add_mobile_error').display = "block";
            document.querySelector('#checkMobile2').innerHTML = "{{ MyHelpers::admin_trans(auth()->user()->id,'Check') }}";
            $('#checkMobile2').attr("disabled", false);
        }
    });
</script>

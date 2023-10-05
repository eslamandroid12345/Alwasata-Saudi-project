@include('FundingCalculater.calculatorScripts')

<script>

    var senarios_of_display_result = null;
    var senarios_agent_of_display_result = null;

    function getCalclaterResultSettings() {

        $.get("{{ route('calculater.getCalculaterResultSettings')}}",
            function (data) {
                senarios_of_display_result = data;
            });
    }

    function getAgentCalclaterResultSettings(senario_id) {

        $.ajax({
            type: "GET",
            url: "{{ route('calculater.getAgentCalculaterResultSettings')}}",
            async: false,
            data: {
                senario_id: senario_id
            },
            success: function (data) {
                handelData(data);
            }
        });

    }

    function handelData(data) {
        senarios_agent_of_display_result = data;
    }


    function applyCommaForEachNumbers() {
        $(".numberWithComma").each(function () {
            var num = $(this).val();
            var commaNum = numberWithCommas(num);
            $(this).val(commaNum);

        });
    }

    $('.numberWithComma').keyup(function () {
        var num = $(this).val();
        var commaNum = numberWithCommas(num);
        $(this).val(commaNum);
    });

    function valueChanged() {
        if ($('#residential_support_caculater').prop("checked"))
            $(".secondToggle").fadeIn();
        else {
            $(".secondToggle").fadeOut();
            $('#add_support_installment_to_salary_caculater').prop('checked', false);
            $('#guarantees_caculater').prop('checked', false);
            $('#extension_support_installment_caculater').prop('checked', false);
        }

    }

    function valueChanged5() {
        if ($('#jointToggleMine').is(":checked"))
            $(".jointToggle").fadeIn();
        else {
            $(".jointToggle").fadeOut();
            $('#joint_add_support_installment_to_salary_caculater').prop('checked', false);
        }

    }

    function sahalChanged() {

        if ($('.sahal').is(":checked"))
            $(".sahal_section").slideDown();
        else {
            $(".sahal_section").slideUp();
            $(".sahal_input").val('');
        }

    }

    function extendedChanged() {

        if ($('.extended').is(":checked"))
            $(".extended_section").slideDown();
        else {
            $(".extended_section").slideUp();
            $(".extended_input").val('');
        }

    }

    function first_batch_mode_inputs() {
        if ($('#first_batch_mode').is(":checked")) {
            $("#valueOn").attr("disabled", true);
            $('#valueOn').prop('checked', false);
            $(".valueOn").slideUp();
            $("#first_batch_profit_caculater").val('');
            $("#fees_caculater").val('');
            $("#discount_caculater").val('');
        } else {
            $("#valueOn").attr("disabled", false);
        }

    }

    function valueChanged1() {
        if ($('.secondChecked').is(":checked"))
            $(".thirdinput").fadeIn();
        else {
            $(".thirdinput").fadeOut();
        }

    }

    function valueChanged2() {
        if ($('.detectionChecked').is(":checked"))
            $(".detectionInput").fadeIn();
        else {
            $(".detectionInput").fadeOut();
            $("#personal_salary_deduction_caculater").val("");
            $("#personal_funding_months_calculater").val("");
            $("#salary_deduction_calculater").val("");
            $("#funding_months_calculater").val("");


            $(".early").fadeOut();
        }

    }

    function valueEarly() {
        if ($('#inlineearlly').is(":checked"))
            $(".early").fadeIn();
        else {

            $(".early").fadeOut();
            $("#early_repayment_calculater").val("");
            $("#credit_installment_calculater").val("");
            $("#obligations_installment_calculater").val("");
            $("#remaining_obligations_months_calculater").val("");

        }

    }

    function jointValueEarly() {
        if ($('#jointInlineearlly').is(":checked"))
            $(".jointearly").fadeIn();
        else {
            $(".jointearly").fadeOut();
            $("#joint_early_repayment_caculater").val("");
            $("#joint_credit_installment_calculater").val("");
            $("#joint_obligations_installment_calculater").val("");
            $("#joint_remaining_obligations_months_calculater").val("");
        }

    }

    function first_batch_inputs() {
        if ($('#valueOn').is(":checked")) {
            $(".valueOn").slideDown();
            $("#first_batch_mode").attr("disabled", true);
            $('#first_batch_mode').prop('checked', false);
        } else {
            $("#first_batch_mode").attr("disabled", false);
            $(".valueOn").slideUp();
            $("#first_batch_profit_caculater").val('');
            $("#first_batch_percentage_caculater").val('');
            $("#fees_caculater").val('');
            $("#discount_caculater").val('');
        }

    }

    function valueSupport() {
        if ($('#support').is(":checked"))
            $(".support").slideDown();
        else
            $(".support").slideUp();
    }

    function valueEarn() {
        if ($('#Earn').is(":checked"))
            $(".Earn").slideDown();
        else
            $(".Earn").slideUp();
    }

    function valueSave() {
        if ($('#save').is(":checked"))
            $(".save").slideDown();
        else {
            $(".save").slideUp();
            $("#joint_hijri_date_caculater").val('');
            $("#joint_salary_caculater").val('');
            $("#joint_work_caculater").val('');
            $("#joint_rank_caculater").val('');
            $("#joint_salary_source_caculater").val('');


            $('#jointToggleMine').prop('checked', false);
            $('#jointInlineearlly').prop('checked', false);
            $('#joint_add_support_installment_to_salary_caculater').prop('checked', false);
            $("#joint_early_repayment_caculater").val('');

            $(".jointearly").fadeOut();
            $(".jointToggle").fadeOut();
        }

    }

    function showCalculaterResult() {
        $('#fundingCalculaterResult').fadeIn();
        $('.userFormsResult').css('display', 'block');

    }

    function hideCalculaterResult() {
        $('#fundingCalculaterResult').fadeOut();
        $('.userFormsResult').css('display', 'none');
    }


    function hideLodingDiv() {

        $(".loading").fadeToggle().addClass("d-grid-new");


    }

    function showLodingDiv() {

        $(".loading").fadeToggle().removeClass("d-grid-new");


    }


    var dataObj = {};
    var resultArray = {};
    var maxFundingArray = {};
    var requestID = $("#reqId").val();
    hideLodingDiv();

    function getLastcalCulaterData() {

        $.ajax({
            type: "GET",
            url: "{{ route('calculater.getLastcalCulaterData')}}",
            data: {
                requestID: requestID
            },
            success: function (data) {

                if (data) {
                    $("#product_type_id_caculater").val(data.product_code);
                    $("#hijri_date_caculater").val(data.birth_hijri);
                    $("#salary_caculater").val(data.salary);
                    $("#work_caculater").val(data.work);
                    if (data.work === 1) {
                        document.getElementById("askary_caculater").style.display = "block";
                    }


                    $("#rank_caculater").val(data.military_rank);
                    $("#salary_source_caculater").val(data.salary_bank_id);
                    $("#basic_salary_caculater").val(data.basic_salary);

                    if (data.guarantees === 1)
                        $("#guarantees_caculater").prop('checked', true);

                    if (data.residential_support === 1)
                        $('#toggleMine').prop('checked', true);

                    if (data.add_support_installment_to_salary === 1)
                        $("#add_support_installment_to_salary_caculater").prop('checked', true);

                    if (data.without_transfer_salary === 1)
                        $("#without_transfer_salary_caculater").prop('checked', true);


                    if (data.personal_salary_deduction || data.salary_deduction || data.funding_months || data.personal_funding_months)
                        $("#show_detection_filds").prop('checked', true);

                    $("#personal_salary_deduction_caculater").val(data.personal_salary_deduction);
                    $("#salary_deduction_calculater").val(data.salary_deduction);
                    $("#funding_months_calculater").val(data.funding_months);
                    $("#personal_funding_months_calculater").val(data.personal_funding_months);

                    //$("#hijri_date_caculater").val(data.personal_bank_profit);
                    //$("#hijri_date_caculater").val(data.bank_profit);

                    if (data.early_repayment) {
                        $("#inlineearlly").prop('checked', true);
                        $("#early_repayment_calculater").val(data.early_repayment);
                        $("#credit_installment_calculater").val(data.credit_installment);
                        $("#obligations_installment_calculater").val(data.obligations_installment);
                        $("#remaining_obligations_months_calculater").val(data.remaining_obligations_months);
                    }


                    $("#property_amount_caculater").val(data.property_amount);
                    $("#property_completed_caculater").val(data.property_completed);
                    $("#residence_type_caculater").val(data.residence_type);


                    $("#housing_allowance_caculater").val(data.housing_allowance);
                    $("#transfer_allowance_caculater").val(data.transfer_allowance);
                    $("#other_allowance_caculater").val(data.other_allowance);
                    $("#retirement_income_caculater").val(data.retirement_income);
                    $("#job_tenure_caculater").val(data.hiring_date);

                    if (data.have_joint === 1)
                        $("#save").prop('checked', true);

                    $("#joint_hijri_date_caculater").val(data.joint_birth_hijri);
                    $("#joint_salary_caculater").val(data.joint_salary);


                    $("#joint_work_caculater").val(data.joint_work);
                    if (data.joint_work === 1)
                        document.getElementById("joint_askary").style.display = "block";


                    $("#joint_rank_caculater").val(data.joint_military_rank);
                    $("#joint_salary_source_caculater").val(data.joint_salary_bank_id);

                    if (data.joint_residential_support === 1)
                        $("#jointToggleMine").prop('checked', true);

                    if (data.joint_add_support_installment_to_salary === 1)
                        $("#joint_add_support_installment_to_salary_caculater").prop('checked', true);

                    if (data.joint_early_repayment) {
                        $("#jointInlineearlly").prop('checked', true);
                        $("#joint_early_repayment_caculater").val(data.joint_early_repayment);
                        $("#joint_credit_installment_calculater").val(data.joint_credit_installment);
                        $("#joint_obligations_installment_calculater").val(data.joint_obligations_installment);
                        $("#joint_remaining_obligations_months_calculater").val(data.joint_remaining_obligations_months);
                    }

                    $("#joint_personal_salary_deduction_caculater").val(data.joint_personal_salary_deduction);
                    $("#joint_salary_deduction_calculater").val(data.joint_salary_deduction);
                    $("#joint_funding_months_calculater").val(data.joint_funding_months);
                    $("#joint_personal_funding_months_calculater").val(data.joint_personal_funding_months);


                    $("#joint_housing_allowance_caculater").val(data.joint_housing_allowance);
                    $("#joint_transfer_allowance_caculater").val(data.joint_transfer_allowance);
                    $("#joint_other_allowance_caculater").val(data.joint_other_allowance);
                    $("#joint_retirement_income_caculater").val(data.joint_retirement_income);
                    $("#joint_job_tenure_caculater").val(data.joint_hiring_date);

                    if (data.provide_first_batch === 1)
                        $("#valueOn").prop('checked', true);

                    if (data.first_batch_mode === 1)
                        $("#first_batch_mode").prop('checked', true);

                    $("#first_batch_percentage_caculater").val(data.first_batch_percentage);
                    $("#first_batch_profit_caculater").val(data.first_batch_profit);
                    $("#fees_caculater").val(data.fees);
                    $("#discount_caculater").val(data.discount);


                    valueChanged();
                    valueChanged1();
                    valueChanged2();
                    valueChanged5();
                    sahalChanged();

                    //extendedChanged();

                    valueEarly();
                    jointValueEarly();
                    first_batch_inputs();
                    first_batch_mode_inputs();
                    valueSave();
                    applyCommaForEachNumbers();

                }
            },
            complete: function () {

                hideLodingDiv();

            },
            error: function (data) {

            }


        });
    }

    $(document).on('click', '#content6', function (e) {


        showLodingDiv();
        hideCalculaterResult();
        getCalclaterResultSettings();


        $('#product_type_id_caculater').html(''); //remove all options from select

        $.ajax({
            type: "GET",
            url: "{{route('calculater.calculaterGetProductTypes')}}",
            success: function (data) {

                $("#product_type_id_caculater").append(new Option('---', ''));

                for (var k = 0; k < data.length; k++)
                    $("#product_type_id_caculater").append(new Option(data[k]['name_ar'], data[k]['code']));
            },
            complete: function () {
                getLastcalCulaterData();
            },
            error: function (data) {

            }


        });


    });

    $(document).on('click', '#calculateSubmit', function (e) {
        showLodingDiv();
        hideCalculaterResult();
        $("#calResultDetails").empty();
        $("#calResultList").empty();
        $("#hint-extension-support-installment").addClass('d-none');
        try {
            const v = $("#extension_support_installment_caculater").prop('checked') && $
            ("#residential_support_caculater").prop('checked');
            const c = !v ? 'addClass' : 'removeClass';
            $("#hint-extension-support-installment")[c]('d-none');
        } catch (e) {
            console.log(e)
        }


        document.getElementById('msg2').style.display = "none";
        $('#calculateSubmit').attr("disabled", true);
        document.querySelector('#calculateSubmit').innerHTML = "<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}";

        emptyErrorsMsg();
        e.preventDefault();

        //-------DID ALL OF THESE STEPS BECAUSE FORM ID OF CALCULATER AND SERLIAZE() RETURN EPTY STRING :\ ----------//
        var dataArray = $('form').serializeArray();

        dataObj = {};
        $(dataArray).each(function (i, field) {
            if (field.name.endsWith('_caculater')) // GET ONLY FILEDS OF CACULATER FORM
                dataObj[field.name] = field.value.replace(/,/g, "");
        });
        //----------------//

        // console.log(dataObj);

        $.ajax({
            headers: {
                'X-CSRF-Token': "{{csrf_token()}}"
            },
            type: "POST",
            url: "{{route('calculater.calculaterApi')}}",
            data: dataObj,
            success: function (data) {

                //console.log(data);

                if (data.calculaterResults.length > 0) {
                    maxFundingArray = data.max_funding;
                    resultArray = data.calculaterResults;
                    calculatorResultList(resultArray, dataObj, data.flexibleSettings, data.personalSettings, data.propertySettings, data.extendedSettings);
                    showCalculaterResult();
                } else {
                    hideCalculaterResult();
                    noResultOfClculater();
                    Swal.fire({
                        html: "لايوجد عروض تمويل",
                        showCloseButton: true,
                        confirmButtonText: "موافق",
                    });
                }

            },
            complete: function () {
                hideLodingDiv();
            },
            error: function (data) {
                $('#msg2').addClass("alert-danger").removeAttr("style").html("<button type='button' class='close' data-dismiss='alert'>&times;</button> حاول مرة أخرى");
                document.getElementById('msg2').style.display = "block";
            }


        }).fail(function (data) {

            var errors = data.responseJSON;

            if ($.isEmptyObject(errors) === false) {

                $.each(errors.errors, function (key, value) {

                    var ErrorID = '#' + key + 'Error';
                    $(ErrorID).removeClass("d-none");
                    $(ErrorID).text(value);
                });

                // console.log(errors);
                swal({
                    title: "خطأ!",
                    text: "تحتاج إلى ملء بعض الحقول الناقصة في الحسبة",
                    icon: 'error',
                    button: 'موافق',
                });

            }
        });


        $('#calculateSubmit').attr("disabled", false);
        document.querySelector('#calculateSubmit').innerHTML = "احسب";


    });

    function noResultOfClculater() {

        $.ajax({
            type: "Get",
            url: "{{route('calculater.noResultOfCalculater')}}",
            data: {
                requestID: requestID,
            },
            success: function (data) {
            },
            complete: function () {
            },
            error: function (data) {
            }


        });
    }


    function emptyErrorsMsg() {

        $('#product_type_id_caculaterError').addClass("d-none");

        $('#birth_hijri_caculaterError').addClass("d-none");
        $('#salary_caculaterError').addClass("d-none");
        $('#work_caculaterError').addClass("d-none");
        $('#military_rank_caculaterError').addClass("d-none");

        $('#salary_bank_id_caculaterError').addClass("d-none");
        $('#residential_support_caculaterError').addClass("d-none");
        $('#add_support_installment_to_salary_caculaterError').addClass("d-none");
        $('#guarantees_caculaterError').addClass("d-none");

        $('#basic_salary_caculaterError').addClass("d-none");
        $('#job_tenure_caculaterError').addClass("d-none");
        $('#without_transfer_salary_caculaterError').addClass("d-none");
        $('#personal_salary_deduction_caculaterError').addClass("d-none");
        $('#personal_funding_months_caculaterError').addClass("d-none");

        $('#salary_deduction_caculaterError').addClass("d-none");
        $('#funding_months_caculaterError').addClass("d-none");
        $('#property_amount_caculaterError').addClass("d-none");
        $('#property_completed_caculaterError').addClass("d-none");

        $('#residence_type_caculaterError').addClass("d-none");
        $('#has_obligations_caculaterError').addClass("d-none");
        $('#early_repayment_caculaterError').addClass("d-none");
        $('#obligations_installment_caculaterError').addClass("d-none");
        $('#remaining_obligations_months_caculaterError').addClass("d-none");
        $('#first_batch_percentage_caculaterError').addClass("d-none");

        $('#provide_first_batch_caculaterError').addClass("d-none");
        $('#first_batch_profit_caculaterError').addClass("d-none");
        $('#fees_caculaterError').addClass("d-none");
        $('#discount_caculaterError').addClass("d-none");

        $('#have_joint_caculaterError').addClass("d-none");
        $('#joint_birth_hijri_caculaterError').addClass("d-none");
        $('#joint_salary_caculaterError').addClass("d-none");
        $('#joint_work_caculaterError').addClass("d-none");

        $('#joint_military_rank_caculaterError').addClass("d-none");
        $('#joint_salary_bank_id_caculaterError').addClass("d-none");
        $('#joint_residential_support_caculaterError').addClass("d-none");
        $('#joint_add_support_installment_to_salary_caculater_caculaterError').addClass("d-none");

        $('#joint_has_obligations_caculaterError').addClass("d-none");
        $('#joint_early_repayment_caculaterError').addClass("d-none");
        $('#joint_obligations_installment_caculaterError').addClass("d-none");
        $('#joint_remaining_obligations_months_caculaterError').addClass("d-none");
    }

    function updateCurrentValue(dataOfInputs, resultOfClculater, resultArray, bank_id_code, hiring_date, joint_hiring_date, program_name) {

        //JOINT
        if (dataOfInputs['joint_birth_hijri_caculater'])
            $('#hijri-date1').val(dataOfInputs['joint_birth_hijri_caculater']);

        if (dataOfInputs['joint_work_caculater'])
            $('#jointwork').val(dataOfInputs['joint_work_caculater']);

        if (resultOfClculater['joint_age'])
            $('#jointage_years').val(resultOfClculater['joint_age']);


        if (joint_hiring_date)
            $('#joint_hiring_date').val(joint_hiring_date);

        if (dataOfInputs['joint_salary_caculater'])
            $('#jointsalary').val(dataOfInputs['joint_salary_caculater']);

        if (dataOfInputs['joint_military_rank_caculater'])
            $('#jointrank').val(dataOfInputs['joint_military_rank_caculater']);

        if (dataOfInputs['joint_residential_support_caculater']) {

            if (dataOfInputs['joint_residential_support_caculater'] === 1)
                $('#joint_is_support').val('yes');
            else if (dataOfInputs['joint_residential_support_caculater'] === 0)
                $('#joint_is_support').val('no');

        }

        if (dataOfInputs['joint_add_support_installment_to_salary_caculater'])
            $('#joint_add_support_installment_to_salary').val(dataOfInputs['joint_add_support_installment_to_salary_caculater']);

        if (dataOfInputs['joint_salary_bank_id_caculater'])
            $('#jointsalary_source').val(dataOfInputs['joint_salary_bank_id_caculater']);

        if (dataOfInputs['joint_salary_bank_id_caculater'])
            $('#jointsalary_source').val(dataOfInputs['joint_salary_bank_id_caculater']);

        if (dataOfInputs['joint_has_obligations_caculater']) {

            if (dataOfInputs['joint_has_obligations_caculater'] === 1)
                $('#joint_has_obligations').val('yes');
            else if (dataOfInputs['joint_has_obligations_caculater'] === 0)
                $('#joint_has_obligations').val('no');

        }

        if (dataOfInputs['joint_salary_bank_id_caculater'])
            $('#jointsalary_source').val(dataOfInputs['joint_salary_bank_id_caculater']);

        if (dataOfInputs['joint_early_repayment_caculater'])
            $('#jointobligations_value').val(dataOfInputs['joint_early_repayment_caculater']);

        /////////////


        //REAL ESTATE
        if (dataOfInputs['property_amount_caculater'])
            $('#realcost').val(dataOfInputs['property_amount_caculater']);

        if (dataOfInputs['property_completed_caculater']) {

            if (dataOfInputs['property_completed_caculater'] === 1)
                $('#realstatus').val('مكتمل');
            else if (dataOfInputs['property_completed_caculater'] === 0)
                $('#realstatus').val('عظم');

        }

        if (dataOfInputs['residence_type_caculater'])
            $('#residence_type').val(dataOfInputs['residence_type_caculater']);

        if (dataOfInputs['residence_type_caculater'])
            $('#residence_type').val(dataOfInputs['residence_type_caculater']);

        ////////////

        //FUNDING
        if (dataOfInputs['personal_salary_deduction_caculater'] && dataOfInputs['personal_salary_deduction_caculater'] !== '')
            $('#personal_salary_deduction').val(dataOfInputs['personal_salary_deduction_caculater']);
        else if (resultOfClculater?.programs?.flexibleProgram)
            $('#personal_salary_deduction').val(resultOfClculater?.programs?.flexibleProgram?.raw?.personal_salary_deduction?.data);
        else if (resultOfClculater?.programs?.flexibleProgram)
            $('#personal_salary_deduction').val(resultOfClculater?.programs?.flexibleProgram?.raw?.personal_salary_deduction?.data);
        /*
        if (dataOfInputs['personal_funding_months_caculater'])
            $('#personal_salary_deduction').val(dataOfInputs['personal_funding_months_caculater']);
            */

        if (dataOfInputs['product_type_id_caculater'])
            $('#product_code').val(dataOfInputs['product_type_id_caculater']);

        if (dataOfInputs['salary_deduction_caculater'] && dataOfInputs['salary_deduction_caculater'] !== '')
            $('#dedp').val(dataOfInputs['salary_deduction_caculater']);
        else if (resultOfClculater?.programs?.propertyProgram)
            $('#dedp').val(resultOfClculater?.programs?.propertyProgram?.raw?.salary_deduction?.data);
        else if (resultOfClculater?.programs?.flexibleProgram)
            $('#dedp').val(resultOfClculater?.programs?.flexibleProgram?.raw?.salary_deduction?.data);


        // if (resultOfClculater?.programs?.flexibleProgram)
        if (resultOfClculater?.programs?.flexibleProgram)
            $('#fundingpersonalp').val(resultOfClculater?.programs?.flexibleProgram['personal_profit']);

        if (resultOfClculater?.programs?.flexibleProgram)
            $('#fundingrealp').val(resultOfClculater?.programs?.flexibleProgram['profit']);
        else if (resultOfClculater?.programs?.propertyProgram)
            $('#fundingrealp').val(resultOfClculater?.programs?.propertyProgram['profit']);

        if (resultOfClculater?.programs?.flexibleProgram)
            $('#flexiableFun_cost').val(resultOfClculater?.programs?.flexibleProgram?.raw?.net_loan_total?.data);

        if (program_name === 'مرن 2×1') {
            if (resultOfClculater?.programs?.flexibleProgram)
                $('#fundingreal').val(resultOfClculater?.programs?.flexibleProgram?.raw?.flexible_loan_total?.data);
        } else {
            if (resultOfClculater?.programs?.propertyProgram)
                // $('#fundingreal').val(resultOfClculater?.programs?.propertyProgram?.raw?.net_loan_total?.data);
                $('#fundingreal').val(resultOfClculater?.programs?.propertyProgram?.raw?.net_loan_total?.data);
        }

        if (resultOfClculater?.programs?.personalProgram)
            $('#fundingpersonal').val(resultOfClculater?.programs?.personalProgram?.raw?.personal_net_loan_total?.data);

        // if (resultOfClculater?.programs?.personalProgram) {
        //     $('#extendFund_cost').val(resultOfClculater?.programs?.personalProgram?.raw?.net_loan_total?.data);
        // }

        if (resultOfClculater?.programs?.personalProgram)
            $('#personal_monthly_installment').val(resultOfClculater?.programs?.personalProgram?.raw?.personal_installment?.data);

        if (resultOfClculater?.programs?.propertyProgram)
            $('#monthIn').val(resultOfClculater?.programs?.propertyProgram?.raw?.installment?.data);

        if (resultOfClculater?.programs?.flexibleProgram)
            $('#monthly_installment_after_support').val(resultOfClculater?.programs?.flexibleProgram?.raw?.installment_after_support?.data);
        else if (resultOfClculater?.programs?.propertyProgram)
            $('#monthly_installment_after_support').val(resultOfClculater?.programs?.propertyProgram?.raw?.installment_after_support?.data);


        $('#funding_source').val(bank_id_code);


        if (resultOfClculater?.programs?.flexibleProgram)
            $('#fundingdur').val(resultOfClculater?.programs?.flexibleProgram?.raw?.funding_years?.data);
        else if (resultOfClculater?.programs?.propertyProgram)
            $('#fundingdur').val(resultOfClculater?.programs?.propertyProgram?.raw?.funding_years?.data);
        ////////////


        //CUSTOMER
        if (dataOfInputs['salary_caculater'])
            $('#salary').val(dataOfInputs['salary_caculater']);

        if (dataOfInputs['birth_hijri_caculater'])
            $('#hijri-date').val(dataOfInputs['birth_hijri_caculater']);

        if (dataOfInputs['work_caculater'])
            $('#work').val(dataOfInputs['work_caculater']);

        if (dataOfInputs['residential_support_caculater']) {

            if (dataOfInputs['residential_support_caculater'] === 1)
                $('#is_support').val('yes');
            else if (dataOfInputs['residential_support_caculater'] === 0)
                $('#is_support').val('no');

        }

        if (hiring_date)
            $('#hiring_date').val(hiring_date);

        if (dataOfInputs['has_obligations_caculater']) {

            if (dataOfInputs['has_obligations_caculater'] === 1)
                $('#has_obligations').val('yes');
            else if (dataOfInputs['has_obligations_caculater'] === 0)
                $('#has_obligations').val('no');

        }

        if (dataOfInputs['early_repayment_caculater'])
            $('#obligations_value').val(dataOfInputs['early_repayment_caculater']);

        if (dataOfInputs['salary_bank_id_caculater'])
            $('#salary_source').val(dataOfInputs['salary_bank_id_caculater']);

        if (dataOfInputs['military_rank_caculater'])
            $('#rank').val(dataOfInputs['military_rank_caculater']);


        if (resultOfClculater['customer_age'])
            $('#age_years').val(resultOfClculater['customer_age']);


        if (dataOfInputs['without_transfer_salary_caculater'])
            $('#without_transfer_salary').val(dataOfInputs['without_transfer_salary_caculater']);

        if (dataOfInputs['add_support_installment_to_salary_caculater'])
            $('#add_support_installment_to_salary').val(dataOfInputs['add_support_installment_to_salary_caculater']);


        if (dataOfInputs['basic_salary_caculater'])
            $('#basic_salary').val(dataOfInputs['basic_salary_caculater']);

        if (dataOfInputs['guarantees_caculater'])
            $('#guarantees').val(dataOfInputs['guarantees_caculater']);


        ///////////
    }


    $(document).on('click', '.resultBtn', function (e) {
        var myID = $(this).attr("id");
        $(this)
            .addClass("active-on")
            .siblings()
            .removeClass("active-on");
        $(".result-body .row.hdie-show").hide();
        $("#" + myID + "-cont").css("display", "flex");
    });

    $(document).on('click', '.toggleBankResult', function (e) {

        $(this).next().slideToggle();

    });

    $(document).on('click', '.selectBankResult', function (e) {

        $(this).attr("disabled", true);
        $(this).html("<i class='fa fa-spinner fa-spin'></i>{{ MyHelpers::admin_trans(auth()->user()->id,'Loading') }}");
        showLodingDiv();

        var bankCode = $(this).attr('data-code');
        var program_name = $(this).attr('data-program');
        var dataOfInputs = dataObj;
        var resultOfClculater = resultArray;

        for (var i = 0; i < resultOfClculater.length; i++) {

            if (resultOfClculater[i]['bank_code'] === bankCode) {
                resultOfClculater = resultOfClculater[i];
                break;
            }
        }

        // console.log(dataOfInputs)
        $.ajax({
            headers: {
                'X-CSRF-Token': "{{csrf_token()}}"
            },
            type: "POST",
            url: "{{route('calculater.selectCalculaterResult')}}",
            data: {
                dataOfInputs: dataOfInputs,
                requestID: requestID,
                customer_age: resultOfClculater['customer_age'],
                joint_age: resultOfClculater['joint_age'],
                program_name: program_name,
                resultOfClculater: resultOfClculater,
                resultArray: resultArray['joint_programs'],
            },
            success: function (data) {
                try {

                    // console.log(data);
                    updateCurrentValue(dataOfInputs, resultOfClculater, resultArray, data.bank_id, data.hiring_date, data.joint_hiring_date, program_name);
                    if (data?.addNewCal) {
                        swal({
                            title: 'تم!',
                            text: 'جارِ تحديث البيانات',
                            type: 'info',
                            timer: -1
                        })
                        $("#frm-update").submit()
                    }
                } catch (e) {
                    console.log(e)
                    swal({
                        title: 'حدث خطأ',
                        text: e?.message || 'خطأ غير معروف',
                        type: 'error',
                        timer: -1
                    })
                }
            },
            complete: function () {
                hideLodingDiv();
                // $("#frm-update").submit();
            },
            error: function (data) {
                // console.log(data)
            }
        });
        $(this).attr("disabled", false);
        $(this).html("اختر النتيجة");
    });

    function checkWork_caculater(that) {

        if (that.value === 1) {

            document.getElementById("askary_caculater").style.display = "block";

        } else {

            document.getElementById("askary_caculater").style.display = "none";
            document.getElementById("rank_caculater").value = "";
        }
    }

    function checkWork_joint_caculater(that) {

        if (that.value === 1) {

            document.getElementById("joint_askary").style.display = "block";

        } else {

            document.getElementById("joint_askary").style.display = "none";
            document.getElementById("joint_rank_caculater").value = "";
        }
    }

    //----------------------------
</script>

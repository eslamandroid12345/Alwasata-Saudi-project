<script>
    var cal_status = $("#mortgage_calculator_status").val();
    var personal_funding_of_request="{{$purchaseFun->personalFun_cost}}";
    var personal_funding_of_request= parseInt(personal_funding_of_request);

    if (cal_status == 1) {

        $(document).ready(function() {

            checkedInputOfFUndingPersonalDiscount();
            checkRealEstateCost();
            purchaseTaxCalculate();
            firstBatchCalculate1();
            checkPersonalFunding();

            $(".visa_m").on("input", function() { // فيزا
                $(".visa_m").not($(this)).val($(this).val());
            });
            $(".car_m").on("input", function() { // سيارة
                $(".car_m").not($(this)).val($(this).val());
            });
            $(".perlo_m").on("input", function() { // شخصي
                $(".perlo_m").not($(this)).val($(this).val());
            });
            $(".realo_m").on("input", function() { // قرض عقاري
                $(".realo_m").not($(this)).val($(this).val());
            });
            $(".realcost_m").on("input", function() { // قيمة العقار
                $(".realcost_m").not($(this)).val($(this).val());
            });
            $(".preval_m").on("input", function() { // الدفعة الاولي(بيانات الرهن العقاري) / قيمة الدفعة (بيانات الدفعة والالتزام)
                $(".preval_m").not($(this)).val($(this).val());
            });
            $(".other1_m").on("input", function() { // غير ذلك(بيانات الرهن العقاري) / اخري (بيانات التساهيل)
                $(".other1_m").not($(this)).val($(this).val());
            });
            $(".m_disposition_value").on("input", function() { // التصرف العقاري(بيانات الرهن العقاري) / التصرف العقاري (بيانات التساهيل)
                $(".m_disposition_value").not($(this)).val($(this).val());
            });
            $(".m_purchase_tax_value").on("input", function() { // ضريبة الشراء(بيانات الرهن العقاري) / ضريبة الشراء (بيانات التساهيل)
                $(".m_purchase_tax_value").not($(this)).val($(this).val());
            });
            $(".funding_personal_m").on("input", function() { // التمويل الشخصي
                $(".funding_personal_m").not($(this)).val($(this).val());
            });
            mortgageDebtCalculate();
        })
        $(document).ready(function() {

            var real_property_cost = document.getElementById('real_property_cost').value;
            var mortgaged_value = document.getElementById('mortgaged_value').value;
            var Real_estate_disposition_value = document.getElementById('Real_estate_disposition_value').value;
            var purchase_tax_value = document.getElementById('purchase_tax_value').value;
            var first_batch_value = document.getElementById('first_batch_value').value;
            var personal_mortgage = document.getElementById('personal_mortgage').value;
            var car_mortgage = document.getElementById('car_mortgage').value;
            var visa_mortgage = document.getElementById('visa_mortgage').value;
            var beside_value = document.getElementById('beside_value').value;
            var other_fees = document.getElementById('other_fees').value;
            if (real_property_cost === '') {
                $('#real_property_cost').val(0);
            }
            $("#real_property_cost").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (mortgaged_value === '') {
                $('#mortgaged_value').val(0);
            }

            $("#mortgaged_value").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (Real_estate_disposition_value === '') {
                $('#Real_estate_disposition_value').val(0);
            }
            $("#Real_estate_disposition_value").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (purchase_tax_value === '') {
                $('#purchase_tax_value').val(0);
            }
            $("#purchase_tax_value").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (first_batch_value === '') {
                $('#first_batch_value').val(0);
            }
            $("#first_batch_value").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (personal_mortgage === '') {
                $('#personal_mortgage').val(0);
            }
            $("#personal_mortgage").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (car_mortgage === '') {
                $('#car_mortgage').val(0);
            }
            $("#car_mortgage").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (visa_mortgage === '') {
                $('#visa_mortgage').val(0);
            }
            $("#visa_mortgage").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (beside_value === '') {
                $('#beside_value').val(0);
            }
            $("#beside_value").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if (other_fees === '') {
                $('#other_fees').val(0);
            }
            $("#other_fees").on("blur", function() {
                if ($(this).val().trim().length == 0) {
                    $(this).val("0");
                }
            });
            if ((mortgaged_value == 0) && (Real_estate_disposition_value == 0) && (purchase_tax_value == 0) && (first_batch_value == 0) && (personal_mortgage == 0) && (car_mortgage == 0) && (visa_mortgage == 0) && (visa_mortgage == 0) && (beside_value == 0)) {
                $('#mortgage_debt').val(0);
                //$('#total_taxes_mortgage').val(0);
                $('#net_to_customer').val(0);
            }
        });

        function mortgageDebtCalculate() {
            var real_property_cost = parseInt($("#real_property_cost").val());
            var mortgaged_value = parseInt($("#mortgaged_value").val());
            var Real_estate_disposition_value = parseInt($("#Real_estate_disposition_value").val());
            var purchase_tax_value = parseInt($("#purchase_tax_value").val());
            var first_batch_value = parseInt($("#first_batch_value").val());
            var personal_mortgage = parseInt($("#personal_mortgage").val());
            var car_mortgage = parseInt($("#car_mortgage").val());
            var visa_mortgage = parseInt($("#visa_mortgage").val());
            var beside_value = parseInt($("#beside_value").val());
            var other_fees = parseInt($("#other_fees").val());
            // start (get the percentage value for every commitment)
            var e = document.getElementById("mortgaged_percentage");
            var mortgaged_percentage = e.value;
            var e1 = document.getElementById("Real_estate_disposition_percentage");
            var Real_estate_disposition_percentage = e1.value;
            var e2 = document.getElementById("purchase_tax_percentage");
            var purchase_tax_percentage = e2.value;
            var e3 = document.getElementById("first_batch_percentage");
            var first_batch_percentage = e3.value;
            var e4 = document.getElementById("perlo_percentage");
            var perlo_percentage = e4.value;
            var e5 = document.getElementById("car_percentage");
            var car_percentage = e5.value;
            var e6 = document.getElementById("visa_percentage");
            var visa_percentage = e6.value;
            var e7 = document.getElementById("beside_percentage");
            var beside_percentage = e7.value;
            // End (get the value for every commitment)
            /////////////////////////////////////////
            // get total commitment without tax (0.15)
            var total_commitment_without_percentage_tax = ((mortgaged_value) + ((mortgaged_value) * (mortgaged_percentage / 100))) +
                ((Real_estate_disposition_value) + ((Real_estate_disposition_value) * (Real_estate_disposition_percentage / 100))) +
                ((purchase_tax_value) + ((purchase_tax_value) * (purchase_tax_percentage / 100))) +
                ((first_batch_value) + ((first_batch_value) * (first_batch_percentage / 100))) +
                ((personal_mortgage) + ((personal_mortgage) * (perlo_percentage / 100))) +
                ((car_mortgage) + ((car_mortgage) * (car_percentage / 100))) +
                ((visa_mortgage) + ((visa_mortgage) * (visa_percentage / 100))) +
                ((beside_value) + ((beside_value) * (beside_percentage / 100)));

            var total_commitment = mortgaged_value +
                Real_estate_disposition_value +
                purchase_tax_value +
                first_batch_value +
                personal_mortgage +
                car_mortgage +
                visa_mortgage +
                beside_value;
            // +other_fees;

            $("#mortgage_debt").val(Math.ceil(total_commitment));
            $("#mortgage_debt_with_tax").val(Math.ceil(total_commitment_without_percentage_tax));
            document.getElementById("net_to_customer").value = Math.ceil(real_property_cost - (total_commitment_without_percentage_tax + other_fees));
            // realEstateDispositionCalculate()
        }
        $(document).on('click', '#save_mortgage', function(e) {
            var reqID = document.getElementById("request_id").value;
            // console.log(reqID);
            var real_property_cost = document.getElementById("real_property_cost").value;
            var personal_funding = document.getElementById("funding_personal").value;
            var mortgaged_value = document.getElementById("mortgaged_value").value;
            var mortgaged_percentage = document.getElementById("mortgaged_percentage").value;
            var Real_estate_disposition_value = document.getElementById("Real_estate_disposition_value").value;
            var Real_estate_disposition_percentage = document.getElementById("Real_estate_disposition_percentage").value;
            var purchase_tax_value = document.getElementById("purchase_tax_value").value;
            var purchase_tax_percentage = document.getElementById("purchase_tax_percentage").value;
            var first_batch_value = document.getElementById("first_batch_value").value;
            var first_batch_from_realValue = document.getElementById("first_batch_from_realValue").value;
            var first_batch_percentage = document.getElementById("first_batch_percentage").value;
            var personal_mortgage = document.getElementById("personal_mortgage").value;
            var perlo_percentage = document.getElementById("perlo_percentage").value;
            var car_mortgage = document.getElementById("car_mortgage").value;
            var car_percentage = document.getElementById("car_percentage").value;
            var visa_mortgage = document.getElementById("visa_mortgage").value;
            var visa_percentage = document.getElementById("visa_percentage").value;
            var beside_value = document.getElementById("beside_value").value;
            var beside_percentage = document.getElementById("beside_percentage").value;
            var other_fees = document.getElementById("other_fees").value;
            var mortgage_debt = document.getElementById("mortgage_debt").value;
            var mortgage_debt_with_tax = document.getElementById("mortgage_debt_with_tax").value;
            //var total_taxes_mortgage = document.getElementById("total_taxes_mortgage").value;
            var net_to_customer = document.getElementById("net_to_customer").value;
            $.post("{{ route('all.sendMortgageData')}}", {
                real_property_cost: real_property_cost,
                personal_funding: personal_funding,
                reqID: reqID,
                mortgaged_value: mortgaged_value,
                mortgaged_percentage: mortgaged_percentage,
                Real_estate_disposition_value: Real_estate_disposition_value,
                Real_estate_disposition_percentage: Real_estate_disposition_percentage,
                purchase_tax_value: purchase_tax_value,
                purchase_tax_percentage: purchase_tax_percentage,
                first_batch_value: first_batch_value,
                first_batch_from_realValue: first_batch_from_realValue,
                first_batch_percentage: first_batch_percentage,
                personal_mortgage: personal_mortgage,
                perlo_percentage: perlo_percentage,
                car_mortgage: car_mortgage,
                car_percentage: car_percentage,
                visa_mortgage: visa_mortgage,
                visa_percentage: visa_percentage,
                beside_value: beside_value,
                beside_percentage: beside_percentage,
                other_fees: other_fees,
                mortgage_debt: mortgage_debt,
                mortgage_debt_with_tax:mortgage_debt_with_tax,
                //total_taxes_mortgage: total_taxes_mortgage,
                net_to_customer: net_to_customer,
            }, function(data) {
                if (data.status == 1) {
                    location.reload(true);
                    swal({
                        title: "تم!",
                        text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Update Succesffuly') }}",
                        icon: 'success',
                    });
                } else {
                    swal({
                        title: "خطأ!",
                        text: "{{ MyHelpers::admin_trans(auth()->user()->id,'Nothing Change') }}",
                        icon: 'error',
                    });
                }
            });
        });


        function updatePersonalFunding() {

            var reqID = document.getElementById("reqID").value;
            var personalFunding= $("#funding_personal").val();

            $.ajax({
            url: "{{ route('all.updatePersonalFundingData')}}",
            type: "POST",
            data: {
                "_token": "{{csrf_token()}}",
                reqID: reqID,
                personalFunding: personalFunding,
            },
            success: function(data) {

                personal_funding_of_request= data.personalFunding;

                //console.log(data);
            },

        });

        }

        function realEstateDispositionCalculate() {
            // var mortgaged_value = $('#mortgaged_value').val();
            var mortgaged_value = parseInt($('#real_property_cost').val());

            if (mortgaged_value > 0) {
                $('#Real_estate_disposition_value').val((mortgaged_value * 0.9 ) * 0.05);
            } else {
                $('#mortgaged_value').val(0);
                $('#Real_estate_disposition_value').val(0);
            }

            mortgageDebtCalculate();
        }

        function purchaseTaxCalculate() {
            var realPropertyCost = $('#real_property_cost').val();

            if (realPropertyCost > 1000000) {
                var purchaseTax = ((realPropertyCost - 1000000) * 0.05);
                $('#purchase_tax_value').val(purchaseTax);
            } else {
                $('#purchase_tax_value').val(0);
            }
            mortgageDebtCalculate();
        }

        function firstBatchCalculate1() {


            var prop_cost = $("#real_property_cost").val();
            var first_batch_value = "{{ $purchaseTsa->prepaymentVal }}";
            var discount_presentage = $("#first_batch_from_realValue").val();

            if (prop_cost != null && prop_cost != 0 && prop_cost != '' && first_batch_value == '')
                $("#first_batch_value").val(prop_cost * (discount_presentage / 100));
            else if (first_batch_value != 0)
                $("#first_batch_value").val(first_batch_value);
            else
                $("#first_batch_value").val(0);


            mortgageDebtCalculate();

        }

        function firstBatchCalculate2() {

            var prop_cost = $("#real_property_cost").val();
            var discount_presentage = $("#first_batch_from_realValue").val();

            if (prop_cost != null && prop_cost != 0 && prop_cost != '')
                $("#first_batch_value").val(prop_cost * (discount_presentage / 100));
            else {
                $("#first_batch_value").val(0);
                swal({
                    title: "خطأ!",
                    text: "قم بملء قيمة العقار (عرض السعر) حتى تحصل على نتيجة صحيحة",
                    icon: 'error',
                });
            }

            mortgageDebtCalculate();

        }

        function checkPersonalFunding() {

            var personalFunding = $("#funding_personal").val();

            if (personalFunding == null || personalFunding == '')
                $("#funding_personal").val(0);

        }

        function checkRealEstateCost() {

            var realCost = $("#real_property_cost").val();

            if (realCost == null || realCost == '')
                $("#real_property_cost").val(0);

        }

        function personalFundingDiscount() {

            if ($('#presonal_funding_discount').is(":checked")) {
                var first_batch_value = $("#first_batch_value").val();
                var personalFunding = $("#funding_personal").val();

                if (personalFunding != 0) {
                    if (parseInt(personalFunding) > parseInt(first_batch_value)) {
                        $("#funding_personal").val(personalFunding - first_batch_value);
                        $("#first_batch_value").val(0);
                    } else {
                        $("#funding_personal").val(0);
                        $("#first_batch_value").val(first_batch_value - personalFunding);
                    }
                } else
                    swal({
                        title: "خطأ!",
                        text: "قيمة التمويل الشخصي تساوي صفر",
                        icon: 'error',
                    });

                mortgageDebtCalculate();
            } else {
                var funding_personal = personal_funding_of_request;
                if (funding_personal == null || funding_personal == 0 || funding_personal == '')
                    $("#funding_personal").val(0);
                else
                    $("#funding_personal").val(parseInt(funding_personal));

                firstBatchCalculate2();
            }

        }

        function checkedInputOfFUndingPersonalDiscount() {
            var first_batch_value = "{{ $purchaseTsa->prepaymentVal }}";

            if (first_batch_value == '')
                $('#presonal_funding_discount').prop("checked", false);
            else
                $('#presonal_funding_discount').prop("checked", true);

        }


        function firstBatchFromRealValue() {

            var prop_cost = $("#real_property_cost").val();
            var discount_presentage = $("#first_batch_from_realValue").val();

            if (prop_cost != null && prop_cost != 0 && prop_cost != '')
                $("#first_batch_value").val(prop_cost * (discount_presentage / 100));
            else {
                $("#first_batch_value").val(0);
                swal({
                    title: "خطأ!",
                    text: "قم بملء قيمة العقار (عرض السعر) حتى تحصل على نتيجة صحيحة",
                    icon: 'error',
                });
            }

            mortgageDebtCalculate();

        }

    }
</script>

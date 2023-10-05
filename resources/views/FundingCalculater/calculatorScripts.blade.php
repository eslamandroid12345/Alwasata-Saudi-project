<script>
    function calculatorResultList(data, dataOfInput, flexibleSettings, personalSettings, propertySettings, extendedSettings) {
        $provide_first_batch_caculater = false;
        $first_batch_mode_caculater = false;
        if (dataOfInput['provide_first_batch_caculater'] !== null)
            if (dataOfInput['provide_first_batch_caculater'] === '1')
                $provide_first_batch_caculater = true;
        if (dataOfInput['first_batch_mode_caculater'] !== null)
            if (dataOfInput['first_batch_mode_caculater'] === '1')
                $first_batch_mode_caculater = true;
        var senario_length = senarios_of_display_result.length;
        var all = false;
        var all_withoutNames = false;
        var max_value_withCustomerBank = false;
        var max_value_with_name = false;
        var max_value_without_name = true; // because it's default result :)
        var user_id = {{auth()->user()->id}};
        var role_check = {{auth()->user()->role}};
        for (var j = 0; j < senario_length; j++) {
            getAgentCalclaterResultSettings(senarios_of_display_result[j]['id']);
            var agent_array = senarios_agent_of_display_result;
            //console.log(agent_array);
            if (j === 4) {
                if (agent_array.includes(user_id) || role_check === 7) {
                    all = true;
                    break;
                }
            }
            if (j === 3) {
                if (agent_array.includes(user_id)) {
                    all_withoutNames = true;
                    break;
                }
            }
            if (j === 2) {
                if (agent_array.includes(user_id)) {
                    max_value_withCustomerBank = true;
                    break;
                }
            }
            if (j === 1) {
                if (agent_array.includes(user_id)) {
                    max_value_with_name = true;
                    break;
                }
            }
            if (j === 0) {
                if (agent_array.includes(user_id))
                    max_value_without_name = true;
            }
        }
        var for_naming_only = 0;
        //FOR Bank LIST
        for (var i = 0; i < data.length; i++) {
            var li = '';
            if (all) {
                if (i === 0) li = $('<li class="resultBtn active-on" id="result' + (i + 1) + '"> </li>');
                else
                    li = $('<li class="resultBtn" id="result' + (i + 1) + '"></li>');
                if (data[i]['bank_img'] === null)
                    li.html('<i class="fas fa-university"></i> ' + data[i]['bank_name']);
                else {
                    var assetBaseUrl = "{{ asset('') }}";
                    var imgurl = assetBaseUrl + data[i]['bank_img'];
                    li.html('<img class="img-fluid" src="' + imgurl + '" alt="">');
                }
            } else if (max_value_with_name && maxFundingArray.includes(data[i]['bank_code'])) {
                if (i === 0) li = $('<li class="resultBtn active-on" id="result' + (i + 1) + '"> </li>');
                else
                    li = $('<li class="resultBtn" id="result' + (i + 1) + '"></li>');
                if (data[i]['bank_img'] === null)
                    li.html('<i class="fas fa-university"></i> ' + data[i]['bank_name']);
                else {
                    var assetBaseUrl = "{{ asset('') }}";
                    var imgurl = assetBaseUrl + data[i]['bank_img'];
                    li.html('<img class="img-fluid" src="' + imgurl + '" alt="">');
                }
            } else if (max_value_withCustomerBank && maxFundingArray.includes(data[i]['bank_code'])) {
                if (i === 0) li = $('<li class="resultBtn active-on" id="result' + (i + 1) + '"> </li>');
                else
                    li = $('<li class="resultBtn" id="result' + (i + 1) + '"></li>');
                if (data[i]['bank_img'] === null)
                    li.html('<i class="fas fa-university"></i> ' + data[i]['bank_name']);
                else {
                    var assetBaseUrl = "{{ asset('') }}";
                    var imgurl = assetBaseUrl + data[i]['bank_img'];
                    li.html('<img class="img-fluid" src="' + imgurl + '" alt="">');
                }
            } else if (all_withoutNames) {
                if (i === 0) li = $('<li class="resultBtn active-on" id="result' + (i + 1) + '"> </li>');
                else
                    li = $('<li class="resultBtn" id="result' + (i + 1) + '"></li>');
                li.html('<i class="fas fa-university"></i> البنك رقم (' + for_naming_only + ')');
                for_naming_only++;
            } else if (max_value_without_name && maxFundingArray.includes(data[i]['bank_code'])) {
                if (i === 0) li = $('<li class="resultBtn active-on" id="result' + (i + 1) + '"> </li>');
                else
                    li = $('<li class="resultBtn" id="result' + (i + 1) + '"></li>');
                li.html('<i class="fas fa-university"></i> البنك رقم (' + for_naming_only + ')');
                for_naming_only++;
            }
            $("#calResultList").append(li);
        }

        for_naming_only = 0;

        //FOR DETAILS
        for (var i = 0; i < data.length; i++) {

            //console.log(data);
            var nameOfBank = '';
            //For dispaly settings
            if (all) {
                nameOfBank = $(' <h5 class="py-3 singleBankName" style="color:#0f5b94;text-align:center;font-weight: bold"> <i class="fas fa-university"></i>' + data[i]['bank_name'] + '</h5>');
            } else if (all_withoutNames) {
                nameOfBank = $(' <h5 class="py-3 singleBankName" style="color:#0f5b94;text-align:center;font-weight: bold"> <i class="fas fa-university"></i>البنك رقم (' + for_naming_only + ')</h5>');
            } else if (max_value_with_name) {
                nameOfBank = $(' <h5 class="py-3 singleBankName" style="color:#0f5b94;text-align:center;font-weight: bold"> <i class="fas fa-university"></i>' + data[i]['bank_name'] + '</h5>');
                if (!maxFundingArray.includes(data[i]['bank_code']))
                    continue;
            } else if (max_value_withCustomerBank) {
                nameOfBank = $(' <h5 class="py-3 singleBankName" style="color:#0f5b94;text-align:center;font-weight: bold"> <i class="fas fa-university"></i>' + data[i]['bank_name'] + '</h5>');
                if (!maxFundingArray.includes(data[i]['bank_code']))
                    continue;
            } else if (max_value_without_name) {
                nameOfBank = $(' <h5 class="py-3 singleBankName" style="color:#0f5b94;text-align:center;font-weight: bold"> <i class="fas fa-university"></i>البنك رقم (' + for_naming_only + ')</h5>');
                if (!maxFundingArray.includes(data[i]['bank_code']))
                    continue;
            }
            //
            for_naming_only++;

            var main = $(' <div class="row hdie-show" id="result' + (i + 1) + '-cont"> </div>');

            var div1 = $(' <div class="col-12"></div>');

            div1.append(nameOfBank);

            if (data[i]?.programs?.personalProgram) {

                var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                var content = $(' <div class="col-12 mb-md-0"></div>');
                var typePersonal = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> شخصي </p> <i class="fas fa-chevron-down"></i> </div>');
                var div4 = $(' <div class="contREsult"></div>');
                var div5 = $(' <div class="row"></div>');


                var dataPersonal = data[i]['programs']['personalProgram']['raw'];
                //PERSONAL DETAILS
                var div7 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_net_loan_total']['text'] + ': <span> ' + dataPersonal['personal_net_loan_total']['value'] + ' </span></p> </div> </div> </div>');
                var div8 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_installment']['text'] + ': <span> ' + dataPersonal['personal_installment']['value'] + '</span> </p> </div> </div> </div>');
                var div9 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" > <div class = "singleTypeResult" ><p> ' + dataPersonal['personal_funding_years']['text'] + ': <span> ' + dataPersonal['personal_funding_years']['value'] + '</span></p></div> </div> </div>');
                var div10 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_profit']['text'] + ': <span> ' + dataPersonal['personal_profit']['value'] + ' </span></p></div> </div> </div>');
                var div12 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['bank_code']['text'] + ': <span> ' + dataPersonal['bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div13 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['bank_name']['text'] + ': <span> ' + dataPersonal['bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div14 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['salary_bank_code']['text'] + ': <span> ' + dataPersonal['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div15 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['salary_bank_name']['text'] + ': <span> ' + dataPersonal['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div16 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['is_salary_transfer']['text'] + ': <span> ' + dataPersonal['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                var div17 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_position_code']['text'] + ': <span> ' + dataPersonal['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                var div18 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_position_name']['text'] + ': <span> ' + dataPersonal['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                var div19 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['age']['text'] + ': <span> ' + dataPersonal['age']['value'] + ' </span></p></div> </div> </div>');
                var div20 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['age_by_months']['text'] + ': <span> ' + dataPersonal['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                var div21 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['retirement_age']['text'] + ': <span> ' + dataPersonal['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                var div22 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['funding_age_limit']['text'] + ': <span> ' + dataPersonal['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                var div23 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['extra_funding_years']['text'] + ': <span> ' + dataPersonal['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div24 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['salary']['text'] + ': <span> ' + dataPersonal['salary']['value'] + ' </span></p></div> </div> </div>');
                var div25 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['basic_salary']['text'] + ': <span> ' + dataPersonal['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                var div26 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['early_repayment']['text'] + ': <span> ' + dataPersonal['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                var div27 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['quest_check']['text'] + ': <span> ' + dataPersonal['quest_check']['value'] + ' </span></p></div> </div> </div>');
                var div28 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['bear_tax']['text'] + ': <span> ' + dataPersonal['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                var div29 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['product_type_id']['text'] + ': <span> ' + dataPersonal['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                var div30 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['housing_allowance']['text'] + ': <span> ' + dataPersonal['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div31 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['transfer_allowance']['text'] + ': <span> ' + dataPersonal['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div32 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['other_allowance']['text'] + ': <span> ' + dataPersonal['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div33 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['retirement_income']['text'] + ': <span> ' + dataPersonal['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                var div34 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_tenure_months']['text'] + ': <span> ' + dataPersonal['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                var div35 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_tenure_years']['text'] + ': <span> ' + dataPersonal['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                var div36 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['first_batch_mode']['text'] + ': <span> ' + dataPersonal['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                var div37 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['credit_installment']['text'] + ': <span> ' + dataPersonal['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                var div38 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['obligations_installment']['text'] + ': <span> ' + dataPersonal['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                var div39 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['remaining_obligations_months']['text'] + ': <span> ' + dataPersonal['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                var div40 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['remaining_retirement_months']['text'] + ': <span> ' + dataPersonal['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                var div41 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['remaining_retirement_years']['text'] + ': <span> ' + dataPersonal['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                var div43 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_funding_months']['text'] + ': <span> ' + dataPersonal['personal_funding_months']['value'] + ' </span></p></div> </div> </div>');
                var div44 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_salary_deduction']['text'] + ': <span> ' + dataPersonal['personal_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div45 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_profit_margin']['text'] + ': <span> ' + dataPersonal['personal_profit_margin']['value'] + ' </span></p></div> </div> </div>');
                var div46 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_loan_total']['text'] + ': <span> ' + dataPersonal['personal_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div47 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['loan_total_profits']['text'] + ': <span> ' + dataPersonal['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');
                var div48 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['calculator_program']['text'] + ': <span> ' + dataPersonal['calculator_program']['value'] + ' </span></p></div> </div> </div>');
                var div49 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['extra_personal_funding_years']['text'] + ': <span> ' + dataPersonal['extra_personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                // console.log(personalSettings)
                if (personalSettings[36]['value_en'] === "personal_loan_total" && personalSettings[36]['option_value'] === 1) {
                    div5.append(div46);
                }
                if (personalSettings[37]['value_en'] === "personal_net_loan_total" && personalSettings[37]['option_value'] === 1) {
                    div5.append(div7);
                }
                if (personalSettings[0]['value_en'] === "bank_code" && personalSettings[0]['option_value'] === 1) {
                    div5.append(div12);
                }
                if (personalSettings[1]['value_en'] === "bank_name" && personalSettings[1]['option_value'] === 1) {
                    div5.append(div13);
                }
                if (personalSettings[2]['value_en'] === "salary_bank_code" && personalSettings[2]['option_value'] === 1) {
                    div5.append(div14);
                }
                if (personalSettings[3]['value_en'] === "salary_bank_name" && personalSettings[3]['option_value'] === 1) {
                    div5.append(div15);
                }
                if (personalSettings[4]['value_en'] === "is_salary_transfer" && personalSettings[4]['option_value'] === 1) {
                    div5.append(div16);
                }
                if (personalSettings[5]['value_en'] === "job_position_code" && personalSettings[5]['option_value'] === 1) {
                    div5.append(div17);
                }
                if (personalSettings[6]['value_en'] === "job_position_name" && personalSettings[6]['option_value'] === 1) {
                    div5.append(div18);
                }
                if (personalSettings[7]['value_en'] === "age" && personalSettings[7]['option_value'] === 1) {
                    div5.append(div19);
                }
                if (personalSettings[8]['value_en'] === "age_by_months" && personalSettings[8]['option_value'] === 1) {
                    div5.append(div20);
                }
                if (personalSettings[9]['value_en'] === "retirement_age" && personalSettings[9]['option_value'] === 1) {
                    div5.append(div21);
                }
                if (personalSettings[10]['value_en'] === "funding_age_limit" && personalSettings[10]['option_value'] === 1) {
                    div5.append(div22);
                }
                if (personalSettings[11]['value_en'] === "extra_funding_years" && personalSettings[11]['option_value'] === 1) {
                    div5.append(div23);
                }
                if (personalSettings[12]['value_en'] === "salary" && personalSettings[12]['option_value'] === 1) {
                    div5.append(div24);
                }
                if (personalSettings[13]['value_en'] === "basic_salary" && personalSettings[13]['option_value'] === 1) {
                    div5.append(div25);
                }
                if (personalSettings[14]['value_en'] === "early_repayment" && personalSettings[14]['option_value'] === 1) {
                    div5.append(div26);
                }
                if (personalSettings[15]['value_en'] === "quest_check" && personalSettings[15]['option_value'] === 1) {
                    div5.append(div27);
                }
                if (personalSettings[16]['value_en'] === "bear_tax" && personalSettings[16]['option_value'] === 1) {
                    div5.append(div28);
                }
                if (personalSettings[17]['value_en'] === "product_type_id" && personalSettings[17]['option_value'] === 1) {
                    div5.append(div29);
                }
                if (personalSettings[18]['value_en'] === "product_type_id" && personalSettings[18]['option_value'] === 1) {
                    div5.append(div30);
                }
                if (personalSettings[19]['value_en'] === "transfer_allowance" && personalSettings[19]['option_value'] === 1) {
                    div5.append(div31);
                }
                if (personalSettings[20]['value_en'] === "other_allowance" && personalSettings[20]['option_value'] === 1) {
                    div5.append(div32);
                }
                if (personalSettings[21]['value_en'] === "retirement_income" && personalSettings[21]['option_value'] === 1) {
                    div5.append(div33);
                }
                if (personalSettings[22]['value_en'] === "job_tenure_months" && personalSettings[22]['option_value'] === 1) {
                    div5.append(div34);
                }
                if (personalSettings[23]['value_en'] === "job_tenure_years" && personalSettings[23]['option_value'] === 1) {
                    div5.append(div35);
                }
                if (personalSettings[24]['value_en'] === "first_batch_mode" && personalSettings[24]['option_value'] === 1) {
                    div5.append(div36);
                }
                if (personalSettings[25]['value_en'] === "credit_installment" && personalSettings[25]['option_value'] === 1) {
                    div5.append(div37);
                }
                if (personalSettings[26]['value_en'] === "obligations_installment" && personalSettings[26]['option_value'] === 1) {
                    div5.append(div38);
                }
                if (personalSettings[27]['value_en'] === "remaining_obligations_months" && personalSettings[27]['option_value'] === 1) {
                    div5.append(div39);
                }
                if (personalSettings[28]['value_en'] === "remaining_retirement_months" && personalSettings[28]['option_value'] === 1) {
                    div5.append(div40);
                }
                if (personalSettings[29]['value_en'] === "remaining_retirement_years" && personalSettings[29]['option_value'] === 1) {
                    div5.append(div41);
                }
                if (personalSettings[30]['value_en'] === "personal_funding_years" && personalSettings[30]['option_value'] === 1) {
                    div5.append(div9);
                }
                if (personalSettings[31]['value_en'] === "personal_funding_months" && personalSettings[31]['option_value'] === 1) {
                    div5.append(div43);
                }
                if (personalSettings[32]['value_en'] === "personal_installment" && personalSettings[32]['option_value'] === 1) {
                    div5.append(div8);
                }
                if (personalSettings[33]['value_en'] === "personal_salary_deduction" && personalSettings[33]['option_value'] === 1) {
                    div5.append(div44);
                }
                if (personalSettings[34]['value_en'] === "personal_profit" && personalSettings[34]['option_value'] === 1) {
                    div5.append(div10);
                }
                if (personalSettings[35]['value_en'] === "personal_profit_margin" && personalSettings[35]['option_value'] === 1) {
                    div5.append(div45);
                }
                if (personalSettings[36]['value_en'] === "personal_loan_total" && personalSettings[36]['option_value'] === 1) {
                    div5.append(div46);
                }
                if (personalSettings[38]['value_en'] === "loan_total_profits" && personalSettings[38]['option_value'] === 1) {
                    div5.append(div47);
                }
                if (personalSettings[39]['value_en'] === "calculator_program" && personalSettings[39]['option_value'] === 1) {
                    div5.append(div48);
                }
                if (personalSettings[40]['value_en'] === "extra_personal_funding_years" && personalSettings[40]['option_value'] === 1) {
                    div5.append(div49);
                }

                div4.html(div5);
                content.append(typePersonal);
                content.append(div4);
                body.html(content);

                div1.append(body);


            }

            if (data[i]?.programs?.propertyProgram) {

                var program_name = 'عقاري فقط';
                var addButton2 = $(' <br> <button class="btn btn-dark px-5 selectBankResult "  type="button" data-program="' + program_name + '" data-bank="' + data[i]['bank_name'] + '" data-code="' + data[i]['bank_code'] + '">اختر النتيجة</button> <br> <br>');

                var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                var content = $(' <div class="col-12 mb-md-0"></div>');
                var typeProperty = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> عقاري </p> <i class="fas fa-chevron-down"></i> </div>');
                var div4 = $(' <div class="contREsult"></div>');
                var div5 = $(' <div class="row"></div>');


                var dataProperty = data[i]['programs']['propertyProgram']['raw'];
                // console.log(dataProperty)
                var div10 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['bank_code']['text'] + ': <span> ' + dataProperty['bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div11 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['bank_name']['text'] + ': <span> ' + dataProperty['bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div12 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary_bank_code']['text'] + ': <span> ' + dataProperty['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div13 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary_bank_name']['text'] + ': <span> ' + dataProperty['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div14 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['is_salary_transfer']['text'] + ': <span> ' + dataProperty['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                var div15 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_position_code']['text'] + ': <span> ' + dataProperty['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                var div16 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_position_name']['text'] + ': <span> ' + dataProperty['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                var div17 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['age']['text'] + ': <span> ' + dataProperty['age']['value'] + ' </span></p></div> </div> </div>');
                var div18 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['age_by_months']['text'] + ': <span> ' + dataProperty['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                var div19 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['retirement_age']['text'] + ': <span> ' + dataProperty['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                var div20 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['funding_age_limit']['text'] + ': <span> ' + dataProperty['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                var div21 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['extra_funding_years']['text'] + ': <span> ' + dataProperty['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div22 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary']['text'] + ': <span> ' + dataProperty['salary']['value'] + ' </span></p></div> </div> </div>');
                var div23 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['basic_salary']['text'] + ': <span> ' + dataProperty['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                var div24 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['early_repayment']['text'] + ': <span> ' + dataProperty['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                var div25 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest_check']['text'] + ': <span> ' + dataProperty['quest_check']['value'] + ' </span></p></div> </div> </div>');
                var div26 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['bear_tax']['text'] + ': <span> ' + dataProperty['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                var div27 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['product_type_id']['text'] + ': <span> ' + dataProperty['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                var div28 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['housing_allowance']['text'] + ': <span> ' + dataProperty['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div29 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['transfer_allowance']['text'] + ': <span> ' + dataProperty['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div30 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['other_allowance']['text'] + ': <span> ' + dataProperty['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div31 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['retirement_income']['text'] + ': <span> ' + dataProperty['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                var div32 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_tenure_months']['text'] + ': <span> ' + dataProperty['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                var div33 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_tenure_years']['text'] + ': <span> ' + dataProperty['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                var div34 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_mode']['text'] + ': <span> ' + dataProperty['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                var div35 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['credit_installment']['text'] + ': <span> ' + dataProperty['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                var div36 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['obligations_installment']['text'] + ': <span> ' + dataProperty['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                var div37 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['remaining_obligations_months']['text'] + ': <span> ' + dataProperty['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                var div38 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['remaining_retirement_months']['text'] + ': <span> ' + dataProperty['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                var div39 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['remaining_retirement_years']['text'] + ': <span> ' + dataProperty['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                var div40 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['residential_support']['text'] + ': <span> ' + dataProperty['residential_support']['value'] + ' </span></p></div> </div> </div>');
                var div41 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['add_support_installment_to_salary']['text'] + ': <span> ' + dataProperty['add_support_installment_to_salary']['value'] + ' </span></p></div> </div> </div>');
                var div42 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['add_support_installment_to_installment']['text'] + ': <span> ' + dataProperty['add_support_installment_to_installment']['value'] + ' </span></p></div> </div> </div>');
                var div43 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['guarantees']['text'] + ': <span> ' + dataProperty['guarantees']['value'] + ' </span></p></div> </div> </div>');
                var div44 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['provide_first_batch']['text'] + ': <span> ' + dataProperty['provide_first_batch']['value'] + ' </span></p></div> </div> </div>');
                var div45 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['secured']['text'] + ': <span> ' + dataProperty['secured']['value'] + ' </span></p></div> </div> </div>');
                var div46 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['property_amount']['text'] + ': <span> ' + dataProperty['property_amount']['value'] + ' </span></p></div> </div> </div>');
                var div47 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['is_property_completed']['text'] + ': <span> ' + dataProperty['is_property_completed']['value'] + ' </span></p></div> </div> </div>');
                var div48 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['residence_type']['text'] + ': <span> ' + dataProperty['residence_type']['value'] + ' </span></p></div> </div> </div>');
                var div49 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_percentage']['text'] + ': <span> ' + dataProperty['first_batch_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div50 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch']['text'] + ': <span> ' + dataProperty['first_batch']['value'] + ' </span></p></div> </div> </div>');
                var div51 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_profit']['text'] + ': <span> ' + dataProperty['first_batch_profit']['value'] + ' </span></p></div> </div> </div>');
                var div52 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_profit_amount']['text'] + ': <span> ' + dataProperty['first_batch_profit_amount']['value'] + ' </span></p></div> </div> </div>');
                var div53 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest']['text'] + ': <span> ' + dataProperty['quest']['value'] + ' </span></p></div> </div> </div>');
                var div54 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest_amount']['text'] + ': <span> ' + dataProperty['quest_amount']['value'] + ' </span></p></div> </div> </div>');
                var div55 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest_amount_with_vat']['text'] + ': <span> ' + dataProperty['quest_amount_with_vat']['value'] + ' </span></p></div> </div> </div>');
                var div56 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['fees']['text'] + ': <span> ' + dataProperty['fees']['value'] + ' </span></p></div> </div> </div>');
                var div57 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['discount']['text'] + ': <span> ' + dataProperty['discount']['value'] + ' </span></p></div> </div> </div>');
                var div58 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['vat']['text'] + ': <span> ' + dataProperty['vat']['value'] + ' </span></p></div> </div> </div>');
                var div59 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['check_total']['text'] + ': <span> ' + dataProperty['check_total']['value'] + ' </span></p></div> </div> </div>');
                var div60 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['net_check_total']['text'] + ': <span> ' + dataProperty['net_check_total']['value'] + ' </span></p></div> </div> </div>');
                var div61 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['net_bond_amount']['text'] + ': <span> ' + dataProperty['net_bond_amount']['value'] + ' </span></p></div> </div> </div>');
                var div62 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['min_net_loan_total']['text'] + ': <span> ' + dataProperty['min_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div63 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['purchase_tax']['text'] + ': <span> ' + dataProperty['purchase_tax']['value'] + ' </span></p></div> </div> </div>');
                var div64 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['purchase_tax_percentage']['text'] + ': <span> ' + dataProperty['purchase_tax_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div65 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['funding_months']['text'] + ': <span> ' + dataProperty['funding_months']['value'] + ' </span></p></div> </div> </div>');
                var div66 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['funding_years']['text'] + ': <span> ' + dataProperty['funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div67 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['installment']['text'] + ': <span> ' + dataProperty['installment']['value'] + ' </span></p></div> </div> </div>');
                var div68 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['support_installment']['text'] + ': <span> ' + dataProperty['support_installment']['value'] + ' </span></p></div> </div> </div>');
                var div69 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['installment_after_support']['text'] + ': <span> ' + dataProperty['installment_after_support']['value'] + ' </span></p></div> </div> </div>');
                var div70 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary_deduction']['text'] + ': <span> ' + dataProperty['salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div71 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['profit']['text'] + ': <span> ' + dataProperty['profit']['value'] + ' </span></p></div> </div> </div>');
                var div72 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['profit_margin']['text'] + ': <span> ' + dataProperty['profit_margin']['value'] + ' </span></p></div> </div> </div>');
                var div73 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['loan_total']['text'] + ': <span> ' + dataProperty['loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div74 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['net_loan_total']['text'] + ': <span> ' + dataProperty['net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div75 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['loan_total_profits']['text'] + ': <span> ' + dataProperty['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');
                var div76 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['calculator_program']['text'] + ': <span> ' + dataProperty['calculator_program']['value'] + ' </span></p></div> </div> </div>');

                if (propertySettings[64]['value_en'] === "net_loan_total" && propertySettings[64]['option_value'] === 1) {
                    div5.append(div74);
                }
                if (propertySettings[57]['value_en'] === "installment" && propertySettings[57]['option_value'] === 1) {
                    div5.append(div67);
                }
                if (propertySettings[59]['value_en'] === "installment_after_support" && propertySettings[59]['option_value'] === 1) {
                    div5.append(div69);
                }
                if (propertySettings[58]['value_en'] === "support_installment" && propertySettings[58]['option_value'] === 1) {
                    div5.append(div68);
                }
                if (propertySettings[56]['value_en'] === "funding_years" && propertySettings[56]['option_value'] === 1) {
                    div5.append(div66);
                }
                if (propertySettings[61]['value_en'] === "profit" && propertySettings[61]['option_value'] === 1) {
                    div5.append(div71);
                }
                if (propertySettings[43]['value_en'] === "quest" && propertySettings[43]['option_value'] === 1) {
                    div5.append(div53);
                }
                if (propertySettings[44]['value_en'] === "quest_amount" && propertySettings[44]['option_value'] === 1) {
                    div5.append(div54);
                }
                if (propertySettings[45]['value_en'] === "quest_amount_with_vat" && propertySettings[45]['option_value'] === 1) {
                    div5.append(div55);
                }
                if (propertySettings[49]['value_en'] === "check_total" && propertySettings[49]['option_value'] === 1) {
                    div5.append(div59);
                }
                if (propertySettings[40]['value_en'] === "first_batch" && propertySettings[40]['option_value'] === 1) {
                    div5.append(div50);
                }
                if (propertySettings[51]['value_en'] === "net_bond_amount" && propertySettings[51]['option_value'] === 1) {
                    div5.append(div61);
                }
                if (propertySettings[50]['value_en'] === "net_check_total" && propertySettings[50]['option_value'] === 1) {
                    div5.append(div60);
                }
                if (propertySettings[53]['value_en'] === "purchase_tax" && propertySettings[53]['option_value'] === 1) {
                    div5.append(div63);
                }
                if (propertySettings[52]['value_en'] === "min_net_loan_total" && propertySettings[52]['option_value'] === 1) {
                    div5.append(div62);
                }
                if (propertySettings[65]['value_en'] === "loan_total_profits" && propertySettings[65]['option_value'] === 1) {
                    div5.append(div75);
                }


                if (propertySettings[0]['value_en'] === "bank_code" && propertySettings[0]['option_value'] === 1) {
                    div5.append(div10);
                }
                if (propertySettings[1]['value_en'] === "bank_name" && propertySettings[1]['option_value'] === 1) {
                    div5.append(div11);
                }
                if (propertySettings[2]['value_en'] === "salary_bank_code" && propertySettings[2]['option_value'] === 1) {
                    div5.append(div12);
                }
                if (propertySettings[3]['value_en'] === "salary_bank_name" && propertySettings[3]['option_value'] === 1) {
                    div5.append(div13);
                }
                if (propertySettings[4]['value_en'] === "is_salary_transfer" && propertySettings[4]['option_value'] === 1) {
                    div5.append(div14);
                }
                if (propertySettings[5]['value_en'] === "job_position_code" && propertySettings[5]['option_value'] === 1) {
                    div5.append(div15);
                }
                if (propertySettings[6]['value_en'] === "job_position_name" && propertySettings[6]['option_value'] === 1) {
                    div5.append(div16);
                }
                if (propertySettings[7]['value_en'] === "age" && propertySettings[7]['option_value'] === 1) {
                    div5.append(div17);
                }
                if (propertySettings[8]['value_en'] === "age_by_months" && propertySettings[8]['option_value'] === 1) {
                    div5.append(div18);
                }
                if (propertySettings[9]['value_en'] === "retirement_age" && propertySettings[9]['option_value'] === 1) {
                    div5.append(div19);
                }
                if (propertySettings[10]['value_en'] === "funding_age_limit" && propertySettings[10]['option_value'] === 1) {
                    div5.append(div20);
                }
                if (propertySettings[11]['value_en'] === "extra_funding_years" && propertySettings[11]['option_value'] === 1) {
                    div5.append(div21);
                }
                if (propertySettings[12]['value_en'] === "salary" && propertySettings[12]['option_value'] === 1) {
                    div5.append(div22);
                }
                if (propertySettings[13]['value_en'] === "basic_salary" && propertySettings[13]['option_value'] === 1) {
                    div5.append(div23);
                }
                if (propertySettings[14]['value_en'] === "early_repayment" && propertySettings[14]['option_value'] === 1) {
                    div5.append(div24);
                }
                if (propertySettings[15]['value_en'] === "quest_check" && propertySettings[15]['option_value'] === 1) {
                    div5.append(div25);
                }
                if (propertySettings[16]['value_en'] === "bear_tax" && propertySettings[16]['option_value'] === 1) {
                    div5.append(div26);
                }
                if (propertySettings[17]['value_en'] === "product_type_id" && propertySettings[17]['option_value'] === 1) {
                    div5.append(div27);
                }
                if (propertySettings[18]['value_en'] === "housing_allowance" && propertySettings[18]['option_value'] === 1) {
                    div5.append(div28);
                }
                if (propertySettings[19]['value_en'] === "transfer_allowance" && propertySettings[19]['option_value'] === 1) {
                    div5.append(div29);
                }
                if (propertySettings[20]['value_en'] === "other_allowance" && propertySettings[20]['option_value'] === 1) {
                    div5.append(div30);
                }
                if (propertySettings[21]['value_en'] === "retirement_income" && propertySettings[21]['option_value'] === 1) {
                    div5.append(div31);
                }
                if (propertySettings[22]['value_en'] === "job_tenure_months" && propertySettings[22]['option_value'] === 1) {
                    div5.append(div32);
                }
                if (propertySettings[23]['value_en'] === "job_tenure_years" && propertySettings[23]['option_value'] === 1) {
                    div5.append(div33);
                }
                if (propertySettings[24]['value_en'] === "first_batch_mode" && propertySettings[24]['option_value'] === 1) {
                    div5.append(div34);
                }
                if (propertySettings[25]['value_en'] === "credit_installment" && propertySettings[25]['option_value'] === 1) {
                    div5.append(div35);
                }
                if (propertySettings[26]['value_en'] === "obligations_installment" && propertySettings[26]['option_value'] === 1) {
                    div5.append(div36);
                }
                if (propertySettings[27]['value_en'] === "remaining_obligations_months" && propertySettings[27]['option_value'] === 1) {
                    div5.append(div37);
                }
                if (propertySettings[28]['value_en'] === "remaining_retirement_months" && propertySettings[28]['option_value'] === 1) {
                    div5.append(div38);
                }
                if (propertySettings[29]['value_en'] === "remaining_retirement_years" && propertySettings[29]['option_value'] === 1) {
                    div5.append(div39);
                }
                if (propertySettings[30]['value_en'] === "residential_support" && propertySettings[30]['option_value'] === 1) {
                    div5.append(div40);
                }
                if (propertySettings[31]['value_en'] === "add_support_installment_to_salary" && propertySettings[31]['option_value'] === 1) {
                    div5.append(div41);
                }
                if (propertySettings[32]['value_en'] === "add_support_installment_to_installment" && propertySettings[32]['option_value'] === 1) {
                    div5.append(div42);
                }
                if (propertySettings[33]['value_en'] === "guarantees" && propertySettings[33]['option_value'] === 1) {
                    div5.append(div43);
                }
                if (propertySettings[34]['value_en'] === "provide_first_batch" && propertySettings[34]['option_value'] === 1) {
                    div5.append(div44);
                }
                if (propertySettings[35]['value_en'] === "secured" && propertySettings[35]['option_value'] === 1) {
                    div5.append(div45);
                }
                if (propertySettings[36]['value_en'] === "property_amount" && propertySettings[36]['option_value'] === 1) {
                    div5.append(div46);
                }
                if (propertySettings[37]['value_en'] === "is_property_completed" && propertySettings[37]['option_value'] === 1) {
                    div5.append(div47);
                }
                if (propertySettings[38]['value_en'] === "residence_type" && propertySettings[38]['option_value'] === 1) {
                    div5.append(div48);
                }
                if (propertySettings[39]['value_en'] === "first_batch_percentage" && propertySettings[39]['option_value'] === 1) {
                    div5.append(div49);
                }
                if (propertySettings[41]['value_en'] === "first_batch_profit" && propertySettings[41]['option_value'] === 1) {
                    div5.append(div51);
                }
                if (propertySettings[42]['value_en'] === "first_batch_profit_amount" && propertySettings[42]['option_value'] === 1) {
                    div5.append(div52);
                }
                if (propertySettings[46]['value_en'] === "fees" && propertySettings[46]['option_value'] === 1) {
                    div5.append(div56);
                }
                if (propertySettings[47]['value_en'] === "discount" && propertySettings[47]['option_value'] === 1) {
                    div5.append(div57);
                }
                if (propertySettings[48]['value_en'] === "vat" && propertySettings[48]['option_value'] === 1) {
                    div5.append(div58);
                }
                if (propertySettings[54]['value_en'] === "purchase_tax_percentage" && propertySettings[54]['option_value'] === 1) {
                    div5.append(div64);
                }
                if (propertySettings[55]['value_en'] === "funding_months" && propertySettings[55]['option_value'] === 1) {
                    div5.append(div65);
                }
                if (propertySettings[60]['value_en'] === "salary_deduction" && propertySettings[60]['option_value'] === 1) {
                    div5.append(div70);
                }
                if (propertySettings[62]['value_en'] === "profit_margin" && propertySettings[62]['option_value'] === 1) {
                    div5.append(div72);
                }
                if (propertySettings[63]['value_en'] === "loan_total" && propertySettings[63]['option_value'] === 1) {
                    div5.append(div73);
                }
                if (propertySettings[66]['value_en'] === "calculator_program" && propertySettings[66]['option_value'] === 1) {
                    div5.append(div76);
                }

                div4.html(div5);
                div4.append(addButton2);
                content.append(typeProperty);
                content.append(div4);
                body.html(content);

                div1.append(body);


            }

            if (data[i]?.programs?.flexibleProgram) {

                var program_name = 'مرن 2×1';
                var addButton = $(' <br> <button class="btn px-5 selectBankResult "  type="button"  data-program="' + program_name + '" data-bank="' + data[i]['bank_name'] + '" data-code="' + data[i]['bank_code'] + '">اختر النتيجة</button> <br> <br>');
                var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                var content = $(' <div class="col-12 mb-md-0"></div>');
                var typeFlixable = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> مرن 2 * 1 </p> <i class="fas fa-chevron-down"></i ></div>');
                var div4 = $(' <div class="contREsult"></div>');
                var div5 = $(' <div class="row"></div>');


                var dataFlixable = data[i]['programs']['flexibleProgram']['raw'];
                var div7 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['net_loan_total']['text'] + ': <span> ' + dataFlixable['net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div8 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" > <p> ' + dataFlixable['personal_net_loan_total']['text'] + ': <span>' + dataFlixable['personal_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div9 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['flexible_loan_total']['text'] + ': <span> ' + dataFlixable['flexible_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div10 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['installment']['text'] + ': <span> ' + dataFlixable['installment']['value'] + ' </span></p></div> </div> </div>');
                var div11 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['installment_after_support']['text'] + ': <span> ' + dataFlixable['installment_after_support']['value'] + ' </span></p></div> </div></div>');
                var div12 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['support_installment']['text'] + ': <span> ' + dataFlixable['support_installment']['value'] + ' </span></p></div> </div> </div>');
                var div13 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['funding_years']['text'] + ': <span>' + dataFlixable['funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div14 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['check_total']['text'] + ': <span> ' + dataFlixable['check_total']['value'] + ' </span></p></div> </div> </div>');
                var div15 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['purchase_tax']['text'] + ': <span> ' + dataFlixable['purchase_tax']['value'] + ' </span></p></div> </div> </div>');
                var div16 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['flexible_installment']['text'] + ': <span> ' + dataFlixable['flexible_installment']['value'] + ' </span></p></div> </div> </div>');
                var div17 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['property_flexible_installment']['text'] + ': <span> ' + dataFlixable['property_flexible_installment']['value'] + ' </span></p></div> </div> </div>');
                var div18 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['bank_code']['text'] + ': <span> ' + dataFlixable['bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div19 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['bank_name']['text'] + ': <span> ' + dataFlixable['bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div20 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary_bank_code']['text'] + ': <span> ' + dataFlixable['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div21 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary_bank_name']['text'] + ': <span> ' + dataFlixable['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div22 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['is_salary_transfer']['text'] + ': <span> ' + dataFlixable['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                var div23 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_position_code']['text'] + ': <span> ' + dataFlixable['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                var div24 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_position_name']['text'] + ': <span> ' + dataFlixable['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                var div25 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['age']['text'] + ': <span> ' + dataFlixable['age']['value'] + ' </span></p></div> </div> </div>');
                var div26 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['age_by_months']['text'] + ': <span> ' + dataFlixable['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                var div27 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['retirement_age']['text'] + ': <span> ' + dataFlixable['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                var div28 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['funding_age_limit']['text'] + ': <span> ' + dataFlixable['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                var div29 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['extra_funding_years']['text'] + ': <span> ' + dataFlixable['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div30 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary']['text'] + ': <span> ' + dataFlixable['salary']['value'] + ' </span></p></div> </div> </div>');
                var div31 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['basic_salary_with_insurance_percentage']['text'] + ': <span> ' + dataFlixable['basic_salary_with_insurance_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div32 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['basic_salary']['text'] + ': <span> ' + dataFlixable['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                var div33 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['early_repayment']['text'] + ': <span> ' + dataFlixable['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                var div34 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest_check']['text'] + ': <span> ' + dataFlixable['quest_check']['value'] + ' </span></p></div> </div> </div>');
                var div35 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['bear_tax']['text'] + ': <span> ' + dataFlixable['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                var div36 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['product_type_id']['text'] + ': <span> ' + dataFlixable['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                var div37 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['housing_allowance']['text'] + ': <span> ' + dataFlixable['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div38 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['transfer_allowance']['text'] + ': <span> ' + dataFlixable['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div39 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['other_allowance']['text'] + ': <span> ' + dataFlixable['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div40 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['retirement_income']['text'] + ': <span> ' + dataFlixable['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                var div41 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_tenure_months']['text'] + ': <span> ' + dataFlixable['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                var div42 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_tenure_years']['text'] + ': <span> ' + dataFlixable['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                var div43 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_mode']['text'] + ': <span> ' + dataFlixable['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                var div44 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['credit_installment']['text'] + ': <span> ' + dataFlixable['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                var div45 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['obligations_installment']['text'] + ': <span> ' + dataFlixable['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                var div46 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['remaining_obligations_months']['text'] + ': <span> ' + dataFlixable['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                var div47 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['remaining_retirement_months']['text'] + ': <span> ' + dataFlixable['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                var div48 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['remaining_retirement_years']['text'] + ': <span> ' + dataFlixable['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                var div49 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_funding_years']['text'] + ': <span> ' + dataFlixable['personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div50 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_funding_months']['text'] + ': <span> ' + dataFlixable['personal_funding_months']['value'] + ' </span></p></div> </div> </div>');
                var div51 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_installment']['text'] + ': <span> ' + dataFlixable['personal_installment']['value'] + ' </span></p></div> </div> </div>');
                var div52 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_salary_deduction']['text'] + ': <span> ' + dataFlixable['personal_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div53 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_profit']['text'] + ': <span> ' + dataFlixable['personal_profit']['value'] + ' </span></p></div> </div> </div>');
                var div54 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_profit_margin']['text'] + ': <span> ' + dataFlixable['personal_profit_margin']['value'] + ' </span></p></div> </div> </div>');
                var div55 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_loan_total']['text'] + ': <span> ' + dataFlixable['personal_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div56 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['loan_total_profits']['text'] + ': <span> ' + dataFlixable['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');
                var div57 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['calculator_program']['text'] + ': <span> ' + dataFlixable['calculator_program']['value'] + ' </span></p></div> </div> </div>');
                var div58 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['extra_personal_funding_years']['text'] + ': <span> ' + dataFlixable['extra_personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div59 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['residential_support']['text'] + ': <span> ' + dataFlixable['residential_support']['value'] + ' </span></p></div> </div> </div>');
                var div60 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['add_support_installment_to_salary']['text'] + ': <span> ' + dataFlixable['add_support_installment_to_salary']['value'] + ' </span></p></div> </div> </div>');
                var div61 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['add_support_installment_to_installment']['text'] + ': <span> ' + dataFlixable['add_support_installment_to_installment']['value'] + ' </span></p></div> </div> </div>');
                var div62 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['guarantees']['text'] + ': <span> ' + dataFlixable['guarantees']['value'] + ' </span></p></div> </div> </div>');
                var div63 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['provide_first_batch']['text'] + ': <span> ' + dataFlixable['provide_first_batch']['value'] + ' </span></p></div> </div> </div>');
                var div64 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['secured']['text'] + ': <span> ' + dataFlixable['secured']['value'] + ' </span></p></div> </div> </div>');
                var div65 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['property_amount']['text'] + ': <span> ' + dataFlixable['property_amount']['value'] + ' </span></p></div> </div> </div>');
                var div66 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['is_property_completed']['text'] + ': <span> ' + dataFlixable['is_property_completed']['value'] + ' </span></p></div> </div> </div>');
                var div67 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['residence_type']['text'] + ': <span> ' + dataFlixable['residence_type']['value'] + ' </span></p></div> </div> </div>');
                var div68 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_percentage']['text'] + ': <span> ' + dataFlixable['first_batch_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div69 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch']['text'] + ': <span> ' + dataFlixable['first_batch']['value'] + ' </span></p></div> </div> </div>');
                var div70 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_profit']['text'] + ': <span> ' + dataFlixable['first_batch_profit']['value'] + ' </span></p></div> </div> </div>');
                var div71 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_profit_amount']['text'] + ': <span> ' + dataFlixable['first_batch_profit_amount']['value'] + ' </span></p></div> </div> </div>');
                var div72 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest']['text'] + ': <span> ' + dataFlixable['quest']['value'] + ' </span></p></div> </div> </div>');
                var div73 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest_amount']['text'] + ': <span> ' + dataFlixable['quest_amount']['value'] + ' </span></p></div> </div> </div>');
                var div74 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest_amount_with_vat']['text'] + ': <span> ' + dataFlixable['quest_amount_with_vat']['value'] + ' </span></p></div> </div> </div>');
                var div75 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['fees']['text'] + ': <span> ' + dataFlixable['fees']['value'] + ' </span></p></div> </div> </div>');
                var div76 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['discount']['text'] + ': <span> ' + dataFlixable['discount']['value'] + ' </span></p></div> </div> </div>');
                var div77 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['vat']['text'] + ': <span> ' + dataFlixable['vat']['value'] + ' </span></p></div> </div> </div>');
                var div78 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['net_check_total']['text'] + ': <span> ' + dataFlixable['net_check_total']['value'] + ' </span></p></div> </div> </div>');
                var div79 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['net_bond_amount']['text'] + ': <span> ' + dataFlixable['net_bond_amount']['value'] + ' </span></p></div> </div> </div>');
                var div80 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['min_net_loan_total']['text'] + ': <span> ' + dataFlixable['min_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div81 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['purchase_tax_percentage']['text'] + ': <span> ' + dataFlixable['purchase_tax_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div82 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['funding_months']['text'] + ': <span> ' + dataFlixable['funding_months']['value'] + ' </span></p></div> </div> </div>');
                var div83 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary_deduction']['text'] + ': <span> ' + dataFlixable['salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div84 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['profit']['text'] + ': <span> ' + dataFlixable['profit']['value'] + ' </span></p></div> </div> </div>');
                var div85 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['profit_margin']['text'] + ': <span> ' + dataFlixable['profit_margin']['value'] + ' </span></p></div> </div> </div>');
                var div86 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['loan_total']['text'] + ': <span> ' + dataFlixable['loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div87 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['flexible_salary_deduction']['text'] + ': <span> ' + dataFlixable['flexible_salary_deduction']['value'] + ' </span></p></div> </div> </div>');

                if (flexibleSettings[76]['value_en'] === "net_loan_total" && flexibleSettings[76]['option_value'] === 1) {
                    div5.append(div7);
                }
                if (flexibleSettings[38]['value_en'] === "personal_net_loan_total" && flexibleSettings[38]['option_value'] === 1) {
                    div5.append(div8);
                }
                if (flexibleSettings[79]['value_en'] === "flexible_loan_total" && flexibleSettings[79]['option_value'] === 1) {
                    div5.append(div9);
                }

                if (flexibleSettings[77]['value_en'] === "flexible_installment" && flexibleSettings[77]['option_value'] === 1) {
                    div5.append(div16);
                }
                if (flexibleSettings[69]['value_en'] === "installment" && flexibleSettings[69]['option_value'] === 1) {
                    div5.append(div10);
                }
                if (flexibleSettings[71]['value_en'] === "installment_after_support" && flexibleSettings[71]['option_value'] === 1) {
                    div5.append(div11);
                }
                if (flexibleSettings[70]['value_en'] === "support_installment" && flexibleSettings[70]['option_value'] === 1) {
                    div5.append(div12);
                }
                if (flexibleSettings[68]['value_en'] === "funding_years" && flexibleSettings[68]['option_value'] === 1) {
                    div5.append(div13);
                }
                if (flexibleSettings[7]['value_en'] === "age" && flexibleSettings[7]['option_value'] === 1) {
                    div5.append(div25);
                }
                if (flexibleSettings[73]['value_en'] === "profit" && flexibleSettings[73]['option_value'] === 1) {
                    div5.append(div84);
                }
                if (flexibleSettings[55]['value_en'] === "quest" && flexibleSettings[55]['option_value'] === 1) {
                    div5.append(div72);
                }
                if (flexibleSettings[56]['value_en'] === "quest_amount" && flexibleSettings[56]['option_value'] === 1) {
                    div5.append(div73);
                }
                if (flexibleSettings[57]['value_en'] === "quest_amount_with_vat" && flexibleSettings[57]['option_value'] === 1) {
                    div5.append(div74);
                }
                if (flexibleSettings[61]['value_en'] === "check_total" && flexibleSettings[61]['option_value'] === 1) {
                    div5.append(div14);
                }
                if (flexibleSettings[52]['value_en'] === "first_batch" && flexibleSettings[52]['option_value'] === 1) {
                    div5.append(div69);
                }
                if (flexibleSettings[63]['value_en'] === "net_bond_amount" && flexibleSettings[63]['option_value'] === 1) {
                    div5.append(div79);
                }
                if (flexibleSettings[62]['value_en'] === "net_check_total" && flexibleSettings[62]['option_value'] === 1) {
                    div5.append(div78);
                }
                if (flexibleSettings[65]['value_en'] === "purchase_tax" && flexibleSettings[65]['option_value'] === 1) {
                    div5.append(div15);
                }
                if (flexibleSettings[64]['value_en'] === "min_net_loan_total" && flexibleSettings[64]['option_value'] === 1) {
                    div5.append(div80);
                }
                if (flexibleSettings[39]['value_en'] === "loan_total_profits" && flexibleSettings[39]['option_value'] === 1) {
                    div5.append(div56);
                }


                if (flexibleSettings[78]['value_en'] === "property_flexible_installment" && flexibleSettings[78]['option_value'] === 1) {
                    div5.append(div17);
                }
                if (flexibleSettings[0]['value_en'] === "bank_code" && flexibleSettings[0]['option_value'] === 1) {
                    div5.append(div18);
                }
                if (flexibleSettings[1]['value_en'] === "bank_name" && flexibleSettings[1]['option_value'] === 1) {
                    div5.append(div19);
                }
                if (flexibleSettings[2]['value_en'] === "salary_bank_code" && flexibleSettings[2]['option_value'] === 1) {
                    div5.append(div20);
                }
                if (flexibleSettings[3]['value_en'] === "salary_bank_name" && flexibleSettings[3]['option_value'] === 1) {
                    div5.append(div21);
                }
                if (flexibleSettings[4]['value_en'] === "is_salary_transfer" && flexibleSettings[4]['option_value'] === 1) {
                    div5.append(div22);
                }
                if (flexibleSettings[5]['value_en'] === "job_position_code" && flexibleSettings[5]['option_value'] === 1) {
                    div5.append(div23);
                }
                if (flexibleSettings[6]['value_en'] === "job_position_name" && flexibleSettings[6]['option_value'] === 1) {
                    div5.append(div24);
                }
                if (flexibleSettings[8]['value_en'] === "age_by_months" && flexibleSettings[8]['option_value'] === 1) {
                    div5.append(div26);
                }
                if (flexibleSettings[9]['value_en'] === "retirement_age" && flexibleSettings[9]['option_value'] === 1) {
                    div5.append(div27);
                }
                if (flexibleSettings[10]['value_en'] === "funding_age_limit" && flexibleSettings[10]['option_value'] === 1) {
                    div5.append(div28);
                }
                if (flexibleSettings[11]['value_en'] === "extra_funding_years" && flexibleSettings[11]['option_value'] === 1) {
                    div5.append(div29);
                }
                if (flexibleSettings[12]['value_en'] === "salary" && flexibleSettings[12]['option_value'] === 1) {
                    div5.append(div30);
                }
                if (flexibleSettings[13]['value_en'] === "basic_salary_with_insurance_percentage" && flexibleSettings[13]['option_value'] === 1) {
                    div5.append(div31);
                }
                if (flexibleSettings[14]['value_en'] === "basic_salary" && flexibleSettings[14]['option_value'] === 1) {
                    div5.append(div32);
                }
                if (flexibleSettings[15]['value_en'] === "early_repayment" && flexibleSettings[15]['option_value'] === 1) {
                    div5.append(div33);
                }
                if (flexibleSettings[16]['value_en'] === "quest_check" && flexibleSettings[16]['option_value'] === 1) {
                    div5.append(div34);
                }
                if (flexibleSettings[17]['value_en'] === "bear_tax" && flexibleSettings[17]['option_value'] === 1) {
                    div5.append(div35);
                }
                if (flexibleSettings[18]['value_en'] === "product_type_id" && flexibleSettings[18]['option_value'] === 1) {
                    div5.append(div36);
                }
                if (flexibleSettings[19]['value_en'] === "housing_allowance" && flexibleSettings[19]['option_value'] === 1) {
                    div5.append(div37);
                }
                if (flexibleSettings[20]['value_en'] === "transfer_allowance" && flexibleSettings[20]['option_value'] === 1) {
                    div5.append(div38);
                }
                if (flexibleSettings[21]['value_en'] === "other_allowance" && flexibleSettings[21]['option_value'] === 1) {
                    div5.append(div39);
                }
                if (flexibleSettings[22]['value_en'] === "retirement_income" && flexibleSettings[22]['option_value'] === 1) {
                    div5.append(div40);
                }
                if (flexibleSettings[23]['value_en'] === "job_tenure_months" && flexibleSettings[23]['option_value'] === 1) {
                    div5.append(div41);
                }
                if (flexibleSettings[24]['value_en'] === "job_tenure_years" && flexibleSettings[24]['option_value'] === 1) {
                    div5.append(div42);
                }
                if (flexibleSettings[25]['value_en'] === "first_batch_mode" && flexibleSettings[25]['option_value'] === 1) {
                    div5.append(div43);
                }
                if (flexibleSettings[26]['value_en'] === "credit_installment" && flexibleSettings[26]['option_value'] === 1) {
                    div5.append(div44);
                }
                if (flexibleSettings[27]['value_en'] === "obligations_installment" && flexibleSettings[27]['option_value'] === 1) {
                    div5.append(div45);
                }
                if (flexibleSettings[28]['value_en'] === "remaining_obligations_months" && flexibleSettings[28]['option_value'] === 1) {
                    div5.append(div46);
                }
                if (flexibleSettings[29]['value_en'] === "remaining_retirement_months" && flexibleSettings[29]['option_value'] === 1) {
                    div5.append(div47);
                }
                if (flexibleSettings[30]['value_en'] === "remaining_retirement_years" && flexibleSettings[30]['option_value'] === 1) {
                    div5.append(div48);
                }
                if (flexibleSettings[31]['value_en'] === "personal_funding_years" && flexibleSettings[31]['option_value'] === 1) {
                    div5.append(div49);
                }
                if (flexibleSettings[32]['value_en'] === "personal_funding_months" && flexibleSettings[32]['option_value'] === 1) {
                    div5.append(div50);
                }
                if (flexibleSettings[33]['value_en'] === "personal_installment" && flexibleSettings[33]['option_value'] === 1) {
                    div5.append(div51);
                }
                if (flexibleSettings[34]['value_en'] === "personal_salary_deduction" && flexibleSettings[34]['option_value'] === 1) {
                    div5.append(div52);
                }
                if (flexibleSettings[35]['value_en'] === "personal_profit" && flexibleSettings[35]['option_value'] === 1) {
                    div5.append(div53);
                }
                if (flexibleSettings[36]['value_en'] === "personal_profit_margin" && flexibleSettings[36]['option_value'] === 1) {
                    div5.append(div54);
                }
                if (flexibleSettings[37]['value_en'] === "personal_loan_total" && flexibleSettings[37]['option_value'] === 1) {
                    div5.append(div55);
                }
                if (flexibleSettings[40]['value_en'] === "calculator_program" && flexibleSettings[40]['option_value'] === 1) {
                    div5.append(div57);
                }
                if (flexibleSettings[41]['value_en'] === "extra_personal_funding_years" && flexibleSettings[41]['option_value'] === 1) {
                    div5.append(div58);
                }
                if (flexibleSettings[42]['value_en'] === "residential_support" && flexibleSettings[42]['option_value'] === 1) {
                    div5.append(div59);
                }
                if (flexibleSettings[43]['value_en'] === "add_support_installment_to_salary" && flexibleSettings[43]['option_value'] === 1) {
                    div5.append(div60);
                }
                if (flexibleSettings[44]['value_en'] === "add_support_installment_to_installment" && flexibleSettings[44]['option_value'] === 1) {
                    div5.append(div61);
                }
                if (flexibleSettings[45]['value_en'] === "guarantees" && flexibleSettings[45]['option_value'] === 1) {
                    div5.append(div62);
                }
                if (flexibleSettings[46]['value_en'] === "provide_first_batch" && flexibleSettings[46]['option_value'] === 1) {
                    div5.append(div63);
                }
                if (flexibleSettings[47]['value_en'] === "secured" && flexibleSettings[47]['option_value'] === 1) {
                    div5.append(div64);
                }
                if (flexibleSettings[48]['value_en'] === "property_amount" && flexibleSettings[48]['option_value'] === 1) {
                    div5.append(div65);
                }
                if (flexibleSettings[49]['value_en'] === "is_property_completed" && flexibleSettings[49]['option_value'] === 1) {
                    div5.append(div66);
                }
                if (flexibleSettings[50]['value_en'] === "residence_type" && flexibleSettings[50]['option_value'] === 1) {
                    div5.append(div67);
                }
                if (flexibleSettings[51]['value_en'] === "first_batch_percentage" && flexibleSettings[51]['option_value'] === 1) {
                    div5.append(div68);
                }
                if (flexibleSettings[53]['value_en'] === "first_batch_profit" && flexibleSettings[53]['option_value'] === 1) {
                    div5.append(div70);
                }
                if (flexibleSettings[54]['value_en'] === "first_batch_profit_amount" && flexibleSettings[54]['option_value'] === 1) {
                    div5.append(div71);
                }
                if (flexibleSettings[58]['value_en'] === "fees" && flexibleSettings[58]['option_value'] === 1) {
                    div5.append(div75);
                }
                if (flexibleSettings[59]['value_en'] === "discount" && flexibleSettings[59]['option_value'] === 1) {
                    div5.append(div76);
                }
                if (flexibleSettings[60]['value_en'] === "vat" && flexibleSettings[60]['option_value'] === 1) {
                    div5.append(div77);
                }
                if (flexibleSettings[66]['value_en'] === "purchase_tax_percentage" && flexibleSettings[66]['option_value'] === 1) {
                    div5.append(div81);
                }
                if (flexibleSettings[67]['value_en'] === "funding_months" && flexibleSettings[67]['option_value'] === 1) {
                    div5.append(div82);
                }
                if (flexibleSettings[72]['value_en'] === "salary_deduction" && flexibleSettings[72]['option_value'] === 1) {
                    div5.append(div83);
                }
                if (flexibleSettings[74]['value_en'] === "profit_margin" && flexibleSettings[74]['option_value'] === 1) {
                    div5.append(div85);
                }
                if (flexibleSettings[75]['value_en'] === "loan_total" && flexibleSettings[75]['option_value'] === 1) {
                    div5.append(div86);
                }
                if (flexibleSettings[80]['value_en'] === "flexible_salary_deduction" && flexibleSettings[80]['option_value'] === 1) {
                    div5.append(div87);
                }

                div4.append(div5);
                div4.append(addButton);
                content.append(typeFlixable);
                content.append(div4);
                body.append(content);
                div1.append(body);


            }

            if (data[i]?.programs?.extendedProgram) {


                var program_name = 'ممتد';
                var addButton3 = $(' <br> <button class="btn btn-dark px-5 selectBankResult "  type="button" data-program="' + program_name + '" data-bank="' + data[i]['bank_name'] + '" data-code="' + data[i]['bank_code'] + '">اختر النتيجة</button> <br> <br>');

                var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                var content = $(' <div class="col-12 mb-md-0"></div>');
                var typeFlixable = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> ممتد </p> <i class="fas fa-chevron-down"></i ></div>');
                var div4 = $(' <div class="contREsult"></div>');
                var div5 = $(' <div class="row"></div>');

                var dataExtended = data[i]['programs']['extendedProgram']['raw'];
                //Flixable DETAILS
                var div10 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['bank_code']['text'] + ': <span> ' + dataExtended['bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div11 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['bank_name']['text'] + ': <span> ' + dataExtended['bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div12 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['salary_bank_code']['text'] + ': <span> ' + dataExtended['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                var div13 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['salary_bank_name']['text'] + ': <span> ' + dataExtended['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                var div14 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['is_salary_transfer']['text'] + ': <span> ' + dataExtended['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                var div15 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['job_position_code']['text'] + ': <span> ' + dataExtended['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                var div16 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['job_position_name']['text'] + ': <span> ' + dataExtended['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                var div17 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['age']['text'] + ': <span> ' + dataExtended['age']['value'] + ' </span></p></div> </div> </div>');
                var div18 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['age_by_months']['text'] + ': <span> ' + dataExtended['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                var div19 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['retirement_age']['text'] + ': <span> ' + dataExtended['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                var div20 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_age_limit']['text'] + ': <span> ' + dataExtended['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                var div21 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['extra_funding_years']['text'] + ': <span> ' + dataExtended['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div22 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['salary']['text'] + ': <span> ' + dataExtended['salary']['value'] + ' </span></p></div> </div> </div>');
                var div23 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['basic_salary']['text'] + ': <span> ' + dataExtended['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                var div24 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['early_repayment']['text'] + ': <span> ' + dataExtended['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                var div25 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['quest_check']['text'] + ': <span> ' + dataExtended['quest_check']['value'] + ' </span></p></div> </div> </div>');
                var div26 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['bear_tax']['text'] + ': <span> ' + dataExtended['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                var div27 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['product_type_id']['text'] + ': <span> ' + dataExtended['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                var div28 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['housing_allowance']['text'] + ': <span> ' + dataExtended['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div29 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['transfer_allowance']['text'] + ': <span> ' + dataExtended['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div30 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['other_allowance']['text'] + ': <span> ' + dataExtended['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                var div31 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['retirement_income']['text'] + ': <span> ' + dataExtended['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                var div32 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['job_tenure_months']['text'] + ': <span> ' + dataExtended['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                var div33 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['job_tenure_years']['text'] + ': <span> ' + dataExtended['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                var div34 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['first_batch_mode']['text'] + ': <span> ' + dataExtended['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                var div35 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['credit_installment']['text'] + ': <span> ' + dataExtended['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                var div36 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['obligations_installment']['text'] + ': <span> ' + dataExtended['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                var div37 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['remaining_obligations_months']['text'] + ': <span> ' + dataExtended['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                var div38 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['remaining_retirement_months']['text'] + ': <span> ' + dataExtended['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                var div39 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['remaining_retirement_years']['text'] + ': <span> ' + dataExtended['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                var div40 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['residential_support']['text'] + ': <span> ' + dataExtended['residential_support']['value'] + ' </span></p></div> </div> </div>');
                var div41 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['add_support_installment_to_salary']['text'] + ': <span> ' + dataExtended['add_support_installment_to_salary']['value'] + ' </span></p></div> </div> </div>');
                var div42 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['add_support_installment_to_installment']['text'] + ': <span> ' + dataExtended['add_support_installment_to_installment']['value'] + ' </span></p></div> </div> </div>');
                var div43 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['guarantees']['text'] + ': <span> ' + dataExtended['guarantees']['value'] + ' </span></p></div> </div> </div>');
                var div44 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['provide_first_batch']['text'] + ': <span> ' + dataExtended['provide_first_batch']['value'] + ' </span></p></div> </div> </div>');
                var div45 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['secured']['text'] + ': <span> ' + dataExtended['secured']['value'] + ' </span></p></div> </div> </div>');
                var div46 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['property_amount']['text'] + ': <span> ' + dataExtended['property_amount']['value'] + ' </span></p></div> </div> </div>');
                var div47 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['is_property_completed']['text'] + ': <span> ' + dataExtended['is_property_completed']['value'] + ' </span></p></div> </div> </div>');
                var div48 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['residence_type']['text'] + ': <span> ' + dataExtended['residence_type']['value'] + ' </span></p></div> </div> </div>');
                var div49 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['first_batch_percentage']['text'] + ': <span> ' + dataExtended['first_batch_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div50 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['first_batch']['text'] + ': <span> ' + dataExtended['first_batch']['value'] + ' </span></p></div> </div> </div>');
                var div51 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['first_batch_profit']['text'] + ': <span> ' + dataExtended['first_batch_profit']['value'] + ' </span></p></div> </div> </div>');
                var div52 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['first_batch_profit_amount']['text'] + ': <span> ' + dataExtended['first_batch_profit_amount']['value'] + ' </span></p></div> </div> </div>');
                var div53 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['quest']['text'] + ': <span> ' + dataExtended['quest']['value'] + ' </span></p></div> </div> </div>');
                var div54 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['quest_amount']['text'] + ': <span> ' + dataExtended['quest_amount']['value'] + ' </span></p></div> </div> </div>');
                var div55 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['quest_amount_with_vat']['text'] + ': <span> ' + dataExtended['quest_amount_with_vat']['value'] + ' </span></p></div> </div> </div>');
                var div56 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['fees']['text'] + ': <span> ' + dataExtended['fees']['value'] + ' </span></p></div> </div> </div>');
                var div57 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['discount']['text'] + ': <span> ' + dataExtended['discount']['value'] + ' </span></p></div> </div> </div>');
                var div58 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['vat']['text'] + ': <span> ' + dataExtended['vat']['value'] + ' </span></p></div> </div> </div>');
                var div59 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['check_total']['text'] + ': <span> ' + dataExtended['check_total']['value'] + ' </span></p></div> </div> </div>');
                var div60 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['net_check_total']['text'] + ': <span> ' + dataExtended['net_check_total']['value'] + ' </span></p></div> </div> </div>');
                var div61 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['net_bond_amount']['text'] + ': <span> ' + dataExtended['net_bond_amount']['value'] + ' </span></p></div> </div> </div>');
                var div62 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['min_net_loan_total']['text'] + ': <span> ' + dataExtended['min_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div63 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['purchase_tax']['text'] + ': <span> ' + dataExtended['purchase_tax']['value'] + ' </span></p></div> </div> </div>');
                var div64 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['purchase_tax_percentage']['text'] + ': <span> ' + dataExtended['purchase_tax_percentage']['value'] + ' </span></p></div> </div> </div>');
                var div65 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_funding_years']['text'] + ': <span> ' + dataExtended['personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div66 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_funding_months']['text'] + ': <span> ' + dataExtended['personal_funding_months']['value'] + ' </span></p></div> </div> </div>');
                var div67 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_installment']['text'] + ': <span> ' + dataExtended['personal_installment']['value'] + ' </span></p></div> </div> </div>');
                var div68 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_salary_deduction']['text'] + ': <span> ' + dataExtended['personal_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div69 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_profit']['text'] + ': <span> ' + dataExtended['personal_profit']['value'] + ' </span></p></div> </div> </div>');
                var div70 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_profit_margin']['text'] + ': <span> ' + dataExtended['personal_profit_margin']['value'] + ' </span></p></div> </div> </div>');
                var div71 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_loan_total']['text'] + ': <span> ' + dataExtended['personal_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div72 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['personal_net_loan_total']['text'] + ': <span> ' + dataExtended['personal_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div73 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['extra_personal_funding_years']['text'] + ': <span> ' + dataExtended['extra_personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div74 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['installment']['text'] + ': <span> ' + dataExtended['installment']['value'] + ' </span></p></div> </div> </div>');
                var div75 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['support_installment']['text'] + ': <span> ' + dataExtended['support_installment']['value'] + ' </span></p></div> </div> </div>');
                var div76 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['installment_after_support']['text'] + ': <span> ' + dataExtended['installment_after_support']['value'] + ' </span></p></div> </div> </div>');
                var div77 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['salary_deduction']['text'] + ': <span> ' + dataExtended['salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div78 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['profit']['text'] + ': <span> ' + dataExtended['profit']['value'] + ' </span></p></div> </div> </div>');
                var div79 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['profit_margin']['text'] + ': <span> ' + dataExtended['profit_margin']['value'] + ' </span></p></div> </div> </div>');
                var div80 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['loan_total']['text'] + ': <span> ' + dataExtended['loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div81 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['flexible_installment']['text'] + ': <span> ' + dataExtended['flexible_installment']['value'] + ' </span></p></div> </div> </div>');
                var div82 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['property_flexible_installment']['text'] + ': <span> ' + dataExtended['property_flexible_installment']['value'] + ' </span></p></div> </div> </div>');
                var div83 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['flexible_salary_deduction']['text'] + ': <span> ' + dataExtended['flexible_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                var div84 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['retirement_salary']['text'] + ': <span> ' + dataExtended['retirement_salary']['value'] + ' </span></p></div> </div> </div>');
                var div85 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['retirement_installment']['text'] + ': <span> ' + dataExtended['retirement_installment']['value'] + ' </span></p></div> </div> </div>');
                var div86 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['flexible_loan_total']['text'] + ': <span> ' + dataExtended['flexible_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div87 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['retirement_loan_total']['text'] + ': <span> ' + dataExtended['retirement_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div88 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['retirement_net_loan_total']['text'] + ': <span> ' + dataExtended['retirement_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div89 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['net_loan_total']['text'] + ': <span> ' + dataExtended['net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                var div90 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['calculator_program']['text'] + ': <span> ' + dataExtended['calculator_program']['value'] + ' </span></p></div> </div> </div>');
                var div91 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_years']['text'] + ': <span> ' + dataExtended['funding_years']['value'] + ' </span></p></div> </div> </div>');
                var div92 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_months']['text'] + ': <span> ' + dataExtended['funding_months']['value'] + ' </span></p></div> </div> </div>');
                var div93 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_years_before']['text'] + ': <span> ' + dataExtended['funding_years_before']['value'] + ' </span></p></div> </div> </div>');
                var div94 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_months_before']['text'] + ': <span> ' + dataExtended['funding_months_before']['value'] + ' </span></p></div> </div> </div>');
                var div95 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_years_after']['text'] + ': <span> ' + dataExtended['funding_years_after']['value'] + ' </span></p></div> </div> </div>');
                var div96 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['funding_months_after']['text'] + ': <span> ' + dataExtended['funding_months_after']['value'] + ' </span></p></div> </div> </div>');
                var div97 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataExtended['loan_total_profits']['text'] + ': <span> ' + dataExtended['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');

                if (extendedSettings[78]['value_en'] === "retirement_net_loan_total" && extendedSettings[78]['option_value'] === 1) {
                    div5.append(div88);
                }
                if (extendedSettings[62]['value_en'] === "personal_net_loan_total" && extendedSettings[62]['option_value'] === 1) {
                    div5.append(div72);
                }
                if (extendedSettings[74]['value_en'] === "retirement_salary" && extendedSettings[74]['option_value'] === 1) {
                    div5.append(div84);
                }
                if (extendedSettings[64]['value_en'] === "installment" && extendedSettings[64]['option_value'] === 1) {
                    div5.append(div74);
                }
                if (extendedSettings[75]['value_en'] === "retirement_installment" && extendedSettings[75]['option_value'] === 1) {
                    div5.append(div85);
                }
                if (extendedSettings[65]['value_en'] === "support_installment" && extendedSettings[65]['option_value'] === 1) {
                    div5.append(div75);
                }
                if (extendedSettings[83]['value_en'] === "funding_years_before" && extendedSettings[83]['option_value'] === 1) {
                    div5.append(div93);
                }
                if (extendedSettings[85]['value_en'] === "funding_years_after" && extendedSettings[85]['option_value'] === 1) {
                    div5.append(div95);
                }
                if (extendedSettings[81]['value_en'] === "funding_years" && extendedSettings[81]['option_value'] === 1) {
                    div5.append(div91);
                }
                if (extendedSettings[68]['value_en'] === "profit" && extendedSettings[68]['option_value'] === 1) {
                    div5.append(div78);
                }
                if (extendedSettings[44]['value_en'] === "quest_amount" && extendedSettings[44]['option_value'] === 1) {
                    div5.append(div54);
                }
                if (extendedSettings[49]['value_en'] === "check_total" && extendedSettings[49]['option_value'] === 1) {
                    div5.append(div59);
                }
                if (extendedSettings[40]['value_en'] === "first_batch" && extendedSettings[40]['option_value'] === 1) {
                    div5.append(div50);
                }
                if (extendedSettings[51]['value_en'] === "net_bond_amount" && extendedSettings[51]['option_value'] === 1) {
                    div5.append(div61);
                }
                if (extendedSettings[50]['value_en'] === "net_check_total" && extendedSettings[50]['option_value'] === 1) {
                    div5.append(div60);
                }
                if (extendedSettings[53]['value_en'] === "purchase_tax" && extendedSettings[53]['option_value'] === 1) {
                    div5.append(div63);
                }
                if (extendedSettings[52]['value_en'] === "min_net_loan_total" && extendedSettings[52]['option_value'] === 1) {
                    div5.append(div62);
                }
                if (extendedSettings[87]['value_en'] === "loan_total_profits" && extendedSettings[87]['option_value'] === 1) {
                    div5.append(div97);
                }



                if (extendedSettings[66]['value_en'] === "installment_after_support" && extendedSettings[66]['option_value'] === 1) {
                    div5.append(div76);
                }
                if (extendedSettings[0]['value_en'] === "bank_code" && extendedSettings[0]['option_value'] === 1) {
                    div5.append(div10);
                }
                if (extendedSettings[1]['value_en'] === "bank_name" && extendedSettings[1]['option_value'] === 1) {
                    div5.append(div11);
                }
                if (extendedSettings[2]['value_en'] === "salary_bank_code" && extendedSettings[2]['option_value'] === 1) {
                    div5.append(div12);
                }
                if (extendedSettings[3]['value_en'] === "salary_bank_name" && extendedSettings[3]['option_value'] === 1) {
                    div5.append(div13);
                }
                if (extendedSettings[4]['value_en'] === "is_salary_transfer" && extendedSettings[4]['option_value'] === 1) {
                    div5.append(div14);
                }
                if (extendedSettings[5]['value_en'] === "job_position_code" && extendedSettings[5]['option_value'] === 1) {
                    div5.append(div15);
                }
                if (extendedSettings[6]['value_en'] === "job_position_name" && extendedSettings[6]['option_value'] === 1) {
                    div5.append(div16);
                }
                if (extendedSettings[7]['value_en'] === "age" && extendedSettings[7]['option_value'] === 1) {
                    div5.append(div17);
                }
                if (extendedSettings[8]['value_en'] === "age_by_months" && extendedSettings[8]['option_value'] === 1) {
                    div5.append(div18);
                }
                if (extendedSettings[9]['value_en'] === "retirement_age" && extendedSettings[9]['option_value'] === 1) {
                    div5.append(div19);
                }
                if (extendedSettings[10]['value_en'] === "funding_age_limit" && extendedSettings[10]['option_value'] === 1) {
                    div5.append(div20);
                }
                if (extendedSettings[11]['value_en'] === "extra_funding_years" && extendedSettings[11]['option_value'] === 1) {
                    div5.append(div21);
                }
                if (extendedSettings[12]['value_en'] === "salary" && extendedSettings[12]['option_value'] === 1) {
                    div5.append(div22);
                }
                if (extendedSettings[13]['value_en'] === "basic_salary" && extendedSettings[13]['option_value'] === 1) {
                    div5.append(div23);
                }
                if (extendedSettings[14]['value_en'] === "early_repayment" && extendedSettings[14]['option_value'] === 1) {
                    div5.append(div24);
                }
                if (extendedSettings[15]['value_en'] === "quest_check" && extendedSettings[15]['option_value'] === 1) {
                    div5.append(div25);
                }
                if (extendedSettings[16]['value_en'] === "bear_tax" && extendedSettings[16]['option_value'] === 1) {
                    div5.append(div26);
                }
                if (extendedSettings[17]['value_en'] === "product_type_id" && extendedSettings[17]['option_value'] === 1) {
                    div5.append(div27);
                }
                if (extendedSettings[18]['value_en'] === "housing_allowance" && extendedSettings[18]['option_value'] === 1) {
                    div5.append(div28);
                }
                if (extendedSettings[19]['value_en'] === "transfer_allowance" && extendedSettings[19]['option_value'] === 1) {
                    div5.append(div29);
                }
                if (extendedSettings[20]['value_en'] === "other_allowance" && extendedSettings[20]['option_value'] === 1) {
                    div5.append(div30);
                }
                if (extendedSettings[21]['value_en'] === "retirement_income" && extendedSettings[21]['option_value'] === 1) {
                    div5.append(div31);
                }
                if (extendedSettings[22]['value_en'] === "job_tenure_months" && extendedSettings[22]['option_value'] === 1) {
                    div5.append(div32);
                }
                if (extendedSettings[23]['value_en'] === "job_tenure_years" && extendedSettings[23]['option_value'] === 1) {
                    div5.append(div33);
                }
                if (extendedSettings[24]['value_en'] === "first_batch_mode" && extendedSettings[24]['option_value'] === 1) {
                    div5.append(div34);
                }
                if (extendedSettings[25]['value_en'] === "credit_installment" && extendedSettings[25]['option_value'] === 1) {
                    div5.append(div35);
                }
                if (extendedSettings[26]['value_en'] === "obligations_installment" && extendedSettings[26]['option_value'] === 1) {
                    div5.append(div36);
                }
                if (extendedSettings[27]['value_en'] === "remaining_obligations_months" && extendedSettings[27]['option_value'] === 1) {
                    div5.append(div37);
                }
                if (extendedSettings[28]['value_en'] === "remaining_retirement_months" && extendedSettings[28]['option_value'] === 1) {
                    div5.append(div38);
                }
                if (extendedSettings[29]['value_en'] === "remaining_retirement_years" && extendedSettings[29]['option_value'] === 1) {
                    div5.append(div39);
                }
                if (extendedSettings[30]['value_en'] === "residential_support" && extendedSettings[30]['option_value'] === 1) {
                    div5.append(div40);
                }
                if (extendedSettings[31]['value_en'] === "add_support_installment_to_salary" && extendedSettings[31]['option_value'] === 1) {
                    div5.append(div41);
                }
                if (extendedSettings[32]['value_en'] === "add_support_installment_to_installment" && extendedSettings[32]['option_value'] === 1) {
                    div5.append(div42);
                }
                if (extendedSettings[33]['value_en'] === "guarantees" && extendedSettings[33]['option_value'] === 1) {
                    div5.append(div43);
                }
                if (extendedSettings[34]['value_en'] === "provide_first_batch" && extendedSettings[34]['option_value'] === 1) {
                    div5.append(div44);
                }
                if (extendedSettings[35]['value_en'] === "secured" && extendedSettings[35]['option_value'] === 1) {
                    div5.append(div45);
                }
                if (extendedSettings[36]['value_en'] === "property_amount" && extendedSettings[36]['option_value'] === 1) {
                    div5.append(div46);
                }
                if (extendedSettings[37]['value_en'] === "is_property_completed" && extendedSettings[37]['option_value'] === 1) {
                    div5.append(div47);
                }
                if (extendedSettings[38]['value_en'] === "residence_type" && extendedSettings[38]['option_value'] === 1) {
                    div5.append(div48);
                }
                if (extendedSettings[39]['value_en'] === "first_batch_percentage" && extendedSettings[39]['option_value'] === 1) {
                    div5.append(div49);
                }
                if (extendedSettings[41]['value_en'] === "first_batch_profit" && extendedSettings[41]['option_value'] === 1) {
                    div5.append(div51);
                }
                if (extendedSettings[42]['value_en'] === "first_batch_profit_amount" && extendedSettings[42]['option_value'] === 1) {
                    div5.append(div52);
                }
                if (extendedSettings[43]['value_en'] === "quest" && extendedSettings[43]['option_value'] === 1) {
                    div5.append(div53);
                }
                if (extendedSettings[45]['value_en'] === "quest_amount_with_vat" && extendedSettings[45]['option_value'] === 1) {
                    div5.append(div55);
                }
                if (extendedSettings[46]['value_en'] === "fees" && extendedSettings[46]['option_value'] === 1) {
                    div5.append(div56);
                }
                if (extendedSettings[47]['value_en'] === "discount" && extendedSettings[47]['option_value'] === 1) {
                    div5.append(div57);
                }
                if (extendedSettings[48]['value_en'] === "vat" && extendedSettings[48]['option_value'] === 1) {
                    div5.append(div58);
                }
                if (extendedSettings[54]['value_en'] === "purchase_tax_percentage" && extendedSettings[54]['option_value'] === 1) {
                    div5.append(div64);
                }
                if (extendedSettings[55]['value_en'] === "personal_funding_years" && extendedSettings[55]['option_value'] === 1) {
                    div5.append(div65);
                }
                if (extendedSettings[56]['value_en'] === "personal_funding_months" && extendedSettings[56]['option_value'] === 1) {
                    div5.append(div66);
                }
                if (extendedSettings[57]['value_en'] === "personal_installment" && extendedSettings[57]['option_value'] === 1) {
                    div5.append(div67);
                }
                if (extendedSettings[58]['value_en'] === "personal_salary_deduction" && extendedSettings[58]['option_value'] === 1) {
                    div5.append(div68);
                }
                if (extendedSettings[59]['value_en'] === "personal_profit" && extendedSettings[59]['option_value'] === 1) {
                    div5.append(div69);
                }
                if (extendedSettings[60]['value_en'] === "personal_profit_margin" && extendedSettings[60]['option_value'] === 1) {
                    div5.append(div70);
                }
                if (extendedSettings[61]['value_en'] === "personal_loan_total" && extendedSettings[61]['option_value'] === 1) {
                    div5.append(div71);
                }
                if (extendedSettings[63]['value_en'] === "extra_personal_funding_years" && extendedSettings[63]['option_value'] === 1) {
                    div5.append(div73);
                }
                if (extendedSettings[67]['value_en'] === "salary_deduction" && extendedSettings[67]['option_value'] === 1) {
                    div5.append(div77);
                }
                if (extendedSettings[69]['value_en'] === "profit_margin" && extendedSettings[69]['option_value'] === 1) {
                    div5.append(div79);
                }
                if (extendedSettings[70]['value_en'] === "loan_total" && extendedSettings[70]['option_value'] === 1) {
                    div5.append(div80);
                }
                if (extendedSettings[71]['value_en'] === "flexible_installment" && extendedSettings[71]['option_value'] === 1) {
                    div5.append(div81);
                }
                if (extendedSettings[72]['value_en'] === "property_flexible_installment" && extendedSettings[72]['option_value'] === 1) {
                    div5.append(div82);
                }
                if (extendedSettings[73]['value_en'] === "flexible_salary_deduction" && extendedSettings[73]['option_value'] === 1) {
                    div5.append(div83);
                }
                if (extendedSettings[76]['value_en'] === "flexible_loan_total" && extendedSettings[76]['option_value'] === 1) {
                    div5.append(div86);
                }
                if (extendedSettings[77]['value_en'] === "retirement_loan_total" && extendedSettings[77]['option_value'] === 1) {
                    div5.append(div87);
                }
                if (extendedSettings[79]['value_en'] === "net_loan_total" && extendedSettings[79]['option_value'] === 1) {
                    div5.append(div89);
                }
                if (extendedSettings[80]['value_en'] === "calculator_program" && extendedSettings[80]['option_value'] === 1) {
                    div5.append(div90);
                }
                if (extendedSettings[82]['value_en'] === "funding_months" && extendedSettings[82]['option_value'] === 1) {
                    div5.append(div92);
                }
                if (extendedSettings[84]['value_en'] === "funding_months_before" && extendedSettings[84]['option_value'] === 1) {
                    div5.append(div94);
                }
                if (extendedSettings[86]['value_en'] === "funding_months_after" && extendedSettings[86]['option_value'] === 1) {
                    div5.append(div96);
                }
                // console.log(extendedSettings)
                div4.append(div5);
                div4.append(addButton3);
                content.append(typeFlixable);
                content.append(div4);
                body.append(content);
                div1.append(body);
            }

            if (data[i]?.joint_programs) {
                var jointTitle = $('<h5 class="py-3 singleBankName" style="color:#0f5b94;text-align:center;font-weight: bold"> <i class="fas fa-user"></i>تمويل المتضامن</h5>');
                div1.append(jointTitle);
                // if (data[i]['joint_programs']['personalProgram'] !== null && data[i]['joint_programs']['personalProgram'] !== '') {
                if (data[i].joint_programs.personalProgram) {
                    var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                    var content = $(' <div class="col-12 mb-md-0"></div>');
                    var typePersonal = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> شخصي </p> <i class="fas fa-chevron-down"></i> </div>');
                    var div4 = $(' <div class="contREsult"></div>');
                    var div5 = $(' <div class="row"></div>');
                    var dataPersonal = data[i]['joint_programs']['personalProgram']['raw'];
                    //PERSONAL DETAILS
                    var div7 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_net_loan_total']['text'] + ': <span> ' + dataPersonal['personal_net_loan_total']['value'] + ' </span></p> </div> </div> </div>');
                    var div8 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_installment']['text'] + ': <span> ' + dataPersonal['personal_installment']['value'] + '</span> </p> </div> </div> </div>');
                    var div9 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" > <div class = "singleTypeResult" ><p> ' + dataPersonal['personal_funding_years']['text'] + ': <span> ' + dataPersonal['personal_funding_years']['value'] + '</span></p></div> </div> </div>');
                    var div10 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_profit']['text'] + ': <span> ' + dataPersonal['personal_profit']['value'] + ' </span></p></div> </div> </div>');
                    var div12 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['bank_code']['text'] + ': <span> ' + dataPersonal['bank_code']['value'] + ' </span></p></div> </div> </div>');
                    var div13 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['bank_name']['text'] + ': <span> ' + dataPersonal['bank_name']['value'] + ' </span></p></div> </div> </div>');
                    var div14 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['salary_bank_code']['text'] + ': <span> ' + dataPersonal['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                    var div15 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['salary_bank_name']['text'] + ': <span> ' + dataPersonal['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                    var div16 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['is_salary_transfer']['text'] + ': <span> ' + dataPersonal['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                    var div17 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_position_code']['text'] + ': <span> ' + dataPersonal['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                    var div18 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_position_name']['text'] + ': <span> ' + dataPersonal['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                    var div19 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['age']['text'] + ': <span> ' + dataPersonal['age']['value'] + ' </span></p></div> </div> </div>');
                    var div20 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['age_by_months']['text'] + ': <span> ' + dataPersonal['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                    var div21 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['retirement_age']['text'] + ': <span> ' + dataPersonal['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                    var div22 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['funding_age_limit']['text'] + ': <span> ' + dataPersonal['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                    var div23 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['extra_funding_years']['text'] + ': <span> ' + dataPersonal['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div24 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['salary']['text'] + ': <span> ' + dataPersonal['salary']['value'] + ' </span></p></div> </div> </div>');
                    var div25 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['basic_salary']['text'] + ': <span> ' + dataPersonal['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                    var div26 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['early_repayment']['text'] + ': <span> ' + dataPersonal['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                    var div27 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['quest_check']['text'] + ': <span> ' + dataPersonal['quest_check']['value'] + ' </span></p></div> </div> </div>');
                    var div28 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['bear_tax']['text'] + ': <span> ' + dataPersonal['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                    var div29 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['product_type_id']['text'] + ': <span> ' + dataPersonal['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                    var div30 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['housing_allowance']['text'] + ': <span> ' + dataPersonal['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div31 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['transfer_allowance']['text'] + ': <span> ' + dataPersonal['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div32 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['other_allowance']['text'] + ': <span> ' + dataPersonal['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div33 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['retirement_income']['text'] + ': <span> ' + dataPersonal['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                    var div34 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_tenure_months']['text'] + ': <span> ' + dataPersonal['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                    var div35 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['job_tenure_years']['text'] + ': <span> ' + dataPersonal['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                    var div36 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['first_batch_mode']['text'] + ': <span> ' + dataPersonal['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                    var div37 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['credit_installment']['text'] + ': <span> ' + dataPersonal['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div38 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['obligations_installment']['text'] + ': <span> ' + dataPersonal['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div39 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['remaining_obligations_months']['text'] + ': <span> ' + dataPersonal['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                    var div40 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['remaining_retirement_months']['text'] + ': <span> ' + dataPersonal['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                    var div41 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['remaining_retirement_years']['text'] + ': <span> ' + dataPersonal['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                    var div43 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_funding_months']['text'] + ': <span> ' + dataPersonal['personal_funding_months']['value'] + ' </span></p></div> </div> </div>');
                    var div44 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_salary_deduction']['text'] + ': <span> ' + dataPersonal['personal_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                    var div45 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_profit_margin']['text'] + ': <span> ' + dataPersonal['personal_profit_margin']['value'] + ' </span></p></div> </div> </div>');
                    var div46 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['personal_loan_total']['text'] + ': <span> ' + dataPersonal['personal_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div47 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['loan_total_profits']['text'] + ': <span> ' + dataPersonal['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');
                    var div48 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['calculator_program']['text'] + ': <span> ' + dataPersonal['calculator_program']['value'] + ' </span></p></div> </div> </div>');
                    var div49 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataPersonal['extra_personal_funding_years']['text'] + ': <span> ' + dataPersonal['extra_personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div12 = $(' <div class="contREsult"></div>');


                    if (personalSettings[0]['value_en'] === "bank_code" && personalSettings[0]['option_value'] === 1) {
                        div5.append(div12);
                    }
                    if (personalSettings[1]['value_en'] === "bank_name" && personalSettings[1]['option_value'] === 1) {
                        div5.append(div13);
                    }
                    if (personalSettings[2]['value_en'] === "salary_bank_code" && personalSettings[2]['option_value'] === 1) {
                        div5.append(div14);
                    }
                    if (personalSettings[3]['value_en'] === "salary_bank_name" && personalSettings[3]['option_value'] === 1) {
                        div5.append(div15);
                    }
                    if (personalSettings[4]['value_en'] === "is_salary_transfer" && personalSettings[4]['option_value'] === 1) {
                        div5.append(div16);
                    }
                    if (personalSettings[5]['value_en'] === "job_position_code" && personalSettings[5]['option_value'] === 1) {
                        div5.append(div17);
                    }
                    if (personalSettings[6]['value_en'] === "job_position_name" && personalSettings[6]['option_value'] === 1) {
                        div5.append(div18);
                    }
                    if (personalSettings[7]['value_en'] === "age" && personalSettings[7]['option_value'] === 1) {
                        div5.append(div19);
                    }
                    if (personalSettings[8]['value_en'] === "age_by_months" && personalSettings[8]['option_value'] === 1) {
                        div5.append(div20);
                    }
                    if (personalSettings[9]['value_en'] === "retirement_age" && personalSettings[9]['option_value'] === 1) {
                        div5.append(div21);
                    }
                    if (personalSettings[10]['value_en'] === "funding_age_limit" && personalSettings[10]['option_value'] === 1) {
                        div5.append(div22);
                    }
                    if (personalSettings[11]['value_en'] === "extra_funding_years" && personalSettings[11]['option_value'] === 1) {
                        div5.append(div23);
                    }
                    if (personalSettings[12]['value_en'] === "salary" && personalSettings[12]['option_value'] === 1) {
                        div5.append(div24);
                    }
                    if (personalSettings[13]['value_en'] === "basic_salary" && personalSettings[13]['option_value'] === 1) {
                        div5.append(div25);
                    }
                    if (personalSettings[14]['value_en'] === "early_repayment" && personalSettings[14]['option_value'] === 1) {
                        div5.append(div26);
                    }
                    if (personalSettings[15]['value_en'] === "quest_check" && personalSettings[15]['option_value'] === 1) {
                        div5.append(div27);
                    }
                    if (personalSettings[16]['value_en'] === "bear_tax" && personalSettings[16]['option_value'] === 1) {
                        div5.append(div28);
                    }
                    if (personalSettings[17]['value_en'] === "product_type_id" && personalSettings[17]['option_value'] === 1) {
                        div5.append(div29);
                    }
                    if (personalSettings[18]['value_en'] === "product_type_id" && personalSettings[18]['option_value'] === 1) {
                        div5.append(div30);
                    }
                    if (personalSettings[19]['value_en'] === "transfer_allowance" && personalSettings[19]['option_value'] === 1) {
                        div5.append(div31);
                    }
                    if (personalSettings[20]['value_en'] === "other_allowance" && personalSettings[20]['option_value'] === 1) {
                        div5.append(div32);
                    }
                    if (personalSettings[21]['value_en'] === "retirement_income" && personalSettings[21]['option_value'] === 1) {
                        div5.append(div33);
                    }
                    if (personalSettings[22]['value_en'] === "job_tenure_months" && personalSettings[22]['option_value'] === 1) {
                        div5.append(div34);
                    }
                    if (personalSettings[23]['value_en'] === "job_tenure_years" && personalSettings[23]['option_value'] === 1) {
                        div5.append(div35);
                    }
                    if (personalSettings[24]['value_en'] === "first_batch_mode" && personalSettings[24]['option_value'] === 1) {
                        div5.append(div36);
                    }
                    if (personalSettings[25]['value_en'] === "credit_installment" && personalSettings[25]['option_value'] === 1) {
                        div5.append(div37);
                    }
                    if (personalSettings[26]['value_en'] === "obligations_installment" && personalSettings[26]['option_value'] === 1) {
                        div5.append(div38);
                    }
                    if (personalSettings[27]['value_en'] === "remaining_obligations_months" && personalSettings[27]['option_value'] === 1) {
                        div5.append(div39);
                    }
                    if (personalSettings[28]['value_en'] === "remaining_retirement_months" && personalSettings[28]['option_value'] === 1) {
                        div5.append(div40);
                    }
                    if (personalSettings[29]['value_en'] === "remaining_retirement_years" && personalSettings[29]['option_value'] === 1) {
                        div5.append(div41);
                    }
                    if (personalSettings[30]['value_en'] === "personal_funding_years" && personalSettings[30]['option_value'] === 1) {
                        div5.append(div9);
                    }
                    if (personalSettings[31]['value_en'] === "personal_funding_months" && personalSettings[31]['option_value'] === 1) {
                        div5.append(div43);
                    }
                    if (personalSettings[32]['value_en'] === "personal_installment" && personalSettings[32]['option_value'] === 1) {
                        div5.append(div8);
                    }
                    if (personalSettings[33]['value_en'] === "personal_salary_deduction" && personalSettings[33]['option_value'] === 1) {
                        div5.append(div44);
                    }
                    if (personalSettings[34]['value_en'] === "personal_profit" && personalSettings[34]['option_value'] === 1) {
                        div5.append(div10);
                    }
                    if (personalSettings[35]['value_en'] === "personal_profit_margin" && personalSettings[35]['option_value'] === 1) {
                        div5.append(div45);
                    }
                    if (personalSettings[36]['value_en'] === "personal_loan_total" && personalSettings[36]['option_value'] === 1) {
                        div5.append(div46);
                    }
                    if (personalSettings[37]['value_en'] === "personal_net_loan_total" && personalSettings[37]['option_value'] === 1) {
                        div5.append(div7);
                    }
                    if (personalSettings[38]['value_en'] === "loan_total_profits" && personalSettings[38]['option_value'] === 1) {
                        div5.append(div47);
                    }
                    if (personalSettings[39]['value_en'] === "calculator_program" && personalSettings[39]['option_value'] === 1) {
                        div5.append(div48);
                    }
                    if (personalSettings[40]['value_en'] === "extra_personal_funding_years" && personalSettings[40]['option_value'] === 1) {
                        div5.append(div49);
                    }


                    div4.html(div5);
                    content.append(typePersonal);
                    content.append(div4);
                    body.html(content);

                    div1.append(body);


                }
                // if (data[i]['joint_programs']['propertyProgram'] !== null && data[i]['joint_programs']['propertyProgram'] !== '') {
                if (data[i].joint_programs.propertyProgram) {
                    var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                    var content = $(' <div class="col-12 mb-md-0"></div>');
                    var typeProperty = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> عقاري </p> <i class="fas fa-chevron-down"></i> </div>');
                    var div4 = $(' <div class="contREsult"></div>');
                    var div5 = $(' <div class="row"></div>');
                    var dataProperty = data[i]['joint_programs']['propertyProgram']['raw'];
                    var div10 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['bank_code']['text'] + ': <span> ' + dataProperty['bank_code']['value'] + ' </span></p></div> </div> </div>');
                    var div11 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['bank_name']['text'] + ': <span> ' + dataProperty['bank_name']['value'] + ' </span></p></div> </div> </div>');
                    var div12 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary_bank_code']['text'] + ': <span> ' + dataProperty['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                    var div13 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary_bank_name']['text'] + ': <span> ' + dataProperty['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                    var div14 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['is_salary_transfer']['text'] + ': <span> ' + dataProperty['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                    var div15 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_position_code']['text'] + ': <span> ' + dataProperty['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                    var div16 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_position_name']['text'] + ': <span> ' + dataProperty['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                    var div17 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['age']['text'] + ': <span> ' + dataProperty['age']['value'] + ' </span></p></div> </div> </div>');
                    var div18 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['age_by_months']['text'] + ': <span> ' + dataProperty['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                    var div19 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['retirement_age']['text'] + ': <span> ' + dataProperty['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                    var div20 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['funding_age_limit']['text'] + ': <span> ' + dataProperty['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                    var div21 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['extra_funding_years']['text'] + ': <span> ' + dataProperty['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div22 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary']['text'] + ': <span> ' + dataProperty['salary']['value'] + ' </span></p></div> </div> </div>');
                    var div23 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['basic_salary']['text'] + ': <span> ' + dataProperty['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                    var div24 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['early_repayment']['text'] + ': <span> ' + dataProperty['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                    var div25 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest_check']['text'] + ': <span> ' + dataProperty['quest_check']['value'] + ' </span></p></div> </div> </div>');
                    var div26 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['bear_tax']['text'] + ': <span> ' + dataProperty['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                    var div27 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['product_type_id']['text'] + ': <span> ' + dataProperty['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                    var div28 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['housing_allowance']['text'] + ': <span> ' + dataProperty['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div29 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['transfer_allowance']['text'] + ': <span> ' + dataProperty['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div30 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['other_allowance']['text'] + ': <span> ' + dataProperty['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div31 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['retirement_income']['text'] + ': <span> ' + dataProperty['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                    var div32 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_tenure_months']['text'] + ': <span> ' + dataProperty['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                    var div33 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['job_tenure_years']['text'] + ': <span> ' + dataProperty['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                    var div34 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_mode']['text'] + ': <span> ' + dataProperty['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                    var div35 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['credit_installment']['text'] + ': <span> ' + dataProperty['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div36 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['obligations_installment']['text'] + ': <span> ' + dataProperty['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div37 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['remaining_obligations_months']['text'] + ': <span> ' + dataProperty['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                    var div38 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['remaining_retirement_months']['text'] + ': <span> ' + dataProperty['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                    var div39 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['remaining_retirement_years']['text'] + ': <span> ' + dataProperty['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                    var div40 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['residential_support']['text'] + ': <span> ' + dataProperty['residential_support']['value'] + ' </span></p></div> </div> </div>');
                    var div41 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['add_support_installment_to_salary']['text'] + ': <span> ' + dataProperty['add_support_installment_to_salary']['value'] + ' </span></p></div> </div> </div>');
                    var div42 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['add_support_installment_to_installment']['text'] + ': <span> ' + dataProperty['add_support_installment_to_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div43 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['guarantees']['text'] + ': <span> ' + dataProperty['guarantees']['value'] + ' </span></p></div> </div> </div>');
                    var div44 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['provide_first_batch']['text'] + ': <span> ' + dataProperty['provide_first_batch']['value'] + ' </span></p></div> </div> </div>');
                    var div45 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['secured']['text'] + ': <span> ' + dataProperty['secured']['value'] + ' </span></p></div> </div> </div>');
                    var div46 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['property_amount']['text'] + ': <span> ' + dataProperty['property_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div47 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['is_property_completed']['text'] + ': <span> ' + dataProperty['is_property_completed']['value'] + ' </span></p></div> </div> </div>');
                    var div48 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['residence_type']['text'] + ': <span> ' + dataProperty['residence_type']['value'] + ' </span></p></div> </div> </div>');
                    var div49 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_percentage']['text'] + ': <span> ' + dataProperty['first_batch_percentage']['value'] + ' </span></p></div> </div> </div>');
                    var div50 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch']['text'] + ': <span> ' + dataProperty['first_batch']['value'] + ' </span></p></div> </div> </div>');
                    var div51 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_profit']['text'] + ': <span> ' + dataProperty['first_batch_profit']['value'] + ' </span></p></div> </div> </div>');
                    var div52 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['first_batch_profit_amount']['text'] + ': <span> ' + dataProperty['first_batch_profit_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div53 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest']['text'] + ': <span> ' + dataProperty['quest']['value'] + ' </span></p></div> </div> </div>');
                    var div54 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest_amount']['text'] + ': <span> ' + dataProperty['quest_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div55 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['quest_amount_with_vat']['text'] + ': <span> ' + dataProperty['quest_amount_with_vat']['value'] + ' </span></p></div> </div> </div>');
                    var div56 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['fees']['text'] + ': <span> ' + dataProperty['fees']['value'] + ' </span></p></div> </div> </div>');
                    var div57 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['discount']['text'] + ': <span> ' + dataProperty['discount']['value'] + ' </span></p></div> </div> </div>');
                    var div58 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['vat']['text'] + ': <span> ' + dataProperty['vat']['value'] + ' </span></p></div> </div> </div>');
                    var div59 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['check_total']['text'] + ': <span> ' + dataProperty['check_total']['value'] + ' </span></p></div> </div> </div>');
                    var div60 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['net_check_total']['text'] + ': <span> ' + dataProperty['net_check_total']['value'] + ' </span></p></div> </div> </div>');
                    var div61 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['net_bond_amount']['text'] + ': <span> ' + dataProperty['net_bond_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div62 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['min_net_loan_total']['text'] + ': <span> ' + dataProperty['min_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div63 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['purchase_tax']['text'] + ': <span> ' + dataProperty['purchase_tax']['value'] + ' </span></p></div> </div> </div>');
                    var div64 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['purchase_tax_percentage']['text'] + ': <span> ' + dataProperty['purchase_tax_percentage']['value'] + ' </span></p></div> </div> </div>');
                    var div65 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['funding_months']['text'] + ': <span> ' + dataProperty['funding_months']['value'] + ' </span></p></div> </div> </div>');
                    var div66 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['funding_years']['text'] + ': <span> ' + dataProperty['funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div67 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['installment']['text'] + ': <span> ' + dataProperty['installment']['value'] + ' </span></p></div> </div> </div>');
                    var div68 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['support_installment']['text'] + ': <span> ' + dataProperty['support_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div69 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['installment_after_support']['text'] + ': <span> ' + dataProperty['installment_after_support']['value'] + ' </span></p></div> </div> </div>');
                    var div70 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['salary_deduction']['text'] + ': <span> ' + dataProperty['salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                    var div71 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['profit']['text'] + ': <span> ' + dataProperty['profit']['value'] + ' </span></p></div> </div> </div>');
                    var div72 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['profit_margin']['text'] + ': <span> ' + dataProperty['profit_margin']['value'] + ' </span></p></div> </div> </div>');
                    var div73 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['loan_total']['text'] + ': <span> ' + dataProperty['loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div74 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['net_loan_total']['text'] + ': <span> ' + dataProperty['net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div75 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['loan_total_profits']['text'] + ': <span> ' + dataProperty['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');
                    var div76 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataProperty['calculator_program']['text'] + ': <span> ' + dataProperty['calculator_program']['value'] + ' </span></p></div> </div> </div>');
                    var div77 = $(' <div class="contREsult"></div>');
                    if (propertySettings[0]['value_en'] === "bank_code" && propertySettings[0]['option_value'] === 1) {
                        div5.append(div10);
                    }
                    if (propertySettings[1]['value_en'] === "bank_name" && propertySettings[1]['option_value'] === 1) {
                        div5.append(div11);
                    }
                    if (propertySettings[2]['value_en'] === "salary_bank_code" && propertySettings[2]['option_value'] === 1) {
                        div5.append(div12);
                    }
                    if (propertySettings[3]['value_en'] === "salary_bank_name" && propertySettings[3]['option_value'] === 1) {
                        div5.append(div13);
                    }
                    if (propertySettings[4]['value_en'] === "is_salary_transfer" && propertySettings[4]['option_value'] === 1) {
                        div5.append(div14);
                    }
                    if (propertySettings[5]['value_en'] === "job_position_code" && propertySettings[5]['option_value'] === 1) {
                        div5.append(div15);
                    }
                    if (propertySettings[6]['value_en'] === "job_position_name" && propertySettings[6]['option_value'] === 1) {
                        div5.append(div16);
                    }
                    if (propertySettings[7]['value_en'] === "age" && propertySettings[7]['option_value'] === 1) {
                        div5.append(div17);
                    }
                    if (propertySettings[8]['value_en'] === "age_by_months" && propertySettings[8]['option_value'] === 1) {
                        div5.append(div18);
                    }
                    if (propertySettings[9]['value_en'] === "retirement_age" && propertySettings[9]['option_value'] === 1) {
                        div5.append(div19);
                    }
                    if (propertySettings[10]['value_en'] === "funding_age_limit" && propertySettings[10]['option_value'] === 1) {
                        div5.append(div20);
                    }
                    if (propertySettings[11]['value_en'] === "extra_funding_years" && propertySettings[11]['option_value'] === 1) {
                        div5.append(div21);
                    }
                    if (propertySettings[12]['value_en'] === "salary" && propertySettings[12]['option_value'] === 1) {
                        div5.append(div22);
                    }
                    if (propertySettings[13]['value_en'] === "basic_salary" && propertySettings[13]['option_value'] === 1) {
                        div5.append(div23);
                    }
                    if (propertySettings[14]['value_en'] === "early_repayment" && propertySettings[14]['option_value'] === 1) {
                        div5.append(div24);
                    }
                    if (propertySettings[15]['value_en'] === "quest_check" && propertySettings[15]['option_value'] === 1) {
                        div5.append(div25);
                    }
                    if (propertySettings[16]['value_en'] === "bear_tax" && propertySettings[16]['option_value'] === 1) {
                        div5.append(div26);
                    }
                    if (propertySettings[17]['value_en'] === "product_type_id" && propertySettings[17]['option_value'] === 1) {
                        div5.append(div27);
                    }
                    if (propertySettings[18]['value_en'] === "housing_allowance" && propertySettings[18]['option_value'] === 1) {
                        div5.append(div28);
                    }
                    if (propertySettings[19]['value_en'] === "transfer_allowance" && propertySettings[19]['option_value'] === 1) {
                        div5.append(div29);
                    }
                    if (propertySettings[20]['value_en'] === "other_allowance" && propertySettings[20]['option_value'] === 1) {
                        div5.append(div30);
                    }
                    if (propertySettings[21]['value_en'] === "retirement_income" && propertySettings[21]['option_value'] === 1) {
                        div5.append(div31);
                    }
                    if (propertySettings[22]['value_en'] === "job_tenure_months" && propertySettings[22]['option_value'] === 1) {
                        div5.append(div32);
                    }
                    if (propertySettings[23]['value_en'] === "job_tenure_years" && propertySettings[23]['option_value'] === 1) {
                        div5.append(div33);
                    }
                    if (propertySettings[24]['value_en'] === "first_batch_mode" && propertySettings[24]['option_value'] === 1) {
                        div5.append(div34);
                    }
                    if (propertySettings[25]['value_en'] === "credit_installment" && propertySettings[25]['option_value'] === 1) {
                        div5.append(div35);
                    }
                    if (propertySettings[26]['value_en'] === "obligations_installment" && propertySettings[26]['option_value'] === 1) {
                        div5.append(div36);
                    }
                    if (propertySettings[27]['value_en'] === "remaining_obligations_months" && propertySettings[27]['option_value'] === 1) {
                        div5.append(div37);
                    }
                    if (propertySettings[28]['value_en'] === "remaining_retirement_months" && propertySettings[28]['option_value'] === 1) {
                        div5.append(div38);
                    }
                    if (propertySettings[29]['value_en'] === "remaining_retirement_years" && propertySettings[29]['option_value'] === 1) {
                        div5.append(div39);
                    }
                    if (propertySettings[30]['value_en'] === "residential_support" && propertySettings[30]['option_value'] === 1) {
                        div5.append(div40);
                    }
                    if (propertySettings[31]['value_en'] === "add_support_installment_to_salary" && propertySettings[31]['option_value'] === 1) {
                        div5.append(div41);
                    }
                    if (propertySettings[32]['value_en'] === "add_support_installment_to_installment" && propertySettings[32]['option_value'] === 1) {
                        div5.append(div42);
                    }
                    if (propertySettings[33]['value_en'] === "guarantees" && propertySettings[33]['option_value'] === 1) {
                        div5.append(div43);
                    }
                    if (propertySettings[34]['value_en'] === "provide_first_batch" && propertySettings[34]['option_value'] === 1) {
                        div5.append(div44);
                    }
                    if (propertySettings[35]['value_en'] === "secured" && propertySettings[35]['option_value'] === 1) {
                        div5.append(div45);
                    }
                    if (propertySettings[36]['value_en'] === "property_amount" && propertySettings[36]['option_value'] === 1) {
                        div5.append(div46);
                    }
                    if (propertySettings[37]['value_en'] === "is_property_completed" && propertySettings[37]['option_value'] === 1) {
                        div5.append(div47);
                    }
                    if (propertySettings[38]['value_en'] === "residence_type" && propertySettings[38]['option_value'] === 1) {
                        div5.append(div48);
                    }
                    if (propertySettings[39]['value_en'] === "first_batch_percentage" && propertySettings[39]['option_value'] === 1) {
                        div5.append(div49);
                    }
                    if (propertySettings[40]['value_en'] === "first_batch" && propertySettings[40]['option_value'] === 1) {
                        div5.append(div50);
                    }
                    if (propertySettings[41]['value_en'] === "first_batch_profit" && propertySettings[41]['option_value'] === 1) {
                        div5.append(div51);
                    }
                    if (propertySettings[42]['value_en'] === "first_batch_profit_amount" && propertySettings[42]['option_value'] === 1) {
                        div5.append(div52);
                    }
                    if (propertySettings[43]['value_en'] === "quest" && propertySettings[43]['option_value'] === 1) {
                        div5.append(div53);
                    }
                    if (propertySettings[44]['value_en'] === "quest_amount" && propertySettings[44]['option_value'] === 1) {
                        div5.append(div54);
                    }
                    if (propertySettings[45]['value_en'] === "quest_amount_with_vat" && propertySettings[45]['option_value'] === 1) {
                        div5.append(div55);
                    }
                    if (propertySettings[46]['value_en'] === "fees" && propertySettings[46]['option_value'] === 1) {
                        div5.append(div56);
                    }
                    if (propertySettings[47]['value_en'] === "discount" && propertySettings[47]['option_value'] === 1) {
                        div5.append(div57);
                    }
                    if (propertySettings[48]['value_en'] === "vat" && propertySettings[48]['option_value'] === 1) {
                        div5.append(div58);
                    }
                    if (propertySettings[49]['value_en'] === "check_total" && propertySettings[49]['option_value'] === 1) {
                        div5.append(div59);
                    }
                    if (propertySettings[50]['value_en'] === "net_check_total" && propertySettings[50]['option_value'] === 1) {
                        div5.append(div60);
                    }
                    if (propertySettings[51]['value_en'] === "net_bond_amount" && propertySettings[51]['option_value'] === 1) {
                        div5.append(div61);
                    }
                    if (propertySettings[52]['value_en'] === "min_net_loan_total" && propertySettings[52]['option_value'] === 1) {
                        div5.append(div62);
                    }
                    if (propertySettings[53]['value_en'] === "purchase_tax" && propertySettings[53]['option_value'] === 1) {
                        div5.append(div63);
                    }
                    if (propertySettings[54]['value_en'] === "purchase_tax_percentage" && propertySettings[54]['option_value'] === 1) {
                        div5.append(div64);
                    }
                    if (propertySettings[55]['value_en'] === "funding_months" && propertySettings[55]['option_value'] === 1) {
                        div5.append(div65);
                    }
                    if (propertySettings[56]['value_en'] === "funding_years" && propertySettings[56]['option_value'] === 1) {
                        div5.append(div66);
                    }
                    if (propertySettings[57]['value_en'] === "installment" && propertySettings[57]['option_value'] === 1) {
                        div5.append(div67);
                    }
                    if (propertySettings[58]['value_en'] === "support_installment" && propertySettings[58]['option_value'] === 1) {
                        div5.append(div68);
                    }
                    if (propertySettings[59]['value_en'] === "installment_after_support" && propertySettings[59]['option_value'] === 1) {
                        div5.append(div69);
                    }
                    if (propertySettings[60]['value_en'] === "salary_deduction" && propertySettings[60]['option_value'] === 1) {
                        div5.append(div70);
                    }
                    if (propertySettings[61]['value_en'] === "profit" && propertySettings[61]['option_value'] === 1) {
                        div5.append(div71);
                    }
                    if (propertySettings[62]['value_en'] === "profit_margin" && propertySettings[62]['option_value'] === 1) {
                        div5.append(div72);
                    }
                    if (propertySettings[63]['value_en'] === "loan_total" && propertySettings[63]['option_value'] === 1) {
                        div5.append(div73);
                    }
                    if (propertySettings[64]['value_en'] === "net_loan_total" && propertySettings[64]['option_value'] === 1) {
                        div5.append(div74);
                    }
                    if (propertySettings[65]['value_en'] === "loan_total_profits" && propertySettings[65]['option_value'] === 1) {
                        div5.append(div75);
                    }
                    if (propertySettings[66]['value_en'] === "calculator_program" && propertySettings[66]['option_value'] === 1) {
                        div5.append(div76);
                    }
                    div5.append(div77);


                    div4.html(div5);
                    content.append(typeProperty);
                    content.append(div4);
                    body.html(content);

                    div1.append(body);


                }
                // if (data[i]['joint_programs']['flexibleProgram'] !== null && data[i]['joint_programs']['flexibleProgram'] !== '') {
                if (data[i].joint_programs.flexibleProgram) {
                    console.log(123)
                    var body = $(' <div class="row mx-1 py-1 contREsultRow"></div>');
                    var content = $(' <div class="col-12 mb-md-0"></div>');
                    var typeFlixable = $(' <div class="toggleBankResult d-flex justify-content-between align-items-center flex-wrap"> <p> مرن 2 * 1 </p> <i class="fas fa-chevron-down"></i ></div>');
                    var div4 = $(' <div class="contREsult"></div>');
                    var div5 = $(' <div class="row"></div>');
                    var dataFlixable = data[i]['joint_programs']['flexibleProgram']['raw'];
                    //Flixable DETAILS
                    var div7 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['net_loan_total']['text'] + ': <span> ' + dataFlixable['net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div8 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" > <p> ' + dataFlixable['personal_net_loan_total']['text'] + ': <span>' + dataFlixable['personal_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div9 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['flexible_loan_total']['text'] + ': <span> ' + dataFlixable['flexible_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div10 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['installment']['text'] + ': <span> ' + dataFlixable['installment']['value'] + ' </span></p></div> </div> </div>');
                    var div11 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['installment_after_support']['text'] + ': <span> ' + dataFlixable['installment_after_support']['value'] + ' </span></p></div> </div></div>');
                    var div12 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['support_installment']['text'] + ': <span> ' + dataFlixable['support_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div13 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['funding_years']['text'] + ': <span>' + dataFlixable['funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div14 = $('  <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['check_total']['text'] + ': <span> ' + dataFlixable['check_total']['value'] + ' </span></p></div> </div> </div>');
                    var div15 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['purchase_tax']['text'] + ': <span> ' + dataFlixable['purchase_tax']['value'] + ' </span></p></div> </div> </div>');
                    var div16 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['flexible_installment']['text'] + ': <span> ' + dataFlixable['flexible_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div17 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['property_flexible_installment']['text'] + ': <span> ' + dataFlixable['property_flexible_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div18 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['bank_code']['text'] + ': <span> ' + dataFlixable['bank_code']['value'] + ' </span></p></div> </div> </div>');
                    var div19 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['bank_name']['text'] + ': <span> ' + dataFlixable['bank_name']['value'] + ' </span></p></div> </div> </div>');
                    var div20 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary_bank_code']['text'] + ': <span> ' + dataFlixable['salary_bank_code']['value'] + ' </span></p></div> </div> </div>');
                    var div21 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary_bank_name']['text'] + ': <span> ' + dataFlixable['salary_bank_name']['value'] + ' </span></p></div> </div> </div>');
                    var div22 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['is_salary_transfer']['text'] + ': <span> ' + dataFlixable['is_salary_transfer']['value'] + ' </span></p></div> </div> </div>');
                    var div23 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_position_code']['text'] + ': <span> ' + dataFlixable['job_position_code']['value'] + ' </span></p></div> </div> </div>');
                    var div24 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_position_name']['text'] + ': <span> ' + dataFlixable['job_position_name']['value'] + ' </span></p></div> </div> </div>');
                    var div25 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['age']['text'] + ': <span> ' + dataFlixable['age']['value'] + ' </span></p></div> </div> </div>');
                    var div26 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['age_by_months']['text'] + ': <span> ' + dataFlixable['age_by_months']['value'] + ' </span></p></div> </div> </div>');
                    var div27 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['retirement_age']['text'] + ': <span> ' + dataFlixable['retirement_age']['value'] + ' </span></p></div> </div> </div>');
                    var div28 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['funding_age_limit']['text'] + ': <span> ' + dataFlixable['funding_age_limit']['value'] + ' </span></p></div> </div> </div>');
                    var div29 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['extra_funding_years']['text'] + ': <span> ' + dataFlixable['extra_funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div30 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary']['text'] + ': <span> ' + dataFlixable['salary']['value'] + ' </span></p></div> </div> </div>');
                    var div31 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['basic_salary_with_insurance_percentage']['text'] + ': <span> ' + dataFlixable['basic_salary_with_insurance_percentage']['value'] + ' </span></p></div> </div> </div>');
                    var div32 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['basic_salary']['text'] + ': <span> ' + dataFlixable['basic_salary']['value'] + ' </span></p></div> </div> </div>');
                    var div33 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['early_repayment']['text'] + ': <span> ' + dataFlixable['early_repayment']['value'] + ' </span></p></div> </div> </div>');
                    var div34 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest_check']['text'] + ': <span> ' + dataFlixable['quest_check']['value'] + ' </span></p></div> </div> </div>');
                    var div35 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['bear_tax']['text'] + ': <span> ' + dataFlixable['bear_tax']['value'] + ' </span></p></div> </div> </div>');
                    var div36 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['product_type_id']['text'] + ': <span> ' + dataFlixable['product_type_id']['value'] + ' </span></p></div> </div> </div>');
                    var div37 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['housing_allowance']['text'] + ': <span> ' + dataFlixable['housing_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div38 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['transfer_allowance']['text'] + ': <span> ' + dataFlixable['transfer_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div39 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['other_allowance']['text'] + ': <span> ' + dataFlixable['other_allowance']['value'] + ' </span></p></div> </div> </div>');
                    var div40 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['retirement_income']['text'] + ': <span> ' + dataFlixable['retirement_income']['value'] + ' </span></p></div> </div> </div>');
                    var div41 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_tenure_months']['text'] + ': <span> ' + dataFlixable['job_tenure_months']['value'] + ' </span></p></div> </div> </div>');
                    var div42 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['job_tenure_years']['text'] + ': <span> ' + dataFlixable['job_tenure_years']['value'] + ' </span></p></div> </div> </div>');
                    var div43 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_mode']['text'] + ': <span> ' + dataFlixable['first_batch_mode']['value'] + ' </span></p></div> </div> </div>');
                    var div44 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['credit_installment']['text'] + ': <span> ' + dataFlixable['credit_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div45 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['obligations_installment']['text'] + ': <span> ' + dataFlixable['obligations_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div46 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['remaining_obligations_months']['text'] + ': <span> ' + dataFlixable['remaining_obligations_months']['value'] + ' </span></p></div> </div> </div>');
                    var div47 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['remaining_retirement_months']['text'] + ': <span> ' + dataFlixable['remaining_retirement_months']['value'] + ' </span></p></div> </div> </div>');
                    var div48 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['remaining_retirement_years']['text'] + ': <span> ' + dataFlixable['remaining_retirement_years']['value'] + ' </span></p></div> </div> </div>');
                    var div49 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_funding_years']['text'] + ': <span> ' + dataFlixable['personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div50 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_funding_months']['text'] + ': <span> ' + dataFlixable['personal_funding_months']['value'] + ' </span></p></div> </div> </div>');
                    var div51 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_installment']['text'] + ': <span> ' + dataFlixable['personal_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div52 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_salary_deduction']['text'] + ': <span> ' + dataFlixable['personal_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                    var div53 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_profit']['text'] + ': <span> ' + dataFlixable['personal_profit']['value'] + ' </span></p></div> </div> </div>');
                    var div54 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_profit_margin']['text'] + ': <span> ' + dataFlixable['personal_profit_margin']['value'] + ' </span></p></div> </div> </div>');
                    var div55 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['personal_loan_total']['text'] + ': <span> ' + dataFlixable['personal_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div56 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['loan_total_profits']['text'] + ': <span> ' + dataFlixable['loan_total_profits']['value'] + ' </span></p></div> </div> </div>');
                    var div57 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['calculator_program']['text'] + ': <span> ' + dataFlixable['calculator_program']['value'] + ' </span></p></div> </div> </div>');
                    var div58 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['extra_personal_funding_years']['text'] + ': <span> ' + dataFlixable['extra_personal_funding_years']['value'] + ' </span></p></div> </div> </div>');
                    var div59 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['residential_support']['text'] + ': <span> ' + dataFlixable['residential_support']['value'] + ' </span></p></div> </div> </div>');
                    var div60 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['add_support_installment_to_salary']['text'] + ': <span> ' + dataFlixable['add_support_installment_to_salary']['value'] + ' </span></p></div> </div> </div>');
                    var div61 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['add_support_installment_to_installment']['text'] + ': <span> ' + dataFlixable['add_support_installment_to_installment']['value'] + ' </span></p></div> </div> </div>');
                    var div62 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['guarantees']['text'] + ': <span> ' + dataFlixable['guarantees']['value'] + ' </span></p></div> </div> </div>');
                    var div63 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['provide_first_batch']['text'] + ': <span> ' + dataFlixable['provide_first_batch']['value'] + ' </span></p></div> </div> </div>');
                    var div64 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['secured']['text'] + ': <span> ' + dataFlixable['secured']['value'] + ' </span></p></div> </div> </div>');
                    var div65 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['property_amount']['text'] + ': <span> ' + dataFlixable['property_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div66 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['is_property_completed']['text'] + ': <span> ' + dataFlixable['is_property_completed']['value'] + ' </span></p></div> </div> </div>');
                    var div67 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['residence_type']['text'] + ': <span> ' + dataFlixable['residence_type']['value'] + ' </span></p></div> </div> </div>');
                    var div68 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_percentage']['text'] + ': <span> ' + dataFlixable['first_batch_percentage']['value'] + ' </span></p></div> </div> </div>');
                    var div69 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch']['text'] + ': <span> ' + dataFlixable['first_batch']['value'] + ' </span></p></div> </div> </div>');
                    var div70 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_profit']['text'] + ': <span> ' + dataFlixable['first_batch_profit']['value'] + ' </span></p></div> </div> </div>');
                    var div71 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['first_batch_profit_amount']['text'] + ': <span> ' + dataFlixable['first_batch_profit_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div72 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest']['text'] + ': <span> ' + dataFlixable['quest']['value'] + ' </span></p></div> </div> </div>');
                    var div73 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest_amount']['text'] + ': <span> ' + dataFlixable['quest_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div74 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['quest_amount_with_vat']['text'] + ': <span> ' + dataFlixable['quest_amount_with_vat']['value'] + ' </span></p></div> </div> </div>');
                    var div75 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['fees']['text'] + ': <span> ' + dataFlixable['fees']['value'] + ' </span></p></div> </div> </div>');
                    var div76 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['discount']['text'] + ': <span> ' + dataFlixable['discount']['value'] + ' </span></p></div> </div> </div>');
                    var div77 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['vat']['text'] + ': <span> ' + dataFlixable['vat']['value'] + ' </span></p></div> </div> </div>');
                    var div78 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['net_check_total']['text'] + ': <span> ' + dataFlixable['net_check_total']['value'] + ' </span></p></div> </div> </div>');
                    var div79 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['net_bond_amount']['text'] + ': <span> ' + dataFlixable['net_bond_amount']['value'] + ' </span></p></div> </div> </div>');
                    var div80 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['min_net_loan_total']['text'] + ': <span> ' + dataFlixable['min_net_loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div81 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['purchase_tax_percentage']['text'] + ': <span> ' + dataFlixable['purchase_tax_percentage']['value'] + ' </span></p></div> </div> </div>');
                    var div82 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['funding_months']['text'] + ': <span> ' + dataFlixable['funding_months']['value'] + ' </span></p></div> </div> </div>');
                    var div83 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['salary_deduction']['text'] + ': <span> ' + dataFlixable['salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                    var div84 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['profit']['text'] + ': <span> ' + dataFlixable['profit']['value'] + ' </span></p></div> </div> </div>');
                    var div85 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['profit_margin']['text'] + ': <span> ' + dataFlixable['profit_margin']['value'] + ' </span></p></div> </div> </div>');
                    var div86 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['loan_total']['text'] + ': <span> ' + dataFlixable['loan_total']['value'] + ' </span></p></div> </div> </div>');
                    var div87 = $(' <div class=" col-lg-4 col-md-6 "> <div class = "BankMineResult" ><div class = "singleTypeResult" ><p> ' + dataFlixable['flexible_salary_deduction']['text'] + ': <span> ' + dataFlixable['flexible_salary_deduction']['value'] + ' </span></p></div> </div> </div>');
                    var div88 = $(' <div class="contREsult"></div>');
                    if (flexibleSettings[76]['value_en'] === "net_loan_total" && flexibleSettings[76]['option_value'] === 1) {
                        div5.append(div7);
                    }
                    if (flexibleSettings[38]['value_en'] === "personal_net_loan_total" && flexibleSettings[38]['option_value'] === 1) {
                        div5.append(div8);
                    }
                    if (flexibleSettings[79]['value_en'] === "flexible_loan_total" && flexibleSettings[79]['option_value'] === 1) {
                        div5.append(div9);
                    }
                    if (flexibleSettings[69]['value_en'] === "installment" && flexibleSettings[69]['option_value'] === 1) {
                        div5.append(div10);
                    }
                    if (flexibleSettings[71]['value_en'] === "installment_after_support" && flexibleSettings[71]['option_value'] === 1) {
                        div5.append(div11);
                    }
                    if (flexibleSettings[70]['value_en'] === "support_installment" && flexibleSettings[70]['option_value'] === 1) {
                        div5.append(div12);
                    }
                    if (flexibleSettings[68]['value_en'] === "funding_years" && flexibleSettings[68]['option_value'] === 1) {
                        div5.append(div13);
                    }
                    if (flexibleSettings[61]['value_en'] === "check_total" && flexibleSettings[61]['option_value'] === 1) {
                        div5.append(div14);
                    }
                    if (flexibleSettings[65]['value_en'] === "purchase_tax" && flexibleSettings[65]['option_value'] === 1) {
                        div5.append(div15);
                    }
                    if (flexibleSettings[77]['value_en'] === "flexible_installment" && flexibleSettings[77]['option_value'] === 1) {
                        div5.append(div16);
                    }
                    if (flexibleSettings[78]['value_en'] === "property_flexible_installment" && flexibleSettings[78]['option_value'] === 1) {
                        div5.append(div17);
                    }
                    if (flexibleSettings[0]['value_en'] === "bank_code" && flexibleSettings[0]['option_value'] === 1) {
                        div5.append(div18);
                    }
                    if (flexibleSettings[1]['value_en'] === "bank_name" && flexibleSettings[1]['option_value'] === 1) {
                        div5.append(div19);
                    }
                    if (flexibleSettings[2]['value_en'] === "salary_bank_code" && flexibleSettings[2]['option_value'] === 1) {
                        div5.append(div20);
                    }
                    if (flexibleSettings[3]['value_en'] === "salary_bank_name" && flexibleSettings[3]['option_value'] === 1) {
                        div5.append(div21);
                    }
                    if (flexibleSettings[4]['value_en'] === "is_salary_transfer" && flexibleSettings[4]['option_value'] === 1) {
                        div5.append(div22);
                    }
                    if (flexibleSettings[5]['value_en'] === "job_position_code" && flexibleSettings[5]['option_value'] === 1) {
                        div5.append(div23);
                    }
                    if (flexibleSettings[6]['value_en'] === "job_position_name" && flexibleSettings[6]['option_value'] === 1) {
                        div5.append(div24);
                    }
                    if (flexibleSettings[7]['value_en'] === "age" && flexibleSettings[7]['option_value'] === 1) {
                        div5.append(div25);
                    }
                    if (flexibleSettings[8]['value_en'] === "age_by_months" && flexibleSettings[8]['option_value'] === 1) {
                        div5.append(div26);
                    }
                    if (flexibleSettings[9]['value_en'] === "retirement_age" && flexibleSettings[9]['option_value'] === 1) {
                        div5.append(div27);
                    }
                    if (flexibleSettings[10]['value_en'] === "funding_age_limit" && flexibleSettings[10]['option_value'] === 1) {
                        div5.append(div28);
                    }
                    if (flexibleSettings[11]['value_en'] === "extra_funding_years" && flexibleSettings[11]['option_value'] === 1) {
                        div5.append(div29);
                    }
                    if (flexibleSettings[12]['value_en'] === "salary" && flexibleSettings[12]['option_value'] === 1) {
                        div5.append(div30);
                    }
                    if (flexibleSettings[13]['value_en'] === "basic_salary_with_insurance_percentage" && flexibleSettings[13]['option_value'] === 1) {
                        div5.append(div31);
                    }
                    if (flexibleSettings[14]['value_en'] === "basic_salary" && flexibleSettings[14]['option_value'] === 1) {
                        div5.append(div32);
                    }
                    if (flexibleSettings[15]['value_en'] === "early_repayment" && flexibleSettings[15]['option_value'] === 1) {
                        div5.append(div33);
                    }
                    if (flexibleSettings[16]['value_en'] === "quest_check" && flexibleSettings[16]['option_value'] === 1) {
                        div5.append(div34);
                    }
                    if (flexibleSettings[17]['value_en'] === "bear_tax" && flexibleSettings[17]['option_value'] === 1) {
                        div5.append(div35);
                    }
                    if (flexibleSettings[18]['value_en'] === "product_type_id" && flexibleSettings[18]['option_value'] === 1) {
                        div5.append(div36);
                    }
                    if (flexibleSettings[19]['value_en'] === "housing_allowance" && flexibleSettings[19]['option_value'] === 1) {
                        div5.append(div37);
                    }
                    if (flexibleSettings[20]['value_en'] === "transfer_allowance" && flexibleSettings[20]['option_value'] === 1) {
                        div5.append(div38);
                    }
                    if (flexibleSettings[21]['value_en'] === "other_allowance" && flexibleSettings[21]['option_value'] === 1) {
                        div5.append(div39);
                    }
                    if (flexibleSettings[22]['value_en'] === "retirement_income" && flexibleSettings[22]['option_value'] === 1) {
                        div5.append(div40);
                    }
                    if (flexibleSettings[23]['value_en'] === "job_tenure_months" && flexibleSettings[23]['option_value'] === 1) {
                        div5.append(div41);
                    }
                    if (flexibleSettings[24]['value_en'] === "job_tenure_years" && flexibleSettings[24]['option_value'] === 1) {
                        div5.append(div42);
                    }
                    if (flexibleSettings[25]['value_en'] === "first_batch_mode" && flexibleSettings[25]['option_value'] === 1) {
                        div5.append(div43);
                    }
                    if (flexibleSettings[26]['value_en'] === "credit_installment" && flexibleSettings[26]['option_value'] === 1) {
                        div5.append(div44);
                    }
                    if (flexibleSettings[27]['value_en'] === "obligations_installment" && flexibleSettings[27]['option_value'] === 1) {
                        div5.append(div45);
                    }
                    if (flexibleSettings[28]['value_en'] === "remaining_obligations_months" && flexibleSettings[28]['option_value'] === 1) {
                        div5.append(div46);
                    }
                    if (flexibleSettings[29]['value_en'] === "remaining_retirement_months" && flexibleSettings[29]['option_value'] === 1) {
                        div5.append(div47);
                    }
                    if (flexibleSettings[30]['value_en'] === "remaining_retirement_years" && flexibleSettings[30]['option_value'] === 1) {
                        div5.append(div48);
                    }
                    if (flexibleSettings[31]['value_en'] === "personal_funding_years" && flexibleSettings[31]['option_value'] === 1) {
                        div5.append(div49);
                    }
                    if (flexibleSettings[32]['value_en'] === "personal_funding_months" && flexibleSettings[32]['option_value'] === 1) {
                        div5.append(div50);
                    }
                    if (flexibleSettings[33]['value_en'] === "personal_installment" && flexibleSettings[33]['option_value'] === 1) {
                        div5.append(div51);
                    }
                    if (flexibleSettings[34]['value_en'] === "personal_salary_deduction" && flexibleSettings[34]['option_value'] === 1) {
                        div5.append(div52);
                    }
                    if (flexibleSettings[35]['value_en'] === "personal_profit" && flexibleSettings[35]['option_value'] === 1) {
                        div5.append(div53);
                    }
                    if (flexibleSettings[36]['value_en'] === "personal_profit_margin" && flexibleSettings[36]['option_value'] === 1) {
                        div5.append(div54);
                    }
                    if (flexibleSettings[37]['value_en'] === "personal_loan_total" && flexibleSettings[37]['option_value'] === 1) {
                        div5.append(div55);
                    }
                    if (flexibleSettings[39]['value_en'] === "loan_total_profits" && flexibleSettings[39]['option_value'] === 1) {
                        div5.append(div56);
                    }
                    if (flexibleSettings[40]['value_en'] === "calculator_program" && flexibleSettings[40]['option_value'] === 1) {
                        div5.append(div57);
                    }
                    if (flexibleSettings[41]['value_en'] === "extra_personal_funding_years" && flexibleSettings[41]['option_value'] === 1) {
                        div5.append(div58);
                    }
                    if (flexibleSettings[42]['value_en'] === "residential_support" && flexibleSettings[42]['option_value'] === 1) {
                        div5.append(div59);
                    }
                    if (flexibleSettings[43]['value_en'] === "add_support_installment_to_salary" && flexibleSettings[43]['option_value'] === 1) {
                        div5.append(div60);
                    }
                    if (flexibleSettings[44]['value_en'] === "add_support_installment_to_installment" && flexibleSettings[44]['option_value'] === 1) {
                        div5.append(div61);
                    }
                    if (flexibleSettings[45]['value_en'] === "guarantees" && flexibleSettings[45]['option_value'] === 1) {
                        div5.append(div62);
                    }
                    if (flexibleSettings[46]['value_en'] === "provide_first_batch" && flexibleSettings[46]['option_value'] === 1) {
                        div5.append(div63);
                    }
                    if (flexibleSettings[47]['value_en'] === "secured" && flexibleSettings[47]['option_value'] === 1) {
                        div5.append(div64);
                    }
                    if (flexibleSettings[48]['value_en'] === "property_amount" && flexibleSettings[48]['option_value'] === 1) {
                        div5.append(div65);
                    }
                    if (flexibleSettings[49]['value_en'] === "is_property_completed" && flexibleSettings[49]['option_value'] === 1) {
                        div5.append(div66);
                    }
                    if (flexibleSettings[50]['value_en'] === "residence_type" && flexibleSettings[50]['option_value'] === 1) {
                        div5.append(div67);
                    }
                    if (flexibleSettings[51]['value_en'] === "first_batch_percentage" && flexibleSettings[51]['option_value'] === 1) {
                        div5.append(div68);
                    }
                    if (flexibleSettings[52]['value_en'] === "first_batch" && flexibleSettings[52]['option_value'] === 1) {
                        div5.append(div69);
                    }
                    if (flexibleSettings[53]['value_en'] === "first_batch_profit" && flexibleSettings[53]['option_value'] === 1) {
                        div5.append(div70);
                    }
                    if (flexibleSettings[54]['value_en'] === "first_batch_profit_amount" && flexibleSettings[54]['option_value'] === 1) {
                        div5.append(div71);
                    }
                    if (flexibleSettings[55]['value_en'] === "quest" && flexibleSettings[55]['option_value'] === 1) {
                        div5.append(div72);
                    }
                    if (flexibleSettings[56]['value_en'] === "quest_amount" && flexibleSettings[56]['option_value'] === 1) {
                        div5.append(div73);
                    }
                    if (flexibleSettings[57]['value_en'] === "quest_amount_with_vat" && flexibleSettings[57]['option_value'] === 1) {
                        div5.append(div74);
                    }
                    if (flexibleSettings[58]['value_en'] === "fees" && flexibleSettings[58]['option_value'] === 1) {
                        div5.append(div75);
                    }
                    if (flexibleSettings[59]['value_en'] === "discount" && flexibleSettings[59]['option_value'] === 1) {
                        div5.append(div76);
                    }
                    if (flexibleSettings[60]['value_en'] === "vat" && flexibleSettings[60]['option_value'] === 1) {
                        div5.append(div77);
                    }
                    if (flexibleSettings[62]['value_en'] === "net_check_total" && flexibleSettings[62]['option_value'] === 1) {
                        div5.append(div78);
                    }
                    if (flexibleSettings[63]['value_en'] === "net_bond_amount" && flexibleSettings[63]['option_value'] === 1) {
                        div5.append(div79);
                    }
                    if (flexibleSettings[64]['value_en'] === "min_net_loan_total" && flexibleSettings[64]['option_value'] === 1) {
                        div5.append(div80);
                    }
                    if (flexibleSettings[66]['value_en'] === "purchase_tax_percentage" && flexibleSettings[66]['option_value'] === 1) {
                        div5.append(div81);
                    }
                    if (flexibleSettings[67]['value_en'] === "funding_months" && flexibleSettings[67]['option_value'] === 1) {
                        div5.append(div82);
                    }
                    if (flexibleSettings[72]['value_en'] === "salary_deduction" && flexibleSettings[72]['option_value'] === 1) {
                        div5.append(div83);
                    }
                    if (flexibleSettings[73]['value_en'] === "profit" && flexibleSettings[73]['option_value'] === 1) {
                        div5.append(div84);
                    }
                    if (flexibleSettings[74]['value_en'] === "profit_margin" && flexibleSettings[74]['option_value'] === 1) {
                        div5.append(div85);
                    }
                    if (flexibleSettings[75]['value_en'] === "loan_total" && flexibleSettings[75]['option_value'] === 1) {
                        div5.append(div86);
                    }
                    if (flexibleSettings[80]['value_en'] === "flexible_salary_deduction" && flexibleSettings[80]['option_value'] === 1) {
                        div5.append(div87);
                    }
                    //Append
                    div5.append(div88);
                    div4.append(div5);
                    content.append(typeFlixable);
                    content.append(div4);
                    body.append(content);
                    div1.append(body);
                }
            }
            main.append(div1);
            $("#calResultDetails").append(main);
        }
    }
</script>

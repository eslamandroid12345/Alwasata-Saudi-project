<div class="userFormsInfo " id="fundingCalculaterResult">
    <div class="userFormsContainer mb-3">


       <!-- Loading div -->
       <div class="loading"><img src="{{ url('assest/images/loadingLogo.png') }}" alt=""> </div>
        <!-- End : Loading div -->

        <div class="dataFrom topRow ">
            <div class="dataFromHeader">
                <div id="result" class="result">
                    <button class="w-100">

                        <i class="fas fa-calculator  "></i>
                        نتائج حاسبة التمويل

                    </button>
                </div>
            </div>
            <div class="userFormsResult  mt-3">

                <div class="row">
                    <div class="col-12">
                        <h5 class="my-2 resultHeader">النتائج:</h5>
                        <p class="my-2 text-danger" id="hint-extension-support-installment">قسط الدعم ينتهي عند 20
                            سنة</p>
                        <ul class="resultOptions list-unstyled d-flex flex-wrab align-items-center justify-content-center topRow" id="calResultList">
                        </ul>

                        <div class="result-body" id="calResultDetails">
                        <input type="hidden" id="calculateResultArray" name="resultArray[]">
                        </div>

                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

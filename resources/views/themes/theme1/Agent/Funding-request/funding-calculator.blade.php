  <!-- begin::portlet  -->
  <div class="portlet">
    <!-- begin::portlet__head  -->
    <div class="portlet__head">
      <!-- begin::portlet__head-label  -->
      <div class="portlet__head-label">التمويل المرن</div>
      <a class="btn btn-primary" href={{ route('calculater.calculaterHistory',['id'=>$id])}} target="_blank">سجل حاسبة التمويل </a>
    </div>
    <!-- end::portlet__head  -->
    <!-- begin::portlet__body  -->
    <div class="portlet__body pt-0">
      <div class="border rounded-5 p-3">
        <div class="row">

            <div class="col-lg-12   mb-md-0">
            @include('FundingCalculater.new_caculater')
            </div>
        </div>
      </div>
    </div>
    <!-- end::portlet__body  -->
  </div>
  <!-- end::portlet  -->

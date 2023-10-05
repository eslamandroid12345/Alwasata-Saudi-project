
{{-- For Search style   --}}
<div class="topRow" >
    <form name="filter" id="filter" method="get"  @if ($manager_role == 7 || $manager_role == 4) action="{{ route('movedRequestChartR') }}" @elseif ($manager_role == 1) action="{{ route('sales.manager.movedRequestChartRForSalesManager') }}" @endif >
        <div class="row align-items-center text-center text-md-left">
            <div class="col-4">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                <input class="form-control" type="date" name="startdate" value="{{ app('request')->input('startdate') }}">

            </div>
            <div class="col-4">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                <input class="form-control" type="date" name="enddate" value="{{ app('request')->input('enddate') }}">

            </div>
            <div class="col-4">
                <label class="label"> الحالة  </label>
                <select class="form-control" name="status_user" id="status_user" style="height: 38px">
                    <option value="2" {{request('status_user') == 2 ? 'selected' : ''}}>الكل</option>
                    <option value="0" {{request('status_user') == 0 ? 'selected' : ''}}>إستشاري مؤرشف</option>
                    <option value="1" {{request('status_user') == 1 ? 'selected' : ''}}>إستشاري نشط</option>
                </select>
            </div>

            <br>

            @if ($manager_role != 1)
                <div class="col-12">

                        <label class="label">اسم المدير </label>
                        <br>
                        <div class="rs-select2 js-select-simple select--no-search">
                            <select class="form-control" name="manager_id[]" multiple required id="manager_id" style="width:100%;">
                                <option value="allManager" {{in_array("allManager",$manager_ids) ? "selected" :""}}>الكل</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}" {{in_array($manager->id,$manager_ids) ? "selected" :""}}>{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            <div class="select-dropdown"></div>
                        </div>

                </div>
            @endif

            <br>
            @if ($manager_role == 1)
                <div class="col-12">
                        <label class="label">إسم المستشار </label>
                        <br>
                        <div class="rs-select2 js-select-simple select--no-search">
                            <select class="form-control" name="adviser_id[]" multiple id="adviser_id" style="width:100%;">
                                <option value="0">الكل</option>

                                @foreach($advisers as $adviser)

                                    <option value="{{ $adviser->id }}" {{in_array($adviser->id,$adviser_ids) ? "selected" :""}}>{{ $adviser->name }}</option>

                                @endforeach

                            </select>
                            <div class="select-dropdown"></div>
                        </div>

                </div>
            @elseif ($manager_role == 7 || $manager_role == 4 )
                <div class="col-12">

                        <label class="label">إسم المستشار </label>
                        <div class="rs-select2 js-select-simple select--no-search">
                            <select class="form-control"  name="adviser_id[]" multiple id="adviser_id">
                                <option value="0">الكل</option>

                            </select>
                            <div class="select-dropdown"></div>
                        </div>

                </div>
            @endif

        </div>

        <div class="searchSub text-center d-block col-12">
            <div class="tableUserOption flex-wrap justify-content-md-end  justify-content-center downOrder text-center padding-top-15" style="display: block">
                <div class="addBtn  mt-lg-0 mt-3 orderBtns text-center">
                    <button class="text-center mr-3 green item"  name="submit" id="submit" type="submit" >
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

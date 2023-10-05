{{-- For Search style   --}}
<div class="topRow" >
    <form method="POST" id="frm-update">
        @csrf
        <div class="row align-items-center text-center text-md-left">
            <div class="{{auth()->user()->role == 0 ?'col-6':'col-4'}}">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'From Date') }}</label>
                <input class="form-control" type="date" name="startdate" id="startdate">

            </div>
            <div class="{{auth()->user()->role == 0 ?'col-6':'col-4'}}">

                <label class="label">{{ MyHelpers::admin_trans(auth()->user()->id,'To Date') }}</label>
                <input class="form-control" type="date" name="enddate" id="enddate">

            </div>
            <div class="col-4 {{auth()->user()->role == 0 ? 'd-none' : ''}}" >
                <label class="label"> الحالة  </label>
                <select class="form-control" name="status_user" id="status_user" style="height: 38px">
                    <option value="1">إستشاري نشط</option>
                    <option value="0">إستشاري مؤرشف</option>
                    <option value="2">الكل</option>

                </select>
            </div>
            @if(auth()->user()->role == 7)
            <div class="col-12">
                <label class="label">اسم المدير </label>
                <div class="rs-select2 js-select-simple select--no-search">
                    <select class="form-control" name="manager_id[]" multiple id="manager_id">
                        <option value="allManager">الكل</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                    <div class="select-dropdown"></div>
                </div>

            </div>
                <div class="col-12">
                    <label class="label">إسم المستشار </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="adviser_id[]" multiple id="adviser_id">
                            <option value="0">الكل</option>

                        </select>
                        <div class="select-dropdown"></div>
                    </div>

                </div>
            @elseif(auth()->user()->role == 0)
            @else
                <input type="hidden" name="{{$name}}"  id="{{$name}}" value="{{$value}}">
                <div class="col-12">
                    <label class="label">إسم المستشار </label>
                    <div class="rs-select2 js-select-simple select--no-search">
                        <select class="form-control" name="adviser_id[]" multiple id="adviser_id">
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
                    <button class="text-center mr-3 green item"  name="submit" id="filter-search-req"  >
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

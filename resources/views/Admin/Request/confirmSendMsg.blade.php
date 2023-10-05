<style>
    .select2-container--open  {
        z-index: 99999999999999999999;
    }
</style>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal5">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">الجودة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <br>


            <div class="modal-body">

                <select id="qulityManager" required name="qulityManager" class="form-control" style="width: 100%">
                    @foreach($qulitys as $qulity)
                    <option value="{{$qulity->id}}"> {{ $qulity->name_for_admin ?? $qulity->name." [ لا يوجد اسم عند الإدارة ] " }} </option>
                    @endforeach


                </select>

                <span id="qualityError" style="color: red;"></span>

            </div>




            <br>

            <div class="modal-footer">
                <button type="button" id="modal-btn-no5" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si5" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal55">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">الجودة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <br>


            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group col-6 col-md-12">
                        <label for="qulityManagers" class="control-label mb-1">أختار مدير الجودة</label>
                        <select class="form-control" name="qulityManager" style="width: 100%" id="qulityManagers" required>
{{--                        <select class="tokenizeable form-control" name="qulityManager[]" id="qulityManagers" multiple>--}}
                            @foreach($qulitys as $qulity)
                                <option value="{{$qulity->id}}"> {{ $qulity->name_for_admin ?? $qulity->name." [ لا يوجد اسم عند الإدارة ] " }} </option>
                            @endforeach
                        </select>
                    </div>
                    <span id="qualityErrors" style="color: red;"></span>
                </div>
                <span id="qualityError" style="color: red;"></span>

            </div>




            <br>

            <div class="modal-footer">
                <button type="button" id="modal-btn-no55" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si55" class="btn btn-primary">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
            </div>
        </div>
    </div>
</div>


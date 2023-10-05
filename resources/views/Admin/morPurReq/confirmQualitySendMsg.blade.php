<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal5">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">الجودة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <br>


            <div class="modal-body row">
                <select id="qulityManager" name="qulityManager" class="form-control" style="width: 100%">

                    <option value=""> --- </option>
                    @foreach($qulitys as $qulity)
                        <option value="{{$qulity->id}}"> {{ $qulity->name }} </option>
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

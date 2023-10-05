<select class="form-control" name="first_batch_from_realValue" id="first_batch_from_realValue" onchange="firstBatchFromRealValue();">
    <option value="5" {{ $purchaseTsa->first_batch_from_realValue == 5 ? 'selected' : '' }}>5</option>
    <option value="10" {{ $purchaseTsa->first_batch_from_realValue == 10 || ($purchaseTsa->first_batch_from_realValue != 5  &&  $purchaseTsa->first_batch_from_realValue != 30) ? 'selected' : '' }}>10</option>
    <option value="30" {{ $purchaseTsa->first_batch_from_realValue == 30 ? 'selected' : '' }}>30</option>
</select>
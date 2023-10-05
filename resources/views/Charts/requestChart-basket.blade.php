
    <label class="label">سلال الطلب </label>
    <div class="rs-select2 js-select-simple select--no-search">
        <select class="form-control" name="basket[]" multiple>
            
            <option value="allBaskets" {{in_array("allBaskets",$baskets)? "selected" :""}}>
                الكل
            </option>
            <option value="complete" {{in_array("complete",$baskets)? "selected" :""}}>
                مكتملة
            </option>
            <option value="archived" {{in_array("archived",$baskets)? "selected" :""}}>
                مؤرشفة
            </option>
            <option value="following" {{in_array("following",$baskets)? "selected" :""}}>
                متابعة
            </option>
            <option value="star" {{in_array("star",$baskets)? "selected" :""}}>
                مميزة
            </option>
            <option value="received" {{in_array("received",$baskets)? "selected" :""}}>
                مستلمة
            </option>
        </select>
        <div class="select-dropdown"></div>
    </div>

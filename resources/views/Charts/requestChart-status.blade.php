
    <label class="label">حالات الطلب </label>
    <div class="rs-select2 js-select-simple select--no-search">
        <select class="form-control" name="status[]" multiple>
            <option value="allStatus" {{in_array("allStatus",$statuses)? "selected" :""}}>
                الكل
            </option>
            <option value="newStatus" {{in_array("newStatus",$statuses)? "selected" :""}}>
                جديد
            </option>
            <option value="openStatus" {{in_array("openStatus",$statuses)? "selected" :""}}>
                مفتوح
            </option>
            <option value="archiveStatus" {{in_array("archiveStatus",$statuses)? "selected" :""}}>
            مؤرشف عند استشاري المبيعات
            </option>
            <option value="watingSMStatus" {{in_array("watingSMStatus",$statuses)? "selected" :""}}>
                بإنتظار موافقة مدير المبيعات
            </option>
            <option value="rejectedSMStatus" {{in_array("rejectedSMStatus",$statuses)? "selected" :""}}>
               رفض من قبل مدير المبيعات
            </option>
            {{--<option value="archiveSMStatus" {{in_array("archiveSMStatus",$statuses)? "selected" :""}}>
               مؤرشف عند مدير المبيعات
            </option>--}}
            <option value="watingFMStatus" {{in_array("watingFMStatus",$statuses)? "selected" :""}}>
                بإنتظار موافقة مدير التمويل
            </option>
            <option value="rejectedFMStatus" {{in_array("rejectedFMStatus",$statuses)? "selected" :""}}>
               رفض من قبل مدير التمويل
            </option>
            {{--<option value="archiveFMStatus" {{in_array("archiveFMStatus",$statuses)? "selected" :""}}>
               مؤرشف عند مدير التمويل
            </option>--}}
            <option value="watingMMStatus" {{in_array("watingMMStatus",$statuses)? "selected" :""}}>
                بإنتظار موافقة مدير الرهن
            </option>
            <option value="rejectedMMStatus" {{in_array("rejectedMMStatus",$statuses)? "selected" :""}}>
               رفض من قبل مدير الرهن
            </option>
            {{--<option value="archiveMMStatus" {{in_array("archiveMMStatus",$statuses)? "selected" :""}}>
               مؤرشف عند مدير الرهن
            </option>--}}
            <option value="watingGMStatus" {{in_array("watingGMStatus",$statuses)? "selected" :""}}>
                بإنتظار موافقة المدير العام
            </option>
            <option value="rejectedGMStatus" {{in_array("rejectedGMStatus",$statuses)? "selected" :""}}>
               رفض من قبل المدير العام
            </option>
            {{--<option value="archiveGMStatus" {{in_array("archiveGMStatus",$statuses)? "selected" :""}}>
               مؤرشف عند المدير العام
            </option>--}}
            <option value="canceledStatus" {{in_array("canceledStatus",$statuses)? "selected" :""}}>
               ملغي
            </option>
            <option value="completedStatus" {{in_array("completedStatus",$statuses)? "selected" :""}}>
               مكتمل
            </option>

            <option value="fundingReportStatus" {{in_array("fundingReportStatus",$statuses)? "selected" :""}}>
               في تقرير التمويل
            </option>
            <option value="mortgageReportStatus" {{in_array("mortgageReportStatus",$statuses)? "selected" :""}}>
               في تقرير الرهن
            </option>
           
        </select>
        <div class="select-dropdown"></div>
    </div>

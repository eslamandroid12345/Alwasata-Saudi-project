<div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ MyHelpers::admin_trans(auth()->user()->id,'request records') }}</h5>
          <button class="btn-close ms-0 shadow-none" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-custom table-striped table-small">
            <thead>
              <tr>
                <th class="bg-white text-dark">اسم المستخدم</th>
                <th class="bg-white text-dark">التحديث</th>
                <th class="bg-white text-dark">وقت التحديث</th>
              </tr>
            </thead>
            <tbody id="records">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


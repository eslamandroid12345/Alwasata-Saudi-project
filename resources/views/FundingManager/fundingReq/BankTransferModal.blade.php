


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true" id="mi-modal6">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">ارسال الطلب الي موظفي البنك</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                @php
                    $related_u_bank_ids = \DB::table('user_collaborators')->where('user_id', auth()->id())->pluck('collaborato_id')->toArray();
                @endphp
                @if (\DB::table('wasata_requestes')->where('req_id', $id)->where('req_type', 'close')->exists())
                    هذا الطلب تم افراغة
                @elseif(sizeof($related_u_bank_ids) == 0)
                عفوا لم يتم اضافة موظفين بنك الي حسابك.
                @else
                    <table class="bordered-table table">
                        <thead >
                            <tr  style="background-color: #0f5b94; color: #fff; font-size: 14px;">
                                <th style="color:#fff;">
                                    اسم موظف البنك
                                </th>
                                <th style="color:#fff;">
                                    العمليات
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\User::where('role', '13')->whereIn('id', $related_u_bank_ids)->get() as $fund_user)

                                <tr style="padding: 0;">
                                    <td>
                                        {{$fund_user->name}}
                                    </td>
                                    <td>
                                        @include('FundingManager.fundingReq.BankTransferActions')
                                    </td>

                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" id="modal-btn-no4" class="btn btn-secondary" data-dismiss="modal">{{ MyHelpers::admin_trans(auth()->user()->id,'Cancel') }}</button>
                <button type="submit" id="modal-btn-si4" class="btn btn-info">{{ MyHelpers::admin_trans(auth()->user()->id,'Send') }}</button>
            </div>
        </div>
    </div>
</div>

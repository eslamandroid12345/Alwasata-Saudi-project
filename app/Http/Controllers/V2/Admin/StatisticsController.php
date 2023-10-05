<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\DataTables\ClassificationAlertSettingDataTable as DataTable;
use App\Http\Controllers\AppController;
use App\Models\Classification;
use App\Models\ClassificationAlertSetting as Model;
use App\Models\ClassificationQuestionnaire;
use App\Models\RequestJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatisticsController extends AppController
{

    public function __construct()
    {
        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();
    }

    public function classifications(Request $request)
    {

        $agentsQuery = User::query()->where(['status' => 1, 'role' => 0]);

        if (($c = $request->get('agent_id'))) {
            !is_array($c) && ($c = explode(',', $c));
            $agentsQuery->whereIn('id', $c);
        }
        if (($c = $request->get('manager_id'))) {
            !is_array($c) && ($c = explode(',', $c));
            $agentsQuery->whereIn('manager_id', $c);
        }

        $userResults = $agentsQuery->get();
        $userIds = $userResults->pluck('id')->toArray();
        $query = ClassificationQuestionnaire::query()->where('classification_id', Classification::AGENT_UNABLE_TO_COMMUNICATE,)->whereIn('user_id', $userIds,
        );
        if (($c = $request->get('date_from'))) {
            $query->whereDate('created_at', '>=', $c);
        }
        if (($c = $request->get('date_to'))) {
            $query->whereDate('created_at', '<=', $c);
        }
        $questionnaires = $query->get();
        $rows = collect();
        $allChangesCount = $allMessagesCount = $allUnableToConnectCount = $allPendingCount = $allRegisterCount = 0;
        //dd($questionnaires->toArray());
        foreach ($userResults as $key => $user) {
            $userData = $questionnaires->filter(fn(ClassificationQuestionnaire $item) => $item->user_id == $user->id);

            //$changes = $userData->filter(fn($item) => $item->title === ClassificationQuestionnaire::TITLE_BACK_BY_CHANGE)->values();
            //$messages = $userData->filter(fn($item) => $item->title === ClassificationQuestionnaire::TITLE_BACK_BY_MESSAGE)->values();

            $changesCount = $userData->filter(fn($item) => $item->title === RequestJob::SOURCE_CHANGE_CLASS && $item->value === !0)->count();
            $messagesCount = $userData->filter(fn($item) => $item->title === RequestJob::SOURCE_APP_MESSAGE && $item->value === !0)->count();

            $unableToConnectCount = $userData->filter(fn($item) => $item->value === !1)->count();

            $registerCount = $userData->filter(fn($item) => !in_array($item->title, [RequestJob::SOURCE_APP_MESSAGE, RequestJob::SOURCE_CHANGE_CLASS]) && $item->value === !0)->count();
            $pendingCount = $userData->filter(fn($item) => is_null($item->value))->count();

            $rows->push([
                'id'                   => ++$key,
                'id'                   => $user->id,
                'name'                 => $user->name,
                'changesCount'         => $changesCount,
                'messagesCount'        => $messagesCount,
                'registerCount'        => $registerCount,
                'unableToConnectCount' => $unableToConnectCount,
                'pendingCount'         => $pendingCount,
            ]);
            $allChangesCount += $changesCount;
            $allMessagesCount += $messagesCount;
            $allRegisterCount += $registerCount;
            $allUnableToConnectCount += $unableToConnectCount;
            $allPendingCount += $pendingCount;
        }

        //dd($rows);
        $agents = User::query()->where([
            'status' => 1,
            'role'   => 0,
        ])->get();
        $managers = User::query()->where([
            'status' => 1,
            'role'   => 1,
        ])->get();
        return view('V2.Admin.Statistics.classifications', [
            'rows'                    => $rows,
            'agents'                  => $agents,
            'managers'                => $managers,
            'allChangesCount'         => $allChangesCount,
            'allMessagesCount'        => $allMessagesCount,
            'allRegisterCount'        => $allRegisterCount,
            'allUnableToConnectCount' => $allUnableToConnectCount,
            'allPendingCount'         => $allPendingCount,
        ]);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), []);
    }
}

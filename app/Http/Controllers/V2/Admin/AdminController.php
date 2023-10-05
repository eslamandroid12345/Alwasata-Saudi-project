<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\Announcement;
use App\Helpers\MyHelpers;
use App\Models\Classification;
use App\Models\QualityRequest;
use App\Models\RequestHistory;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;

class AdminController
{
    public function __construct()
    {
        if (config('app.debug')) {
            view()->share([
                'announces'                 => Announcement::where('status', 1)->get(),
                'all_reqs_count'            => null,
                'agent_received_reqs_count' => null,
                'follow_reqs_count'         => null,
                'star_reqs_count'           => null,
                'arch_reqs_count'           => null,
                'pending_request_count'     => null,
                'need_action_request_count' => null,
                'sent_task_count'           => null,
                'received_task_count'       => null,
                'completed_task_count'      => null,
                'calculator_suggests'       => null,
                'onlineUsers'               => [],
                'notifyWithoutReminders'    => collect(),
                'notifyWithOnlyReminders'   => collect(),
                'notifyWithHelpdesk'        => collect(),
                'unread_conversions'        => null,
                'unread_messages'           => collect(),
            ]);
        }
        else {
            View::composers([
                'App\Composers\HomeComposer'             => ['layouts.content'],
                'App\Composers\ActivityComposer'         => ['layouts.content'],
                'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
            ]);
        }
    }

    public function report1(Request $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->get();
        $managers = $all_users->filter(function ($u) {
            return $u->role == 1;
        })->values();
        $manager_role = auth()->user()->role;
        $manager_id = $request->get('manager_id', []);
        $adviser_ids = $request->get('adviser_id', []);
        //d($request->all());

        $from = $request->get('start_date', now()->subWeek());
        $from = Carbon::parse($from);

        $to = $request->get('end_date', now());
        $to = Carbon::parse($to);

        $startDate = $from->hours(0)->minutes(0)->seconds(0);
        $endDate = $to->hours(23)->minutes(59)->seconds(59);
        // dd($startDate->toDateTimeString(), $endDate->toDateTimeString());
        $users = User::with([
            'requests'                 => function ($builder) use ($startDate, $endDate) {
                return $builder->where(function (Builder $builder) use ($startDate, $endDate) {
                    return $builder->where(function (Builder $builder) use ($startDate, $endDate) {
                        return $builder->whereBetween(DB::raw('DATE(agent_date)'), [$startDate, $endDate]);
                    })->orWhere(function (Builder $builder) use ($startDate, $endDate) {
                        return $builder->whereBetween(DB::raw('DATE(req_date)'), [$startDate, $endDate]);
                    });
                });
            },
            'receivedRequestHistories' => function ($builder) use ($startDate, $endDate) {
                return $builder->where(function (Builder $builder) use ($startDate, $endDate) {
                    return $builder->whereBetween(DB::raw('DATE(history_date)'), [$startDate, $endDate])->transferred()->where(function (Builder $builder) {
                        return $builder->whereIn('title', [
                            'طلب جديد تم إضافته لسلتك',
                            'إضافة الطلب من عطارد',
                            'إضافة الطلب من الموقع الإلكتروني',
                            'إضافة الطلب من قبل مدير النظام',
                            'إضافة الطلب من تمويلك',
                        ]);
                        //->where('title', 'طلب جديد تم إضافته لسلتك')->orWhere('title', 'إضافة الطلب من عطارد')->orWhere('title', 'إضافة الطلب من الموقع الإلكتروني')->orWhere('title', 'إضافة الطلب من قبل مدير النظام')->orWhere('title', 'إضافة الطلب من تمويلك');
                    });
                })->with('request');
            },
        ])
            //->withCount([
            //    'requests' => function ($builder) {
            //        return $builder->whereDate('');
            //    },
            //])
            ->where([
                'role' => 0,
            ])->when($request->status_user != 2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })
            //->whereNull('id')
            ->oldest('created_at');
        if (is_array($manager_id) && !empty($manager_id)) {
            $users->whereIn('manager_id', $manager_id);
        }
        if (is_array($adviser_ids) && !empty($adviser_ids)) {
            $users->whereIn('id', $adviser_ids);
        }
        $users = $users->get();
        //dd($users->first());
        $chartUsers = collect();
        $chartValues = collect();
        /** @var User $user */
        foreach ($users as $user) {
            $found = [];
            $ignored = [];
            $requests = $user->requests;
            $ids = $requests->pluck('id')->toArray();
            $transferred = $user->receivedRequestHistories->filter(function ($e) use ($ids) {
                return !in_array($e->req_id, $ids);
            })->values();
            //dd($ids,$transferred->toArray());
            /** @var RequestHistory $history */
            foreach ($transferred as $history) {
                /** @var Request $r */
                $r = $history->request;
                if ($r->user_id == $user->id) {
                    array_push($found, $history);
                }
                else {
                    //dd($history);
                    if (!$r->history()->where('id', '!=', $history->id)->forReport1()->exists()) {
                        array_push($found, $history);
                    }
                    //else {
                    //array_push($ignored, $history);
                    //}
                }
            }
            $chartUsers->push($user->name);
            $chartValues->push(count($found) + count($ids));
        }
        //$chartCategories = collect($result)->pluck();
        return view('V2.Reports.report1', compact('chartUsers', 'chartValues', 'startDate', 'endDate', 'manager_role', 'manager_id', 'managers', 'adviser_ids'));
    }

    public function report2(Request $request)
    {
        //dd($request->all());
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $agentsQuery = User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->where(['role' => 0]);

        $manager_role = auth()->user()->role;
        $manager_id = $request->get('manager_id', []);
        $adviser_ids = $request->get('adviser_id', []);
        if (($c = $request->get('agent_id'))) {
            !is_array($c) && ($c = explode(',', $c));
            $agentsQuery->whereIn('id', $c);
        }
        if (($c = $request->get('manager_id'))) {
            !is_array($c) && ($c = explode(',', $c));
            $agentsQuery->whereIn('manager_id', $c);
        }
        //$userResults = $request->has('submit') ? $agentsQuery->get() : null;
        $userResults = $agentsQuery->get();#->filter(fn($u) => $u->id == 61);
        //dd($userResults);
        //$userIds = $userResults->pluck('id')->toArray();
        //$query = RequestHistory::query()->where([
        //    'content' => RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO,
        //    'title'   => RequestHistory::TITLE_MOVE_REQUEST,
        //])->whereIn('user_id', $userIds);

        //$requests = $query->get();
        $rows = collect();
        $allFrozenCount = 0;
        $allNotFrozenCount = 0;
        //dd($requests->toArray());
        if ($userResults) {
            foreach ($userResults as $key => $user) {
                $frozenQuery = \App\Models\Request::query()->whereHas('requestHistories', function (Builder $b) use ($request, $user) {
                    $b->where('title', 'LIKE', RequestHistory::MOVE_TO_FREEZE);
                    $b->where('user_id', $user->id);

                    if (($c = $request->get('date_from'))) {
                        $b->whereDate('history_date', '>=', $c);
                    }

                    if (($c = $request->get('date_to'))) {
                        $b->whereDate('history_date', '<=', $c);
                    }
                    return $b;
                });
                //$frozenQuery = \App\Models\RequestHistory::query()->where([
                //    'content' => RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO,
                //    'title'   => RequestHistory::TITLE_MOVE_REQUEST,
                //    'user_id' => $user->id,
                //])->groupBy('req_id');
                /* $notFrozenQuery = \App\Models\RequestHistory::query()->where([
                     'title'   => RequestHistory::TITLE_MOVE_REQUEST,
                     'user_id' => $user->id,
                 ])->whereIn('content', [
                     RequestHistory::CONTENT_AGENT_TAKE_FROZEN_REQUEST,
                     RequestHistory::CONTENT_FROZEN_NEW_BACK,
                     RequestHistory::CONTENT_SEND_MESSAGE_UNABLE_TO_COMMUNICATE,
                 ])->groupBy('req_id');*/

                //if (($c = $request->get('date_from'))) {
                //    $frozenQuery->whereDate('history_date', '>=', $c);
                //$notFrozenQuery->whereDate('history_date', '>=', $c);
                //}

                //if (($c = $request->get('date_to'))) {
                //    $frozenQuery->whereDate('history_date', '<=', $c);
                //$notFrozenQuery->whereDate('history_date', '<=', $c);
                //}

                $notFrozenQuery = \App\Models\Request::query()
                    ->whereHas('requestHistories', function (Builder $b) use ($request, $user) {
                        $b->where([
                            //'content' => RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO,
                            'title'   => RequestHistory::MOVE_TO_FREEZE,
                            'user_id' => $user->id,
                        ]);

                        //if (($c = $request->get('date_from'))) {
                        //    $b->whereDate('history_date', '>=', $c);
                        //}
                        //
                        //if (($c = $request->get('date_to'))) {
                        //    $b->whereDate('history_date', '<=', $c);
                        //}
                        return $b;
                    })
                    ->whereHas('requestHistories', function (Builder $b) use ($request, $user) {
                        $b->where('title', RequestHistory::MOVE_FROM_FREEZE);

                        if (($c = $request->get('date_from'))) {
                            $b->whereDate('history_date', '>=', $c);
                        }

                        if (($c = $request->get('date_to'))) {
                            $b->whereDate('history_date', '<=', $c);
                        }
                        return $b;
                    });

                //if (($c = $request->get('date_from'))) {
                //    $frozenQuery->whereDate('history_date', '>=', $c);
                //    $notFrozenQuery->whereDate('history_date', '>=', $c);
                //}
                //
                //if (($c = $request->get('date_to'))) {
                //    $frozenQuery->whereDate('history_date', '<=', $c);
                //    $notFrozenQuery->whereDate('history_date', '<=', $c);
                //}

                //$frozenData = $temp->where(fn(RequestHistory $item) => $item->user_id == $user->id);
                $frozenData = $frozenQuery->count();
                //dd($frozenQuery->get());
                $notFrozenData_rows = $notFrozenQuery->pluck('id')->toArray();
                $notFrozenData = $notFrozenQuery->count();
                //dd($notFrozenQuery->get());

                $rows->push([
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'frozen'    => $frozenData,
                    'notFrozen' => $notFrozenData,
                    'notFrozen_rows' => $notFrozenData_rows,
                ]);
                $allFrozenCount += $frozenData;
                $allNotFrozenCount += $notFrozenData;
            }
        }

        //dd($rows);
        $agents = User::query()->where([
            'role' => 0,
        ])->when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->get();
        $managers = User::query()->where([
            'role' => 1,
        ])->when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        })->get();

        return view('V2.Reports.report2', [
            'rows'              => $rows,
            'agents'            => $agents,
            'managers'          => $managers,
            'allFrozenCount'    => $allFrozenCount,
            'allNotFrozenCount' => $allNotFrozenCount,
            'manager_role'      => $manager_role,
            'manager_id'        => $manager_id,
            'adviser_ids'       => $adviser_ids,
        ]);
        //return view('V2.Admin.report2', compact('chartUsers', 'chartValues', 'startDate', 'endDate', 'manager_role', 'manager_id', 'managers', 'adviser_ids'));
    }

    public function requests_table(Request $request)
    {
        $ids = $request->ids;
        $ids = explode(',', $ids);
        $rows = \App\Models\Request::whereIn('id', $ids)->get();
        return ['view' => view('V2.Reports.partials.requests_table', [
            'rows'              => $rows,
        ])->render()];
    }

    public function report3(Request $request)
    {
        //dd($request->all());
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $all_users = \App\User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        /// start default data
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];
        $manager_role = auth()->user()->role;
        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = \App\User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        $data_for_chart = [];
        /** @var \App\User $user */
        foreach ($users as $user) {
            $data_moved = [];
            $data_moved['name'] = $user->name;

            //MOVEING TYPES
            $data_moved['moved_AskReq'] = $user->movedRequests_AskReq($request['startdate'], $request['enddate']);
            $data_moved['moved_transfer_basket'] = $user->movedRequestsAskRequestTransferBasket($request['startdate'], $request['enddate']);
            $data_moved['moved_Admin'] = $user->movedRequests_Admin($request['startdate'], $request['enddate']);
            $data_moved['moved_NeedActionTable'] = $user->movedRequests_NeedActionTable($request['startdate'], $request['enddate']);
            $data_moved['moved_ArchiveBacket'] = $user->movedRequests_ArchiveBacket($request['startdate'], $request['enddate']);
            $data_moved['moved_ArchiveAgent'] = $user->movedRequests_ArchiveAgent($request['startdate'], $request['enddate']);
            $data_moved['moved_Pending'] = $user->movedRequests_Pending($request['startdate'], $request['enddate']);
            //$data_moved['moved_Undefined'] = $user->movedRequests_Undefined($request['startdate'], $request['enddate']);
            $data_for_chart[] = $data_moved;
        }
        return view('V2.Reports.report3', [
            'filterForm'     => '',
            'managers'       => $managers,
            'manager_role'   => $manager_role,
            'manager_ids'    => $manager_ids,
            'adviser_ids'    => $adviser_ids,
            'data_for_chart' => $data_for_chart,
        ]);
    }

    public function report4(Request $request)
    {
        if (!$request->has('status_user')) {
            $request->merge([
                'status_user' => 2,
            ]);
        }
        $submit = $request->has('submit');
        //dd($request->all());
        $report = setting('report4', []);
        if ($report && !is_array($report)) {
            $report = json_decode($report, !0);
        }
        //dd($report);
        $all_users = \App\User::when($request->status_user != 2, function ($q, $v) use ($request) {
            $q->where('status', $request->status_user);
        });
        $managers = $all_users->where('role', '1');
        $manager_ids = $request['manager_id'] ?: ['allManager'];
        $adviser_ids = $request['adviser_id'] ?: [0];
        $manager_role = auth()->user()->role;
        if (in_array('allManager', $manager_ids)) {
            if (in_array(0, $adviser_ids)) {
                $users = \App\User::where('role', 0)->when($request->status_user != 2, function ($q, $v) use ($request) {
                    $q->where('status', $request->status_user);
                })->get();
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }
        else {
            if ($request['adviser_id'] == 0) {
                $users = $all_users->whereIn('manager_id', $manager_ids);
            }
            else {
                $users = $all_users->whereIn('id', $adviser_ids);
            }
        }

        $startDate = $request->get('startdate');
        $endDate = $request->get('enddate');

        !$submit && $request->merge($report);

        $positiveAgent = $request->get('positive_agent_classification_id', []);
        $negativeAgent = $request->get('negative_agent_classification_id', []);
        $positiveQuality = $request->get('positive_quality_classification_id', []);
        $negativeQuality = $request->get('negative_quality_classification_id', []);
        $report = [
            'positive_agent_classification_id'   => $positiveAgent,
            'negative_agent_classification_id'   => $negativeAgent,
            'positive_quality_classification_id' => $positiveQuality,
            'negative_quality_classification_id' => $negativeQuality,
        ];
        //dd($report);

        $submit && setting(['report4' => json_encode($report),])->save();

        $userData = [];
        /** @var \App\User $user */
        foreach ($users as $user) {
            $positive = \App\Models\Request::query()->whereIn('class_id_agent', $positiveAgent)->where('user_id', $user->id);
            $startDate && $positive->whereDate('req_date', '>=', $startDate);
            $endDate && $positive->whereDate('req_date', '<=', $endDate);

            //$positive->where(fn(Builder $builder) => $builder->orWhereIn('class_id_quality', $negativeQuality)->orWhereIn('class_id_quality', $positiveQuality));
            //dd($positive->get());
            $positiveData = $positive->get();
            $positiveCount = $positiveData->count();

            $negative = \App\Models\Request::query()->whereIn('class_id_agent', $negativeAgent)->where('user_id', $user->id);
            $startDate && $negative->whereDate('req_date', '>=', $startDate);
            $endDate && $negative->whereDate('req_date', '<=', $endDate);

            //$negative->where(fn(Builder $builder) => $builder->orWhereIn('class_id_quality', $negativeQuality)->orWhereIn('class_id_quality', $positiveQuality));
            //dd($positive->get());
            $negativeData = $negative->get();
            $negativeCount = $negativeData->count();

            $data = [];
            $data['name'] = $user->name;
            $data['positiveCount'] = $positiveCount;
            $data['positive_positiveCount'] = $positiveData->filter(fn($item) => in_array($item->class_id_quality, $positiveQuality))->count();
            $data['negative_positiveCount'] = $positiveData->filter(fn($item) => in_array($item->class_id_quality, $negativeQuality))->count();

            $data['negativeCount'] = $negativeCount;
            $data['positive_negativeCount'] = $negativeData->filter(fn($item) => in_array($item->class_id_quality, $positiveQuality))->count();
            $data['negative_negativeCount'] = $negativeData->filter(fn($item) => in_array($item->class_id_quality, $negativeQuality))->count();
            $userData[] = $data;
        }
        return view('V2.Reports.report4', [
            'filterForm'   => '',
            'managers'     => $managers,
            'manager_role' => $manager_role,
            'manager_ids'  => $manager_ids,
            'adviser_ids'  => $adviser_ids,
            'userData'     => $userData,
        ]);
    }

    public function report5(Req $request)
    {
        $all_users = User::where([
            'role'   => 5,
            'status' => 1,
        ]);
        $quality_id = $request->get('quality_id', []);
        !empty($quality_id) && $all_users->whereIn('id', $quality_id);
        $users = $all_users->get();

        $startDate = $request['startdate'];
        $endDate = $request['enddate'];

        $data_for_class_chart = [];
        $data_for_basket_chart = [];
        $data_for_status_chart = [];

        $QualityClassifications = Classification::where('user_role', 5)->get();
        $statuses = $request->get('status', []);
        $class_id = $request->get('class_id', []);
        $baskets = $request->get('baskets', []);
        //dd($baskets);
        $statusSelect = MyHelpers::QualityRequestStatusSelect();
        $basketsSelect = MyHelpers::QualityBasketsSelect();
        foreach ($users as $user) {
            $name = $user->name;
            $name .= ($name ? ' - ' : '').($user->name_for_admin ?: '');
            $name = trim($name,'- ');
            $data_statuses = [
                'name' => $name,
            ];
            $data_classifications = [
                'name' => $name,
            ];
            $data_baskets = [
                'name' => $name,
            ];

            foreach ($statusSelect as $value) {
                $id = $value['id'];
                $data_statuses[$id] = 0;
                if (!empty($statuses) && !in_array($id, $statuses)) {
                    continue;
                }
                $data_statuses[$id] = QualityRequest::byReport5($user, $id, $class_id, $baskets, $startDate, $endDate)->count();
            }
            $data_for_status_chart[] = $data_statuses;

            foreach ($QualityClassifications as $value) {
                $id = $value['id'];
                $data_classifications[$id] = 0;
                if (!empty($class_id) && !in_array($id, $class_id)) {
                    continue;
                }
                $data_classifications[$id] = QualityRequest::byReport5($user, $statuses, $id, $baskets, $startDate, $endDate)->count();
            }
            $data_for_class_chart[] = $data_classifications;

            foreach ($basketsSelect as $value) {
                $id = $value['id'];
                $data_baskets[$id] = 0;
                if (!empty($baskets) && !in_array($id, $baskets)) {
                    continue;
                }
                //dd($baskets,$id);
                $data_baskets[$id] = QualityRequest::byReport5($user, $statuses, $class_id, $id, $startDate, $endDate)->count();
            }
            $data_for_basket_chart[] = $data_baskets;
        }
        //dd($data_for_class_chart);
        return view('V2.Reports.report5', compact(
            'users',
            'data_for_basket_chart',
            'data_for_status_chart',
            'data_for_class_chart',
        ));
    }

    public function userMessages(User $user)
    {
        return view('V2.Admin.userMessages', compact('user'));
    }

}

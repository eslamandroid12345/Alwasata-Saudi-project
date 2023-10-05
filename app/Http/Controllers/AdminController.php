<?php

namespace App\Http\Controllers;

use App\Area;
use App\classifcation;
use App\CollaboratorProfile;
use App\customer;
use App\CustomersPhone;
use App\Email;
use App\EmailUser;
use App\funding_source;
use App\Http\Controllers\SplitController\SplitAdminControllerTrait;
use App\Http\Controllers\SplitController\SplitAdminFourControllerTrait;
use App\Http\Controllers\SplitController\SplitAdminThreeControllerTrait;
use App\Http\Controllers\SplitController\SplitAdminTwoControllerTrait;
use App\Imports\requestImport;
use App\Mail\WastaMailNotification;
use App\Model\RequestSearching;
use App\Models\Bank;
use App\Models\OtpRequest;
use App\Models\RequestHistory;
use App\Models\SmsLog;
use App\salary_source;
use App\task;
use App\task_content;
use App\User;
use App\user_collaborator;
use App\WorkSource;
use Auth;
use Carbon\Carbon;
//use Datatables;
use Excel;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use MyHelpers;
use View;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    use SplitAdminControllerTrait;
    use SplitAdminTwoControllerTrait;
    use SplitAdminThreeControllerTrait;
    use SplitAdminFourControllerTrait;

    public function __construct()
    {
        if (config('app.debug')) {
            /*view()->share([
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
                'unread_conversions'        => null,
                'onlineUsers'               => [],
                'announces'                 => collect(),
                'notifyWithoutReminders'    => collect(),
                'notifyWithOnlyReminders'   => collect(),
                'notifyWithHelpdesk'        => collect(),
                'unread_messages'           => collect(),
            ]);*/
        }
        else {
            View::composers([
                'App\Composers\HomeComposer'             => ['layouts.content'],
                'App\Composers\ActivityComposer'         => ['layouts.content'],
                'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
            ]);
        }
    }

    public static function SendEmailNotification($userId, $content, $emailName, $subject)
    {
        $email = Email::where('email_name', $emailName)->first();
        if (EmailUser::where(['user_id' => $userId, 'email_id' => $email->id])->count() > 0) {
            Mail::to(User::find($userId)->email)->send(new WastaMailNotification($content, $subject));
        }
    }

    public static function ifThereRoleAndUsersAnnounce($id)
    {

        $announce_user = DB::table('announce_users')->where('announce_id', $id)->first();

        $announce_role = DB::table('announce_role')->where('announce_id', $id)->first();

        if ($announce_user || $announce_role) {
            return 'true';
        }
        else {
            return 'false';
        }
    }

    public static function getUsersAnnounce($id)
    {
        $userID = auth()->user()->id;

        $announce_user = DB::table('announce_users')->where('announce_id', $id)->where('user_id', $userID)->first();

        if ($announce_user) {
            return 'true';
        }
        else {
            return 'false';
        }
    }

    public static function getRoleAnnounce($id)
    {
        $userRole = auth()->user()->role;

        $announce_role = DB::table('announce_role')->where('announce_id', $id)->where('role', $userRole)->first();

        if ($announce_role) {
            return 'true';
        }
        else {
            return 'false';
        }
    }

    public static function getSeenAnnounce($id)
    {
        $announce_user = true;
        $userID = auth()->user()->id;

        $userInfo = DB::table('users')->where('id', $userID)->first();
        $annonceInfo = DB::table('announcements')->where('id', $id)->first();

        //only users that created before genrating announcement , will see announce...(new users cannot see old genrated annoncment).
        if (Carbon::parse($annonceInfo->created_at)->format('Y-m-d') >= Carbon::parse($userInfo->created_at)->format('Y-m-d')) {
            $announce_user = DB::table('announce_seen')->where('announce_id', $id)->where('user_id', $userID)->first();
        }
        if (!$announce_user) {
            return 'true';
        }
        else {
            return 'false';
        }
    }

    public function allEmails()
    {

        $users = DB::table('emails')->count(); // only active
        $emails = DB::table('emails')->get();  // only active

        $salesManagers = DB::table('users')->where(['role' => 1, 'status' => 1])->get();
        $fundingManagers = DB::table('users')->where(['role' => 2, 'status' => 1])->get();
        $mortgageManagers = DB::table('users')->where(['role' => 3, 'status' => 1])->get();
        $salesAgents = DB::table('users')->where(['role' => 0, 'status' => 1])->get();
        /*        $agent_quality = DB::table('agent_qualities')->get();*/
        $generalManagers = DB::table('users')->where(['role' => 4, 'status' => 1])->get();

        return view('Admin.Emails.myEmails', compact('users', 'emails', 'salesManagers', 'fundingManagers', 'mortgageManagers', 'salesAgents', 'generalManagers'));
    }

    public function deleteEmail()
    {

        $user = DB::table('emails')->where('id', request('id'))->first();
        foreach (EmailUser::where('email_id', $user->id)->get() as $email) {
            $email->delete();
        }
        DB::table('emails')->where('id', request('id'))->delete();
        return redirect()->route('admin.emails')->with('message2', 'تمت المسح بنجاح');
    }

    public function addEmailPage()
    {

        $salesManagers = DB::table('users')->where(['role' => 1, 'status' => 1])->get();
        $fundingManagers = DB::table('users')->where(['role' => 2, 'status' => 1])->get();
        $mortgageManagers = DB::table('users')->where(['role' => 3, 'status' => 1])->get();
        $salesAgents = DB::table('users')->where(['role' => 0, 'status' => 1])->get();
        /*        $agent_quality = DB::table('agent_qualities')->get();*/
        $generalManagers = DB::table('users')->where(['role' => 4, 'status' => 1])->get();

        return view('Admin.Emails.addEmailPage', compact('salesManagers', 'salesAgents', 'fundingManagers', 'mortgageManagers', 'generalManagers'));
    }

    public function addEmail(Request $request)
    {
        $display_name = $request->display_name;
        $email_name = $request->email_name;
        $status = $request->status;

        $rules = [
            'email_name'   => 'required|unique:emails',
            'display_name' => 'required',
            'status'       => 'required',
        ];

        $this->validate($request, $rules);

        $newEmail = Email::create([
            'email_name'   => $email_name,
            'display_name' => $display_name,
            'status'       => $status,
            'created_at'   => (Carbon::now('Asia/Riyadh')),
        ]);
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            EmailUser::create([
                'email_id' => $newEmail->id,
                'user_id'  => $user->id,
            ]);
        }
        return redirect()->route('admin.emails')->with('message2', 'تمت الإضافة بنجاح');
    }

    public function getAllMailsNotifications()
    {
        if (request('user_id')) {
            $userId = request('user_id');
            $emails = Email::all();

            return view('Admin.Emails.usersEmails', compact('emails', 'userId'));
        }
        else {
            return redirect()->back();
        }
    }

    public function saveAllMailsNotifications(Request $request)
    {
        $emails = EmailUser::where('user_id', $request->user_id)->delete();
        if ($request->emails) {
            foreach ($request->emails as $email) :
                EmailUser::create([
                    'email_id' => $email,
                    'user_id'  => $request->user_id,
                ]);
            endforeach;
        }
        return redirect()->route('admin.users')->with('message2', 'تمت التعديل بنجاح');
    }

    public function importExealrequest(Request $request)
    {
        $data = Excel::import(new requestImport, $request->file('select_file'));

        return back();
    }

    public function allUsers()
    {
        //$users = DB::table('users')->where('status', 1)->where('role', '!=', '6')->count(); // only active
        $users = 0;
        $salesManagers = DB::table('users')->where(['role' => 1, 'status' => 1])->get();
        $fundingManagers = DB::table('users')->where(['role' => 2, 'status' => 1])->get();
        $mortgageManagers = DB::table('users')->where(['role' => 3, 'status' => 1])->get();
        $salesAgents = DB::table('users')->where(['role' => 0, 'status' => 1])->get();
        $agent_quality = DB::table('agent_qualities')->get();
        $generalManagers = DB::table('users')->where(['role' => 4, 'status' => 1])->get();
        $errorSms = null;

        if (($log = SmsLog::query()->latest()->where('sent', !1)->first())) {
            $data = $log->data;

            if ($log->read == 0) {
                $log->update(['read' => 1]);
                $errorSms = __('messages.errorSms', [
                    'time' => $log->created_at->format(config('config.date_format.full')),
                    'code' => $data['Code'] ?? null,
                ]);
            }
        }

        $RolesSelect = [
            [
                'id'   => 0,
                'name' => __("language.Sales_Agent"),
            ],
            [
                'id'   => 1,
                'name' => __("language.Sales Manager"),
            ],
            [
                'id'   => 2,
                'name' => __("language.Funding Manager"),
            ],
            [
                'id'   => 3,
                'name' => __("language.Mortgage Manager"),
            ],
            [
                'id'   => 4,
                'name' => __("language.General Manager"),
            ],

            [
                'id'   => 5,
                'name' => __("language.Quality User"),
            ],
            [
                'id'   => 9,
                'name' => __("language.Quality Manager"),
            ],

            [
                'id'   => 6,
                'name' => __("language.Collaborator"),
            ],
            [
                'id'   => 7,
                'name' => __("language.Admin"),
            ],
            [
                'id'   => 8,
                'name' => __("language.Accountant"),
            ],
            [
                'id'   => 11,
                'name' => __("language.Training"),
            ],
            [
                'id'   => 12,
                'name' => __("language.Hr"),
            ],
            [
                'id'   => 13,
                'name' => __("global.bankDelegate"),
            ],
        ];
        $areas = Area::all();
        return view('Admin.Users.myUsers', compact('RolesSelect','areas','errorSms', 'users', 'salesManagers', 'fundingManagers', 'mortgageManagers', 'salesAgents', 'generalManagers', 'agent_quality'));
    }

    public function allUsers_datatable(Request $request)
    {


        $users = User::when($request->has("name"), function ($q, $v) use ($request) {
            $q->where(function ($q) use ($request){
                $q->where('name',"LIKE", "%%".$request->name."%%")
                    ->orWhere('mobile',"LIKE", "%%".$request->name."%%")
                    ->orWhere('email',"LIKE", "%%".$request->name."%%");
            });
        })->orderBy('id', 'DESC');
        /*if ($request->role =="sa"){
            $users = $users->where("role",0);
        }*/
        return Datatables::of($users)->setRowId(function ($users) {
            return $users->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            //============ this button to allow some users to access another users requests ============
            // $data = $data.'<span class="pointer " id="access_requests" type="button" data-toggle="tooltip" data-placement="top" data-id="'.$row->id.'"  title="السماح للمستخدمين بمتابعه الطلبات">
            //                 <i class="fas fa-user-shield"></i>
            //             </span>';
            //==============================================================================

            if($row->status == 1){
                if ($row->role !=20) {
                    if ($row->allow_recived == 1) {//هنشوف هل المستخدم مسموحله استقبال طلبات ولا
                        if ($row->role == 0) {//استشاري المبيعات
                            //هنشوف هل الاستشارى ده مرتبط بمتعاون ولا لا
                            $collabarator = user_collaborator::where("user_id",$row->id)->first();
                            $new =' ';
                            if ($collabarator){
                                //لو مرتبط .. هنجيب اسم المتعاون
                                $new.='data-name="'.$collabarator->collaborator->name.'"';
                            }
                            $data = $data.'<span id="active" '.$new.' class="pointer " data-type="agent" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'not_allow').'">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>';
                        }else{
                            $data = $data.'<span id="active" class="pointer " data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'not_allow').'">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </span>';
                        }

                    }
                    else {

                    $data = $data.'<span id="active" class="redBg pointer " data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'allow').'">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </span>';


                    }
                }

                if ($row->role !=20){
                    $data = $data.'<span class="pointer item" id="switch" type="button"data-toggle="tooltip" data-placement="top"  data-id="'.$row->id.'" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Switch Login').'">
                    <i class="fas fa-sign-in-alt"></i>
                                     </span>';
                }


                $data = $data.'<span class="pointer " id="edit" type="button" data-toggle="tooltip" data-placement="top" data-id="'.$row->id.'"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                        <i class="fas fa-edit"></i>
                                    </span>';
                $data = $data.'<span class="pointer " id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>';
                $data = $data."<span class='pointer' title='عرض ملف الموظف'>
                                   <a href='".route('HumanResource.user.profile', $row->id)."'>
                                    <i class='fas fa-user-shield'></i>
                                    </a>
                                </span> ";

                if ($row->role !=20) {
                    $data = $data.'<span class="pointer " title="'.MyHelpers::admin_trans(auth()->user()->id, 'Email Manage').'">
                                       <a href="'.route('admin.getAllMailsNotifications').'?user_id='.$row->id.'">
                                        <i class="fas fa-envelope"></i>
                                        </a>
                                    </span> ';

                    $data = $data."<span class='pointer' title='".__('global.app_chats')."'>
                                       <a href='".route('V2.Admin.userMessages', $row->id)."'>
                                        <i class='fas fa-chalkboard-teacher'></i>
                                        </a>
                                    </span> ";
                }

            }else{
                $data = $data.'<span class="pointer " id="restore" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
                <a href="'.route('admin.restoreUser', $row->id).'"><i class="fas fa-reply-all"></i></a></span>';
            }

        return $data.'</div>';
        })->filter(function ($instance) use ($request) {

            // active and not archive if (allow recived = 1 && status = 1)
            // not active and not archive if (allow recived = 0 && status = 1)
            if ($request->get('status') == '0' || $request->get('status') == '1') {
                $instance->where('allow_recived', $request->get('status'))->where('status', 1);
            }

            // archive => status = 0
            if ($request->get('status') == '2') {
                $instance->where('status', 0);
            }

            // Collaborator => role = 6
            if ($request->get('status') == '4') {
                $instance->where('role', 6);
            }

            // Filter according to type of user
            if (isset($request->role)) {
                $instance->where('role', $request->get('role'));
            }

        })->editColumn('role', function ($row) {
            switch ($row->role) {
                case 0:
                    $role = 'Sales Agent';
                    break;
                case 1:
                    $role = 'Sales Manager';
                    break;
                case 2:
                    $role = 'Funding Manager';
                    break;
                case 3:
                    $role = 'Mortgage Manager';
                    break;
                case 4:
                    $role = 'General Manager';
                    break;
                case 5:
                    $role = 'Quality User';
                    break;
                case 9:
                    $role = 'Quality Manager';
                    break;
                case 6:
                    $role = 'Collaborator';
                    break;
                case 7:
                    $role = 'Admin';
                    break;
                case 8:
                    $role = 'Accountant';
                    break;
                case 10:
                    $role = 'Property User';
                    break;
                case 11:
                    $role = 'Training';
                    break;
                case 12:
                    $role = 'Hr';
                    break;
                case 13:
                    $role = 'bankDelegate';
                    break;
                case 20:
                    $role = $row->subdomain;
                    break;
                default:
                    $role = 'Undefined';
                    break;
            }
            if ($row->role ==20){
                return  $role ??'-';
            }
            $data = MyHelpers::admin_trans(auth()->user()->id, $role);

            if ($row->role == 8) {
                if ($row->accountant_type == 0) {
                    $data = $data.' - تساهيل';
                }

                if ($row->accountant_type == 1) {
                    $data = $data.' - وساطة';
                }
            }

            return $data;
        })->editColumn('status', function ($row) {
            if ($row->allow_recived == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'not active');
            }
            else {
                $data = 'نشط';
            }
            return $data;
        })
            //->editColumn('name', fn($row) => trim("{$row->name} - {$row->name_for_admin}", '- '))
            ->make(true);
    }

    public function allcolloberatorUsers()
    {

        $users = DB::table('users')->where('status', 1)->where('role', '=', '6')->count(); // only active

        $salesManagers = DB::table('users')->where(['role' => 1, 'status' => 1])->get();
        $fundingManagers = DB::table('users')->where(['role' => 2, 'status' => 1])->get();
        $mortgageManagers = DB::table('users')->where(['role' => 3, 'status' => 1])->get();
        $salesAgents = DB::table('users')->where(['role' => 0, 'status' => 1])->get();
        $agent_quality = DB::table('agent_qualities')->get();
        $generalManagers = DB::table('users')->where(['role' => 4, 'status' => 1])->get();

        return view('Admin.Users.ColloberatotrUsers', compact('users', 'salesManagers', 'fundingManagers', 'mortgageManagers', 'salesAgents', 'generalManagers', 'agent_quality'));
    }

    public function colloberator_datatable(Request $request)
    {
        $users = User::withCount("repeated")->where('status', 1)
            ->where('role', '6');
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $users = $users->leftjoin('user_collaborators', 'user_collaborators.collaborato_id', 'users.id')->whereIn('user_collaborators.user_id', $request->get('agents_ids'))->select('users.*');
        }
        return Datatables::of($users)->setRowId(function ($users) {
            return $users->id;
        })->addColumn('req_count', function ($row) {
            return $row->req_count ?? 0;
        })->addColumn('pen_count', function ($row) {
            return $row->pen_count ?? 0;
        })->addColumn('repeated_count', function ($row) {
            return $row->repeated_count ?? 0;
        })->addColumn('actions', function ($row) {
            $data = '<div id="tableAdminOption" class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="copy-my-url" data-name="'.$row->username.'" data-url="'.url('ar/col/'.$row->id.'-'.\Str::slug($row->username)).'" data-toggle="tooltip" data-placement="top" title="نسخ الرابط">
                <i class="fas fa-copy"></i></a>
            </span>';

            $data = $data.'<span class="pointer " title="الطلبات النشطة">
                                   <a href="'.route('admin.collaborator.requests.active',$row->id).'">
                                    <i class="fas fa-list"></i>
                                    </a>
                                </span> ';
            $data = $data.'<span class="pointer " title="الطلبات المكررة">
                                   <a href="'.route('admin.collaborator.requests.repeated',$row->id).'">
                                    <i class="fas fa-clipboard"></i>
                                    </a>
                                </span> ';
            $data = $data.'<span class="pointer " title="الطلبات المعلقة">
                                   <a href="'.route('admin.collaborator.requests.pending',$row->id).'">
                                    <i class="fas fa-ban"></i>
                                    </a>
                                </span> ';
            $data = $data.'</div>';


            return $data;
        })->addColumn('action', function ($row) {
            $data = '<div id="tableAdminOption" class="tableAdminOption">';
            if ($row->allow_recived == 1) {
                $data = $data.'<span class="item pointer" id="active" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'not_allow').'">
                                    <i class="fas fa-exclamation-circle"></i></a>
                                </span>';
            }
            else {
                $data = $data.'<span class="item redBg pointer" id="active" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'allow').'">
                                     <i class="fas fa-exclamation-circle"></i></a>
                                </span>';
            }
            $data = $data.'<span class="item pointer" id="switch" type="button" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Switch Login').'">
            <i class="fas fa-sign-in-alt"></i>
                            </span>';

            $data = $data.'<span class="item pointer" class="item" id="edit" type="button" data-toggle="tooltip" data-id="'.$row->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';
            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'<span class="pointer " title="'.MyHelpers::admin_trans(auth()->user()->id, 'Email Manage').'">
                                   <a href="'.route('admin.getAllMailsNotifications').'?user_id='.$row->id.'">
                                    <i class="fas fa-envelope"></i>
                                    </a>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->addColumn('salesAgent', function ($row) {
            $salesagents = DB::table('user_collaborators')->where('collaborato_id', $row->id)->join('users', 'users.id', 'user_collaborators.user_id')->select('users.name')->get();

            if (empty($salesagents)) {
                return '';
            }
            $arr_colors = [
                'is-primary',
                'is-link',
                'is-success',
                'is-black',
                'is-warning',
                'is-danger',
                'is-white',
                'is-primary',
                'is-dark',
                'is-light',
                'is-link',
                'is-success',
                'is-black',
                'is-warning',
                'is-danger',
                'is-primary',
                'is-dark',
                'is-light',
                'is-link',
                'is-primary',
                'is-link',
                'is-success',
                'is-black',
                'is-warning',
            ];
            $data = '<div>';
            $counter = 4;
            foreach ($salesagents as $salesagent) {
                $color = array_shift($arr_colors);
                if ($counter == 0) {
                    $data = $data.'</br>';
                    $counter = 4;
                }
                $data = $data.'<span  style="margin-right:5px;" class="tag '.$color.'">';
                $data = $data.$salesagent->name;
                $data = $data.'</span>';
                $counter--;
            }
            $data = $data.'</div>';
            return $data;
        })->editColumn('status', function ($row) {
            if ($row->allow_recived == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'not active');
            }
            else {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'active');
            }
            return $data;
        })->escapeColumns([])->rawColumns(['salesAgent', 'action','actions'])->make(true);
    }

    public function getUser(Request $request)
    {

        $user = \App\Models\User::with("areas","cities","districts","direction")->where('id', $request->id)->first();

        $salesagents = DB::table('user_collaborators')->where('collaborato_id', $request->id)->get(); // stop here

        $quality = DB::table('agent_qualities')->where('Quality_id', $request->id)->get();

        if (!empty($user)) {
            return response()->json(['user' => $user, 'salesagents' => $salesagents, 'quality' => $quality, 'status' => 1]);
        }
        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }

    public function switchUser(Request $request)
    {

        $request->session()->put('existing_user_id', Auth::user()->id); //Admin id
        $request->session()->put('user_is_switched', true);

        $newuserId = $request->id;

        Auth::loginUsingId($newuserId);
        //dd(auth()->user());
        if (auth()->user()->role == 0) {
            return route('agent.myRequests');
        }
        elseif (auth()->user()->role == 1) {
            return route('sales.manager.myRequests');
        }
        elseif (auth()->user()->role == 2) {
            return route('funding.manager.myRequests');
        }
        elseif (auth()->user()->role == 3) { // type
            return route('mortgage.manager.myRequests');
        }
        elseif (auth()->user()->role == 4) {
            return route('general.manager.myRequests');
        }
        elseif (auth()->user()->role == 5) {
            return route('quality.manager.myRequests');
        }
        elseif (auth()->user()->role == 9) {
            return route('quality.manager.myRequests');
        }
        elseif (auth()->user()->role == 6) {
            return route('proper.requests');
        }
        elseif (auth()->user()->role == 7) {
            return route('admin.users');
        }
        elseif (auth()->user()->role == 8) {
            if (auth()->user()->accountant_type == 1) {
                return route('report.wsataAccountingUnderReport');
            }
            else {
                return route('report.tsaheelAccountingUnderReport');
            }
        }
        elseif (auth()->user()->role == 10) {
            return route('proper.requests');
        }
        elseif (auth()->user()->role == 11) {
            return route('training.myRequests');
        }
        elseif (auth()->user()->role == 12) {
            return route('HumanResource.users.index');
        }
        else {
            return route('homePage');
            //die('605');
        }
    }

    public function restorUser(Request $request)
    {

        $oldUserId = $request->session()->get('existing_user_id');
        Auth::loginUsingId($oldUserId);
        $request->session()->forget('existing_user_id');
        $request->session()->forget('user_is_switched');

        return redirect()->route('admin.users');
    }

    public function archUsers()
    {

        $users = DB::table('users')->where('status', 0)->count(); // only archive

        return view('Admin.Users.archUsers', compact('users'));
    }

    public function archUsers_datatable()
    {
        $users = DB::table('users')->where('status', 0)->orderBy('id', 'DESC');
        return Datatables::of($users)->setRowId(function ($users) {
            return $users->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="pointer " id="restore" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Restore').'">
            <a href="'.route('admin.restoreUser', $row->id).'"><i class="fas fa-reply-all"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->addColumn('role', function ($row) {
            switch ($row->role) {
                case 0:
                    $role = 'Sales Agent';
                    break;
                case 1:
                    $role = 'Sales Manager';
                    break;
                case 2:
                    $role = 'Funding Manager';
                    break;
                case 3:
                    $role = 'Mortgage Manager';
                    break;
                case 4:
                    $role = 'General Manager';
                    break;
                case 5:
                    $role = 'Quality User';
                    break;
                case 9:
                    $role = 'Quality Manager';
                    break;
                case 6:
                    $role = 'Collaborator';
                    break;
                case 7:
                    $role = 'Admin';
                    break;
                case 8:
                    $role = 'Accountant';
                    break;
                case 11:
                    $role = 'Training';
                    break;
                case 12:
                    $role = 'Hr';
                    break;
                default:
                    $role = 'Undefined';
                    break;
            }
            return MyHelpers::admin_trans(auth()->user()->id, $role);
        })->make(true);
    }

    public function addUserPage()
    {
        dd('111');

        $salesManagers = DB::table('users')->where(['role' => 1, 'status' => 1])->get();
        $fundingManagers = DB::table('users')->where(['role' => 2, 'status' => 1])->get();
        $mortgageManagers = DB::table('users')->where(['role' => 3, 'status' => 1])->get();
        $salesAgents = DB::table('users')->where(['role' => 0, 'status' => 1])->get();
        $agent_quality = DB::table('agent_qualities')->get();
        $generalManagers = DB::table('users')->where(['role' => 4, 'status' => 1])->get();
        //$roles = Role
        return view('Admin.Users.addUserPage', compact('salesManagers', 'salesAgents', 'fundingManagers', 'mortgageManagers', 'generalManagers', 'agent_quality'));
    }

    public function addUser(Request $request)
    {
       // dd($request->all());
        //dd($request->input('salesagents', []));

        // get  auth info
        $auth = Auth::user();
        $id = $auth->id;
        $name = $request->name;
        $username = $request->username;
        $email = $request->email;
        $mobile = $request->mobile;
        $lang = $request->get('locale');
        $callCenterName = $request->callCenterName;

        $role = $request->role == 'sa' ? 0 : $request->role;


        if ($request->role == 0 && $request->isTsaheel == 0) {
            $rules = [
                'username'     => 'required|unique:users,username,'.$id,   // unique username',
                'email'        => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                'password'     => 'required|min:6',
                'locale'       => 'required',
                'role'         => 'required',
                'salesmanager' => 'required',

            ];

            $customMessages = [
                'username.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'username.unique'       => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                'password.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'locale.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'role.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'salesmanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                'email.email'           => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                'password.min'          => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
            ];
        }
        else {
            if ($request->role == 0 && $request->isTsaheel == 1) {

                $rules = [
                    'username'        => 'required|unique:users,username,'.$id,   // unique username',
                    'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                    'password'        => 'required|min:6',
                    'locale'          => 'required',
                    'role'            => 'required',
                    'mortgagemanager' => 'required',

                ];

                $customMessages = [
                    'username.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'username.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                    'password.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'locale.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'role.required'            => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'mortgagemanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'email.unique'             => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                    'email.email'              => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                    'password.min'             => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                ];
            }
            else {
                if ($request->role == 6) {

                    $rules = [
                        'username' => 'required|unique:users,username,'.$id,   // unique username',
                        'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                        'password' => 'required|min:6',
                        'locale'   => 'required',
                        'role'     => 'required',

                        'salesagents' => 'required|array|min:1',
                        'active'     => 'required_if:check,0',
                    ];

                    $data = '';
                    $error = '';

                    if ($request->has("salesagents")){

                        foreach (User::whereIn("id",$request->salesagents)->get() as $item) {
                            if ($item->allow_recived == 0){
                                $data.=$item->name." , ";
                            }
                        }
                        if ($data != ''){
                            $error = "الإستشاريين التاليين غير مسموح لهم بنزول طلبات [ ".$data
                                ." ] هل انت متأكد من الإضافة ";
                        }
                        if(\App\User::whereIn("id",$request->salesagents)->where("allow_recived",0)->count() == 0){
                            $request->merge([
                                "active"    => 1
                            ]);
                        }
                    }

                    $customMessages = [
                        'username.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'username.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                        'password.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'locale.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'role.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'active.required_if'      => $error,
                        'salesagents.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'email.unique'         => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                        'email.email'          => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                        'password.min'         => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),

                    ];

                   /* return response()->json([
                        "message"   => "message",
                        "errors"    => $request->all()
                    ]);*/

                }
                else {
                    if ($request->role == 1) {

                        $rules = [
                            'username'        => 'required|unique:users,username,'.$id,   // unique username',
                            'email'           => 'email|max:255|unique:users,email,'.$id, // unique email
                            'password'        => 'required|min:6',
                            'locale'          => 'required',
                            'role'            => 'required',
                            'fundingmanager'  => 'required',
                            'mortgagemanager' => 'required',

                        ];

                        $customMessages = [
                            'username.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'username.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                            'password.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            //'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'locale.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'role.required'            => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'fundingmanager.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'mortgagemanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'email.unique'             => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                            'email.email'              => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                            'password.min'             => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                        ];
                    }
                    else {
                        if ($request->role == 5) {

                            $rules = [
                                'username' => 'required|unique:users,username,'.$id,   // unique username',
                                'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                'password' => 'required|min:6',
                                'locale'   => 'required',
                                'role'     => 'required',
                                // 'quality' => 'required',

                            ];

                            $customMessages = [
                                'username.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'username.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                'password.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                //'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'locale.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'role.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                //  'quality.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'email.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                'email.email'       => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                'password.min'      => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                            ];
                        }
                        else {
                            if ($request->role == 2 || $request->role == 3) {

                                $rules = [
                                    'username'       => 'required|unique:users,username,'.$id,   // unique username',
                                    'email'          => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                    'password'       => 'required|min:6',
                                    'locale'         => 'required',
                                    'role'           => 'required',
                                    'generalmanager' => 'required',

                                ];

                                $customMessages = [
                                    'username.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'username.unique'         => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                    'password.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    //'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'locale.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'role.required'           => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'generalmanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'email.unique'            => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                    'email.email'             => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                    'password.min'            => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                                ];
                            }
                            else {
                                if ($request->role == 8) {
                                    $rules = [
                                        'username'        => 'required|unique:users,username,'.$id,   // unique username',
                                        'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                        'password'        => 'required|min:6',
                                        'locale'          => 'required',
                                        'role'            => 'required',
                                        'accountant_type' => 'required',
                                    ];

                                    $customMessages = [
                                        'username.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'username.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                        'password.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        // 'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'locale.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'role.required'            => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'email.unique'             => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                        'email.email'              => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                        'password.min'             => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                                        'accountant_type.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    ];
                                }
                                else {
                                    $rules = [
                                        'username' => 'required|unique:users,username,'.$id,   // unique username',
                                        'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                        'password' => 'required|min:6',
                                        'locale'   => 'required',
                                        'role'     => 'required',
                                    ];

                                    $customMessages = [
                                        'username.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'username.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                        'password.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        // 'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'locale.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'role.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'email.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                        'email.email'       => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                        'password.min'      => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($request->role == 20){
            $rules = [
                'role'     => 'required',
            ];

            $customMessages = [
                'role.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            ];
        }
        /**
         *  Bank Delegate rules
         */
        $error = '';
        if ($request->get('role') == 13) {
            $data = '';

            if ($request->has("salesagents_users")){

                foreach (\App\User::whereIn("id",$request->salesagents)->get() as $item) {
                    if ($item->allow_recived == 0){
                        $data.=$item->name." , ";
                    }
                }
                if ($data != ''){
                    $error = "الإستشاريين التاليين غير مسموح لهم بنزول طلبات [ ".$data
                        ." ] هل انت متأكد من الإضافة ";
                }
                if(\App\User::whereIn("id",$request->salesagents)->where("allow_recived",0)->count() == 0){
                    $request->merge([
                        "active"    => 1
                    ]);
                }
            }
            $rules = [
              'active' => 'required_if:check,0',
              'bank_id' => 'required', Rule::exists(Bank::getModelTable(), 'id'),
              'subdomain' => 'required',
              'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
              'password' => 'required|min:6',
              'username' => 'required',
              'code' => 'required',
              'salesagents' => 'required|array|min:1'
            ];

        }
        $customMessages = ['active.required_if'      => $error,
                           'bank_id.required' => " البنك مطلوب",
                           'salesagents.required' => "الأستشاريين مطلوبين",
                           'subdomain.required' => "المجال الفرعي مطلوب ",
                           'code.required' => " كود مصدر المعامة مطلوب",
                           'username.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'username.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                           'password.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           // 'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'locale.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'role.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'email.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                           'email.email'       => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                           'password.min'      => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),];
        $this->validate($request, $rules, $customMessages);

        //dd($request->all());
        $password = Hash::make($request->password);

        if ($request->name == null) {
            $name = 'لايوجد اسم';
        }
        $mergeUser = [
            'bank_id'        => $request->get('bank_id'),
            'subdomain'      => $request->get('subdomain'),
            'code'      => $request->get('code'),
            'name_for_admin' => $request->get('name_for_admin'),
        ];


        if ($role == 2 || $role == 3) {
            //insertGetId : insertGetId method to insert a record and then retrieve the ID
            $newuser = DB::table('users')->insertGetId(array_merge([
                'name'               => $name,
                'username'           => $username,
                'email'              => $email,
                'mobile'             => $mobile,
                'password'           => $password,
                'locale'             => $lang,
                'role'               => $role,
                'manager_id'         => $request->generalmanager,
                'funding_mnager_id'  => $request->fundingmanager,
                'mortgage_mnager_id' => $request->mortgagemanager,
                'created_at'         => (Carbon::now('Asia/Riyadh')),
                'accountant_type'    => $request->accountant_type,
                'name_in_callCenter' => $callCenterName,
            ], $mergeUser));
        }
        else {
            if ($role == 0) {
                $newuser = DB::table('users')->insertGetId(array_merge([
                    'name'               => $name,
                    'username'           => $username,
                    'email'              => $email,
                    'mobile'             => $mobile,
                    'password'           => $password,
                    'locale'             => $lang,
                    'role'               => $role,
                    'manager_id'         => $request->salesmanager,
                    'funding_mnager_id'  => $request->fundingmanager,
                    'mortgage_mnager_id' => $request->mortgagemanager,
                    'isTsaheel'          => $request->isTsaheel,
                    'created_at'         => (Carbon::now('Asia/Riyadh')),
                    'accountant_type'    => $request->accountant_type,
                    'name_in_callCenter' => $callCenterName,
                ], $mergeUser));
            }
            else {
                if ($request->role == 20){
                    $mergeUser["subdomain"] = $request->others;
                }
                $newuser = DB::table('users')->insertGetId(array_merge([
                    'name'               => $name,
                    'username'           => $username,
                    'email'              => $email,
                    'mobile'             => $mobile,
                    'password'           => $password,
                    'locale'             => $lang,
                    'role'               => $role,
                    'funding_mnager_id'  => $request->fundingmanager,
                    'mortgage_mnager_id' => $request->mortgagemanager,
                    'created_at'         => (Carbon::now('Asia/Riyadh')),
                    'accountant_type'    => $request->accountant_type,
                    'name_in_callCenter' => $callCenterName,
                    'subdomain'          => $request->get('subdomain'),
                    'bank_id'            => $request->get('bank_id'),
                ], $mergeUser));
            }
        }
        if ($role == 5) { // colorbetor needs to sales agents
            $is_follow = $request->has("is_follow") ? "follow" : null;

            DB::table('users')
                ->where('id', $newuser)->update([
                    "subdomain"  => $is_follow
                ]);

        }
        if ($role == 6) { // colorbetor needs to sales agents

            $salesAgents = $request->input('salesagents', []);
            $user_collibretor = null;

            foreach ($salesAgents as $salesAgent) {
                $user_collibretor = DB::table('user_collaborators')->insert([
                    'user_id'        => $salesAgent,
                    'collaborato_id' => $newuser,
                ]);
            }

            $admin_col = $request->domain_col;

            DB::table('users')
                ->where('id', $newuser)->update([
                    "subdomain"  => $admin_col != null ? "report" : null
                ]);
            $is_follow = $request->is_agent_show;
            if ($is_follow){
                DB::table('users')
                    ->where('id', $newuser)->update([
                        "code"  => $is_follow != null ? "agent_show" : null
                    ]);
            }
            CollaboratorProfile::whereNotIn("value",$request->area_id ?? [])
                ->where(["user_id" => $newuser,"key" =>"area_id"])->delete();

            CollaboratorProfile::whereNotIn("value",$request->city_id ?? [])
                ->where(["user_id" => $newuser,"key" =>"city_id"])->delete();

            CollaboratorProfile::whereNotIn("value",$request->district_id ?? [])
                ->where(["user_id" => $newuser,"key" =>"district_id"])->delete();

            CollaboratorProfile::updateOrCreate([
                "key"   => $request->direction,
                "user_id"   => $newuser
            ],[
                "key"   => 'direction',
                "value" =>  $request->direction,
                "user_id"   => $newuser
            ]);

            foreach ($request->area_id ?? [] as $key=> $item) {
                CollaboratorProfile::updateOrCreate([
                    "key"   => "area_id",
                    "user_id"   => $newuser,
                    "value" => $item,
                ]);
            }

            foreach ($request->city_id ?? [] as $key=> $item) {
                CollaboratorProfile::updateOrCreate([
                    "key"   => "city_id",
                    "user_id"   => $newuser,
                    "value" => $item,
                ]);
            }

            foreach ($request->district_id ?? [] as $key=> $item) {
                CollaboratorProfile::updateOrCreate([
                    "key"   => "district_id",
                    "user_id"   => $newuser,
                    "value" => $item,
                ]);
            }
            if ($user_collibretor == true || $user_collibretor == null) {
                return redirect()->route('admin.users')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        // Adding all email values to users Task-21
        /**************************************************/
        $emails = DB::table('emails')->get(); // only active

        foreach ($emails as $email) {
            EmailUser::create([
                'user_id'  => $newuser,
                'email_id' => $email->id,
            ]);
        }
        if ($role == 13){
            $salesAgents = $request->input('salesagents_users', []);
            $fundingmanagers = $request->input('fundingmanagers', []);

            $user_collibretor = null;

            foreach ($salesAgents as $salesAgent) {
                if ($salesAgent != "all"){
                    DB::table('user_collaborators')->insert([
                        'user_id'        => $salesAgent,
                        'collaborato_id' => $newuser,
                    ]);
                }

            }

            foreach ($fundingmanagers as $fundingmanager) {
                if ($fundingmanager != "all") {
                    DB::table('user_collaborators')->insert([
                        'user_id'        => $fundingmanager,
                        'collaborato_id' => $newuser,
                    ]);
                }
            }
        }
        //************************************************

        if ($newuser != null) {
            return redirect()->route('admin.users')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function addCustomer(Request $request)
    {
        dd('33');
        // dd(($request->birth));
        // dd(Carbon::parse(new DateTime('2020-04-01')));

        $rules = [
            'name'       => 'required',
            'SalesAgent' => 'required',
            'mobile'     => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            // 'sex' => 'required',
            //   'birth' => 'required',
            //   'work' => 'required',
            //   'salary_source' => 'required',
            //   'salary' => 'numeric',
        ];

        $customMessages = [
            'mobile.regex'        => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.digits'       => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'name.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'SalesAgent.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            //  'birth.required' => 'The birth date filed is required ',
        ];

        $this->validate($request, $rules, $customMessages);

        $mobile = $request->mobile;
        $checkmobile = DB::table('customers')->where('mobile', $mobile)->first();

        if (!empty($checkmobile)) {
            $userID = $request->SalesAgent;
            $name = $request->name;
            $sex = $request->sex;
            $birth = $request->birth;
            $birth_hijri = $request->birth_hijri;
            $age = $request->age;
            $work = $request->work;
            $salary_source = $request->salary_source;
            $salary = $request->salary;
            $support = $request->support;
            $rank = $request->rank;
            $madany = $request->madany_work;
            $job_title = $request->job_title;
            $askary_work = $request->askary_work;

            $customerId = DB::table('customers')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                [ //add it once use insertGetId
                  'user_id'          => $userID,
                  'name'             => $name,
                  'mobile'           => $mobile,
                  'sex'              => $sex,
                  'birth_date'       => $birth,
                  'birth_date_higri' => $birth_hijri,
                  'age'              => $age,
                  'work'             => $work,
                  'salary_id'        => $salary_source,
                  'salary'           => $salary,
                  'is_supported'     => $support,
                  'military_rank'    => $rank,
                  'madany_id'        => $madany,
                  'welcome_message'  => 2,
                  'job_title'        => $job_title,
                  'askary_id'        => $askary_work,
                ]);

            $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                [ //add it once use insertGetId
                  // 'customer_id' => $customerId,
                  'created_at' => (Carbon::now('Asia/Riyadh')),
                ]);

            $realID = DB::table('real_estats')->insertGetId([
                    // 'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                ]

            );

            $funID = DB::table('fundings')->insertGetId([
                    //'customer_id' => $customerId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                ]

            );

            $reqdate = (Carbon::now('Asia/Riyadh'));
            $searching_id = RequestSearching::create()->id;
            $reqID = DB::table('requests')->insertGetId([
                    'source'          => $request->reqsour,
                    'req_date'        => $reqdate,
                    'created_at'      => (Carbon::now('Asia/Riyadh')),
                    'user_id'         => $userID,
                    'customer_id'     => $customerId,
                    'collaborator_id' => $request->collaborator,
                    'searching_id'    => $searching_id,
                    'joint_id'        => $joinID,
                    'real_id'         => $realID,
                    'fun_id'          => $funID,
                    'statusReq'       => 0,
                    'agent_date'      => carbon::now(),
                ]

            );

            if ($customerId == true) {
                return redirect()->route('admin.allCustomers')->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Customer added successfully'));
            }
            else {
                return redirect()->route('admin.allCustomers')->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        else {

            return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'));
        }
    }

    public function getAgentCollberators(Request $request)
    {

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', $request->agentID)->get();

        $count = $collaborators->count();

        return response()->json(['collaborators' => $collaborators, 'count' => $count]);
    }

    public function addCustomerWithReq()
    {

        $salesAgents = DB::table('users')->where('role', 0)->where('status', 1)->get();

        $request_sources = DB::table('request_source')->get();

        return view('Admin.Customer.addCustomerWithReq', compact('salesAgents', 'request_sources'));
    }

    public function needActionReqsDone(Request $request)
    {

        $requests = DB::table('request_need_actions')->where('request_need_actions.status', 1)->join('customers', 'customers.id', '=', 'request_need_actions.customer_id')->join('users', 'users.id', '=', 'request_need_actions.agent_id')->select('request_need_actions.*',
            'customers.name as customer_name', 'users.name as user_name', 'customers.salary_id', 'customers.mobile')->count();

        $salesAgents = User::where('role', 0)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('Admin.Request.needActionReqsDone', compact('requests', 'salesAgents', 'worke_sources', 'request_sources'));
    }

    public function needActionReqs_datatableDone(Request $request)
    {

        $requests = DB::table('request_need_actions')->where('request_need_actions.status', 1)->join('customers', 'customers.id', '=', 'request_need_actions.customer_id')->join('requests', 'requests.id', '=', 'request_need_actions.req_id')->join('users', 'users.id', '=',
            'request_need_actions.agent_id')->select('request_need_actions.id as need_id', 'request_need_actions.action', 'request_need_actions.status', 'request_need_actions.created_at as need_created_at', 'requests.*', 'customers.name as customer_name', 'users.name as user_name',
            'customers.salary_id', 'customers.mobile')->orderBy('request_need_actions.created_at', 'DESC');

        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->editColumn('need_created_at', function ($row) {
            $data = $row->need_created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return 'جديد';
            }
            else {
                return 'تمت المعالجة';
            }
        })->rawColumns(['actions'])->make(true);
    }


    public function restReq(Request $request, $id)
    {

        $userID = (auth()->user()->id);

        $reqInfo = DB::table('requests')->where('id', $id)->first();

        if ($reqInfo->statusReq == 2) {
            $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
                $query->where('statusReq', 2);
            })->update(['statusReq' => 1, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //open request
        }
        else {
            if ($reqInfo->statusReq == 5) {
                $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
                    $query->where('statusReq', 5);
                })->update(['statusReq' => 3, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //open request
            }
            else {
                if ($reqInfo->statusReq == 8) {
                    $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
                        $query->where('statusReq', 8);
                    })->update(['statusReq' => 6, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //open request
                }
                else {
                    if ($reqInfo->statusReq == 11) {
                        $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
                            $query->where('statusReq', 11);
                        })->update(['statusReq' => 9, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //open request
                    }
                    else {
                        if ($reqInfo->statusReq == 14) {
                            $restRequest = DB::table('requests')->where('id', $id)->where(function ($query) {
                                $query->where('statusReq', 14);
                            })->update(['statusReq' => 12, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0]); //open request
                        }
                        else {
                            $restRequest = 0;
                        }
                    }
                }
            }
        }

        if ($restRequest == 1) {

            return redirect()->route('admin.archRequests')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function archReqArr(Request $request)
    {

        /*  $result = DB::table('requests')
            ->whereIn('id',  $request->array)
            ->update([
                'statusReq' => 30, //30 : admin delete the request
            ]);
        return response($result); // if 1: update succesfally
*/
    }

    public function otaredfemale(Request $request)
    {

        $requests = DB::table('requests')->where('collaborator_id', 17)->where('users.name', 'like', '%...%')->join('users', 'users.id', '=', 'requests.user_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name')->orderBy('req_date',
            'DESC')->count();

        return view('Admin.Request.otaredfemaleReqs', compact('requests'));
    }

    public function otaredfemale_datatable(Request $request)
    {

        $requests = DB::table('requests')->where('collaborator_id', 17)->where('users.name', 'like', '%...%')->join('users', 'users.id', '=', 'requests.user_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->join('customers', 'customers.id', '=',
            'requests.customer_id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="table-data-feature">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<button class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="zmdi zmdi-eye"></i></a></button>';
            }
            else {
                $data = $data.'<button class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="zmdi zmdi-eye"></i></a></button>';
            }
            $data = $data.'</div>';
            return $data;
        })->addColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->addColumn('status', function ($row) {

            if ($row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                switch ($row->payStatus) {
                    case 0:
                        $status = 'draft in funding manager';
                        break;
                    case 1:
                        $status = 'wating for sales maanger';
                        break;
                    case 2:
                        $status = 'funding manager canceled';
                        break;
                    case 3:
                        $status = 'rejected from sales maanger';
                        break;
                    case 4:
                        $status = 'wating for sales agent';
                        break;
                    case 5:
                        $status = 'wating for mortgage maanger';
                        break;
                    case 6:
                        $status = 'rejected from mortgage maanger';
                        break;
                    case 7:
                        $status = 'approve from mortgage maanger';
                        break;
                    case 8:
                        $status = 'mortgage manager canceled';
                        break;
                    case 9:
                        $status = 'The prepayment is completed';
                        break;
                    case 10:
                        $status = 'rejected from funding manager';
                        break;
                    default:
                        $status = 'Undefined';
                        break;
                }
            }
            else {
                switch ($row->statusReq) {
                    case 0:
                        $status = 'new req';
                        break;
                    case 1:
                        $status = 'open req';
                        break;
                    case 2:
                        $status = 'archive in sales agent req';
                        break;
                    case 3:
                        $status = 'wating sales manager req';
                        break;
                    case 4:
                        $status = 'rejected sales manager req';
                        break;
                    case 5:
                        $status = 'archive in sales manager req';
                        break;
                    case 6:
                        $status = 'wating funding manager req';
                        break;
                    case 7:
                        $status = 'rejected funding manager req';
                        break;
                    case 8:
                        $status = 'archive in funding manager req';
                        break;
                    case 9:
                        $status = 'wating mortgage manager req';
                        break;
                    case 10:
                        $status = 'rejected mortgage manager req';
                        break;
                    case 11:
                        $status = 'archive in mortgage manager req';
                        break;
                    case 12:
                        $status = 'wating general manager req';
                        break;
                    case 13:
                        $status = 'rejected general manager req';
                        break;
                    case 14:
                        $status = 'archive in general manager req';
                        break;
                    case 15:
                        $status = 'Canceled';
                        break;
                    case 16:
                        $status = 'Completed';
                        break;
                    case 17:
                        $status = 'draft in mortgage maanger';
                        break;
                    case 18:
                        $status = 'wating sales manager req';
                        break;
                    case 19:
                        $status = 'wating sales agent req';
                        break;
                    case 20:
                        $status = 'rejected sales manager req';
                        break;
                    case 21:
                        $status = 'wating funding manager req';
                        break;
                    case 22:
                        $status = 'rejected funding manager req';
                        break;
                    case 23:
                        $status = 'wating general manager req';
                        break;
                    case 24:
                        $status = 'cancel mortgage manager req';
                        break;
                    case 25:
                        $status = 'rejected general manager req';
                        break;
                    case 26:
                        $status = 'Completed';
                        break;
                    case 27:
                        $status = 'Canceled';
                        break;
                    default:
                        $status = 'Undefined';
                        break;
                }
            }
            return MyHelpers::admin_trans(auth()->user()->id, $status);
        })->addColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->make(true);
    }

    public function otaredmale(Request $request)
    {

        $requests = DB::table('requests')->where('collaborator_id', 17)->where('users.name', 'like', '%،،،%')->join('users', 'users.id', '=', 'requests.user_id')->join('customers', 'customers.id', '=', 'requests.customer_id')->select('requests.*', 'customers.name', 'users.name')->orderBy('req_date',
            'DESC')->count();

        return view('Admin.Request.otaredmaleReqs', compact('requests'));
    }

    public function otaredmale_datatable(Request $request)
    {

        $requests = DB::table('requests')->where('collaborator_id', 17)->where('users.name', 'like', '%،،،%')->join('users', 'users.id', '=', 'requests.user_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')->join('customers', 'customers.id', '=',
            'requests.customer_id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')->orderBy('req_date', 'DESC')->get();

        return Datatables::of($requests)->addColumn('action', function ($row) {
            $data = '<div class="table-data-feature">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<button class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="zmdi zmdi-eye"></i></a></button>';
            }
            else {
                $data = $data.'<button class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="zmdi zmdi-eye"></i></a></button>';
            }
            $data = $data.'</div>';
            return $data;
        })->addColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->addColumn('status', function ($row) {

            if ($row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                switch ($row->payStatus) {
                    case 0:
                        $status = 'draft in funding manager';
                        break;
                    case 1:
                        $status = 'wating for sales maanger';
                        break;
                    case 2:
                        $status = 'funding manager canceled';
                        break;
                    case 3:
                        $status = 'rejected from sales maanger';
                        break;
                    case 4:
                        $status = 'wating for sales agent';
                        break;
                    case 5:
                        $status = 'wating for mortgage maanger';
                        break;
                    case 6:
                        $status = 'rejected from mortgage maanger';
                        break;
                    case 7:
                        $status = 'approve from mortgage maanger';
                        break;
                    case 8:
                        $status = 'mortgage manager canceled';
                        break;
                    case 9:
                        $status = 'The prepayment is completed';
                        break;
                    case 10:
                        $status = 'rejected from funding manager';
                        break;
                    default:
                        $status = 'Undefined';
                        break;
                }
            }
            else {
                switch ($row->statusReq) {
                    case 0:
                        $status = 'new req';
                        break;
                    case 1:
                        $status = 'open req';
                        break;
                    case 2:
                        $status = 'archive in sales agent req';
                        break;
                    case 3:
                        $status = 'wating sales manager req';
                        break;
                    case 4:
                        $status = 'rejected sales manager req';
                        break;
                    case 5:
                        $status = 'archive in sales manager req';
                        break;
                    case 6:
                        $status = 'wating funding manager req';
                        break;
                    case 7:
                        $status = 'rejected funding manager req';
                        break;
                    case 8:
                        $status = 'archive in funding manager req';
                        break;
                    case 9:
                        $status = 'wating mortgage manager req';
                        break;
                    case 10:
                        $status = 'rejected mortgage manager req';
                        break;
                    case 11:
                        $status = 'archive in mortgage manager req';
                        break;
                    case 12:
                        $status = 'wating general manager req';
                        break;
                    case 13:
                        $status = 'rejected general manager req';
                        break;
                    case 14:
                        $status = 'archive in general manager req';
                        break;
                    case 15:
                        $status = 'Canceled';
                        break;
                    case 16:
                        $status = 'Completed';
                        break;
                    case 17:
                        $status = 'draft in mortgage maanger';
                        break;
                    case 18:
                        $status = 'wating sales manager req';
                        break;
                    case 19:
                        $status = 'wating sales agent req';
                        break;
                    case 20:
                        $status = 'rejected sales manager req';
                        break;
                    case 21:
                        $status = 'wating funding manager req';
                        break;
                    case 22:
                        $status = 'rejected funding manager req';
                        break;
                    case 23:
                        $status = 'wating general manager req';
                        break;
                    case 24:
                        $status = 'cancel mortgage manager req';
                        break;
                    case 25:
                        $status = 'rejected general manager req';
                        break;
                    case 26:
                        $status = 'Completed';
                        break;
                    case 27:
                        $status = 'Canceled';
                        break;
                    default:
                        $status = 'Undefined';
                        break;
                }
            }
            return MyHelpers::admin_trans(auth()->user()->id, $status);
        })->addColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->make(true);
    }

    public function waitingReqsNew(Request $request)
    {
        $requests = DB::table('request_waiting_lists')->where('request_waiting_lists.status', 0)
            ->join('customers', 'customers.id', '=', 'request_waiting_lists.customer_id')
            ->join('users', 'users.id', '=', 'request_waiting_lists.agent_id')
            ->select('request_waiting_lists.*',
                'customers.name as customer_name', 'users.name as user_name', 'customers.salary_id', 'customers.mobile')->count();

        $salesAgents2 = DB::table('request_waiting_lists')->where('request_waiting_lists.status', 0)
            ->join('users', 'users.id', '=', 'request_waiting_lists.agent_id')
            ->distinct('users.id')->select('users.name', 'users.id')->get();

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        return view('Admin.Request.waitingReqsNew', compact('requests', 'salesAgents', 'salesAgents2', 'classifcations_sa'));
    }

    public function waitingReqs_datatableNew(Request $request)
    {
        $requests = DB::table('request_waiting_lists')
            ->where('request_waiting_lists.status', 0)
            ->join('customers', 'customers.id', '=', 'request_waiting_lists.customer_id')
            ->join('requests', 'requests.id', '=', 'request_waiting_lists.req_id')
            ->join('request_source', 'request_source.id', '=', 'requests.source')
            ->join('users', 'users.id', '=',
                'request_waiting_lists.agent_id')
            ->select('request_waiting_lists.id as need_id', 'request_waiting_lists.action', 'request_waiting_lists.status', 'request_waiting_lists.created_at as need_created_at', 'requests.*', 'customers.name as customer_name', 'users.name as user_name',
                'customers.salary_id', 'customers.mobile', 'request_source.value as request_source')
            ->orderBy('request_waiting_lists.created_at', 'DESC');

        if ($request->get('action')) {
            $requests = $requests->where('request_waiting_lists.action', $request->get('action'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        $xses = [
            'sa' => 'class_id_agent',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }
        /*if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('actions', function ($row) {

            if ($row->status == 0) {
                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->need_id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                       <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->need_id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                       <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                if ($row->type != 'رهن-شراء' && $row->type != 'شراء-دفعة' && $row->statusReq != 16 && $row->statusReq != 15 && $row->statusReq != 14) {
                    $data = $data.'<span class="item pointer" id="move" data-need="'.$row->need_id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                                           <i class="fas fa-random"></i>
                                       </span> ';
                }
                $data = $data.'<span class="item pointer" id="moveToDone"  data-id="'.$row->need_id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'notification done').'">
                   <i class="fas fa-check"></i> </span> ';

                $data = $data.'</div>';
            }
            else {
                $data = '';
            }
            return $data;
        })->editColumn('need_created_at', function ($row) {
            $data = $row->need_created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = $row->request_source;
            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $row->request_source.' - '.$collInfo->name;
                }
                else {
                    $data = $row->request_source;
                }
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return 'جديد';
            }
            else {
                return 'تمت المعالجة';
            }
        })->rawColumns(['actions'])->make(true);
    }

    public function waitingReqsDone(Request $request)
    {

        $requests = DB::table('request_waiting_lists')->where('request_waiting_lists.status', 1)->join('customers', 'customers.id', '=', 'request_waiting_lists.customer_id')->join('users', 'users.id', '=', 'request_waiting_lists.agent_id')->select('request_waiting_lists.*',
            'customers.name as customer_name', 'users.name as user_name', 'customers.salary_id', 'customers.mobile')->count();
        $salesAgents = User::where('role', 0)->get();

        return view('Admin.Request.waitingReqsDone', compact('requests', 'salesAgents',));
    }

    public function waitingReqs_datatableDone(Request $request)
    {

        $requests = DB::table('request_waiting_lists')->where('request_waiting_lists.status', 1)
            ->join('customers', 'customers.id', '=', 'request_waiting_lists.customer_id')
            ->join('requests', 'requests.id', '=', 'request_waiting_lists.req_id')
            ->join('request_source', 'request_source.id', '=', 'requests.source')
            ->join('users', 'users.id', '=',
                'request_waiting_lists.agent_id')
            ->select('request_waiting_lists.id as need_id', 'request_waiting_lists.action', 'request_waiting_lists.status', 'request_waiting_lists.created_at as need_created_at', 'requests.*', 'customers.name as customer_name', 'users.name as user_name',
                'customers.salary_id', 'customers.mobile', 'request_source.value as request_source')->orderBy('request_waiting_lists.created_at', 'DESC');

        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->editColumn('created_at', function ($row) {
            $data = $row->need_created_at;

            return $row->need_created_at;
        })->editColumn('source', function ($row) {
            $data = $row->request_source;
            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $row->request_source.' - '.$collInfo->name;
                }
                else {
                    $data = $row->request_source;
                }
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return 'جديد';
            }
            else {
                return 'تمت المعالجة';
            }
        })->rawColumns(['actions'])->make(true);
    }


    public function addToWaitingReq(Request $request)
    {
        $check = false;
        $request = \App\request::find($request->id);
        $message = 'طلب مضاف من قبل مدير النظام';
        $check = MyHelpers::checkDublicateOfWaitingReq($request->id);
        if ($check) {
            MyHelpers::addWaitingReqWithoutConditions($message, $request->id);
        }
        return response()->json([
            'check'   => $check,
            'success' => true,
            'message' => 'تم إضافة الطلب ',
        ]);
    }

    public function addToWaitingReqArray(Request $request)
    {
        $check = false;
        foreach ($request->array as $request_array) {
            $request = \App\request::find($request_array);
            $message = 'طلب مضاف من قبل مدير النظام';
            $check = MyHelpers::checkDublicateOfWaitingReq($request->id);
            if ($check) {
                MyHelpers::addWaitingReqWithoutConditions($message, $request->id);
            }
        }
        return response()->json([
            'check'   => $check,
            'success' => true,
            'message' => 'تم إضافة الطلب ',
        ]);
    }

    public function moveWatingReqToAnotherArrayAgent(Request $request)
    {
        $counter = 0;
        $i = 0;
        $salesAgents = [];
        $waitingReqs = RequestWaitingList::whereIn('id', $request->id)->pluck('req_id')->toArray();
        $requests_data = DB::table('requests')->whereIn('id', $waitingReqs)->get();

        if ($request->agents_ids == '') {
            $salesAgents = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        }
        else {
            $salesAgents = array_merge($salesAgents, $request->agents_ids);
        }
        foreach ($requests_data as $reqInfo) {
            #check If there is need action req
            $this->checkIfThereIsWaitingReq($reqInfo->id);

            if (count($salesAgents) == $i) {
                $i = 0;
            }
            $updatereq = 0;
            $prev_user = $reqInfo->user_id;
            $prev_user = str_replace(' ', '', $prev_user);
            if (($key = array_search($prev_user, $salesAgents)) !== false) {
                unset($salesAgents[$key]); // to remove same user to dublicate with same request
            }
            if (count($salesAgents) == 0)  //check if there's no avalibile agent
            {
                return response()->json(['updatereq' => 2, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'No Avaliable Agents')]);
            }

            $reqID = $reqInfo->id;
            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();
            /////////////////////////////////////////////////////////////////
            //MOVE NEW AND READ TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {
                $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $salesAgents[$i],
                    'created_at' => carbon::now(),
                ]);

                $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            //////MOVE REPLAID TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();
            if (count($getAllTasksIds) > 0) {
                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $salesAgents[$i],
                            'user_id'   => $task->user_id,
                        ]);

                        $newContent = task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }
            ///////////////////////////////////////////////////////
            $customerID = $reqInfo->customer_id;
            $updatereq = DB::table('requests')->where('id', $reqID)->update([
                'user_id'                 => $salesAgents[$i],
                'statusReq'               => 0,
                'agent_date'              => carbon::now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);
            if ($updatereq) {
                $counter++;
            }
            if ($reqInfo->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $salesAgents[$i], //active
                ]);
            }
            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                 'recived_id' => $salesAgents[$i],
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 0,
                                                 'req_id'     => $reqID,
                                                ]);

            $agenInfo = DB::table('users')->where('id', $salesAgents[$i])->first();
            //  $pwaPush = MyHelpers::pushPWA($salesAgents[$i], ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);
            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $salesAgents[$i],
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'req_id'         => $reqID,
                'class_id_agent' => $reqInfo->class_id_agent,
                'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
            ]);
            DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                                'recived_id' => $prev_user,
                                                'req_id'     => $reqID,
            ])->delete();
            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $salesAgents[$i];
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
           // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            #move customer's messages to new agent
            MyHelpers::movemessage($customerID, $salesAgents[$i], $prev_user);

            #Remove request from Quality & Need Action Req once moved it
            #1::Remove Req from Quality
            if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
                $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
                if ($qualityReqDelte == 0) {
                    MyHelpers::updateQualityReqToCompleteByReqID($reqID);
                }
            }
            #2::Remove from Need Action Req
            MyHelpers::removeNeedActionReqByReqID($reqID);

            $i++;
        }
        if ($counter == 0) {
            return response()->json(['updatereq' => 0, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
        }
        return response()->json(['counter' => $counter, 'updatereq' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

    }

    public function ipAddresss()
    {
        //$ips = DB::table('otp_request')
        //    ->select('id', 'ip', DB::raw('COUNT(mobile) as count'))
        //    ->groupBy('ip')
        //    ->having("count", ">", 1)->get();
        //$ips = count($ips);
        $ips = 0;
        return view('Admin.IpAddresses.index', compact('ips'));
    }

    public function ipAddresssMerge(Request $request)
    {
        $request_ = \App\request::find($request->request_id);

        $ip = DB::table('otp_request')
            ->where('mobile', '<>', $request_->customer->mobile)
            ->where('ip', $request->ip)
            ->pluck('mobile')->toArray();

        $mobiles = customer::whereIn('mobile', $ip)
            ->where('id', '<>', $request_->customer_id)->pluck('mobile')
            ->toArray();

        $ids = customer::whereIn('mobile', $ip)
            ->where('id', '<>', $request_->customer_id)
            ->pluck('id')->toArray();

        $mobiles_other = CustomersPhone::whereIn('customer_id', $ids)
            ->pluck('mobile')->toArray();

        foreach ($mobiles_other as $cust) {
            $inputs['mobile'] = substr($cust, -9);

            if ($request_->customer->mobile != $inputs['mobile']) {
                CustomersPhone::firstOrCreate([
                    'customer_id' => $request_->customer_id,
                    'mobile'      => $inputs['mobile'],
                ], [
                    'request_id'  => $request_->id,
                    'customer_id' => $request_->customer_id,
                    'mobile'      => $inputs['mobile'],
                ]);
            }
        }
        foreach ($mobiles as $cust) {
            $inputs['mobile'] = substr($cust, -9);

            if ($request_->customer->mobile != $inputs['mobile']) {
                CustomersPhone::firstOrCreate([
                    'customer_id' => $request_->customer_id,
                    'mobile'      => $inputs['mobile'],
                ], [
                    'request_id'  => $request_->id,
                    'customer_id' => $request_->customer_id,
                    'mobile'      => $inputs['mobile'],
                ]);
            }

        }

        DB::table('otp_request')
            ->where('mobile', '<>', $request_->customer->mobile)
            ->where('ip', $request->ip)->delete();

        $requests = \App\request::with('user', 'customer')
            ->whereIn('customer_id', $ids)
            ->delete();

        return redirect()->route('admin.ips');
    }

    public function ipAddresssDetails($id)
    {
        $mobiles = OtpRequest::where('ip', $id)->pluck('mobile')->toArray();
        //dd($mobiles);
        //$ip = DB::table('otp_request')->where('ip', $id)->pluck('mobile')->toArray();
        //$customers = customer::whereIn('mobile', $ip)->pluck('id')->toArray();
        //->whereIn('customer_id', $customers)->get();
        $requests = \App\Models\Request::with('user', 'customer', 'requestSource')->whereHas('customer', fn(Builder $b) => $b->whereIn('mobile', $mobiles))->get();
        return view('Admin.IpAddresses.single', compact('requests', 'id'));
    }

    public function salesAgents(Request $request)
    {
        if($request->sales_manager_id != null){
            $salesAgents=User::where('manager_id',$request->sales_manager_id)->get();
        }else{
            $salesAgents = User::where('role', 0)->get();
        }
        return response()->json(['salesAgents'=>$salesAgents ]);
    }

    public function multipleSalesAgents(Request $request)
    {
        if(isset($request->multiple_sales_manager_ids)){
            $salesAgents=User::whereIn('manager_id',$request->multiple_sales_manager_ids)->get();
        }else{
            $salesAgents = User::where('role', 0)->get();
        }
        return response()->json(['salesAgents'=>$salesAgents ]);
    }

    public function allowRecievedSalesManagers(Request $request)
    {
        if($request->active_sales_managers == 0){
            $activeSalesManagers=User::where('role',1)->get();
        }else{
            $activeSalesManagers=User::where('role',1)->where('status',1)->get();
        }
        return response()->json(['activeSalesManagers' => $activeSalesManagers ]);
    }


    public function allowRecievedSalesAgents(Request $request)
    {
        if($request->active_sales_agents == 0){
            if(isset($request->sales_agents)){
                $activeSalesAgents=User::whereIn('id',$request->sales_agents)->get();
            }else{
                $activeSalesAgents = User::where('role', 0)->get();
            }
        }else{
            if(isset($request->sales_agents)){
                $activeSalesAgents=User::whereIn('id',$request->sales_agents)->where('status',1)->get();
            }else{
                $activeSalesAgents = User::where('role', 0)->get();
            }
        }
        return response()->json(['activeSalesAgents' => $activeSalesAgents ]);
    }

}

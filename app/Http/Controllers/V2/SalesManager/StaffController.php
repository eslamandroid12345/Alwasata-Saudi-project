<?php

namespace App\Http\Controllers\V2\SalesManager;

use App\Area;
use App\cities as City;
use App\District;
use App\Email;
use App\EmailUser;
use App\EmployeeControl;
use App\EmployeeFile;
use App\Helpers\MyHelpers;
use App\Models\SmsLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;
class StaffController extends Controller
{
    public function __construct()
    {
        \View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }
    public function index()
    {
        return view('V2.SalesManager.Staff.index');
    }
    public function indexDataTable()
    {
        $managerID = (auth()->user()->id);
        $users = User::where('manager_id',$managerID)->get();

        return Datatables::of($users)
            ->addColumn('action', function ($row) {
                $data = '<div class="tableAdminOption">';

                    $data = $data."<span class='pointer' title='عرض ملف الموظف'>
                                   <a href='".route('sales.manager.user.profile', $row->id)."'>
                                    <i class='fas fa-user-shield'></i>
                                    </a>
                                </span> ";

                    //if ($row->role !=20) {
                    //    $data = $data.'<span class="pointer " title="'.MyHelpers::admin_trans(auth()->user()->id, 'Email Manage').'">
                    //                   <a href="'.route('sales.manager.getAllMailsNotifications').'?user_id='.$row->id.'">
                    //                    <i class="fas fa-envelope"></i>
                    //                    </a>
                    //                </span> ';

                        //$data = $data."<span class='pointer' title='".__('global.app_chats')."'>
                        //               <a href='".route('V2.Admin.userMessages', $row->id)."'>
                        //                <i class='fas fa-chalkboard-teacher'></i>
                        //                </a>
                        //            </span> ";
                    //}
                return $data.'</div>';
            })
            ->editColumn('role', function ($row) {
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
            })
            ->editColumn('status', function ($row) {
                if ($row->allow_recived == 0) {
                    $data = MyHelpers::admin_trans(auth()->user()->id, 'not active');
                }
                else {
                    $data = 'نشط';
                }
                return $data;
            })
            ->make(true);
    }
    public function profile($userId,$pdf=null) {
        $user = \App\User::find($userId);
        $controls = collect(EmployeeControl::isActive()->where('type','<>','subsection')->get())->groupBy('type');

        $files = EmployeeFile::where(['user_id' => $userId,'deleted_at' => null])->get();
        $cities = City::all();
        $areas = Area::all();
        $districts = District::all();
        $data=[
            'districts'  => $districts,
            'areas'  => $areas,
            'cities'  => $cities,
            'user'  => $user,
            'files'  => $files,
            'controls'   =>$controls
        ];
        if ($pdf){
            $pdf = PDF::loadView('HumanResource.Users.pdf',$data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');
            return $pdf->stream('HR-'.$user->name.'-'.$userId.'.pdf');
        }

        return view('HumanResource.Users.profile',$data);
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
        return redirect()->route('sales.manager.staff_index')->with('message2', 'تمت التعديل بنجاح');
    }
}

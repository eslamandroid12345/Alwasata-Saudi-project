<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\SplitController;

use App\Announcement;
use App\AnnounceUser;
use App\AskAnswer;
use App\classifcation;
use App\customer;
use App\CustomersPhone;
use App\District;
use App\funding_source;
use App\helpDesk;
use App\Model\RequestSearching;
use App\Models\Classification;
use App\Models\ClassificationAlertSchedule;
use App\Models\RequestHistory;
use App\Models\RequestRecord;
use App\Models\User;
use App\quality_req;
use App\RejectionsReason;
use App\salary_source;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MyHelpers;

trait SplitAdminTwoControllerTrait
{

    public function addReqToQuality(Request $request)
    {
        if ($request->quality == 0){
            $qualityId = getLastQualityOfDistribution();
            setLastQualityOfDistribution($qualityId);
            $request->merge([
                  'quality'  => $qualityId
            ]);
        }
        //if (MyHelpers::checkQualityReq($request->id)) {
        //    $checkAddingStatus = true;
        //}
        //else {
        //    $quality_req_id = MyHelpers::checkQualityReqWithArchivedUser($request->id);
        //    if ($quality_req_id != false) {
//
        //        //Update Current quality req in archived quality to complete before creating another one
        //        MyHelpers::updateQualityReqToComplete($quality_req_id);
        //        $checkAddingStatus = true;
        //    }
        //}

        $check_quality_req = quality_req::query()->where('req_id',  $request->id)
        ->whereIn('status', [0, 1, 2, 4, 5])
        ->first();

        if (!empty($check_quality_req)){
            //check if selected quality is same with active quality req.
            if ($check_quality_req->user_id ==  $request->quality)
                return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'There is request Under Processing'), 'status' => 0]);

            # not worked yet
            if (in_array($check_quality_req->status, [0,1])){ # not worked yet
                $check_quality_req->delete();
                RequestHistory::create([
                    'req_id'         => $request->id,
                    'user_id'        => $check_quality_req->user_id,
                    'recive_id'      => null,
                    'title'          => RequestHistory::UPDATE_QUALITY_REQUEST,
                    'content'        => RequestHistory::DELETE_QUALITY_REQUEST,
                    'history_date'   => Carbon::now(),
                ]);

            }
            else{
                quality_req::query()->where('id', $check_quality_req->id)->update(['status' => 3]);
                #$check_quality_req->update(['status',3]); #marked as completed
                RequestHistory::create([
                    'req_id'         => $request->id,
                    'user_id'        => $check_quality_req->user_id,
                    'recive_id'      => null,
                    'title'          => RequestHistory::UPDATE_QUALITY_REQUEST,
                    'content'        => RequestHistory::MARK_AS_COMPLETED,
                    'history_date'   => Carbon::now(),
                ]);
            }

        }


            // remove need action req if existed(will nt allowed to recived same request with admin & quality)
            //$needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($request->id);
            //if ($needReq != 'false') {
            //    MyHelpers::removeNeedActionReq($needReq->id);
            //}
                $newReq = quality_req::create([
                    'req_id'     => $request->id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'user_id'    => $request->quality,
                    'status'     => 0,
                    'is_followed'=> 0,
                    'allow_recive'=> 1,
                ]);
                DB::table('request_histories')->insert([
                    'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                    'user_id'      => null,
                    'recive_id'    => $request->quality,
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id'       => $request->id,
                    'content'      => null,
                ]);
                DB::table('notifications')->insert([
                    'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                    'recived_id' => $newReq->user_id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 0,
                    'req_id'     => $newReq->id,
                ]);
                if ($newReq) {
                    if (!MyHelpers::checkIfThereDailyPrefromenceRecord($request->quality)) {
                        $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($request->quality);
                    }
                    MyHelpers::incrementDailyPerformanceColumn($request->quality, 'received_basket', $newReq->id);
                    return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'), 'status' => 1]);
                }
                else {
                    return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
                }
    }

    public function postMoveNeedActionsToQuality(Request $request)
    {
        return $this->addReqToQualityArray($request);
    }

    public function getTheQualityUserTurn($turn,$qualities) {

    }
    public function getTheQualitiesUserTurn($turn,$qualities,$requests) {
        $array =[];
        #TODO : check if not user id
        foreach ($requests as $key=>$request) {

            if ($turn != "false"){
                $qualityId = getLastQualityOfDistribution();
                setLastQualityOfDistribution($qualityId);
                $array[$key]= $qualityId;
            }else{
                $keys = $key%count($qualities);
                $array[$key]= $qualities[$keys];
            }
        }
        return $array;
    }
    public function addReqToQualityArray(Request $request)
    {
        if($request->quality != null){
            foreach ($request->array as $key => $requestId) {

                $check_quality_req = quality_req::query()->where('req_id',  $requestId)
                ->whereIn('status', [0, 1, 2, 4, 5])
                ->first();

                if (!empty($check_quality_req)){
                    //check if selected quality is same with active quality req.
                    if ($check_quality_req->user_id ==  $request->quality)
                        continue;

                    # not worked yet
                    if (in_array($check_quality_req->status, [0,1])){ # not worked yet
                        $check_quality_req->delete();
                        RequestHistory::create([
                            'req_id'         => $requestId,
                            'user_id'        => $check_quality_req->user_id,
                            'recive_id'      => null,
                            'title'          => RequestHistory::UPDATE_QUALITY_REQUEST,
                            'content'        => RequestHistory::DELETE_QUALITY_REQUEST,
                            'history_date'   => Carbon::now(),
                        ]);
                    }
                    else{
                        quality_req::query()->where('id', $check_quality_req->id)->update(['status' => 3]);
                        #$check_quality_req->update(['status',3]); #marked as completed
                        RequestHistory::create([
                            'req_id'         => $requestId,
                            'user_id'        => $check_quality_req->user_id,
                            'recive_id'      => null,
                            'title'          => RequestHistory::UPDATE_QUALITY_REQUEST,
                            'content'        => RequestHistory::MARK_AS_COMPLETED,
                            'history_date'   => Carbon::now(),
                        ]);
                    }

                }
                $newReq = quality_req::create([
                    'req_id'     => $requestId,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'user_id'    => $request->quality,
                    'status'     => 0,
                    'is_followed'=> 0,
                    'allow_recive'=> 1,
                ]);
                DB::table('request_histories')->insert([
                    'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                    'user_id'      => null,
                    'recive_id'    => $request->quality,
                    'history_date' => (Carbon::now('Asia/Riyadh')),
                    'req_id'       => $requestId,
                    'content'      => null,
                ]);
                DB::table('notifications')->insert([
                    'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                    'recived_id' => $newReq->user_id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'type'       => 0,
                    'req_id'     => $newReq->id,
                ]);

                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($request->quality)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($request->quality);
                }
                MyHelpers::incrementDailyPerformanceColumn($request->quality, 'received_basket', $newReq->id);
            }
        }
        return response()->json(['success'=>"تمت الاضافة"]);
        //$requests = $request->array;
        //$ele  = array_pop($requests);
        //if ($ele != 0){
        //    $requests = $request->array;
        //}
        //$quality = $this->getTheQualitiesUserTurn($request->turn,$request->quality,$requests);
        //$checkAddingStatus = false;
        //$move_count = 0;
        //$request_count = count($request->array);
        //foreach ($requests as $key=>$ids) {
        //    if (MyHelpers::checkQualityReq($ids)) {   // true => no exists , false => exists
        //        $checkAddingStatus = true;
        //    }else {
        //        $quality_req_id = MyHelpers::checkQualityReqWithArchivedUser($ids);
        //        if ($quality_req_id != false) {
        //            //Update Current quality req in archived quality to complete before creating another one
        //            MyHelpers::updateQualityReqToComplete($quality_req_id);
        //            $checkAddingStatus = true;
        //        }
        //    }
        //    if ($checkAddingStatus) {
        //        // remove need action req if existed(will nt allowed to received same request with admin & quality)
        //        $needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($ids);
        //        if ($needReq != 'false') {
        //            MyHelpers::removeNeedActionReq($needReq->id);
        //        }
        //        $checkUser = MyHelpers::checkQualityUser($ids, $request->quality);
        //        if ($checkUser == "true") {
        //            $newReq = quality_req::create([
        //                'req_id'     => $ids,
        //                'created_at' => (Carbon::now('Asia/Riyadh')),
        //                'user_id'    => $quality[$key],
        //            ]);
        //            $move_count++;
        //            DB::table('request_histories')->insert([
        //                'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
        //                'user_id'      => null,
        //                'recive_id'    => $quality[$key],
        //                'history_date' => (Carbon::now('Asia/Riyadh')),
        //                'req_id'       => $request->id,
        //                'content'      => null,
        //            ]);
        //
        //            DB::table('notifications')->insert([
        //                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
        //                'recived_id' => $newReq->user_id,
        //                'created_at' => (Carbon::now('Asia/Riyadh')),
        //                'type'       => 0,
        //                'req_id'     => $newReq->id,
        //            ]);
        //            //DB::table('users')->where('id', $newReq->user_id)->first();
        //        }
        //        else {
        //            $newReq = quality_req::create([
        //                'req_id'     => $request->id,
        //                'created_at' => (Carbon::now('Asia/Riyadh')),
        //                'user_id'    => $checkUser,
        //            ]);
        //            $move_count++;
        //            DB::table('request_histories')->insert([
        //                'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
        //                'user_id'      => null,
        //                'recive_id'    => $checkUser,
        //                'history_date' => (Carbon::now('Asia/Riyadh')),
        //                'req_id'       => $request->id,
        //                'content'      => null,
        //            ]);
        //            DB::table('notifications')->insert([
        //                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
        //                'recived_id' => $newReq->user_id,
        //                'created_at' => (Carbon::now('Asia/Riyadh')),
        //                'type'       => 0,
        //                'req_id'     => $newReq->id,
        //            ]);
        //            //$agenInfo = DB::table('users')->where('id', $newReq->user_id)->first();
        //            //$pwaPush = MyHelpers::pushPWA($newReq->user_id, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'qualityManager', 'fundingreqpage', $newReq->id);
        //        }
        //    }
        //
        //}
        //return response()->json(['move_count' => $move_count, 'request_count' => $request_count]);
    }

    public function openFile($id)
    {

        $document = DB::table('documents')->where('id', '=', $id)->first();

        $filename = $document->location;
        return response()->file(storage_path('app/public/'.$filename));
    }

    public function downloadFile($id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();

        $filename = $document->location;
        //return Storage::download('app/public/' . $document->location, $document->filename);
        return response()->download(storage_path('app/public/'.$filename)); // download
    }

    public function uploadFile(Request $request)
    {

        $rules = [
            'name' => 'required',
            'file' => 'required|file|max:10240',
        ];

        $customMessages = [
            'file.max'      => MyHelpers::admin_trans(auth()->user()->id, 'Should not exceed 10 MB'),
            'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'file.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $name = $request->name;
        $reqID = $request->id;
        $userID = auth()->user()->id;
        $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $file = $request->file('file');

        // generate a new filename. getClientOriginalExtension() for the file extension
        $filename = $name.time().'.'.$file->getClientOriginalExtension();

        // save to storage/app/photos as the new $filename
        $path = $file->storeAs('documents', $filename);

        DB::table('documents')->insertGetId([
            'filename'    => $name,
            'location'    => $path,
            'upload_date' => $upload_date,
            'req_id'      => $reqID,
            'user_id'     => $userID,
        ]);

        $documents = DB::table('documents')->where('req_id', '=', $reqID)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
        ->select('documents.*', 'users.name')->get();

        return response()->json($documents);
    }

    public function allAnnouncements()
    {
        $announcements = Announcement::get();
        return view('Admin.Announcements.allAnnouncements', compact('announcements'));
    }

    public function allAnnouncements_datatable()
    {
        $announcements = Announcement::all();
        return Datatables::of($announcements)->setRowId(function ($announcements) {
            return $announcements->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            if ($row->status == 1) {
                $data = $data.'<span class="item pointer Green" id="active" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'not active').'">
                                    <i class="fas fa-exclamation-circle"></i></a>
                                </span>';
            }
            else {
                $data = $data.'<span class="item pointer Red" id="active" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'active').'">
                                     <i class="fas fa-exclamation-circle"></i></a>
                                </span>';
            }

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                <a href="'.route('admin.openAnnouncePage', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                <a href="'.route('admin.editAnnouncePage', $row->id).'"><i class="fas fa-edit"></i></a></span>';

            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->editColumn('end_at', function ($row) {
            return $row->end_at ?? '-';
        })->editColumn('status', function ($row) {
            if ($row->status == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'not active');
            }
            else {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'active');
            }
            return $data;
        })->make(true);
    }

    public function addAnnouncePage()
    {
        $users = User::where('status', 1)->where('role', '<>', 1)->get();
        $managers = User::where(['status' => 1, 'role' => 1])->get();
        return view('Admin.Announcements.addAnnouncePage', compact('users', 'managers'));
    }

    public function addNewAnnounce(Request $request)
    {

        $rules = [
            'content'    => 'required',
            'attachment' => 'file|max:10240',
        ];

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'attachment.max'   => MyHelpers::admin_trans(auth()->user()->id, 'Should not exceed 10 MB'),
        ];

        $this->validate($request, $rules, $customMessages);

        $path = null;
        $attachment = $request->file('attachment');

        if ($attachment != null) {
            $filename = 'announce'.time().'.'.$attachment->getClientOriginalExtension();
            $path = $attachment->storeAs('announcements', $filename);
        }

        $newAnnounce = Announcement::create([
            'content'    => $request->get('content'),
            'status'     => $request->status,
            'color'      => $request->color,
            'end_at'     => $request->end_at,
            'attachment' => $path,

        ]);

        $users = $request->input('users', []);

        foreach ($users as $user) {
            $announceUser = DB::table('announce_users')->insert([
                'announce_id' => $newAnnounce->id,
                'user_id'     => $user,
            ]);
        }

        if ($request->has('managers')) {
            foreach ($request->managers as $manager) {
                $announceUser = AnnounceUser::firstOrCreate([
                    'announce_id' => $newAnnounce->id,
                    'user_id'     => $manager,
                ], [
                    'announce_id' => $newAnnounce->id,
                    'user_id'     => $manager,
                ]);

                foreach (User::where('manager_id', $manager)->get() as $item) {
                    AnnounceUser::firstOrCreate([
                        'announce_id' => $newAnnounce->id,
                        'user_id'     => $item->id,
                    ], [
                        'announce_id' => $newAnnounce->id,
                        'user_id'     => $item->id,
                    ]);
                }
            }
        }

        if ($request->all =="not"){
            $rol = [0,1,2,3,4,5,7,8,11,12];
        }elseif ($request->all =="all"){
            $rol = [0,1,2,3,4,5,6,7,8,11,12,13];
        }else{
            $rol = [];
        }
        $roles = $request->input('roles', $rol);
        foreach ($roles as $role) {

            if ($role == 'sa') {
                $role = 0;
            }
            DB::table('announce_role')->insert([
                'announce_id' => $newAnnounce->id,
                'role'        => $role,
            ]);
        }

        if ($newAnnounce != null) {
            return redirect()->route('admin.announcements')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function updateAnnounceStatus(Request $request)
    {

        $announce = Announcement::where('id', $request->id)->first();

        if ($announce->status == 0) {
            //active
            $updateResult = Announcement::where('id', $request->id)->update([
                'status' => 1,
            ]);
        }
        else {
            //deactive
            $updateResult = Announcement::where('id', $request->id)->update([
                'status' => 0,
            ]);
        }
        return response($updateResult);
    }

    public function deleteAnnounce(Request $request)
    {

        $announce = Announcement::where('id', $request->id)->delete();
        if ($announce == 0) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $announce]);
        }
        // if 1: update successfully
        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Successfully'), 'status' => 1]);

    }

    public function editAnnouncePage($id)
    {
        $announce = Announcement::where('id', $id)->first();
        $managers = DB::table('announce_users')->where('announce_id', $id)->join('users', 'users.id', 'announce_users.user_id')->where("users.role", 1)->pluck('users.id')->toArray();
        $users = DB::table('announce_users')->where('announce_id', $id)->join('users', 'users.id', 'announce_users.user_id')/*   ->whereNotIn("users.manager_id",$managers)*/ ->pluck('users.id')->toArray();

        $roles = DB::table('announce_role')->where('announce_id', $id)->pluck('role')->toArray();

        $allUsers = User::where('status', 1)->where('role', '<>', 1)->get();

        $allManagers = User::where(['status' => 1, 'role' => 1])->get();
        return view('Admin.Announcements.editAnnouncePage', compact('managers', 'allManagers', 'users', 'roles', 'announce', 'allUsers'));
    }

    public function editAnnounce(Request $request)
    {

        $rules = [
            'content'    => 'required',
            'attachment' => 'file|max:10240',
        ];

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'attachment.max'   => MyHelpers::admin_trans(auth()->user()->id, 'Should not exceed 10 MB'),
        ];

        $this->validate($request, $rules, $customMessages);

        $attachment = $request->file('attachment');

        if ($attachment == null) {
            $path = null;
        }
        else {

            $announce = Announcement::where('id', '=', $request->id)->first();
            if ($announce->attachment != null) {
                unlink(storage_path('app/public/'.$announce->attachment));
            }

            $filename = 'announce'.time().'.'.$attachment->getClientOriginalExtension();
            $path = $attachment->storeAs('announcements', $filename);
        }

        $updateAnnounce = Announcement::where('id', $request->id)->update([
            'content'    => $request->get('content'),
            'status'     => $request->status,
            'color'      => $request->color,
            'attachment' => $path,

        ]);

        DB::table('announce_users')->where('announce_id', $request->id)->delete();
        DB::table('announce_role')->where('announce_id', $request->id)->delete();
        $users = $request->input('users', []);

        foreach ($users as $user) {
            DB::table('announce_users')->insert([
                'announce_id' => $request->id,
                'user_id'     => $user,
            ]);
        }

        if ($request->all =="not"){
            $rol = [0,1,2,3,4,5,7,8,11,12];
        }elseif ($request->all =="all"){
            $rol = [0,1,2,3,4,5,6,7,8,12,11,13];
        }else{
            $rol = [];
        }
        $roles = $request->input('roles', $rol);

        foreach ($roles as $role) {
            if ($role == 'sa') {
                $role = 0;
            }
            DB::table('announce_role')->insert([
                'announce_id' => $request->id,
                'role'        => $role,
            ]);
        }

        if ($updateAnnounce != null) {
            return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function openAnnounceFile($id)
    {
        $announce = Announcement::where('id', '=', $id)->first();
        $file = $announce->attachment;
        return response()->file(storage_path('app/public/'.$file));
    }

    public function deleteAnnounceFile($id)
    {
        $announce = Announcement::where('id', '=', $id)->first();
        unlink(storage_path('app/public/'.$announce->attachment));
        Announcement::where('id', $id)->update([
            'attachment' => null,
        ]);
        return redirect()->back();
    }

    public function openAnnouncePage($id)
    {
        $announce = Announcement::where('id', $id)->first();
        $users_seen = DB::table('announce_seen')->where('announce_id', $id)->join('users', 'users.id', 'announce_seen.user_id')->select('users.name', 'announce_seen.created_at')->get();
        $allUsers = User::where('status', 1)->get();
        return view('Admin.Announcements.openAnnouncePage', compact('announce', 'users_seen'));
    }

    public function trainingPremations()
    {
        $traning_premtions_agent_array = DB::table('training_and_agent')->pluck('training_id')->toArray();
        $traning_premtions_agent_count = count($traning_premtions_agent_array);
        $traning_premtions_type_array = DB::table('training_and_req_types')->pluck('training_id')->toArray();
        $traning_premtions_type_count = count($traning_premtions_type_array);
        $trainig_users = DB::table('users')->where('role', 11)->where('status', 1)->get();
        $agents = DB::table('users')->where('role', 0)->where('status', 1)->get();
        return view('Admin.TrainingPremtions.allTrainingPremtions', compact('traning_premtions_agent_array', 'traning_premtions_agent_count', 'traning_premtions_type_count', 'traning_premtions_type_array', 'trainig_users', 'agents'));
    }

    public function addNewPremtion(Request $request)
    {
        $salesAgents = $request->input('agents', []);
        $types = $request->input('types', []);
        $trainID = $request->train;
        if (!$trainID) {
            $rules = ['trainings' => 'required',];
            $customMessages = ['trainings' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill trainig'),];
            $this->validate($request, $rules, $customMessages);
        }

        if ($salesAgents) {
            foreach ($salesAgents as $salesAgent) {
                DB::table('training_and_agent')->insert([
                    [
                        'training_id' => $trainID,
                        'agent_id'    => $salesAgent,
                    ],
                ]);
            }
        }

        if ($types) {
            foreach ($types as $type) {
                DB::table('training_and_req_types')->insert([
                    [
                        'training_id' => $trainID,
                        'type'        => $type,
                    ],
                ]);
            }
        }

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
    }

    public function updatePremtion(Request $request)
    {

        $salesAgents = $request->input('agents', []);
        $types = $request->input('type', []);
        $trainID = $request->trainID;

        if (!$trainID) {
            $rules = ['trainings' => 'required',];

            $customMessages = ['trainings' => MyHelpers::admin_trans(auth()->user()->id, 'You must fill at least one filed'),];
            $this->validate($request, $rules, $customMessages);
        }
        DB::table('training_and_agent')->where('training_id', $trainID)->delete();
        DB::table('training_and_req_types')->where('training_id', $trainID)->delete();
        if ($salesAgents) {
            foreach ($salesAgents as $salesAgent) {
                DB::table('training_and_agent')->insert([
                    [
                        'training_id' => $trainID,
                        'agent_id'    => $salesAgent,
                    ],
                ]);
            }
        }

        if ($types) {
            foreach ($types as $type) {
                DB::table('training_and_req_types')->insert([
                    [
                        'training_id' => $trainID,
                        'type'        => $type,
                    ],
                ]);
            }
        }

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function removePremtion(Request $request)
    {

        $deleteagent = DB::table('training_and_agent')->where('training_id', $request->id)->delete();
        $deletetypes = DB::table('training_and_req_types')->where('training_id', $request->id)->delete();
        if ($deleteagent || $deletetypes) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
    }

    public function helpDeskPage()
    {
        $helpDeskReqs = helpDesk::all()->count();
        return view('Admin.helpDesk.helpDeskPage', compact('helpDeskReqs'));
    }

    public function helpDesk_datatable(Request $request)
    {
        $helpDeskReqs = helpDesk::orderBy('created_at', 'DESC')->whereNull('parent_id');
        return Datatables::of($helpDeskReqs)->setRowId(function ($helpDeskReqs) {
            return $helpDeskReqs->id;
        })->filter(function($instance) use ($request){
            // if ($request->get('type_of_helpdesk') == '0') { // Auth Customer Technical support
            //     $instance->whereNotNull('customer_id');
            // }

            if ($request->get('type_of_helpdesk') == '1') { // Guest Customer Technical support
                $instance->whereNull('technical_owner_id');
                // $instance->whereNull('technical_owner_id')->whereNull('customer_id');
            }

            if ($request->get('type_of_helpdesk') == '2') { // Employee Technical support
                $instance->whereNotNull('technical_owner_id');
            }


            if ($request->get('status_of_helpdesk') == '0') { // new
                $instance->where('status',0);
            }
            if ($request->get('status_of_helpdesk') == '1') { // opened
                $instance->where('status',1);
            }
            if ($request->get('status_of_helpdesk') == '2') { // completed
                $instance->where('status',2);
            }
            if ($request->get('status_of_helpdesk') == '3') { // canceled
                $instance->where('status',3);
            }


        })->addColumn('action', function (helpDesk $row) {
            $data = '<div class="tableAdminOption">';
            $reqID = null;
            $mobile = $row->customer->mobile ?: $row->mobile;
            //if($row->customer_id){
            //    $getCustomer = $this->checkCustomerHasRequest($mobile);
            //    dd($reqID,$getCustomer,$mobile);
            //    dd($row->customer);
            //}
            if (($getCustomer = $this->checkCustomerHasRequest($mobile))) {
                $reqID = $this->getCustomerRequest($getCustomer);
            }

            if ($reqID) {
                if ($reqID == 'طلب معلق') {
                    $data = $data.'<span disabled class="item pointer" id="openPending" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'request_hanged').'">
                <i class="fas fa-ban"></i></span>';
                }
                else {
                    if ($reqID->type == null || $reqID->type == 'شراء' || $reqID->type == 'رهن') {
                        $data = $data.'<span disabled class="item pointer" id="open" data-id="'.$reqID->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                        <a href="'.route('admin.fundingRequest', $reqID->id).'"> <i class="fas fa-eye"></i></a></span>';
                    }
                    else {
                        $data = $data.'<span class="item pointer" id="open" data-id="'.$reqID->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                    <a href="'.route('admin.morPurRequest', $reqID->id).'"> <i class="fas fa-eye"></i></a></span>';
                    }
                }
            }

            $data = $data.'<span class="item" id="open pointer" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'open_helpDesk').'">
            <a href="'.route('admin.openHelpDeskPage', $row->id).'"> <i class="fas fa-cog"></i></a>
             </span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('status', function ($row) {
            switch ($row->status) {
                case 0:
                    $status = 'طلب جديد';
                    break;
                case 1:
                    $status = 'طلب تم فتحه';
                    break;
                case 2:
                    $status = 'طلب مكتمل';
                    break;
                case 3:
                    $status = 'طلب ملغي';
                    break;
                default:
                    $status = 'غير معرف';
                    break;
            }
            return $status;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;
            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->addColumn('has_request', function ($row) {
            if ($row->customer_id != null) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'Yes');
            }
            else {
                $getCustomer = $this->checkCustomerHasRequest($row->mobile);
                if ($getCustomer) {
                    $data = MyHelpers::admin_trans(auth()->user()->id, 'Yes');
                }
                else {
                    $data = MyHelpers::admin_trans(auth()->user()->id, 'No');
                }
            }
            return $data;
        })->make(true);
    }

    public function checkCustomerHasRequest($mobile)
    {
        if (($getCustomer = DB::table('customers')->where('mobile', $mobile)->first())) {
            return $getCustomer->id;
        }
        return false;
    }

    public function getCustomerRequest($customerID)
    {
        $getCustomerReq = DB::table('requests')->where('customer_id', $customerID)->first();
        if ($getCustomerReq) {
            return $getCustomerReq;
        }
        else {
            $getCustomerReq = DB::table('pending_requests')->where('customer_id', $customerID)->first();
            if ($getCustomerReq) {
                return 'طلب معلق';
            }
        }
        return false;
    }

    public function openHelpDeskPage($id)
    {
        $helpDesk = helpDesk::where('help_desks.id', $id)->leftJoin('users', 'users.id', 'help_desks.user_id')->select('help_desks.*', 'users.name as username')->first();
        // open req
        if ($helpDesk->status == 0) {
            helpDesk::where('id', $id)->update(['status' => 1]);
        }

        DB::table('notifications')->where('status', 0)->where('type', 8)->where('req_id', $id)->update(['status' => 1]);
        $reqInfo = null;
        $getCustomer = $this->checkCustomerHasRequest($helpDesk->mobile);
        if ($getCustomer) {
            $reqInfo = $this->getCustomerRequest($getCustomer);
        }

        if($helpDesk->parent_id)
        $helpDesk = helpDesk::find($helpDesk->parent_id);
        return view('Admin.helpDesk.openHelpDeskPage', compact('helpDesk', 'reqInfo'));
    }

    public function postReplayHelpDesk(Request $request)
    {
        $rules = ['replay' => ['required']];

        $customMessages = ['replay.required' => MyHelpers::guest_trans('Mobile filed is required'),];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        $validator->validate();

        $updateReq = helpDesk::where('id', $request->reqID)->update(['replay' => $request->replay, /*'status' => 2,*/ 'user_id' => auth()->user()->id, 'date_replay' => Carbon::now('Asia/Riyadh')]);
        $row = helpDesk::where('id', $request->reqID)->first();
        // new chat
        if($row->technical_owner_id)
        {
            $new_row = new helpDesk;
            $new_row->parent_id = $request->reqID;
            $new_row->descrebtion = $request->replay;
            $new_row->technical_owner_id = $row->technical_owner_id;
            $new_row->user_id = $row->user_id;
            $new_row->save();
            // send notification to employee
            DB::table('notifications')->insert([
                'value'      => 'الدعم الفني: رد من الاداره',
                'recived_id' => $row->technical_owner_id,
                'created_at' => now('Asia/Riyadh'),
                'type'       => 8,
                'req_id'     => $new_row->id,
            ]);
        }
        try{
            MyHelpers::sendEmailNotifiactionByEmailOnly($request->email, $request->replay, ' تم الرد على طلب الدعم الفني - شركة الوساطة العقارية');

        }catch(\Exception $e){}

        if ($updateReq) {
            return redirect()->back()->with('message', 'تم التحديث بنجاح');
        }
        else {
            return redirect()->back()->with('message2', 'حدث خطأ ، حاول مجددا');
        }
    }

    public function canceleHelpDesk($id)
    {
        $helpDesk = helpDesk::where('id', $id)->first();
        $updateReq = 0;
        if ($helpDesk->status == 0 || $helpDesk->status == 1) {
            $updateReq = helpDesk::where('id', $id)->update(['status' => 3, 'user_id' => auth()->user()->id]);
        } // cancel req

        if ($updateReq != 0) {
            return redirect()->back()->with('message', 'تم التحديث بنجاح');
        }
        else {
            return redirect()->back()->with('message2', 'حدث خطأ ، حاول مجددا');
        }
    }

    public function completeHelpDesk($id)
    {
        $helpDesk = helpDesk::where('id', $id)->first();
        $updateReq = 0;
        if ($helpDesk->status == 0 || $helpDesk->status == 1) {
            $updateReq = helpDesk::where('id', $id)->update(['status' => 2, 'user_id' => auth()->user()->id]);
        } // cancel req

        if ($updateReq != 0) {
            return redirect()->back()->with('message', 'تم التحديث بنجاح');
        }
        else {
            return redirect()->back()->with('message2', 'حدث خطأ ، حاول مجددا');
        }
    }

    public function addCustomerWithReqPost(Request $request)
    {

        if ($request->reqsour == 2) { //should supicify who is collobreator once selectd req source from collobreator

            $rules = [
                'agent'        => 'required',
                'name'         => 'required',
                'collaborator' => 'required',
                'reqsour'      => 'required',
                'mobile'       => 'required|digits:9|regex:/^(5)[0-9]{8}$/|unique:customers,mobile,'.auth()->user()->id, // unique email',
            ];

            $customMessages = [
                'agent.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The agent is required'),
                'mobile.unique'         => MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'),
                'name.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'collaborator.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'mobile.digits'         => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqsour.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            ];
        }
        else {
            $rules = [
                'agent'   => 'required',
                'name'    => 'required',
                'reqsour' => 'required',
                'mobile'  => 'required|digits:9|regex:/^(5)[0-9]{8}$/|unique:customers,mobile,'.auth()->user()->id, // unique email',
            ];

            $customMessages = [
                'agent.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The agent is required'),
                'mobile.unique'    => MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'),
                'name.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'mobile.digits'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqsour.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            ];
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        // Task-17 Add Another Validation For Phones Table
        //**************************************************************
        $validator2 = Validator::make($request->all(), [
            'mobile' => ['unique:customers_phones'],
        ], [
            'mobile.unique' => 'رقم الجوال موجود بالفعل  *',
        ]);

        $validator->validate();
        $validator2->validate();

        $agentID = $request->agent;
        $customerId = DB::table('customers')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
            [ //add it once use insertGetId
              'user_id'         => $agentID,
              'name'            => $request->name,
              'welcome_message' => 2,
              'mobile'          => $request->mobile,
              'created_at'      => (Carbon::now('Asia/Riyadh')),
            ]);

        $joinID = DB::table('joints')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
            [ //add it once use insertGetId
              // 'customer_id' => $customerId,
              'created_at' => (Carbon::now('Asia/Riyadh')),
            ]);

        $realID = DB::table('real_estats')->insertGetId([
                //'customer_id' => $customerId,
                'created_at' => (Carbon::now('Asia/Riyadh')),
            ]

        );

        $funID = DB::table('fundings')->insertGetId([
                // 'customer_id' => $customerId,
                'created_at' => (Carbon::now('Asia/Riyadh')),
            ]

        );

        $reqdate = (Carbon::now('Asia/Riyadh'));

        $searching_id = RequestSearching::create()->id;
        $reqID = DB::table('requests')->insertGetId([
                'source'          => $request->reqsour,
                'req_date'        => $reqdate,
                'created_at'      => (Carbon::now('Asia/Riyadh')),
                'searching_id'    => $searching_id,
                'user_id'         => $agentID,
                'customer_id'     => $customerId,
                'collaborator_id' => $request->collaborator,
                'joint_id'        => $joinID,
                'real_id'         => $realID,
                'fun_id'          => $funID,
                'statusReq'       => 0,
                'agent_date'      => carbon::now(),
            ]

        );

        if ($reqID) {
            $this->history($reqID, MyHelpers::admin_trans(auth()->user()->id, 'Create Request'), null, null);

            DB::table('users')->where('id', $agentID)->first();
            MyHelpers::addNewNotify($reqID, $agentID);                                                               // to add notification
            MyHelpers::sendEmailNotifiaction('new_req', $agentID, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك'); //email notification
            //$pwaPush = MyHelpers::pushPWA($agentID, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $reqID);
            MyHelpers::incrementDailyPerformanceColumn($agentID, 'received_basket',$reqID);
            return redirect()->route('admin.fundingRequest', $reqID)->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }

        return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

    public function history($reqID, $title, $recived_id, $comment)
    {

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        DB::table('request_histories')->insert([
            'title'          => $title,
            'user_id'        => (auth()->user()->id),
            'recive_id'      => $recived_id,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'content'        => $comment,
            'req_id'         => $reqID,
            'user_switch_id' => $userSwitch,
        ]);

        //  dd($rowOfLastUpdate);
    }

    public function fundingreqpage($id)
    {

        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        $agentInfo = DB::table('users')->where('id', '=', $request->user_id)->first();

        $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

        if (!empty($purchaseCustomer)) {

            $reqStatus = $request->statusReq;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            $purchaseClass2 = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_sm')->where('requests.id', '=', $id)->first();

            $purchaseClass3 = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_fm')->where('requests.id', '=', $id)->first();

            $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            //   dd($collaborator);

            $collaborators = DB::table('users')->where('role', 6)->select('users.id as collaborato_id', 'users.name')->get();

            $regions = customer::select('region_ip')->groupBy('region_ip')->get();

            //dd( $regions);

            if ($request->type == 'رهن-شراء') {
                $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();
            }
            else {
                $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
            }

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $user_role = DB::table('users')->select('role')->get();
            $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', 0)->get();
            $classifcations2 = DB::table('classifcations')->select('id', 'value')->where('user_role', 1)->get();
            $classifcations3 = DB::table('classifcations')->select('id', 'value')->where('user_role', 3)->get();

            /* $histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

            $documents = DB::table('documents')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            $salesAgents = User::where('role', 0)->where('status', 1)->get();
            $qulitys = User::where('role', 5)->where('status', 1)->get();

            #---------------FOLLOW DATE OF CURRENT USER & AGENT-----------------------
            $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            $followdate_agent = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', $request->user_id)->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime_agent = ($followdate_agent != null ? Carbon::parse($followdate_agent->reminder_date)->format('H:i') : null);

            if (!empty($followdate_agent)) {
                $followdate_agent->reminder_date = (Carbon::parse($followdate_agent->reminder_date)->format('Y-m-d'));
            }
            #----------------END FOLLOW DATE OF CURRENT USER & AGENT--------------------------------

            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
            MyHelpers::openReqWillOpenNotify($id);
            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

            $product_types = null;
            $getTypes = MyHelpers::getProductType();
            if ($getTypes != null) {
                $product_types = $getTypes;
            }

            $districts = District::all();

            $prefix = 'admin';

            $rejections = RejectionsReason::all();
            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            // dd( $followdate);
            $modelRequest = \App\Models\Request::find($id);
            return view('Admin.fundingReq.fundingreqpage',
                compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseClass', 'purchaseClass2', 'purchaseClass3', 'purchaseTsa', 'salary_sources', 'funding_sources', 'regions', 'askary_works', 'madany_works', 'classifcations', 'classifcations2', 'classifcations3',
                    'districts', 'prefix', 'id', 'documents', 'reqStatus', 'payment', 'followdate', 'followdate_agent', 'collaborator', 'cities', 'ranks', 'collaborators', 'followtime', 'followtime_agent', 'realTypes', 'agentInfo', 'salesAgents', 'qulitys', 'product_types', 'rejections',
                    'worke_sources', 'request_sources','modelRequest'));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "You do not have a premation to do that"));
        }
    }

    public function morPurpage($id)
    {

        $morPur = DB::table('requests')->where('id', '=', $id)->first();

        $agentInfo = DB::table('users')->where('id', '=', $morPur->user_id)->first();

        if (!empty($morPur)) {

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

            $reqStatus = $morPur->statusReq;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            $purchaseClass2 = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_sm')->where('requests.id', '=', $id)->first();

            $purchaseClass3 = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_fm')->where('requests.id', '=', $id)->first();

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            $payment = DB::table('prepayments')->where('req_id', '=', $morPur->req_id)->first();

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $collaborators = DB::table('users')->where('role', 6)->select('users.id as collaborato_id', 'users.name')->get();

            $regions = customer::select('region_ip')->groupBy('region_ip')->get();

            $user_role = DB::table('users')->select('role')->get();
            $classifcations = DB::table('classifcations')->select('id', 'value')->where('user_role', 0)->get();
            $classifcations2 = DB::table('classifcations')->select('id', 'value')->where('user_role', 1)->get();
            $classifcations3 = DB::table('classifcations')->select('id', 'value')->where('user_role', 3)->get();

            $histories = DB::table('req_records')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
            ->get();

            $documents = DB::table('documents')->where('req_id', '=', $id)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            #---------------- FOLLOW DATE OF CURRENT USER & AGENT--------------------------------

            $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            $followdate_agent = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', $morPur->user_id)->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime_agent = ($followdate_agent != null ? Carbon::parse($followdate_agent->reminder_date)->format('H:i') : null);

            if (!empty($followdate_agent)) {
                $followdate_agent->reminder_date = (Carbon::parse($followdate_agent->reminder_date)->format('Y-m-d'));
            }

            #----------------END FOLLOW DATE OF CURRENT USER & AGENT--------------------------------

            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
            MyHelpers::openReqWillOpenNotify($id);
            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

            $salesAgents = User::where('role', 0)->where('status', 1)->get();
            $qulitys = User::where('role', 5)->where('status', 1)->get();

            $product_types = null;
            $getTypes = MyHelpers::getProductType();
            if ($getTypes != null) {
                $product_types = $getTypes;
            }

            // dd(  $morPur);

            $districts = District::all();

            $prefix = 'admin';

            $rejections = RejectionsReason::all();
            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            return view('Admin.morPurReq.fundingreqpage', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id', //Request ID
                'histories', 'documents', 'reqStatus', 'payment', 'regions', 'morPur', 'followdate', 'followdate_agent', 'collaborator', 'cities', 'ranks', 'followtime', 'followtime_agent', 'realTypes', 'agentInfo', 'salesAgents', 'qulitys', 'collaborators', 'classifcations2', 'classifcations3',
                'purchaseClass2', 'purchaseClass3', 'product_types', 'rejections', 'worke_sources', 'request_sources'

            ));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function updatefunding(Request $request)
    {
        //----------------------------------------------------------------
        //          #Task-03# Whene Change ClassId Delete Survuy To enable Make Again
        //----------------------------------------------------------------
        $requestModel = \App\request::query()->findOrFail($request->reqID);
        $deleteClassificationAlertSchedule33 = $requestModel->class_id_agent == 33 && $request->req_class != $requestModel->class_id_agent;
        if ($request->req_class == 61 && $requestModel->class_id_agent) {
            $classification = Classification::findOrFail(61);
            $record = RequestRecord::query()->where([
                'colum'   => 'class_agent',
                'user_id' => $requestModel->user_id,
                'req_id'  => $requestModel->id,
                'value'   => $classification->id,
            ])->exists();
            if ($record) {
                return redirect()->back()->with('message2', __("messages.updateRequestClassification61"));
            }
        }
        if ($request->req_class == 62 && $requestModel->class_id_agent) {
            $classification = Classification::findOrFail(62);
            $record = RequestRecord::query()->where([
                'colum'   => 'class_agent',
                'user_id' => $requestModel->user_id,
                'req_id'  => $requestModel->id,
                'value'   => $classification->id,
            ])->exists();
            if ($record) {
                return redirect()->back()->with('message2', __("messages.updateRequestClassification61"));
            }
        }

        if ($request->req_class != $requestModel->class_id_agent) {
            if ($request->reqclass != 13) {
                $answers = AskAnswer::where('request_id', $requestModel->id)->update(['batch' => DB::raw('batch+1')]);
            }
        }
        //----------------------------------------------------------------

        //dd($request);

        if ($request->reqsour == 2) { //should supicify who is collobreator once selectd req source from collobreator

            $rules = [
                //   'name' => 'required',
                'mobile'       => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
                //'reqtyp'  => 'required',
                'reqsour'      => 'required',
                'collaborator' => 'required',
                //'jointmobile'=> 'regex:/^(05)[0-9]{8}$/',
                //'sex' => 'required',
                //   'birth' => 'required',
                //   'work' => 'required',
                //   'salary_source' => 'required',
                //   'salary' => 'numeric',
            ];

            $customMessages = [
                'mobile.regex'          => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.digits'         => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'collaborator.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'reqtyp.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqsour.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                //'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'jointmobile.regex' => 'Should start with 05 ',
                //  'birth.required' => 'The birth date filed is required ',
            ];
        }
        else {

            $rules = [
                //   'name' => 'required',
                'mobile'  => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
                //'reqtyp'  => 'required',
                'reqsour' => 'required',
                //'jointmobile'=> 'regex:/^(05)[0-9]{8}$/',
                //'sex' => 'required',
                //   'birth' => 'required',
                //   'work' => 'required',
                //   'salary_source' => 'required',
                //   'salary' => 'numeric',
            ];

            $customMessages = [
                'mobile.regex'     => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.digits'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
                'mobile.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'reqtyp.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'reqsour.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                //'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'jointmobile.regex' => 'Should start with 05 ',
                //  'birth.required' => 'The birth date filed is required ',
            ];
        }

        $this->validate($request, $rules, $customMessages);

        //REQUEST
        $reqID = $request->reqID; //request id for update
        $fundingReq = DB::table('requests')->where('id', $reqID)->first();
        //

        if (!empty($fundingReq)) {

            // dd($fundingReq);

            //JOINT
            $jointId = $fundingReq->joint_id;
            //

            //CUSTOMER
            $customerId = $fundingReq->customer_id;
            $customerInfo = DB::table('customers')->where('id', '=', $customerId)->first();
            //

            //FUNDING INFO
            $fundingId = $fundingReq->fun_id;
            //

            //REAL ESTAT
            $realId = $fundingReq->real_id;
            //

            //CLASSIFICATION
            $classId = $fundingReq->class_id_agent;
            //

            $checkmobile = DB::table('customers')->where('mobile', $request->mobile)->first();
            $checkmobile2 = CustomersPhone::where('mobile', $request->mobile)->first();

            if ((empty($checkmobile2) && empty($checkmobile)) || $customerInfo->mobile == $request->mobile) {

                if ($request->name == null) {
                    $request->name = 'بدون اسم';
                }

                $this->records($reqID, 'customerName', $request->name);
                $this->records($reqID, 'mobile', $request->mobile);
                $this->records($reqID, 'sex', $request->sex);
                $this->records($reqID, 'birth_date', $request->birth);
                $this->records($reqID, 'birth_hijri', $request->birth_hijri);
                $this->records($reqID, 'salary', $request->salary);
                $this->records($reqID, 'regionip', $request->regionip);

                if ($request->is_support != null) {
                    if ($request->is_support == 'no') {
                        $this->records($reqID, 'support', 'لا');
                    }
                    if ($request->is_support == 'yes') {
                        $this->records($reqID, 'support', 'نعم');
                    }
                }
                if ($request->has_obligations != null) {
                    if ($request->has_obligations == 'no') {
                        $this->records($reqID, 'obligations', 'لا');
                    }
                    if ($request->has_obligations == 'yes') {
                        $this->records($reqID, 'obligations', 'نعم');
                    }
                }

                if ($request->has_financial_distress != null) {
                    if ($request->has_financial_distress == 'no') {
                        $this->records($reqID, 'distress', 'لا');
                    }
                    if ($request->has_financial_distress == 'yes') {
                        $this->records($reqID, 'distress', 'نعم');
                    }
                }

                $getworkValue = DB::table('work_sources')->where('id', $request->work)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'work', $getworkValue->value);
                }

                $this->records($reqID, 'obligations_value', $request->obligations_value);
                $this->records($reqID, 'financial_distress_value', $request->financial_distress_value);

                $this->records($reqID, 'jobTitle', $request->job_title);

                $getsalaryValue = DB::table('salary_sources')->where('id', $request->salary_source)->first();
                if (!empty($getsalaryValue)) {
                    $this->records($reqID, 'salary_source', $getsalaryValue->value);
                }

                $getaskaryValue = DB::table('askary_works')->where('id', $request->askary_work)->first();
                if (!empty($getaskaryValue)) {
                    $this->records($reqID, 'askaryWork', $getaskaryValue->value);
                }

                $getmadanyValue = DB::table('madany_works')->where('id', $request->madany_work)->first();
                if (!empty($getmadanyValue)) {
                    $this->records($reqID, 'madanyWork', $getmadanyValue->value);
                }
                $getrankValue = DB::table('military_ranks')->where('id', $request->rank)->first();
                if (!empty($getrankValue)) {
                    $this->records($reqID, 'rank', $getrankValue->value);
                }

                $updateResult = DB::table('customers')->where([
                    ['id', '=', $customerId],
                ])->update([
                    'name'                     => $request->name,
                    'mobile'                   => $request->mobile,
                    'sex'                      => $request->sex,
                    'birth_date'               => $request->birth,
                    'birth_date_higri'         => $request->birth_hijri,
                    'age'                      => $request->age,
                    'work'                     => $request->work,
                    'madany_id'                => $request->madany_work,
                    'job_title'                => $request->job_title,
                    'askary_id'                => $request->askary_work,
                    'military_rank'            => $request->rank,
                    'salary_id'                => $request->salary_source,
                    'salary'                   => $request->salary,
                    'is_supported'             => $request->is_support,
                    'has_obligations'          => $request->has_obligations,
                    'obligations_value'        => $request->obligations_value,
                    'has_financial_distress'   => $request->has_financial_distress,
                    'financial_distress_value' => $request->financial_distress_value,
                    'region_ip'                => $request->regionip,
                ]);

                //
                $name = $request->jointname;
                $mobile = $request->jointmobile;
                $birth = $request->jointbirth;
                $birth_higri = $request->jointbirth_hijri;
                $age = $request->jointage;
                $work = $request->jointwork;
                $salary = $request->jointsalary;
                $salary_source = $request->jointsalary_source;
                $rank = $request->jointrank;
                $madany = $request->jointmadany_work;
                $job_title = $request->jointjob_title;
                $askary_work = $request->jointaskary_work;
                $jointfunding_source = $request->jointfunding_source;

                $this->records($reqID, 'jointName', $request->jointname);
                $this->records($reqID, 'jointMobile', $request->jointmobile);
                $this->records($reqID, 'jointSalary', $request->jointsalary);
                $this->records($reqID, 'jointBirth', $request->jointbirth);
                $this->records($reqID, 'jointBirth_higri', $request->jointbirth_hijri);
                $this->records($reqID, 'jointJobTitle', $job_title);

                $getjointfundingValue = DB::table('funding_sources')->where('id', $request->jointfunding_source)->first();
                if (!empty($getjointfundingValue)) {
                    $this->records($reqID, 'jointfunding_source', $getjointfundingValue->value);
                }

                $getjointsalaryValue = DB::table('salary_sources')->where('id', $request->jointsalary_source)->first();
                if (!empty($getjointsalaryValue)) {
                    $this->records($reqID, 'jointsalary_source', $getjointsalaryValue->value);
                }

                $getjointrankValue = DB::table('military_ranks')->where('id', $request->jointrank)->first();
                if (!empty($getjointrankValue)) {
                    $this->records($reqID, 'jointRank', $getjointrankValue->value);
                }

                $getworkValue = DB::table('work_sources')->where('id', $request->jointwork)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'jointWork', $getworkValue->value);
                }

                $getjointaskaryValue = DB::table('askary_works')->where('id', $request->jointaskary_work)->first();
                if (!empty($getjointaskaryValue)) {
                    $this->records($reqID, 'jointaskaryWork', $getjointaskaryValue->value);
                }

                $getjointmadanyValue = DB::table('madany_works')->where('id', $request->jointmadany_work)->first();
                if (!empty($getjointmadanyValue)) {
                    $this->records($reqID, 'jointmadanyWork', $getjointmadanyValue->value);
                }

                DB::table('joints')->where('id', $jointId)->update([
                    'name'             => $name,
                    'mobile'           => $mobile,
                    'salary'           => $salary,
                    'birth_date'       => $birth,
                    'birth_date_higri' => $birth_higri,
                    'age'              => $age,
                    'work'             => $work,
                    'salary_id'        => $salary_source,
                    'military_rank'    => $rank,
                    'madany_id'        => $madany,
                    'job_title'        => $job_title,
                    'funding_id'       => $jointfunding_source,
                    'askary_id'        => $askary_work,
                ]);

                //

                //

                $realname = $request->realname;
                $realmobile = $request->realmobile;
                $realcity = $request->realcity;
                $region = $request->realregion;
                $realpursuit = $request->realpursuit;
                $realstatus = $request->realstatus;
                $realage = $request->realage;
                $realcost = $request->realcost;
                $realhas = $request->realhasprop;
                $realtype = $request->realtype;
                $othervalue = $request->othervalue;
                $realeva = $request->realeva;
                $realten = $request->realten;
                $realmor = $request->realmor;
                $mortgage_value = $request->mortgage_value;
                $owning_property = $request->owning_property;

                $this->records($reqID, 'realName', $request->realname);
                $this->records($reqID, 'realMobile', $request->realmobile);
                $getcityValue = DB::table('cities')->where('id', $request->realcity)->first();
                if (!empty($getcityValue)) {
                    $this->records($reqID, 'realCity', $getcityValue->value);
                }
                $this->records($reqID, 'realRegion', $request->realregion);
                $this->records($reqID, 'realPursuit', $request->realpursuit);
                $this->records($reqID, 'realAge', $request->realage);
                $this->records($reqID, 'realStatus', $request->realstatus);
                $this->records($reqID, 'realCost', $request->realcost);
                $this->records($reqID, 'owning_property', 'لا');
                if ($request->owning_property == 'yes') {
                    $this->records($reqID, 'owning_property', 'نعم');
                }
                $this->records($reqID, 'mortValue', $request->mortgage_value);
                $gettypeValue = DB::table('real_types')->where('id', $request->realtype)->first();
                if (!empty($gettypeValue)) {
                    $this->records($reqID, 'realType', $gettypeValue->value);
                }

                DB::table('real_estats')->where('id', $realId)->update([
                    'name'            => $realname,
                    'mobile'          => $realmobile,
                    'city'            => $realcity,
                    'region'          => $region,
                    'pursuit'         => $realpursuit,
                    'age'             => $realage,
                    'status'          => $realstatus,
                    'cost'            => $realcost,
                    'type'            => $realtype,
                    'other_value'     => $othervalue,
                    'evaluated'       => $realeva,
                    'tenant'          => $realten,
                    'mortgage'        => $realmor,
                    'has_property'    => $realhas,
                    'mortgage_value'  => $mortgage_value,
                    'owning_property' => $owning_property,
                ]);

                //

                if ($request->reqtyp == 'رهن' && ($fundingReq->payment_id === null)) { //add tsaheel info

                    $paypreID = DB::table('prepayments')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                        [ //add it once use insertGetId
                          'pay_date' => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                          'req_id'   => $reqID,
                        ]);

                    $payId = $paypreID;

                    $updatereq = DB::table('requests')->where('id', $reqID)->update([
                        'payment_id' => $paypreID,
                    ]);
                }

                /********update fundingreq after update request */

                $fundingReq = DB::table('requests')->where('id', $reqID)->first();

                /******** end update fundingreq after update request */

                if ($request->reqtyp == 'رهن' && ($fundingReq->payment_id != null)) { //add tsaheel info

                    //TSAHEEL
                    $payId = $fundingReq->payment_id;

                    //

                    $real = $request->real;
                    $incr = $request->incr;
                    $preval = $request->preval;
                    $prepre = $request->prepre;
                    $precos = $request->precos;
                    $net = $request->net;
                    $deficit = $request->deficit;
                    $visa = $request->visa;
                    if ($visa == null) {
                        $visa = 0;
                    }

                    $carlo = $request->carlo;
                    if ($carlo == null) {
                        $carlo = 0;
                    }

                    $perlo = $request->perlo;
                    if ($perlo == null) {
                        $perlo = 0;
                    }

                    $realo = $request->realo;
                    if ($realo == null) {
                        $realo = 0;
                    }

                    $credban = $request->credban;
                    if ($credban == null) {
                        $credban = 0;
                    }

                    $other1 = $request->other1;
                    if ($other1 == null) {
                        $other1 = 0;
                    }

                    $debt = $request->debt;
                    $morpre = $request->morpre;
                    $morcos = $request->morcos;
                    $propre = $request->propre;
                    $procos = $request->procos;
                    $valadd = $request->valadd;
                    $admfe = $request->admfe;
                    //

                    //

                    $this->records($reqID, 'realCost', $request->real);
                    $this->records($reqID, 'incValue', $request->incr);
                    $this->records($reqID, 'preValue', $request->preval);
                    $this->records($reqID, 'prePresent', $request->prepre);
                    $this->records($reqID, 'preCost', $request->precos);
                    $this->records($reqID, 'netCust', $request->net);
                    $this->records($reqID, 'deficitCust', $request->deficit);

                    if ($request->visa != 0) {
                        $this->records($reqID, 'preVisa', $request->visa);
                    }

                    if ($request->carlo != 0) {
                        $this->records($reqID, 'carLo', $request->carlo);
                    }

                    if ($request->perlo != 0) {
                        $this->records($reqID, 'personalLo', $request->perlo);
                    }

                    if ($request->realo != 0) {
                        $this->records($reqID, 'realLo', $request->realo);
                    }

                    if ($request->credban != 0) {
                        $this->records($reqID, 'credBank', $request->credban);
                    }

                    if ($request->other1 != 0) {
                        $this->records($reqID, 'otherLo', $request->other1);
                    }

                    $this->records($reqID, 'morPresnt', $request->morpre);
                    $this->records($reqID, 'mortCost', $request->mortCost);
                    $this->records($reqID, 'pursitPresnt', $request->propre);
                    $this->records($reqID, 'profCost', $request->procos);
                    $this->records($reqID, 'addedValue', $request->valadd);
                    $this->records($reqID, 'adminFees', $request->admfe);

                    //

                    DB::table('prepayments')->where('id', $payId)->update([
                        'realCost'        => $real,
                        'incValue'        => $incr,
                        'prepaymentVal'   => $preval,
                        'prepaymentPre'   => $prepre,
                        'prepaymentCos'   => $precos,
                        'visa'            => $visa,
                        'carLo'           => $carlo,
                        'personalLo'      => $perlo,
                        'realLo'          => $realo,
                        'credit'          => $credban,
                        'netCustomer'     => $net,
                        'other'           => $other1,
                        'debt'            => $debt,
                        'mortPre'         => $morpre,
                        'mortCost'        => $morcos,
                        'proftPre'        => $propre,
                        'deficitCustomer' => $deficit,
                        'profCost'        => $procos,
                        'addedVal'        => $valadd,
                        'adminFee'        => $admfe,
                        'req_id'          => $reqID,
                        'pay_date'        => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
                    ]);
                }

                //
                //
                if ($request->reqtyp == 'رهن-شراء') {

                    $real = $request->real;
                    $incr = $request->incr;
                    $preval = $request->preval;
                    $prepre = $request->prepre;
                    $precos = $request->precos;
                    $net = $request->net;
                    $deficit = $request->deficit;

                    $visa = $request->visa;
                    $carlo = $request->carlo;
                    $perlo = $request->perlo;
                    $realo = $request->realo;
                    $credban = $request->credban;
                    $other = $request->other;
                    $debt = $request->debt;
                    $morpre = $request->morpre;
                    $morcos = $request->morcos;
                    $propre = $request->propre;
                    $procos = $request->procos;
                    $valadd = $request->valadd;
                    $admfe = $request->admfe;
                    //

                    //

                    $this->records($reqID, 'realCost', $request->real);
                    $this->records($reqID, 'incValue', $request->incr);
                    $this->records($reqID, 'preValue', $request->preval);
                    $this->records($reqID, 'prePresent', $request->prepre);
                    $this->records($reqID, 'preCost', $request->precos);
                    $this->records($reqID, 'netCust', $request->net);
                    $this->records($reqID, 'deficitCust', $request->deficit);

                    if ($request->visa != 0) {
                        $this->records($reqID, 'preVisa', $request->visa);
                    }

                    if ($request->carlo != 0) {
                        $this->records($reqID, 'carLo', $request->carlo);
                    }

                    if ($request->perlo != 0) {
                        $this->records($reqID, 'personalLo', $request->perlo);
                    }

                    if ($request->realo != 0) {
                        $this->records($reqID, 'realLo', $request->realo);
                    }

                    if ($request->credban != 0) {
                        $this->records($reqID, 'credBank', $request->credban);
                    }

                    if ($request->other1 != 0) {
                        $this->records($reqID, 'otherLo', $request->other1);
                    }

                    $this->records($reqID, 'morPresnt', $request->morpre);
                    $this->records($reqID, 'mortCost', $request->mortCost);
                    $this->records($reqID, 'pursitPresnt', $request->propre);
                    $this->records($reqID, 'profCost', $request->procos);
                    $this->records($reqID, 'addedValue', $request->valadd);
                    $this->records($reqID, 'adminFees', $request->admfe);

                    //

                    $payupdate = DB::table('prepayments')->where('req_id', $fundingReq->req_id)->update([
                        'realCost'        => $real,
                        'incValue'        => $incr,
                        'prepaymentVal'   => $preval,
                        'prepaymentPre'   => $prepre,
                        'prepaymentCos'   => $precos,
                        'visa'            => $visa,
                        'carLo'           => $carlo,
                        'personalLo'      => $perlo,
                        'realLo'          => $realo,
                        'credit'          => $credban,
                        'netCustomer'     => $net,
                        'other'           => $other,
                        'debt'            => $debt,
                        'mortPre'         => $morpre,
                        'mortCost'        => $morcos,
                        'proftPre'        => $propre,
                        'deficitCustomer' => $deficit,
                        'profCost'        => $procos,
                        'addedVal'        => $valadd,
                        'adminFee'        => $admfe,
                    ]);
                }
                //

                ////********************REMINDERS BODY************************* */

                //only one reminder to each request
                $checkFollow = DB::table('notifications')->where('req_id', '=', $reqID)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)->where('status', '=', 2)->first(); // check dublicate

                if ($request->follow != null) {

                    $date = $request->follow;
                    $time = $request->follow1;
                    if ($time == null) {
                        $time = "00:00";
                    }

                    $newValue = $date."T".$time;

                    if (empty($checkFollow)) { //first reminder

                        // add following notification
                        DB::table('notifications')->insert([
                            'value'         => MyHelpers::admin_trans(auth()->user()->id, 'The request need following'),
                            'recived_id'    => (auth()->user()->id),
                            'status'        => 2,
                            'type'          => 1,
                            'reminder_date' => $newValue,
                            'req_id'        => $reqID,
                            'created_at'    => (Carbon::now('Asia/Riyadh')),
                        ]);
                    }
                    else {

                        $overWriteReminder = DB::table('notifications')->where('id', $checkFollow->id)->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]); //set new notifiy

                    }
                }
                else {

                    #if empty reminder, so the reminder ll remove if it's existed.
                    if (!empty($checkFollow)) {
                        DB::table('notifications')->where('id', $checkFollow->id)->delete();
                    }
                }

                ////********************REMINDERS BODY************************* */

                //

                $funding_source = $request->funding_source;
                $fundingdur = $request->fundingdur;
                $fundingpersonal = $request->fundingpersonal;
                $fundingpersonalp = $request->fundingpersonalp;
                $fundingreal = $request->fundingreal;
                $fundingrealp = $request->fundingrealp;
                $dedp = $request->dedp;
                $monthIn = $request->monthIn;

                $this->records($reqID, 'fundDur', $fundingdur);
                $this->records($reqID, 'fundPers', $fundingpersonal);
                $this->records($reqID, 'fundPersPre', $fundingpersonalp);
                $this->records($reqID, 'fundReal', $fundingreal);
                $this->records($reqID, 'fundRealPre', $fundingrealp);
                $this->records($reqID, 'fundDed', $dedp);
                $this->records($reqID, 'fundMonth', $monthIn);

                $getfundingValue = DB::table('funding_sources')->where('id', $request->funding_source)->first();
                if (!empty($getfundingValue)) {
                    $this->records($reqID, 'funding_source', $getfundingValue->value);
                }

                DB::table('fundings')->where('id', $fundingId)->update([
                    'funding_source'   => $funding_source,
                    'funding_duration' => $fundingdur,
                    'personalFun_cost' => $fundingpersonal,
                    'personalFun_pre'  => $fundingpersonalp,
                    'realFun_cost'     => $fundingreal,
                    'realFun_pre'      => $fundingrealp,
                    'ded_pre'          => $dedp,
                    'monthly_in'       => $monthIn,
                ]);

                //

                $reqtype = $request->reqtyp;
                $reqsour = $request->reqsour;
                $reqclass = $request->reqclass;
                $reqcomm = $request->reqcomm;
                $webcomm = $request->webcomm;
                $update = Carbon::now('Asia/Riyadh');

                if ($fundingReq->statusReq == 0) {

                    if (strlen($reqcomm) > 4) {
                        $this->updateNewReq($reqID);
                    }
                }

                $this->records($reqID, 'comment', $reqcomm);
                $this->records($reqID, 'commentWeb', $webcomm);

                $getsourceValue = DB::table('request_source')->where('id', $reqsour)->first();
                if (!empty($getclassValue)) {
                    $this->records($reqID, 'reqSource', $getsourceValue->value);
                }

                $getclassValue = DB::table('classifcations')->where('id', $request->reqclass)->first();
                if (!empty($getclassValue)) {
                    $this->records($reqID, 'class_agent', $getclassValue->id);
                }

                $getclassValue = DB::table('classifcations')->where('id', $request->reqclass_m)->first();
                if (!empty($getclassValue)) {
                    $this->records($reqID, 'class_id_sm', $getclassValue->value);
                }

                $getclassValue = DB::table('classifcations')->where('id', $request->reqclass_fm)->first();
                if (!empty($getclassValue)) {
                    $this->records($reqID, 'class_id_fm', $getclassValue->value);
                }

                $getRejectionValue = DB::table('rejections_reasons')->where('id', $request->rejection_id_agent)->first();
                if (!empty($getRejectionValue)) {
                    $this->records($reqID, 'rejection_id_agent', $getRejectionValue->title);
                }
                // check if class_id_agent changed and request has quality_req change its status to completed
                if($request->reqclass != $requestModel->class_id_agent)
                {
                    MyHelpers::UpdatingRequest($requestModel);
                }
                DB::table('requests')->where('id', $reqID)->update([
                    'rejection_id_agent' => $request->rejection_id_agent,
                    'source'             => $reqsour,
                    'class_id_agent'     => $request->reqclass,
                    'class_id_sm'        => $request->reqclass_m,
                    'class_id_fm'        => $request->reqclass_fm,
                    'type'               => $reqtype,
                    'noteWebsite'        => $webcomm,
                    'comment'            => $reqcomm,
                    'updated_at'         => $update,
                    'collaborator_id'    => $request->collaborator,

                ]);

                $collName = DB::table('users')->where('id', $request->collaborator)->first();
                if (!empty($collName)) {
                    $this->records($reqID, 'collaborator_name', $collName->name);
                }

                //

                //I DELETE HISTORIES FROM HERE****

                //
                if ($deleteClassificationAlertSchedule33) {
                    ClassificationAlertSchedule::query()->where(['request_id' => $requestModel->id])->delete();
                }
                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Mobile Already Existed'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function records($reqID, $coloum, $value)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')->where('req_id', '=', $reqID)->where('colum', '=', $coloum)->max('id'); //to retrive id of last record update of comment

        if ($lastUpdate != null) {
            $rowOfLastUpdate = DB::table('req_records')->where('id', '=', $lastUpdate)->first();
        } //we get here the row of this id
        //

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        if ($lastUpdate == null && ($value != null)) {
            DB::table('req_records')->insert([
                'colum'          => $coloum,
                'user_id'        => (auth()->user()->id),
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => $userSwitch,
            ]);
        }

        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => (auth()->user()->id),
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => $userSwitch,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function updateNewReq($id)
    {
        $updateResult = DB::table('requests')->where([
            ['id', '=', $id],
            ['statusReq', '=', 0],
        ])->update([
            'statusReq' => 1, //open
        ]);

        //    return response($updateResult); // if 1: update succesfally
    }

    public function myReqstest()
    {
        $requests = DB::table('requests')->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->select('requests.*', 'customers.name as customer_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id',
            'customers.mobile', 'customers.birth_date', 'customers.work')->count();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        $collaborators = DB::table('user_collaborators')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');

        $collaborators->values()->all();

        // dd($collaborators);

        $qulitys = User::where('role', 5)->where('status', 1)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Admin.myReqs',
            compact('requests', 'regions', 'classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'all_status', 'all_salaries', 'founding_sources', 'collaborators', 'pay_status', 'salesAgents', 'qulitys', 'worke_sources', 'request_sources'));
    }

    public function status($getBy = 'empty')
    {
        $s = [
            0  => MyHelpers::admin_trans(auth()->user()->id, 'new req'),
            1  => MyHelpers::admin_trans(auth()->user()->id, 'open req'),
            2  => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales agent req'),
            3  => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            4  => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            //5 => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales manager req'),
            5  => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            6  => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            7  => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            // 8 => MyHelpers::admin_trans(auth()->user()->id, 'archive in funding manager req'),
            8  => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            9  => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            // 11 => MyHelpers::admin_trans(auth()->user()->id, 'archive in mortgage manager req'),
            11 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            12 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            13 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            //14 => MyHelpers::admin_trans(auth()->user()->id, 'archive in general manager req'),
            14 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            15 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            16 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            17 => MyHelpers::admin_trans(auth()->user()->id, 'draft in mortgage maanger'),
            18 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            19 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales agent req'),
            20 => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            21 => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            22 => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            23 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            24 => MyHelpers::admin_trans(auth()->user()->id, 'cancel mortgage manager req'),
            25 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            26 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            27 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            28 => MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),
            29 => MyHelpers::admin_trans(auth()->user()->id, 'Rejected and archived'),
            30 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            31 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            32 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            33 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            34 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            35 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[35]);
    }

    public function statusPay($getBy = 'empty')
    {
        $s = [
            0  => MyHelpers::admin_trans(auth()->user()->id, 'draft in funding manager'),
            1  => MyHelpers::admin_trans(auth()->user()->id, 'wating for sales maanger'),
            2  => MyHelpers::admin_trans(auth()->user()->id, 'funding manager canceled'),
            3  => MyHelpers::admin_trans(auth()->user()->id, 'rejected from sales maanger'),
            4  => MyHelpers::admin_trans(auth()->user()->id, 'wating for sales agent'),
            5  => MyHelpers::admin_trans(auth()->user()->id, 'wating for mortgage maanger'),
            6  => MyHelpers::admin_trans(auth()->user()->id, 'rejected from mortgage maanger'),
            7  => MyHelpers::admin_trans(auth()->user()->id, 'approve from mortgage maanger'),
            8  => MyHelpers::admin_trans(auth()->user()->id, 'mortgage manager canceled'),
            9  => MyHelpers::admin_trans(auth()->user()->id, 'The prepayment is completed'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected from funding manager'),
            11 => MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),

        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }

    public function myReqstest_datatable(Request $request)
    {

        $requests = DB::table('requests')->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings',
            'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
            //  ->leftjoin('quality_reqs', 'quality_reqs.req_id', '=', 'requests.id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'requests.class_id_quality as is_quality_recived')->orderBy('requests.created_at', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                $requests = $requests->where('requests.class_id_quality', '!=', null);
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                $requests = $requests->where('requests.class_id_quality', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            $requests = $requests->whereIn('payStatus', $request->get('pay_status'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('customers.salary', $request->get('customer_salary'));
        }

        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            $requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
        }

        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $requests = $requests->whereIn('customers.work', $request->get('work_source'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            $requests = $requests->whereIn('customers.salary_id', $request->get('salary_source'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }/*
        if ($request->has('search')) {
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
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            if ($row->type != 'رهن-شراء' && $row->type != 'شراء-دفعة' && $row->statusReq != 16 && $row->statusReq != 15 && $row->statusReq != 14) {
                $data = $data.'<span class="item pointer" id="move" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                <i class="fas fa-random"></i></span> ';
            }

            $data = $data.'<span class="item pointer" id="addQuality" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add To Quality').'">
            <i class="fas fa-envelope"></i></span> ';

            $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-alt"></i></a></span>';

            $data = $data.'</div>';

            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {

            $data = $row->agent_date;

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
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {

            $data = '<div style="text-align: center;">';

            if ($row->class_id_quality != null) {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }

            return $data.'</div>';
        })->rawColumns(['is_quality_recived', 'action'])->make(true);
    }
}

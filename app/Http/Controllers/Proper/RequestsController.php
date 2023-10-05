<?php

namespace App\Http\Controllers\Proper;
use App\askary_work;
use App\City;
use App\classifcation;
use App\CollaboratorRequest;
use App\CustomersPhone;
use App\District;
use App\document;
use App\funding;
use App\funding_source;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCustomerRequest;
use App\joint;
use App\madany_work;
use App\military_ranks;
use App\Model\RequestSearching;
use App\Models\Customer;
use App\Models\PrePayment;
use App\Property;
use App\PropertyRequest;
use App\Models\RealEstate;
use App\Models\Request as RequestModel;
use App\Models\RequestSource;
use App\Models\User;
use App\notification;
use App\real_estat;
use App\realType;
use App\RejectionsReason;
use App\reqRecord;
use App\requestHistory;
use App\salary_source;
use App\WorkSource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MyHelpers;
use View;
class RequestsController extends Controller
{
    protected $status;
    public function __construct()
    {
        View::composers([
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }
    public function addCustomer()
    {
        return view('Proper.Customer.addCustomerWithReq');
    }
    public function storeCustomer(AddCustomerRequest $request)
    {
        $agentID = getNextAgentForCollaborates();
        $isForMe = true;

        if ($agentID == 0){
            $isForMe = false;
            $agentID = getLastAgentOfDistribution();
        }
        $validator = Validator::make($request->all(),[
            "mobile" => "unique:customers|unique:customers_phones"
        ],[
            'mobile.required'      => 'حقل رقم الجوال إلزامي',
            'mobile.unique'      => 'رقم الجوال غير متاح ',
        ]);
        if ($validator->passes()) {
            $customer = customer::create([
                'user_id'=> $agentID ?? 61,
                'name'=> $request->name,
                'password'=> bcrypt(123456789),
                'welcome_message'=> 2,
                'mobile'=> $request->mobile,
                'created_at'=> (Carbon::now('Asia/Riyadh')),
                'isVerified'=> true,
                'salary' => $request->salary
            ]);
            $join =joint::create(['created_at' => (Carbon::now('Asia/Riyadh'))]);
            $real = real_estat::create(['created_at' => (Carbon::now('Asia/Riyadh'))]);
            $fun = funding::create(['created_at' => (Carbon::now('Asia/Riyadh'))]);
            $reqdate = (Carbon::now('Asia/Riyadh'));
            $searching_id = RequestSearching::create()->id;
            $req = RequestModel::create([
                'source'=> 2,'req_date'=> $reqdate,'created_at'=> (Carbon::now('Asia/Riyadh')),'searching_id'=> $searching_id,'user_id'=> $agentID ?? 61,
                'customer_id'=> $customer->id,'collaborator_id'=> auth()->id(),'joint_id'=> $join->id,
                'real_id'=> $real->id,'fun_id'=> $fun->id,'statusReq'=> 0,'agent_date'=> Carbon::now('Asia/Riyadh'),
            ]);
            if ($isForMe){
                setLastAgentOfDistributionCollaborator($agentID);
            }else{
                setLastAgentOfDistribution($agentID,false);
            }

            if ($req) {
                MyHelpers::incrementDailyPerformanceColumn($agentID ?? 61, 'received_basket',$req->id);
                $this->history($req->id, MyHelpers::admin_trans(auth()->user()->id, 'Create Request'), null, null);
                return redirect()->route('proper.request.show', $req->id)->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
            }
        }else{
            $customer = Customer::where("mobile",$request->mobile)->first();
            if (!$customer){
                $customer = CustomersPhone::where("mobile",$request->mobile)->first();
                $customerId = $customer->customer_id;
            }else{
                $customerId = $customer->id;
            }
            $requestModel = RequestModel::where("customer_id",$customerId)->first();
            CollaboratorRequest::firstOrCreate([
                "user_id"    => auth()->id(),
                "type"    => 'repeated',
                "req_id"    => $requestModel->id,
            ],[
                "user_id"    => auth()->id(),
                "type"    => 'repeated',
                "req_id"    => $requestModel->id,
            ]);
            $request->validate([
                "mobile" => "unique:customers|unique:customers_phones"
            ]);
        }

    }
    public function Propertyrequest($id){
        /*if (\request()->has("not_id")){
            DB::table("notifications")->where("id",\request("not_id"))->update([
                "status"    => 1,
            ]);
        }*/
        $data = PropertyRequest::with(['propertyType','district','city','area','customer'])->find($id);
        $values = Property::where("creator_id",auth()->id())->when($data->area_id != null,function($q,$v) use ($data){
            $q->where("area_id",$data->area_id);
        })->when($data->city_id != null,function($q,$v) use ($data){
            $q->where("city_id",$data->city_id);
        })->when($data->district_id != null,function($q,$v) use ($data){
            $q->where("district_id",$data->district_id);
        })->when($data->property_type_id != null,function($q,$v) use ($data){
            $q->where("type_id",$data->property_type_id);
        })->when($data->min_price != null && $data->max_price == null,function($q,$v) use ($data){
            $q->where("fixed_price",">",$data->min_price);
        })->when($data->min_price == null && $data->max_price != null,function($q,$v) use ($data){
            $q->where("fixed_price","<",$data->max_price);
        })->when($data->min_price != null && $data->max_price != null,function($q,$v) use ($data){
            $q->whereBetween("fixed_price",[$data->min_price,$data->max_price]);
        })->get();


        return view('Proper.PropertyRequest.show',[
            'data'  => $data,
            'values'  => $values,
        ]);

    }
    public function requests()
    {
        return view('Proper.Request.index',$this->returnData());
    }
    public function archives()
    {
        return view('Proper.Request.archives',$this->returnData());
    }
    public function actives()
    {
        return view('Proper.Request.actives',$this->returnData());
    }
    public function returnData() {
        $regions = customer::select('region_ip')->groupBy('region_ip')->get();
        $salesAgents = RequestModel::with('user')->where('collaborator_id',auth()->user()->id)->groupBy('user_id')->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $all_status = $this->status();
        return [
            'all_status'            =>$all_status,
            'classifcations_sa'     =>$classifcations_sa,
            'salesAgents'           =>$salesAgents,
            'regions'               =>$regions
        ];
    }
    public function datatable($status = null)
    {
        $userID = (auth()->user()->id);
        $requests = \App\Models\Request::where('collaborator_id', $userID)
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'users.name as user_name', 'customers.name as cust_name', 'customers.mobile as mobile', 'customers.id as cust_id', 'requests.class_id_quality as is_quality_recived')
            ->orderBy('req_date', 'DESC');

        if ($status == 'archive') {
            $requests = $requests->where('statusReq','=', 2);
        }
        elseif ($status == 'active') {
            $requests = $requests->where('statusReq', '<>', 2);
        }

        if (request()->get('agents_ids') && is_array(request()->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', request()->get('agents_ids'));
        }
        if (request()->get('classifcation_sa') && is_array(request()->get('classifcation_sa'))) {
            $requests = $requests->whereIn('requests.class_id_agent', request()->get('classifcation_sa'));
        }
        if (request()->get('req_date_from')) {
            $requests = $requests->whereDate('agent_date', '>=', request()->get('req_date_from'));
        }
        if (request()->get('req_date_to')) {
            $requests = $requests->whereDate('agent_date', '<=', request()->get('req_date_to'));
        }
        if (request()->get('notes_status')) {
            if (request()->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('collaborator_notes', '!=', null);
            }

            if (request()->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('collaborator_notes', null);
            }
        }
        if (request()->get('req_status') && is_array(request()->get('req_status'))) {
            if (request()->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', request()->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', request()->get('req_status'));
            }
        }
        if (request()->get('customer_phone')) {
            $mobile = Customer::where('mobile', request()->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', request()->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', request()->get('customer_phone'));
            }
        }
        return Datatables::of($requests)
            ->addColumn('action', function ($row) {
                $data = '<div class="tableAdminOption">';
                $data = $data.'<span class="item pointer"  id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('proper.request.show', $row->id).'"><i class="fas fa-eye"></i></a></span>';

                $data = $data.'</div>';
                return $data;
            })
            ->editColumn('created_at', function ($row) {
                $data = $row->created_at;
                return Carbon::parse($data)->format('Y-m-d g:ia');
            })->editColumn('agent_date', function ($row) {
                $data = $row->agent_date;
                return Carbon::parse($data)->format('Y-m-d g:ia');
            })->editColumn('statusReq', function ($row) {
                if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                    return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
                }
                else {
                    return @$this->status()[$row->statusReq] ?? $this->status()[28];
                }
            })->editColumn('class_id_agent', function ($row) {
                $get_agent_and_status_of_show = [];
                $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassification($row->id, $row->comment);
                $hide_negative_comment = $get_agent_and_status_of_show[1];
                if (!$hide_negative_comment) {
                    $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();
                    if ($classifcations_sa != null) {
                        return $classifcations_sa->value;
                    }
                    else {
                        return $row->class_id_agent;
                    }
                }
                else {
                    return '';
                }
            })->make(true);
    }
    public function show($id)
    {
        $data['userID'] = auth()->user()->id;
        $data['purchaseCustomer'] = RequestModel::query()->with(
            [
                'customer','joint','funding','real_estat','agentClassification','prePayment','collaborator'
            ]
            )->where('id',$id)->first();
        $data['agentuser'] = User::find($data['purchaseCustomer']->user_id);
                $data['reqStatus'] = $data['purchaseCustomer']->statusReq;
                $data['purchaseJoint'] =$data['purchaseCustomer']->joint;
                $data['purchaseReal'] =$data['purchaseCustomer']->real_estat;
                $data['purchaseFun'] =$data['purchaseCustomer']->funding;
                $data['purchaseClass'] =$data['purchaseCustomer']->agentClassification;
                $data['purchaseTsa'] = $data['purchaseCustomer']->prePayment;
                $data['collaborator'] = $data['purchaseCustomer']->collaborator;
                $data['collaborators'] = auth()->user()->agents;
                $data['product_types'] = null;
                $getTypes = MyHelpers::getProductType();
                if ($getTypes != null) {
                    $data['product_types'] = $getTypes;
                }
                $data['get_agent_and_status_of_show'] = [];
                $data['get_agent_and_status_of_show'] = MyHelpers::hideCommentOfNegativeClassification($id, $data['purchaseCustomer']->comment);
                $data['history_negative_agent'] = $data['get_agent_and_status_of_show'][0];
                $data['hide_negative_comment'] = $data['get_agent_and_status_of_show'][1];
                $data['salary_sources'] = salary_source::select('id', 'value')->get();
                $data['cities'] = City::select('id', 'value')->get();
                $data['ranks'] = military_ranks::select('id', 'value')->get();
                $data['funding_sources'] = funding_source::select('id', 'value')->get();
                $data['askary_works'] = askary_work::select('id', 'value')->get();
                $data['madany_works'] = madany_work::select('id', 'value')->get();
                $data['realTypes'] = realType::select('id', 'value')->get();
                $data['user_role'] = User::select('role')->where('id', $data['userID'])->get();
                $data['classifcations'] =classifcation::select('id', 'value')->where('user_role', $data['user_role'][0]->role)->get();

                $data['documents'] = document::with('user')->requestDocs($id)->get();
                $data['notifys'] = $this->fetchNotify(); //get notificationes
                $data['followdate'] =notification::where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)->get()->last(); //to get last reminder
                $data['followtime'] = ($data['followdate'] != null ? Carbon::parse($data['followdate']->reminder_date)->format('H:i') : null);
                if (!empty($data['followdate'])) {
                    $data['followdate']->reminder_date = (Carbon::parse($data['followdate']->reminder_date)->format('Y-m-d'));
                }
                MyHelpers::openReqWillOpenNotify($id);
                $data['show_funding_source'] = MyHelpers::canShowBankName(auth()->user()->id);
                $data['is_customer_reopen_request'] = false;
                $data['histories'] = requestHistory::where('req_id', $id)->where('title', 'فتح الطلب')->first();
                if (!empty($data['histories'])) {
                    $data['is_customer_reopen_request'] = true;
                }
                $data['districts'] = District::all();
                $data['prefix'] = 'proper';
                $data['worke_sources'] = WorkSource::all();
                $data['request_sources'] = RequestSource::all();
                $data['rejections'] = RejectionsReason::all();

               /* return view('Proper.Request.single.fundingreqpage',
                compact('history_negative_agent', 'hide_negative_comment', 'purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'districts', 'prefix', 'id',
                    'documents', 'reqStatus', 'notifys', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'agentuser', 'followtime', 'realTypes', //'missedFileds',
                    'show_funding_source', 'is_customer_reopen_request', 'product_types', 'rejections', 'worke_sources', 'request_sources'));
                */

                return view('Proper.Request.single.fundingreqpage',$data,compact('id'));


    }
    public function records($reqID, $coloum, $value)
    {
        $lastUpdate = reqRecord::where('req_id', '=', $reqID)->where('colum', '=', $coloum)->max('id'); //to retrive id of last record update of comment

        if ($lastUpdate != null) {
            $rowOfLastUpdate = reqRecord::find($lastUpdate);
        }
        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }
        if ($lastUpdate == null && ($value != null)) {
            reqRecord::create([
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

                reqRecord::create([
                    'colum'          => $coloum,
                    'user_id'        => (auth()->user()->id),
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => $userSwitch,
                ]);
            }
        }

    }
    public function storeRequestRecords($request,$requestModel)
    {
        $this->records($requestModel->id, 'collaborator_notes', $request->collaborator_notes);
        $this->records($requestModel->id, 'private_notes', $request->private_notes);
        $this->records($requestModel->id, 'customerName', $request->name);
        $this->records($requestModel->id, 'mobile', $request->mobile);
        $this->records($requestModel->id, 'sex', $request->sex);
        $this->records($requestModel->id, 'birth_date', $request->birth);
        $this->records($requestModel->id, 'birth_hijri', $request->birth_hijri);
        $this->records($requestModel->id, 'salary', $request->salary);
        $this->records($requestModel->id, 'regionip', $request->regionip);

        if ($request->is_support != null) {
            if ($request->is_support == 'no') {
                $this->records($requestModel->id, 'support', 'لا');
            }
            if ($request->is_support == 'yes') {
                $this->records($requestModel->id, 'support', 'نعم');
            }
        }
        if ($request->has_obligations != null) {
            if ($request->has_obligations == 'no') {
                $this->records($requestModel->id, 'obligations', 'لا');
            }
            if ($request->has_obligations == 'yes') {
                $this->records($requestModel->id, 'obligations', 'نعم');
            }
        }

        if ($request->has_financial_distress != null) {
            if ($request->has_financial_distress == 'no') {
                $this->records($requestModel->id, 'distress', 'لا');
            }
            if ($request->has_financial_distress == 'yes') {
                $this->records($requestModel->id, 'distress', 'نعم');
            }
        }

        $getworkValue = WorkSource::find($request->work);
        if (!empty($getworkValue)) {
            $this->records($requestModel->id, 'work', $getworkValue->value);
        }

        $this->records($requestModel->id, 'obligations_value', $request->obligations_value);
        $this->records($requestModel->id, 'financial_distress_value', $request->financial_distress_value);

        $this->records($requestModel->id, 'jobTitle', $request->job_title);

        $getsalaryValue = salary_source::find( $request->salary_source);
        if (!empty($getsalaryValue)) {
            $this->records($requestModel->id, 'salary_source', $getsalaryValue->value);
        }

        $getaskaryValue = askary_work::find($request->askary_work);
        if (!empty($getaskaryValue)) {
            $this->records($requestModel->id, 'askaryWork', $getaskaryValue->value);
        }

        $getmadanyValue = madany_work::find($request->madany_work);
        if (!empty($getmadanyValue)) {
            $this->records($requestModel->id, 'madanyWork', $getmadanyValue->value);
        }
        $getrankValue = military_ranks::find($request->rank);
        if (!empty($getrankValue)) {
            $this->records($requestModel->id, 'rank', $getrankValue->value);
        }

        $this->records($requestModel->id, 'fundDur', $request->fundingdur);
        $this->records($requestModel->id, 'fundPers', $request->fundingpersonal);
        $this->records($requestModel->id, 'fundPersPre', $request->fundingpersonalp);
        $this->records($requestModel->id, 'fundReal', $request->fundingreal);
        $this->records($requestModel->id, 'fundRealPre', $request->fundingrealp);
        $this->records($requestModel->id, 'fundDed', $request->dedp);
        $this->records($requestModel->id, 'fundMonth', $request->monthIn);

        $getfundingValue =funding_source::find($request->funding_source);
        if (!empty($getfundingValue)) {
            $this->records($requestModel->id, 'funding_source', $getfundingValue->value);
        }


        $this->records($requestModel->id, 'realName', $request->realname);
        $this->records($requestModel->id, 'realMobile', $request->realmobile);
        $getcityValue = DB::table('cities')->where('id', $request->realcity)->first();
        if (!empty($getcityValue)) {
            $this->records($requestModel->id, 'realCity', $getcityValue->value);
        }
        $this->records($requestModel->id, 'realRegion', $request->realregion);
        $this->records($requestModel->id, 'realPursuit', $request->realpursuit);
        $this->records($requestModel->id, 'realAge', $request->realage);
        $this->records($requestModel->id, 'realStatus', $request->realstatus);
        $this->records($requestModel->id, 'realCost', $request->realcost);
        if ($request->owning_property == 'no') {
            $this->records($requestModel->id, 'owning_property', 'لا');
        }
        if ($request->owning_property == 'yes') {
            $this->records($requestModel->id, 'owning_property', 'نعم');
        }
        $gettypeValue = DB::table('real_types')->where('id', $request->realtype)->first();
        if (!empty($gettypeValue)) {
            $this->records($requestModel->id, 'realType', $gettypeValue->value);
        }
        $this->records($requestModel->id, 'residence_type', $request->residence_type);

    }
    public function updatefunding(Request $request)
    {
        $requestModel = RequestModel::query()->with(['customer','funding','prePayment'])->findOrFail($request->reqID);
        $this->updateReal($request,$requestModel->real_id);
        $this->updateCustomer($requestModel->customer_id,$request);
        $checkFollow = DB::table('notifications')->where('req_id', '=', $requestModel->id)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)->where('status', '=', 2)->first(); // check dublicate
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
                        'value'         => \App\Helpers\MyHelpers::admin_trans(auth()->id(), 'The request need following'),
                        'recived_id'    => (auth()->id()),
                        'status'        => 2,
                        'type'          => 1,
                        'reminder_date' => $newValue,
                        'req_id'        => $requestModel->id,
                        'created_at'    => (Carbon::now('Asia/Riyadh')),
                    ]);
                }
                else {
                    DB::table('notifications')->where('id', $checkFollow->id)->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]); //set new notifiy
                }
            }
            else {
                #if empty reminder, so the reminder ll remove if it's existed.
                if (!empty($checkFollow)) {
                    DB::table('notifications')->where('id', $checkFollow->id)->delete();
                }
            }
        // send notification to related users
        if(trim($requestModel->collaborator_notes) != trim($request->collaborator_notes) && trim($request->collaborator_notes) != ''){
            $msg = auth()->user()->name .': #Req '. $requestModel->id . ' ' . \App\Helpers\MyHelpers::admin_trans(auth()->id(), 'New Note');
            \App\Helpers\MyHelpers::SendNotificationToUser($msg, $requestModel->user_id, $requestModel->id);
        }
        // send notification to related users end
        $this->storeRequestRecords($request,$requestModel);
        DB::table('requests')->where('id', $requestModel->id)->update([
            "collaborator_notes"    => $request->collaborator_notes,
            "private_notes"    => $request->private_notes
        ]);

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }
    private function updateReal($request, $realId)
    {
        RealEstate::findOrFail($realId)->update([
            'name'            => $request->realname,
            'mobile'          => $request->realmobile,
            'city'            => $request->realcity,
            'region'          => $request->realregion,
            'pursuit'         => $request->realpursuit,
            'age'             => $request->realage,
            'status'          => $request->realstatus,
            'cost'            => $request->realcost,
            'type'            => $request->realtype,
            'other_value'     => $request->othervalue,
            'evaluated'       => $request->realeva,
            'tenant'          => $request->realten,
            'mortgage'        => $request->realmor,
            'has_property'    => $request->realhasprop,
            'owning_property' => $request->owning_property,
            'residence_type'  => $request->residence_type,
        ]);
        return true;
    }
    private function updatePrepayment($paymentId,$request) : bool
    {
        \DB::table('prepayments')->where('id',$paymentId)->update([
            'realCost'                      => $request->real,
            'incValue'                      => $request->incr,
            'prepaymentVal'                 => $request->preval,
            'prepaymentPre'                 => $request->prepre,
            'prepaymentCos'                 => $request->precos,
            'visa'                          => $request->visa,
            'carLo'                         => $request->carlo,
            'personalLo'                    => $request->perlo,
            'realLo'                        => $request->realo,
            'credit'                        => $request->credban,
            'netCustomer'                   => $request->net,
            'other'                         => $request->other1,
            'debt'                          => $request->debt,
            'mortPre'                       => $request->morpre,
            'mortCost'                      => $request->morcos,
            'proftPre'                      => $request->propre,
            'deficitCustomer'               => $request->deficit,
            'profCost'                      => $request->precos,
            'addedVal'                      => $request->valadd,
            'adminFee'                      => $request->admfe,
            'req_id'                        => $request->reqID,
            'pay_date'                      => (Carbon::today('Asia/Riyadh')->format('Y-m-d')),
            'Real_estate_disposition_value' => $request->Real_estate_disposition_value_tsaheel,
            'purchase_tax_value'            => $request->purchase_tax_value_tsaheel,
        ]);
        return true;
    }
    private function updateCustomer($customerId, $request) : bool
    {
        Customer::findOrFail($customerId)
            ->update([
                'name'                     => $request->name,
                'mobile'                   => $request->mobile,
                'sex'                      => $request->sex,
                'birth_date'               => $request->birth,
                'birth_date_higri'         => $request->birth_hijri,
                'hiring_date'         => $request->hiring_date,
                'age'                      => $request->age,
                'age_years'                => $request->age_years,
                'work'                     => $request->work,
                'madany_id'                => $request->madany_work,
                'job_title'                => $request->job_title,
                'askary_id'                => $request->askary_work,
                'military_rank'            => $request->rank,
                'salary_id'                => $request->salary_source,
                'salary'                   => $request->salary,
                'basic_salary'             => $request->basic_salary,
                'is_supported'             => $request->is_support,
                'has_obligations'          => $request->has_obligations,
                'obligations_value'        => $request->obligations_value,
                'has_financial_distress'   => $request->has_financial_distress,
                'financial_distress_value' => $request->financial_distress_value,
                'without_transfer_salary'  => $request->without_transfer_salary,
                'add_support_installment_to_salary'  => $request->add_support_installment_to_salary,
                'guarantees'               => $request->guarantees,
                'region_ip'                => $request->regionip,
            ]);
        return true;
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

        document::create([
            'filename'    => $name,
            'location'    => $path,
            'upload_date' => $upload_date,
            'req_id'      => $reqID,
            'user_id'     => $userID,
        ]);

        $documents = document::with('user')->requestDocs($reqID)->get();

        return response()->json($documents);
    }
    public function openFile($id)
    {
        $document = document::where('id', '=', $id)->first();
        $request = RequestModel::findOrFail($document->req_id);
        if (($request->user_id == auth()->user()->id) || (auth()->user()->id ==  $document->user_id)) {
            try {
                $filename = $document->location;
                return response()->file(storage_path('app/public/'.$filename));
            }catch (\Exception $e){
                return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');
            }
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }
    public function downloadFile($id)
    {
        $document = document::where('id', '=', $id)->first();
        try {
            $filename = $document->location;
            return response()->download(storage_path('app/public/'.$filename));
        }catch (\Exception $e){
            return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

        }
    }
    public function deleteFile(Request $request) {

        $document = document::where('id',$request->id)->first();
        $result = $document;
        unlink(storage_path('app/public/'.$document->location));
          try{
          $document->delete();
          }catch(Exception $e){
              return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
          }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete successfully'), 'status' => 1]);
    }

    /***************************************************************/
    // Helpers
    /***************************************************************/
    public function checkReciveReqOpenAndWithoutCommentAndClass($userID)
    {

        return RequestModel::where('user_id', $userID)->where('statusReq', 1)->where('is_followed', 0)->where('is_stared', 0)->where(function ($query) {
            $query->where('class_id_agent', null)->orWhere('comment', null);
        })->pluck('id')->toArray();
    }
    public function fetchNotify()
    { // to get notificationes of users
        return Notification::where('recived_id', (auth()->user()->id))->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('notifications.status', 0) // new
        ->orderBy('notifications.id', 'DESC')->select('notifications.*', 'customers.name')->get();
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
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
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
    public function history($reqID, $title, $recived_id, $comment)
    {

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        requestHistory::create([
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
}

<?php

namespace App\Http\Controllers;

use App\customer;
use App\Models\User;
use App\Traits\General;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;
use const d;

class CustomerController extends Controller
{
    use General;

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content', 'layouts.Customermaster', 'Customer.customerIndexPage', 'Customer.fundingReq.customerReqLayout'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.Customermaster', 'Customer.fundingReq.customerReqLayout'],
        ]);
    }

    public static function customerProfile()
    {
        return view('Customer.profilePage');
    }

    public static function editCustomerProfile()
    {
        return view('Customer.editProfilePage');
    }

    public static function salesAgent()
    {

        $request = DB::table('requests')->where('customer_id', auth()->guard('customer')->user()->id)->first();

        if (!$request) {
            $request = DB::table('pending_requests')->where('customer_id', auth()->guard('customer')->user()->id)->first();
        }

        return $request->user_id ?? '';
    }

    public static function requestID()
    {

        $request = DB::table('requests')->where('customer_id', auth()->guard('customer')->user()->id)->first();

        if (!$request) {
            $request = DB::table('pending_requests')->where('customer_id', auth()->guard('customer')->user()->id)->first();
        }

        return $request->id ?? '';
    }

    public static function requestInfo()
    {

        $request = DB::table('requests')->where('customer_id', auth()->guard('customer')->user()->id)->first();

        if (!$request) {
            $request = DB::table('pending_requests')->where('customer_id', auth()->guard('customer')->user()->id)->first();
        }

        return $request;
    }

    public static function fileds($getBy)
    {
        $s = [

            "customerReq_customerName"                  => 'name',
            "customerReq_customerSex"                   => 'sex',
            "customerReq_customerMobile"                => 'mobile',
            "customerReq_customerDOB"                   => 'birth_date_higri',
            "customerReq_customerWork"                  => 'work',
            "customerReq_customerRegion"                => 'region_ip',
            "customerReq_customerWorkMadanySource"      => 'madany_id',
            "customerReq_customerWorkMadany"            => 'job_title',
            "customerReq_customerWorkAskarySource"      => 'askary_id',
            "customerReq_customerWorkAskaryRank"        => 'military_rank',
            "customerReq_customerSalary"                => 'salary',
            "customerReq_customerSalarySource"          => 'salary_id',
            "customerReq_customerSupport"               => 'is_supported',
            "customerReq_customerObligations"           => 'has_obligations',
            "customerReq_customerObligationsCost"       => 'obligations_value',
            "customerReq_customerFinancialDistress"     => 'has_financial_distress',
            "customerReq_customerFinancialDistressCost" => 'financial_distress_value',

            "customerReq_jointName"          => 'name',
            "customerReq_jointMobile"        => 'mobile',
            "customerReq_jointSalary"        => 'salary',
            "customerReq_jointSalarySource"  => 'salary_id',
            "customerReq_jointDOB"           => 'birth_date_higri',
            "customerReq_jointWork"          => 'work',
            "customerReq_jointFundingSource" => 'funding_id',

            "customerReq_realName"              => 'name',
            "customerReq_realMobile"            => 'mobile',
            "customerReq_realCity"              => 'city',
            "customerReq_realDistrict"          => 'region',
            "customerReq_realFundingProfit"     => 'pursuit',
            "customerReq_realStatus"            => 'status',
            "customerReq_realAge"               => 'age',
            "customerReq_realFundingMortgage"   => 'mortgage_value',
            "customerReq_realType"              => 'type',
            "customerReq_realCost"              => 'cost',
            "customerReq_realCustomerHasReal"   => 'owning_property',
            "customerReq_realCustomerFoundReal" => 'has_property',
            "customerReq_realAssment"           => 'evaluated',
            "customerReq_realTenants"           => 'tenant',
            "customerReq_realMortgaged"         => 'mortgage',

            "customerReq_fundingSource"             => 'funding_source',
            "customerReq_fundingDuration"           => 'funding_duration',
            "customerReq_fundingPersonalCost"       => 'personalFun_cost',
            "customerReq_fundingPersonalPresentage" => 'personalFun_pre',
            "customerReq_fundingRealCost"           => 'realFun_cost',
            "customerReq_fundingRealPresentage"     => 'realFun_pre',
            "customerReq_fundingDeductionRate"      => 'ded_pre',
            "customerReq_fundingMonthlyInstallment" => 'monthly_in',

            "customerReq_prepaymentRealCost"            => 'realCost',
            "customerReq_prepaymentRealIncreaseCost"    => 'incValue',
            "customerReq_prepaymentCost"                => 'prepaymentVal',
            "customerReq_prepaymentPresentage"          => 'prepaymentPre',
            "customerReq_prepaymentCostAfterPresentage" => 'prepaymentCos',
            "customerReq_prepaymentCustomerNet"         => 'netCustomer',
            "customerReq_prepaymentCustomerDeficit"     => 'deficitCustomer',
            "customerReq_prepaymentVisa"                => 'visa',
            "customerReq_prepaymentCar"                 => 'carLo',
            "customerReq_prepaymentPersonal"            => 'personalLo',
            "customerReq_prepaymentReal"                => 'realLo',
            "customerReq_prepaymentBank"                => 'credit',
            "customerReq_prepaymentOther"               => 'other',
            "customerReq_prepaymentTotalDebt"           => 'debt',
            "customerReq_prepaymentMortgagePresantage"  => 'mortPre',
            "customerReq_prepaymentMortgageCost"        => 'mortCost',
            "customerReq_prepaymentProfitPresantage"    => 'proftPre',
            "customerReq_prepaymentProfitCost"          => 'profCost',
            "customerReq_prepaymentValueAdded"          => 'addedVal',
            "customerReq_prepaymentAdminFees"           => 'adminFee',

            "customerReq_attachments" => '',

        ];

        return (isset($s[$getBy]) ? $s[$getBy] : '');
    }

    public function index()
    {
        return view('Customer.customerIndexPage');
    }

    public function my_reqs()
    {

        return view('Customer.PropertyRequests');
    }

    public function helpDeskPage()
    {
        return view('Customer.askHelpDeskPage');
    }

    public function checkMobileAvailability($mobile)
    {
        $found = customer::where('mobile', $mobile)->first();
        if ($found) {
            return response()->json(['msg' => MyHelpers::guest_trans('Mobile Used'), 'status' => 'found']);
        }
        else {
            return response()->json(['msg' => MyHelpers::guest_trans('Mobile Available'), 'status' => 'available']);
        }
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
        $userID = au % d;
        $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $file = $request->file('file');

        // generate a new filename. getClientOriginalExtension() for the file extension
        $filename = $name.time().'.'.$file->getClientOriginalExtension();

        // save to storage/app/photos as the new $filename
        $path = $file->storeAs('documents', $filename);

        $docID = DB::table('documents')->insertGetId([
            'filename'    => $name,
            'location'    => $path,
            'upload_date' => $upload_date,
            'req_id'      => $reqID,
            'user_id'     => $userID,
        ]);

        //$docRow = DB::table('documents')->where('id', $docID)->first();

        $documents = DB::table('documents')->where('req_id', '=', $reqID)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
        ->select('documents.*', 'users.name')->get();

        return response()->json($documents);
    }

    public function fundingreqpage($id)
    {
        if ($this->checkCustomerRequest($id)) {

            $request = DB::table('requests')->where('requests.id', '=', $id)->first();

            if (!$request) {
                $request = DB::table('pending_requests')->where('id', '=', $id)->first();
            }

            $reqStatus = $request->statusReq;

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

            if (!$purchaseCustomer) {
                $purchaseCustomer = DB::table('pending_requests')->leftjoin('customers', 'customers.id', '=', 'pending_requests.customer_id')->where('pending_requests.id', '=', $id)->first();
            }

            if (!isset($purchaseCustomer->type)) {
                $purchaseCustomer->type = 'afnan';
            }

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            if (!$purchaseJoint) {
                $purchaseJoint = DB::table('pending_requests')->leftjoin('joints', 'joints.id', '=', 'pending_requests.joint_id')->where('pending_requests.id', '=', $id)->first();
            }

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            if (!$purchaseReal) {
                $purchaseReal = DB::table('pending_requests')->leftjoin('real_estats', 'real_estats.id', '=', 'pending_requests.real_id')->where('pending_requests.id', '=', $id)->first();
            }

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            if (!$purchaseFun) {
                $purchaseFun = DB::table('pending_requests')->leftjoin('fundings', 'fundings.id', '=', 'pending_requests.fun_id')->where('pending_requests.id', '=', $id)->first();
            }

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            if (!$purchaseClass) {
                $purchaseClass = null;
            }

            $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

            if (!$purchaseTsa) {
                $purchaseTsa = null;
            }

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            if (!$collaborator) {
                $collaborator = DB::table('pending_requests')->join('users', 'users.id', '=', 'pending_requests.collaborator_id')->where('pending_requests.id', '=', $id)->first();
            }

            $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')->where('user_collaborators.user_id', auth()->user()->id)->get();

            $payment = null;
            if (isset($request->payment_id)) {
                $payment = DB::table('prepayments')->where('id', '=', $request->payment_id)->first();
            }

            $paymentForDisplayonly = null;

            if (isset($request->type)) {
                if ($request->type == 'شراء-دفعة' && $payment == null) {
                    $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)->first();
                }
            }

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();
            $classifcations = DB::table('classifcations')->select('id', 'value')->get();

            $documents = DB::table('documents')->where('req_id', '=', $id)->leftjoin('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            $followdate = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->user()->id))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            $fields = DB::table('settings')->where('option_name', 'LIKE', 'customerReq%')->where('option_value', 'show');

            return view('Customer.fundingReq.fundingReqPageTest', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseTsa', 'purchaseClass', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'id', //Request ID
                'documents', 'reqStatus', 'payment', 'followdate', 'collaborator', 'cities', 'ranks', 'collaborators', 'paymentForDisplayonly', 'followtime', 'realTypes', 'fields',));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "You do not have a premation to do that"));
        }
    }

    public function checkCustomerRequest($id)
    {

        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        if (!$request) {
            $request = DB::table('pending_requests')->where('id', '=', $id)->first();
        }

        return (auth()->guard('customer')->user()->id == $request->customer_id);
    }

    public function updateFoundProperty(Request $request)
    {
        $updateResult = DB::table('requests')->where([
            ['id', '=', $request->id],
        ])->update([
            'customer_found_property' => 1,
        ]);
        if ($updateResult) {
            $reqinfo = DB::table('requests')->where('id', $request->id)->first();

            DB::table('notifications')->insert([
                'value'      => 'عميلك وجد عقار',
                'recived_id' => $reqinfo->user_id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 5,
                'req_id'     => $request->id,
            ]);
            //$token = DB::table('users')->where('id',$reqinfo->user_id)->first();
            //$customer = DB::table('customers')->where('id',$reqinfo->customer_id)->first();
            $userModel = User::findOrFail($reqinfo->user_id);
            $customer = Customer::findOrFail($reqinfo->customer_id);
            $this->fcm_send($userModel->getPushTokens(), $customer->name."  إشعار من عميلك   ", "عميلك وجد عقار");
        }
        return response($updateResult); // if 1: update succesfally
    }

    public function needToEditReqInfo(Request $request)
    {

        $insertNewNotify = 0;
        $reqinfo = DB::table('requests')->where('id', $request->id)->first();
        //$userModel = DB::table('users')->where('id',$reqinfo->user_id)->first();
        //$customer = DB::table('customers')->where('id',$reqinfo->customer_id)->first();
        $userModel = User::findOrFail($reqinfo->user_id);
        $customer = Customer::findOrFail($reqinfo->customer_id);
        $previousNotify = DB::table('notifications')->where('req_id', $request->id)->where('type', 6)->where('status', 0)->where('recived_id', $reqinfo->user_id)->get()->count();

        if ($previousNotify == 0) {
            DB::table('notifications')->insert([
                'value'      => 'عميلك يحتاج إلى تعديل بياناته',
                'recived_id' => $reqinfo->user_id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 6,
                'req_id'     => $request->id,
            ]);
            $this->fcm_send($userModel->getPushTokens(), $customer->name."  إشعار من عميلك   ", "عميلك يحتاج إلى تعديل بياناته");
            return response(1);
        }

        return response($insertNewNotify);
    }

    public function updateCustomerResolveProblem(Request $request)
    {
        $updateResult = DB::table('requests')->where([
            ['id', '=', $request->id],
        ])->update([
            'customer_resolve_problem' => 1,
        ]);
        if ($updateResult) {
            $reqinfo = DB::table('requests')->where('id', $request->id)->first();

            DB::table('notifications')->insert([
                'value'      => 'عميلك حل مشكلة التمويل',
                'recived_id' => $reqinfo->user_id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 5,
                'req_id'     => $request->id,
            ]);
            //$token = DB::table('users')->where('id', $reqinfo->user_id)->first();
            //$customer = DB::table('customers')->where('id', $reqinfo->customer_id)->first();
            $userModel = User::findOrFail($reqinfo->user_id);
            $customer = Customer::findOrFail($reqinfo->customer_id);
            $this->fcm_send($userModel->getPushTokens(), $customer->name."  إشعار من عميلك   ", "عميلك حل مشكلة التمويل");
        }
        return response($updateResult); // if 1: update succesfally
    }

    public function updatecustomerwanttoreject(Request $request)
    {
        $updateResult = DB::table('requests')->where([
            ['id', '=', $request->id],
        ])->update([
            'customer_want_to_reject_req' => $request->customerwant,
        ]);
        if ($request->customerwant == 1) {
            $updateResult = DB::table('requests')->where([
                ['id', '=', $request->id],
            ])->update([
                'customer_reason_for_rejected' => $request->reasonvalue,
            ]);
        }
        if ($updateResult && $request->customerwant == 0) {
            $reqinfo = DB::table('requests')->where('id', $request->id)->first();
            DB::table('notifications')->insert([
                'value'      => 'عميلك لايريد إلغاء طلبه',
                'recived_id' => $reqinfo->user_id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 5,
                'req_id'     => $request->id,
            ]);
            //$token = DB::table('users')->where('id', $reqinfo->user_id)->first();
            //$customer = DB::table('customers')->where('id', $reqinfo->customer_id)->first();
            $userModel = User::findOrFail($reqinfo->user_id);
            $customer = Customer::findOrFail($reqinfo->customer_id);
            $this->fcm_send($userModel->getPushTokens(), $customer->name."  إشعار من عميلك   ", "عميلك لايريد إلغاء طلبه");
        }
        return response($updateResult); // if 1: update succesfally
    }

    public function openFile($id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();
        $request = DB::table('requests')->where('id', '=', $document->req_id)->first();

        if ($request->customer_id == auth()->guard('customer')->user()->id) {

            $filename = $document->location;
            return response()->file(storage_path('app/public/'.$filename));
        } // open without dowunload

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function downloadFile($id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();

        $filename = $document->location;
        return response()->download(storage_path('app/public/'.$filename)); // download
    }
}

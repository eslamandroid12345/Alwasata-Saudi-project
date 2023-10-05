<?php

namespace App\Http\Controllers\Frontend;

use App\Area;
use App\cities;
use App\City;
use App\CollaboratorRequest;
use App\customer;
use App\CustomersPhone;
use App\District;
use App\helpDesk;
use App\HelperFunctions\Helper;
use App\Http\Controllers\Controller;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\Models\Customer as ModelsCustomer;
use App\Models\CustomerPhone;
use App\Models\OtpRequest;
use App\Models\Request as ModelsRequest;
use App\Models\RequestHistory;
use App\Models\RequestJob;
use App\Property;
use App\realType;
use App\Traits\General;
use App\User;
use App\WorkSource;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Location;
use MyHelpers;

class PageController extends Controller
{
    use General;

    private $STATIC_PHONE = '551036844'; //Ahmed's phone for test.

    function property($id)
    {
        $property = Property::with('image', 'creator', 'type')->findOrFail($id);
        if ($property && $property->is_published == 1) {
            switch ($property->creator->role) {

                case '6': /* Collaborator */ if ($property->creator->allow_recived) { // if collaborator allowed recived  , he will be responsible to property request
                    $recv = $property->creator_id;
                }
                else {
                    $recv = MyHelpers::findNextPropertyAgent();
                }
                    break;

                case '10':  /* Propertor */ if ($property->creator->allow_recived) { // if Propertor allowed recived  , he will be responsible to property request
                    $recv = $property->creator->id;
                }
                else {
                    // get next property agent
                    $recv = MyHelpers::findNextPropertyAgent();
                }
                    break;
                default:
                    $recv = $property->creator_id;
            }
            return view('testWebsite.Pages.property', compact('property', 'recv'));
        }

        return redirect()->route('missing');
    }

    public function properties()
    {

        $types = realType::all();
        $properties = Property::where('is_published', 1)->paginate(9);

        $cities = cities::all();
        $maxRoom = Property::max('num_of_rooms');
        $minRoom = Property::min('num_of_rooms');

        $maxSalon = Property::max('num_of_salons');
        $minSalon = Property::min('num_of_salons');

        $maxKit = Property::max('num_of_kitchens');
        $minKit = Property::min('num_of_kitchens');

        $maxBath = Property::max('num_of_bathrooms');
        $minBath = Property::min('num_of_bathrooms');

        $cities = City::all();
        $areas = Area::all();
        $districts = District::all();
        return view('testWebsite.Pages.properities', compact('properties', 'areas', 'districts', 'cities', 'types', 'minBath', 'minKit', 'minRoom', 'minSalon', 'maxBath', 'maxKit', 'maxRoom', 'maxSalon'));
    }

    public function newPage()
    {
        return view('Frontend.newPage');
    }

    public function getIndex(Request $request)
    {
        $id = '';
        $page = DB::table('pages')->where('slug', $request->segment(2))->first();
        if ($page) {
            if (!is_null($page->form_type)) {
                $template = config('cms.form_templates')[$page->form_type];
                $page->template_path = "Frontend.pages.form_templates.".$template;
            }
            //  dd( $page->template_path);
            return view('Frontend.pages.index', compact('page', 'id'));
        }
        else {
            //page not found
            return 'Error';
        }
    }

    public function duplicateCustomer()
    {

        if (!Session::has('duplicatedCustomer')) {
            $this->forgetSesstion();
            return redirect()->back();
        }

        $id = Session::get('duplicatedCustomer');

        return view('testWebsite.Pages.myOrdersPage', compact('id'));
    }

    public function forgetSesstion()
    {

        Session::forget('duplicatedCustomer');
        Session::forget('requestID');
        Session::forget('mobileCheckNumber');
        Session::forget('otpcode');
        Session::forget('isVerified');
        Session::forget('oldCustomer');
        Session::forget('helpDesk');
        Session::forget('customerID');
    }

    public function postCheckOrderStatus(Request $request)
    {

        $this->forgetSesstion();

        $rules = [
            // 'order_number' => 'required|exists:requests,id',
            'order_number' => 'required|exists:request_searchings,id',
        ];

        $customMessages = [
            'order_number.required' => MyHelpers::guest_trans('The filed is required'),
            // 'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        // $order = DB::table('requests')->where('id', $request->order_number)->first();
        $order = DB::table('requests')->where('searching_id', $request->order_number)->first();
        $pending_order = PendingRequest::where('searching_id', $request->order_number)->first();
        if ($order) {
            $status = $order->noteWebsite ? $order->noteWebsite : 'طلبك قيد المراجعة ... يرجى الانتظار '; //Your order status is Under Progress
            $customer = DB::table('customers')->where('id', $order->customer_id)->first();
            return response()->json([
                'status'   => 1,
                'msg'      => $status,
                'customer' => $customer,
            ]);
        }
        else {
            if ($pending_order) {
                $status = 'طلبك قيد المراجعة ... يرجى الانتظار'; //Your order status is Under Progress
                return response()->json([
                    'status' => 11,
                    'msg'    => $status,
                ]);
            }
            else {
                $status = 'رقم الطلب غير صالح'; //Your order status is Under Progress
                return response()->json([
                    'status' => 0,
                    'msg'    => $status,
                ]);
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public function postConsultancyRequest(Request $request)
    {

        $this->forgetSesstion();

        $rules = [];

        $rules = [
            'name'       => 'required',
            'email'      => 'email|unique:customers|nullable',
            'birth_date' => 'date|nullable',
            'mobile'     => ['required', 'numeric', 'unique:customers', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'salary_id'  => 'numeric|nullable',
        ];

        if ($this->getOptionValue('askforconsultant_salary') == 'show' && ($this->getValidationValue('request_validation_from_salary') != null && $this->getValidationValue('request_validation_from_salary') != '') || ($this->getValidationValue('request_validation_to_salary') != null && $this->getValidationValue('request_validation_to_salary') != '')) {
            $rules['salary'] = 'required|numeric|nullable';
        }
        else {
            $rules['salary'] = 'nullable|numeric';
        }

        if ($this->getOptionValue('askforconsultant_work') == 'show' && $this->getValidationValue('request_validation_to_work') != null && $this->getValidationValue('request_validation_to_work') != '') {
            $rules['work'] = 'required';
        }
        else {
            $rules['work'] = 'nullable';
        }

        if ($this->getOptionValue('askforconsultant_isSupported') == 'show' && $this->getValidationValue('request_validation_to_support') != null && $this->getValidationValue('request_validation_to_support') != '') {
            $rules['is_supported'] = 'required';
        }
        else {
            $rules['is_supported'] = 'nullable';
        }

        if ($this->getOptionValue('askforconsultant_has_obligations') == 'show' && $this->getValidationValue('request_validation_to_has_obligations') != null && $this->getValidationValue('request_validation_to_has_obligations') != '') {
            $rules['has_obligations'] = 'required';
        }
        else {
            $rules['has_obligations'] = 'nullable';
        }

        if ($this->getOptionValue('askforconsultant_has_financial_distress') == 'show' && $this->getValidationValue('request_validation_to_has_financial_distress') != null && $this->getValidationValue('request_validation_to_has_financial_distress') != '') {
            $rules['has_financial_distress'] = 'required';
        }
        else {
            $rules['has_financial_distress'] = 'nullable';
        }

        if ($this->getOptionValue('askforconsultant_owning_property') == 'show' && $this->getValidationValue('request_validation_to_owningProperty') != null && $this->getValidationValue('request_validation_to_owningProperty') != '') {
            $rules['owning_property'] = 'required';
        }
        else {
            $rules['owning_property'] = 'nullable';
        }

        $customMessages = [
            'salary.required'                 => MyHelpers::guest_trans('Salary filed is required'),
            'work.required'                   => MyHelpers::guest_trans('Work filed is required'),
            'is_supported.required'           => MyHelpers::guest_trans('Support filed is required'),
            'has_obligations.required'        => MyHelpers::guest_trans('Obligation filed is required'),
            'has_financial_distress.required' => MyHelpers::guest_trans('Distress filed is required'),
            'owning_property.required'        => MyHelpers::guest_trans('The filed is required'),
            'name.required'                   => MyHelpers::guest_trans('Name filed is required'),
            'mobile.required'                 => MyHelpers::guest_trans('Mobile filed is required'),
            'mobile.unique'                   => MyHelpers::guest_trans('This customer already existed'),
            'mobile.regex'                    => MyHelpers::guest_trans('Should start with 5'),
            'mobile.numeric'                  => MyHelpers::guest_trans('Should start with 5'),
            'email.email'                     => MyHelpers::guest_trans('Email is invalid'),
            'email.unique'                    => MyHelpers::guest_trans('Email Already Existed'),
            // 'sex.required' => MyHelpers::guest_trans(auth()->user()->id, 'The filed is required'),
        ];

        if ($request->salary != null) {
            $request->merge([
                'salary' => str_replace(',', '', $request->salary),
            ]);
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        // Check validation failure
        if ($validator->fails()) {
            $failedRules = $validator->failed();

            if (isset($failedRules['mobile']['Unique'])) {
                $checkpending = true;
                $status = 3; //for unpending request
                $customer = customer::where('mobile', $request->mobile)->first();
                $request = DB::table('requests')->where('customer_id', $customer->id)->first();
                if (empty($request)) {
                    $checkpending = false;
                    $status = 2; //for pending request only
                    $request = DB::table('pending_requests')->where('customer_id', $customer->id)->first();
                }

                //Notify GM
                $gms = MyHelpers::getAllActiveGM();

                #send notifiy to admin
                foreach ($gms as $gm) {
                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::guest_trans('The customer tried to submit a new request'),
                                                         'recived_id' => $gm->id,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 5,
                                                         'req_id'     => $request->id,
                    ]);

                    MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                    //$pwaPush = MyHelpers::pushPWA($gm->id, ' يومك سعيد  ' . $gm->name, 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني', 'فتح الطلب', 'admin', 'fundingreqpage', $request->id);
                }
                //Notify GM

                /*
                //Notify Admin
                    $admins=MyHelpers::getAllActiveAdmin();

                    #send notifiy to admin
                    foreach( $admins as $admin){
                    DB::table('notifications')->insert([ // add notification to send user
                        'value' => MyHelpers::guest_trans('The customer tried to submit a new request'), 'recived_id' =>  $admin->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 5,
                        'req_id' =>  $request->id,
                    ]);

                    $emailNotify=MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $admin->id,' عميل مكرر ','العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                    if ($checkpending)
                    //$pwaPush=MyHelpers::pushPWA($admin->id, ' يومك سعيد  '.$admin->name, 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني', 'فتح الطلب','admin','fundingreqpage',$request->id);

                }
                //Notify Admin
                */

                //Session::put('duplicatedCustomer', $request->searching_id);
                Session::put('duplicatedCustomer', $request->searching_id);

                return response()->json([
                    'status'   => $status,
                    'request'  => $request,
                    'customer' => $customer,
                ], 201);
            }
            else {
                $validator->validate();
            }
        }

        // Check validation success
        if ($validator->passes()) {

            $request = MyHelpers::checkPostRequest($request);

            $is_approved = MyHelpers::check_is_request_acheive_condition($request);
            if ($is_approved) {
                $user_id = $this->getNextAgentForRequest();
            }
            else {
                $user_id = $this->getNextAgentForPending();
            }

            $pass = Str::random(8);
            $customerMobile = $request->mobile;
            $customer = DB::table('customers')->insertGetId([
                'name'                   => $request->name,
                'username'               => $is_approved ? 'customer_'.rand(10000000, 99999999) : null,
                'password'               => $is_approved ? Hash::make($pass) : null,
                'pass_text'              => $is_approved ? $pass : null,
                'birth_date'             => $request->birth_date,
                'birth_date_higri'       => $request->birth_hijri,
                'mobile'                 => $request->mobile,
                'email'                  => $request->email,
                'work'                   => $request->work,
                'salary'                 => $request->salary,
                'salary_id'              => $request->salary_id,
                'is_supported'           => $request->is_supported,
                'has_joint'              => $request->has_joint,
                'has_obligations'        => $request->has_obligations,
                'has_financial_distress' => $request->has_financial_distress,
                'user_id'                => $user_id,
                'welcome_message'        => 2,
                'region_ip'              => $this->getRegion(),
                'created_at'             => (Carbon::now('Asia/Riyadh')),
            ]);
            $reqDate = Carbon::today('Asia/Riyadh')->format('Y-m-d');

            if ($customer) {
                //insertGetId : insertGetId method to insert a record and then retrieve the ID
                // //add it once use insertGetId
                $joinID = DB::table('joints')->insertGetId([
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                ]);
                if ($request->owning_property == null) {
                    $owning_property = 'no';
                }
                else {
                    $owning_property = $request->owning_property;
                }

                $has_property = $request->property_id;

                if ($has_property != null) {
                    $property = Property::find($request->property_id);
                    // طلب عقار
                    $realID = DB::table('real_estats')->insertGetId([
                        'created_at'      => (Carbon::now('Asia/Riyadh')),
                        'owning_property' => $owning_property,
                        'city'            => $property->city_id,
                        'region'          => $property->area_id,
                        'cost'            => $property->fixed_price,
                        'type'            => $property->type_id,
                    ]);
                }
                else {
                    // طلب تمويل
                    $realID = DB::table('real_estats')->insertGetId([
                        'created_at'      => (Carbon::now('Asia/Riyadh')),
                        'owning_property' => $owning_property,
                    ]);
                }

                $funID = DB::table('fundings')->insertGetId([
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                ]);

                /////////// check if data accept by condiion
                // $is_approved = MyHelpers::check_is_request_acheive_condition($request);

                $searching_id = RequestSearching::create()->id;
                if ($is_approved) {
                    $request = DB::table('requests')->insertGetId([
                        'statusReq'    => 0,
                        'customer_id'  => $customer,
                        'user_id'      => $user_id,
                        'source'       => $request->source,
                        'req_date'     => $reqDate,
                        'created_at'   => (Carbon::now('Asia/Riyadh')),
                        'agent_date'   => (Carbon::now('Asia/Riyadh')),
                        'joint_id'     => $joinID,
                        'real_id'      => $realID,
                        'searching_id' => $searching_id,
                        'fun_id'       => $funID,

                    ]);
                    if ($has_property != null) {
                        $getcityValue = DB::table('cities')->where('id', $property->city_id)->first();
                        if (!empty($getcityValue)) {
                            $this->propertRecords('realCity', $getcityValue->value, $request);
                        }

                        $gettypeValue = DB::table('real_types')->where('id', $property->type_id)->first();
                        if (!empty($gettypeValue)) {
                            $this->propertRecords('realType', $gettypeValue->value, $request);
                        }

                        $this->propertRecords('realCost', $property->fixed_price, $request);
                    }

                    //$agenInfo = DB::table('users')->where('id', $user_id)->first();
                    MyHelpers::addNewNotify($request, $user_id); // to add notification
                    MyHelpers::addNewReordWebsite($request, $user_id); // to add new history record
                    MyHelpers::sendEmailNotifiaction('new_req', $user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك'); //email notification
                    //$pwaPush = MyHelpers::pushPWA($user_id, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);
                }
                else {
                    $request_prnding = PendingRequest::create([
                        'statusReq'    => 0,
                        'customer_id'  => $customer,
                        'user_id'      => $user_id,
                        'source'       => $request->source,
                        'req_date'     => $reqDate,
                        'created_at'   => (Carbon::now('Asia/Riyadh')),
                        'joint_id'     => $joinID,
                        'real_id'      => $realID,
                        'searching_id' => $searching_id,
                        'fun_id'       => $funID,

                    ]);
                    // $request = $request_prnding->id;
                }
                setLastAgentOfDistribution($user_id, !$is_approved);

                Session::put('requestID', $searching_id);
                Session::put('mobileCheckNumber', $customerMobile);
                // $emailNotify = MyHelpers::sendEmailNotifiactionCustomer($customer, ' عزيزي العميل ، تم إنشاء طلبك بنجاح وسيقوم فريق التمويل بالتواصل معك قريبا  ', ' تم إنشاء طلبك - شركة الوساطة العقارية');

                if ($request) {
                    return response()->json([
                        'data'   => [
                            'id'       => $searching_id,
                            'customer' => $customerMobile,
                        ],
                        'status' => 1,
                        'msg'    => 'تم بنجاح',
                    ], 201);
                }
            }
            else {
                return response()->json([
                    'errors' => 'try again',
                ], 422);
            }
        }
    }

    public static function getOptionValue($option_name)
    {
        $setting = DB::table('settings')->where('option_name', $option_name)->get();
        return $setting[0]->option_value;
    }

    public static function getValidationValue($option_name)
    {
        $setting = DB::table('request_condition_settings')->where($option_name, '!=', null)->first();

        return $setting;
    }

    public function getNextAgentForRequest()
    {
        return getLastAgentOfDistribution();

        // To get user_id for last request
        $last_req_id = DB::table('requests')->max('id'); // latest request_id
        $last_req = DB::table('requests')->where('id', $last_req_id)->first(); // latest request object
        $last_user_id = $last_req ? $last_req->user_id : null;

        $maxValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
        $minValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

        if ($last_user_id == null) {
            $last_user_id = 61;
        } //Ahmed Qassem

        if ($last_user_id == $maxValue) {
            $user_id = $minValue;
        }
        else {
            // get next user id
            $user_id = User::where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
            if ($user_id == null) {
                $user_id = User::where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
            }
        }

        return $user_id;
    }

    public function getNextAgentForPending()
    {
        return getLastAgentOfDistribution(!0);
        // To get user_id for last request
        $last_req_id = DB::table('pending_requests')->max('id'); // latest request_id
        if ($last_req_id != null) {
            $last_req = DB::table('pending_requests')->where('id', $last_req_id)->first(); // latest request object
            $last_user_id = $last_req->user_id;
            $maxValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
            $minValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

            if ($last_user_id == null) {
                $last_user_id = 61;
            } //Ahmed Qassem

            if ($last_user_id == $maxValue) {
                $user_id = $minValue;
            }
            else {
                // get next user id
                $user_id = User::where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                if ($user_id == null) {
                    $user_id = User::where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                }
            }
        }
        else {
            $last_req_id = DB::table('requests')->max('id'); // latest request_id
            $last_req = DB::table('requests')->where('id', $last_req_id)->get(); // latest request object
            $last_user_id = $last_req[0]->user_id;
            $maxValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
            $minValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

            if ($last_user_id == null) {
                $last_user_id = 61;
            } //Ahmed Qassem

            if ($last_user_id == $maxValue) {
                $user_id = $minValue;
            }
            else {
                // get next user id
                $user_id = User::where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                if ($user_id == null) {
                    $user_id = User::where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
                }
            }
        }

        return $user_id;
    }

    public function getRegion()
    {

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else {
                if (isset($_SERVER['HTTP_X_FORWARDED'])) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                }
                else {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    }
                    else {
                        if (isset($_SERVER['HTTP_FORWARDED'])) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        }
                        else {
                            if (isset($_SERVER['REMOTE_ADDR'])) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            }
                            else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }

        $location = Location::get($ipaddress);

        if ($location) {
            return $location->cityName;
        }
        return null;
    }

    public function propertRecords($coloum, $value, $request)
    {
        DB::table('req_records')->insert([
            'colum'          => $coloum,
            'user_id'        => null,
            'value'          => $value,
            'updateValue_at' => Carbon::now('Asia/Riyadh'),
            'req_id'         => $request,
            'user_switch_id' => null,
            'comment'        => 'العميل',
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function postFundingRequest(Request $request)
    {
        //dd(123);
        $this->forgetSesstion();
        if ($request->salary != null) {
            $request->merge([
                'salary' => str_replace(',', '', $request->salary),
                'mobile' => MyHelpers::EnglishDigits($request->mobile),
            ]);
        }

        $rules = [
            'name'       => 'required',
            'email'      => 'email|unique:customers|nullable',
            'birth_date' => 'date|nullable',
            'salary_id'  => 'numeric|nullable',
        ];

        if ($this->STATIC_PHONE == $request->mobile) {
            $rules['mobile'] = ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
        }
        else {
            $rules['mobile'] = ['required', 'numeric', 'unique:customers', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
        }

        if ($this->getOptionValue('askforfunding_salary') == 'show' && ($this->getValidationValue('request_validation_from_salary') != null && $this->getValidationValue('request_validation_from_salary') != '') || ($this->getValidationValue('request_validation_to_salary') != null && $this->getValidationValue('request_validation_to_salary') != '')) {
            $rules['salary'] = 'required|min:4';
        }
        else {
            $rules['salary'] = 'nullable|min:4';
        }

        if ($this->getOptionValue('askforfunding_birthDate') == 'show' && ($this->getValidationValue('request_validation_from_birth_hijri') != null && $this->getValidationValue('request_validation_from_birth_hijri') != '') || ($this->getValidationValue('request_validation_to_birth_hijri') != null && $this->getValidationValue('request_validation_to_birth_hijri') != '')) {
            $rules['birth_hijri'] = 'required';
        }
        else {
            $rules['birth_hijri'] = 'nullable';
        }

        if ($this->getOptionValue('askforfunding_work') == 'show' && $this->getValidationValue('request_validation_to_work') != null && $this->getValidationValue('request_validation_to_work') != '') {
            $rules['work'] = 'required';
        }
        else {
            $rules['work'] = 'nullable';
        }

        if ($this->getOptionValue('askforfunding_isSupported') == 'show' && $this->getValidationValue('request_validation_to_support') != null && $this->getValidationValue('request_validation_to_support') != '') {
            $rules['is_supported'] = 'required';
        }
        else {
            $rules['is_supported'] = 'nullable';
        }

        if ($this->getOptionValue('askforfunding_has_obligations') == 'show' && $this->getValidationValue('request_validation_to_has_obligations') != null && $this->getValidationValue('request_validation_to_has_obligations') != '') {
            $rules['has_obligations'] = 'required';
        }
        else {
            $rules['has_obligations'] = 'nullable';
        }

        if ($this->getOptionValue('askforfunding_has_financial_distress') == 'show' && $this->getValidationValue('request_validation_to_has_financial_distress') != null && $this->getValidationValue('request_validation_to_has_financial_distress') != '') {
            $rules['has_financial_distress'] = 'required';
        }
        else {
            $rules['has_financial_distress'] = 'nullable';
        }

        if ($this->getOptionValue('askforfunding_owning_property') == 'show' && $this->getValidationValue('request_validation_to_owningProperty') != null && $this->getValidationValue('request_validation_to_owningProperty') != '') {
            $rules['owning_property'] = 'required';
        }
        else {
            $rules['owning_property'] = 'nullable';
        }

        $customMessages = [
            'salary.required'                 => MyHelpers::guest_trans('Salary filed is required'),
            'salary.min'                      => MyHelpers::guest_trans('Salary filed should contain at least 4 digits'),
            'work.required'                   => MyHelpers::guest_trans('Work filed is required'),
            'is_supported.required'           => MyHelpers::guest_trans('Support filed is required'),
            'has_obligations.required'        => MyHelpers::guest_trans('Obligation filed is required'),
            'has_financial_distress.required' => MyHelpers::guest_trans('Distress filed is required'),
            'owning_property.required'        => MyHelpers::guest_trans('The filed is required'),
            'name.required'                   => MyHelpers::guest_trans('Name filed is required'),
            'mobile.required'                 => MyHelpers::guest_trans('Mobile filed is required'),
            'mobile.unique'                   => MyHelpers::guest_trans('This customer already existed'),
            'mobile.regex'                    => MyHelpers::guest_trans('Should start with 5'),
            'mobile.numeric'                  => MyHelpers::guest_trans('Should start with 5'),
            'email.email'                     => MyHelpers::guest_trans('Email is invalid'),
            'email.unique'                    => MyHelpers::guest_trans('Email Already Existed'),
            'birth_hijri.required'            => MyHelpers::guest_trans('Hijri date filed is required'),
            // 'sex.required' => MyHelpers::guest_trans(auth()->user()->id, 'The filed is required'),
        ];

        $inputs = $request->all();
        $inputs['mobile'] = $inputs['mobile'] ?? null;
        if ($request->mobile != null) {
            $request->mobile = substr($request->mobile, -9);
            $inputs['mobile'] = $request->mobile;
        }

        $validator = Validator::make($inputs, $rules, $customMessages);
        //$validator = Validator::make($request->all(), $rules, $customMessages);
        $validator2 = Validator::make($inputs, [
            'mobile' => [/*'unique:customers_phones',*/ 'required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
        ], [
            'mobile.unique' => MyHelpers::guest_trans('This customer already existed'),
        ]);

        $isDuplicateCustomer = false;
        $mobileNumber = $inputs['mobile'];
        if ($mobileNumber) {
            // I ve added that because some numbers , sys allows to be duplicated, so i do double-check.
            $isDuplicateCustomer = customer::where('mobile', $mobileNumber)->exists();
            /*if (!empty($checkDuplicated)) {
                $isDuplicateCustomer = true;
            }*/
            //dd($isDuplicateCustomer);
            if (!$isDuplicateCustomer) {
                $checkDuplicatedMobiles = CustomersPhone::where('mobile', $mobileNumber)->first();
                if (!empty($checkDuplicatedMobiles)) {
                    $checkDuplicateCustomer = DB::table('customers')->where('id', $checkDuplicatedMobiles->customer_id)->first();
                    if (!empty($checkDuplicateCustomer)) {
                        $isDuplicateCustomer = true;
                    }
                }
            }
        }

        //dd($isDuplicateCustomer);
        // Check validation failure
        if ($validator->fails() || $validator2->fails() || $isDuplicateCustomer) {
            $failedRules = $validator->failed();
            $failedRules2 = $validator2->failed();

            if ($isDuplicateCustomer || isset($failedRules['mobile']['Unique']) || isset($failedRules2['mobile']['Unique'])) {
                //for request
                $status = 2;

                if (!($customer = customer::where('mobile', $mobileNumber)->first())) {
                    ($mobiles = CustomersPhone::where('mobile', $mobileNumber)->first()) && (
                    $customer = DB::table('customers')->where('id', $mobiles->customer_id)->first());
                    //$customer = customer::find($mobiles->customer_id)->first();
                }
                if (!$customer) {
                    return response()->json(['message' => "حدث خطأ، الرجاء المحاولة لاحقاً"], 422);
                }

                if (!($requestModel = \App\Models\Request::where('customer_id', $customer->id)->first())) {
                    $status = 2;
                    $requestModel = DB::table('pending_requests')->where('customer_id', $customer->id)->first();
                }

                if (!$requestModel) {
                    return response()->json(['message' => "حدث خطأ، الرجاء المحاولة لاحقاً"], 422);
                }
                if ($status == 3) {

                    // Todo: Move From Freeze
                    if (method_exists($requestModel, 'createJob')) {
                        if ((bool) $requestModel->is_freeze) {
                            $requestModel->createJob(RequestJob::BACK_FROM_FROZEN_BY_REGISTER_AUTO, ['source_back' => RequestJob::SOURCE_WEB]);
                            return response()->json([
                                'data'   => [
                                    'id'       => $requestModel->searching_id,
                                    'customer' => $mobileNumber,
                                ],
                                'status' => 'freeze',
                                'msg'    => 'تم بنجاح',
                            ]);
                        }
                        else {
                            $requestModel->createJob(RequestJob::CHECK_FROM_BACK_OF_UNABLE_TO_COMMUNICATE, ['source_back' => RequestJob::SOURCE_WEB]);
                        }
                    }

                    //to check if there's no update in the request with period of time (with duplicate customer)
                    #ADD TO NEED ACTION BASKET IF THE AGENT IS ARCHIVED AND CLASS NOT EQUAL TO REJECTED = 16.
                    $agentIsArchived = DB::table('users')->where('id', $requestModel->user_id)->where('status', 0)->exists();
                    //dd($agentIsArchived,$mobileNumber,$status);
                    if ($agentIsArchived) {
                        $checkDuplicatedOfNeedActionReq = MyHelpers::checkDublicateOfNeedActionReq('عميل مكرر لإستشاري مؤرشف', $requestModel->user_id, $requestModel->id);
                        if ($checkDuplicatedOfNeedActionReq) {
                            MyHelpers::addNeedActionReqWithoutConditions('عميل مكرر لإستشاري مؤرشف', $requestModel->user_id, $requestModel->id);
                        }
                    }

                    // Notify GM
                    if ($requestModel->class_id_agent != 16 && $requestModel->class_id_agent != 13) {
                        //we will not notify the REJECTED & CUSTOMER NOT WANT TO COMPLETE classifications
                        if (MyHelpers::resubmitCustomerReqTime($requestModel->agent_date)) {
                            // If The Difference Between Days is Greater Than Specified
                            $gms = MyHelpers::getAllActiveGM();
                            #send notify to admin
                            foreach ($gms as $gm) {
                                $value = MyHelpers::guest_trans('The customer tried to submit a new request');
                                if (MyHelpers::checkDublicateNotification($gm->id, $value, $requestModel->id)) {
                                    DB::table('notifications')->insert([
                                        'value'      => $value,
                                        'recived_id' => $gm->id,
                                        'created_at' => (Carbon::now('Asia/Riyadh')),
                                        'type'       => 5,
                                        'req_id'     => $requestModel->id,
                                    ]);
                                    MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                                }
                            }
                        }
                        else {
                            // If The Difference Between Days is Less Than Specified
                            $value = MyHelpers::guest_trans('Your customer tried to submit a new request');
                            $user = DB::table('users')->where('id', $requestModel->user_id)->first();
                            if (MyHelpers::checkDublicateNotification($user->id, $value, $requestModel->id)) {
                                DB::table('notifications')->insert([
                                    'value'      => $value,
                                    'recived_id' => $user->id,
                                    'created_at' => (Carbon::now('Asia/Riyadh')),
                                    'type'       => 5,
                                    'req_id'     => $requestModel->id,
                                ]);
                                MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $user->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                            }
                        }
                    }
                }

                Session::put('duplicatedCustomer', $request->searching_id);
                // return response()->json([
                //     'status'   => $status,
                //     'request'  => $requestModel,
                //     'customer' => $customer,
                // ], 201);
            }
            else {
                $validator->validate();
            }
            $validator2->validate();
        }

        MyHelpers::checkPostRequest($request);
        //dd($request);
        $isApproved = MyHelpers::check_is_request_acheive_condition($request);
        //Ahmed agent
        $user_id = $this->STATIC_PHONE == $mobileNumber ? 61 : ($isApproved ? $this->getNextAgentForRequest() : $this->getNextAgentForPending());
        /*if ($this->STATIC_PHONE == $mobileNumber) {
            $user_id = 61;
        }
        else {
            if ($isApproved) {
                $user_id = $this->getNextAgentForRequest();
            }
            else {
                $user_id = $this->getNextAgentForPending();
            }
        }*/

        $pass = Str::random(8);
        $customerMobile = $mobileNumber;

        // Khaled
        $isNewCustomer=CustomerPhone::where('mobile',$customerMobile)->first();
        $cusomer_id = $isNewCustomer->customer_id ??  null;
        if(!$isNewCustomer){
            $isNewCustomer=customer::where('mobile',$customerMobile)->first();
            $cusomer_id = $isNewCustomer->id ?? null;
        }


       if(empty($isNewCustomer) && !$isDuplicateCustomer){
            $customer = customer::create([
                'name'                   => $request->name,
                'username'               => $isApproved ? 'customer_'.rand(10000000, 99999999) : null,
                'password'               => $isApproved ? Hash::make($pass) : null,
                'birth_date'             => $request->birth_date,
                'birth_date_higri'       => $request->birth_hijri,
                'mobile'                 => $mobileNumber,
                'email'                  => $request->email,
                'work'                   => $request->work,
                'salary'                 => $request->salary,
                'salary_id'              => $request->salary_id,
                'is_supported'           => $request->is_supported,
                'has_joint'              => $request->has_joint,
                'has_obligations'        => $request->has_obligations,
                'has_financial_distress' => $request->has_financial_distress,
                'region_ip'              => $this->getRegion(),
                'created_at'             => (Carbon::now('Asia/Riyadh')),
            ]);
            $reqDate = now('Asia/Riyadh')->format('Y-m-d');
            if ($customer) {
                //insertGetId : insertGetId method to insert a record and then retrieve the ID
                //add it once use insertGetId
                $joinID = DB::table('joints')->insertGetId(['created_at' => now('Asia/Riyadh')]);

                //if ($request->owning_property == null) {
                //    $owning_property = 'no';
                //}
                //else {
                //    $owning_property = $request->owning_property;
                //}
                $owning_property = $request->get('owning_property', 'no');
                $realID = DB::table('real_estats')->insertGetId([
                    'created_at'      => (Carbon::now('Asia/Riyadh')),
                    'owning_property' => $owning_property,
                ]);

                $funID = DB::table('fundings')->insertGetId([
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                ]);
                // check if data accept by condition
                $searching_id = RequestSearching::create()->id;
                if ($isApproved) {
                    $requestModel = DB::table('requests')->insertGetId([
                        'statusReq'    => 0,
                        'customer_id'  => $customer->id,
                        'user_id'      => $user_id,
                        'source'       => $request->source,
                        'req_date'     => $reqDate,
                        'created_at'   => (Carbon::now('Asia/Riyadh')),
                        'agent_date'   => (Carbon::now('Asia/Riyadh')),
                        'joint_id'     => $joinID,
                        'real_id'      => $realID,
                        'searching_id' => $searching_id,
                        'fun_id'       => $funID,
                    ]);
                    $ipAddress = $this->getIpAddress();
                    // Insert In OTP_Request Table
                    Helper::insertOtpRequest($request->mobile, $ipAddress);
                    //***********UPDATE DAILY PREFERENCE */
                    $agent_id = $user_id;
                    if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                        MyHelpers::addDailyPerformanceRecord($agent_id);
                    }
                    MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$requestModel);
                    //MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$requestModel);
                    //***********END - UPDATE DAILY PREFERENCE */

                    //$userModel = DB::table('users')->where('id', $user_id)->first();
                    $userModel = User::find($user_id);
                    MyHelpers::addNewNotify($requestModel, $user_id); // to add notification
                    MyHelpers::addNewReordWebsite($requestModel, $user_id); // to add new history record
                    MyHelpers::sendEmailNotifiaction('new_req', $user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك');
                    if ($userModel) {
                        $this->fcm_send($userModel->getPushTokens(), "طلب جديد", "طلب جديد تم إضافته لسلتك");
                    }
                    if ($request->has("col_id")){

                        CollaboratorRequest::create([
                            "user_id"    => $request->col_id,
                            "type"    => 'request',
                            "req_id"    => $requestModel,
                        ]);

                        $user = User::find($request->col_id)->increment("req_count");
                    }
                    //$pwaPush = MyHelpers::pushPWA($user_id, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $requestModel);
                }
                else {
                    $requestModel = PendingRequest::create([
                        'statusReq'    => 0,
                        'customer_id'  => $customer->id,
                        'user_id'      => $user_id,
                        'source'       => $request->source,
                        'req_date'     => $reqDate,
                        'created_at'   => (Carbon::now('Asia/Riyadh')),
                        'joint_id'     => $joinID,
                        'real_id'      => $realID,
                        'searching_id' => $searching_id,
                        'fun_id'       => $funID,

                    ]);
                    if ($request->has("col_id")){

                        CollaboratorRequest::create([
                            "user_id"    => $request->col_id,
                            "type"    => 'request',
                            "pen_id"    => $requestModel->id,
                        ]);

                        $user = User::find($request->col_id)->increment("pen_count");
                    }
                    //$request = $requestModel->id;
                }
                setLastAgentOfDistribution($user_id, !$isApproved);
                Session::put('requestID', $searching_id);
                Session::put('mobileCheckNumber', $customerMobile);
                //$emailNotify = MyHelpers::sendEmailNotifiactionCustomer($customer, ' عزيزي العميل ، تم إنشاء طلبك بنجاح وسيقوم فريق التمويل بالتواصل معك قريبا  ', ' تم إنشاء طلبك - شركة الوساطة العقارية');


                if ($requestModel) {
                    $status = $isApproved ? 1 : 11;
                    return response()->json([
                        'data'   => [
                            'id'       => $searching_id,
                            'customer' => $customerMobile,
                        ],
                        'status' => $status,
                        'msg'    => 'تم بنجاح',
                    ], 201);
                    /*
                    if ($isApproved) {
                        return response()->json([
                            'data'   => [
                                'id'       => $searching_id,
                                'customer' => $customerMobile,
                            ],
                            'status' => 1,
                            'msg'    => 'تم بنجاح',
                        ], 201);
                    }
                    else {
                        return response()->json([
                            'data'   => [
                                'id'       => $searching_id,
                                'customer' => $customerMobile,
                            ],
                            'status' => 11,
                            'msg'    => 'تم بنجاح',
                        ], 201);
                    }
                    */
                }
            }
            return response()->json([
                'errors' => 'try again',
            ], 422);

       }else{
           $oldRequest=ModelsRequest::where('customer_id',$cusomer_id)->first();
           $oldCustomer=ModelsCustomer::where('id',$cusomer_id)->first();
           $oldRequest->update([
            //    'phoneNumbers' => $oldRequest->phoneNumbers != null ? $oldRequest->phoneNumbers.',' . $customerMobile : $oldCustomer->mobile . ',' . $customerMobile
               'phoneNumbers' => $oldRequest->phoneNumbers != null ? $oldRequest->phoneNumbers.',' . $customerMobile : $oldCustomer->mobile . ',' . $customerMobile
           ]);
           RequestHistory::create([
                'title'          => 'محاولة تسجيل طلب جديد برقم موجود',
                'user_id'        => null,
                'recive_id'      => null,
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'content'        => 'هذا العميل حاول تسجيل طلب اخر باستخدام رقم الجوال 05xxxx',
                'req_id'         => $oldRequest->id,
                'user_switch_id' => null,
            ]);
           Session::put('duplicatedCustomer', $oldRequest->searching_id);
            return response()->json([
                'status'   => $status,
                'request'  => $oldRequest,
                'customer' => $oldCustomer,
            ], 201);

       }
    }

    public function getIpAddress()
    {

        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else {
                if (isset($_SERVER['HTTP_X_FORWARDED'])) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                }
                else {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    }
                    else {
                        if (isset($_SERVER['HTTP_FORWARDED'])) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        }
                        else {
                            if (isset($_SERVER['REMOTE_ADDR'])) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            }
                            else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }

        return $ipaddress;
    }

    public function postSaveLoanRequest(Request $request)
    {

        $this->forgetSesstion();

        if ($request->salary != null) {
            $request->merge([
                'salary' => str_replace(',', '', $request->salary),
            ]);
        }

        $rules = [];

        $rules = [
            'name'       => 'required',
            'email'      => 'email|unique:customers|nullable',
            'birth_date' => 'date|nullable',
            'salary_id'  => 'numeric|nullable',
        ];

        if ($this->STATIC_PHONE == $request->mobile) {
            $rules['mobile'] = ['required', 'numeric', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
        }
        else {
            $rules['mobile'] = ['required', 'numeric', 'unique:customers', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
        }

        if ($this->getOptionValue('realEstateCalculator_salary') == 'show' && ($this->getValidationValue('request_validation_from_salary') != null && $this->getValidationValue('request_validation_from_salary') != '') || ($this->getValidationValue('request_validation_to_salary') != null && $this->getValidationValue('request_validation_to_salary') != '')) {
            $rules['salary'] = 'required|min:4';
        }
        else {
            $rules['salary'] = 'nullable|min:4';
        }

        if ($this->getOptionValue('realEstateCalculator_work') == 'show' && $this->getValidationValue('request_validation_to_work') != null && $this->getValidationValue('request_validation_to_work') != '') {
            $rules['work'] = 'required';
        }
        else {
            $rules['work'] = 'nullable';
        }

        if ($this->getOptionValue('realEstateCalculator_isSupported') == 'show' && $this->getValidationValue('request_validation_to_support') != null && $this->getValidationValue('request_validation_to_support') != '') {
            $rules['is_supported'] = 'required';
        }
        else {
            $rules['is_supported'] = 'nullable';
        }

        if ($this->getOptionValue('realEstateCalculator_has_obligations') == 'show' && $this->getValidationValue('request_validation_to_has_obligations') != null && $this->getValidationValue('request_validation_to_has_obligations') != '') {
            $rules['has_obligations'] = 'required';
        }
        else {
            $rules['has_obligations'] = 'nullable';
        }

        if ($this->getOptionValue('realEstateCalculator_has_financial_distress') == 'show' && $this->getValidationValue('request_validation_to_has_financial_distress') != null && $this->getValidationValue('request_validation_to_has_financial_distress') != '') {
            $rules['has_financial_distress'] = 'required';
        }
        else {
            $rules['has_financial_distress'] = 'nullable';
        }

        if ($this->getOptionValue('realEstateCalculator_owning_property') == 'show' && $this->getValidationValue('request_validation_to_owningProperty') != null && $this->getValidationValue('request_validation_to_owningProperty') != '') {
            $rules['owning_property'] = 'required';
        }
        else {
            $rules['owning_property'] = 'nullable';
        }

        $customMessages = [
            'salary.required'                 => MyHelpers::guest_trans('Salary filed is required'),
            'work.required'                   => MyHelpers::guest_trans('Work filed is required'),
            'is_supported.required'           => MyHelpers::guest_trans('Support filed is required'),
            'has_obligations.required'        => MyHelpers::guest_trans('Obligation filed is required'),
            'has_financial_distress.required' => MyHelpers::guest_trans('Distress filed is required'),
            'owning_property.required'        => MyHelpers::guest_trans('The filed is required'),
            'name.required'                   => MyHelpers::guest_trans('Name filed is required'),
            'mobile.required'                 => MyHelpers::guest_trans('Mobile filed is required'),
            'mobile.unique'                   => MyHelpers::guest_trans('This customer already existed'),
            'mobile.regex'                    => MyHelpers::guest_trans('Should start with 5'),
            'mobile.numeric'                  => MyHelpers::guest_trans('Should start with 5'),
            'email.email'                     => MyHelpers::guest_trans('Email is invalid'),
            'email.unique'                    => MyHelpers::guest_trans('Email Already Existed'),
            // 'sex.required' => MyHelpers::guest_trans(auth()->user()->id, 'The filed is required'),
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        // Check validation failure
        if ($validator->fails()) {
            $failedRules = $validator->failed();

            if (isset($failedRules['mobile']['Unique'])) {
                $checkpending = true;
                $status = 3; //for unpending request
                $customer = customer::where('mobile', $request->mobile)->first();
                $request = DB::table('requests')->where('customer_id', $customer->id)->first();
                if (empty($request)) {
                    $checkpending = false;
                    $status = 2; //for pending request only
                    $request = DB::table('pending_requests')->where('customer_id', $customer->id)->first();
                }

                if ($status == 3) { //to check if there's no update in the request with period of time (with dublicate customer)
                    /*
                    $checkReqClass = MyHelpers::checkClassOfRequestWithoutUpdate($request->id);
                    if ($checkReqClass) {
                        $checkReqUpdate = MyHelpers::checkRequestRecord($request->id);
                        if ($checkReqUpdate) {
                            if (MyHelpers::checkQualityReq($request->id)) { // if not recived by quality
                                $checkDublicateOfNeedActionReq = MyHelpers::checkDublicateOfNeedActionReq('طلب بدون تحديث', $request->user_id, $request->id);
                                if ($checkDublicateOfNeedActionReq)
                                    MyHelpers::addNeedActionReq('طلب بدون تحديث', $request->user_id, $request->id);
                            }
                        }
                    }
                    */

                    #ADD TO NEED ACTION BASKET IF THE AGENT IS ARCHIEVED AND CLASS NOT EQUAL TO REQJECTED = 16.
                    $checkIfArchive = DB::table('users')->where('id', $request->user_id)->where('status', 0)->first();
                    if (!empty($checkIfArchive)) {
                        $checkDublicateOfNeedActionReq = MyHelpers::checkDublicateOfNeedActionReq('عميل مكرر لإستشاري مؤرشف', $request->user_id, $request->id);
                        if ($checkDublicateOfNeedActionReq) {
                            MyHelpers::addNeedActionReqWithoutConditions('عميل مكرر لإستشاري مؤرشف', $request->user_id, $request->id);
                        }
                    }
                }

                //Notify GM
                $gms = MyHelpers::getAllActiveGM();

                #send notifiy to admin
                foreach ($gms as $gm) {
                    DB::table('notifications')->insert([ // add notification to send user
                                                         'value'      => MyHelpers::guest_trans('The customer tried to submit a new request'),
                                                         'recived_id' => $gm->id,
                                                         'created_at' => (Carbon::now('Asia/Riyadh')),
                                                         'type'       => 5,
                                                         'req_id'     => $request->id,
                    ]);

                    $emailNotify = MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                }
                //Notify GM

                /*
                //Notify Admin
                    $admins=MyHelpers::getAllActiveAdmin();

                    #send notifiy to admin
                    foreach( $admins as $admin){
                    DB::table('notifications')->insert([ // add notification to send user
                        'value' => MyHelpers::guest_trans('The customer tried to submit a new request'), 'recived_id' =>  $admin->id,
                        'created_at' => (Carbon::now('Asia/Riyadh')), 'type' => 5,
                        'req_id' =>  $request->id,
                    ]);

                    $emailNotify=MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $admin->id,' عميل مكرر ','العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                    if ($checkpending)
                    //$pwaPush=MyHelpers::pushPWA($admin->id, ' يومك سعيد  '.$admin->name, 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني', 'فتح الطلب','admin','fundingreqpage',$request->id);

                }
                //Notify Admin
                */

                Session::put('duplicatedCustomer', $request->searching_id);

                return response()->json([
                    'status'   => $status,
                    'request'  => $request,
                    'customer' => $customer,
                ], 201);
            }
            else {
                $validator->validate();
            }
        }

        $request = MyHelpers::checkPostRequest($request);

        $is_approved = MyHelpers::check_is_request_acheive_condition($request);

        if ($this->STATIC_PHONE == $request->mobile) {
            $user_id = 61;
        } //Ahmed agent

        else {
            if ($is_approved) {
                $user_id = $this->getNextAgentForRequest();
            }
            else {
                $user_id = $this->getNextAgentForPending();
            }
        }

        $pass = str_random(8);
        $customerMobile = $request->mobile;
        $customer = DB::table('customers')->insertGetId([
            'name'                   => $request->name,
            'username'               => $is_approved ? 'customer_'.rand(10000000, 99999999) : null,
            'password'               => $is_approved ? Hash::make($pass) : null,
            'pass_text'              => $is_approved ? $pass : null,
            'birth_date'             => $request->birth_date,
            'birth_date_higri'       => $request->birth_hijri,
            'mobile'                 => $request->mobile,
            'email'                  => $request->email,
            'work'                   => $request->work,
            'salary'                 => $request->salary,
            'salary_id'              => $request->salary_id,
            'is_supported'           => $request->is_supported,
            'has_joint'              => $request->has_joint,
            'has_obligations'        => $request->has_obligations,
            'has_financial_distress' => $request->has_financial_distress,
            'welcome_message'        => 2,
            'user_id'                => $user_id,
            'region_ip'              => $this->getRegion(),
            'created_at'             => (Carbon::now('Asia/Riyadh')),
        ]);

        $reqDate = Carbon::today('Asia/Riyadh')->format('Y-m-d');

        if ($customer) {
            //insertGetId : insertGetId method to insert a record and then retrieve the ID
            //add it once use insertGetId
            $joinID = DB::table('joints')->insertGetId([
                'created_at' => (Carbon::now('Asia/Riyadh')),
            ]);
            if ($request->owning_property == null) {
                $owning_property = 'no';
            }
            else {
                $owning_property = $request->owning_property;
            }
            $realID = DB::table('real_estats')->insertGetId([
                    'created_at'      => (Carbon::now('Asia/Riyadh')),
                    'owning_property' => $owning_property,
                ]

            );

            $funID = DB::table('fundings')->insertGetId([
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                ]

            );
            /////////// check if data accept by condiion
            //  $is_approved = MyHelpers::check_is_request_acheive_condition($request);
            $searching_id = RequestSearching::create()->id;

            if ($is_approved) {
                $request = DB::table('requests')->insertGetId([
                    'statusReq'    => 0,
                    'customer_id'  => $customer,
                    'user_id'      => $user_id,
                    'source'       => $request->source,
                    'req_date'     => $reqDate,
                    'created_at'   => (Carbon::now('Asia/Riyadh')),
                    'agent_date'   => (Carbon::now('Asia/Riyadh')),
                    'joint_id'     => $joinID,
                    'real_id'      => $realID,
                    'searching_id' => $searching_id,
                    'fun_id'       => $funID,

                ]);

                $agenInfo = DB::table('users')->where('id', $user_id)->first();
                $notify = MyHelpers::addNewNotify($request, $user_id); // to add notification
                $record = MyHelpers::addNewReordWebsite($request, $user_id); // to add new history record
                $emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك'); //email notification
                //$pwaPush = MyHelpers::pushPWA($user_id, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);
            }
            else {
                $request_prnding = PendingRequest::create([
                    'statusReq'    => 0,
                    'customer_id'  => $customer,
                    'user_id'      => $user_id,
                    'source'       => $request->source,
                    'req_date'     => $reqDate,
                    'created_at'   => (Carbon::now('Asia/Riyadh')),
                    'joint_id'     => $joinID,
                    'real_id'      => $realID,
                    'searching_id' => $searching_id,
                    'fun_id'       => $funID,

                ]);
                $request = $request_prnding->id;
            }
            setLastAgentOfDistribution($user_id, !$is_approved);
            Session::put('requestID', $searching_id);
            Session::put('mobileCheckNumber', $customerMobile);
            //$emailNotify = MyHelpers::sendEmailNotifiactionCustomer($customer, ' عزيزي العميل ، تم إنشاء طلبك بنجاح وسيقوم فريق التمويل بالتواصل معك قريبا  ', ' تم إنشاء طلبك - شركة الوساطة العقارية');

            if ($request) {

                if ($is_approved) {
                    return response()->json([
                        'data'   => [
                            'id'       => $searching_id,
                            'customer' => $customerMobile,
                        ],
                        'status' => 1,
                        'msg'    => 'تم بنجاح',
                    ], 201);
                }
                else {
                    return response()->json([
                        'data'   => [
                            'id'       => $searching_id,
                            'customer' => $customerMobile,
                        ],
                        'status' => 11,
                        'msg'    => 'تم بنجاح',
                    ], 201);
                }
            }
        }
        else {
            return response()->json([
                'errors' => 'try again',
            ], 422);
        }
    }

    public function get404Page()
    {
        $this->forgetSesstion();
        return view('Frontend.missing');
    }

    public function thankyou()
    {
        if (!Session::has('requestID') && !Session::has('helpDesk')) {
            $this->forgetSesstion();
            return redirect()->back();
        }

        $customer = '';
        $id = '';
        $helpDesk = '';

        if (Session::has('helpDesk')) {
            $helpDesk = Session::get('helpDesk');
        }
        else {
            if (Session::has('requestID')) {

                $id = Session::get('requestID');

                $request = DB::table('requests')->where('searching_id', $id)->first();

                if ($request) {
                    $customer = DB::table('customers')->where('id', $request->customer_id)->first();
                }

                else {
                    $request = DB::table('pending_requests')->where('searching_id', $id)->first();
                    $customer = DB::table('customers')->where('id', $request->customer_id)->first();
                }
            }
        }

        $key = null;
        if (\request()->has('key')) {
            $key = \request('key');
        }

        return view('testWebsite.Pages.thankYouPage', compact('id', 'customer', 'helpDesk', 'key'));
    }

    public function thanksForHelpDesk()
    {
        if (!Session::has('requestID') && !Session::has('helpDesk')) {
            $this->forgetSesstion();
            return redirect()->back();
        }

        $customer = '';
        $id = '';
        $helpDesk = '';

        if (Session::has('helpDesk')) {
            $helpDesk = Session::get('helpDesk');
        }
        else {
            if (Session::has('requestID')) {
                $id = Session::get('requestID');
                $request = DB::table('requests')->where('searching_id', $id)->first();

                if ($request) {
                    $customer = DB::table('customers')->where('id', $request->customer_id)->first();
                }

                else {
                    $request = DB::table('pending_requests')->where('searching_id', $id)->first();
                    $customer = DB::table('customers')->where('id', $request->customer_id)->first();
                }
            }
        }
        return view('testWebsite.Pages.thankYouPage', compact('id', 'customer', 'helpDesk'));
    }

    public function askForFundingPage($url=null)
    {
        $user = User::where(["id" => Str::slug(explode('-',$url)[0]) ,'role' => 6])->first();
        if (!$user && $url){
            return redirect()->route("request_service");
        }
        $this->forgetSesstion();
        $id = '';
        $property = null;
        if (request()->has('source')) {
            \session()->flash('source', 'ويب - عقار');
            $property = request('id');
            \session()->flash('property', request('property'));
        }
        $worke_sources = WorkSource::all();

        return view('testWebsite.Pages.askForFundingPage', compact('user','id', 'property', 'worke_sources'));
    }

    public function askForConsltantPage()
    {
        $this->forgetSesstion();
        $id = '';
        $worke_sources = WorkSource::all();
        return view('testWebsite.Pages.askForConsltantPage', compact('id', 'worke_sources'));
    }

    public function myOrders()
    {
        $this->forgetSesstion();
        $id = '';

        return view('testWebsite.Pages.myOrdersPage', compact('id'));
    }

    public function fundingcalculator()
    {
        $this->forgetSesstion();

        return view('testWebsite.Pages.fundingCalculatorPage');
    }

    public function otpverify(Request $request)
    {
        if (!Session::has('mobileCheckNumber')) {
            $this->forgetSesstion();
            return redirect('/');
        }

        $mobileNumber = Session::get('mobileCheckNumber');
        return response()->json(['mobile' => $mobileNumber, 'status' => 1]);
    }

    public function setNewPasswordPage()
    {
        if (!Session::has('mobileCheckNumber') && !Session::has('isVerified')) {
            $this->forgetSesstion();
            return redirect('/');
        }

        $mobileNumber = Session::get('mobileCheckNumber');
        return view('testWebsite.Pages.setNewPasswordPage', compact('mobileNumber'));
    }

    public function setNewPassword(Request $request)
    {
        $rules = [
            'password' => 'required|confirmed|min:6',
        ];

        $customMessages = [
            'password.required'  => MyHelpers::guest_trans('The filed is required'),
            'password.min:6'     => 'لابد أن تحوي كلمة المرور على الأقل من 6 خانات',
            'password.confirmed' => 'كلمة المرور وتأكيد كلمة المرور لايتطابقان',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $validator->validate();

        $password = $request->password;

        /*
        $tokenData = DB::table('password_resets')
        ->where('token', $request->token)->first();
        if (!$tokenData){
            $this->forgetSesstion();
            return redirect('/');
        }
        */

        $mobileNumber = $request->mobileNumber;
        $customer = customer::where('mobile', $mobileNumber)->first();
        $customer->password = Hash::make($password);
        $customer->login_time = Carbon::now();
        $customer->logout = false;
        $customer->update(); //or $customer->save();

        //login the user immediately they change password successfully
        //Auth::guard('customer')->login($customer);

        //Delete the token
        /*
        DB::table('password_resets')->where('token', $request->token)
        ->delete();
        */
        Session::flash('message', "عزيزي العميل تم اضافة طلبك بنجاح!");
        //Send Email Reset Success Email
        return redirect('/ar/app');
        //return redirect('/customer');
    }

    public function otpverifyPage()
    {
        if (!Session::has('mobileCheckNumber')) {
            $this->forgetSesstion();
            return redirect('/');
        }

        $mobileNumber = Session::get('mobileCheckNumber');

        $ipAddress = $this->getIpAddress();

        #$checkOtpList = $this->getOtpRequestCount($mobileNumber, $ipAddress);
        #$checkOtpIpList = $this->getOtpIpCount($ipAddress);
        $check = false;

        # if ($checkOtpList <= 2 ||  $this->STATIC_PHONE == $mobileNumber)
        #    if ($checkOtpIpList <= 2 ||  $this->STATIC_PHONE == $mobileNumber) {
        #       $check = true;
        return view('testWebsite.Pages.otpPage', compact('mobileNumber'));
        #   }

        /*
        if (!$check) {

            if (!Session::has('oldCustomer')) {
                $customerInfo = DB::table('customers')->where('mobile',   $mobileNumber)->first();

                $reqInfo = DB::table('requests')->where('customer_id', $customerInfo->id)->first();
                if (empty($reqInfo))
                    $reqInfo = DB::table('pending_requests')->where('customer_id', $customerInfo->id)->first();

                //DELETE ALL ::
                DB::table('joints')->where('id', $reqInfo->joint_id)->delete();
                DB::table('real_estats')->where('id', $reqInfo->real_id)->delete();
                DB::table('fundings')->where('id', $reqInfo->fun_id)->delete();
                DB::table('notifications')->where('req_id', $reqInfo->id)->delete();
                DB::table('customers')->where('id', $customerInfo->id)->delete();
                DB::table('requests')->where('id', $reqInfo->id)->delete();
            }


            $this->forgetSesstion();
            return redirect('/')->with('errorExceedOTP', 'لاتستطيع إكمال العملية.. نعتذر على ذلك');
        }
        */
    }

    public function sendSmsOtp(Request $request)
    {
        if ($request->mobileNumber != null) {
            $mobileNumber = $request->mobileNumber;
        }
        else {
            $mobileNumber = Session::get('mobileCheckNumber');
        }

        $ipAddress = $this->getIpAddress();
        $checkOtpList = $this->getOtpRequestCount($mobileNumber, $ipAddress);
        $checkOtpIpList = $this->getOtpIpCount($ipAddress);
        //$check = false;
        //dd($checkOtpIpList, $checkOtpList);
        if ($checkOtpList <= 500 || $this->STATIC_PHONE == $mobileNumber) {
            if ($checkOtpIpList <= 500 || $this->STATIC_PHONE == $mobileNumber) {
                $code = $this->STATIC_PHONE == $mobileNumber ? 1234 : rand(1000, 9999);
                //if ($this->STATIC_PHONE == $mobileNumber) {
                //    $otpsms = 1234;
                //}
                //else {
                //    $otpsms = rand(1000, 9999);
                //}

                Session::put('otpcode', $code);
                if ($this->STATIC_PHONE != $mobileNumber) {
                    //Helper::insertOtpRequest($mobileNumber, $ipAddress);
                    MyHelpers::sendSmsOtp($mobileNumber, $code);
                }
                return response()->json([
                    'status'       => 1,
                    'sendsms'      => '',
                    'otpsms'       => $code,
                    'mobileNumber' => $mobileNumber,
                ]);
            }
        }

        //return response()->json([
        //    'status'       => 1,
        //    'sendsms'      => '',
        //    'otpsms'       => $otpsms,
        //    'mobileNumber' => $mobileNumber,
        //]);
        //if (!$check) {
        $this->forgetSesstion();
        return response('');
        //}
    }

    public function getOtpRequestCount($mobile, $ipAddress)
    {
        if (($customer = \App\Models\Customer::where('mobile', $mobile)->first())) {
            return (int) $customer->otp_resend_count;
        }
        return $this->getOtpIpCount($ipAddress);
        //return DB::table('otp_request')->where('ip', $ipAddress)->where('mobile', $mobile)->get()->count();
    }

    public function getOtpIpCount($ipAddress)
    {
        $mobiles = OtpRequest::where('id', $ipAddress)->pluck('mobile')->toArray();
        return \App\Models\Customer::whereIn('mobile', $mobiles)->count();
        //return DB::table('otp_request')->where('ip', $ipAddress)->get()->count();
    }

    public function modifyMobilePage()
    {
        if (!Session::has('mobileCheckNumber')) {
            $this->forgetSesstion();
            return redirect('/');
        }

        $mobileNumber = Session::get('mobileCheckNumber');

        $ipAddress = $this->getIpAddress();
        $checkOtpList = $this->getOtpRequestCount($mobileNumber, $ipAddress);
        $checkOtpIpList = $this->getOtpIpCount($ipAddress);
        if ($checkOtpList <= 3 || $this->STATIC_PHONE == $mobileNumber) {
            if ($checkOtpIpList <= 3 || $this->STATIC_PHONE == $mobileNumber) {
                //$insertNewRequest = $this->insertOtpRequest($mobileNumber, $ipAddress);
                return view('testWebsite.Pages.modifyMobileNumberPage', compact('mobileNumber'));
            }
        }

        $this->forgetSesstion();
        return redirect('/')->with('errorExceedOTP', 'لاتستطيع إكمال العملية.. نعتذر على ذلك');
    }

    /**
     * @throws ValidationException
     */
    public function modifyMobilePost(Request $request)
    {

        if (!Session::has('customerID')) {
            $customerInfo = DB::table('customers')->where('mobile', $request->oldMobile)->first();
            if (!empty($customerInfo)) {
                Session::put('customerID', $customerInfo->id);
            }
        }

        $rules = [];

        if ($this->STATIC_PHONE == $request->mobile) {
            $rules['mobile'] = ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
        }
        else {
            $rules['mobile'] = ['required', 'numeric', Rule::unique('customers')->ignore(Session::get('customerID')), 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
        }

        $customMessages = [
            'mobile.required' => MyHelpers::guest_trans('Mobile filed is required'),
            'mobile.unique'   => MyHelpers::guest_trans('This customer already existed'),
            'mobile.regex'    => MyHelpers::guest_trans('Should start with 5'),
            'mobile.numeric'  => MyHelpers::guest_trans('Should start with 5'),
            // 'sex.required' => MyHelpers::guest_trans(auth()->user()->id, 'The filed is required'),
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $validator->validate();

        $request->mobile = substr($request->mobile, -9);

        if ($request->oldMobile != $request->mobile) {
            /*
            $reqInfo = DB::table('requests')->where('customer_id', $customerInfo->id)->first();
            $updateMobile = DB::table('customers')->where('id', $customerInfo->id)
                ->update(['mobile' => $request->mobile]);
            $this->records($reqInfo->id, 'mobile', $request->oldMobile);
            $this->records($reqInfo->id, 'mobile', $request->mobile);
            */
        }
        $updateMobile = 1;

        Session::put('mobileCheckNumber', $request->mobile);

        return response()->json([
            'status'    => $updateMobile,
            'newMobile' => $request->mobile,
            'msg'       => '',
        ]);
    }

    public function setSessionMobileNumber(Request $request)
    {
        Session::put('mobileCheckNumber', $request->mobile);
        Session::put('oldCustomer', 'yes');
    }

    /**
     * @throws ValidationException
     */
    public function checkotpCode(Request $request)
    {
        $rules = [
            'otp_number' => ['required', 'regex:/^([0-9]{4})$/'],
        ];
        $customMessages = [
            'otp_number.required' => MyHelpers::guest_trans('The filed is required'),
            'otp_number.regex'    => 'لابد أن يكون المدخل 4 أرقام',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);
        $validator->validate();
        $otpcode = Session::get('otpcode');
        $recivedotp = $request->otp_number;
        if ($otpcode == $recivedotp) {
            $mobileNumber = $request->mobileNumber;
            Session::put('isVerified', 1);

            if (!Session::has('customerID')) {
                $customerInfo = DB::table('customers')->where('mobile', $mobileNumber)->first();
                if (!empty($customerInfo)) {
                    Session::put('customerID', $customerInfo->id);
                }
            }

            $customerID = Session::get('customerID');
            $reqInfo = DB::table('requests')->where('customer_id', $customerID)->first();

            if ($this->STATIC_PHONE != $mobileNumber) {
                $updateMobile = DB::table('customers')->where('id', $customerID)->update(['isVerified' => 1, 'mobile' => $mobileNumber]);
            }
            else {
                $updateMobile = 1;
            }
            //$this->setTokenOfCustomer($request->_token);
            $this->records($reqInfo->id, 'mobile', $mobileNumber);
            $emailNotify = MyHelpers::sendEmailNotifiactionCustomer($customerID, ' عزيزي العميل ، تم إنشاء طلبك بنجاح وسيقوم فريق التمويل بالتواصل معك قريبا  ', ' تم إنشاء طلبك - شركة الوساطة العقارية');
            Session::forget('customerID');

            return response()->json([
                'status' => $updateMobile,
                'msg'    => '',
            ]);
        }
        return response()->json([
            'status' => 0,
            'msg'    => 'المُدخل لايتطابق مع الرمز المُرسل',
        ]);
    }

    public function records($reqID, $coloum, $value)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')->where('req_id', '=', $reqID)->where('colum', '=', $coloum)->max('id'); //to retrive id of last record update of comment

        if ($lastUpdate != null) {
            $rowOfLastUpdate = DB::table('req_records')->where('id', '=', $lastUpdate)->first();
        } //we get here the row of this id
        //

        if ($lastUpdate == null && ($value != null)) {
            DB::table('req_records')->insert([
                'colum'          => $coloum,
                'user_id'        => null,
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => null,
            ]);
        }

        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => null,
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => null,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function aboutUs()
    {
        $this->forgetSesstion();
        return view('testWebsite.Pages.aboutUsPage');
    }

    public function contactUs()
    {
        $this->forgetSesstion();
        return view('testWebsite.Pages.contactUsPage');
    }

    public function homePageOfWebsite()
    {
        $this->forgetSesstion();
        return view('testWebsite.Pages.homePage');
    }

    public function privacyPage()
    {
        $this->forgetSesstion();
        return view('testWebsite.Pages.privacy_policy');
    }

    public function setTokenOfCustomer($token)
    {
        $user = DB::table('password_resets')->insert([
            'email'      => '',
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);
    }

    public function helpDeskPage()
    {
        $this->forgetSesstion();
        return view('testWebsite.Pages.askHelpDeskPage');
    }

    public function postHelpDesk(Request $request)
    {
        $rules = [];

        $rules = [
            'name'        => 'required',
            'email'       => ['email', 'required'],
            'mobile'      => ['required', 'numeric', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'descrebtion' => 'required',
        ];

        $customMessages = [
            'name.required'        => MyHelpers::guest_trans('Name filed is required'),
            'descrebtion.required' => MyHelpers::guest_trans('The filed is required'),
            'mobile.required'      => MyHelpers::guest_trans('Mobile filed is required'),
            'mobile.regex'         => MyHelpers::guest_trans('Should start with 5'),
            'mobile.numeric'       => MyHelpers::guest_trans('Should start with 5'),
            'email.email'          => MyHelpers::guest_trans('Email is invalid'),
            'email.required'       => MyHelpers::guest_trans('The filed is required'),
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        $validator->validate();

        $reqID = null;

        $getCustomer = $this->checkCustomerHasRequest($request->mobile);
        if ($getCustomer) {
            $reqID = $this->getCustomerRequest($getCustomer);
        }

        if ($reqID != 'طلب معلق' && $reqID != null) {
            $helpDeskRequest = helpDesk::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'mobile'      => $request->mobile,
                'descrebtion' => $request->descrebtion,
                'customer_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null,
            ]);

            //Notify Admin
            $admins = MyHelpers::getAllActiveAdmin();

            #send notifiy to admin
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => MyHelpers::guest_trans('You have new help desk request'),
                                                     'recived_id' => $admin->id,
                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                     'type'       => 8,
                                                     'req_id'     => $helpDeskRequest ? $helpDeskRequest->id : null,
                ]);

                $emailNotify = MyHelpers::sendEmailNotifiaction('new_help_desk', $admin->id, 'لديك طلب دعم فني جديد  ', 'طلب دعم فني ');
            }
        }

        //Notify Admin

        Session::put('helpDesk', 'yes');
        return response()->json(['status' => 1]);
    }

    public function checkCustomerHasRequest($mobile)
    {
        $getCustomer = DB::table('customers')->where('mobile', $mobile)->first();
        if ($getCustomer) {
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

    public function update_soical_clicktimes(Request $request)
    {
        $type = $request->button_id;
        $get_count = null;

        if ($type == 'android_button') {
            $get_count = DB::table('settings')->where('option_name', 'count_number_times_android_button')->first();
            DB::table('settings')->where('option_name', 'count_number_times_android_button')->update(['option_value' => intval($get_count->option_value) + 1]);
        }
        else {
            $get_count = DB::table('settings')->where('option_name', 'count_number_times_apple_button')->first();
            DB::table('settings')->where('option_name', 'count_number_times_apple_button')->update(['option_value' => intval($get_count->option_value) + 1]);
        }

    }
}

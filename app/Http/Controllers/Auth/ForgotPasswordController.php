<?php

namespace App\Http\Controllers\Auth;

use App\customer;
use App\Http\Controllers\Controller;
use App\Mail\SendResetPasswordLinkCustomer;
use App\Model\PendingRequest;
use Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use MyHelpers;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {

        return view('Customer.passwords.email');
    }

    public function showSMSRequestForm()
    {

        return view('Customer.passwords.sms');
    }

    public function showSMSVerifyRequestForm()
    {

        if (session()->has('code') == true) {
            return view('Customer.passwords.sms-verify');
        }
        else {
            return redirect()->back();
        }
    }

    public function sendResetLinkSMS(Request $request)
    {
        $rules = [
            'mobile' => ['required', 'numeric', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
        ];
        $request->validate($rules, [
            'mobile.required' => 'رقم الجوال مطلوب * ',
            'mobile.numeric'  => 'أدخل صيغة صحيحة لرقم الجوال',
            'mobile.regex'    => 'أدخل صيغة صحيحة لرقم الجوال مكونة من 9 أرقام ويبدأ ب5',
        ]);
        $customer = customer::where(['mobile' => $request->mobile])->first();
        // If Not Exists
        if (customer::where(['mobile' => $request->mobile])->count() == 0) {
            Session::flash('status', 'رقم الجوال لايتطابق مع أي من البيانات المسجلة لدينا ');
            return redirect()->back();
        }
        $req = \App\request::where('customer_id', $customer->id)->first();
        // If No Requests
        if ($req->count() == 0) {
            Session::flash('status', 'رقم الجوال لايتطابق مع أي من البيانات المسجلة لدينا');
            return redirect()->back();
        }

        // If Pending
        if (PendingRequest::where('customer_id', $customer->id)->count() != 0) {
            Session::flash('status', 'طلبك قيد المراجعة ، سيتم التواصل معك في أقرب وقت ممكن ');
            return redirect()->back();
        }
        $day = Carbon::now('Asia/Riyadh');
        $today = date("Y-m-d", strtotime($day));

        $phones = DB::table('password_resets')->where([
            'mobile' => $request->mobile,
        ])->where('day', $today)->count();

        $ips = DB::table('password_resets')->where([
            'ip' => $request->ip(),
        ])->where('day', $today)->count();

        $val = DB::table('password_resets')->where([
            'mobile' => $request->mobile,
        ])->where('waiting_at', '>', $day)->orderBy('waiting_at', 'DESC')->first();

        // Has A Valid Code

        if ($val != null) {
            $first = new DateTime($val->waiting_at);
            $second = new DateTime($day);
            $diff = $first->diff($second);
            // If Rejected

            if ($ips == 3 || $phones == 3) {
                Session::flash('status', 'لديك رمز تحقق صالح للإستخدام إذا لم يصلك الرمز الرجاء المحاولة مجددا فى اليوم التالى ');
            }
            else {
                Session::flash('status', 'لديك رمز تحقق صالح للإستخدام إذا لم يصلك الرمز الرجاء المحاولة مجددا بعد :  '.($diff->format(' %i دقيقة ')));
            }
            if ($req->class_id_agent == 16) {
                Session::flash('status', 'لديك رمز تحقق صالح للإستخدام إذا لم يصلك الرمز الرجاء المحاولة من خلال البريد الإلكترونى ');
            }
            return redirect()->route('customer.sms.check');
        }
        // If Rejected
        if ($req->class_id_agent == 16) {
            $val = DB::table('password_resets')->where([
                'mobile' => $request->mobile,
            ])->count();
            if ($val != 0) {
                Session::flash('status', 'لقد استنذفت محاولتك الرجاء إستخدام البريد الإلكترونى  ');
                return redirect()->back();
            }
        }
        $input['batch'] = 1;
        if (($ips == 3 || $phones == 3) && $val == null) {
            if (session()->has('code')) {
                Session::forget('code');
            }
            Session::flash('status', 'لقد استنفدت عدد المحاولات اليوم حاول فى اليوم التالى ..');
            return redirect()->back();
        }
        if ($ips == 2 || $phones == 2) {
            $input['batch'] = 0;
        }
        $otpsms = rand(1000, 9999);

        $user = DB::table('password_resets')->insert([
            'mobile'     => $request->mobile,
            'token'      => Str::random(60),
            'code'       => $otpsms,
            'email'      => $customer->email,
            'created_at' => $day,
            'ip'         => $request->ip(),
            'waiting_at' => $day->addHours(1),
            'day'        => $today,
            'batch'      => $input['batch'],
        ]);
        if (session()->has('code')) {
            Session::forget('code');
        }
        Session::put('mobileNumber', $request->mobile);
        Session::put('code', $otpsms);
        //----------------------------------------------------------
        $send = MyHelpers::sendSmsOtp($request->mobile, $otpsms);
        //----------------------------------------------------------
        return redirect(route('customer.sms.verify'));
    }

    public function CheckSMS(Request $request)
    {

        $rules = [
            /*            'mobile' => ['required', 'numeric', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/','exists:password_resets,mobile'],*/
            'code' => ['required', 'size:4'],
        ];

        $request->validate($rules, [
            /* 'mobile.required'    => 'الجوال مطلوب *' ,
             'mobile.exists'    => 'رقم الجوال غير موجود' ,*/
            'code.exists'   => 'رمز التحقق غير صالح',
            'code.size'     => 'رمز التحقق مكون من 4 خانات ',
            'code.required' => 'رمز التحقق مطلوب',
        ]);

        $day = Carbon::now('Asia/Riyadh');
        $password = DB::table('password_resets')
            ->where('code', $request->code);
        if ($password->count() == 0) {
            Session::flash('status', 'رمز التحقق لايتطابق مع المُرسل إليك ..');
            return redirect()->back();
        }
        elseif ($password->where('waiting_at', '>', $day)->count() == 0) {
            Session::flash('status', 'لقد انتهت صلاحية الكود المرسل ..');
            return redirect()->back();
        }
        if (Session::has('mobileNumber')) {
            $data = $password->where('mobile', Session::get('mobileNumber'))->get()->first();
        }
        else {
            $data = $password->get()->first();
        }

        //----------------------------------------------------------
        return redirect(route('customer.password.reset', $data->token)."?mobile=".$data->mobile);

    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:customers',
        ], [
            'email.exists' => 'البريد الإلكتروني لايتطابق مع البيانات المسجلة لدينا',
        ]);

        DB::table('password_resets')->where(['email' => $request->email, 'mobile' => null])->delete();
        $user = DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => Str::random(60),
            'created_at' => Carbon::now(),
        ]);
        $user = DB::table('password_resets')->where('email', $request->email)->first();
        $link = config('app.website_url').'/customer/password/reset/'.$user->token.'?email='.urlencode($user->email);
        Mail::to($request->email)->send(new SendResetPasswordLinkCustomer($link, $user));
        Session::flash('status', 'تم إرسال إيميل إعادة تعيين كلمة المرور إلى صندوق بريدك ');
        return redirect()->back();
        // send link in email [admin]
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
    }

    public function broker()
    {
        return Password::broker('customers');
    }

    public function guard()
    {
        return Auth::guard('customer');
    }
}

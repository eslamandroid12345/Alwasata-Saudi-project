<?php

namespace App\Http\Controllers\Auth;

use App\customer;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = '/';

    public function __construct()
    {

    }

    public function showResetForm(Request $request, $token = null)
    {
        $customer = DB::table('password_resets')->where([
            'token' => $token,
        ])->get()->first();
        $emailId = customer::where('email', $customer->email)->first()->id;
        return view('Customer.passwords.reset', [
            'title'               => 'Reset Tutor Password',
            'passwordUpdateRoute' => 'Customer.password.update',
            'token'               => $token,
            'customer'            => $customer,
            'customerId'          => $emailId,
        ]);
    }

    public function reset(Request $request)
    {
        if ($request->has('mobile')) {
            $rules = [
                'email'    => 'nullable|email|unique:customers,email,'.$request->customer_id,
                'mobile'   => ['required', 'numeric', 'regex:/^(5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', 'exists:password_resets,mobile'],
                'password' => 'required|confirmed|min:6',// |regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/
                'token'    => 'required',
            ];
            $messages = [
                'email.unique'       => 'البريد الإلكترونى مستخدم من قبل ',
                'email.email'        => 'البريد الإلكترونى غير صحيح ',
                'password.min'       => 'الرقم السري لابد ان يكون اكبر من 6 حروف',
                'password.confirmed' => 'الرقم السرى / تأكيد الرقم السري غير متطابقان',
                'password.required'  => 'الرقم السري مطلوب',
            ];
        }
        else {
            $rules = [
                'email'    => 'required|email|exists:customers,email',
                'password' => 'required|confirmed|min:6',// |regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/
                'token'    => 'required',
            ];
            $messages = [
                'email.required'     => 'الرقم السري مطلوب',
                'password.min'       => 'الرقم السري لابد ان يكون اكبر من 6 حروف',
                'password.confirmed' => 'الرقم السرى / تأكيد الرقم السري غير متطابقان',
                'password.required'  => 'الرقم السري مطلوب',
            ];
        }
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();// Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) {
            return view('customer.passwords.email');
        }
        //Validate input
        $request->validate($rules, $messages);
        if ($request->has('mobile')) {
            $user = customer::where('mobile', $tokenData->mobile)->first();
            if ($request->has('email')) {
                $user->email = $request->email;
            }
        }
        else {
            $user = customer::where('email', $tokenData->email)->first();
        }

        $password = $request->password;
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email not found']);
        }//Hash and update the new password
        $user->password = Hash::make($password);
        $user->login_time = Carbon::now();

        $user->logout = false;
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::guard('customer')->login($user);

        //Delete the token
        DB::table('password_resets')->where(['email' => $user->email, 'mobile' => null])
            ->delete();
        Session::flash('status', 'تم تغيير كلمة المرور بنجاح ');
        //Send Email Reset Success Email
        return redirect('/customer');
    }

    protected function broker()
    {
        return Password::broker('customers');
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }
}

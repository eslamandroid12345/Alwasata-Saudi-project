<?php

namespace App\Repositories\Customer;

use App\customer as Customer;
use App\Http\Requests\Customer\GuestCustomerRequest;
use App\Interfaces\Customer\GuestCustomerInterface;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//use DB;
class GuestCustomerRepository implements GuestCustomerInterface
{
    // use ResponseAPI Trait in this repository
    use ResponseAPI;

    public function requestCustomerLogin(GuestCustomerRequest $request)
    {
        try {
            if (auth()->guard('customer')->attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
                config(['auth.guards.api.provider' => 'customer']);
                $customer = Customer::find(auth()->guard('customer')->user()->id);
                if ($customer && $customer->isVerified != 0) {
                    $updateLogout = Customer::where('id', $customer->id)->update([
                        'logout'     => false,
                        'login_time' => Carbon::now('Asia/Riyadh'),
                    ]);
                    $getCustomerData = Customer::leftJoin('requests', 'requests.customer_id', '=', 'customers.id')->join('users', 'users.id', '=', 'requests.user_id')->where('customers.id', $customer->id)->select('customers.id as customer_id', 'requests.noteWebsite',
                        'customers.name as customer_name', 'customers.email as customer_email', 'customers.mobile as customer_mobile', 'users.name as agent_name', 'requests.user_id as agent_id')->first();
                    $success = $getCustomerData;
                    $success['token'] = $customer->createToken('MyApp', ['customer'])->accessToken;
                    return $this->success(" ", $success);
                }
                else {
                    return $this->error("حسابك غير مفعل للآن!");
                }
            }
            else {
                return $this->error("معلومات الحساب غير صحيحة");
            }
        }
        catch (\Exception $e) {
            //            return $this->error($e->getMessage(),$e->getCode());
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!");
        }
    }

    public function customerLogout()
    {
        try {
            $customer = auth()->guard('customer-api')->id();
            $revoke = DB::table('oauth_access_tokens')->where('user_id', $customer)->where('scopes', '=', '["customer"]')->update(['revoked' => true]);
            $updateLogoutCustomer = Customer::where('id', $customer)->update(['logout' => true]);
            //                $updateCustomerActivity = CustomerActivity::where('customer_id',$customer)
            //                    ->update(['last_activity' => 1494030000]);
            if ($revoke && $updateLogoutCustomer) {
                return $this->success("تم تسجيل الخروج بنجاح!", null);
            }
            return $this->error("حدث خطأ, الرجاء المحاولة مرة أخري");
        }
        catch (\Exception $e) {
            return $this->error("حدث خطأ ما , من فضلك حاول بوقت لاحق!");
        }
    }
}

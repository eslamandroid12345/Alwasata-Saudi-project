<?php

namespace App\Http\Controllers;

use App\Area;
use App\CollaboratorProfile;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'], //attaches HomeComposer to pages
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }

    public function profile()
    {
        // this code to retrive chat view
        $user = User::find(auth()->id());
        $areas = Area::all();

        return view('Profile.profile', compact('user','areas'));

    }

    //update user auth profile depending on auth id
    public function updateProfile(Request $request)
    {
        $auth = Auth::user();  // get  auth info
        $id = $auth->id;
        $name = $request->name;
        $email = $request->email;
        $lang = $request->locale;



        if ($request->has('password') && !empty($request->input('password'))) {
            // validate request fields
            $rules = [
                'name'     => 'required',
                'email'    => 'required|email|max:255|unique:users,email,'.$id, // unique email
                'password' => 'min:6',
                'locale'   => 'required',
            ];

            $customMessages = [
                'name.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'locale.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.unique'    => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                'email.email'     => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                'password.min'    => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
            ];

            $this->validate($request, $rules, $customMessages);

            $pass = Hash::make($request->password);
        }
        else {

            $rules = [
                'name'   => 'required',
                'email'  => 'required|email|max:255|unique:users,email,'.$id, // unique email
                'locale' => 'required',
            ];

            $customMessages = [
                'name.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'locale.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.unique'    => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                'email.email'     => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
            ];

            $this->validate($request, $rules, $customMessages);

            $pass = $auth->password;
        }

        $update = DB::table('users')->where('id', $id)
            ->update([
                'name'     => $name,
                'email'    => $email,
                'password' => $pass,
                'locale'   => $lang,
            ]);


        CollaboratorProfile::whereNotIn("value",$request->area_id ?? [])
            ->where(["user_id" => $id,"key" =>"area_id"])->delete();

        CollaboratorProfile::whereNotIn("value",$request->city_id ?? [])
            ->where(["user_id" => $id,"key" =>"city_id"])->delete();

        CollaboratorProfile::whereNotIn("value",$request->district_id ?? [])
            ->where(["user_id" => $id,"key" =>"district_id"])->delete();


        CollaboratorProfile::updateOrCreate([
            "key"   => $request->direction,
            "user_id"   => $id
        ],[
            "key"   => 'direction',
            "value" =>  $request->direction,
            "user_id"   => $id
        ]);

        foreach ($request->area_id ?? [] as $key=> $item) {
            CollaboratorProfile::updateOrCreate([
                "key"   => "area_id",
                "user_id"   => $id,
                "value" => $item,
            ]);
        }

        foreach ($request->city_id ?? [] as $key=> $item) {
            CollaboratorProfile::updateOrCreate([
                "key"   => "city_id",
                "user_id"   => $id,
                "value" => $item,
            ]);
        }

        foreach ($request->district_id ?? [] as $key=> $item) {
            CollaboratorProfile::updateOrCreate([
                "key"   => "district_id",
                "user_id"   => $id,
                "value" => $item,
            ]);
        }
        return redirect()->back()->with('success', MyHelpers::admin_trans(auth()->user()->id, 'Profile updated'));


    }

    public function customerProfile()
    {
        // this code to retrive customer profile view
        $user = auth('customer')->user();
        $cities = City::all();
        return view('Profile.Customer.index', compact('user', 'cities'));
    }

    //update user auth profile depending on auth id
    public function customerUpdateProfile(Request $request)
    {

        $auth = auth('customer')->user();  // get  auth info
        $id = $auth->id;
        $name = $request->name;
        $email = $request->email;
        // $city = $request->city ;
        //  $region = $request->region ;

        if ($request->has('password') && !empty($request->input('password'))) {
            // validate request fields
            $rules = [
                'name'     => 'required',
                //'city'=> 'required',
                //'region'=> 'required',
                'email'    => 'required|email|max:255|unique:customers,email,'.$id, // unique email
                'password' => 'min:6',
            ];

            $customMessages = [
                'name.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'city.required' => MyHelpers::admin_trans(auth()->user()->id,'The filed is required'),
                // 'region.required' => MyHelpers::admin_trans(auth()->user()->id,'The filed is required'),
                'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                'email.email'    => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                'password.min'   => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
            ];

            $this->validate($request, $rules, $customMessages);

            $pass = Hash::make($request->password);
            $passTxt = $request->password;
        }
        else {

            $rules = [
                'name'  => 'required',
                // 'city'=> 'required',
                // 'region'=> 'required',
                'email' => 'required|email|max:255|unique:customers,email,'.$id, // unique email
            ];

            $customMessages = [
                'name.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                // 'city.required' => MyHelpers::admin_trans(auth()->user()->id,'The filed is required'),
                // 'region.required' => MyHelpers::admin_trans(auth()->user()->id,'The filed is required'),
                'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                'email.email'    => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
            ];

            $this->validate($request, $rules, $customMessages);

            $pass = $auth->password;
            $passTxt = $auth->pass_text;
        }

        $update = DB::table('customers')->where('id', $id)
            ->update([
                'name'      => $name,
                'email'     => $email,
                'password'  => $pass,
                'pass_text' => $passTxt,
            ]);
        /*
        $real = DB::table('real_estats')->where('customer_id', $id)
            ->update([
                'city' => $city, 'region' => $region,
            ]);
        */

        //  if (! empty($update) || !empty($real))
        if ($update) {
            return redirect()->back()->with('success', MyHelpers::admin_trans(auth()->user()->id, 'Profile updated'));
        }
        else {
            return redirect()->back()->with('error', MyHelpers::admin_trans(auth()->user()->id, 'Error on update profile'));
        }

    }
}


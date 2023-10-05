<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\SplitController;

use App\CollaboratorProfile;
use App\customer;
use App\CustomerMessageHistory;
use App\Models\Bank;
use App\Models\RequestHistory;
use App\Models\QualityRequestNeedTurned;
use App\Models\User;
use App\task;
use App\task_content;
use App\userActivity;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use MyHelpers;

trait SplitAdminThreeControllerTrait
{
    public function editUser(Request $request)
    {
        $user = DB::table('users')->where('id', $request->id)->first();
        $auth = Auth::user();  // get  auth info
        $id = $auth->id;
        $name = $request->name;
        $username = $request->username ?? \Str::slug($request->username);
        $email = $request->email;
        $mobile = $request->mobile;
        $lang = $request->get('locale');
        $callCenterName = $request->callCenterName;


        if ($request->role == 'sa') {
            $role = 0;
        }
        else {
            $role = $request->role;
        }

        if ($email == $user->email) {
            $check = false;
        }
        else {
            $check = true;
        }

        if ($username == $user->username) {
            $check1 = false;
        }
        else {
            $check1 = true;
        }

        if ($request->role == 0 && $request->isTsaheel == 0) {

            if ($check && $check1) {
                $rules = [
                    'username'     => 'required|unique:users,username,'.$id,   // unique username',
                    // 'name' => 'required',
                    'email'        => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                    //  'password' => 'min:6',
                    'locale'       => 'required',
                    'role'         => 'required',
                    'salesmanager' => 'required',

                ];
            }
            else {
                if ($check && !$check1) {
                    $rules = [
                        'username'     => 'required',
                        'email'        => 'nullable|email|max:255|unique:users,email,'.$id,
                        'locale'       => 'required',
                        'role'         => 'required',
                        'salesmanager' => 'required',

                    ];
                }
                else {
                    if (!$check && $check1) {
                        $rules = [
                            'username'     => 'required|unique:users,username,'.$id,
                            'email'        => 'nullable|email|max:255',
                            'locale'       => 'required',
                            'role'         => 'required',
                            'salesmanager' => 'required',

                        ];
                    }
                    else {
                        $rules = [
                            'username'     => 'required',      // unique username',
                            //  'name' => 'required',
                            'email'        => 'nullable|email|max:255', // unique email
                            //  'password' => 'min:6',
                            'locale'       => 'required',
                            'role'         => 'required',
                            'salesmanager' => 'required',

                        ];
                    }
                }
            }

            $customMessages = [
                'username.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'username.unique'       => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                //  'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'password.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'locale.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'role.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'salesmanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                'email.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                'email.email'           => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                'password.min'          => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
            ];
        }
        else {
            if ($request->role == 0 && $request->isTsaheel == 1) {

                if ($check && $check1) {
                    $rules = [
                        'username'        => 'required|unique:users,username,'.$id,   // unique username',
                        // 'name' => 'required',
                        'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                        //  'password' => 'min:6',
                        'locale'          => 'required',
                        'role'            => 'required',
                        'mortgagemanager' => 'required',

                    ];
                }
                else {
                    if ($check && !$check1) {
                        $rules = [
                            'username'        => 'required',
                            // 'name' => 'required',
                            'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                            //  'password' => 'min:6',
                            'locale'          => 'required',
                            'role'            => 'required',
                            'mortgagemanager' => 'required',

                        ];
                    }
                    else {
                        if (!$check && $check1) {
                            $rules = [
                                'username'        => 'required|unique:users,username,'.$id, // unique username',
                                // 'name' => 'required',
                                'email'           => 'nullable|email|max:255',
                                //  'password' => 'min:6',
                                'locale'          => 'required',
                                'role'            => 'required',
                                'mortgagemanager' => 'required',

                            ];
                        }
                        else {
                            $rules = [
                                'username'        => 'required',      // unique username',
                                //  'name' => 'required',
                                'email'           => 'nullable|email|max:255', // unique email
                                //  'password' => 'min:6',
                                'locale'          => 'required',
                                'role'            => 'required',
                                'mortgagemanager' => 'required',

                            ];
                        }
                    }
                }

                $customMessages = [
                    'username.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'username.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                    //  'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'password.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'locale.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'role.required'            => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'mortgagemanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                    'email.unique'             => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                    'email.email'              => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                    'password.min'             => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                ];
            }
            else {
                if ($request->role == 6) {

                    if ($check && $check1) {
                        $rules = [
                            'username' => 'required|unique:users,username,'.$id,   // unique username',
                            //  'name' => 'required',
                            'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                            //  'password' => 'min:6',
                            'locale'   => 'required',
                            'role'     => 'required',

                            'salesagents' => 'required|array|min:1',
                            'active'     => 'required_if:check,0',
                        ];
                    }
                    else {
                        if ($check && !$check1) {
                            $rules = [
                                'username' => 'required',
                                //  'name' => 'required',
                                'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                //  'password' => 'min:6',
                                'locale'   => 'required',
                                'role'     => 'required',

                                'salesagents' => 'required|array|min:1',
                                'active'     => 'required_if:check,0',
                            ];
                        }
                        else {
                            if (!$check && $check1) {
                                $rules = [
                                    'username' => 'required|unique:users,username,'.$id, // unique username',
                                    //  'name' => 'required',
                                    'email'    => 'nullable|email|max:255',                       // unique email
                                    //  'password' => 'min:6',
                                    'locale'   => 'required',
                                    'role'     => 'required',

                                    'salesagents' => 'required|array|min:1',
                                    'active'     => 'required_if:check,0',
                                ];
                            }
                            else {
                                $rules = [
                                    'username' => 'required',      // unique username',
                                    //  'name' => 'required',
                                    'email'    => 'nullable|email|max:255', // unique email
                                    //  'password' => 'min:6',
                                    'locale'   => 'required',
                                    'role'     => 'required',

                                    'salesagents' => 'required|array|min:1',
                                    'active'     => 'required_if:check,0',
                                ];
                            }
                        }
                    }
                    $data = '';
                    $error = '';

                    if ($request->has("salesagents")){

                        foreach (\App\User::whereIn("id",$request->salesagents)->get() as $item) {
                            if ($item->allow_recived == 0){
                                $data.=$item->name." , ";
                            }
                        }
                        if ($data != ''){
                            $error = "الإستشاريين التاليين غير مسموح لهم بنزول طلبات [ ".$data
                                ." ] هل انت متأكد من الإضافة ";
                        }
                        if(\App\User::whereIn("id",$request->salesagents)->where("allow_recived",0)->count() == 0){
                            $request->merge([
                                "active"    => 1
                            ]);
                        }
                    }
                    $customMessages = [
                        'username.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'username.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                        //   'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'password.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'locale.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'role.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'active.required_if'      => $error,
                        'salesagents.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                        'email.unique'         => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                        'email.email'          => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                        'password.min'         => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                    ];
                }
                else {
                    if ($request->role == 5) {

                        if ($check && $check1) {
                            $rules = [
                                'username' => 'required|unique:users,username,'.$id,   // unique username',
                                //  'name' => 'required',
                                'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                //  'password' => 'min:6',
                                'locale'   => 'required',
                                'role'     => 'required',
                                // 'quality' => 'required|array|min:1',

                            ];
                        }
                        else {
                            if ($check && !$check1) {
                                $rules = [
                                    'username' => 'required',
                                    //  'name' => 'required',
                                    'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                    //  'password' => 'min:6',
                                    'locale'   => 'required',
                                    'role'     => 'required',
                                    //  'quality' => 'required|array|min:1',
                                ];
                            }
                            else {
                                if (!$check && $check1) {
                                    $rules = [
                                        'username' => 'required|unique:users,username,'.$id, // unique username',
                                        //  'name' => 'required',
                                        'email'    => 'nullable|email|max:255',                       // unique email
                                        //  'password' => 'min:6',
                                        'locale'   => 'required',
                                        'role'     => 'required',
                                        //  'quality' => 'required|array|min:1',

                                    ];
                                }
                                else {
                                    $rules = [
                                        'username' => 'required',      // unique username',
                                        //  'name' => 'required',
                                        'email'    => 'nullable|email|max:255', // unique email
                                        //  'password' => 'min:6',
                                        'locale'   => 'required',
                                        'role'     => 'required',
                                        //   'quality' => 'required|array|min:1',

                                    ];
                                }
                            }
                        }

                        $customMessages = [
                            'username.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'username.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                            //   'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'password.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'locale.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'role.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            // 'quality.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                            'email.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                            'email.email'       => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                            'password.min'      => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                        ];
                    }
                    else {
                        if ($request->role == 2 || $request->role == 3) {

                            if ($check && $check1) {
                                $rules = [
                                    'username'       => 'required|unique:users,username,'.$id,   // unique username',
                                    // 'name' => 'required',
                                    'email'          => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                    //  'password' => 'min:6',
                                    'locale'         => 'required',
                                    'role'           => 'required',
                                    'generalmanager' => 'required',

                                ];
                            }
                            else {
                                if ($check && !$check1) {
                                    $rules = [
                                        'username'       => 'required',
                                        // 'name' => 'required',
                                        'email'          => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                        //  'password' => 'min:6',
                                        'locale'         => 'required',
                                        'role'           => 'required',
                                        'generalmanager' => 'required',
                                    ];
                                }
                                else {
                                    if (!$check && $check1) {
                                        $rules = [
                                            'username'       => 'required|unique:users,username,'.$id, // unique username',
                                            // 'name' => 'required',
                                            'email'          => 'nullable|email|max:255',
                                            //  'password' => 'min:6',
                                            'locale'         => 'required',
                                            'role'           => 'required',
                                            'generalmanager' => 'required',

                                        ];
                                    }
                                    else {
                                        $rules = [
                                            'username'       => 'required',      // unique username',
                                            //  'name' => 'required',
                                            'email'          => 'nullable|email|max:255', // unique email
                                            //  'password' => 'min:6',
                                            'locale'         => 'required',
                                            'role'           => 'required',
                                            'generalmanager' => 'required',

                                        ];
                                    }
                                }
                            }

                            $customMessages = [
                                'username.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'username.unique'         => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                //  'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'password.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'locale.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'role.required'           => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'generalmanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                'email.unique'            => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                'email.email'             => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                'password.min'            => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                            ];
                        }
                        else {
                            if ($request->role == 8) {

                                if ($check && $check1) {
                                    $rules = [
                                        'username'        => 'required|unique:users,username,'.$id,   // unique username',
                                        // 'name' => 'required',
                                        'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                        //  'password' => 'min:6',
                                        'locale'          => 'required',
                                        'role'            => 'required',
                                        'accountant_type' => 'required',

                                    ];
                                }
                                else {
                                    if ($check && !$check1) {
                                        $rules = [
                                            'username'        => 'required',
                                            // 'name' => 'required',
                                            'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                            //  'password' => 'min:6',
                                            'locale'          => 'required',
                                            'role'            => 'required',
                                            'accountant_type' => 'required',
                                        ];
                                    }
                                    else {
                                        if (!$check && $check1) {
                                            $rules = [
                                                'username'        => 'required|unique:users,username,'.$id, // unique username',
                                                // 'name' => 'required',
                                                'email'           => 'nullable|email|max:255',
                                                //  'password' => 'min:6',
                                                'locale'          => 'required',
                                                'role'            => 'required',
                                                'accountant_type' => 'required',

                                            ];
                                        }
                                        else {
                                            $rules = [
                                                'username'        => 'required',      // unique username',
                                                //  'name' => 'required',
                                                'email'           => 'nullable|email|max:255', // unique email
                                                //  'password' => 'min:6',
                                                'locale'          => 'required',
                                                'role'            => 'required',
                                                'accountant_type' => 'required',

                                            ];
                                        }
                                    }
                                }

                                $customMessages = [
                                    'username.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'username.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                    //  'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'password.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    //  'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'locale.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'role.required'            => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'accountant_type.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                    'email.unique'             => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                    'email.email'              => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                    'password.min'             => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                                ];
                            }
                            else {
                                if ($request->role == 1) {

                                    if ($check && $check1) {
                                        $rules = [
                                            'username'        => 'required|unique:users,username,'.$id,   // unique username',
                                            // 'name' => 'required',
                                            'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                            // 'password' => 'min:6',
                                            'locale'          => 'required',
                                            'role'            => 'required',
                                            'fundingmanager'  => 'required',
                                            'mortgagemanager' => 'required',

                                        ];
                                    }
                                    else {
                                        if ($check && !$check1) {
                                            $rules = [
                                                'username'        => 'required',                              // unique username',
                                                // 'name' => 'required',
                                                'email'           => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                                // 'password' => 'min:6',
                                                'locale'          => 'required',
                                                'role'            => 'required',
                                                'fundingmanager'  => 'required',
                                                'mortgagemanager' => 'required',
                                            ];
                                        }
                                        else {
                                            if (!$check && $check1) {
                                                $rules = [
                                                    'username'        => 'required|unique:users,username,'.$id, // unique username',
                                                    // 'name' => 'required',
                                                    'email'           => 'nullable|email|max:255',                       // unique email
                                                    // 'password' => 'min:6',
                                                    'locale'          => 'required',
                                                    'role'            => 'required',
                                                    'fundingmanager'  => 'required',
                                                    'mortgagemanager' => 'required',

                                                ];
                                            }
                                            else {
                                                $rules = [
                                                    'username'        => 'required', // unique username',
                                                    // 'name' => 'required',
                                                    'email'           => 'nullable|email|max:255',
                                                    // 'password' => 'min:6',
                                                    'locale'          => 'required',
                                                    'role'            => 'required',
                                                    'fundingmanager'  => 'required',
                                                    'mortgagemanager' => 'required',

                                                ];
                                            }
                                        }
                                    }

                                    $customMessages = [
                                        'username.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'username.unique'          => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                        //   'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'password.required'        => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        //'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'locale.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'role.required'            => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'fundingmanager.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'mortgagemanager.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'email.unique'             => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                        'email.email'              => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                        'password.min'             => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                                    ];
                                }
                                else {

                                    if ($check && $check1) {
                                        $rules = [
                                            'username' => 'required|unique:users,username,'.$id,   // unique username',
                                            // 'name' => 'required',
                                            'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                            //'password' => 'min:6',
                                            'locale'   => 'required',
                                            'role'     => 'required',
                                        ];
                                    }
                                    else {
                                        if ($check && !$check1) {
                                            $rules = [
                                                'username' => 'required',
                                                // 'name' => 'required',
                                                'email'    => 'nullable|email|max:255|unique:users,email,'.$id, // unique email
                                                //'password' => 'min:6',
                                                'locale'   => 'required',
                                                'role'     => 'required',
                                            ];
                                        }
                                        else {
                                            if (!$check && $check1) {
                                                $rules = [
                                                    'username' => 'required|unique:users,username,'.$id, // unique username',
                                                    // 'name' => 'required',
                                                    'email'    => 'nullable|email|max:255',                       // unique email
                                                    //'password' => 'min:6',
                                                    'locale'   => 'required',
                                                    'role'     => 'required',

                                                ];
                                            }
                                            else {
                                                $rules = [
                                                    'username' => 'required',
                                                    // 'name' => 'required',
                                                    'email'    => 'nullable|email|max:255',
                                                    //'password' => 'min:6',
                                                    'locale'   => 'required',
                                                    'role'     => 'required',
                                                ];
                                            }
                                        }
                                    }

                                    $customMessages = [
                                        'username.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'username.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                                        //  'name.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'password.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        // 'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'locale.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'role.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                                        'email.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                                        'email.email'       => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                                        'password.min'      => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        /**
         *  Bank Delegate rules
         */
        if ($request->get('role') == 13) {
            $rules = [
                'active' => 'required_if:check,0',
                'bank_id' => 'required', Rule::exists(Bank::getModelTable(), 'id'),
                'subdomain' => 'required',
                // 'password' => 'required|min:6',
                'username' => 'required',
                'code' => 'required',
                'salesagents' => 'required|array|min:1'
            ];

            if ($role == 13){
                $salesAgents = $request->input('salesagents_users', []);
                $fundingmanagers = $request->input('fundingmanagers', []);
                $user_collibretor = null;
                if (!empty($salesAgents)) {
                    DB::table('user_collaborators')->where('collaborato_id', $request->id)->delete();
                }

                foreach ($salesAgents as $salesAgent) {
                    DB::table('user_collaborators')->insert([
                        'user_id'        => $salesAgent,
                        'collaborato_id' => $request->id,
                    ]);
                }

                foreach ($fundingmanagers as $fundingmanager) {
                    DB::table('user_collaborators')->insert([
                        'user_id'        => $fundingmanager,
                        'collaborato_id' => $request->id,
                    ]);
                }
            }
        }
        $data = '';
        $error = '';

        if ($request->has("salesagents_users")){

            foreach (\App\User::whereIn("id",$request->salesagents)->get() as $item) {
                if ($item->allow_recived == 0){
                    $data.=$item->name." , ";
                }
            }
            if ($data != ''){
                $error = "الإستشاريين التاليين غير مسموح لهم بنزول طلبات [ ".$data
                    ." ] هل انت متأكد من الإضافة ";
            }
            if(\App\User::whereIn("id",$request->salesagents)->where("allow_recived",0)->count() == 0){
                $request->merge([
                    "active"    => 1
                ]);
            }
        }
        $customMessages = ['active.required_if'      => $error,
                           'bank_id.required' => " البنك مطلوب",
                           'salesagents.required' => "الأستشاريين مطلوبين",
                           'subdomain.required' => "المجال الفرعي مطلوب ",
                           'code.required' => " كود مصدر المعامة مطلوب",
                           'username.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'username.unique'   => MyHelpers::admin_trans(auth()->user()->id, 'Username already existed'),
                           'password.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           // 'email.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'locale.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'role.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
                           'email.unique'      => MyHelpers::admin_trans(auth()->user()->id, 'Email already existed'),
                           'email.email'       => MyHelpers::admin_trans(auth()->user()->id, 'Email not valid'),
                           'password.min'      => MyHelpers::admin_trans(auth()->user()->id, 'Password should be at least 6 letters'),];

        //dd($request->all());
        $this->validate($request, $rules, $customMessages);

        if ($request->password != null && $request->password != '') {
            $password = Hash::make($request->password);

            if ($request->name == null) {
                $name = 'لايوجد اسم';
            }

            $userModel = User::findOrFail($request->get('id'));
            $mergeUser = [
                'bank_id'        => $request->get('bank_id'),
                'subdomain'      => $request->get('subdomain'),
                'code'      => $request->get('code'),
                'name_for_admin' => $request->get('name_for_admin'),
            ];
            if ($role == 2 || $role == 3) {
                $updateUser = DB::table('users')->where('id', $request->id)->update(array_merge([
                    'name'               => $name,
                    'username'           => $username,
                    'email'              => $email,
                    'mobile'             => $mobile,
                    'password'           => $password,
                    'locale'             => $lang,
                    'role'               => $role,
                    'name_for_admin'         => $request->name_for_admin,
                    'manager_id'         => $request->generalmanager,
                    'funding_mnager_id'  => $request->fundingmanager,
                    'mortgage_mnager_id' => $request->mortgagemanager,
                    'accountant_type'    => $request->accountant_type,
                    'updated_at'         => (Carbon::now('Asia/Riyadh')),
                    'name_in_callCenter' => $callCenterName,
                ], $mergeUser));
            }
            else {
                if ($request->role == 20){
                    $mergeUser["subdomain"] = $request->others;
                }

                $updateUser = DB::table('users')->where('id', $request->id)->update(array_merge([
                    'name'               => $name,
                    'username'           => $username,
                    'email'              => $email,
                    'mobile'             => $mobile,
                    'password'           => $password,
                    'locale'             => $lang,
                    'role'               => $role,
                    'name_for_admin'         => $request->name_for_admin,
                    'manager_id'         => $request->salesmanager,
                    'isTsaheel'          => $request->isTsaheel,
                    'funding_mnager_id'  => $request->fundingmanager,
                    'mortgage_mnager_id' => $request->mortgagemanager,
                    'accountant_type'    => $request->accountant_type,
                    'updated_at'         => (Carbon::now('Asia/Riyadh')),
                    'name_in_callCenter' => $callCenterName,
                ], $mergeUser));
            }
        }
        else {

            if ($role == 2 || $role == 3) {
                $updateUser = DB::table('users')->where('id', $request->id)->update([
                    'name'               => $name,
                    'username'           => $username,
                    'email'              => $email,
                    'mobile'             => $mobile,
                    'locale'             => $lang,
                    'role'               => $role,
                    'name_for_admin'         => $request->name_for_admin,
                    'manager_id'         => $request->generalmanager,
                    'name_in_callCenter' => $callCenterName,
                    'funding_mnager_id'  => $request->fundingmanager,
                    'mortgage_mnager_id' => $request->mortgagemanager,
                    'accountant_type'    => $request->accountant_type,
                    'updated_at'         => (Carbon::now('Asia/Riyadh')),
                ]);
            }
            else {
                $mergeUser = [
                    'bank_id'        => $request->get('bank_id'),
                    'subdomain'      => $request->get('subdomain'),
                    'name_for_admin' => $request->get('name_for_admin'),
                ];
                if ($request->role == 20){
                    $mergeUser["subdomain"] = $request->others;
                }


                $updateUser = DB::table('users')
                    ->where('id', $request->id)->update(array_merge([
                    'name'               => $name,
                    'username'           => $username,
                    'email'              => $email,
                    'mobile'             => $mobile,
                    'locale'             => $lang,
                    'role'               => $role,
                    'name_for_admin'         => $request->name_for_admin,
                    'manager_id'         => $request->salesmanager,
                    'isTsaheel'          => $request->isTsaheel,
                    'name_in_callCenter' => $callCenterName,
                    'funding_mnager_id'  => $request->fundingmanager,
                    'mortgage_mnager_id' => $request->mortgagemanager,
                    'accountant_type'    => $request->accountant_type,
                    'updated_at'         => (Carbon::now('Asia/Riyadh')),
                ],$mergeUser));
            }
        }

        $user2 = DB::table('users')->where('id', $request->id)->first();

        if ($role == 5) { // colorbetor needs to sales agents
            $is_follow = $request->is_follow;
            if ($is_follow){
                DB::table('users')
                    ->where('id', $request->id)->update([
                        "subdomain"  => $is_follow != null ? "follow" : null
                    ]);
            }

        }
        if ($role == 6) { // colorbetor needs to sales agents

            $salesAgents = $request->input('salesagents', []);
            $user_collibretor = null;

            if (!empty($salesAgents)) {
                DB::table('user_collaborators')->where('collaborato_id', $request->id)->delete();
            }


            CollaboratorProfile::whereNotIn("value",$request->area_id ?? [])
                ->where(["user_id" => $request->id,"key" =>"area_id"])->delete();

            CollaboratorProfile::whereNotIn("value",$request->city_id ?? [])
                ->where(["user_id" => $request->id,"key" =>"city_id"])->delete();

            CollaboratorProfile::whereNotIn("value",$request->district_id ?? [])
                ->where(["user_id" => $request->id,"key" =>"district_id"])->delete();

            CollaboratorProfile::updateOrCreate([
                "key"   => $request->direction,
                "user_id"   => $request->id
            ],[
                "key"   => 'direction',
                "value" =>  $request->direction,
                "user_id"   => $request->id
            ]);

            foreach ($request->area_id ?? [] as $key=> $item) {
                CollaboratorProfile::updateOrCreate([
                    "key"   => "area_id",
                    "user_id"   => $request->id,
                    "value" => $item,
                ]);
            }

            foreach ($request->city_id ?? [] as $key=> $item) {
                CollaboratorProfile::updateOrCreate([
                    "key"   => "city_id",
                    "user_id"   => $request->id,
                    "value" => $item,
                ]);
            }

            foreach ($request->district_id ?? [] as $key=> $item) {
                CollaboratorProfile::updateOrCreate([
                    "key"   => "district_id",
                    "user_id"   => $request->id,
                    "value" => $item,
                ]);
            }
            foreach ($salesAgents as $salesAgent) {

                $user_collibretor = DB::table('user_collaborators')->insert([
                    'user_id'        => $salesAgent,
                    'collaborato_id' => $request->id,
                ]);
            }
            $admin_col = $request->domain_col;

            DB::table('users')
                ->where('id', $request->id)->update([
                    "subdomain"  => $admin_col != null ? "report" : null
                ]);
            $is_follow = $request->is_agent_show;
            if ($is_follow){
                DB::table('users')
                    ->where('id', $request->id)->update([
                        "code"  => $is_follow != null ? "agent_show" : null
                    ]);
            }
            if ($user_collibretor > 0 || $user_collibretor == null) {
                if ($user_collibretor == 1 && $updateUser == 1) {
                    return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'user' => $user2]);
                }
                else {
                    return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
                }
            }
        }

        /*  if ($role == 5) { // colorbetor needs to quality

            $quality = $request->input('quality', []);

            if (!empty($quality))
                DB::table('agent_qualities')->where('Quality_id', $request->id)->delete();

            foreach ($quality as $salesAgent) {

                $agent_quality = DB::table('agent_qualities')
                    ->insert([
                        'Agent_id' => $salesAgent, 'Quality_id' => $request->id,
                    ]);
            }

            if ($agent_quality != null)
                if ($agent_quality == 1 && $updateUser == 1)
                    return response()->json(['status' => 1, 'message' =>  MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'user' => $user2]);

                else
                    return response()->json(['status' => 0, 'message2' =>  MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
        }
        */


        if ($updateUser == 1) {
            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'user' => $user2]);
        }
        return response()->json(['status' => 0, 'message2' => MyHelpers::admin_trans(auth()->user()->id, 'Nothing Change')]);
    }

    public function updateUserStatus(Request $request)
    {

        $user = DB::table('users')->where('id', $request->id)->first();

        if ($user->allow_recived == 0) {

            $updateResult = DB::table('users')->where('id', '=', $user->id)->update([
                'allow_recived' => 1, //active
            ]);
        }
        else {

            $updateResult = DB::table('users')->where('id', '=', $user->id)->update([
                'allow_recived' => 0, //deactive
            ]);
        }

        $user2 = DB::table('users')->where('id', $request->id)->first();

        if ($updateResult == 0) {
            return response($updateResult);
        }
        return response()->json(['user' => $user2]); // if 1: update succesfally

    }

    public function moveReqNeedActionToAnother(Request $request)
    {
        // Todo: remove this action
        return response()->json(['updatereq' => null, 'message' => 'تم الغاء هذه الميزة']);
        $removeReq = DB::table('request_need_actions')->where('req_id', $request->id)->first();
        if ($removeReq && $removeReq->status != 0) {
            return response()->json(['updatereq' => null, 'message' => 'Status Not new']);
        }
        if (empty($removeReq) || $removeReq->status == 0) {

            $reqInfo = DB::table('requests')->where('id', $request->id)->first();

            #check If there is need action req

            $this->checkIfThereIsNeedActionReq($request->id);

            $prev_user = $reqInfo->user_id;

            $reqID = $reqInfo->id;

            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

            /////////////////////////////////////////////////////////////////
            //MOVE NEW AND READ TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $request->salesAgent,
                    'created_at' => carbon::now(),
                ]);

                $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            //////MOVE REPLAID TASK

            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $request->salesAgent,
                            'user_id'   => $task->user_id,
                        ]);

                        task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }

            ///////////////////////////////////////////////////////

            $customerID = $reqInfo->customer_id;

            //  return response()->json(['req'=>$customerID]);

            $updatereq = DB::table('requests')->where('id', $request->id)->update([
                'user_id'                 => $request->salesAgent,
                'statusReq'               => 0,
                'agent_date'              => carbon::now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);

            if ($reqInfo->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $request->salesAgent, //active
                ]);
            }

            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                 'recived_id' => $request->salesAgent,
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 0,
                                                 'req_id'     => $request->id,
            ]);

            $agenInfo = DB::table('users')->where('id', $request->salesAgent)->first();
            //$pwaPush = MyHelpers::pushPWA($request->salesAgent, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);

            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $request->salesAgent,
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'req_id'         => $request->id,
                'class_id_agent' => $reqInfo->class_id_agent,
                'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
            ]);

            DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                                'recived_id' => $prev_user,
                                                'req_id'     => $request->id,
            ])->delete();

            #move customer's messages to new agent
            MyHelpers::movemessage($customerID, $request->salesAgent, $prev_user);

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$request->id);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $request->salesAgent;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$request->id);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$request->id);
           // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->id);
            //***********END - UPDATE DAILY PREFROMENCE */

            #Remove request from Quality & Need Action Req once moved it
            #1::Remove Req from Quality
            if (MyHelpers::checkQualityReqExistedByReqID($request->id) > 0) {
                $qualityReqDelte = MyHelpers::removeQualityReqByReqID($request->id);
                if ($qualityReqDelte == 0) {
                    MyHelpers::updateQualityReqToCompleteByReqID($request->id);
                }
            }
            #2::Remove from Need Action Req
            MyHelpers::removeNeedActionReqByReqID($request->id);

            if ($updatereq == 0) {
                return response()->json(['updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
            }

            return response()->json(['agentName' => $agenInfo->name, 'updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

        }

        return response()->json(['updatereq' => null, 'message' => 'حدث خطأ']);
    }


    public function rejectNeedToBeTurnedReq(Request $request)
    {
        $removeReq = DB::table('quality_request_need_turneds')->where('id', $request->id)->first();
        if (empty($removeReq) || $removeReq->status != 0) {
            return response()->json(['updatereq' => null, 'message' => 'حدث خطأ']);
        }
        if ($removeReq->status == 0) {

            $reqInfo = DB::table('requests')->where('id',$removeReq->agent_req_id)->first();

            $prev_user = $reqInfo->user_id;


            # UPDATE NEED TO BE TURNED REQUEST STATUS:
            $previous_agent_user = $removeReq->previous_agent_id;
            $quality_user = $removeReq->quality_id;
            $quality_req = $removeReq->quality_req_id;
            DB::table('quality_request_need_turneds')->where('id', $request->id)->update([
                'status' => 2 ,
                'reject_reason' =>$request->reject_reason ,
            ]);
            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Your Need Turned Request is reject'),
                'recived_id' => $quality_user,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 1,
                'req_id'     =>  $quality_req,
            ]);

            # add a new task for current agent , if ther's no task .. otherwise not add anything::
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($quality_req, $previous_agent_user, $quality_user) {

                $query->where(function ($query) use ($quality_req,  $previous_agent_user, $quality_user) {
                    $query->where('tasks.req_id', $quality_req);
                    $query->where('tasks.recive_id',  $previous_agent_user);
                    $query->where('tasks.user_id',  $quality_user);
                });

            })->whereIn('status', [0, 1])->pluck('id')->toArray();


            if (count($getAllTasksIds) == 0) {
                $newTask = task::create([
                    'req_id'    => $quality_req,
                    'recive_id' => $previous_agent_user,
                    'user_id'   => $quality_user,
                ]);

                task_content::create([
                    'content'         => QualityRequestNeedTurned::TASK_OF_FOLLOW_UP,
                    'date_of_content' => Carbon::now('Asia/Riyadh'),
                    'task_id'         => $newTask->id,
                ]);
            }

            return response()->json(['updatereq' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully')]);

        }

        return response()->json(['updatereq' => null,'message' => 'حدث خطأ']);
    }

    public function moveNeedToBeTurnedReq(Request $request)
    {
        $removeReq = DB::table('quality_request_need_turneds')->where('id', $request->id)->first();
        if (empty($removeReq) || $removeReq->status != 0) {
            return response()->json(['updatereq' => null, 'message' => 'حدث خطأ']);
            //return response()->json(['updatereq' => null, 'message' => 'Status Not new']);
        }
        if ($removeReq->status == 0) {

            $reqInfo = DB::table('requests')->where('id',$removeReq->agent_req_id)->first();

            $prev_user = $reqInfo->user_id;

            $reqID = $reqInfo->id;

            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

            /////////////////////////////////////////////////////////////////
            $is_there_task = False;
            //MOVE NEW AND READ TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                $is_there_task = True;
                $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $request->salesAgent,
                    'created_at' => carbon::now(),
                ]);

                $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            //////MOVE REPLAID TASK

            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {
                $is_there_task = True;
                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $request->salesAgent,
                            'user_id'   => $task->user_id,
                        ]);

                        task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }

            if (!$is_there_task){ # add a new task
                $newTask = task::create([
                    'req_id'    => $removeReq->quality_req_id,
                    'recive_id' => $request->salesAgent,
                    'user_id'   => $removeReq->quality_id,
                ]);

                task_content::create([
                    'content'         => QualityRequestNeedTurned::TASK_OF_MOVMENT,
                    'date_of_content' => Carbon::now('Asia/Riyadh'),
                    'task_id'         => $newTask->id,
                ]);
            }

            ///////////////////////////////////////////////////////

            $customerID = $reqInfo->customer_id;

            $updatereq = DB::table('requests')->where('id', $reqID)->update([
                'user_id'                 => $request->salesAgent,
                'statusReq'               => 0,
                'agent_date'              => carbon::now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);

            if ($reqInfo->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $request->salesAgent, //active
                ]);
            }

            DB::table('notifications')->insert([ // add notification to send user
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                'recived_id' => $request->salesAgent,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 0,
                'req_id'     => $reqID ,
            ]);

            $agenInfo = DB::table('users')->where('id', $request->salesAgent)->first();
            //$pwaPush = MyHelpers::pushPWA($request->salesAgent, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);

            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $request->salesAgent,
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'req_id'         => $reqID,
                'class_id_agent' => $reqInfo->class_id_agent,
                'content'        => RequestHistory::NEED_TO_TURNED_QUALITY,
            ]);

            DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                                'recived_id' => $prev_user,
                                                'req_id'     => $reqID,
            ])->delete();

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $request->salesAgent;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$reqID);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_task', $reqID);
           // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->id);
            //***********END - UPDATE DAILY PREFROMENCE */


            # UPDATE NEED TO BE TURNED REQUEST STATUS:
            $quality_user = $removeReq->quality_id;
            $quality_req = $removeReq->quality_req_id;

            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($quality_user)) {
                MyHelpers::addDailyPerformanceRecord($quality_user);
            }
            MyHelpers::incrementDailyPerformanceColumn($quality_user, 'received_task', $reqID);

            DB::table('quality_request_need_turneds')->where('id', $request->id)->update([
                'status' => 1 ,
            ]);
            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Your Need Turned Request is accept'),
                'recived_id' => $quality_user,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 1,
                'req_id'     =>  $quality_req,
            ]);

            return response()->json(['agentName' => $agenInfo->name, 'updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

        }

        return response()->json(['updatereq' => null, 'message' => 'حدث خطأ']);
    }

    public function moveMoveNeedToBeTurnedReqArray(Request $request)
    {
        $autoDistribution = !1;
        $counter = 0;
        $i = 0;
        $salesAgents = [];
        $req_ids = $request->id;

        if ($request->agents_ids == '') {
            $autoDistribution = !0;
        }
        else {
            $salesAgents = array_merge($salesAgents, $request->agents_ids);
        }
        //dd($requestsData);
        foreach ($req_ids as $model) {

            $need_to_be_turned_req = QualityRequestNeedTurned::where('id', $model)->where('status', 0)->first();
            $prev_user = $need_to_be_turned_req->previous_agent_id;
            $prev_user = str_replace(' ', '', $prev_user);
            if ($autoDistribution) {
                $salesAgents[$i] = getLastAgentOfDistribution();
                if ($prev_user == $salesAgents[$i]) {
                    setLastAgentOfDistribution( $prev_user);
                    $salesAgents[$i] = getLastAgentOfDistribution();
                }
            }
            else {
                // to remove same user to duplicate with same request
                if (($key = array_search($prev_user, $salesAgents)) !== false) {
                    unset($salesAgents[$key]);
                    $salesAgents = array_values($salesAgents);
                }
            }
            //check if there's no available agent
            if (!$autoDistribution && count($salesAgents) == 0) {
                return response()->json(['updatereq' => 2, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'No Avaliable Agents')]);
            }


            $agent_req_id = $need_to_be_turned_req->agent_req_id;
            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $agent_req_id)->pluck('id')->toArray();

            //MOVE NEW AND READ TASK
            $is_there_task = False;
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });

            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {
                $is_there_task = True;
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $salesAgents[$i],
                    'created_at' => carbon::now(),
                ]);

                DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            // MOVE replaid task
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {
                $is_there_task = True;
                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $salesAgents[$i],
                            'user_id'   => $task->user_id,
                        ]);

                        task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }



            if (!$is_there_task){ # add a new task
                $newTask = task::create([
                    'req_id'    => $need_to_be_turned_req->quality_req_id,
                    'recive_id' => $salesAgents[$i],
                    'user_id'   => $need_to_be_turned_req->quality_id,
                ]);

                task_content::create([
                    'content'         => QualityRequestNeedTurned::TASK_OF_MOVMENT,
                    'date_of_content' => Carbon::now('Asia/Riyadh'),
                    'task_id'         => $newTask->id,
                ]);
            }

            ///////////////////////////////////////////////////////
            $orginal_request_info =  DB::table('requests')->where('id',$need_to_be_turned_req->agent_req_id)->first();
            $customerID = $orginal_request_info->customer_id;
            $updatereq = DB::table('requests')->where('id', $orginal_request_info->id)->update([
                'user_id'                 => $salesAgents[$i],
                'statusReq'               => 0,
                'agent_date'              => now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
            ]);
            if ($updatereq) {
                $counter++;
                if ($autoDistribution) {
                    setLastAgentOfDistribution($salesAgents[$i]);
                }
            }

            if ($orginal_request_info->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $salesAgents[$i], //active
                ]);
            }

            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                'recived_id' => $salesAgents[$i],
                'created_at' => now('Asia/Riyadh'),
                'type'       => 0,
                'req_id'     => $orginal_request_info->id,
            ]);

            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $salesAgents[$i],
                'history_date'   => now('Asia/Riyadh'),
                'req_id'         => $orginal_request_info->id,
                'class_id_agent' => $orginal_request_info->class_id_agent,
                'content'        => RequestHistory::NEED_TO_TURNED_QUALITY,
            ]);

            //remove previous notificationes that related to previous agent's request
            DB::table('notifications')->where([
                'recived_id' => $prev_user,
                'req_id'     =>  $orginal_request_info->id,
            ])->delete();

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$orginal_request_info->id);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $salesAgents[$i];
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$orginal_request_info->id);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$orginal_request_info->id);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_task', $orginal_request_info->id);
            //***********END - UPDATE DAILY PREFROMENCE */

            # UPDATE NEED TO BE TURNED REQUEST STATUS:
            $quality_user = $need_to_be_turned_req->quality_id;
            $quality_req = $need_to_be_turned_req->quality_req_id;


            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($quality_user)) {
                MyHelpers::addDailyPerformanceRecord($quality_user);
            }
            MyHelpers::incrementDailyPerformanceColumn($quality_user, 'received_task', $orginal_request_info->id);

            DB::table('quality_request_need_turneds')->where('id', $need_to_be_turned_req->id)->update([
                'status' => 1 ,
            ]);
            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'Your Need Turned Request is accept'),
                'recived_id' => $quality_user,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'type'       => 1,
                'req_id'     =>  $quality_req,
            ]);


            $i++;
        }

        return response()->json([
            'counter'   => $counter,
            'updatereq' => 1,
            //'message'   => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully'),
            'message'   => sprintf("تم نقل %d بنجاح",$counter),
        ]);

    }

    public function checkIfThereIsNeedActionReq($id)
    {
        $checkNeedReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($id);
        if ($checkNeedReq != 'false') {
            $updateNeedReq = MyHelpers::updateNeedActionReqStatus($checkNeedReq->id);
        }
    }

    public function moveReqToAnother(Request $request)
    {
        $reqInfo = DB::table('requests')->where('id', $request->id)->first();

        //dd($reqInfo);
        #check If there is need action req
        $this->checkIfThereIsNeedActionReq($request->id);

        $prev_user = $reqInfo->user_id;

        $reqID = $reqInfo->id;

        $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

        /////////////////////////////////////////////////////////////////
        //MOVE NEW AND READ TASK
        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [0, 1])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status'     => 0,
                'recive_id'  => $request->salesAgent,
                'created_at' => carbon::now(),
            ]);

            $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                'task_contents_status' => 0,
                'date_of_content'      => carbon::now(),
            ]);
        }

        //////MOVE REPLAID TASK

        $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

            $query->where(function ($query) use ($reqID, $prev_user) {
                $query->where('tasks.req_id', $reqID);
                $query->where('tasks.recive_id', $prev_user);
            });

            $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                $query->where('tasks.recive_id', $prev_user);
            });
        })->whereIn('status', [2])->pluck('id')->toArray();

        if (count($getAllTasksIds) > 0) {

            //set current task as completed
            DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                'status' => 3,
            ]);

            //GET ALL PERVIOS TASK INFO
            $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

            foreach ($tasks as $task) {

                $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                ->first();

                if (!empty($getTaskContent)) {
                    $newTask = task::create([
                        'req_id'    => $task->req_id,
                        'recive_id' => $request->salesAgent,
                        'user_id'   => $task->user_id,
                    ]);

                    $newContent = task_content::create([
                        'content'         => $getTaskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }

        ///////////////////////////////////////////////////////

        $customerID = $reqInfo->customer_id;

        //  return response()->json(['req'=>$customerID]);
        // Todo: update managers
        $updatereq = DB::table('requests')->where('id', $request->id)->update([
            'user_id'                 => $request->salesAgent,
            'statusReq'               => 0,
            'agent_date'              => carbon::now(),
            'is_stared'               => 0,
            'is_followed'             => 0,
            'add_to_stared'           => null,
            'add_to_followed'         => null,
            'isUnderProcFund'         => 0,
            'isUnderProcMor'          => 0,
            'recived_date_report'     => null,
            'recived_date_report_mor' => null,
            'is_freeze'               => 0,
            // 'created_at' => carbon::now(),
            // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
        ]);

        if ($reqInfo->collaborator_id == null) {
            $updatecust = DB::table('customers')->where('id', $customerID)->update([
                'user_id' => $request->salesAgent, //active
            ]);
        }

        DB::table('notifications')->insert([ // add notification to send user
                                             'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                             'recived_id' => $request->salesAgent,
                                             'created_at' => (Carbon::now('Asia/Riyadh')),
                                             'type'       => 0,
                                             'req_id'     => $request->id,
        ]);

        $agenInfo = DB::table('users')->where('id', $request->salesAgent)->first();
        //$pwaPush = MyHelpers::pushPWA($request->salesAgent, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);
        $trans_basket = (bool) $request->get('trans_basket', 0);
        $content = !$trans_basket ? MyHelpers::admin_trans(auth()->user()->id, 'Admin') : RequestHistory::CONTENT_ADMIN_TRANS_BASKET;
        //d($request->all());
        DB::table('request_histories')->insert([
            'title'          => RequestHistory::TITLE_MOVE_REQUEST,
            'user_id'        => $prev_user,
            'recive_id'      => $request->salesAgent,
            'history_date'   => (Carbon::now('Asia/Riyadh')),
            'req_id'         => $request->id,
            'class_id_agent' => $reqInfo->class_id_agent,
            //'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
            //'content'        => RequestHistory::CONTENT_ADMIN_TRANS_BASKET,
            'content'        => $content,
        ]);

        DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                            'recived_id' => $prev_user,
                                            'req_id'     => $request->id,
        ])->delete();

        #move customer's messages to new agent
        MyHelpers::movemessage($customerID, $request->salesAgent, $prev_user);

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = $prev_user;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$request->id);
        //***********END - UPDATE DAILY PREFROMENCE */

        //***********UPDATE DAILY PREFROMENCE */
        $agent_id = $request->salesAgent;
        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
            $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
        }
        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$request->id);
        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$request->id);
       // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->id);
        //***********END - UPDATE DAILY PREFROMENCE */

        #Remove request from Quality & Need Action Req once moved it
        #1::Remove Req from Quality
        if (MyHelpers::checkQualityReqExistedByReqID($request->id) > 0) {
            $qualityReqDelte = MyHelpers::removeQualityReqByReqID($request->id);
            if ($qualityReqDelte == 0) {
                MyHelpers::updateQualityReqToCompleteByReqID($request->id);
            }
        }
        #2::Remove from Need Action Req
        MyHelpers::removeNeedActionReqByReqID($request->id);

        if ($updatereq == 0) {
            return response()->json(['updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
        }

        return response()->json(['agentName' => $agenInfo->name, 'updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

    }

    public function moveReqToAnotherArray(Request $request)
    {
        $counter = 0;
        $requests_data = DB::table('requests')
            ->whereIn('id', $request->get('id'))
            ->where(fn($b) => $b->whereNotIn('class_id_agent', [57, 58])->orWhereNull('class_id_agent'))
            ->get();
        $updatereq = 0 ;
        foreach ($requests_data as $reqInfo) {

            $prev_user = $reqInfo->user_id;
            $reqID = $reqInfo->id;
            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

            /*
            #move all curent tasks to new agent
            $getAllTasksIds = DB::table('tasks')
                ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                    $query->where(function ($query) use ($reqID, $prev_user) {
                        $query->where('tasks.req_id',  $reqID);
                        $query->where('tasks.recive_id',  $prev_user);
                    });

                    $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                        $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                        $query->where('tasks.recive_id', $prev_user);
                    });
                })
                ->whereIn('status', [0, 1, 2])
                ->pluck('id')->toArray();


            if (count($getAllTasksIds) > 0) {

                $updateTask = DB::table('tasks')
                    ->whereIn('id', $getAllTasksIds)
                    ->update([
                        'status' => 0,
                        'recive_id' => $request->salesAgent,
                        'created_at' => carbon::now(),
                    ]);
            }
            #
            */

            /////////////////////////////////////////////////////////////////
            //MOVE NEW AND READ TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $request->salesAgent,
                    'created_at' => carbon::now(),
                ]);

                $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            //////MOVE REPLAID TASK

            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $request->salesAgent,
                            'user_id'   => $task->user_id,
                        ]);

                        $newContent = task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }

            ///////////////////////////////////////////////////////

            $customerID = $reqInfo->customer_id;
            // Todo: update managers
            $updatereq = DB::table('requests')->where('id', $reqID)->update([
                'user_id'                 => $request->salesAgent,
                'statusReq'               => 0,
                'agent_date'              => carbon::now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);
            if ($updatereq) {
                $counter++;
            }

            if ($reqInfo->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $request->salesAgent, //active
                ]);
            }

            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                 'recived_id' => $request->salesAgent,
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 0,
                                                 'req_id'     => $reqID,
            ]);

            $agenInfo = DB::table('users')->where('id', $request->salesAgent)->first();
            //$pwaPush = MyHelpers::pushPWA($request->salesAgent, ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);

            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $request->salesAgent,
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'req_id'         => $reqID,
                'class_id_agent' => $reqInfo->class_id_agent,
                'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
            ]);

            DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                                'recived_id' => $prev_user,
                                                'req_id'     => $reqID,
            ])->delete();

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $request->salesAgent;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$reqID);
           // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            #move customer's messages to new agent
            MyHelpers::movemessage($customerID, $request->salesAgent, $prev_user);

            #Remove request from Quality & Need Action Req once moved it
            #1::Remove Req from Quality
            if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
                $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
                if ($qualityReqDelte == 0) {
                    MyHelpers::updateQualityReqToCompleteByReqID($reqID);
                }
            }
            #2::Remove from Need Action Req
            MyHelpers::removeNeedActionReqByReqID($reqID);
        }

        if ($counter == 0) {
            return response()->json(['updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
        }
        return response()->json(['counter' => $counter, 'updatereq' => $updatereq, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

    }

    public function moveReqToAnotherArrayAgent(Request $request)
    {
        //dd(2);
        $counter = 0;
        $i = 0;
        $salesAgents = [];

        $requests_data = DB::table('requests')
            ->whereIn('id', $request->id)
            ->where(fn($b) => $b->whereNotIn('class_id_agent', [57, 58])->orWhereNull('class_id_agent'))
            ->get();

        if ($request->agents_ids == '') {
            $salesAgents = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        }
        else {
            $salesAgents = array_merge($salesAgents, $request->agents_ids);
        }

        foreach ($requests_data as $reqInfo) {

            if (count($salesAgents) == $i) {
                $i = 0;
            }

            $updatereq = 0;

            $prev_user = $reqInfo->user_id;
            $prev_user = str_replace(' ', '', $prev_user);
            if (($key = array_search($prev_user, $salesAgents)) !== false) {
                unset($salesAgents[$key]); // to remove same user to dublicate with same request
            }

            if (count($salesAgents) == 0)  //check if there's no avalibile agent
            {
                return response()->json(['updatereq' => 2, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'No Avaliable Agents')]);
            }

            #check If there is need action req
            $this->checkIfThereIsNeedActionReq($reqInfo->id);

            $reqID = $reqInfo->id;
            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

            /*
            #move all curent tasks to new agent
            $getAllTasksIds = DB::table('tasks')
                ->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                    $query->where(function ($query) use ($reqID, $prev_user) {
                        $query->where('tasks.req_id',  $reqID);
                        $query->where('tasks.recive_id',  $prev_user);
                    });

                    $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                        $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                        $query->where('tasks.recive_id', $prev_user);
                    });
                })
                ->whereIn('status', [0, 1, 2])
                ->pluck('id')->toArray();


            if (count($getAllTasksIds) > 0) {

                $updateTask = DB::table('tasks')
                    ->whereIn('id', $getAllTasksIds)
                    ->update([
                        'status' => 0,
                        'recive_id' => $salesAgents[$i],
                        'created_at' => carbon::now(),
                    ]);
            }
            #
            */

            /////////////////////////////////////////////////////////////////
            //MOVE NEW AND READ TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                $updateTask = DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $salesAgents[$i],
                    'created_at' => carbon::now(),
                ]);

                $updateTaskContent = DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            //////MOVE REPLAID TASK

            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {

                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $salesAgents[$i],
                            'user_id'   => $task->user_id,
                        ]);

                        $newContent = task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }

            ///////////////////////////////////////////////////////

            $customerID = $reqInfo->customer_id;
            $updatereq = DB::table('requests')->where('id', $reqID)->update([
                'user_id'                 => $salesAgents[$i],
                'statusReq'               => 0,
                'agent_date'              => carbon::now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);
            if ($updatereq) {
                $counter++;
            }

            if ($reqInfo->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $salesAgents[$i], //active
                ]);
            }

            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                                                 'recived_id' => $salesAgents[$i],
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 0,
                                                 'req_id'     => $reqID,
            ]);

            $agenInfo = DB::table('users')->where('id', $salesAgents[$i])->first();
            //  //$pwaPush = MyHelpers::pushPWA($salesAgents[$i], ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);

            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $salesAgents[$i],
                'history_date'   => (Carbon::now('Asia/Riyadh')),
                'req_id'         => $reqID,
                'class_id_agent' => $reqInfo->class_id_agent,
                'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
            ]);

            DB::table('notifications')->where([ //remove previous notificationes that related to previous agent's request
                                                'recived_id' => $prev_user,
                                                'req_id'     => $reqID,
            ])->delete();

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $salesAgents[$i];
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$reqID);
           // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            #move customer's messages to new agent
            MyHelpers::movemessage($customerID, $salesAgents[$i], $prev_user);

            #Remove request from Quality & Need Action Req once moved it
            #1::Remove Req from Quality
            if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
                $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
                if ($qualityReqDelte == 0) {
                    MyHelpers::updateQualityReqToCompleteByReqID($reqID);
                }
            }
            #2::Remove from Need Action Req
            MyHelpers::removeNeedActionReqByReqID($reqID);

            $i++;
        }

        if ($counter == 0) {
            return response()->json(['updatereq' => 0, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
        }
        return response()->json(['counter' => $counter, 'updatereq' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

    }

    public function deleteUser(Request $request)
    {

        $user = DB::table('users')->where('id', $request->id)->first();

        $updateResult = DB::table('users')->where('id', '=', $user->id)->update([
            'status' => 0, //archive
        ]);

        //LOGOUT ARCHIVED USER
        userActivity::where('user_id', $user->id)->update([
            'last_activity' => 1494030000,
        ]);
        User::where('id', $user->id)->update(['logout' => true]);
        ///

        if ($updateResult == 0) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $updateResult]);
        }
        return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Archive Successfully'), 'status' => $updateResult]); // if 1: update succesfally

    }

    public function restoreUser($id)
    {

        $updateResult = DB::table('users')->where('id', '=', $id)->update([
            'status' => 1, //active
        ]);

        if ($updateResult == 0) {
            return redirect()->route('admin.archUsers')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
        }
        return redirect()->route('admin.archUsers')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Restore sucessfully'));
    }

    public function archUserArr(Request $request)
    {

        $result = DB::table('users')->whereIn('id', $request->array)->update([
            'status' => 0, //archive
        ]);
        return response($result); // if 1: update succesfally

    }

    public function restUserArr(Request $request)
    {

        $result = DB::table('users')->whereIn('id', $request->array)->update([
            'status' => 1, //active
        ]);
        return response($result); // if 1: update succesfally

    }

    public function sendCustomerArray(Request $request)
    {
        $customers = Customer::query()->whereIn('id', $request->array)->get();
        $counter = 0;
        $request_count = count($request->array);
        $type = $request->type;
        foreach ($customers as $customer) {
            $data = [
                "customer_id" => $customer->id,
                "type"        => $type,
                "message"     => $request->text,
                "subject"     => $request->subject,
            ];
            if ($type == 'email') {
                if ($customer->email != null) {
                    $counter++;
                    MyHelpers::sendEmail($customer->email, $request->text, $request->subject);
                    $data["type_value"] = $customer->email;
                    $customer->increment('send_email_count');
                }
            }
            else {
                if ($customer->mobile != null) {
                    $counter++;
                    MyHelpers::sendSMS($customer->mobile, $request->text);
                    $data["type_value"] = $customer->mobile;
                }
            }
            CustomerMessageHistory::create($data);
        }
        return response()->json(['counter' => $counter, 'request_count' => $request_count]);
    }

    public function updatecustomer(Request $request)
    {

        //  return response($request);

        $retriveCustomerData = DB::table('customers')->join('users', 'users.id', '=', 'customers.user_id')->where([
            ['customers.id', '=', $request->id],
        ])->select('customers.*', 'users.name as user_name')->first();

        if (!empty($retriveCustomerData)) {
            return response()->json([$retriveCustomerData, 'status' => 1]);
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'), 'status' => 0]);
        }
    }

    public function archiveCustomer(Request $request)
    {

        if ($request->ajax()) {

            $resulte = DB::table('customers')->where([
                ['id', '=', $request->id],
            ])->update(['status' => 1]); //archive
            if ($resulte == 0) //nothing delete
            {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => $resulte]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Archive successfully'), 'status' => $resulte]);
            }
        }
    }

    public function restoreCustomer(Request $request)
    {

        $resulte = DB::table('customers')->where([
            ['id', '=', $request->id],
        ])->update(['status' => 0]);
        if ($resulte == 0) //nothing delete
        {
            return redirect()->back()->with('msg2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
        }
        else {
            return redirect()->back()->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Restore successfully'));
        }
    }

    public function archCustArr(Request $request)
    {

        $result = DB::table('customers')->whereIn('id', $request->array)->update([
            'status' => 1, //archived
        ]);
        return response($result); // if 1: update succesfally

    }

    public function restCustArr(Request $request)
    {

        $result = DB::table('customers')->whereIn('id', $request->array)->update([
            'status' => 0, //active
        ]);
        return response($result); // if 1: update succesfally

    }

    public function editCustomer(Request $request)
    {

        $auth = Auth::user();  // get  auth info
        $id = $auth->id;

        $checkmobile = DB::table('customers')->where('mobile', $request->mobile)->first();

        if (!empty($checkmobile)) {
            if ($request->id != $checkmobile->id) {
                return response('existed');
            }
        }

        $rules = [
            'name'   => 'required',
            'mobile' => 'required|digits:9|regex:/^(5)[0-9]{8}$/',
            // 'sex' => 'required',
            // 'birth' => 'required',
            // 'work' => 'required',
            // 'salary_source' => 'required',
            //  'salary' => 'numeric',
        ];

        $customMessages = [
            // 'mobile.unique' => MyHelpers::admin_trans(auth()->user()->id, 'This customer already existed'),
            'mobile.regex'    => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.digits'   => MyHelpers::admin_trans(auth()->user()->id, 'Should start with 5'),
            'mobile.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'name.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            // 'sex.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateResult = DB::table('customers')->where([
            ['id', '=', $request->id],
        ])->update([
            'name'             => $request->name,
            'mobile'           => $request->mobile,
            'sex'              => $request->sex,
            'birth_date'       => $request->birth,
            'birth_date_higri' => $request->birth_hijri,
            'age'              => $request->age,
            'work'             => $request->work,
            'salary_id'        => $request->salary_source,
            'salary'           => $request->salary,
            'is_supported'     => $request->support,
        ]); //if $updateResult=1 , so it's edit a new thing but if return 0 , so no data change

        $arr = [

            'request'    => $request->all(), //brcause it's contain alot of data
            'salesagent' => $request->salesagent,
            'ss'         => $updateResult,
        ];

        return response($arr);
    }

    public function addCustomer_page()
    {

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $madany_works = DB::table('madany_works')->select('id', 'value')->get();
        $askary_works = DB::table('askary_works')->select('id', 'value')->get();
        $salesagents = DB::table('users')->where('role', 0)->get();

        $notifys = $this->fetchNotify(); //get notificationes

        return view('Admin.Customer.addCustomer', compact('salary_sources', 'madany_works', 'askary_works', 'notifys', 'salesagents'));
    }

    public function fetchNotify()
    { // to get notificationes of users

        $checkFollow = DB::table('notifications')->where('recived_id', (auth()->user()->id))->where('reminder_date', "<=", Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s"))->where('status', 2) //Not Active (for following)
        ->first();

        if (!empty($checkFollow)) {
            DB::table('notifications')->where('id', $checkFollow->id)->update([
                'status'     => 0,
                'created_at' => Carbon::now('Asia/Riyadh'),
            ]);
        }

        return DB::table('notifications')->where('recived_id', (auth()->user()->id))->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('notifications.status', 0) // new
        ->orderBy('notifications.id', 'DESC')->select('notifications.*', 'customers.name')->get();
    }

    public function getCustomerHistory($customerId)
    {
        $histories = CustomerMessageHistory::where('customer_id', $customerId)->get();
        $customer = customer::find($customerId);
        return view('Admin.Customer.CustomerMessageHistory',
            compact('histories', 'customerId', 'customer'));
    }

    public function getCustomerHistory_datatable($customerId)
    {

        $histories = CustomerMessageHistory::with('customer')->where('customer_id', $customerId)->get();
        return DataTables::of($histories)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('type', function ($histories) {
                $return = '';
                if ($histories->type == 'email') {
                    $return = '<span class="badge badge-danger label-inline mr-2">رسالة بريد إلكترونى</span>';
                }
                else {
                    $return = '<span class="badge badge-success label-inline mr-2">رسالة نصية </span>';
                }
                return $return;
            })
            ->addColumn('subject', function ($histories) {
                return $histories->type_value;
            })
            ->addColumn('subject', function ($histories) {
                return $histories->subject ?? '-';
            })
            ->addColumn('message', function ($histories) {
                return str_limit($histories->message, 40);
            })
            ->addColumn('customer', function ($histories) {
                return $histories->customer->name;
            })
            ->addColumn('actions', function ($histories) {
                $subject = $histories->subject ?? 'رسالة نصية';

                return '
                        <div id="tableAdminOption" class="tableAdminOption">
                            <span class="item pointer" id="open" data-id="'.$histories->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                            <a data-toggle="modal" data-target="#exampleModal'.$histories->id.'"><i class="fas fa-eye"></i></a>
                            </span>
                        </div>
                        <div class="modal fade" id="exampleModal'.$histories->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"> '.$subject.'</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <span style="white-space:break-spaces">'.$histories->message.'</span>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> إغلاق</button>
                              </div>
                            </div>
                          </div>
                        </div>';
            })
            ->rawColumns(['idn', 'customer', 'message', 'subject', 'type', 'actions'])->make(true);
    }
}

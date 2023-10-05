<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class SearchController extends Controller
{
    public function search_user_account(Request $request)
    {
        //tasks,requests,users,customers,notifications
        
       $search_value= $request->search;
       $project_url=url('/');
       
       $role=auth()->user()->role;
       $user_id=auth()->user()->id;

       //return $user_id;
      
       
       //return $cond;

       if($role=='7'){//admin
            //search in user table
            $user_result=DB::table('users')->select('id','name as title',DB::raw("CONCAT('".$project_url."/HumanResource/user/profile/',id) AS url"));
                        $user_result->where(function($query) use($search_value){
                            $query->where('name','LIKE','%'.$search_value.'%')
                            ->orwhere('name_for_admin','LIKE','%'.$search_value.'%')
                            ->orwhere('username','LIKE','%'.$search_value.'%')
                            ->orwhere('name_in_callCenter','LIKE','%'.$search_value.'%')
                            ->orwhere('mobile','LIKE','%'.$search_value.'%')
                            ->orwhere('email','LIKE','%'.$search_value.'%'); 
                        });

       }
       
        //search in customer table
        $customer_result=DB::table('customers')->select('id','name as title',DB::raw("CONCAT('".$project_url."/admin/fundingreqpage/',id) AS url"));
                            if($role!='7'){$customer_result = $customer_result->where('user_id',$user_id);}
                            $customer_result->where(function($query) use($search_value){
                                $query->where('name','LIKE','%'.$search_value.'%')
                                ->orwhere('username','LIKE','%'.$search_value.'%')
                                ->orwhere('age','LIKE','%'.$search_value.'%')
                                ->orwhere('mobile','LIKE','%'.$search_value.'%')
                                ->orwhere('job_title','LIKE','%'.$search_value.'%')
                                ->orwhere('sex','LIKE','%'.$search_value.'%'); 
                            });
                            
        //search in notifications table
        $notifications_result=DB::table('notifications')->select('id','value as title',DB::raw("CONCAT('".$project_url."/admin/all/opennot/',req_id,'/',recived_id) AS url"));
                                 if($role!='7'){
                                    $notify_tasks=DB::table('tasks')->where('user_id',$user_id)->pluck('id');
                                    $notifications_result = $notifications_result->whereIn('task_id',$notify_tasks);
                                    // $notifications_result = $notifications_result->whereIn('task_id',$notify_tasks)->orwhereNULL('task_id');
                                }
                                 $notifications_result->where(function($query) use($search_value){
                                     $query->where('value','LIKE','%'.$search_value.'%'); 
                                 });

        //search in task_contents table
        $tasks_result=DB::table('task_contents')->select('id','content as title',DB::raw("CONCAT('".$project_url."/all/show_users_task/',task_id) AS url"));
                    if($role!='7'){
                        $notify_tasks=DB::table('tasks')->where('user_id',$user_id)->pluck('id');
                        $tasks_result = $tasks_result->whereIn('task_id',$notify_tasks)->orwhereNULL('task_id');
                    }
                    $tasks_result->where(function($query) use($search_value){
                        $query->where('content','LIKE','%'.$search_value.'%')
                              ->orwhere('user_note','LIKE','%'.$search_value.'%'); 
                    });
                    
        //search in requests table
        $requests_result=DB::table('requests')->select('id','comment as title',DB::raw("CONCAT('".$project_url."/admin/fundingreqpage/',id) AS url"));
                                 if($role!='7'){$requests_result = $requests_result->where('user_id',$user_id);}
                                 $requests_result->where(function($query) use($search_value){
                                    $query->where('type','LIKE','%'.$search_value.'%')
                                    ->orwhere('comment','LIKE','%'.$search_value.'%')
                                    ->orwhere('quacomment','LIKE','%'.$search_value.'%')
                                    ->orwhere('accountcomment','LIKE','%'.$search_value.'%')
                                    ->orwhere('sm_comment','LIKE','%'.$search_value.'%')
                                    ->orwhere('fm_comment','LIKE','%'.$search_value.'%')
                                    ->orwhere('mm_comment','LIKE','%'.$search_value.'%')
                                    ->orwhere('gm_comment','LIKE','%'.$search_value.'%')
                                    ->orwhere('noteWebsite','LIKE','%'.$search_value.'%')
                                    ->orwhere('reqNoBank','LIKE','%'.$search_value.'%')
                                    ->orwhere('empBank','LIKE','%'.$search_value.'%')
                                    ->orwhere('marckting_company','LIKE','%'.$search_value.'%')
                                    ->orwhere('markter','LIKE','%'.$search_value.'%')
                                    ->orwhere('funder','LIKE','%'.$search_value.'%')
                                    ->orwhere('natureRequest','LIKE','%'.$search_value.'%')
                                    ->orwhere('customer_reason_for_cancel','LIKE','%'.$search_value.'%')
                                    ->orwhere('collaborator_notes','LIKE','%'.$search_value.'%')
                                    ->orwhere('agent_identity_number','LIKE','%'.$search_value.'%')
                                    ->orwhere('phoneNumbers','LIKE','%'.$search_value.'%')
                                    ->orwhere('source','LIKE','%'.$search_value.'%'); 
                                });

       // return $requests_result->get();
        // $merged = $user_result->merge($customer_result);
        // $merged = $merged->merge($notifications_result);
        // $merged = $merged->merge($tasks_result);
        // $merged = $merged->merge($requests_result);

        // return $merged->toarray();
        return [
            
            'customers'=>$customer_result->get(),
            'notifications'=>$notifications_result->get(),
            'tasks'=>$tasks_result->get(),
            'requests'=>$requests_result->get(),
            'users'=> (isset($user_result))? $user_result->get():[]
        ];

    
    }
//----------------------------------------------------------------------------------------
}

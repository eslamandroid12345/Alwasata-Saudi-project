<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\Request;

use App\AskAnswer;
use App\funding;
use App\joint;
use App\real_estat;
use App\requestHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait OldModelTrait
{

    /*public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }*/

    public function phones()
    {
        return $this->belongsTo('App\CustomersPhone');
    }

    public function joint()
    {
        return $this->belongsTo(joint::class, 'joint_id');
    }

    public function real_estat()
    {
        return $this->belongsTo(real_estat::class, 'real_id');
    }

    public function funding()
    {
        return $this->belongsTo(funding::class, 'fun_id');
    }

    public function setUserAttribute($value)
    {
        $this->attributes['user_id'] = $value;
    }

    public function request_histories()
    {
        return $this->hasMany(requestHistory::class);
    }

    public function findNextAgent($colID, $approved)
    {

        if ($approved) {
            // To get user_id for last request

            $last_req_id = DB::table('requests')->where('collaborator_id', $colID)->max('id'); // latest request_id

            //dd(( $last_req_id));

            if ($last_req_id != null) {
                $last_req = DB::table('requests')->where('id', $last_req_id)->first(); // latest request object
                $last_user_id = $last_req->user_id;

                //dd(( $last_user_id));

                $maxValue = DB::table('user_collaborators')->where('user_collaborators.collaborato_id', $colID)
                    ->join('users', 'users.id', '=', 'user_collaborators.user_id')->max('user_collaborators.user_id'); // last user id (Sale Agent User)

                $minValue = DB::table('user_collaborators')->where('user_collaborators.collaborato_id', $colID)
                    ->join('users', 'users.id', '=', 'user_collaborators.user_id')->min('user_collaborators.user_id'); // first user id (Sale Agent User)

                if ($last_user_id == $maxValue) {
                    $user_id = $minValue;
                }
                else {

                    // get next user id
                    $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                        ->where('user_collaborators.user_id', '>', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                        ->min('user_collaborators.user_id');

                    if ($user_id == null) {
                        $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                            ->where('user_collaborators.user_id', '<', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                            ->min('user_collaborators.user_id');
                    }
                }
            }
            else {
                $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                    ->where('user_collaborators.collaborato_id', $colID)
                    ->min('user_collaborators.user_id');
            }

            return $user_id;
        }
        else {

            $last_req_id = DB::table('pending_requests')->where('collaborator_id', $colID)->max('id'); // latest request_id

            //dd(( $last_req_id));

            if ($last_req_id != null) {
                $last_req = DB::table('pending_requests')->where('id', $last_req_id)->first(); // latest request object
                $last_user_id = $last_req->user_id;

                //dd(( $last_user_id));

                $maxValue = DB::table('user_collaborators')->where('user_collaborators.collaborato_id', $colID)
                    ->join('users', 'users.id', '=', 'user_collaborators.user_id')->max('user_collaborators.user_id'); // last user id (Sale Agent User)

                $minValue = DB::table('user_collaborators')->where('user_collaborators.collaborato_id', $colID)
                    ->join('users', 'users.id', '=', 'user_collaborators.user_id')->min('user_collaborators.user_id'); // first user id (Sale Agent User)

                if ($last_user_id == $maxValue) {
                    $user_id = $minValue;
                }
                else {

                    // get next user id
                    $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                        ->where('user_collaborators.user_id', '>', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                        ->min('user_collaborators.user_id');

                    if ($user_id == null) {
                        $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                            ->where('user_collaborators.user_id', '<', $last_user_id)->where('user_collaborators.collaborato_id', $colID)
                            ->min('user_collaborators.user_id');
                    }
                }
            }
            else {
                $user_id = DB::table('user_collaborators')->join('users', 'users.id', '=', 'user_collaborators.user_id')
                    ->where('user_collaborators.collaborato_id', $colID)
                    ->min('user_collaborators.user_id');
            }

            return $user_id;
        }
    }

    public function deleteAfterPending($id)
    {

        DB::table('requests')->where('id', $id)->delete();
    }

    public function updateCreated_at($id)
    {

        $request = DB::table('requests')
            ->where('id', $id)
            ->first();

        if ($request->req_date != null) {
            $newValue = $request->req_date.' '.Carbon::now('Asia/Riyadh')->format('H:i:s');
        }
        else {
            $newValue = Carbon::parse($request->created_at);
        }

        $updatereq = DB::table('requests')->where('id', $request->id)
            ->update([
                'created_at' => $newValue,
                'agent_date' => Carbon::now('Asia/Riyadh'),
            ]);
    }

    public function answers()
    {
        return $this->hasMany(AskAnswer::class);
    }

    public function batch()
    {
        return $this->answers->first()->batch;
    }
}

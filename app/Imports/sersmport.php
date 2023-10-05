<?php

namespace App\Imports;

use App\customer;
use App\funding;
use App\joint;
use App\Model\RequestSearching;
use App\real_estat;
use App\request as req;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use MyHelpers;

// use Maatwebsite\Excel\Concerns\ToModel;

//to take date

class sersmport implements ToCollection, WithHeadingRow, WithValidation
{

    public function collection(Collection $rows)
    {
        $count = 0;
        $error = 0;
        $excel_agents = DB::table('agent_and_excel_import')->pluck('user_id')->toArray();
        $excel_agents_count = DB::table('agent_and_excel_import')->get()->count();

        foreach ($rows as $row) {

            $checkValidMobile = preg_match("/^(5)[0-9]{8}$/", $row['mobile']);
            if ($checkValidMobile == 0) { // not valid
                $error++;
                continue;
            }

            $mobile = customer::where('mobile', $row['mobile'])->first();

            if ($mobile != null) { //dublicated customer
                $error++;
                continue;
            }

            if ($excel_agents_count == 0) {
                $user_id = $this->findNextAgent();
            }
            else {
                $user_id = $this->findNextAgentWithEntred($excel_agents);
            }

            $costomer = customer::create([
                'name'    => 'بدون اسم',
                'mobile'  => $row['mobile'],
                'user_id' => $user_id,
            ]);

            $joint = joint::create([
            ]);

            $real = real_estat::create([
            ]);

            $fun = funding::create([
            ]);

            $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
            $searching_id = RequestSearching::create()->id;

            $request = req::create([
                'source'       => 1,
                'req_date'     => $reqdate,
                'user_id'      => $user_id,
                'customer_id'  => $costomer->id,
                'searching_id' => $searching_id,
                'joint_id'     => $joint->id,
                'real_id'      => $real->id,
                'fun_id'       => $fun->id,
                'statusReq'    => 0,
                'agent_date'   => carbon::now(),
            ]);

            $notify = MyHelpers::addNewNotify($request->id, $request->user_id); // to add notification
            $record = MyHelpers::addNewReordExcel($request->id, $request->user_id); // to add new history record
            $emailNotify = MyHelpers::sendEmailNotifiaction('new_req', $request->user_id, 'لديك طلب جديد', 'طلب جديد تم إضافته لسلتك');//email notification

            $count++;

        }

        return back()->with(['excelCount' => $count, 'countRow' => ($error + $count)]);
    }

    public function findNextAgent()
    {
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
        }

        return $user_id;

    }

    public function findNextAgentWithEntred($agentExcel)
    {
        $last_req_id = DB::table('requests')->whereIn('user_id', $agentExcel)->max('id');
        $last_req = DB::table('requests')->where('id', $last_req_id)->get();
        $last_user_id = $last_req[0]->user_id;
        $maxValue = DB::table('users')->whereIn('id', $agentExcel)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
        $minValue = DB::table('users')->whereIn('id', $agentExcel)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

        if ($last_user_id == null) {
            $last_user_id = 61;
        } //Ahmed Qassem

        if ($last_user_id == $maxValue) {
            $user_id = $minValue;
        }
        else {
            // get next user id
            $user_id = User::where('id', '>', $last_user_id)->whereIn('id', $agentExcel)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
        }

        return $user_id;
    }

    public function getNextUser()
    {
        return [
            'customer_mobile' => 'required|unique:customers,mobile',
        ];
    }

    /* Archive Request

        foreach ($rows as $row)
        {


             $customername=$row['customer_name'];
            $customer = customer::where('name',$customername)
           ->where('user_id',$row['user_id'])->first();


             if ($row['classifcation'] != null){
                        $value  = classifcation::where('value',$row['classifcation'])->first();
                        if ($value === null) {
                            $classifcation = classifcation::create([
                                'user_role'  => 0,
                                'value'      => $row['classifcation'],
                            ]);
                        }
                    }


            if ($customer != null){
            $update = request::where('customer_id',$customer->id)
            ->update(['statusReq'  =>  2,
            'comment'=>$row['note'],
            'class_id_agent' =>$row['classifcation']=== null ? null : ($value === null ? $classifcation->id : $value->id)
            ]);
            $count++;}

              // dd($row['user_id']);


        }

    */

    public function rules(): array
    {
        return [
            'customer_mobile' => 'required|unique:customers,mobile',
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }

}

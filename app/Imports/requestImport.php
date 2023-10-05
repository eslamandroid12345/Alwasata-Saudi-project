<?php

namespace App\Imports;

use App\customer;
use App\request;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class requestImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param  array  $row
     *
     * @return Model|null
     */
    public function collection(Collection $rows)
    {
        // dd($rows);
        $mobilearray = [];
        $count = 0;
        $error = 0;
        foreach ($rows as $row) {
            $mobile = customer::where('mobile', $row['customer_mobile'])->first();
            if ($mobile === null) {
                $costomer = customer::create([
                    'name'             => $row['customer_name'],
                    'mobile'           => $row['customer_mobile'],
                    'salary'           => $row['customer_salary'],
                    'age'              => $row['customer_age'],
                    'birth_date'       => Carbon::parse($row['customer_birth_date']),
                    'birth_date_higri' => Carbon::parse($row['customer_birth_date_higri']),
                ]);
                // dd($costomer);
                request::create([
                    'type'        => $row['type'],
                    // 'statusReq'       => $row['statusReq'],
                    'source'      => $row['source'],
                    'req_date'    => Carbon::parse($row['req_date']),
                    'comment'     => $row['comment'],
                    'customer_id' => $costomer->id,
                ]);
                $count++;
            }
            else {
                $error++;
                array_push($mobilearray, $row['customer_mobile']);
            }
        }

        return back()->with(['exeal_error' => $mobilearray, 'exeal_Count' => $count, 'er' => $error]);

        // return $mobilearray;
        // dd($mobilearray);
    }

    public function rules(): array
    {
        return [
            'customer_mobile' => 'required|unique:customers,mobile',
        ];
    }

    public function model(array $row)
    {
        // dd($row);

        return DB::table('requests')->insert([
            // 'id'  => $row['id'],
            'type'            => $row['type'],
            'statusReq'       => $row['statusReq'],
            'source'          => $row['source'],
            'req_date'        => Carbon::parse($row['req_date']),
            'comment'         => $row['comment'],
            'user_id'         => $row['user_id'],
            'customer_id'     => $row['customer_id'],
            // 'collaborator_id'       => '' ,
            // 'class_id_agent'       => $row['class_id_agent'],
            // 'class_id_sm'       => $row['class_id_sm'],
            // 'class_id_fm'       => $row['class_id_fm'],
            // 'class_id_mm'       => $row['class_id_mm'],
            // 'class_id_gm'       => $row['class_id_gm'],
            // 'joint_id'       => $row['joint_id'],
            // 'real_id'       => $row['real_id'],
            // 'fun_id'       => $row['fun_id'],
            // 'payment_id'       => $row['payment_id'],
            // 'req_id'       => $row['req_id'],
            'noteWebsite'     => $row['noteWebsite'],
            'isUnderProcFund' => $row['isUnderProcFund'],
            'reqNoBank'       => $row['reqNoBank'],
            'empBank'         => $row['empBank'],
            // 'isSentSalesAgent'       => $row['isSentSalesAgent'],
            // 'isSentSalesManager'       => $row['isSentSalesManager'],
            // 'isSentFundingManager'       => $row['isSentFundingManager'],
            // 'isSentMortgageManager'       => $row['isSentMortgageManager'],
            // 'isSentGeneralManager'       => $row['isSentGeneralManager'],
            'is_stared'       => $row['is_stared'],
            'is_canceled'     => $row['is_canceled'],
            'is_followed'     => $row['is_followed'],
        ]);

        // dd($row);
        // DB::table('tbl_customer')->insert($data);

    }

    public function headingRow(): int
    {
        return 1;
    }
}

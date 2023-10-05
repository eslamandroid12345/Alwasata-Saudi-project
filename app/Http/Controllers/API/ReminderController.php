<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\Customer;
use App\Model\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addNewReminderForCustomer(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $day = now('Asia/Riyadh');
            $today = date("Y-m-d", strtotime($day));
            $current = date("H:i", strtotime($day));
            $validatedData = Validator::make($request->all(), [
                'date' => 'required|date|after_or_equal:'.$today,
                'time' => 'required|date_format:H:i|after:'.$current,
                'body' => 'required',
            ], [
                'date.required'       => 'تاريخ التذكير مطلوب * ',
                'time.required'       => 'وقت التذكير مطلوب * ',
                'time.date_format'    => 'وقت التذكير لابد ان يكون ساعات ودقائق',
                'body.required'       => ' محتوى التذكير مطلوب * ',
                'date.date'           => ' تاريخ التذكير غير صالح * ',
                'date.after_or_equal' => 'تاريخ التذكير لابد ان يكون بعد او يساوى تاريخ اليوم * ',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            else {
                //              $reminderDate = date($request->date, strtotime($request->time));
                $reminder = Reminder::create([
                    'date'        => $request->date,
                    'customer_id' => $customerId,
                    'time'        => $request->time,
                    'body'        => $request->body,
                    'status'      => 'new',
                ]);

                DB::table('notifications')->insert([
                    'value'         => $reminder->body,
                    'recived_id'    => $reminder->customer_id,
                    'receiver_type' => 'customer',
                    'created_at'    => now('Asia/Riyadh'),
                    'type'          => 9,
                    'reminder_date' => $request->date.' '.$request->time,
                    'req_id'        => $reminder->id,
                ]);
                return self::successResponse(200, true, "تم اضافة التذكير بنجاح", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }

    }

    public function getCustomerReminders(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $validatedData = Validator::make($request->all(), [
                'page' => 'required|integer',
            ], [
                'page.required' => 'page id is required',
                'page.integer'  => 'page id must be an integer value',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            $day = now('Asia/Riyadh');
            $today = date("Y-m-d", strtotime($day));
            $current = date("H:i", strtotime($day));
            $reminders = Reminder::where('customer_id', $customerId)
                // ->where('date','=',$today)
                //           ->where('status','=','new')
                // ->where('time','=',$current)
                ->select('id as reminder_id', 'body', 'date', 'time', 'status')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            if ($reminders->count() <= 0) {
                return self::errorResponse(422, false, "لا يوجد لديك تذكيرات بالوقت الحالي !", null);
            }
            else {
                return $this->responseWithPagination($reminders, $reminders->all(), $request->page);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function updateCustomerReminder(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $day = now('Asia/Riyadh');
            $today = date("Y-m-d", strtotime($day));
            $current = date("H:i", strtotime($day));
            $validatedData = Validator::make($request->all(), [
                'reminder_id' => 'required|integer',
                'date'        => 'nullable|date|after_or_equal:'.$today,
                'time'        => 'nullable|date_format:H:i|after:'.$current,
                'body'        => 'nullable',
            ], [
                'reminder_id.required' => 'reminder id is required',
                'reminder_id.integer'  => 'reminder id must be an integer value',
                'time.date_format'     => 'وقت التذكير لابد ان يكون ساعات ودقائق ',
                'time.after'           => 'وقت التذكير لابد ان يكون بعد الوقت الحالى '.$current.'* ',
                'date.date'            => ' تاريخ التذكير غير صالح * ',
                'date.after_or_equal'  => 'تاريخ التذكير لابد ان يكون بعد او يساوى تاريخ اليوم * ',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            else {
                $checkReminder = Reminder::where('id', $request->reminder_id)
                    ->where('customer_id', $customerId)
                    ->first();
                if ($checkReminder) {
                    if ($checkReminder->status === "new") {
                        if (count($request->all()) > 1) {
                            $requestData = $request->except(['reminder_id']);
                            $updateReminder = Reminder::where('id', $checkReminder->id)
                                ->where('customer_id', $customerId)
                                ->where('status', '=', 'new')
                                ->update($requestData);
                            if ($updateReminder) {
                                DB::table('notifications')->where([
                                    'req_id'        => $checkReminder->id,
                                    'recived_id'    => $customerId,
                                    'receiver_type' => 'customer',
                                ])->update([
                                    'value'         => $request->body,
                                    'reminder_date' => null,
                                ]);
                                return self::errorResponse(200, true, "تم تحديث التذكير بنجاح", null);
                            }
                        }
                        else {
                            return self::errorResponse(422, false, "لا توجد بيانات تم اضافتها للتحديث", null);
                        }
                    }
                    else {
                        return self::errorResponse(200, true, "!لا يمكنك التعديل على هذا التذكير بسبب ان وقته أنتهي", null);
                    }
                }
                else {
                    return self::errorResponse(422, false, "رقم التذكير خاطئ او لا يتبع هذا العميل", null);
                }
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function deleteCustomerReminder(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $validatedData = Validator::make($request->all(), [
                'reminder_id' => 'required|integer',
            ], [
                'reminder_id.required' => 'reminder id is required',
                'reminder_id.integer'  => 'reminder id must be an integer value',
            ]);
            if ($validatedData->fails()) {
                return self::errorResponse(422, false, $validatedData->errors()->first(), null);
            }
            else {
                $reminder = Reminder::where('id', $request->reminder_id)
                    ->where('customer_id', $customerId)
                    ->first();
                if ($reminder) {
                    DB::table('notifications')->where([
                        'req_id'        => $request->reminder_id,
                        'recived_id'    => $customerId,
                        'receiver_type' => 'customer',
                    ])->delete();
                    $reminder->delete();
                    return self::successResponse(200, true, "تم حذف التذكير بنجاح", null);
                }
                else {
                    return self::errorResponse(422, false, "رقم التذكير غير موجود", null);
                }
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }
}

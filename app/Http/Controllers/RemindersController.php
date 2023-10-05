<?php

namespace App\Http\Controllers;

use App\Model\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;

class RemindersController extends Controller
{
    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content', 'layouts.Customermaster', 'Customer.customerIndexPage', 'Customer.fundingReq.customerReqLayout'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.Customermaster', 'Customer.fundingReq.customerReqLayout'],
        ]);
    }

    static function ArabicDate($date)
    {
        $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
        $en_month = date("M", strtotime($date));
        foreach ($months as $en => $ar) {
            if ($en == $en_month) {
                $ar_month = $ar;
            }
        }

        $find = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"];
        $replace = ["السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة"];
        $ar_day_format = date('D', strtotime($date)); // The Current Day
        $ar_day = str_replace($find, $replace, $ar_day_format);

        header('Content-Type: text/html; charset=utf-8');
        $standard = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        $eastern_arabic_symbols = ["٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"];
        $current_date = $ar_day.' '.date('d', strtotime($date)).' / '.$ar_month.' / '.date('Y', strtotime($date));
        $arabic_date = str_replace($standard, $eastern_arabic_symbols, $current_date);

        return $arabic_date;
    }

    public function index()
    {
        $day = now('Asia/Riyadh');
        $today = date("Y-m-d", strtotime($day));
        $current = date("H:i:s", strtotime($day));
        $reminders = Reminder::where([
            'customer_id' => auth('customer')->user()->id,
            /*            'status' => 'new',*/
        ])->orderBy('created_at', 'DESC')->get();
        return view('Customer.reminders.index', [
            'reminders' => $reminders,
            'today'     => $today,
            'current'   => $current,
        ]);
    }

    public function store(Request $request)
    {
        $day = now('Asia/Riyadh');
        $today = date("Y-m-d", strtotime($day));
        $current = date("H:i", strtotime($day));
        $request->validate([
            'date' => 'required|date|after_or_equal:'.$today,
            'time' => 'required',
            'body' => 'required',
        ], [
            'date.required'       => 'تاريخ التذكير مطلوب * ',
            'time.required'       => 'وقت التذكير مطلوب * ',
            'body.required'       => ' محتوى التذكير مطلوب * ',
            'date.date'           => ' تاريخ التذكير غير صالح * ',
            'date.after_or_equal' => 'تاريخ التذكير لابد ان يكون بعد او يساوى تاريخ اليوم * ',
        ]);

        if ($request->date == $today) {
            $request->validate([
                'time' => 'after_or_equal:'.$current,
            ], [
                'time.after_or_equal' => 'وقت التذكير لابد ان يكون بعد الوقت الحالى '.date("h:i A", strtotime($day)).'* ',
            ]);
        }

        $reminder = Reminder::create([
            'date'        => $request->date,
            'customer_id' => auth('customer')->user()->id,
            'time'        => $request->time,
            'body'        => $request->body,
            'status'      => 'new',
        ]);

        DB::table('notifications')->insert([
            'value'         => $reminder->body,
            'recived_id'    => $reminder->customer_id,
            'receiver_type' => 'customer',
            'created_at'    => (Carbon::now('Asia/Riyadh')),
            'type'          => 9,
            'reminder_date' => null,
            'req_id'        => $reminder->id,
        ]);
        session()->flash('success', 'تم إضافة التذكير ');
        return redirect()->route('customer.customer-reminders.index');
    }

    public function edit($id)
    {
        $day = now('Asia/Riyadh');
        $edit = Reminder::find($id);
        $today = date("Y-m-d", strtotime($day));

        $current = date("H:i:s", strtotime($day));

        if ($edit->date < $today) {
            return redirect()->back();
        }
        if ($edit->date == $today) {
            if ($current > $edit->time) {
                return redirect()->back();
            }
        }
        $reminders = Reminder::where([
            'customer_id' => auth('customer')->user()->id,
            'status'      => 'new',
        ])->orderBy('date', 'DESC')->get();
        return view('Customer.reminders.index', [
            'reminders' => $reminders,
            'edit'      => $edit,
            'editMode'  => 1,
            'today'     => $today,
            'current'   => $current,
        ]);
    }

    public function update(Request $request, $id)
    {
        $day = now('Asia/Riyadh');
        $today = date("Y-m-d", strtotime($day));
        $current = date("H:i", strtotime($day));
        $reminder = Reminder::find($id);

        $request->validate([
            'date' => 'required|date|after_or_equal:'.$today,
            'time' => 'required',
            'body' => 'required',
        ], [
            'date.required'       => 'تاريخ التذكير مطلوب * ',
            'time.required'       => 'وقت التذكير مطلوب * ',
            'body.required'       => ' محتوى التذكير مطلوب * ',
            'date.date'           => ' تاريخ التذكير غير صالح * ',
            'date.after_or_equal' => 'تاريخ التذكير لابد ان يكون بعد او يساوى تاريخ اليوم * ',
        ]);
        if ($request->date == $today) {
            $request->validate([
                'time' => 'after:'.$current,
            ], [
                'time.after_or_equal' => 'وقت التذكير لابد ان يكون بعد الوقت الحالى '.$current.'* ',
            ]);
        }

        $reminder->update([
            'date'        => $request->date,
            'customer_id' => auth('customer')->user()->id,
            'time'        => $request->time,
            'body'        => $request->body,
            'status'      => 'new',
        ]);

        DB::table('notifications')->where([
            'req_id'        => $reminder->id,
            'recived_id'    => $reminder->customer_id,
            'receiver_type' => 'customer',
        ])->update([
            'value'         => $reminder->body,
            'reminder_date' => null,
        ]);
        session()->flash('updated', 'تم تعديل التذكير ');
        return redirect()->route('customer.customer-reminders.index');
    }

    public function destroy($id)
    {
        $reminder = Reminder::find($id);
        DB::table('notifications')->where([
            'req_id'        => $reminder->id,
            'recived_id'    => $reminder->customer_id,
            'receiver_type' => 'customer',
        ])->delete();
        $reminder->delete();
        session()->flash('success', 'تم مسح التذكير ');
        return redirect()->route('customer.customer-reminders.index');
    }

    public function IndexNotification()
    {
        $day = now('Asia/Riyadh');
        $today = date("Y-m-d", strtotime($day));
        $current = date("H:i", strtotime($day));
        $now = date("Y-m-d H:i:s", strtotime($today.' '.$current));
        $reminders = DB::table('notifications')
            ->where('recived_id', (auth()->user()->id))
            ->where('notifications.type', 9)
            ->where('notifications.reminder_date', '<=', $now)
            ->orderBy('notifications.id', 'DESC')
            ->get();

        return view('Customer.reminders.notification', [
            'reminders' => $reminders,
        ]);
    }

    public function markNotification($id, $type = null)
    {
        DB::table('notifications')->where('id', $id)->update(['status' => 1]);
        session()->flash('success', 'تم قراءة التنبيه ');
        if ($type != null) {
            if ($type == 9) {
                return redirect()->route('customer.customer-reminders.index');
            }
            else {
                return redirect()->route('customer.profile');
            }
        }
        else {
            return redirect()->route('customer.notifications.index');
        }
    }

    public function destroyNotification($id)
    {
        DB::table('notifications')->where('id', $id)->delete();
        session()->flash('success', 'تم مسح التنبيه ');
        return redirect()->route('customer.notifications.index');
    }
}

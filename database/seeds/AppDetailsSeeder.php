<?php

use App\Models\AppDetail;
use Illuminate\Database\Seeder;

class AppDetailsSeeder extends Seeder
{
   
    public function run()
    {
        $icon_names=[
           'الشات',
           'اطلب عقار' ,
           'عقاراتى',
           'الطلبات',
           'العقارات',
           'أضافه عميل',
           'تذكيرات',
           'رابط دينامك',
           'نسخ الرابط'
        ];

        AppDetail::truncate();
        for($d=0;$d<sizeof($icon_names);$d++){
            AppDetail::create([ 'icon_name' => $icon_names[$d]]);
        }
    }
}

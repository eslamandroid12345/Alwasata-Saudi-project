<?php

namespace App\Http\Controllers\Proper;

use App\Area;
use App\cities as City;
use App\customer;
use App\District;
use App\Http\Controllers\Controller;
use App\Property;
use App\PropertyRequest;
use App\real_estat;
use App\realType;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

class IndexController extends Controller
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

    function properties()
    {
        $requests = PropertyRequest::where([
            'customer_id' => auth('customer')->user()->id,
        ])
            ->pluck('property_id')->toArray();
        $types = realType::all();
        $properties = Property::where('is_published', 1)->whereNotIn('id', $requests)->paginate(9);

        $cities = City::all();
        $areas = Area::all();
        $districts = District::all();

        $maxRoom = Property::max('num_of_rooms');
        $minRoom = Property::min('num_of_rooms');

        $maxSalon = Property::max('num_of_salons');
        $minSalon = Property::min('num_of_salons');

        $maxKit = Property::max('num_of_kitchens');
        $minKit = Property::min('num_of_kitchens');

        $maxBath = Property::max('num_of_bathrooms');
        $minBath = Property::min('num_of_bathrooms');

        return view('Proper.Frontend.properties', compact('properties', 'areas', 'districts', 'cities', 'types'
            , 'minBath', 'minKit', 'minRoom', 'minSalon', 'maxBath', 'maxKit', 'maxRoom', 'maxSalon'));
    }

    function property($id)
    {
        $property = Property::with('image', 'creator', 'type')->findOrFail($id);
        if ($property && $property->is_published == 1) {
            switch ($property->creator->role) {

                case '6': /* Collaborator */
                    if ($property->creator->allow_recived) { // if collaborator allowed recived  , he will be responsible to property request
                        $recv = $property->creator_id;
                    }
                    else {
                        $recv = MyHelpers::findNextPropertyAgent();
                    }
                    break;

                case '10':  /* Propertor */
                    if ($property->creator->allow_recived) { // if Propertor allowed recived  , he will be responsible to property request
                        $recv = $property->creator->id;
                    }
                    else {
                        // get next property agent
                        $recv = MyHelpers::findNextPropertyAgent();
                    }
                    break;
                default :
                    $recv = $property->creator_id;

            }
            return view('Proper.Frontend.property', compact('property', 'recv'));
        }

        return redirect()->route('missing');

    }

    public function requestProperty(Request $request)
    {

        $requests = \App\request::where('customer_id', $request->customer_id)->first();

        $customer_id = $request->customer_id;
        $property_id = $request->property_id;
        $property = Property::find($request->property_id);
        $customer = customer::find($customer_id);

        $req = PropertyRequest::firstOrCreate([
            'property_id' => $property_id,
            'customer_id' => $customer_id,
        ], [
            'statusReq'      => 1, // New Request
            'property_id'    => $property_id,
            'customer_id'    => $customer_id,
            'responsible_id' => auth('customer')->user()->user->id,
            'source'         => 'ويب - طلب عقار',
            'req_date'       => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
        ]);
        $exists = PropertyRequest::where([
            'property_id' => $property_id,
            'customer_id' => $customer_id,
        ])->count();

        DB::table('notifications')->insert([
            'value'         => 'وجد العميل عقار مناسب ',
            'recived_id'    => $requests->user_id,
            'receiver_type' => 'web',
            'created_at'    => (Carbon::now('Asia/Riyadh')),
            'type'          => 5,
            'reminder_date' => null,
            'req_id'        => $requests->id,
        ]);

        $realID = real_estat::find($requests->real_id)->update([
            'created_at'      => (Carbon::now('Asia/Riyadh')),
            'owning_property' => 'no',
            'city'            => $property->city_id,
            'region'          => $property->area_id,
            'cost'            => $property->fixed_price,
            'type'            => $property->type_id,
        ]);

        DB::table('req_records')->insert([
            'colum'   => $customer->name,
            'user_id' => $requests->user_id,
            'req_id'  => $requests->id,
        ]);

        if ($exists == 1) {
            $lang = User::whereId(auth('customer')->user()->user->id)->first()->locale;
            //---------------------------------------------------
            //commented because of setting error
            //---------------------------------------------------
            /*$notify_from_db = $lang == 'ar'? Setting::getByIndex('property_requested_notification_ar') :Setting::getByIndex('property_requested_notification_en');
            $message = $lang == 'ar'? Setting::getMessage('property_requested_notification_ar') :Setting::getMessage('property_requested_notification_en');
            $notify_msg = $message.' - '. trans('language.property num').'#' . $property_id;   // translate notification*/
            // MyHelpers::sendNotify(auth('customer')->user()->user->id ,'web', $notify_msg ,$req->id , 2 , null );

        }

        if ($req) {
            session()->flash('success', 'تم بنجاح طلب العقار ..');
            return redirect()->route('allProperties');
        }
    }

    public function getProperities(Request $request)
    {
        $property = Property::orderBy('created_at');
        if ($request->type != null) {

            $property->where('type_id', $request->type);
        }

        if ($request->area_id != null) {
            $property->where('area_id', $request->area_id);
        }

        if ($request->address != null) {
            $property->where('address', 'LIKE', '%'.$request->address.'%');
        }

        if ($request->price_type != null) {
            $property->where('price_type', $request->price_type);
            if ($request->price != null) {
                if ($request->price_type == 'fixed') {
                    $property->where('fixed_price', $request->price);
                }
                else {
                    $property->where('min_price', '<=', $request->price)->where('max_price', '>=', $request->price);
                }
            }
        }
        if ($request->num_of_bathrooms != null) {
            $property->where('num_of_bathrooms', $request->num_of_bathrooms);
        }

        if ($request->num_of_kitchens != null) {
            $property->where('num_of_kitchens', $request->num_of_kitchens);
        }

        if ($request->num_of_bathrooms != null) {
            $property->where('num_of_rooms', $request->num_of_rooms);
        }

        if ($request->num_of_salons != null) {
            $property->where('num_of_salons', $request->num_of_salons);
        }
        $data = '<div class="row fadeInUp" style="padding-top: 20px;">';
        foreach ($property->get() as $Property) {
            $price = $Property->price_type === 'fixed' ? $Property->fixed_price : $Property->max_price.'-'.$Property->min_price;
            $district = $Property->district ? $Property->district_id : '';
            $data .= '<div class=" col-xs-12 col-md-4 col-sm-6 col-lg-4 p-1">
                       <div class="card">
                           <div class="card-body text-center">
                               <img src="'.asset(@$Property->image()->first()->image_path).'" alt="" class="img-fluid" style="height: 200px;">
                               <hr>
                               <h3 class="text-center"><b>تفاصيل العقار</b></h3>
                               <strong>'.$Property->type->value.'</strong>
                               <br>
                               <span>'.MyHelpers::guest_trans('sar').'</span>
                               <strong>'.$price.'</strong>
                               <br>
                               <span class="fa fa-map-marker"></span>
                               <span class="loc">'.$Property->address.'</span>
                               <h6 class="pt-3"><a href="'.route('propertyDetails', $Property->id).'" class="btn btn-primary" title="">تفاصيل العقار</a>
                               </h6>
                           </div>
                       </div>

                   </div>';
        }
        $data .= '</div>';

        return $data;

    }

}

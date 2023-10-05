<?php

namespace App\Http\Controllers\API;

use App\Area;
use App\City;
use App\District;
use App\Helpers\MyHelpers;
use App\Http\Controllers\Controller;
use App\Model\Customer;
use App\Property;
use App\PropertyRequest;
use App\realType;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RealEstateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getRealEstateTypes(Request $request)
    {
        $realTypes = realType::all();
        if ($realTypes->count() >= 1) {
            return self::successResponse(200, true, null, $realTypes);
        }
        else {
            return self::errorResponse(422, false, "لا توجد اي سجلات حاليا", null);
        }
    }

    public function getAllAreas()
    {
        $getAllAreas = Area::all();
        if ($getAllAreas->count() >= 1) {
            return self::successResponse(200, true, null, $getAllAreas);
        }
        else {
            return self::errorResponse(422, false, "لا توجد اي سجلات حاليا", null);
        }
    }

    public function getAllCities(Request $request)
    {
        $validatedData = Validator::make($request->only(['area_id']), [
            'area_id' => 'required|integer',
        ], [
            'area_id.required' => 'area id is required',
            'area_id.integer'  => 'area id must be an integer',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            $getCities = City::where('area_id', $request->area_id)->get();
            if ($getCities->count() >= 1) {
                return self::successResponse(200, true, null, $getCities);
            }
            return self::errorResponse(422, false, "لا توجد اي سجلات حاليا", null);
        }

    }

    public function getAllDistricts(Request $request)
    {
        $validatedData = Validator::make($request->only(['city_id']), [
            'city_id' => 'required|integer',
        ], [
            'city_id.required' => 'city id is required',
            'city_id.integer'  => 'city id must be an integer',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        else {
            $getDistricts = District::where('city_id', $request->city_id)->get();
            if ($getDistricts->count() >= 1) {
                return self::successResponse(200, true, null, $getDistricts);
            }
            return self::errorResponse(422, false, "لا توجد اي سجلات حاليا", null);
        }

    }

    public function getAllProperties(Request $request)
    {
        $validatedData = Validator::make($request->only(['page']), [
            'page' => 'required|integer',
        ], [
            'page.required' => 'page is required',
            'page.integer'  => 'page must be an integer',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $result = Property::query()
                ->with('type', 'area', 'city', 'district', 'image')
                ->where('is_published', 1)
                ->paginate(10);
            if ($result->count() <= 0) {
                return self::errorResponse(422, false, "لا توجد بيانات بالوقت الحالي !", null);
            }
            else {
                return $this->responseWithPagination($result, $result->all(), $request->page);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function propertiesFilters(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'type_id'          => 'nullable|integer',
            'area_id'          => 'nullable|integer',
            'city_id'          => 'nullable|integer',
            'district_id'      => 'nullable|integer',
            'price_type'       => 'nullable|in:range,fixed',
            'num_of_bathrooms' => 'nullable|integer',
            'num_of_kitchens'  => 'nullable|integer',
            'num_of_salons'    => 'nullable|integer',
            'price'            => 'nullable|numeric',

        ], [
            'type_id.integer'          => 'type must be an integer',
            'area_id.integer'          => 'area_id must be an integer',
            'city_id.integer'          => 'city_id must be an integer',
            'district_id.integer'      => 'district_id must be an integer',
            'num_of_bathrooms.integer' => 'num of bathrooms must be an integer',
            'num_of_kitchens.integer'  => 'num of kitchens must be an integer',
            'num_of_salons.integer'    => 'num of salons must be an integer',
            'price.numeric'            => 'price must be an number value',
            'price_type.in'            => 'price type must be an fixed or range only',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $result = Property::query()
                ->with('type', 'area', 'city', 'district', 'image')
                ->where('is_published', 1);
            if ($request->has('type_id')) {
                $result->where('type_id', $request->type_id);
            }
            if ($request->has('area_id')) {
                $result->where('area_id', $request->area_id);
            }
            if ($request->has('city_id')) {
                $result->where('city_id', $request->city_id);
            }
            if ($request->has('district_id')) {
                $result->where('district_id', $request->district_id);
            }
            if ($request->has('price_type')) {
                $result->where('price_type', $request->price_type);
                if ($request->price != null) {
                    if ($request->price_type == 'fixed') {
                        $result->where('fixed_price', $request->price);
                    }
                    else {
                        $result->where('min_price', '<=', $request->price)
                            ->where('max_price', '>=', $request->price);
                    }
                }
            }
            if ($request->has('num_of_bathrooms')) {
                $result->where('num_of_bathrooms', $request->num_of_bathrooms);
            }
            if ($request->has('num_of_kitchens')) {
                $result->where('num_of_kitchens', $request->num_of_kitchens);
            }
            if ($request->has('num_of_salons')) {
                $result->where('num_of_salons', $request->num_of_salons);
            }
            return self::successResponse(200, true, null, $result->get());
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function propertiesDetails(Request $request)
    {
        $validatedData = Validator::make($request->only(['property_id']), [
            'property_id' => 'required|integer',
        ], [
            'property_id.required' => 'property id must be an integer',
            'property_id.integer'  => 'property id must be an integer',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $checkProperty = Property::where('id', $request->property_id)->first();
            if ($checkProperty) {
                $asset = asset('/');
                $requests = PropertyRequest::where([
                    'customer_id' => Auth::guard('api')->id(),
                ])
                    ->pluck('property_id')->toArray();
                $property = Property::join('users', 'users.id', '=', 'properties.creator_id')
                    ->leftjoin('cities', 'cities.id', '=', 'properties.city_id')
                    ->leftjoin('areas', 'areas.id', '=', 'properties.area_id')
                    ->leftjoin('districts', 'districts.id', '=', 'properties.district_id')
                    ->leftjoin('images', 'images.imageable_id', '=', 'properties.id')
                    ->where('properties.id', $request->property_id)
                    ->where('properties.is_published', 1)
                    ->where('users.role', 10)
                    ->whereNotIn('properties.id', $requests)
                    ->select('properties.id as property_id', 'users.name',
                        'properties.price_type', 'properties.fixed_price',
                        'properties.max_price', 'properties.min_price',
                        'cities.value as city_name', 'areas.value as area_name',
                        'districts.value as district_name', 'properties.address as property_address',
                        'properties.num_of_rooms', 'properties.num_of_salons', 'properties.num_of_kitchens',
                        'properties.num_of_bathrooms', 'properties.description',
                        'properties.lat as latitude', 'properties.lng as longitude',
                        DB::raw("CONCAT('$asset', '' ,images.image_path) as imag_url")
                    )->selectRaw('DATE(properties.created_at) as property_created_date')
                    ->first();
                $images = DB::table('images')
                    ->where('imageable_id', $request->property_id)
                    ->select(
                        DB::raw("CONCAT('$asset', '' ,images.image_path) as image_url")
                    )->get();
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => null,
                    'payload' => [
                        'property'        => $property,
                        'property_images' => $images,
                    ],
                ], 200);
            }
            else {
                return self::errorResponse(422, false, "رقم العقار غير مسجل لدينا بالنظام", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }

    public function requestProperty(Request $request)
    {
        $validatedData = Validator::make($request->only(['property_id']), [
            'property_id' => 'required|integer',
        ], [
            'property_id.required' => 'property id must be an integer',
            'property_id.integer'  => 'property id must be an integer',
        ]);
        if ($validatedData->fails()) {
            return self::errorResponse(422, false, $validatedData->errors()->first(), null);
        }
        $customerId = Auth::guard('api')->id();
        $checkCustomer = Customer::where('id', $customerId)->first();
        if ($checkCustomer) {
            $req = PropertyRequest::firstOrCreate([
                'property_id' => $request->property_id,
                'customer_id' => $customerId,
            ], [
                'statusReq'      => 1, // New Request
                'property_id'    => $request->property_id,
                'customer_id'    => $customerId,
                'responsible_id' => $checkCustomer->user_id,
                'source'         => 'ويب - طلب عقار',
                'req_date'       => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);
            $exists = PropertyRequest::where([
                'property_id' => $request->property_id,
                'customer_id' => $customerId,
            ])->count();
            if ($exists == 1) {
                $lang = User::whereId($checkCustomer->user_id)->first()->locale;
                $notify_from_db = $lang == 'ar' ? Setting::getByIndex('property_requested_notification_ar') : Setting::getByIndex('property_requested_notification_en');
                $message = $lang == 'ar' ? Setting::getMessage('property_requested_notification_ar') : Setting::getMessage('property_requested_notification_en');
                $notify_msg = $message.' - '.trans('language.property num').'#'.$request->property_id;
                MyHelpers::sendNotify($checkCustomer->user_id, 'web', $notify_msg, $req->id, 2, null);
            }
            if ($req) {
                return self::successResponse(200, true, "تم طلب العقار بنجاح", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للوصول لهذه الصفحة", null);
        }
    }
}

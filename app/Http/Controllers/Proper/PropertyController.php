<?php

namespace App\Http\Controllers\Proper;

use App\Area;
use App\cities as City;
use App\District;
use App\Http\Controllers\Controller;
use App\Image;
use App\Property;
use App\PropertyStreetWidth;
use App\realType;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ImageHelper;
use MyHelpers;
use View;

//to take date

class PropertyController extends Controller
{
    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }



    public function index()
    {
        //dd("test");
        // send notification to update property info
        $this->sendNotificationForUpdate();
        $properties = $this->getProperties();
        if (request()->ajax()) {

            return Datatables::of($properties)
                ->editColumn('id', function ($property) {
                    static $var = 1;
                    return '<div class="custom-control custom-checkbox" style="line-height: 20px;">
                            <input type="checkbox" name="customers_checkbox[]" value="'.$property->id.'" class="custom-control-input customers_checkbox" id="customCheck'.$property->id.'">
                            <label class="custom-control-label text-xs" for="customCheck'.$property->id.'">'.$property->id/*$var++*/.'</label>
                        </div>';
                })
                ->editColumn('created_at', function ($property) {
                    return @$property->created_at->format('Y-m-d');
                })
                ->editColumn('areaName', function ($property) {
                    return $property->areaName->value ?? "-";
                })
                ->addColumn('source', function ($property) {
                    switch (@$property->creator->role) {
                        case '6':
                            $role = MyHelpers::admin_trans(auth()->user()->id, 'collaborator');
                            break;
                        case '9':
                            $role = MyHelpers::admin_trans(auth()->user()->id, 'property agent');
                            break;
                        case '10':
                            $role = MyHelpers::admin_trans(auth()->user()->id, 'Propertor');
                            break;
                        default:
                            break;
                    }
                    $name = @$property->creator->name;
                    return @$role.' - '.@$name;
                })
                ->addColumn('price', function ($property) {
                    if (@$property->price_type == 'range') {
                        $price = MyHelpers::admin_trans(auth()->user()->id, 'range price').'( '.@$property->min_price.' - '.@$property->max_price.' )';
                    }
                    else {
                        $price = MyHelpers::admin_trans(auth()->user()->id, 'fixed price').'-'.@$property->fixed_price;
                    }


                })
                ->addColumn('fixed_price', function ($property) {
                    return number_format($property->fixed_price, 2, '.', ',');
                })
                ->addColumn('type', function ($property) {
                    return @$property->type->value;
                })
                ->addColumn('type', function ($property) {
                    return @$property->type->value;
                })

                ->addColumn('zone', function ($property) {
                    return @$property->lng.' * '.@$property->lat;
                })
                ->editColumn('action', function ($property) {
                    $id = @$property->id;
                    $data = '<div class="table-data-feature">';
                    $edit = route('property.edit', [@$property->id]);
                    $show = route('property.show', [@$property->id]);
                    $delete = route('property.destroy', [@$property->id]);

                    $data = $data.' <a href="'.$show.'" style="margin:auto 5px;">
                    <button class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'show_property_details').'">
                                    <i class="fa fa-eye"></i>
                                </button> </a> ';


                    $data = $data.' <a href="'.$edit.'" style="margin:auto 5px;"> <button class="btn btn-info btn-sm"  data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'edit').'">
                                    <i class="fa fa-edit"></i>
                                </button> </a> ';


                    $data = $data.'<a style="margin:auto 5px;"  onclick="deleteData('.$property->id.')" >
                    <button class="btn btn-danger btn-sm" data-id="'.$property->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                                    <i class="fa fa-trash"></i>
                                </button> </a> ';

                    $data = $data.'</div>';
                    return $data;
                })->escapeColumns([])->rawColumns(['action'])
                ->make(true);
        }

        return view('Proper.Property.index', compact('properties'));
    }

    public function sendNotificationForUpdate()
    {
        $properties = Property::where('creator_id', auth()->id());
        $properties = $properties->where('updated_at', '<=', Carbon::now()->subMonths(2)) // No update in last 2 month
        ->where(function ($query) {
            $query->where('last_notification_date', '=', null) // hasn't notification
            ->orWhere('last_notification_date', '<=', Carbon::now()->subWeeks(1)); // or notification has been more than a week old
        })
            ->get();
        foreach ($properties as $property) {
            $this->notifyForUpdate($property);
            $property->update(['last_notification_date' => Carbon::today(), 'updated_at' => $property->updated_at]);
        }
    }

    public function notifyForUpdate($property)
    {
        $receiver_id = auth()->id();
        // detect agent language  يعني بأي لغة سوف نرسل الاشعار
        $lang = User::whereId($receiver_id)->first()->locale;
        $notify_from_db = $lang == 'ar' ? Setting::getByIndex('update_property_notification_ar') : Setting::getByIndex('update_property_notification_en');
        $notify_msg = $notify_from_db.' , '.trans('language.property num', [], $lang).'#'.$property->id;   // translate notification and add property id to notification text
        //sendNotify($receiver_id, $receiver_type='web' ,$notification , $req = null ,$req_type = 1 ,$type=null)
        MyHelpers::sendNotify($receiver_id, 'web', $notify_msg, null, 3, null);
    }

    private function getProperties()
    {
        if (auth()->user()->role == '7') {
            $properties = Property::all();
        }
        else {
            $properties = Property::with('creator', 'type','areaName')->where('creator_id', auth()->id())->get();
        }
        return $properties;
    }

    public function create()
    {
        $types = realType::all();
        //*******************************************
        // Task-10-Edit
        //*******************************************
        $cities = City::all();
        $areas = Area::all();
        $districts = District::all();
        //-------------------------------------------
        return view('Proper.Property.create', compact(
            'types',
            //******************Task-10-Edit*************************
            'cities',
            'areas',
            'districts'
        ));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $rules = [
            'type_id'          => 'required',
            //'price_type'       => 'required',
         /*   'fixed_price'      => 'nullable|numeric',
            'min_price'        => 'nullable|numeric',
            'max_price'        => 'nullable|numeric',*/
           /* 'latitude'         => 'required',
            'longitude'        => 'required',*/
         /*   'is_published'     => 'nullable',*/
            //'has_offer'        => 'nullable',
            //'offer_price'      => 'nullable|numeric',
         /*   'city_id'          => 'required',
            'area_id'          => 'required',
            'address'          => 'required',
            'num_of_rooms'     => 'required|numeric',
            'num_of_salons'    => 'required|numeric',
            'num_of_kitchens'  => 'required|numeric',
            'num_of_bathrooms' => 'required|numeric',
            'description'      => 'required',
            'video_url'        => 'nullable|url',

            'number_of_streets' => 'nullable',
            'images'           => 'required',*/
        ];
        $customMessages = [
            'required'                  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'type_id.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
         /*   'price_type.required'       => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'required.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'region.required'           => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'address.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_rooms.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_salons.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_kitchens.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_bathrooms.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'description.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'images.required'           => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'video_url.url'             => MyHelpers::admin_trans(auth()->user()->id, 'The value must be valid url'),*/
           /* 'latitude.required'         => 'إحداثيات العقار على الخريطة مطلوبة',*/
        ];
        if ($request->fixed_price != null) {
            $request->merge([
                'fixed_price' => str_replace(',', '', $request->fixed_price),
            ]);
        }
        if ($request->min_price != null) {
            $request->merge([
                'min_price' => str_replace(',', '', $request->min_price),
            ]);
        }
        if ($request->max_price != null) {
            $request->merge([
                'max_price' => str_replace(',', '', $request->max_price),
            ]);
        }
        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->passes()) {
            $request->merge(['creator_id' => auth()->id()]);
            $data = $request->except(['images']);
            $data['lat'] = $request->latitude;
            $data['lng'] = $request->longitude;

            // create a new property
            $property = Property::create($data);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $name = Str::random(32).'.'.$image->getClientOriginalExtension();
                    $path = asset('uploads/'.$name);
                    $image->move(public_path('uploads/'), $name);
                    $imageModel = new Image();
                    $imageModel->image_path = $path;
                    $imageModel->imageable_id = $property->id;
                    $imageModel->imageable_type = 'App\Models\Property';
                    $imageModel->save();
                }
            }
            if($request->street_width != null){
                foreach ($request->street_width as $value){
                    PropertyStreetWidth::create([
                        'property_id' => $property->id,
                        'width' => $value
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العقار',
            ]);
        }

        return response()->json(['errors' => $validator->errors(), 'message' => 'لديك بعض الحقول ناقصة']);
    }

    public function show($id)
    {
        $properties = $this->getProperties()->pluck('id')->toArray();
        if (in_array($id, $properties)) {
            $property = Property::query()->with(['city','areaName','district'])->findOrFail($id);
            return view('Proper.Property.show', compact('property'));
        }
        else {
            die('لا تملك صلاحية الاطلاع على هذا العقار');
        }
    }

    public function edit($id)
    {
        $properties = $this->getProperties()->pluck('id')->toArray();
        if (in_array($id, $properties)) {
            $types = realType::all();
            $cities = City::all();
            $areas = Area::all();
            $districts = District::all();
            //---------------------------------------------
            $property = Property::findOrFail($id);
            return view('Proper.Property.edit', compact(
                'types',
                'property',
                //****************************Task-10-Edit************
                'cities',
                'areas',
                'districts'
            ));
        }
        else {
            die('لا تملك صلاحية التعديل على هذا العقار');
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'type_id'          => 'required',
         /*   'fixed_price'      => 'nullable|numeric',*/
           /* 'latitude'         => 'required',
            'longitude'        => 'required',*/
           /* 'is_published'     => 'nullable',
            'city_id'          => 'required',
            'area_id'          => 'required',
            'address'          => 'required',
            'num_of_rooms'     => 'required|numeric',
            'num_of_salons'    => 'required|numeric',
            'num_of_kitchens'  => 'required|numeric',
            'num_of_bathrooms' => 'required|numeric',
            'description'      => 'required',
            'video_url'        => 'nullable|url',*/
        ];
        $customMessages = [
            'type_id.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
         /*   'required.required'         => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'region.required'           => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'address.required'          => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_rooms.required'     => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_salons.required'    => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_kitchens.required'  => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'num_of_bathrooms.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'description.required'      => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'images.required'           => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'video_url.url'             => MyHelpers::admin_trans(auth()->user()->id, 'The value must be valid url'),*/
          /*  'latitude.required'         => 'إحداثيات العقار على الخريطة مطلوبة',*/
        ];
        if ($request->fixed_price != null) {
            $request->merge([
                'fixed_price' => str_replace(',', '', $request->fixed_price),
            ]);
        }
        if ($request->min_price != null) {
            $request->merge([
                'min_price' => str_replace(',', '', $request->min_price),
            ]);
        }
        if ($request->max_price != null) {
            $request->merge([
                'max_price' => str_replace(',', '', $request->max_price),
            ]);
        }
        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->passes()) {
            $property = Property::find($id);
            $input = $request->except(['images']);
            $input['lat'] = $request->latitude;
            $input['lng'] = $request->longitude;
            $input['address'] = $request->address;
            if ($request->area_id != $property->area_id) {
               /* $rule = [
                    'city_id' => 'required',
                ];
                $this->validate($request, $rule, $customMessages);*/
                $input['district_id'] = null;
            }
            // update property
            $update = $property->update($input);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $name = Str::random(32).'.'.$image->getClientOriginalExtension();
                    $path = 'storage/properties/';
                    ImageHelper::save($property, $path.$name);
                    ImageHelper::upload($image, $name, $path);
                }
            }
            if ($update) {
                Property::find($id)->update(['last_notification_date' => null]);
            }
            return response()->json([
                'success' => true,
                'message' => 'تم تعديل العقار',
            ]);
        }
        return response()->json(['errors' => $validator->errors(), 'message' => 'لديك بعض الحقول ناقصة']);
    }

    /*
     * For Sending Notification
     */

    public function destroy($id)
    {
        // destroy property
        $property = Property::find($id);
        if ($property->delete()) {
            return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Deleted successfully'), 'type' => 'success']);
        }
        return response()->json(['msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'type' => 'error']);
    }

    public function status(Request $request)
    {
        $property = Property::find($request->id);
        if ($request->status == 'true') {
            $status = 1;
        }
        else {
            $status = 0;
        }
        $property->update([
            'is_published' => $status,
        ]);
        return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Edited successfully'), 'type' => 'success'], 201);
    }

    public function areas(Request $request){
        $output = '';
        $data = Area::all();

        if ($request->has("user_id")){
            $userId = $request->user_id;
        }else{
            $userId = auth()->id();
        }
        $user = \App\Models\User::with("areas")->find($userId);
        foreach ($data as $row) {
            $selected  =in_array($row->id,$user->areas->pluck("value")->toArray()) ? 'selected' : '';
            if ($user->role == 6 && $request->has("profile")){
                $output .= '<option '.$selected.' value="'.$row->id.'" >'.$row->value.'</option>';
            }else{
                $output .= '<option value="'.$row->id.'">'.$row->value.'</option>';
            }
        }
        echo $output;
    }
    public function cities(Request $request)
    {

        $output = '';
        if ($request->has("user_id")){
            $userId = $request->user_id;
        }else{
            $userId = auth()->id();
        }

        if (!is_array($request->id)  && $request->has("id") ){
            $request->merge([
                "id" => [$request->id]
            ]);
            $output.='<option selected disabled>أختار المدينة</option>';
        }

        $user = \App\Models\User::with("cities","areas")->find($userId);
        if ($user->role == 6 && $request->has("profile") && $user->areas->count() != 0){
            $data = City::whereIn('area_id', $user->areas->pluck("value")->toArray())->get();
        }else{

            $data = City::whereIn('area_id', $request->id ?? [])->get();

        }

        foreach ($data as $row) {
            $selected  =in_array($row->id,$user->cities->pluck("value")->toArray()) ? 'selected' : '';
            if ($user->role == 6 && $request->has("profile")){
                $output .= '<option '.$selected.' value="'.$row->id.'" >'.$row->value.'</option>';
            }else{
                $output .= '<option value="'.$row->id.'">'.$row->value.'</option>';
            }
        }
        echo $output;
    }

    public function districts(Request $request)
    {
        $output = '';

        if ($request->has("user_id")){
            $userId = $request->user_id;
        }else{
            $userId = auth()->id();
        }
        $user = \App\Models\User::with("districts","cities")->find($userId);

        if (!is_array($request->id) && $request->has("id") ){

            $request->merge([
                "id" => [$request->id]
            ]);
            if ( $user->districts()->count() == 0){
                $output.='<option selected disabled>أختار الحي</option>';

            }

        }
        if ($user->role == 6 && $request->has("profile")  && $user->cities->count() != 0){
            $data = District::whereIn('city_id', $user->cities->pluck("value")->toArray())->get();
        }else{
            $data = District::whereIn('city_id', $request->id ?? [])->get();
        }
        foreach ($data as $row) {
            $selected  =in_array($row->id,$user->districts->pluck("value")->toArray()) ? 'selected' : '';
            if ($user->role == 6 && $request->has("profile")){
                $output .= '<option '.$selected.' value="'.$row->id.'" >'.$row->value.'</option>';
            }else{
                $output .= '<option value="'.$row->id.'">'.$row->value.'</option>';
            }
        }
        echo $output;
    }

    public function archPropertyArr(Request $request)
    {
        $page = Property::whereIn('id', $request->ids);
        $page->delete();

        return response()->json([
            'message' => 'تم مسح العناصر بنجاح',
            'type'    => 'success',
        ]);
    }
}

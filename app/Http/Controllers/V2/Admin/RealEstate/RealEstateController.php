<?php

namespace App\Http\Controllers\V2\Admin\RealEstate;

use App\Helpers\MyHelpers;
use App\Http\Controllers\Controller;
use App\Models\RealEstate;
use App\Models\User;
use App\Property;
use App\PropertyRequest as Model;
use App\Property as PropertyModel;
use App\realType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class RealEstateController extends Controller
{
    /** load real estate page (request by customer)
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customerRealEstatesPage()
    {
        $propertyRequests=Model::all();
        $agents=[];
        $realTypes=[];
        foreach ($propertyRequests as $propertyRequest) {

            /*if($propertyRequest->customer->request->user){
                $agents[]=$propertyRequest->customer->request->user;
            }

            if($propertyRequest->propertyType){
                $realTypes[]=$propertyRequest->propertyType;
            }*/

            if($propertyRequest->city){
                $cities[]=$propertyRequest->city;
            }
            if($propertyRequest->customer()->exists() && $propertyRequest->customer->request()->exists() && $propertyRequest->customer->request->user){
                $agents[]=$propertyRequest->customer->request->user;
            }
            if($propertyRequest->propertyType()->exists())
            $realTypes[]=$propertyRequest->propertyType;
        }
        $uniqueAgents=array_unique($agents);
        $uniqueRealTypes=array_unique($realTypes);
        $uniqueCities=array_unique($cities);
        return view('V2.Admin.RealEstates.Customer.index',compact('uniqueRealTypes','uniqueAgents','uniqueCities'));
    }

    /** real estate data table
     * @return mixed
     * @throws \Exception
     */
    public function customerRealEstatesData()
    {
        $request=request();

        $data = Model::query()
            ->with(['customer','propertyType','city','district','area'])
            ->latest()
            ->get();

        if($request->get('uniqueRealTypes'))
        {
            $data=$data->where('property_type_id',$request->get('uniqueRealTypes'));
        }

        if($request->get('uniqueAgents'))
        {
            $data=$data->where('customer.request.user.id',$request->get('uniqueAgents'));
        }

        if($request->get('uniqueCities'))
        {
            $data=$data->where('city.id',$request->get('uniqueCities'));
        }


        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $data = '<div class="tableAdminOption">';

                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                    <a href="'.route('admin.customerShowRealEstatesData', $row->id).'"> <i class="fas fa-eye"></i></a>
                                </span>';

                $data = $data.'</div>';
                return $data;
            })
            ->editColumn('agent_name', function ($data) {
                return $data->customer->request->user->name ?? '';
            })
            ->editColumn('value', function ($data) {
                return $data->propertyType->value ?? '';
            })
            ->editColumn('price', function ($data) {
                return ' من ' . $data->min_price . ' إلي  ' . $data->max_price;
            })
            ->editColumn('city.value', function ($data) {
                return $data->city->value ?? '';
            })
            ->editColumn('district.value', function ($data) {
                return $data->district->value ?? '';
            })
            ->editColumn('area.value', function ($data) {
                return $data->area->value ?? '';
            })
            ->make(true);
    }

    public function customerShowRealEstatesData($id)
    {
        $realestate=Model::find($id);
        return view('V2.Admin.RealEstates.Customer.show',compact('realestate'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collaboratorRealEstates()
    {
        $propertyRequests=Model::all();
        $cols=[];
        $realTypes=[];
        foreach ($propertyRequests as $propertyRequest) {
            if($propertyRequest->city){
                $cities[]=$propertyRequest->city;
            }

            if($propertyRequest->propertyType()->exists())
            $realTypes[]=$propertyRequest->propertyType;
        }
        $collaborators = User::where('role', '6')->where('status', '1')->get();
        // $collaborators=array_unique($cols);
        $uniqueRealTypes=array_unique($realTypes);
        $uniqueCities=array_unique($cities);
        return view('V2.Admin.RealEstates.Collaborator.index', compact('collaborators', 'uniqueRealTypes', 'uniqueCities'));
    }
    /**
     * @return mixed
     * @throws \Exception
     */
    public function collaboratorRealEstatesData(Request $request)
    {
        $data = PropertyModel::query()
            ->with(['creator','type','city','area','district'])
            ->whereHas('creator', function (Builder $query){
                $query->where('role',6);
            });
            if($request->get('uniqueRealTypes'))
            {
                $data=$data->where('type_id',$request->get('uniqueRealTypes'));
            }

            if($request->get('collaborator'))
            {
                $data=$data->where('creator_id',$request->get('collaborator'));
            }

            if($request->get('uniqueCities'))
            {
                $data=$data->where('city_id',$request->get('uniqueCities'));
            }
        return DataTables::of($data)
            ->editColumn('collaborator_name', function ($data) {
                return $data->creator->name ?? '';
            })
            ->editColumn('property_type', function ($data) {
                return $data->type->value ?? '';
            })
            ->editColumn('is_published', function ($data) {
                if ($data->is_published != 0)
                    return "منشور";
                return "معطل";
            })
            ->editColumn('city', function ($data) {
                return $data->city->value ?? '';
            })
            ->editColumn('address', function ($data) {
                return Str::limit( $data->address,35);
            })
            ->editColumn('description', function ($data) {
                return Str::limit( $data->description,35);
            })
            ->editColumn('district', function ($data) {
                return $data->district->value ?? '';
            })->editColumn('action', function ($property) {
                $data = '<div class="table-data-feature">';
                $show = route('property.show', [@$property->id]);

                $data = $data.' <a href="'.$show.'" style="margin:auto 5px;">
                    <button class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'show_property_details').'">
                                    <i class="fa fa-eye"></i>
                                </button> </a> ';

                $data = $data.'</div>';
                return $data;
            })
            ->make(true);
    }

    public function collaboratorRealEstatesDataShow($id)
    {

        $property = Property::query()->with(['city','areaName','district'])->findOrFail($id);
        return view('Proper.Property.show', compact('property'));

    }

}

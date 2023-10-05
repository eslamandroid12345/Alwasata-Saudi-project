<?php

namespace App\Http\Controllers;

use App\Image;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ImageHelper;
use League\Flysystem\Exception;
use MyHelpers;
use View;

class ImageController extends Controller
{
    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'             => ['layouts.customer_app'],
            'App\Composers\ActivityComposer'         => ['layouts.customer_app'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }

    public function list($type, $id)
    {

        $images = Image::where('imageable_type', 'App\\'.$type)->where('imageable_id', $id)->get();

        if (request()->ajax()) {
            return Datatables::of($images)
                ->editColumn('created_at', function ($image) {
                    return $image->created_at->format('Y-m-d');
                })
                ->addColumn('path', function ($image) {
                    $path = asset($image->image_path);
                    /* to create Image ID*/
                    $imgID = 'image_'.$image->id;
                    return "<a data-toggle='modal' data-target='#zoom_image' data-id='$image->id'>
<img src='$path' width='200' height='200' class='img-responsive img-fluid' id='$imgID'/></a>";
                })
                ->editColumn('action', function ($image) {
                    $id = $image->id;
                    //                    $edit = route('image.edit',  $image->id) ;
                    $delete = route('image.delete', $image->id);
                    $data = '<div class="table-data-feature">';
                    $data = $data.' <a href="" data-id="'.$id.'" id="zoom_image_btn" data-toggle="modal"  data-target="#zoom_image" style="margin:auto 5px;"> <button class="btn btn-success btn-sm" type="button" >
                                    <i class="fa fa-search-plus"></i>
                                </button> </a> ';
                    $data = $data.' <a href="" data-id="'.$id.'" data-toggle="modal"  data-target="#edit_image" style="margin:auto 5px;"> <button class="btn btn-info btn-sm" type="button" >
                                    <i class="fa fa-edit"></i>
                                </button> </a> ';
                    $data = $data.' <a style="margin:auto 5px;" class="" onclick="deleteDate('.$image->id.')"  > <button class="btn btn-danger btn-sm" id="delete" data-id="'.$image->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                                    <i class="fa fa-trash"></i>
                                </button> </a> ';

                    $data = $data.'</div>';
                    return $data;
                })->escapeColumns([])->rawColumns(['action'])
                ->make(true);

        }
    }

    public function update(Request $request)
    {
        $current_image = Image::find($request->id);
        unlink($current_image->image_path); //delete current image from public folder

        if ($request->hasFile('image')) {
            $new_image = $request->image;
            $name = Str::random(32).'.'.$new_image->getClientOriginalExtension();
            $path = 'storage/properties/';
            $current_image->update([
                'image_path' => $path.$name,
            ]);
            ImageHelper::upload($new_image, $name, $path);
        }
        return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Edited successfully'), 'type' => 'success']);
    }

    // Task-14
    public function delete($id)
    {
        $image = Image::find($id);
        try {
            unlink($image->image_path);
        }
        catch (Exception $e) {

        }
        $image->delete(); //delete current image from DB
        return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Deleted successfully'), 'type' => 'warning']);
    }
}

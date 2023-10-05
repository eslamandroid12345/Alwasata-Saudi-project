<?php

namespace App\Http\Controllers\Proper;

use App\customer;
use App\Http\Controllers\Controller;
use App\Model\RequestSearching;
use App\PropertyRequest;
use App\request as TamweelReq;
use App\User;
use App\user_collaborator;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class PropertyRequestController extends Controller
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
        if (auth()->user()->role == '7') {
            $requests = PropertyRequest::with('responsible', 'customer', 'property', 'classification', 'classification_collaborator')->get();
        }
        else {
            $requests = PropertyRequest::with('responsible', 'customer', 'property', 'classification', 'classification_collaborator')->where('responsible_id', auth()->id())->get();
        }

        if (request()->ajax()) {
            return Datatables::of($requests)
                ->addColumn('customer_name', function ($request) {
                    return @$request->customer->name;
                })
                ->addColumn('customer_mobile', function ($request) {
                    return @$request->customer->mobile;
                })
                ->addColumn('property_info', function ($request) {
                    switch (@$request->property->creator->role) {
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
                    $name = @$request->property->creator->name;
                    return MyHelpers::admin_trans(auth()->user()->id, 'property num').' :# '.@$request->property->id.
                        '<br>'.MyHelpers::admin_trans(auth()->user()->id, 'property source')." : ".@$role.' - '.@$name;
                })
                ->addColumn('request_responsible', function ($request) {
                    switch (@$request->responsible->role) {
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
                    $name = @$request->responsible->name;
                    return @$role.' - '.@$name;
                })
                ->addColumn('classification', function ($request) {
                    return (auth()->user()->role == '6') ? @$request->classification_collaborator->value : @$request->classification->value;
                })
                ->addColumn('status', function ($request) {
                    switch (@$request->statusReq) {
                        case '0':
                            $status = MyHelpers::admin_trans(auth()->user()->id, 'new');
                            break;
                        case '1':
                            $status = MyHelpers::admin_trans(auth()->user()->id, 'open');
                            break;
                        case '2':
                            $status = MyHelpers::admin_trans(auth()->user()->id, 'canceled');
                            break;
                        case '3':
                            $status = MyHelpers::admin_trans(auth()->user()->id, 'completed');
                            break;
                        case '4':
                            $status = MyHelpers::admin_trans(auth()->user()->id, 'convertedToTamweel');
                            break;
                        default:
                            break;
                    }
                    return @$status;
                })
                ->editColumn('comment', function ($request) {
                    switch (auth()->user()->role) {
                        case '6':
                            $comment = @$request->collaborator_comment;
                            break;
                        case '7':
                            $comment = (!is_null(@$request->collaborator_comment)) ? @$request->collaborator_comment : @$request->comment;
                            break;
                        default:
                            $comment = @$request->comment;
                    }
                    return $comment;
                })
                ->editColumn('action', function ($row) {
                    $id = @$row->id;
                    $customer = @$row->customer->id;
                    if (auth()->user()->role == '6') {
                        $comment = @$row->collaborator_comment;
                        $class_id = @$row->class_id_propertyagent;
                    }
                    else {
                        $comment = @$row->comment;
                        $class_id = @$row->class_id_collaborator;
                    }
                    $data = '<div class="table-data-feature">';
                    $formID = 'customerChat'.$row->id;
                    $data = $data.'  <form method="post" action="'.route('newChat').'" id="'.$formID.'" >
                                            <input type="hidden" name="_token" value="'.csrf_token().'" />
                                            <input type="hidden" name="receivers[]" value="'.$customer.'" />
                                            <input type="hidden" name="receiver_model_type" value="App\customer" />
                                            <a style="margin:auto 10px;" onclick="$(this).submit()"> <button class="item" type="submit" >
                                                <i class="zmdi zmdi-email-open"></i>
                                            </button> </a>
                                    </form>';
                    if (auth()->user()->role != '7') {
                        $data = $data.' <a style="margin:auto 5px;" data-toggle="modal" data-id="'.$id.'" data-comment="'.$comment.'" data-classification="'.$class_id.'" data-status="'.$row->statusReq.'"   data-target="#edit_property_request"> <button class="item" type="button" >
                                        <i class="zmdi zmdi-edit"></i>
                                    </button> </a> ';
                    }
                    if (@$row->statusReq != 4) {

                        $data = $data."<a onclick='convert_to_tamweel($id)'  style='margin:auto 5px;'> <button class='item btn btn-default'  title='".MyHelpers::admin_trans(auth()->user()->id, 'convertedToTamweel')."'>
                                    <i class='zmdi zmdi-transform'></i></a>
                                </button></a>";
                    }
                    return $data;
                })->escapeColumns([])->rawColumns(['action'])
                ->make(true);

        }
        return view('Proper.PropertyRequest.index');
    }

    public function update(Request $request)
    {
        $req = PropertyRequest::findOrFail($request->id);
        if (auth()->user()->role == '6') {
            $comment_column = 'collaborator_comment';
            $class_column = 'class_id_collaborator';
        }
        else {
            $comment_column = 'comment';
            $class_column = 'class_id_propertyagent';
        }
        $req->update([
            'statusReq'     => $request->statusReq,
            $class_column   => $request->class_id,
            $comment_column => $request->comment,
        ]);
        return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Edited successfully'), 'type' => 'success']);
    }

    public function convertPropertyRequestToTamweelRequest(Request $request)
    {
        $req = PropertyRequest::whereId($request->id)->with('property', 'customer')->first();
        $customerID = $req->customer_id;
        $reqdate = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $joinID = customer::where('id', $customerID)->joint->id;
        $realID = customer::where('id', $customerID)->real_estat->id;
        $funID = customer::where('id', $customerID)->funding->id;
        $searching_id = RequestSearching::create()->id;

        if ($req->property->creator->role != '6') {
            $user_id = MyHelpers::findNextAgent();
        }
        else {
            $agents = user_collaborator::where('collaborato_id', auth()->id())->pluck('user_id')->toArray();
            $users = User::whereIn('id', $agents)->pluck('id')->toArray();
            $user_id = MyHelpers::findNextCollaboratorAgent($users);
        }

        $tamweel_request = TamweelReq::create(
            [
                'statusReq'    => 0,
                'customer_id'  => $customerID,
                'user_id'      => $user_id,
                'source'       => 'طلب عقاري',
                'req_date'     => $reqdate,
                'created_at'   => (Carbon::now('Asia/Riyadh')),
                'agent_date'   => (Carbon::now('Asia/Riyadh')),
                'joint_id'     => $joinID,
                'real_id'      => $realID,
                'searching_id' => $searching_id,
                'fun_id'       => $funID,
            ]
        );

        if ($tamweel_request) {
            $req->update(['statusReq' => 4]); // update status to coverted to tamweel request
            return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Converted successfully'), 'type' => 'success'], 201);
        }
        else {
            return response()->json(['msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'type' => 'danger'], 422);
        }

    }
}

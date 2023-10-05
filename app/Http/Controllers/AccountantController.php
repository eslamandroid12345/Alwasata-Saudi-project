<?php

namespace App\Http\Controllers;

use App\funding_source;
use App\User;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

//to take date

class AccountantController extends Controller
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

    public function wsataAccountingUnderReport()
    {

        $role = auth()->user()->role;
        $salesAgents = User::where('role', 0)->get();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [12, 14, 15, 32]);
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 23);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'users.name as user_name', 'customers.name as cust_name', 'customers.mobile', 'customers.salary_id', 'real_estats.type as realtype', 'real_estats.name as realname', 'real_estats.mobile as realmobile', 'real_estats.city as realcity', 'real_estats.pursuit',
                'prepayments.mortCost', 'prepayments.profCost', 'prepayments.payStatus', 'fundings.funding_source')
            ->orderBy('req_date', 'DESC')
            ->count();

        /*     $editCoulmns = editCoulmnsSettings::where('tableName', 'wsataAccountingReport')
            ->where('user_id', auth()->user()->id)->get();
            */

        $worke_sources = WorkSource::all();

        if ($role == 7 || $role == 4 || ($role == 8 && auth()->user()->accountant_type == 1)) {
            return view('Accountant.Reports.wsataAccountingUnderReport', compact(
                'requests',
                'collaborators',
                'salesAgents',
                'founding_sources',
                'worke_sources'
            //'realTypes',
            // 'editCoulmns',
            ));
        }

        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function wsataAccountingUnderReport_datatable(Request $request)
    {

        $requests = DB::table('requests')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [12, 14, 15, 32]);
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 23);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                'prepayments.mortCost',
                //'users.id as to_date', // just for pass ^^
                // 'users.email as action', // just for pass ^^
                'customers.name as cust_name',
                'customers.mobile',
                'real_estats.type as realtype',
                'real_estats.cost as real_cost',
                'real_estats.value_added',
                'real_estats.assment_fees',
                'real_estats.collobreator_cost',
                'real_estats.net',
                'real_estats.pursuit',
                'real_estats.mortgage_value as mortgage_value',
                'fundings.funding_source'
            )
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {

            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('accountcomment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('accountcomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            if (auth()->user()->role == 8) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }

                if ($row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                    $data = $data.'<span class="item pointer darkBg"  id="approve" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Approve req').'">
                                    <a href="'.route('accountant.approveWsataReq', $row->id).'"> <i class="fas fa-check"></i></a></a>
                                </span>';
                }

                elseif ($row->is_approved_by_wsata_acc == 1 && $row->statusReq != 26 && $row->statusReq != 16) {
                    $data = $data.'<span class="item pointer warningBg" id="Reapprove" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Reapprove').'">
                                     <a href="'.route('accountant.reApproveWsataReq', $row->id).'">   <i class="fas fa-undo"></i></a></a>
                                </span>';
                }
            }
            elseif (auth()->user()->role == 7) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }
            else {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('collaborator_id', function ($row) {

            $data = null;

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $collInfo->name;
                }
            }
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;
            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('accountcomment', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<textarea title="'.$row->accountcomment.'"  id="reqComment'.$row->id.'" class="textarea"  onblur="savecomm('.$row->id.')" >'.$row->accountcomment.' </textarea>';
            }

            else {
                $data = '<textarea title="'.$row->accountcomment.'"  disabled id="reqComment'.$row->id.'" class="textarea" >'.$row->accountcomment.' </textarea>';
            }

            return $data;
        })->editColumn('collobreator_cost', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="collobreator_cost'.$row->id.'"  contenteditable="true" onblur="savecollcost('.$row->id.') " >'.$row->collobreator_cost.' </p>';
            }

            else {
                $data = '<p  id="collobreator_cost'.$row->id.'"  >'.$row->collobreator_cost.' </p>';
            }

            return $data;
        })->editColumn('assment_fees', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="assment_fees'.$row->id.'"  contenteditable="true" onblur="saveassment('.$row->id.')" >'.$row->assment_fees.' </p>';
            }

            else {
                $data = '<p  id="assment_fees'.$row->id.'" >'.$row->assment_fees.' </p>';
            }

            return $data;
        })->editColumn('cust_name', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="cust_name'.$row->id.'"  contenteditable="true" onblur="savecustname('.$row->id.')" >'.$row->cust_name.' </p>';
            }

            else {
                $data = '<p  id="cust_name'.$row->id.'" >'.$row->cust_name.' </p>';
            }

            return $data;
        })->editColumn('value_added', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="value_added'.$row->id.'"  contenteditable="true" onblur="saveit('.$row->id.') " >'.$row->value_added.' </p>';
            }

            else {
                $data = '<p  id="value_added'.$row->id.'"   >'.$row->value_added.' </p>';
            }

            return $data;
        })->editColumn('pursuit', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_wsata_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="pursuit'.$row->id.'"  contenteditable="true" onblur="savepursuit('.$row->id.')" >'.$row->pursuit.' </p>';
            }

            else {
                $data = '<p  id="pursuit'.$row->id.'"   >'.$row->pursuit.' </p>';
            }

            return $data;
        })->editColumn('net', function ($row) {

            $data = '<p  id="net'.$row->id.'">'.$row->net.' </p>';

            return $data;
        })->editColumn('funding_source', function ($row) {

            $funding_sour = DB::table('funding_sources')->where('id', $row->funding_source)->first();

            if ($funding_sour != null) {
                return $funding_sour->value;
            }
            else {
                return $row->funding_source;
            }
        })->editColumn('realtype', function ($row) {

            $realType = DB::table('real_types')->where('id', $row->realtype)
                ->first();

            if (!empty($realType)) {
                return $realType->value;
            }
            else {
                return $row->realtype;
            }
        })->editColumn('is_approved_by_wsata_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_wsata_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->editColumn('is_approved_by_tsaheel_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_tsaheel_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->rawColumns(['is_approved_by_wsata_acc', 'is_approved_by_tsaheel_acc', 'action', 'pursuit', 'cust_name', 'net', 'accountcomment', 'assment_fees', 'value_added', 'collobreator_cost'])
            ->make(true);
    }

    public function wsataAccountingReport()
    {

        $role = auth()->user()->role;
        $salesAgents = User::where('role', 0)->get();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [16, 35]);
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted

                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 26);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select('requests.*', 'users.name as user_name', 'customers.name as cust_name', 'customers.mobile', 'customers.salary_id', 'real_estats.type as realtype', 'real_estats.name as realname', 'real_estats.mobile as realmobile', 'real_estats.city as realcity', 'real_estats.pursuit',
                'prepayments.mortCost', 'prepayments.profCost', 'prepayments.payStatus', 'fundings.funding_source')
            ->orderBy('req_date', 'DESC')
            ->count();

        /*     $editCoulmns = editCoulmnsSettings::where('tableName', 'wsataAccountingReport')
            ->where('user_id', auth()->user()->id)->get();
            */

        $worke_sources = WorkSource::all();

        if ($role == 7 || $role == 4 || ($role == 8 && auth()->user()->accountant_type == 1)) {
            return view('Accountant.Reports.wsataAccountingReport', compact(
                'requests',
                'collaborators',
                'salesAgents',
                'founding_sources',
                'worke_sources'
            //'realTypes',
            // 'editCoulmns',
            ));
        }

        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function wsataAccountingReport_datatable(Request $request)
    {

        $requests = DB::table('requests')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [16, 35]);
                    $query->whereIn('requests.type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted

                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 26);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                'prepayments.mortCost',
                //'users.id as to_date', // just for pass ^^
                // 'users.email as action', // just for pass ^^
                'customers.name as cust_name',
                'customers.mobile',
                'real_estats.type as realtype',
                'real_estats.cost as real_cost',
                'real_estats.value_added',
                'real_estats.assment_fees',
                'real_estats.collobreator_cost',
                'real_estats.net',
                'real_estats.pursuit',
                'real_estats.mortgage_value as mortgage_value',
                'fundings.funding_source'
            )
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {

            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('accountcomment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('accountcomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            if (auth()->user()->role == 8) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }

            }
            elseif (auth()->user()->role == 7) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }
            else {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('collaborator_id', function ($row) {

            $data = null;

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $collInfo->name;
                }
            }
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;
            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('accountcomment', function ($row) {

            $data = '<textarea title="'.$row->accountcomment.'"  disabled id="reqComment'.$row->id.'" class="textarea" >'.$row->accountcomment.' </textarea>';

            return $data;
        })->editColumn('collobreator_cost', function ($row) {

            $data = '<p  id="collobreator_cost'.$row->id.'"  >'.$row->collobreator_cost.' </p>';

            return $data;
        })->editColumn('assment_fees', function ($row) {

            $data = '<p  id="assment_fees'.$row->id.'" >'.$row->assment_fees.' </p>';

            return $data;
        })->editColumn('cust_name', function ($row) {

            $data = '<p  id="cust_name'.$row->id.'" >'.$row->cust_name.' </p>';

            return $data;
        })->editColumn('value_added', function ($row) {

            $data = '<p  id="value_added'.$row->id.'"   >'.$row->value_added.' </p>';

            return $data;
        })->editColumn('pursuit', function ($row) {

            $data = '<p  id="pursuit'.$row->id.'"   >'.$row->pursuit.' </p>';

            return $data;
        })->editColumn('net', function ($row) {

            $data = '<p  id="net'.$row->id.'">'.$row->net.' </p>';

            return $data;
        })->editColumn('funding_source', function ($row) {

            $funding_sour = DB::table('funding_sources')->where('id', $row->funding_source)->first();

            if ($funding_sour != null) {
                return $funding_sour->value;
            }
            else {
                return $row->funding_source;
            }
        })->editColumn('realtype', function ($row) {

            $realType = DB::table('real_types')->where('id', $row->realtype)
                ->first();

            if (!empty($realType)) {
                return $realType->value;
            }
            else {
                return $row->realtype;
            }
        })->editColumn('is_approved_by_wsata_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_wsata_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->editColumn('is_approved_by_tsaheel_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_tsaheel_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->rawColumns(['is_approved_by_wsata_acc', 'is_approved_by_tsaheel_acc', 'action', 'pursuit', 'cust_name', 'net', 'accountcomment', 'assment_fees', 'value_added', 'collobreator_cost'])
            ->make(true);
    }

    public function updateNet(Request $request)
    {

        $req = DB::table('requests')
            ->where('requests.id', $request->id)
            ->join('real_estats', 'real_estats.id', 'requests.real_id')
            ->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['net' => ($req->pursuit - ($req->assment_fees + $req->collobreator_cost + $req->value_added))]);

        $newRequest = DB::table('real_estats')->where('id', $req->real_id)
            ->first();

        return response()->json(['status' => $updateReq, 'newData' => $newRequest->net]);
    }

    public function updateValueAdded(Request $request)
    {

        if ($request->value_added != null) {
            $this->records($request->id, 'value_added', $request->value_added);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['value_added' => $request->value_added]);

        return response($updateReq);
    }

    public function records($reqID, $coloum, $value)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')
            ->where('req_id', '=', $reqID)
            ->where('colum', '=', $coloum)
            ->max('id'); //to retrive id of last record update of comment

        if ($lastUpdate != null) {
            $rowOfLastUpdate = DB::table('req_records')->where('id', '=', $lastUpdate)->first();
        } //we get here the row of this id
        //

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        if ($lastUpdate == null && ($value != null)) {
            DB::table('req_records')->insert([
                'colum'          => $coloum,
                'user_id'        => (auth()->user()->id),
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => $userSwitch,
            ]);
        }

        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => (auth()->user()->id),
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => $userSwitch,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function updatepursuit(Request $request)
    {

        if ($request->pursuit != null) {
            $this->records($request->id, 'realPursuit', $request->pursuit);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['pursuit' => $request->pursuit]);

        return response($updateReq);
    }

    public function updaterequestProfit(Request $request)
    {

        if ($request->request_profit != null) {
            $this->records($request->id, 'request_profit', $request->request_profit);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('prepayments')->where('id', $req->payment_id)
            ->update(['request_profit' => $request->request_profit]);

        return response($updateReq);
    }

    public function updatemarktingCompany(Request $request)
    {

        if ($request->marckting_company != null) {
            $this->records($request->id, 'marckting_company', $request->marckting_company);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['marckting_company' => $request->marckting_company]);

        return response($request2);
    }

    public function updateFunder(Request $request)
    {

        if ($request->funder != null) {
            $this->records($request->id, 'funder', $request->funder);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['funder' => $request->funder]);

        return response($request2);
    }

    public function updateMarkter(Request $request)
    {

        if ($request->markter != null) {
            $this->records($request->id, 'markter', $request->markter);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['markter' => $request->markter]);

        return response($request2);
    }

    public function updatenatureRequest(Request $request)
    {

        if ($request->natureRequest != null) {
            $this->records($request->id, 'natureRequest', $request->natureRequest);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['natureRequest' => $request->natureRequest]);

        return response($request2);
    }

    public function updateStartDate(Request $request)
    {

        $reqInfo = DB::table('requests')->where('id', $request->id)->first();

        //GET PREVIOUS TO_DATE
        $date = Carbon::parse($reqInfo->recived_date_report_mor);
        if ($reqInfo->counter_report_mor != null) {
            $to_date = Carbon::parse($date->addDays($reqInfo->counter_report_mor))->format('Y-m-d');
        }
        else {
            $to_date = '';
        }

        if ($request->recived_date_report_mor != null) {
            $this->records($request->id, 'recived_date_report_mor', $request->recived_date_report_mor);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['recived_date_report_mor' => $request->recived_date_report_mor]);

        if ($reqInfo->counter_report_mor == null) {

            $date = Carbon::parse($request->recived_date_report_mor);
            $now = Carbon::now();
            $counter = $date->diffInDays($now);

            DB::table('requests')->where('id', $request->id)
                ->update([
                    'counter_report_mor' => $counter,
                ]);

            $this->records($request->id, 'counter_report_mor', $counter);
        }

        if ($reqInfo->isUnderProcMor == 1) {

            $date = Carbon::parse($request->recived_date_report_mor);
            $now = Carbon::now();
            $counter = $date->diffInDays($now);

            DB::table('requests')->where('id', $request->id)
                ->update([
                    'counter_report_mor' => $counter,
                ]);

            $this->records($request->id, 'counter_report_mor', $counter);
        }
        else {

            $start = Carbon::parse($reqInfo->recived_date_report_mor);
            $end = Carbon::parse($to_date);
            $counter = $start->diffInDays($end);
        }

        return response()->json(['request' => $request2, 'counter' => $counter]);
    }

    public function updateEndDate(Request $request)
    {
        $reqInfo = DB::table('requests')->where('id', $request->id)->first();
        $start = Carbon::parse($reqInfo->recived_date_report_mor);
        $end = $request->to_date;
        $counter = $start->diffInDays($end);

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['counter_report_mor' => $counter]);

        if ($request2) {
            $this->records($request->id, 'counter_report_mor', $counter);
        }

        return response()->json(['request' => $request2, 'counter' => $counter]);
    }

    public function updateagreementCost(Request $request)
    {

        if ($request->agreement_cost != null) {
            $this->records($request->id, 'agreement_cost', $request->agreement_cost);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('prepayments')->where('id', $req->payment_id)
            ->update(['agreement_cost' => $request->agreement_cost]);

        return response($updateReq);
    }

    public function updateAccountStatus(Request $request)
    {

        if ($request->account_status != null) {
            $this->records($request->id, 'account_status', $request->account_status);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('prepayments')->where('id', $req->payment_id)
            ->update(['account_status' => $request->account_status]);

        return response($updateReq);
    }

    public function updateAccountProfitPresentage(Request $request)
    {

        if ($request->account_profit_presntage != null) {
            $this->records($request->id, 'account_profit_presntage', $request->account_profit_presntage);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('prepayments')->where('id', $req->payment_id)
            ->update(['account_profit_presntage' => $request->account_profit_presntage]);

        return response($updateReq);
    }

    public function updatemortCost(Request $request)
    {

        if ($request->mortCost != null) {
            $this->records($request->id, 'mortCost', $request->mortCost);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('prepayments')->where('id', $req->payment_id)
            ->update(['mortCost' => $request->mortCost]);

        return response($updateReq);
    }

    public function updateTsaheelMortgageValue(Request $request)
    {

        if ($request->tsaheel_mortgage_value != null) {
            $this->records($request->id, 'tsaheel_mortgage_value', $request->tsaheel_mortgage_value);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['tsaheel_mortgage_value' => $request->tsaheel_mortgage_value]);

        return response($updateReq);
    }

    public function updateMortgageValue(Request $request)
    {

        if ($request->mortgage_value != null) {
            $this->records($request->id, 'mortgage_value', $request->mortgage_value);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['mortgage_value' => $request->mortgage_value]);

        return response($updateReq);
    }

    public function updateassmentFees(Request $request)
    {

        if ($request->assment_fees != null) {
            $this->records($request->id, 'assment_fees', $request->assment_fees);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['assment_fees' => $request->assment_fees]);

        return response($updateReq);
    }

    public function updatecustName(Request $request)
    {

        if ($request->cust_name != null) {
            $this->records($request->id, 'customerName', $request->cust_name);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('customers')->where('id', $req->customer_id)
            ->update(['name' => $request->cust_name]);

        return response($updateReq);
    }

    public function updatecollobreatorCost(Request $request)
    {

        if ($request->collobreator_cost != null) {
            $this->records($request->id, 'collobreator_cost', $request->collobreator_cost);
        }

        $req = DB::table('requests')->where('id', $request->id)->first();

        $updateReq = DB::table('real_estats')->where('id', $req->real_id)
            ->update(['collobreator_cost' => $request->collobreator_cost]);

        return response($updateReq);
    }

    public function updateComm(Request $request)
    {

        if ($request->reqComm != null) {
            $this->records($request->id, 'accountcomment', $request->reqComm);
        }

        $request2 = DB::table('requests')->where('id', $request->id)
            ->update(['accountcomment' => $request->reqComm]);

        return response()->json(['status' => $request2, 'newComm' => $request->reqComm]);
    }

    public function tsaheelAccountingUnderReport()
    {

        $role = auth()->user()->role;
        $salesAgents = User::where('role', 0)->get();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            ->where(function ($query) {
                /*
                $query->where(function ($query) {
                    $query->where('requests.statusReq', 9);
                    $query->where('requests.type', 'رهن');
                    $query->where('requests.isUnderProcMor', 1);
                });

                */

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [12, 14, 15, 32]);
                    $query->whereIn('requests.type', ['شراء-دفعة', 'تساهيل']);

                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 23);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            //->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                'customers.name as cust_name',
                'customers.mobile',
                'customers.salary_id',
                'prepayments.mortCost',
                'prepayments.agreement_cost',
                'prepayments.request_profit',
                'prepayments.account_status',
                'prepayments.account_profit_presntage',
                'fundings.funding_source'
            )
            ->orderBy('req_date', 'DESC')
            ->count();

        /*     $editCoulmns = editCoulmnsSettings::where('tableName', 'wsataAccountingReport')
            ->where('user_id', auth()->user()->id)->get();
            */

        $worke_sources = WorkSource::all();

        if ($role == 7 || $role == 4 || ($role == 8 && auth()->user()->accountant_type == 0)) {
            return view('Accountant.Reports.tsaheelAccountingUnderReport', compact(
                'requests',
                'collaborators',
                'salesAgents',
                'founding_sources',
                'worke_sources'
            //'realTypes',
            // 'editCoulmns',
            ));
        }

        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function tsaheelAccountingUnderReport_datatable(Request $request)
    {

        $requests = DB::table('requests')
            ->where(function ($query) {

                /*
                  $query->where(function ($query) {
                      $query->where('requests.statusReq', 9);
                      $query->where('requests.type', 'رهن');
                      $query->where('requests.isUnderProcMor', 1);
                  });

                  */

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [12, 14, 15, 32]);
                    $query->whereIn('requests.type', ['شراء-دفعة', 'تساهيل']);

                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 23);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            //->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                //'users.id as to_date', // just for pass ^^
                // 'users.email as action', // just for pass ^^
                'customers.name as cust_name',
                'customers.mobile',
                'customers.salary_id',
                'prepayments.mortCost',
                'prepayments.agreement_cost',
                'prepayments.request_profit',
                'prepayments.account_status',
                'prepayments.account_profit_presntage',
                'fundings.funding_source',
                'real_estats.mortgage_value as mortgage_value',
                'real_estats.tsaheel_mortgage_value as tsaheel_mortgage_value',
            )
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {

            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('accountcomment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('accountcomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            if (auth()->user()->role == 8) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }

                if ($row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                    $data = $data.'<span class="item pointer darkBg" id="approve" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Approve req').'">
                                    <a href="'.route('accountant.approveTsaheelReq', $row->id).'"> <i class="fas fa-check"></i></a></a>
                                </span>';
                }

                elseif ($row->is_approved_by_tsaheel_acc == 1 && $row->statusReq != 26 && $row->statusReq != 16) {
                    $data = $data.'<span class="item pointer warningBg" id="Reapprove" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Reapprove').'">
                                     <a href="'.route('accountant.reApproveTsaheelReq', $row->id).'">   <i class="fas fa-undo"></i></a>
                                </span>';
                }
            }
            elseif (auth()->user()->role == 7) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }
            else {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('recived_date_report_mor', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<input style="text-align:center" id="recived_date_report_mor'.$row->id.'" type="date" onchange="saveStartDate('.$row->id.');" contenteditable="true" value="'.$row->recived_date_report_mor.'" >';
            }
            else {
                $data = '<p  style="text-align:center" id="recived_date_report_mor'.$row->id.'" >'.$row->recived_date_report_mor.' </p>';
            }

            return $data;
        })->editColumn('natureRequest', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<select  id="natureRequest'.$row->id.'" style="width:100px;"  name="natureRequest" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); savenatureRequest(this,'.$row->id.')" >';

                $data = $data.'<option value="" selected>---</option>';

                if ($row->natureRequest == "عقد") {
                    $data = $data.'<option value="عقد" selected >عقد</option>';
                }
                else {
                    $data = $data.'<option value="عقد"  >عقد</option>';
                }

                if ($row->natureRequest == "عقد شراء") {
                    $data = $data.'<option value="عقد شراء"  selected >عقد شراء</option>';
                }
                else {
                    $data = $data.'<option value="عقد شراء"   >عقد شراء</option>';
                }

                if ($row->natureRequest == "دفعة رهن") {
                    $data = $data.'<option value="دفعة رهن" selected>دفعة رهن</option>';
                }
                else {
                    $data = $data.'<option value="دفعة رهن" >دفعة رهن</option>';
                }

                if ($row->natureRequest == "دفعة شراء") {
                    $data = $data.'<option value="دفعة شراء" selected>دفعة شراء</option>';
                }
                else {
                    $data = $data.'<option value="دفعة شراء" >دفعة شراء</option>';
                }

                if ($row->natureRequest == "قرض شخصي") {
                    $data = $data.'<option value="قرض شخصي" selected >قرض شخصي</option>';
                }
                else {
                    $data = $data.'<option value="قرض شخصي"  >قرض شخصي</option>';
                }

                if ($row->natureRequest == "مبلغ إضافي") {
                    $data = $data.'<option value="مبلغ إضافي" selected>مبلغ إضافي</option>';
                }
                else {
                    $data = $data.'<option value="مبلغ إضافي" >مبلغ إضافي</option>';
                }

                $data = $data.'</select>';
            }
            else {
                $data = $row->natureRequest;
            }

            return $data;
        })->addColumn('to_date', function ($row) {

            $date = Carbon::parse($row->recived_date_report_mor);
            if ($row->isUnderProcMor != 1 && $row->recived_date_report_mor != null && $row->counter_report_mor != null && auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<input style="text-align:center" id="to_date'.$row->id.'" type="date" onchange="saveEndDate('.$row->id.');" contenteditable="true" value="'.Carbon::parse($date->addDays($row->counter_report_mor))->format('Y-m-d').'" >';
            }
            elseif ($row->isUnderProcMor != 1 && $row->recived_date_report_mor != null && $row->counter_report_mor != null) {
                $data = Carbon::parse($date->addDays($row->counter_report_mor))->format('Y-m-d');
            }
            else {
                $data = '---';
            }

            return $data;
        })->editColumn('counter_report_mor', function ($row) {

            if ($row->isUnderProcMor == 1) {
                $date = Carbon::parse($row->recived_date_report_mor);
                $now = Carbon::now();
                $counter = $date->diffInDays($now);

                if ($row->counter_report_mor != $counter) {

                    DB::table('requests')->where('id', $row->id)
                        ->update([
                            'counter_report_mor' => $counter,
                        ]);

                    $data = '<p  id="counter_report_mor'.$row->id.'" >'.$counter.' يوم'.' </p>';
                }
                else {
                    $data = '<p  id="counter_report_mor'.$row->id.'" >'.$row->counter_report_mor.' يوم'.' </p>';
                }
            }
            else {
                $data = '<p  id="counter_report_mor'.$row->id.'" >'.$row->counter_report_mor.' يوم'.' </p>';
            }

            return $data;
        })->editColumn('agreement_cost', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="agreement_cost'.$row->id.'"  contenteditable="true" onblur="saveAgreement('.$row->id.')" >'.$row->agreement_cost.' </p>';
            }
            else {
                $data = '<p  id="agreement_cost'.$row->id.'" >'.$row->agreement_cost.' </p>';
            }

            return $data;
        })->editColumn('request_profit', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="request_profit'.$row->id.'"  contenteditable="true" onblur="saveProfit('.$row->id.')" >'.$row->request_profit.' </p>';
            }
            else {
                $data = '<p  id="request_profit'.$row->id.'"   >'.$row->request_profit.' </p>';
            }

            return $data;
        })->editColumn('marckting_company', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="marckting_company'.$row->id.'"  contenteditable="true" onblur="saveMarktingCompany('.$row->id.')" >'.$row->marckting_company.' </p>';
            }
            else {
                $data = '<p  id="marckting_company'.$row->id.'"  >'.$row->marckting_company.' </p>';
            }

            return $data;
        })->editColumn('funder', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="funder'.$row->id.'"  contenteditable="true" onblur="saveFunder('.$row->id.')" >'.$row->funder.' </p>';
            }
            else {
                $data = '<p  id="funder'.$row->id.'"  >'.$row->funder.' </p>';
            }

            return $data;
        })->editColumn('mortCost', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="mortCost'.$row->id.'"  contenteditable="true" onblur="savemortCost('.$row->id.')" >'.$row->mortCost.' </p>';
            }
            else {
                $data = '<p  id="mortCost'.$row->id.'"  >'.$row->mortCost.' </p>';
            }

            return $data;
        })->editColumn('cust_name', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="cust_name'.$row->id.'"  contenteditable="true" onblur="savecustname('.$row->id.') updateNet('.$row->id.')" >'.$row->cust_name.' </p>';
            }

            else {
                $data = '<p  id="cust_name'.$row->id.'" >'.$row->cust_name.' </p>';
            }

            return $data;
        })->editColumn('account_status', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="account_status'.$row->id.'"  contenteditable="true" onblur="saveAccountStatus('.$row->id.')" >'.$row->account_status.' </p>';
            }
            else {
                $data = '<p  id="account_status'.$row->id.'"  >'.$row->account_status.' </p>';
            }

            return $data;
        })->editColumn('account_profit_presntage', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="account_profit_presntage'.$row->id.'"  contenteditable="true" onblur="saveProfitPres('.$row->id.')" >'.$row->account_profit_presntage.' </p>';
            }
            else {
                $data = '<p  id="account_profit_presntage'.$row->id.'" >'.$row->account_profit_presntage.' </p>';
            }

            return $data;
        })->editColumn('tsaheel_mortgage_value', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="tsaheel_mortgage_value'.$row->id.'"  contenteditable="true" onblur="saveTsaheelMortgage('.$row->id.')" >'.$row->tsaheel_mortgage_value.' </p>';
            }
            else {
                $data = '<p  id="tsaheel_mortgage_value'.$row->id.'" >'.$row->tsaheel_mortgage_value.' </p>';
            }

            return $data;
        })->editColumn('mortgage_value', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="mortgage_value'.$row->id.'"  contenteditable="true" onblur="saveMortgageValue('.$row->id.')" >'.$row->mortgage_value.' </p>';
            }
            else {
                $data = '<p  id="mortgage_value'.$row->id.'" >'.$row->mortgage_value.' </p>';
            }

            return $data;
        })->editColumn('markter', function ($row) {

            if (auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<p  id="markter'.$row->id.'"  contenteditable="true" onblur="saveMarkter('.$row->id.')" >'.$row->markter.' </p>';
            }
            else {
                $data = '<p  id="markter'.$row->id.'" >'.$row->markter.' </p>';
            }

            return $data;
        })->editColumn('is_approved_by_wsata_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_wsata_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->editColumn('is_approved_by_tsaheel_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_tsaheel_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->rawColumns([
            'is_approved_by_wsata_acc',
            'is_approved_by_tsaheel_acc',
            'natureRequest',
            'counter_report_mor',
            'mortCost',
            'recived_date_report_mor',
            'to_date',
            'mortgage_value',
            'action',
            'cust_name',
            'markter',
            'account_profit_presntage',
            'tsaheel_mortgage_value',
            'account_status',
            'funder',
            'marckting_company',
            'request_profit',
            'agreement_cost',
        ])
            ->make(true);
    }

    public function tsaheelAccountingReport()
    {

        $role = auth()->user()->role;
        $salesAgents = User::where('role', 0)->get();
        $founding_sources = funding_source::all();

        $coll = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->pluck('user_collaborators.collaborato_id', 'users.name'); // to prevent dublicate

        $collaborators = DB::table('users')->whereIn('id', $coll)->get();

        $requests = DB::table('requests')
            ->where(function ($query) {

                /*
             $query->where(function ($query) {
                 $query->where('requests.statusReq', 9);
                 $query->where('requests.type', 'رهن');
                 $query->where('requests.isUnderProcMor', 1);
             });

             */

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [16, 35]);
                    $query->whereIn('requests.type', ['شراء-دفعة', 'تساهيل']);

                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 26);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            //->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                'customers.name as cust_name',
                'customers.mobile',
                'customers.salary_id',
                'prepayments.mortCost',
                'prepayments.agreement_cost',
                'prepayments.request_profit',
                'prepayments.account_status',
                'prepayments.account_profit_presntage',
                'fundings.funding_source'
            )
            ->orderBy('req_date', 'DESC')
            ->count();

        /*     $editCoulmns = editCoulmnsSettings::where('tableName', 'wsataAccountingReport')
            ->where('user_id', auth()->user()->id)->get();
            */

        $worke_sources = WorkSource::all();
        if ($role == 7 || $role == 4 || ($role == 8 && auth()->user()->accountant_type == 0)) {
            return view('Accountant.Reports.tsaheelAccountingReport', compact(
                'requests',
                'collaborators',
                'salesAgents',
                'founding_sources',
                'worke_sources'
            //'realTypes',
            // 'editCoulmns',
            ));
        }

        else {
            return MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that');
        }
    }

    public function tsaheelAccountingReport_datatable(Request $request)
    {

        $requests = DB::table('requests')
            ->where(function ($query) {

                /*
             $query->where(function ($query) {
                 $query->where('requests.statusReq', 9);
                 $query->where('requests.type', 'رهن');
                 $query->where('requests.isUnderProcMor', 1);
             });

             */

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [16, 35]);
                    $query->whereIn('requests.type', ['شراء-دفعة', 'تساهيل']);

                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 26);
                    $query->where('requests.type', 'رهن-شراء');
                });
            })
            ->join('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            //->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->select(
                'requests.*',
                'users.name as user_name',
                //'users.id as to_date', // just for pass ^^
                // 'users.email as action', // just for pass ^^
                'customers.name as cust_name',
                'customers.mobile',
                'customers.salary_id',
                'prepayments.mortCost',
                'prepayments.agreement_cost',
                'prepayments.request_profit',
                'prepayments.account_status',
                'prepayments.account_profit_presntage',
                'fundings.funding_source',
                'real_estats.mortgage_value as mortgage_value',
                'real_estats.tsaheel_mortgage_value as tsaheel_mortgage_value',
            )
            ->orderBy('req_date', 'DESC');

        if ($request->get('req_date_from') || $request->get('req_date_to')) {
            $requests = $requests->whereBetween('req_date', [$request->get('req_date_from'), $request->get('req_date_to')]);
        }

        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {

            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
        }

        if ($request->get('notes_status')) {
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('accountcomment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('accountcomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else {
            $requests = $requests;
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            if (auth()->user()->role == 8) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('accountant.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }

            }
            elseif (auth()->user()->role == 7) {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }
            else {

                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('general.manager.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('recived_date_report_mor', function ($row) {

            $data = '<p  style="text-align:center" id="recived_date_report_mor'.$row->id.'" >'.$row->recived_date_report_mor.' </p>';

            return $data;
        })->editColumn('natureRequest', function ($row) {

            $data = $row->natureRequest;

            return $data;
        })->addColumn('to_date', function ($row) {

            $date = Carbon::parse($row->recived_date_report_mor);
            if ($row->isUnderProcMor != 1 && $row->recived_date_report_mor != null && $row->counter_report_mor != null && auth()->user()->role == 8 && $row->is_approved_by_tsaheel_acc == 0 && $row->statusReq != 26 && $row->statusReq != 16) {
                $data = '<input style="text-align:center" id="to_date'.$row->id.'" type="date" onchange="saveEndDate('.$row->id.');" contenteditable="true" value="'.Carbon::parse($date->addDays($row->counter_report_mor))->format('Y-m-d').'" >';
            }
            elseif ($row->isUnderProcMor != 1 && $row->recived_date_report_mor != null && $row->counter_report_mor != null) {
                $data = Carbon::parse($date->addDays($row->counter_report_mor))->format('Y-m-d');
            }
            else {
                $data = '---';
            }

            return $data;
        })->editColumn('counter_report_mor', function ($row) {

            if ($row->isUnderProcMor == 1) {
                $date = Carbon::parse($row->recived_date_report_mor);
                $now = Carbon::now();
                $counter = $date->diffInDays($now);

                if ($row->counter_report_mor != $counter) {

                    DB::table('requests')->where('id', $row->id)
                        ->update([
                            'counter_report_mor' => $counter,
                        ]);

                    $data = '<p  id="counter_report_mor'.$row->id.'" >'.$counter.' يوم'.' </p>';
                }
                else {
                    $data = '<p  id="counter_report_mor'.$row->id.'" >'.$row->counter_report_mor.' يوم'.' </p>';
                }
            }
            else {
                $data = '<p  id="counter_report_mor'.$row->id.'" >'.$row->counter_report_mor.' يوم'.' </p>';
            }

            return $data;
        })->editColumn('agreement_cost', function ($row) {

            $data = '<p  id="agreement_cost'.$row->id.'" >'.$row->agreement_cost.' </p>';

            return $data;
        })->editColumn('request_profit', function ($row) {

            $data = '<p  id="request_profit'.$row->id.'"   >'.$row->request_profit.' </p>';

            return $data;
        })->editColumn('marckting_company', function ($row) {

            $data = '<p  id="marckting_company'.$row->id.'"  >'.$row->marckting_company.' </p>';

            return $data;
        })->editColumn('funder', function ($row) {

            $data = '<p  id="funder'.$row->id.'"  >'.$row->funder.' </p>';

            return $data;
        })->editColumn('mortCost', function ($row) {

            $data = '<p  id="mortCost'.$row->id.'"  >'.$row->mortCost.' </p>';

            return $data;
        })->editColumn('cust_name', function ($row) {

            $data = '<p  id="cust_name'.$row->id.'" >'.$row->cust_name.' </p>';

            return $data;
        })->editColumn('account_status', function ($row) {

            $data = '<p  id="account_status'.$row->id.'"  >'.$row->account_status.' </p>';

            return $data;
        })->editColumn('account_profit_presntage', function ($row) {

            $data = '<p  id="account_profit_presntage'.$row->id.'" >'.$row->account_profit_presntage.' </p>';

            return $data;
        })->editColumn('tsaheel_mortgage_value', function ($row) {

            $data = '<p  id="tsaheel_mortgage_value'.$row->id.'" >'.$row->tsaheel_mortgage_value.' </p>';

            return $data;
        })->editColumn('mortgage_value', function ($row) {

            $data = '<p  id="mortgage_value'.$row->id.'" >'.$row->mortgage_value.' </p>';

            return $data;
        })->editColumn('markter', function ($row) {

            $data = '<p  id="markter'.$row->id.'" >'.$row->markter.' </p>';

            return $data;
        })->editColumn('is_approved_by_wsata_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_wsata_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->editColumn('is_approved_by_tsaheel_acc', function ($row) {

            $data = '';

            if ($row->is_approved_by_tsaheel_acc == 1) {
                $data = '<i class="fas fa-check" style="font-size:large" aria-hidden="true" title="'.MyHelpers::admin_trans(auth()->user()->id, 'approved req').'"></i>';
            }

            return $data;
        })->rawColumns([
            'is_approved_by_wsata_acc',
            'is_approved_by_tsaheel_acc',
            'natureRequest',
            'counter_report_mor',
            'mortCost',
            'recived_date_report_mor',
            'to_date',
            'mortgage_value',
            'action',
            'cust_name',
            'markter',
            'account_profit_presntage',
            'tsaheel_mortgage_value',
            'account_status',
            'funder',
            'marckting_company',
            'request_profit',
            'agreement_cost',
        ])
            ->make(true);
    }

    public function approveTsaheelReq($id)
    {

        if (auth()->user()->accountant_type == 0) {

            $restRequest = DB::table('requests')->where('id', $id)
                ->where('is_approved_by_tsaheel_acc', 0)
                ->where('statusReq', '!=', 26)
                ->update(['is_approved_by_tsaheel_acc' => 1]);

            if ($restRequest) {

                DB::table('request_histories')->insert([ // add to request history
                                                         'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Approve req from tsaheel accountant'),
                                                         'user_id'      => (auth()->user()->id),
                                                         'history_date' => (Carbon::now('Asia/Riyadh')),
                                                         'req_id'       => $id,
                ]);

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Aprroved sucessfully'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function reApproveTsaheelReq($id)
    {

        if (auth()->user()->accountant_type == 0) {

            $restRequest = DB::table('requests')->where('id', $id)
                ->where('is_approved_by_tsaheel_acc', 1)
                ->where('statusReq', '!=', 26)
                ->update(['is_approved_by_tsaheel_acc' => 0]);

            if ($restRequest) {

                DB::table('request_histories')->insert([ // add to request history
                                                         'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Reapprove req from tsaheel accountant'),
                                                         'user_id'      => (auth()->user()->id),
                                                         'history_date' => (Carbon::now('Asia/Riyadh')),
                                                         'req_id'       => $id,
                ]);

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function approveWsataReq($id)
    {

        if (auth()->user()->accountant_type == 1) {

            $restRequest = DB::table('requests')->where('id', $id)
                ->where('is_approved_by_wsata_acc', 0)
                ->where('statusReq', '!=', 26)
                ->update(['is_approved_by_wsata_acc' => 1]);

            if ($restRequest) {

                DB::table('request_histories')->insert([ // add to request history
                                                         'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Approve req from wsata accountant'),
                                                         'user_id'      => (auth()->user()->id),
                                                         'history_date' => (Carbon::now('Asia/Riyadh')),
                                                         'req_id'       => $id,
                ]);

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Aprroved sucessfully'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function reApproveWsataReq($id)
    {

        if (auth()->user()->accountant_type == 1) {

            $restRequest = DB::table('requests')->where('id', $id)
                ->where('is_approved_by_wsata_acc', 1)
                ->where('statusReq', '!=', 26)
                ->update(['is_approved_by_wsata_acc' => 0]);

            if ($restRequest) {

                DB::table('request_histories')->insert([ // add to request history
                                                         'title'        => MyHelpers::admin_trans(auth()->user()->id, 'Reapprove req from wsata accountant'),
                                                         'user_id'      => (auth()->user()->id),
                                                         'history_date' => (Carbon::now('Asia/Riyadh')),
                                                         'req_id'       => $id,
                ]);

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Successfully'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function tsaheelAccountingReportWithNotifiy()
    {

        $updateNotify = DB::table('notifications')->where('recived_id', auth()->user()->id)
            ->update(['status' => 1]); // open

        return redirect()->route('report.tsaheelAccountingUnderReport');
    }

    public function wsataAccountingReportWithNotifiy()
    {

        $updateNotify = DB::table('notifications')->where('recived_id', auth()->user()->id)
            ->update(['status' => 1]); // open

        return redirect()->route('report.wsataAccountingUnderReport');
    }

    public function fundingreqpage($id)
    {

        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        $reqStatus = $request->statusReq;

        $purchaseCustomer = DB::table('requests')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseJoint = DB::table('requests')
            ->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseReal = DB::table('requests')
            ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseFun = DB::table('requests')
            ->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseClass = DB::table('requests')
            ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseTsa = DB::table('requests')
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('requests.id', '=', $id)
            ->first();

        $collaborator = DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.collaborator_id')
            ->where('requests.id', '=', $id)
            ->first();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->where('user_collaborators.user_id', auth()->user()->id)
            ->get();

        $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();

        if ($request->type == 'شراء-دفعة' && $payment == null) {
            $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)
                ->first();
        }
        else {
            $paymentForDisplayonly = null;
        }

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $cities = DB::table('cities')->select('id', 'value')->get();
        $ranks = DB::table('military_ranks')->select('id', 'value')->get();
        $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
        $askary_works = DB::table('askary_works')->select('id', 'value')->get();
        $madany_works = DB::table('madany_works')->select('id', 'value')->get();
        $realTypes = DB::table('real_types')->select('id', 'value')->get();
        $classifcations = DB::table('classifcations')->select('id', 'value')->get();

        $documents = DB::table('documents')->where('req_id', '=', $id)
            ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')
            ->get();

        $followdate = DB::table('notifications')->where('req_id', '=', $id)
            ->where('recived_id', '=', (auth()->user()->id))
            ->where('type', '=', 1)
            //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
            ->get()
            ->last(); //to get last reminder

        $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

        if (!empty($followdate)) {
            $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Accountant.fundingReq.fundingreqpage', compact(
            'purchaseCustomer',
            'purchaseJoint',
            'purchaseReal',
            'purchaseFun',
            'purchaseTsa',
            'purchaseClass',
            'salary_sources',
            'funding_sources',
            'askary_works',
            'madany_works',
            'classifcations',
            'id', //Request ID
            'documents',
            'reqStatus',
            'payment',
            'followdate',
            'collaborator',
            'cities',
            'ranks',
            'collaborators',
            'paymentForDisplayonly',
            'followtime',
            'realTypes',
            'worke_sources',
            'request_sources'
        ));
    }

    public function morPurpage($id)
    {

        $request = DB::table('requests')->where('requests.id', '=', $id)->first();

        $reqStatus = $request->statusReq;

        $purchaseCustomer = DB::table('requests')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseJoint = DB::table('requests')
            ->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseReal = DB::table('requests')
            ->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseFun = DB::table('requests')
            ->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseClass = DB::table('requests')
            ->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')
            ->where('requests.id', '=', $id)
            ->first();

        $purchaseTsa = DB::table('requests')
            ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
            ->where('requests.id', '=', $id)
            ->first();

        $collaborator = DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.collaborator_id')
            ->where('requests.id', '=', $id)
            ->first();

        $collaborators = DB::table('user_collaborators')->select('user_collaborators.user_id', 'user_collaborators.collaborato_id', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->where('user_collaborators.user_id', auth()->user()->id)
            ->get();

        $payment = DB::table('prepayments')->where('req_id', '=', $request->req_id)->first();

        if ($request->type == 'شراء-دفعة' && $payment == null) {
            $paymentForDisplayonly = DB::table('prepayments')->where('req_id', '=', $id)
                ->first();
        }
        else {
            $paymentForDisplayonly = null;
        }

        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        $cities = DB::table('cities')->select('id', 'value')->get();
        $ranks = DB::table('military_ranks')->select('id', 'value')->get();
        $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
        $askary_works = DB::table('askary_works')->select('id', 'value')->get();
        $madany_works = DB::table('madany_works')->select('id', 'value')->get();
        $realTypes = DB::table('real_types')->select('id', 'value')->get();
        $classifcations = DB::table('classifcations')->select('id', 'value')->get();

        $documents = DB::table('documents')->where('req_id', '=', $id)
            ->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')
            ->get();

        $followdate = DB::table('notifications')->where('req_id', '=', $id)
            ->where('recived_id', '=', (auth()->user()->id))
            ->where('type', '=', 1)
            //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
            ->get()
            ->last(); //to get last reminder

        $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

        if (!empty($followdate)) {
            $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
        }

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Accountant.morPurReq.fundingreqpage', compact(
            'purchaseCustomer',
            'purchaseJoint',
            'purchaseReal',
            'purchaseFun',
            'purchaseTsa',
            'purchaseClass',
            'salary_sources',
            'funding_sources',
            'askary_works',
            'madany_works',
            'classifcations',
            'id', //Request ID
            'documents',
            'reqStatus',
            'payment',
            'followdate',
            'collaborator',
            'cities',
            'ranks',
            'collaborators',
            'paymentForDisplayonly',
            'followtime',
            'realTypes',
            'worke_sources',
            'request_sources'
        ));
    }
}

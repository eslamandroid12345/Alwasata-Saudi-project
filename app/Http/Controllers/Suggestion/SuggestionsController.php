<?php

namespace App\Http\Controllers\Suggestion;

use App\BankPercentage;
use App\FundingYear;
use App\Http\Controllers\Controller;
use App\SuggestionUser;
use Datatables;
use DB;
use Illuminate\Http\Request;
use View;

class SuggestionsController extends Controller
{
    public function __construct()
    {
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
        $this->middleware('auth');
    }

    public function index()
    {
        if (\request()->has('notify')) {
            DB::table('notifications')->where('id', request('notify'))
                ->update(['status' => 1]);
        }
        $done_banks = SuggestionUser::where([
            'suggestable_type' => BankPercentage::class,
            'user_id'          => auth()->id(),
        ])
            ->pluck('suggestable_id')
            ->toArray();
        $done_years = SuggestionUser::where([
            'suggestable_type' => FundingYear::class,
            'user_id'          => auth()->id(),
        ])
            ->pluck('suggestable_id')
            ->toArray();
        $banks = BankPercentage::whereNotIn('id', $done_banks)
            ->where('user_id', '<>', auth()->user()->id)
            ->where(['status' => 0])
            ->count();
        $years = FundingYear::whereNotIn('id', $done_years)
            ->where('user_id', '<>', auth()->user()->id)
            ->where(['status' => 0])
            ->count();
        return view('Suggestions.index', compact('banks', 'years'));
    }

    public function years()
    {
        $done_years = SuggestionUser::where([
            'suggestable_type' => FundingYear::class,
            'user_id'          => auth()->id(),
        ])
            ->pluck('suggestable_id')
            ->toArray();
        $extraFundingYears = FundingYear::whereNotIn('id', $done_years)
            ->where('user_id', '<>', auth()->user()->id)
            ->where(['status' => 0])
            ->count();
        return view('Suggestions.Vote.years', compact('extraFundingYears'));
    }

    public function percentages()
    {
        $done_banks = SuggestionUser::where([
            'suggestable_type' => BankPercentage::class,
            'user_id'          => auth()->id(),
        ])
            ->pluck('suggestable_id')
            ->toArray();
        $profitPercentages = BankPercentage::whereNotIn('id', $done_banks)
            ->where('user_id', '<>', auth()->user()->id)
            ->where(['status' => 0])
            ->count();
        return view('Suggestions.Vote.percentages', compact('profitPercentages'));
    }

    public function yearsDataTables()
    {
        $done_years = SuggestionUser::where([
            'suggestable_type' => FundingYear::class,
            'user_id'          => auth()->id(),
        ])
            ->pluck('suggestable_id')
            ->toArray();
        $extraFundingYears = FundingYear::whereNotIn('id', $done_years)
            ->where('user_id', '<>', auth()->user()->id)
            ->where(['status' => 0])
            ->get();

        return Datatables::of($extraFundingYears)->setRowId(function ($extraFundingYear) {
            return $extraFundingYear->id;
        })->addColumn('user', function ($profitPercentage) {
            return $profitPercentage->user->name;
        })->addColumn('created_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->created_at));
        })->addColumn('updated_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->updated_at));
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<a onclick="ApproveForm('.$row->id.')" class="btn btn-success btn-sm text-white" > موافقة <i class="fas fa-thumbs-up"></i></a>
                                     <a onclick="RejectForm('.$row->id.')" class="btn btn-danger btn-sm text-white"> رفض <i class="fas fa-thumbs-down"></i></a>';
            return $data;
        })->make(true);
    }

    public function percentagesDataTables()
    {
        $done_banks = SuggestionUser::where([
            'suggestable_type' => BankPercentage::class,
            'user_id'          => auth()->id(),
        ])
            ->pluck('suggestable_id')
            ->toArray();
        $profitPercentages = BankPercentage::whereNotIn('id', $done_banks)
            ->where('user_id', '<>', auth()->user()->id)
            ->where(['status' => 0])
            ->get();
        return Datatables::of($profitPercentages)->setRowId(function ($profitPercentage) {
            return $profitPercentage->id;
        })->addColumn('user', function ($profitPercentage) {
            return $profitPercentage->user->name;
        })->addColumn('created_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->created_at));
        })->addColumn('updated_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->updated_at));
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<a onclick="ApproveForm('.$row->id.')" class="btn btn-success btn-sm text-white" > موافقة <i class="fas fa-thumbs-up"></i></a>
                                     <a onclick="RejectForm('.$row->id.')" class="btn btn-danger btn-sm text-white"> رفض <i class="fas fa-thumbs-down"></i></a>';
            return $data;
        })->make(true);
    }

    public function Vote(Request $request)
    {
        $model = 'App\\'.$request->suggestable_type;

        $data = [
            'suggestable_type' => $model,
            'user_id'          => auth()->id(),
            'suggestable_id'   => $request->id,
            'vote'             => $request->vote,
            'no_reason'        => $request->not_reason,
        ];
        SuggestionUser::firstOrCreate($data, $data);
        return response()->json([
            'success' => true,
            'message' => 'تم التقييم بنجاح',
        ]);
    }

}

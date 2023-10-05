<?php

namespace App\Http\Middleware;

use App\BankPercentage;
use App\EditCalculationFormulaUser;
use App\FundingYear;
use App\SuggestionUser;
use Auth;
use Closure;
use Illuminate\Http\Request;

class EnsureThereIsNoCalculaterSuggestion
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    //**********************************************************************
    // Task-38
    //**********************************************************************
    public function handle($request, Closure $next)
    {

        if (Auth::check()) {
            $user_id = Auth::user()->id;
            if (EditCalculationFormulaUser::where(['user_id' => $user_id, 'type' => 0])->count() != 0) {
                $done_banks = SuggestionUser::where([
                    'suggestable_type' => BankPercentage::class,
                    'user_id'          => $user_id,
                ])
                    ->pluck('suggestable_id')
                    ->toArray();
                $done_years = SuggestionUser::where([
                    'suggestable_type' => FundingYear::class,
                    'user_id'          => $user_id,
                ])
                    ->pluck('suggestable_id')
                    ->toArray();
                $banks = BankPercentage::whereNotIn('id', $done_banks)
                    ->where('user_id', '<>', $user_id)
                    ->where(['status' => 0])
                    ->count();
                $years = FundingYear::whereNotIn('id', $done_years)
                    ->where('user_id', '<>', $user_id)
                    ->where(['status' => 0])
                    ->count();

                if ($banks > 0 || $years > 0) {
                    session()->flash('EnsureThereIsNoCalculaterSuggestion', 'يوجد اقتراح على الحاسبة بحاجة لتصويت !');
                }
            }
        }

        return $next($request);
    }
}

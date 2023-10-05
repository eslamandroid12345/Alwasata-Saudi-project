<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use App\Http\Resources\V2\ClassificationResource;
use App\Models\Classification;
use App\Models\ClassificationAlertSetting;
use Illuminate\View\View;

class ClassificationComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return [
            'V2.Admin.ClassificationAlertSetting.partials.form',
            'V2.ExternalCustomer.partials.details',
            'V2.Reports.report5',
            'Charts.movedRequestWithPostiveChart-parameters',
            'Admin.WelcomeMessages.add',
            'Admin.WelcomeMessages.edit',
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): View
    {
        $UserAgentClassifications = Classification::where('user_role', 0)->get();
        $QualityClassifications = Classification::where('user_role', 5)->get();
        $UserBankDelegateClassifications = Classification::where('user_role', 0)->get()->map(function ($e) {
            $e->name = $e->value;
            $e->value = $e->id;
            return $e;
        });
        $UserAgentClassificationsSelect = ClassificationResource::collection($UserAgentClassifications)->toArray(request());
        $QualityClassificationsSelect = ClassificationResource::collection($QualityClassifications)->toArray(request());
        $ClassificationAlertSettingTypesSelect = collect(ClassificationAlertSetting::TYPES)->map(function ($v) {
            return [
                'id'   => $v,
                'name' => $v,
            ];
        })->toArray();
        //dd($ClassificationAlertSettingTypesSelect->pluck('id', 'name'));
        return $view->with([
            'QualityClassificationsSelect'          => $QualityClassificationsSelect,
            'UserAgentClassificationsSelect'        => $UserAgentClassificationsSelect,
            'ClassificationAlertSettingTypesSelect' => $ClassificationAlertSettingTypesSelect,
            'UserBankDelegateClassifications'       => $UserBankDelegateClassifications,
        ]);
    }
}

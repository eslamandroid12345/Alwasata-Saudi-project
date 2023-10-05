<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2;

use App\askary_work;
use App\customer;
use App\DataTables\FreezeRequestDataTable as DataTable;
use App\District;
use App\funding;
use App\funding_source;
use App\Helpers\MyHelpers;
use App\Http\Controllers\AppController;
use App\Http\Requests\ExternalCustomerRequest;
use App\madany_work;
use App\military_ranks;
use App\Models\ExternalCustomer as Model;
use App\Models\ExternalCustomer;
use App\Models\User;
use App\RejectionsReason;
use App\salary_source;
use App\Setting;
use App\Traits\ResponseAPI;
use App\WorkSource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use yajra\Datatables\Datatables;

/**
 *
 */
class ExternalCustomerController extends AppController
{
    use ResponseAPI;
    /**
     *
     */
    const ROLES = [13];

    /**
     *
     */
    public function __construct()
    {


        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();

        \View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
        view()->share([
            "BankDelegateHost" => rtrim(Setting::getBankDelegateHost(), '/'),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        return view('V2.ExternalCustomer.index');
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws \Exception
     */
    public function indexDatatable(Request $request)
    {
        //dd($request->all());
        /** @var Builder|Model $builder */
        $builder = Model::query()->where('user_id', auth()->id());
        $archived = $request->get('archived');
        if ($archived) {
            $builder->onlyTrashed();
        }
        return Datatables::of($builder)
            ->setRowId(fn(Model $row) => $row->id)
            ->addColumn('action', function (Model $row) use ($archived) {
                $view = __("global.view");
                $viewRoute = route('V2.ExternalCustomer.show', $row->id);
                $viewBtn = <<<viewBtn
            <span class="item pointer dt-view-btn" data-id="$row->id" data-toggle="tooltip" data-placement="top" title="$view">
                        <a  href="$viewRoute"><i class="fas fa-eye"></i></a>
            </span>
            viewBtn;
                            //$viewBtn = null;

                            $archive = __("global.archive");
                            $archiveUrl = route('V2.ExternalCustomer.Archive.archive', $row->id);
                            $archiveBtn = $archived ? null : <<<btn
                <span class="item pointer dt-archive-btn" data-id="$row->id" data-url="$archiveUrl" data-toggle="tooltip" data-placement="top" title="$archive">
                            <a href="javascript:void(0)"><i class="fas fa-trash-alt"></i></a>
                </span>
            btn;
                            //$archiveBtn = null;

                            $restore = __("global.restore");
                            $restoreRoute = route('V2.ExternalCustomer.Archive.restore', $row->id);
                            $restoreBtn = $archived ? <<<btn
                <span class="item pointer dt-restore-btn" data-id="$row->id" data-url="$restoreRoute" data-toggle="tooltip" data-placement="top" title="$restore">
                            <a href="javascript:void(0)"><i class="fas fa-redo-alt"></i></a>
                </span>
            btn: null;
                            //$restoreBtn = null;

                            return <<<HTML
            <div class="tableAdminOption">
            $viewBtn $archiveBtn $restoreBtn
            </div>
            HTML;
                        })
            ->editColumn("classification_id", fn(Model $model) => $model->classification_id_to_string ?: '-')
            //->editColumn("req_date", fn(Model $model) => $model->req_date_to_date_format)
            ->rawColumns(['action'])->make(true);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function archivedIndex()
    {
        return view('V2.ExternalCustomer.index', ['archived' => !0]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function ids(Request $request)
    {
        if (($ids = $request->get('ids'))) {
            try {
                //dd($ids);
                Model::whereIn('id', $ids)->delete();
                return $this->controller_response(__("messages.success"));
            }
            catch (\Exception $exception) {
            }
        }
        return $this->controller_response(__("messages.fail"), 422);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function restoreIds(Request $request)
    {
        if (($ids = $request->get('ids'))) {
            try {
                Model::withTrashed()->whereIn('id', $ids)->restore();
                return $this->controller_response(__("messages.success"));
            }
            catch (\Exception $exception) {
            }
        }
        return $this->controller_response(__("messages.fail"), 422);
    }

    /**
     * @param  Model  $model
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function archive(Model $model)
    {
        try {
            $model->delete();
        }
        catch (\Exception $exception) {
        }
        return $this->controller_response(__("messages.success_name", ['name' => $model->name]));
    }

    /**
     * @param  Model  $model
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function show($model, Request $req)
    {
        $external_customer_id=$model->id;

        // $purchaseCustomer=ExternalCustomer::find($external_customer_id) ?? ExternalCustomer::withTrashed()->where('id',$external_customer_id)->first();
        $purchaseCustomer=ExternalCustomer::withTrashed()->where('id',$external_customer_id)->first();

        $purchaseFun=funding::where('id',$purchaseCustomer->funding_id)->first();

        $worke_sources = WorkSource::all();

        $salary_sources =salary_source::select('id', 'value')->get();

        $funding_sources =funding_source::select('id', 'value')->get();

        $askary_works = askary_work::select('id', 'value')->get();

        $madany_works = madany_work::select('id', 'value')->get();

        $ranks = military_ranks::select('id', 'value')->get();

        $product_types = null;
        $getTypes = MyHelpers::getProductType();
        if ($getTypes != null) {
            $product_types = $getTypes;
        }

        $show_funding_source = false;
        $show_funding_source = MyHelpers::canShowBankName(auth()->id());
        $followdate=null;
        $followdate_agent=null;

        $reminderDate = DB::table('notifications')->where('external_customer_id', '=', $external_customer_id)
                                                         ->where('recived_id', '=', (auth()->user()->id))
                                                         ->where('type', '=', 1)
                                                         ->where('status', '=', 2)
                                                         ->first();

        $DateOfReminder=null;
        $timeOfReminder=null;
        if(!empty($reminderDate)){
            $DateOfReminder=Carbon::parse($reminderDate->reminder_date )->format('Y-m-d');
            $timeOfReminder=Carbon::parse($reminderDate->reminder_date )->format('H:i:s');
        }
        return view('V2.ExternalCustomer.show', compact(
            'purchaseCustomer','purchaseFun','worke_sources',
            'salary_sources','funding_sources','askary_works',
            'madany_works',/*'cities',*/'ranks',/*'realTypes',*/
            'product_types','show_funding_source','followdate',
            'followdate_agent','external_customer_id','DateOfReminder','timeOfReminder'
        ));
    }

    // public function updatefunding(ExternalCustomerRequest $request)
    public function updatefunding(Request $request)
    {
        try {
            // dd($request->all());
            $external_customer=ExternalCustomer::withTrashed()->where('id',$request->external_customer_id)->first();
            $external_customer->update([
                'name'                     => $request->name,
                'mobile'                   => $request->mobile,
                'id_number'                => $request->id_number,
                'sex'                      => $request->sex,
                'birth_date'               => $request->birth,
                'birth_date_higri'         => $request->birth_hijri,
                'hiring_date'              => Carbon::parse($request->hiring_date),
                'age'                      => $request->age,
                'age_years'                => $request->age_years,
                'work'                     => $request->work,
                'madany_id'                => $request->madany_work,
                'job_title'                => $request->job_title,
                'askary_id'                => $request->askary_work,
                'military_rank'            => $request->rank,
                'salary_id'                => $request->salary_source,
                'salary'                   => $request->salary,
                'basic_salary'             => $request->basic_salary,
                'is_supported'             => $request->is_support,
                'has_obligations'          => $request->has_obligations,
                'obligations_value'        => $request->obligations_value,
                'has_financial_distress'   => $request->has_financial_distress,
                'financial_distress_value' => $request->financial_distress_value,
                'without_transfer_salary'  => $request->without_transfer_salary,
                'add_support_installment_to_salary'  => $request->add_support_installment_to_salary,
                'guarantees'               => $request->guarantees,
                'region_ip'                => $request->regionip,
                'notes'                    => $request->notes,
            ]);

            funding::findOrFail($external_customer->funding_id)->update([
                'funding_source'                    => $request->funding_source,
                'funding_duration'                  => $request->fundingdur,
                'personalFun_cost'                  => $request->fundingpersonal,
                'personalFun_pre'                   => $request->fundingpersonalp,
                'realFun_cost'                      => $request->fundingreal,
                'realFun_pre'                       => $request->fundingrealp,
                'ded_pre'                           => $request->dedp,
                'monthly_in'                        => $request->monthIn,
                'flexiableFun_cost'                 => $request->flexFund,
                'personal_salary_deduction'         => $request->personalDed,
                'personal_monthly_installment'      => $request->personalMonthIn,
                'monthly_installment_after_support' => $request->monthInAfterSupport,
                'extendFund_cost'                   => $request->extenFund,
                'product_code'                      => $request->product_code,
            ]);

         ////********************REMINDERS BODY************************* */
                //only one reminder to each request
                $checkFollow = DB::table('notifications')->where('external_customer_id', '=', $request->external_customer_id)
                                                         ->where('recived_id', '=', (auth()->user()->id))
                                                         ->where('type', '=', 1)
                                                         ->where('status', '=', 2)
                                                         ->first(); // check dublicate
                // dd($checkFollow);
                if ($request->follow != null) {

                    $date = $request->follow;
                    $time = $request->follow1;
                    if ($time == null) {
                        $time = "00:00";
                    }
                    $newValue = $date."T".$time;
                    if (empty($checkFollow)) { //first reminder
                        // add following notification
                        DB::table('notifications')->insert([
                            'value'                 => MyHelpers::admin_trans(auth()->user()->id, 'The request need following'),
                            'recived_id'            => (auth()->user()->id),
                            'status'                => 2,
                            'type'                  => 1,
                            'reminder_date'         => $newValue,
                            'req_id'                => null,
                            'external_customer_id'  => $request->external_customer_id,
                            'created_at'            => (Carbon::now('Asia/Riyadh')),
                        ]);
                    }
                    else {
                        $overWriteReminder = DB::table('notifications')->where('id', $checkFollow->id)
                                            ->update([
                                                'reminder_date' => $newValue,
                                                'created_at' => (Carbon::now('Asia/Riyadh'))
                                            ]); //set new notifiy
                    }
                }
                else {
                    #if empty reminder, so the reminder ll remove if it's existed.
                    if (!empty($checkFollow)) {
                        DB::table('notifications')->where('id', $checkFollow->id)->delete();
                    }
                }
                ////********************REMINDERS BODY************************* */

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), []);
    }

    public function storeBankRequest(Request $request)
    {
       try {
           $funding=new funding();
           $funding->save();

           ExternalCustomer::create([
            'user_id' =>$request->user_id,
            'funding_id' =>$funding->id,

            'name' =>$request->name,
            'mobile' =>$request->mobile,
            'id_number' =>$request->id_number,
            'basic_salary' =>$request->basic_salary,

            'has_obligations' =>$request->has_obligations,
            'obligations_value' =>$request->obligations_value,
            'duration_of_obligations' =>$request->duration_of_obligations,

            'work' =>$request->work,
            'askary_id' =>$request->askary_id,
            'military_rank' =>$request->military_rank,

            'madany_id' =>$request->madany_id,
            'job_title' =>$request->job_title,
            'salary_id' =>$request->salary_id,

            'birth_date' =>$request->birth_date,
            'birth_date_higri'         => $request->birth_date_hijri,

            'is_supported' =>$request->is_supported,
            'hiring_date' =>$request->hiring_date,
            'hiring_date_hijri' =>$request->hiring_date_hijri,
            'salary' =>$request->salary,
           ]);
            return $this->success(" ", true);
        }
        catch (\Exception $e) {
            // return $e->getMessage();
            return $this->error($e->getMessage(),'500');
        }
    }

    public function getUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);
        try {
            $user=User::where('id',$request->user_id)->first();
            return $this->success(' ',$user);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(),'500');
        }
    }

}

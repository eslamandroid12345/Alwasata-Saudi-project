<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppController extends BaseController
{

    /** @var array */
    public $viewData = [];

    /** @var string getter for attribute display */
    public $model_display_attribute = "name";

    /** @var null|string @uses request()->route()->getName() */
    public $CURRENT_ROUT_NAME = null;

    /** @var null|string permission name without map @uses parsePermission() */
    public $CTRL_PER_NAME = null;

    /** @var null|string View Path */
    public $CTRL_VIEW = null;

    /** @var null|string Language Path */
    public $CTRL_LANG = null;

    /** @var null|string Route of Controller */
    public $CTRL_ROUTE = null;

    /** @var mixed|null  str_singular Of $this */
    public $CTRL_NAME = null;

    /** @var null|string  str_plural Of $this */
    public $CTRL_NAMES = null;

    /** @var null For Update and Create */
    public $MODEL = null;

    /** @var null For DataTable */
    public $DataTableClass = null;

    /** @var bool DataTable show active buttons */
    public $SHOW_ACTIVE_BUTTONS = false;

    /** @var Request $request */
    public $request;

    /** @var bool Redirect After Store */
    protected $redirectStore = true;

    /** @var bool Redirect After Update */
    protected $redirectUpdate = false;

    /** @var string */
    protected $namespaceView = 'V2.layouts';

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if (app()->runningInConsole()) {
            return;
        }
        $this->request = request();
        $this->CURRENT_ROUT_NAME = request()->route()->getName();

        try {
            //$route = app(Route::class);
            //$uses = $route->getAction()['uses'];
            //if (!$uses) return;
            //dd($uses);
            //$_use = explode("@", $uses);
            //$use = $_use[0];

            $get_class = get_class($this);
            $class_basename = class_basename($this);
            $isModule = starts_with($get_class, "Modules");

            //if (!$this->CTRL_PER_NAME) {
            //    $this->CTRL_PER_NAME = null;
            //}
            if (!$this->CTRL_VIEW) {
                $VIEW = "";

                $c = explode('\\', $get_class);

                // Module Name "package"
                if ($isModule) {
                    $VIEW = strtolower($c[1])."::";
                    unset($c[1]);
                }
                unset($c[0]);

                foreach ($c as $value) {
                    if (Str::contains(strtolower($value), [
                        "http",
                        "controllers",
                    ])) {
                        continue;
                    }

                    $VIEW .= ucfirst(str_ireplace("Controller", "", $value)).".";
                }
                //dd($VIEW);
                $this->CTRL_VIEW = $VIEW;
                //$this->CTRL_VIEW = trim($VIEW, '.');
            }
            if (!$this->CTRL_LANG) {
                $LANG = "";

                $c = explode('\\', $get_class);
                //                dd($c);
                // Module Name "package"
                if ($isModule) {
                    $LANG = strtolower($c[1])."::";
                    unset($c[1]);
                }
                unset($c[0]);
                $LANG .= str_ireplace("Controller", "", end($c)).".";
                //dd($LANG);
                $this->CTRL_LANG = $LANG;
                //$this->CTRL_LANG = trim($LANG, '.');
            }
            if (!$this->CTRL_ROUTE) {
                $this->CTRL_ROUTE = trim($this->CTRL_VIEW, ".");
            }
            if (!$this->CTRL_NAME) {
                $this->CTRL_NAME = str_ireplace("Controller", "", $class_basename);
            }
            if (!$this->CTRL_NAMES) {
                $this->CTRL_NAMES = Str::plural(str_ireplace("Controller", "", $class_basename));
            }

            $viewData = [
                "CTRL_SHOW_ACTIVE_BUTTONS" => $this->SHOW_ACTIVE_BUTTONS,
                "CTRL_PER_NAME"            => $this->CTRL_PER_NAME,
                "CTRL_VIEW"                => $this->CTRL_VIEW,
                "CTRL_ROUTE"               => $this->CTRL_ROUTE,
                "CTRL_LANG"                => $this->CTRL_LANG,
                "CTRL_NAME"                => $this->CTRL_NAME,
                "CTRL_NAMES"               => $this->CTRL_NAMES,
                "CURRENT_ROUT_NAME"        => $this->CURRENT_ROUT_NAME,
            ];

            view()->share(array_merge($this->viewData, $viewData));

            //$this->middleware(function ($request, $next) use ($viewData) {
            //    view()->share(array_merge($this->viewData, $viewData));
            //    return $next($request);
            //});
            // dd($this);
        }
        catch (Exception $exception) {
            if (env("APP_DEBUG")) {
                dd($exception->getMessage());
            }
            abort(404);
        }
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $dataTable = app($this->DataTableClass);
        $dataTable->controller = $this;
        $view = view()->exists($v = "{$this->CTRL_VIEW}index") ? $v : rtrim($this->namespaceView, '.').".app_index";
        return $dataTable->render($view, compact("dataTable"));
    }

    /**
     * @return mixed
     */
    public function trashed()
    {
        $dataTable = app($this->DataTableClass);
        $dataTable->controller = $this;
        $dataTable->trashed = true;
        $view = view()->exists($v = "{$this->CTRL_VIEW}index") ? $v : rtrim($this->namespaceView, '.').".app_index";
        return $dataTable->render($view, compact("dataTable"));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $compact = ["model" => app($this->MODEL)];
        $this->renderCreate($compact);
        $view = view()->exists($v = "{$this->CTRL_VIEW}create") ? $v : rtrim($this->namespaceView, '.').".app_create";
        return view($view, $compact);
    }

    /**
     * Render Create Function
     *
     * @param  array  $compact
     *
     * @return void
     */
    protected function renderCreate(&$compact = [])
    {
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $model = app($this->MODEL);
        $this->validator($request)->validate();
        $model->fill($request->only($model->getFillable()));
        $this->saving($model, $request);
        $this->creating($model, $request);
        $model->save();
        $this->saved($model, $request);
        $this->created($model, $request);
        $url = $this->redirectStore ? $this->redirectStoreRoute($model) : ['reload' => true];
        $message = __("replace.add_success", ["name" => $model->name]);
        return $this->controller_response($message, $url);
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

    /**
     * both updating & creating
     *
     * @param $model
     * @param  Request  $request
     */
    protected function saving($model, Request $request)
    {
    }

    /**
     * Before Creating
     *
     * @param  $model
     * @param  Request  $request
     *
     * @return void
     * @uses $this->store
     */
    protected function creating($model, Request $request)
    {
    }

    /**
     * Created & Updated
     *
     * @param $model
     * @param  Request  $request
     *
     * @return void
     */
    protected function saved($model, Request $request)
    {
    }

    /**
     * After Create
     *
     * @param $model
     * @param  Request  $request
     *
     * @return void
     * @uses $this->store
     */
    protected function created($model, Request $request)
    {
    }

    /**
     * @param $model
     *
     * @return string
     */
    protected function redirectStoreRoute($model)
    {
        return is_bool($this->redirectStore) ? route("{$this->CTRL_ROUTE}.show", $model->id) : $this->redirectStore;
    }

    /**
     * @param $model
     * @param  Request  $request
     *
     * @return Factory|View
     */
    public function show($model, Request $request)
    {
        $compact = ["model" => $model];
        $this->renderShow($compact);
        if (!view()->exists($v = "{$this->CTRL_VIEW}show")) {
            return $this->edit(...func_get_args());
        }

        $view = view()->exists($v) ? $v : rtrim($this->namespaceView, '.').".app_show";
        return view($view, $compact);
    }

    /**
     * Render Show Function
     *
     * @param  array  $compact
     *
     * @return void
     */
    protected function renderShow(&$compact = [])
    {
    }

    /**
     * @param $model
     *
     * @return Factory|View
     */
    public function edit($model)
    {
        $compact = ["model" => $model];
        $this->renderEdit($compact);
        $view = view()->exists($v = "{$this->CTRL_VIEW}update") ? $v : rtrim($this->namespaceView, '.').".app_update";
        return view($view, $compact);
    }

    /**
     * Render Edit function
     *
     * @param  array  $compact
     */
    protected function renderEdit(&$compact = [])
    {
    }

    /**
     * @param $model
     * @param  Request  $request
     * @throws ValidationException
     */
    public function update($model, Request $request)
    {
        $this->validator($request)->validate();
        $name = $model->name;
        $model->fill($request->only($model->getFillable()));
        $this->saving($model, $request);
        $this->updating($model, $request);
        $model->save();
        $this->saved($model, $request);
        $this->updated($model, $request);
        $url = $this->redirectUpdate ? $this->redirectUpdateRoute($model) : [];
        $message = __("replace.update_success", ["name" => $name]);
        return $this->controller_response($message, $url);
    }

    /**
     * Before Updating
     *
     * @param $model
     * @param  Request  $request
     *
     * @return void
     * @uses $this->update
     */
    protected function updating($model, Request $request)
    {
    }

    /**
     * After Update
     *
     * @param $model
     * @param  Request  $request
     *
     * @return void
     * @uses $this->update
     */
    protected function updated($model, Request $request)
    {
    }

    /**
     * @param $model
     *
     * @return string
     */
    protected function redirectUpdateRoute($model)
    {
        return is_bool($this->redirectUpdate) ? route("{$this->CTRL_ROUTE}.edit", $model->id) : $this->redirectUpdate;
    }

    /**
     * @param $model
     */
    public function enable($model)
    {
        $model->setEnable();
        $message = __("replace.update_success", ["name" => $model->{$this->model_display_attribute}]);
        return $this->controller_response($message);
    }

    /**
     * @param $model
     */
    public function disable($model)
    {
        if ($model->is(auth()->user())) {
            return $this->controller_response(__("messages.fail"), 422);
        }

        $model->setDisabled();
        // $url = route("{$this->CTRL_ROUTE}.show", $model->id);
        $message = __("replace.update_success", ["name" => $model->{$this->model_display_attribute}]);
        return $this->controller_response($message);
    }

    /**
     * @param $model
     */
    public function restore($model)
    {
        if ($model->restore()) {
            // $url = route("{$this->CTRL_ROUTE}.show", $model->id);
            $message = __("replace.update_success", ["name" => $model->{$this->model_display_attribute}]);
            return $this->controller_response($message);
        }
        return $this->controller_response(__("messages.fail"), 422);
    }

    /**
     * @param $model
     */
    public function destroy($model)
    {
        $deletedName = $model->name;
        // dd($model);
        $model->delete();
        return $this->controller_response(__("replace.delete_success", ["name" => $deletedName]));
    }

    /**
     * @param $model
     */
    public function forceDestroy($model)
    {
        $deletedName = $model->name;
        $model->forceDelete();
        return $this->controller_response(__("replace.delete_success", ["name" => $deletedName]));
    }

    /**
     * @param $model
     * @param $relationships
     *
     * @return array
     */
    public function countRelationships($model, $relationships): array
    {
        $counter = [];

        foreach ($relationships as $relationship => $text) {
            if ($c = $model->$relationship()->count()) {
                $counter[] = __("replace.of", [
                    "name"  => ucwords(trans_choice("choice.$text", $c)),
                    "count" => $c,
                ]);
            }
        }

        return $counter;
    }

    /**
     * Mass delete relationships with events being fired.
     *
     * @param  $model
     * @param  $relationships
     *
     * @return void
     */
    public function deleteRelationships($model, $relationships)
    {
        foreach ((array) $relationships as $relationship) {
            if (is_callable($model->$relationship) && $model->$relationship() instanceof BelongsToMany) {
                $model->$relationship()->detach();
            }
            //else {
            //    if (empty($model->$relationship)) {
            //        continue;
            //    }
            //dd($model->$relationship);
            //$items = $model->$relationship->all();
            //
            //if ($items instanceof Collection) {
            //    $items = $items->all();
            //}
            //foreach ((array) $items as $item) {
            //    $item->delete();
            //}
            //}
        }
    }

    /**
     * @param $message
     */
    protected function errorMessage($message)
    {
        return $this->response($message, 422);
    }

    /**
     * @param  null  $message
     * @param  null  $urlOrCode
     * @param  int  $code
     */
    protected function response($message = null, $urlOrCode = null, int $code = 200)
    {
        !$message && ($message = __("messages.success"));
        return $this->controller_response($message, $urlOrCode, $code);
    }
}

<?php

namespace App\Providers;

use App\Models\ClassificationAlertSetting;
use App\Models\ExternalCustomer;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $proper = 'App\Http\Controllers\Proper';
    protected $HumanResource = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Route::model('ClassificationAlertSetting', ClassificationAlertSetting::class);
        Route::model('UserId', User::class);
        Route::model('Request', Request::class);
        //Route::model('ExternalCustomer', ExternalCustomer::class);
        Route::bind('ExternalCustomer', fn($id) => ExternalCustomer::withTrashed()->where('id', $id)->firstOrFail());
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapWsataRoutes();
        $this->mapHrRoutes();
    }

    /**
     *
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWsataRoutes()
    {
        Route::middleware('web')->namespace($this->proper)->group(base_path('routes/wsata.php'));
    }

    protected function mapHrRoutes()
    {
        Route::middleware('web')
            ->namespace($this->HumanResource)
            ->group(base_path('routes/hr.php'));
    }
}

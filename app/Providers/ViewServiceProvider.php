<?php

namespace App\Providers;

use App\Models\User;
use App\Http\View\Composers\AppComposerInterface;
use Illuminate\Support\Facades\View as ViewComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as View;

class ViewServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        /** Global All Views */
        ViewComposer::composer("*", function (View $view) {
            $locale = $this->app->getLocale();
            $view->with([
                "APP_NAME"   => config('app.name'),
                "APP_URL"    => config('app.url'),
                "align"      => $locale === "ar" ? "right" : "left",
                "align2"     => $locale === "ar" ? "left" : "right",
                "direction"  => $locale === "ar" ? "rtl" : "ltr",
                "direction2" => $locale === "ar" ? "ltr" : "rtl",
                "locale"     => $locale,
                "AUTH_USER"  => auth()->check() ? auth()->user() : new User(),
            ]);
        });

        foreach (glob(__DIR__.'/../Http/View/Composers/*.php') as $composer) {
            $class = "\\App\\Http\\View\\Composers\\".pathinfo($composer, PATHINFO_FILENAME);
            $implements = class_implements($class);
            if (!$class || empty($implements)) continue;
            if (is_array($implements) && in_array(AppComposerInterface::class, $implements)) {
                ViewComposer::composer($class::views(), $class);
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

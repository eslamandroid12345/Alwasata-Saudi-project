<?php

namespace App\Http;

use App\Http\Middleware\Accountant;
use App\Http\Middleware\AccountantAdminGeneralmanager;
use App\Http\Middleware\Admin;
use App\Http\Middleware\adminAndGeneralManager;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\BankDelegateMiddleware;
use App\Http\Middleware\ChatMiddleware;
use App\Http\Middleware\CheckForMaintenanceMode;
use App\Http\Middleware\CheckVisitAPIS;
use App\Http\Middleware\Collaborator;
use App\Http\Middleware\CollaboratorOrPropertor;
use App\Http\Middleware\Cors;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\EnsureThereIsNoCalculaterSuggestion;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\FundingManager;
use App\Http\Middleware\GeneralManager;
use App\Http\Middleware\GuestUser;
use App\Http\Middleware\HasPropertyRequests;
use App\Http\Middleware\HumanResource;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\LogoutUsers;
use App\Http\Middleware\MortgageManager;
use App\Http\Middleware\Propertor;
use App\Http\Middleware\PropertyAgent;
use App\Http\Middleware\PropertyShowToGuestCustomer;
use App\Http\Middleware\QualityManager;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SalesAgent;
use App\Http\Middleware\SalesManager;
use App\Http\Middleware\SettingsOfCalculatorResult;
use App\Http\Middleware\SuggestionCalculater;
use App\Http\Middleware\Training;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\UserSwitched;
use App\Http\Middleware\VerifyCsrfToken;
use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
        LanguageMiddleware::class,
        HandleCors::class,
        //        \App\Http\Middleware\CustomerAuth::class

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            EnsureThereIsNoCalculaterSuggestion::class,
        ],

        'api' => [
            'throttle:60,1',
            SubstituteBindings::class,
            ForceJsonResponse::class,
            Cors::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                                => Authenticate::class,
        'logout'                              => LogoutUsers::class,
        'auth.basic'                          => AuthenticateWithBasicAuth::class,
        'bindings'                            => SubstituteBindings::class,
        'cache.headers'                       => SetCacheHeaders::class,
        'can'                                 => Authorize::class,
        'guest'                               => RedirectIfAuthenticated::class,
        'signed'                              => ValidateSignature::class,
        'throttle'                            => ThrottleRequests::class,
        'verified'                            => EnsureEmailIsVerified::class,
        'salesagent'                          => SalesAgent::class,
        'collaborator'                        => Collaborator::class,
        'training'                            => Training::class,
        'salesmanager'                        => SalesManager::class,
        'fundingmanager'                      => FundingManager::class,
        'mortgagemanager'                     => MortgageManager::class,
        'generalmanager'                      => GeneralManager::class,
        'qualitymanager'                      => QualityManager::class,
        'admin'                               => Admin::class,
        'accountant'                          => Accountant::class,
        'hr'                                  => HumanResource::class,
        'accountantAndAdminAndGeneralManager' => AccountantAdminGeneralmanager::class,
        'adminAndGeneralManager'              => adminAndGeneralManager::class,
        'userswitched'                        => UserSwitched::class,
        'guestuser'                           => GuestUser::class,
        'SuggestionCalculater'                => SuggestionCalculater::class,
        'SettingsOfCalculatorResult'          => SettingsOfCalculatorResult::class,
        'chatComposerView'                    => ChatMiddleware::class,
        'json.response'                       => ForceJsonResponse::class,
        'cors'                                => Cors::class,
        //        'api_customer' => \App\Http\Middleware\CustomerAuth::class,

        'checkVisitAPIS'              => CheckVisitAPIS::class,
        'propertyagent'               => PropertyAgent::class,
        'HasPropertyRequests'         => HasPropertyRequests::class,
        'propertor'                   => Propertor::class,
        'CollaboratorOrPropertor'     => CollaboratorOrPropertor::class,
        'PropertyShowToGuestCustomer' => PropertyShowToGuestCustomer::class,
        'bank_delegate'                => BankDelegateMiddleware::class,

    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        AuthenticateSession::class,
        SubstituteBindings::class,
        Authorize::class,
        EnsureThereIsNoCalculaterSuggestion::class,
    ];
}

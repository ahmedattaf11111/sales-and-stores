<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    const DEF_NAMESPACE = "App\\Http\\Controllers";
    protected $namespace = self::DEF_NAMESPACE;
    protected $modules = [
        "api" => self::DEF_NAMESPACE,
        "auth" => self::DEF_NAMESPACE,
        "admin-panel-setting" => self::DEF_NAMESPACE,
        "treasury" => self::DEF_NAMESPACE,
        "invoice-category" => self::DEF_NAMESPACE,
        "item-category" => self::DEF_NAMESPACE,
        "store" => self::DEF_NAMESPACE,
        "unit-of-measure" => self::DEF_NAMESPACE,
        "item" => self::DEF_NAMESPACE,
        "account" => self::DEF_NAMESPACE,
        "customer" => self::DEF_NAMESPACE,
        "delegate" => self::DEF_NAMESPACE,
        "supplier" => self::DEF_NAMESPACE . "\\Supplier",
        "purchase-invoice" => self::DEF_NAMESPACE . "\\PurchaseInvoice",
        "admin" => self::DEF_NAMESPACE,
        "shift" => self::DEF_NAMESPACE,
        "treasury-transaction" => self::DEF_NAMESPACE,
        "sale-invoice" => self::DEF_NAMESPACE. "\\SaleInvoice",
        "reports" => self::DEF_NAMESPACE. "\\Reports",
    ];
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        foreach ($this->modules as $name => $controller_namespace) {
            $this->setApiModuleRoutes($name, $controller_namespace);
        }
        $this->routes(function () {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }
    //Commons

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function setApiModuleRoutes(string $module, string $namespace)
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($namespace)
            ->group(base_path("routes/api-routes/$module.php"));
    }
}

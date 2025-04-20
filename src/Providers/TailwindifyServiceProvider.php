<?php

namespace Azuriom\Plugin\Tailwindify\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\Tailwindify\Console\Commands\CompileTailwind;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Tailwindify\Helpers\BootstrapClassReplacer;
use Azuriom\Plugin\Tailwindify\Helpers\BootstrapClassScanner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class TailwindifyServiceProvider extends BasePluginServiceProvider
{
    protected array $middleware = [];
    protected array $middlewareGroups = [];
    protected array $routeMiddleware = [];
    protected array $policies = [];

    public function register(): void
    {
        $this->commands([
            CompileTailwind::class,
        ]);
    }

    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerRouteDescriptions();
        $this->registerAdminNavigation();
        $this->registerUserNavigation();
        $this->setupLogging();
        $this->compileTailwind();
        $this->injectCss();
        //$this->bootstrap();
    }

    protected function bootstrap(): void
    {
        $bootstrapClasses = BootstrapClassScanner::scanForBootstrapClasses();

        foreach ($bootstrapClasses as $bootstrapClass) {
            View::creator('*', function ($view) {
                if (!Request::is('admin*')) {
                    $html = $view->render();
                    $view->with('content', BootstrapClassReplacer::replaceBootstrapClasses($html));
                }
            });
        }
    }

    protected function setupLogging(): void
    {
        $logPath = storage_path('logs/tailwindify.log');

        if (!File::exists($logPath)) {
            File::put($logPath, "📜 Logs de Tailwindify initialisés.\n");
        }
    }

    protected function log(string $message): void
    {
        $timestamp = Carbon::now('Europe/Paris')->format('d/m/Y H:i:s');
        Log::info($message);
        File::append(storage_path('logs/tailwindify.log'), "[{$timestamp}] " . $message . "\n");
    }

    protected function compileTailwind(): void
    {
        $theme = Setting::where('name', 'theme')->value('value') ?? 'default';
        $themePath = base_path("resources/themes/{$theme}");

        if (!File::exists($themePath)) {
            $this->log("❌ Le thème '{$theme}' n'existe pas dans /resources/themes");
            return;
        }

        $this->log("📢 Lancement de la compilation TailwindCSS...");
        Artisan::call('tailwindify:compile');
    }

    protected function injectCss(): void
    {
        $theme = Setting::where('name', 'theme')->value('value') ?? 'default';
        $themePath = base_path("resources/themes/{$theme}");
        $outputCss = "{$themePath}/assets/css/output.css";

        View::composer('*', function ($view) use ($theme, $outputCss) {
            if (!Request::is('admin*')) {
                if (File::exists($outputCss)) {
                    $view->with('tailwindify_css', theme_asset("css/output.css"));
                } else {
                    $this->log("⚠️ Aucun output.css trouvé pour le thème '{$theme}', utilisation du CSS par défaut du plugin.");
                    $view->with('tailwindify_css', plugin_asset('tailwindify', "css/output.css"));
                }
            } else {
                $view->with('tailwindify_css', null);
            }
        });
    }

    protected function adminNavigation(): array
    {
        return [
            'tailwindify' => [
                'name' => 'Tailwindify',
                'type' => 'dropdown',
                'icon' => 'bi bi-gear-fill',
                'route' => 'tailwindify.admin.*',
                'items' => [
                    'tailwindify.admin.index' => [
                        'name' => 'Summary',
                        'permission' => 'tailwindify.index',
                    ],
                    'tailwindify.admin.logs' => [
                        'name' => 'Logs',
                        'permission' => 'tailwindify.logs',
                    ],
                    'tailwindify.admin.bootstrap' => [
                        'name' => 'Bootstrap Conversion',
                        'permission' => 'tailwindify.bootstrap',
                    ],
                ],
            ],
        ];
    }
}

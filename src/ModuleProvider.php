<?php
declare(strict_types = 1);
namespace RabbitCMS\Themes;

use Illuminate\Foundation\AliasLoader;
use Illuminate\View\Factory;
use RabbitCMS\Modules\Contracts\PackageContract;
use RabbitCMS\Modules\Managers\Modules;
use RabbitCMS\Modules\ModuleProvider as BaseModuleProvider;
use RabbitCMS\Themes\Facades\Themes as ThemesFacade;
use RabbitCMS\Themes\Managers\Themes;

/**
 * Class ModuleProvider.
 */
class ModuleProvider extends BaseModuleProvider
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'themes';
    }

    public function register()
    {
        parent::register();

        AliasLoader::getInstance([
            'Themes' => ThemesFacade::class
        ]);

//        $this->app->singleton(['themes' => Themes::class], function ($app) {
//            return new Themes($app);
//        });
//
//        $this->app->singleton('themes.commands.scan', function () {
//            return new ScanCommand($this->app->make('modules'));
//        });
//
//        $this->app->singleton('themes.commands.enable', function () {
//            return new EnableCommand($this->app->make('modules'));
//        });
//
//        $this->app->singleton('themes.commands.disable', function () {
//            return new DisableCommand($this->app->make('modules'));
//        });
//
//        $this->app->singleton('themes.commands.list', function () {
//            return new ListCommand($this->app->make('modules'));
//        });
//
//        $this->commands([
//            'themes.commands.scan',
//            'themes.commands.enable',
//            'themes.commands.disable',
//            'themes.commands.list',
//        ]);
    }

    /**
     * @param Modules $modules
     */
    public function boot(Modules $modules, Factory $view, Themes $themes)
    {

        $theme = $this->module->config('theme', 'default');
        if (!$themes->has($theme)) {
            return;
        }
        $theme = $themes->get($theme);

        $modules->setAssetResolver(function ($module, $path, $secure) use ($theme, $themes) {
            if (is_file($theme->getPath("public/{$module}/{$path}"))) {
                return $this->app->make('url')
                    ->asset($themes->getAssetsPath() . "/{$theme->getName()}/{$module}/" . $path, $secure);
            }
            return null;
        });

        $path = $theme->getPath();
        if (is_dir($path)) {
            $modules->enabled()->each(function (PackageContract $module) use ($theme, $view) {
                $modulePath = $theme->getPath("{$module->getName()}/views");
                if (is_dir($modulePath)) {
                    $view->prependNamespace($module->getName(), [$modulePath]);
                }
            });
        }
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return ['themes'];
    }
}

<?php
declare(strict_types = 1);
namespace RabbitCMS\Themes;

use Illuminate\Foundation\AliasLoader;
use Illuminate\View\Factory;
use RabbitCMS\Modules\Contracts\PackageContract;
use RabbitCMS\Modules\Managers\Modules;
use RabbitCMS\Modules\ModuleProvider as BaseModuleProvider;
use RabbitCMS\Themes\Console\ScanCommand;
use RabbitCMS\Themes\Facades\Themes as ThemesFacade;
use RabbitCMS\Themes\Managers\Themes;
use RabbitCMS\Themes\Managers\Themes as ThemesManager;

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
        $this->app->singleton('themes.commands.scan', function () {
            return new ScanCommand($this->app->make(ThemesManager::class));
        });
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
        $this->commands([
            'themes.commands.scan',
//            'themes.commands.enable',
//            'themes.commands.disable',
//            'themes.commands.list',
        ]);
    }

    /**
     * @param Modules $modules
     */
    public function boot(Modules $modules, Factory $view, Themes $themes)
    {
        $themeName = $this->module->config('theme');
        $foundThemes = [];
        while ($themeName !== null && $themes->has($themeName)) {
            /* @var Theme $theme */
            $foundThemes[] = $theme = $themes->get($themeName);
            $themeName = $theme->getExtends();
        }

        $modules->setAssetResolver(function ($module, $path, $secure) use ($themes, $foundThemes) {
            foreach ($foundThemes as $theme) {
                if (is_file($theme->getPath("public/{$module}/{$path}"))) {
                    return $this->app->make('url')
                        ->asset($themes->getAssetsPath() . "/{$theme->getName()}/{$module}/" . $path, $secure);
                }
            }
            return null;
        });

        foreach (array_reverse($foundThemes) as $theme) {
            $path = $theme->getPath();
            if (is_dir($path)) {
                $modules->enabled()->each(function (PackageContract $module) use ($theme, $view) {
                    $modulePath = $theme->getPath("views/{$module->getName()}");
                    if (is_dir($modulePath)) {
                        $view->prependNamespace($module->getName(), [$modulePath]);
                    }
                });
            }
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

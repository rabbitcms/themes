<?php
declare(strict_types = 1);
namespace RabbitCMS\Themes;

use Illuminate\View\Factory;
use RabbitCMS\Modules\Contracts\ModulesManager;
use RabbitCMS\Modules\Module;
use RabbitCMS\Modules\ModuleProvider as BaseModuleProvider;

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

    /**
     * @param ModulesManager $manager
     */
    public function boot(ModulesManager $manager, Factory $view)
    {
        $theme = $this->module->config('theme', 'default');
        $path = "{$this->app->basePath()}/{$this->module->config('path', 'resources/themes')}/$theme";
        if (is_dir($path)) {
            $manager->enabled()->each(function (Module $module) use ($path, $view) {
                $modulePath = "$path/{$module->getName()}";
                if (is_dir($modulePath)) {
                    $view->prependNamespace($module->getName(), [$modulePath]);
                }
            });
        }
        dd($view->getFinder());
    }


    /**
     * @return array
     */
    public function provides(): array
    {
        return ['themes'];
    }
}

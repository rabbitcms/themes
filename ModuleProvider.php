<?php
declare(strict_types=1);
namespace RabbitCMS\Themes;

use Illuminate\Foundation\AliasLoader;
use RabbitCMS\Modules\ModuleProvider as BaseModuleProvider;

/**
 * Class ModuleProvider.
 */
class ModuleProvider extends BaseModuleProvider
{
    /**
     * @return string
     */
    public function name():string
    {
        return 'themes';
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(['themes' => Themes::class], Themes::class);

        AliasLoader::getInstance(['Themes' => ThemesFacade::class]);
    }

    /**
     * @return array
     */
    public function provides():array
    {
        return ['themes'];
    }
}

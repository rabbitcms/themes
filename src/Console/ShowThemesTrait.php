<?php
declare(strict_types = 1);
namespace RabbitCMS\Themes\Console;

use Illuminate\Console\Command;
use RabbitCMS\Modules\Contracts\PackageContract;
use RabbitCMS\Modules\Module;
use RabbitCMS\Modules\Repository;
use RabbitCMS\Themes\Theme;

/**
 * Class ShowModulesTrait.
 *
 * @mixin Command
 */
trait ShowThemesTrait
{
    /**
     * @param Repository $modules
     */
    protected function showModules(Repository $modules)
    {
        $this->table(
            ['Name', 'Path', 'Extends', 'Description'],
            $modules->map(function (Theme $module) {
                return [
                    $module->getName(),
                    preg_replace('/^' . preg_quote(base_path() . '/', '/') . '/', '', $module->getPath()),
                    $module->getExtends() ?? '-',
                    $module->getDescription(),
                ];
            })
        );
    }
}

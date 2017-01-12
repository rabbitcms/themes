<?php
declare(strict_types=1);
namespace RabbitCMS\Themes\Facades;

use Illuminate\Support\Facades\Facade;
use RabbitCMS\Themes\Managers\Themes as ThemesManager;

/**
 * Class Modules Facade.
 * @method static has(string $name): bool
 * @method static current(): Theme|null
 */
class Themes extends Facade
{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return ThemesManager::class;
    }
}

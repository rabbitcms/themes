<?php
declare(strict_types=1);
namespace RabbitCMS\Themes;

use Illuminate\Support\Facades\Facade;

/**
 * Class ThemesFacade.
 */
class ThemesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'themes';
    }
}

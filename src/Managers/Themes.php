<?php
declare(strict_types=1);
namespace RabbitCMS\Themes\Managers;

use RabbitCMS\Modules\Contracts\PackagesManager;
use RabbitCMS\Modules\Contracts\PackageContract;
use RabbitCMS\Modules\Managers\ManagerImpl;
use RabbitCMS\Themes\Theme;
use SplFileInfo;

/**
 * Class Themes.
 * @package RabbitCMS\Modules\Managers
 */
class Themes implements PackagesManager
{
    use ManagerImpl;

    /**
     * @inheritdoc
     */
    protected function cacheFile(): string
    {
        return 'bootstrap/cache/themes.json';
    }

    /**
     * @inheritdoc
     */
    protected function restoreItem(array $item): PackageContract
    {
        return new Theme($item);
    }

    /**
     * @param SplFileInfo $file
     * @param array $composer
     * @return null|Theme
     */
    protected function checkPackage(SplFileInfo $file, array $composer)
    {
        if (empty($composer['extra']['theme']) || !is_array($composer['extra']['theme'])) {
            return null;
        }
        $theme = $composer['extra']['theme'];
        if (empty($theme['name'])) {
            $names = explode('/', $composer['name']);
            $theme['name'] = $names[1];
        }

        $theme['description'] = array_key_exists('description', $composer) ? $composer['description'] : '';
        $theme['path'] = $file->getPathname();
        $theme = new Theme($theme);

        $this->updateLink(
            $theme->getPath('public'),
            public_path($this->getAssetsPath() . '/' . $theme->getName())
        );
        return $theme;
    }

    /**
     * @inheritdoc
     */
    public function getAssetsPath(): string
    {
        return $this->config('assets', 'themes');
    }

    /**
     * @return Theme|null
     */
    public function current()
    {
        $theme = $this->config('theme');
        if ($this->has($theme)) {
            /* @var Theme $theme */
            $theme = $this->get($theme);
            return $theme;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function config(string $key, $default = null)
    {
        return $this->app->make('config')->get('module.themes.' . $key, $default);
    }
}

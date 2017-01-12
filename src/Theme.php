<?php
declare(strict_types = 1);
namespace RabbitCMS\Themes;

use RabbitCMS\Modules\Contracts\PackageContract;

/**
 * Class Module.
 */
class Theme implements PackageContract
{
    /**
     * Module path.
     *
     * @var string
     */
    protected $path;

    /**
     * Module name.
     *
     * @var string
     */
    protected $name;

    /**
     * Module description.
     *
     * @var string
     */
    protected $description;

    /**
     * Module enabled.
     *
     * @var bool
     */
    protected $enabled;

    /**
     * @var bool
     */
    protected $system;

    /**
     * Module constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->path = $options['path'];
        $this->name = array_key_exists('name', $options) ? $options['name'] : basename($this->path);
        $this->description = array_key_exists('description', $options) ? $options['description'] : '';
        $this->enabled = array_key_exists('enabled', $options) ? $options['enabled'] : true;
        $this->system = array_key_exists('system', $options) ? $options['system'] : false;
    }

    /**
     * Get module name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set enabled module.
     *
     * @param bool $value
     */
    public function setEnabled(bool $value = true)
    {
        $this->enabled = $value;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->system;
    }

    /**
     * Get module path.
     *
     * @param string $path
     *
     * @return string
     */
    public function getPath(string $path = ''): string
    {
        return $this->path . ($path ? '/' . $path : '');
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'path' => $this->path,
            'name' => $this->name,
            'description' => $this->description,
            'enabled' => $this->enabled,
            'system' => $this->system,
        ];
    }

    /**
     * Get module description.
     *
     * @return string
     */
    public function getDescription():string
    {
        return $this->description;
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string $key
     * @param  mixed $default
     *
     * @return mixed
     */
    public function config(string $key, $default = null)
    {
        return app('config')->get("theme.{$this->getName()}.{$key}", $default);
    }
}

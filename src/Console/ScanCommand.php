<?php
declare(strict_types = 1);
namespace RabbitCMS\Themes\Console;

use Illuminate\Console\Command;
use RabbitCMS\Themes\Managers\Themes;

/**
 * Class ScanCommand.
 * @package RabbitCMS\Modules
 */
class ScanCommand extends Command
{
    use ShowThemesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes:scan {--pretend : Only show found modules.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rescan available themes';

    /**
     * @var Themes
     */
    protected $themes;

    /**
     * ScanCommand constructor.
     *
     * @param Themes $themes
     */
    public function __construct(Themes $themes)
    {
        parent::__construct();
        $this->themes = $themes;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->themes->scan(!$this->option('pretend'));
        $this->showModules($this->themes->all());
    }
}

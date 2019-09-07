<?php


namespace ExoUNX\Vasri\Commands;

use Exception;
use Illuminate\Console\Command;
use ExoUNX\Vasri\ManifestBuilder;

/**
 * Class VasriCommand
 *
 * @package ExoUNX\Vasri
 * @author  Gaige Lama <gaigelama@gmail.com>
 * @license MIT License
 * @link    https://github.com/ExoUNX/Vasri
 */
class VasriCommand extends Command
{
    /**
     * Command signature
     * @var string
     */
    protected $signature = 'vasri:build';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Build Manifest';

    /**
     * @var ManifestBuilder
     */
    private $manifest;

    /**
     * Inherits constructor from Illuminate\Console\Command
     */
    public function __construct()
    {
        parent::__construct();
        $this->manifest = new ManifestBuilder();

    }

    /**
     * The executing method
     * @throws Exception
     */
    public function handle()
    {
        $this->manifest->deployManifest();
    }
}

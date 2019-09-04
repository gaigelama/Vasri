<?php


namespace ExoUNX\Vasri\Commands;

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
     * @var string
     */
    protected $signature = 'vasri:build';

    /**
     * @var string
     */
    protected $description = 'Build Manifest';

    /**
     * VasriCommand constructor. Inherits constructor from Illuminate\Console\Command
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The executing method
     */
    public function handle()
    {
        $manifest = new ManifestBuilder();

        $manifest->buildManifest();

    }
}

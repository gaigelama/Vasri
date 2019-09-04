<?php


namespace ExoUNX\Vasri;

use ExoUNX\Vasri\ManifestReader;
use Exception;

/**
 * Constructs Manifest
 * Class ManifestBuilder
 *
 * @package ExoUNX\Vasri
 * @author  Gaige Lama <gaigelama@gmail.com>
 * @license MIT License
 * @link    https://github.com/ExoUNX/Vasri
 */
class ManifestBuilder
{

    /**
     * @var
     */
    private $mixManifest;

    /**
     * @var \ExoUNX\Vasri\ManifestReader
     */
    private $manifestReader;

    /**
     * ManifestBuilder constructor.
     */
    public function __construct()
    {
        $this->mixManifest    = config('vasri.mix-manifest');
        $this->manifestReader = $this->loadManifestReader();
    }

    /**
     * @return array
     * @throws Exception
     */
    private function buildAssets(): array
    {
        $vasriManifest = [];
        if ($this->mixManifest === true) {
            $manifest = $this->manifestReader->getMixManifest();
            foreach ($manifest as $key => $val) {
                $vasriManifest[] = $key;
            }

        } elseif ( ! empty(config('vasri.assets'))) {

            $vasriManifest = config('vasri.assets');

        } else {

            throw new Exception('No manifest or assets found');

        }

        return $vasriManifest;
    }

    /**
     * @throws Exception
     */
    public function buildManifest(): void
    {
        file_put_contents(base_path('vasri-manifest.json'),
            stripslashes(json_encode($this->buildAssets(), JSON_PRETTY_PRINT)));
    }

    /**
     * @return \ExoUNX\Vasri\ManifestReader
     */
    private function loadManifestReader(): ManifestReader
    {
        return new ManifestReader();
    }

}

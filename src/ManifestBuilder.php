<?php


namespace ExoUNX\Vasri;

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
     * @var ManifestReader
     */
    private $manifestReader;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * ManifestBuilder constructor.
     */
    public function __construct()
    {
        $this->mixManifest    = config('vasri.mix-manifest');
        $this->manifestReader = new ManifestReader();
        $this->builder        = new Builder();
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
     * @return array
     * @throws Exception
     */
    private function buildManifest(): array
    {
        $manifest = [];
        foreach ($this->buildAssets() as $asset) {
            $manifest[$asset] = [
                'sri'     => $this->builder->sri($asset),
                'version' => $this->builder->versioning($asset)
            ];
        }

        return $manifest;
    }

    /**
     * @throws Exception
     */
    public function deployManifest(): void
    {
        file_put_contents(base_path('vasri-manifest.json'),
            stripslashes(json_encode($this->buildManifest(), JSON_PRETTY_PRINT)));
    }

}

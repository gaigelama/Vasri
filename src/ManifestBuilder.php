<?php


namespace ExoUNX\Vasri;

use Illuminate\Support\Facades\File;
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
     * @var array
     */
    private $vasriConfig;
    /**
     * @var bool
     */
    private $isMixManifestEnabled;

    /**
     * @var string
     */
    private $mixManifestPath;
    /**
     * @var array
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
     * @throws Exception
     */
    public function __construct()
    {
        $this->manifestReader       = new ManifestReader();
        $this->builder              = new Builder();
        $this->vasriConfig          = config('vasri');
        $this->isMixManifestEnabled = $this->vasriConfig['mix-manifest'];
        $this->mixManifestPath      = public_path('mix-manifest.json');
    }

    /**
     * @param  array  $mixManifest
     * @param  array  $vasriConfigAssets
     *
     * @return array
     * @throws Exception
     */
    private function buildAssets(array $mixManifest = [], array $vasriConfigAssets = []): array
    {
        $vasriManifest = [];

        if ($this->isMixManifestEnabled && File::exists($this->mixManifestPath)) {

            foreach ($mixManifest as $key => $val) {
                $vasriManifest[] = $key;
            }

        } elseif ( ! empty($vasriConfigAssets)) {

            $vasriManifest = $this->vasriConfig['assets'];

        } else {

            throw new Exception('No manifest or valid assets found');

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

        foreach (
            $this->buildAssets(
                $this->manifestReader->getManifest($this->mixManifestPath),
                $this->vasriConfig['assets']
            ) as $asset
        ) {
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

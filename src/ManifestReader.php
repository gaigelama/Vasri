<?php


namespace ExoUNX\Vasri;


use Illuminate\Support\Facades\File;
use Exception;

/**
 * Class ManifestReader
 *
 * @package ExoUNX\Vasri
 * @author  Gaige Lama <gaigelama@gmail.com>
 * @license MIT License
 * @link    https://github.com/ExoUNX/Vasri
 */
class ManifestReader
{

    /**
     * @var
     */
    private $mixManifest;

    /**
     * @var
     */
    private $vasriManifest;

    /**
     * ManifestReader constructor.
     */
    public function __construct()
    {
        $this->mixManifest   = public_path('mix-manifest.json');
        $this->vasriManifest = base_path('vasri-manifest.json');
    }

    /**
     * @param  string  $file
     *
     * @return array
     */
    private function jsonFileToArray(string $file): array
    {
        return json_decode(file_get_contents($file), true);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getMixManifest(): array
    {
        if (File::exists($this->mixManifest)) {
            return $this->jsonFileToArray($this->mixManifest);
        } else {
            throw new Exception('Incorrect file path or file does not exist for mix-manifest.json');
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getVasriManifest(): array
    {
        if (File::exists($this->mixManifest)) {
            return $this->jsonFileToArray($this->vasriManifest);
        } else {
            throw new Exception('Incorrect file path or file does not exist for vasri-manifest.json');
        }
    }

}

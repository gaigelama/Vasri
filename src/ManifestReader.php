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
     * ManifestReader constructor.
     */
    public function __construct()
    {
        $this->mixManifest = public_path('mix-manifest.json');
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getMixManifest(): array
    {
        if (File::exists($this->mixManifest)) {
            return json_decode(file_get_contents($this->mixManifest), true);
        } else {
            throw new Exception('Incorrect file path or file does not exist for mix-manfest.json');
        }
    }

}

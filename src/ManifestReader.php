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
     * @param  string  $file
     *
     * @return array
     */
    private function jsonFileToArray(string $file): array
    {
        return json_decode(file_get_contents($file), true);
    }

    public function getManifest(string $file): array
    {
        if (File::exists($file)) {
            return $this->jsonFileToArray($file);
        } else {
            throw new Exception('Incorrect file path or file does not exist for '.$file);
        }
    }

}

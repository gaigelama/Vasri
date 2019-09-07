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
     * @throws Exception
     */
    public function getManifest(string $file): array
    {
        if (File::exists($file)) {

            return json_decode(file_get_contents($file), true);

        } else {

            throw new Exception(sprintf('Incorrect file path or file does not exist for %s', $file));

        }
    }

}

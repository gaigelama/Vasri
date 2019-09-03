<?php


namespace ExoUNX\Vasri;

use Exception;

/**
 * Builds resources for the access class
 * Class Builder
 *
 * @package ExoUNX\Vasri
 * @author  Gaige Lama <gaigelama@gmail.com>
 * @license MIT License
 * @link    https://github.com/ExoUNX/Vasri
 */
class Builder
{

    /**
     * SHA256 Hash Algorithm
     */
    private const SHA256 = 'sha256';

    /**
     * SHA384 Hash Algorithm
     */
    private const SHA384 = 'sha384';

    /**
     * SHA512 Hash Algorithm
     */
    private const SHA512 = 'sha512';

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        //TODO Set config options here
    }

    /**
     * @param  string  $file
     *
     * @param $algorithm
     *
     * @return string
     * @throws Exception
     */
    public function sri(string $file, $algorithm)
    {
        return $algorithm.'-'.base64_encode(
                hash_file(
                    self::selectAlgorithm(
                        $algorithm
                    ), $file, true
                )
            );
    }

    /**
     * @param  string  $file
     *
     * @return string
     */
    public function versioning(string $file)
    {
        return '?id='.hash_file('md5', $file);
    }

    /**
     * @param  string  $path
     *
     * @return string
     * @throws Exception
     */
    public function attribute(string $path): string
    {
        $extension = self::parseExtension($path);

        switch ($extension) {
            case 'css':
                $attribute = 'href';
                break;
            case 'js':
                $attribute = 'src';
                break;
            default:
                throw new Exception('Invalid or Unsupported Extension');
        }

        return $attribute;
    }

    /**
     * @param  string  $algorithm
     *
     * @return string
     * @throws Exception
     */
    private static function selectAlgorithm(string $algorithm): string
    {
        if ($algorithm === self::SHA256
            || $algorithm === self::SHA384
            || $algorithm === self::SHA512
        ) {
            return $algorithm;
        } else {
            throw new Exception('Invalid or Unsupported Hash Algorithm');
        }
    }

    private static function parseExtension(string $path): string
    {
        return preg_replace("#\?.*#", "", pathinfo($path, PATHINFO_EXTENSION));
    }

}

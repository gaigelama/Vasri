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
     * Constructs a valid SRI string
     *
     * @param  string  $file
     *
     *
     * @return string
     * @throws Exception
     */
    public function sri(string $file)
    {
        $algorithm = self::selectAlgorithm();

        return $algorithm.'-'.base64_encode(
                hash_file($algorithm, public_path($file), true)
            );
    }

    /**
     * Constructs a query string containing the md5 hash of the input file
     *
     * @param  string  $file
     *
     * @return string
     */
    public function versioning(string $file)
    {
        return '?id='.hash_file('md5', public_path($file));
    }

    /**
     * Sets the HTML attribute based on the file extension and throws an exception if invalid
     *
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

    public function crossOrigin(string $keyword): string
    {
        return "crossorigin=\"".$keyword."\"";
    }

    /**
     * Checks config for hash algorithm and if nothing is found default to SHA384
     *
     * @return string
     * @throws Exception
     */
    private
    static function selectAlgorithm(): string
    {
        if ( ! empty(config('vasri.hash-algorithm'))) {
            $algorithm = config('vasri.hash-algorithm');
            if ($algorithm !== self::SHA256
                && $algorithm !== self::SHA384
                && $algorithm !== self::SHA512
            ) {
                throw new Exception('Invalid or Unsupported Hash Algorithm');
            }
        } else {
            $algorithm = self::SHA384;
        }

        return $algorithm;
    }

    /**
     * Removes everything but the short extension
     *
     * @param  string  $path
     *
     * @return string
     */
    private
    static function parseExtension(
        string $path
    ): string {
        return preg_replace("#\?.*#", "", pathinfo($path, PATHINFO_EXTENSION));
    }

}

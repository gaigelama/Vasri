<?php


namespace ExoUNX\Vasri;

use Exception;

/**
 * Class Builder
 * @package ExoUNX\Vasri
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
     *
     */
    private const SCRIPT = 'script';

    /**
     *
     */
    private const LINK = 'link';

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
     * @param  string  $type
     *
     * @return string
     * @throws Exception
     */
    public function attribute(string $type): string
    {

        switch ($type) {
            case self::SCRIPT:
                $attr = 'src';
                break;
            case self::LINK:
                $attr = 'href';
                break;
            default:
                throw new Exception("Invalid or Unsupported Attribute");
        }

        return $attr;
    }

    /**
     * @param  string  $algorithmrithm
     *
     * @return string
     * @throws Exception
     */
    private static function selectAlgorithm(string $algorithmrithm): string
    {
        if ($algorithmrithm === self::SHA256
            || $algorithmrithm === self::SHA384
            || $algorithmrithm === self::SHA512
        ) {
            return $algorithmrithm;
        } else {
            throw new Exception('Invalid or Unsupported Hash Algorithm');
        }
    }

}

<?php


namespace ExoUNX\Vasri;

use Illuminate\Support\Facades\File;
use Exception;

/**
 * Access class for Vasri
 * Class Vasri
 *
 * @package ExoUNX\Vasri
 * @author  Gaige Lama <gaigelama@gmail.com>
 * @license MIT License
 * @link    https://github.com/ExoUNX/Vasri
 */
class Vasri
{

    /**
     * @var Builder|null
     */
    private static $builder = null;

    /**
     * Vasri constructor.
     */
    public function __construct()
    {
        self::$builder = self::loadBuilder();
    }

    /**
     * @param  string  $path
     * @param  bool  $enableVersioning
     * @param  bool  $enableSRI
     *
     * @return string
     * @throws Exception
     */
    public static function vasri(
        string $path,
        bool $enableVersioning = true,
        bool $enableSRI = true
    ): string {
        $output = '';

        if (self::isPath($path) === true) {
            $output .= self::addAttribute($path, $enableVersioning);
            if ($enableSRI === true) {
                $output .= self::addSRI($path);
            }

            return $output;

        } else {
            throw new Exception('Incorrect file path or file does not exist');
        }
    }

    /**
     * @param  string  $path
     *
     * @return string
     */
    private static function addVersioning(string $path): string
    {
        return self::$builder->versioning($path);
    }

    /**
     * @param  string  $path
     * @param  string  $hash
     *
     * @return string
     * @throws Exception
     */
    private static function addSRI(string $path, string $hash = 'sha384'): string
    {
        return self::$builder->sri($path, $hash);
    }

    /**
     * @return Builder
     */
    private static function loadBuilder(): Builder
    {
        return new Builder();
    }

    /**
     * @param  string  $path
     * @param  bool  $enableVersioning
     *
     * @return string
     * @throws Exception
     */
    private static function addAttribute(string $path, bool $enableVersioning)
    {
        try {
            if ($enableVersioning === true) {
                $output = self::$builder->attribute($path)."=\"".self::addVersioning($path)."\"";
            } else {
                $output = self::$builder->attribute($path)."=\"".$path."\"";
            }

            return $output;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     *
     * @param  string  $path
     *
     * @return bool
     */
    private static function isPath(string $path): bool
    {
        return File::exists(public_path($path));
    }

}

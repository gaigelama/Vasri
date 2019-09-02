<?php


namespace ExoUNX\Vasri;

use Illuminate\Support\Facades\File;
use Exception;

/**
 * Class Vasri
 * @package ExoUNX\Vasri
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
     * @param  string  $type
     * @param  string  $path
     * @param  bool  $enable_versioning
     * @param  bool  $enable_sri
     *
     * @return string
     * @throws Exception
     */
    public static function vasri(
        string $type,
        string $path,
        bool $enable_versioning = true,
        bool $enable_sri = true
    ): string {
        $output = '';

        if (self::checkPath($path) === true) {
            $output .= self::addAttribute($type, $path, $enable_versioning);
            if ($enable_sri === true) {
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
     * @param  string  $type
     * @param  string  $path
     * @param  bool  $enable_versioning
     *
     * @return string
     * @throws Exception
     */
    private static function addAttribute(string $type, string $path, bool $enable_versioning)
    {
        if ($enable_versioning === true) {
            try {
                return self::$builder->attribute($type)."=\"".self::addVersioning($path)."\"";
            } catch (Exception $e) {
                throw new Exception($e);
            }
        } else {
            try {
                return self::$builder->attribute($type)."=\"".$path."\"";
            } catch (Exception $e) {
                throw new Exception($e);
            }
        }
    }

    /**
     *
     * @param  string  $path
     *
     * @return bool
     */
    private static function checkPath(string $path): bool
    {
        return File::exists(public_path($path));
    }

}

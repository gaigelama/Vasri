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
    private $builder = null;

    /**
     * Vasri constructor.
     */
    public function __construct()
    {
        $this->builder = $this->loadBuilder();
    }

    /**
     * @param  string  $path
     * @param  bool  $enableVersioning
     * @param  bool  $enableSRI
     *
     * @return string
     * @throws Exception
     */
    public function vasri(
        string $path,
        bool $enableVersioning = true,
        bool $enableSRI = true
    ): string {
        $output = '';

        if (self::isPath($path) === true) {
            $output .= $this->addAttribute($path, $enableVersioning);
            if ($enableSRI === true) {
                $output .= $this->addSRI($path);
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
    private function addVersioning(string $path): string
    {
        return $this->builder->versioning($path);
    }

    /**
     * @param  string  $path
     *
     * @return string
     * @throws Exception
     */
    private function addSRI(string $path): string
    {
        return "integrity=\"".$this->builder->sri($path)."\"";
    }

    /**
     * @return Builder
     */
    private function loadBuilder(): Builder
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
    private function addAttribute(string $path, bool $enableVersioning)
    {
        try {
            if ($enableVersioning === true) {
                $output = $this->builder->attribute($path)."=\"".$path.self::addVersioning($path)."\"";
            } else {
                $output = $this->builder->attribute($path)."=\"".$path."\"";
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

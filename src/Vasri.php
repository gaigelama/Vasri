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
     * @var Builder
     */
    private $builder;

    /**
     * @var ManifestReader
     */
    private $manifestReader;

    /**
     * @var array
     */
    private $vasriManifest;

    /**
     * Vasri constructor.
     */
    public function __construct()
    {
        $this->builder        = new Builder();
        $this->manifestReader = new ManifestReader();
        $this->vasriManifest  = $this->manifestReader->getVasriManifest();
    }

    /**
     * @param  string  $file
     * @param  bool  $enableVersioning
     * @param  bool  $enableSRI
     *
     * @param  string  $keyword
     *
     * @return string
     * @throws Exception
     */
    public function vasri(
        string $file,
        bool $enableVersioning = true,
        bool $enableSRI = true,
        string $keyword = 'anonymous'
    ): string {
        $output = '';

        if (self::isFile($file) === true) {
            $output .= $this->addAttribute($file, $enableVersioning);
            if ($enableSRI === true) {
                $output .= $this->addSRI($file, $keyword);
            }

            return $output;

        } else {
            throw new Exception('Incorrect file path or file does not exist for local asset');
        }
    }

    /**
     * @param  string  $file
     *
     * @return string
     */
    private function addVersioning(string $file): string
    {
        return $this->builder->versioning($file);
    }

    /**
     * @param  string  $file
     *
     * @return string
     * @throws Exception
     */
    private function addSRI(string $file, string $keyword): string
    {
        return " integrity=\"".$this->vasriManifest[$file]['sri']."\" ".$this->builder->crossOrigin($keyword);
    }

    /**
     * @param  string  $file
     * @param  bool  $enableVersioning
     *
     * @return string
     * @throws Exception
     */
    private function addAttribute(string $file, bool $enableVersioning)
    {
        try {
            if ($enableVersioning === true) {
                $output = $this->builder->attribute($file)."=\"".$file.$this->vasriManifest[$file]['version']."\"";
            } else {
                $output = $this->builder->attribute($file)."=\"".$file."\"";
            }

            return $output;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     *
     * @param  string  $file
     *
     * @return bool
     */
    private static function isFile(string $file): bool
    {
        return File::exists(public_path($file));
    }

}

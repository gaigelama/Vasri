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
     * @var mixed
     */
    private $appEnvironment;

    /**
     * Vasri constructor.
     */
    public function __construct()
    {
        $this->builder        = new Builder();
        $this->manifestReader = new ManifestReader();
        $this->vasriManifest  = $this->manifestReader->getManifest(base_path('vasri-manifest.json'));
        $this->appEnvironment = env('APP_ENV', 'production');
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
        if (self::isPublicFile($file) === true) {
            return $this->addAttributes($file, $enableVersioning, $enableSRI, $keyword);
        } else {
            throw new Exception('Incorrect file path or file does not exist for local asset');
        }
    }

    /**
     * @param  string  $file
     *
     * @param  string  $keyword
     *
     * @return string
     */
    private function getSRI(string $file, string $keyword): string
    {
        return ' integrity="'.$this->vasriManifest[$file]['sri'].'" '.$this->builder->crossOrigin($keyword);
    }

    /**
     * @param  string  $file
     * @param  bool  $enableVersioning
     *
     * @param  bool  $enableSRI
     * @param  string  $keyword
     *
     * @return string
     * @throws Exception
     */
    private function addAttributes(string $file, bool $enableVersioning, bool $enableSRI, string $keyword): string
    {
        $output = $this->getSourceAttribute($file, $this->getVersioning($file));

        if ($this->appEnvironment === 'local' && ! config('vasri.local-versioning') || ! $enableVersioning) {
            $output = $this->getSourceAttribute($file);
        }
        if ($enableSRI) {
            $output .= $this->getSRI($file, $keyword);
        }

        return $output;
    }

    private function getVersioning(string $file): string
    {
        return $this->vasriManifest[$file]['version'];
    }

    private function getSourceAttribute(string $file, string $version = ''): string
    {
        return $this->builder->attribute($file).'="'.$file.$version.'"';
    }

    /**
     *
     * @param  string  $file
     *
     * @return bool
     */
    private static function isPublicFile(string $file): bool
    {
        return File::exists(public_path($file));
    }

}

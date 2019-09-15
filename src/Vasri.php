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
     * @var
     */
    private $vasriConfig;

    /**
     * @var
     */
    private $isMixManifestAltEnabled;

    /**
     * Vasri constructor.
     */
    public function __construct()
    {
        $this->builder                 = new Builder();
        $this->manifestReader          = new ManifestReader();
        $this->vasriConfig             = config('vasri');
        $this->isMixManifestAltEnabled = $this->vasriConfig['mix-manifest-alt'];
        $this->vasriManifest           = $this->manifestReader->getManifest(base_path('vasri-manifest.json'));
        $this->appEnvironment          = env('APP_ENV', 'production');
    }

    /**
     * The Vasri helper function
     *
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

        $altFile = '';

        if ($this->isMixManifestAltEnabled) {

            $altFile = $this->vasriManifest[$file]['alt'];

        }

        if (self::isPublicFile($file) || self::isPublicFile($altFile)) {

            return $this->addAttributes($file, $altFile, $enableVersioning, $enableSRI, $keyword);

        } else {

            throw new Exception('Incorrect file path or file does not exist for local asset');

        }
    }

    /**
     * Fetches the SRI hash from the Vasri Manifest and adds the crossorigin attribute
     *
     * @param  string  $file
     *
     * @param  string  $keyword
     *
     * @return string
     */
    private function getSRI(string $file, string $keyword): string
    {

        return sprintf('integrity="%s" %s', $this->vasriManifest[$file]['sri'], $this->builder->crossOrigin($keyword));
    }

    /**
     * Builds all the attributes
     *
     * @param  string  $file
     * @param  string  $altFile
     * @param  bool  $enableVersioning
     *
     * @param  bool  $enableSRI
     * @param  string  $keyword
     *
     * @return string
     * @throws Exception
     */
    private function addAttributes(
        string $file,
        string $altFile,
        bool $enableVersioning,
        bool $enableSRI,
        string $keyword
    ): string {


        $option = $this->getOptions($enableVersioning, $enableSRI);
        $output = $this->getSourceAttribute($file);

        if ($option['versioning']) {

            $path = $file;

            if ($this->isMixManifestAltEnabled) {
                $path = $altFile;
            }

            $output = $this->getSourceAttribute($path, $this->getVersioning($file));

        }
        if ($option['sri']) {

            $output = sprintf('%s %s', $output, $this->getSRI($file, $keyword));

        }

        return $output;
    }

    /**
     * Fetches the version query string from the Vasri Manifest
     *
     * @param  string  $file
     *
     * @return string
     */
    private function getVersioning(string $file): string
    {
        return $this->vasriManifest[$file]['version'];
    }

    /**
     * Figures out whether or not to toggle versioning and sri
     *
     * @param  bool  $enableVersioning
     * @param  bool  $enableSRI
     *
     * @return array
     */
    private function getOptions(bool $enableVersioning, bool $enableSRI): array
    {
        return [
            'versioning' => ! ($this->appEnvironment === 'local'
                               && ! config('vasri.local-versioning')
                               || ! $enableVersioning
                               || ! $this->vasriConfig['versioning']
            ),
            'sri'        => $enableSRI && $this->vasriConfig['sri'],
        ];
    }

    /**
     * Gets source attribute based on the extension, adds file path and version
     *
     * @param  string  $file
     * @param  string  $version
     *
     * @return string
     * @throws Exception
     */
    private function getSourceAttribute(string $file, string $version = ''): string
    {
        return sprintf('%s="%s%s"', $this->builder->attribute($file), $file, $version);
    }

    /**
     * Checks if the file is in the Laravel public path
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

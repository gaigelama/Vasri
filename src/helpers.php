<?php

use ExoUNX\Vasri\Vasri;

if ( ! function_exists('vasri')) {
    /**
     * Get file and generate version and SRI if enabled
     *
     * @param  string  $path
     * @param  bool  $versioning
     * @param  bool  $sri
     * @param  string  $keyword
     *
     * @return string
     * @throws Exception
     */
    function vasri(string $path, bool $versioning = true, bool $sri = true, $keyword = 'anonymous'): string
    {
        $vasri = new Vasri();

        return $vasri->vasri($path, $versioning, $sri, $keyword);
    }
}

<?php

use ExoUNX\Vasri\Vasri;

if ( ! function_exists('vasri')) {
    /**
     * Get file and generate version and SRI if enabled
     *
     * @param  $path
     * @param  bool  $versioning
     * @param  bool  $sri
     *
     * @return string
     * @throws Exception
     */
    function vasri(string $path, bool $versioning = true, bool $sri = true): string
    {
        $vasri = new Vasri();

        return $vasri->vasri($path, $versioning, $sri);
    }
}

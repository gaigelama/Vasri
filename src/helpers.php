<?php

use ExoUNX\Vasri\Vasri;

if ( ! function_exists('vasri')) {
    /**
     * Get file and generate version and SRI if enabled
     *
     * @param $type
     * @param $path
     * @param  bool  $versioning
     * @param  bool  $sri
     *
     * @return mixed
     * @throws Exception
     */
    function vasri(string $type, string $path, bool $versioning = true, bool $sri = true): string
    {
        return Vasri::vasri($type, $path, $versioning, $sri);
    }
}

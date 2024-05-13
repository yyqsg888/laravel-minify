<?php

static $minify_path_array = [];

function minify($file)
{
    return minifier($file, true);
}
function minify_noversioning($file)
{
    return minifier($file, false);
}
function minifier($file, $versioning)
{
    global $minify_path_array;

    $minify_cache_enabled = (bool) config('minify.cache_enabled', true);
    if (!$minify_cache_enabled) {
        \Cache::forget('minify_path');
        $minify_path_array = [];
    }

    if (!isset($minify_path_array[$file])) {
        $minify_path_array = Cache::rememberForever('minify_path', function () {
            return [];
        });
    }

    if (!isset($minify_path_array[$file])) {
        $path = resource_path($file);

        if (!file_exists($path)) {
            throw new \Exception('File not found');
        }

        $hash = hash_file('sha256', $path);

        $path = '_minify/' . ltrim($file, '/\\'); // remove slash or backslash from the beginning of the file path
        if ($versioning) {
            $path .= '?v=' . $hash;
        }

        $minify_path_array[$file] = $path;

        Cache::put('minify_path', $minify_path_array);
    }

    return $minify_path_array[$file];
}

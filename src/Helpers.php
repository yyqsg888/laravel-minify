<?php

function minify($file)
{
    $path = resource_path($file);
    if (!file_exists($path)) {
        throw new \Exception('File not found');
    }

    $path .= '?v=' . hash_file('sha256', $path);

    // remove slash or backslash from the beginning of the file path
    $file = ltrim($file, '/\\');

    $path = '_minify/' . $file;

    return $path;
}

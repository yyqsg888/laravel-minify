<?php

function minify($file)
{
    return Cache::rememberForever('minify_path_' . $file, function () use ($file) {

        $path = resource_path($file);

        $fileExists = \Cache::rememberForever('fileExists_' . $file, function () use ($path) {
            return file_exists($path);
        });
        if (!$fileExists) {
            throw new \Exception('File not found');
        }

        $hash = hash_file('sha256', $path);

        // remove slash or backslash from the beginning of the file path
        $file = ltrim($file, '/\\');

        $path = '_minify/' . $file;
        $path .= '?v=' . $hash;

        return $path;
    });
}

function minify_noversioning($file)
{
    return Cache::rememberForever('minify_path_' . $file, function () use ($file) {

        $path = resource_path($file);

        $fileExists = \Cache::rememberForever('fileExists_' . $file, function () use ($path) {
            return file_exists($path);
        });
        if (!$fileExists) {
            throw new \Exception('File not found');
        }

        // remove slash or backslash from the beginning of the file path
        $file = ltrim($file, '/\\');

        $path = '_minify/' . $file;

        return $path;
    });
}

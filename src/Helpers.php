<?php

function minify($file)
{
    return Cache::rememberForever('minify_path_' . $file, function () use ($file) {
        $path = resource_path($file);
        if (!file_exists($path)) {
            throw new \Exception('File not found');
        }

        $hash = hash_file('sha256', $path);
        \Log::warning('File hashed: ' . $path);

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
        if (!file_exists($path)) {
            throw new \Exception('File not found');
        }

        \Log::warning('File no versioning: ' . $path);

        // remove slash or backslash from the beginning of the file path
        $file = ltrim($file, '/\\');

        $path = '_minify/' . $file;

        return $path;
    });
}

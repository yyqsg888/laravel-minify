<?php
use LaravelMinifier\Minify\Controllers\HttpConnectionHandler;

function minify($file)
{
    if (!isset(HttpConnectionHandler::$minifiedPaths[$file]) || is_null(HttpConnectionHandler::$minifiedPaths[$file])) {
        HttpConnectionHandler::$minifiedPaths[$file] = Cache::rememberForever('minify_path_' . $file, function () use ($file) {

            $path = resource_path($file);

            if (!file_exists($path)) {
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

    return HttpConnectionHandler::$minifiedPaths[$file];
}

function minify_noversioning($file)
{
    if (!isset(HttpConnectionHandler::$minifiedPaths[$file]) || is_null(HttpConnectionHandler::$minifiedPaths[$file])) {
        HttpConnectionHandler::$minifiedPaths[$file] = Cache::rememberForever('minify_path_' . $file, function () use ($file) {

            $path = resource_path($file);

            if (!file_exists($path)) {
                throw new \Exception('File not found');
            }

            // remove slash or backslash from the beginning of the file path
            $file = ltrim($file, '/\\');

            $path = '_minify/' . $file;

            return $path;
        });
    }
    return HttpConnectionHandler::$minifiedPaths[$file];
}

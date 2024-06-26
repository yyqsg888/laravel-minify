<?php

namespace LaravelMinifier\Minify\Controllers;

use LaravelMinifier\Minify\Helpers\CSS;
use LaravelMinifier\Minify\Helpers\Javascript;

class HttpConnectionHandler
{
    // Static variables useless as each call to a file is in a new request
    public function __invoke($file)
    {
        $minify_cache_enabled = (bool) config('minify.cache_enabled', true);
        if (!$minify_cache_enabled) {
            \Cache::forget('minify_' . $file);
        }

        return \Cache::rememberForever('minify_' . $file, function () use ($file) {
            $path = resource_path($file);

            if (!file_exists($path)) {
                return abort(404);
            }

            if (!preg_match("/^(css|js)\//", $file)) {
                return abort(404);
            }

            $js_insert_semicolon = (bool) config('minify.insert_semicolon.js', true);
            $css_insert_semicolon = (bool) config('minify.insert_semicolon.css', true);
            $obfuscate = (bool) config('minify.obfuscate', false);
            $enabled = (bool) config('minify.assets_enabled', true);

            $css = new CSS();
            $js = new Javascript();

            $content = file_get_contents($path);
            $mime = 'text/plain';

            // due to support only for css and js (issue #9)
            if ($enabled) {
                if (preg_match("/\.css$/", $file)) {
                    $content = $css->replace($content, $css_insert_semicolon);
                    $mime = 'text/css';
                } elseif (preg_match("/\.min\.js$/", $file)) {
                    $mime = 'application/javascript';
                } elseif (preg_match("/\.js$/", $file)) {
                    $content = $js->replace($content, $js_insert_semicolon);
                    if ($obfuscate) {
                        $content = $js->obfuscate($content);
                    }
                    $mime = 'application/javascript';
                }
            }

            return response($content, 200, [
                'Content-Type' => $mime . '; charset=UTF-8',
                'Cache-Control' => 'public,max-age=31536000,s-maxage=31536000,immutable',
                'CDN-Cache-Control' => 'public,max-age=31536000,s-maxage=31536000,immutable',
            ]);
        });
    }
}

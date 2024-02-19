<?php

namespace LaravelMinifier\Minify\Middleware;

use LaravelMinifier\Minify\Helpers\Javascript;

class MinifyJavascript extends Minifier
{
    protected static $allowInsertSemicolon;

    protected function apply()
    {
        static::$minifyJavascriptHasBeenUsed = true;
        static::$allowInsertSemicolon = (bool) config('minify.insert_semicolon.js', false);
        $javascript = new Javascript();
        $obfuscate = (bool) config('minify.obfuscate', false);

        foreach ($this->getByTag('script') as $el) {
            $value = $javascript->replace($el->nodeValue, static::$allowInsertSemicolon);
            if ($obfuscate) {
                $value = $javascript->obfuscate($value);
            }
            $el->nodeValue = '';
            $el->appendChild(static::$dom->createTextNode($value));
        }

        return static::$dom->saveHtml();
    }
}

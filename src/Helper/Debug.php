<?php

namespace App\Helper;

class Debug
{
    public static function dump($response)
    {
        echo "<pre>";
        var_dump($response);
        echo "</pre>";
    }

    public static function dd($response)
    {
        self::dump($response);
        die();
    }
}
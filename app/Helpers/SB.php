<?php

namespace App\Helpers;

class SB
{
    public static function navUrl(string $path, ?string $view = null): string
    {
        $base = url($path);
        return $view ? $base . '?view=' . $view : $base;
    }
}
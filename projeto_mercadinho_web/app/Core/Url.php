<?php declare(strict_types=1);

namespace App\Core;

final class Url
{
    public static function basePath(): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $base = rtrim(str_replace('\\','/', dirname($script)), '/');
        return ($base === '' || $base === '/') ? '' : $base;
    }

    public static function to(string $path): string
    {
        $p = '/' . ltrim($path, '/');
        return self::basePath() . $p;
    }
}

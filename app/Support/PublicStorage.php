<?php

namespace App\Support;

class PublicStorage
{
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = trim(str_replace('\\', '/', $path), '/');

        if ($path === '') {
            return null;
        }

        if (preg_match('/^(https?:)?\/\//i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        $encodedPath = collect(explode('/', $path))
            ->map(fn ($segment) => rawurlencode($segment))
            ->implode('/');

        $baseUrl = rtrim(request()->getBaseUrl(), '/');

        return ($baseUrl === '' ? '' : $baseUrl).'/storage/'.$encodedPath;
    }
}

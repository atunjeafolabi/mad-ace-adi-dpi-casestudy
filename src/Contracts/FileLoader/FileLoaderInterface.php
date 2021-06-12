<?php

declare(strict_types=1);

namespace App\Contracts\FileLoader;

interface FileLoaderInterface
{
    public function load(string $fileName);
}

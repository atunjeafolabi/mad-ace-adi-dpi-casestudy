<?php

declare(strict_types=1);

namespace App\Contracts\FileWriter;

interface FileWriter
{
    /**
     * @param string $fileName
     * @param array $data
     */
    public function write(string $fileName, array $data);
}

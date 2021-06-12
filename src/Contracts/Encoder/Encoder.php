<?php

declare(strict_types=1);

namespace App\Contracts\Encoder;

interface Encoder
{
    /**
     * @return string
     */
    public function format(): string;

    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data): array;
}

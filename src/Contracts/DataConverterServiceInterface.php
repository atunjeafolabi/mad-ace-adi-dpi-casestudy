<?php

namespace App\Contracts;

interface DataConverterServiceInterface
{
    public function convert(string $fileName, array $option = []);
}

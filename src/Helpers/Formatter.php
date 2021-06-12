<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class Formatter
 * @package App\Helpers
 */
final class Formatter
{
    const OUTPUT_FORMAT = "csv";
    /**
     * @param $fileName
     * @param $outputDir
     * @return string
     */
    public static function format($fileName, $outputDir): string
    {
        $transformedFileName = str_replace(".", "_", $fileName);

        return $outputDir . "/$transformedFileName" . "." . self::OUTPUT_FORMAT;
    }
}

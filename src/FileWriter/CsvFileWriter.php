<?php

declare(strict_types=1);

namespace App\FileWriter;

use App\Contracts\FileWriter\FileWriter;
use App\Helpers\Formatter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class CsvFileWriter implements FileWriter
{
    private $outputDir;
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * CsvFileWriter constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->outputDir = $this->params->get("OUTPUT_DIR");
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $fileName, array $records)
    {
        $outputFile = Formatter::format($fileName, $this->outputDir);

        $fp = fopen($outputFile, 'w');
        // Handle proper display of special characters in CSV file.
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

        $header = $this->generateHeadersForCsvFile($records);

        // Write the header to CSV file
        fputcsv($fp, $header);

        // Loop through each array and enter each row on the csv file
        foreach ($records as $record) {
            fputcsv($fp, $record);
        }

        fclose($fp);
    }

    /**
     * Extract array keys that will be used for column headers in CSV file
     *
     * @param array $records
     * @return array
     */
    private function generateHeadersForCsvFile(array $records): array
    {
        $headers = array_keys(array_values($records)[0]);
        return $headers;
    }
}

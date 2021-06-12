<?php

declare(strict_types=1);

namespace App\Service;

use App\Contracts\DataConverterServiceInterface;
use App\Contracts\Encoder\Encoder;
use App\Contracts\Validator\RecordValidator;
use App\FileLoader\FileLoader;
use App\Filter\Filter;
use App\FileWriter\CsvFileWriter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class DataConverterService implements DataConverterServiceInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var CsvFileWriter
     */
    private $fileWriter;

    /**
     * @var Encoder
     */
    private $encoder;
    /**
     * @var RecordValidator
     */
    private $recordValidator;
    /**
     * @var FileLoader
     */
    private $filLoader;

    /**
     * DataConverterService constructor.
     * @param ParameterBagInterface $params
     * @param CsvFileWriter $fileWriter
     * @param FileLoader $fileLoader
     */
    public function __construct(
        ParameterBagInterface $params,
        CsvFileWriter $fileWriter,
        FileLoader $fileLoader
    ) {
        $this->params = $params;
        $this->fileWriter = $fileWriter;
        $this->filLoader = $fileLoader;
    }

    /**
     * @param Encoder $encoder
     */
    public function setEncoder(Encoder $encoder): void
    {
        $this->encoder = $encoder;
    }

    /**
     * @param RecordValidator $recordValidator
     */
    public function setValidator(RecordValidator $recordValidator): void
    {
        $this->recordValidator = $recordValidator;
    }

    /**
     * @param string $fileName
     * @param array $option
     */
    public function convert(string $fileName, array $option = []): void
    {
        $fileContents = $this->filLoader->load($fileName);

        $validRecords = Filter::validRecords($this->recordValidator, $this->encoder->decode($fileContents));

        if (!$validRecords) {
            throw new \DomainException("No valid record found in file");
        }

        if (isset($option['sort'])) {
            $this->sortRecords($validRecords, $option);
        }

        $this->fileWriter->write($fileName, $validRecords);
    }

    /**
     * @param $records
     * @param array $option
     */
    protected function sortRecords(&$records, array $option): void
    {
        array_multisort(
            array_column($records, $option["sort"]),
            $option['order'] ?? SORT_DESC,
            $records
        );
    }
}

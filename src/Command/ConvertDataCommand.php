<?php

declare(strict_types=1);

namespace App\Command;

use App\Contracts\Encoder\Encoder;
use App\Encoder\JsonEncoder;
use App\Encoder\XmlEncoder;
use App\Service\DataConverterService;
use App\Contracts\Validator\RecordValidator;
use App\Validator\OrdinaryRecordValidator;
use App\Validator\StrictRecordValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertDataCommand extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'trivago:convert';

    /**
     * @var DataConverterService
     */
    private $dataConverterService;

    /**
     * ImportHotelDataCommand constructor.
     * @param DataConverterService $dataConverterService
     */
    public function __construct(DataConverterService $dataConverterService)
    {
        $this->dataConverterService = $dataConverterService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Converts hotel data to CSV.')
            ->setHelp('This command allows you to import hotel data from a file,
            validates it and then outputs it to a CSV file.')
            ->addArgument('file_name', InputArgument::REQUIRED, 'Input file name to be converted.');

        $this->addOption(
            'sort',
            null,
            InputOption::VALUE_OPTIONAL,
            'Sort by which field? Fields: name, stars',
            false
        );

        $this->addOption(
            'order',
            null,
            InputOption::VALUE_OPTIONAL,
            'Select sort order. "asc" for ascending order and "desc" for descending order',
            false
        );

        $this->addOption(
            'strict',
            null,
            InputOption::VALUE_OPTIONAL,
            'Strict mode. No file is generated if input file contain invalid data',
            false
        );

        $this->addOption(
            'debug',
            null,
            InputOption::VALUE_OPTIONAL,
            'Display error on console',
            false
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('==== Hotel Data Converter =====');
        $output->writeln("Converting data ...");

        $fileName = $input->getArgument('file_name');
        $encoder = $this->getEncoder($this->fileType($fileName));
        $options = $this->sortingOptions($input, $output);

        $this->dataConverterService->setEncoder($encoder);
        $this->dataConverterService->setValidator($this->getValidator($input));

        $this->dataConverterService->convert($fileName, $options);

        $output->writeln("---------------------");
        $output->writeln("Complete ...");
        $output->writeln("---------------------");

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @return RecordValidator
     */
    private function getValidator(InputInterface $input): RecordValidator
    {
        if ($input->getOption('strict')) {
            return new StrictRecordValidator();
        }

        return new OrdinaryRecordValidator((bool) $input->getOption('debug'));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $option
     * @return array
     */
    protected function sortingOptions(InputInterface $input, OutputInterface $output, array $option = []): array
    {
        if ($input->getOption("sort")) {
            $option["sort"] = $input->getOption("sort");

            if (!in_array($option["sort"], ["name", "stars"])) {
                throw new InvalidOptionException("Invalid sorting option supplied (use name or stars");
            }

            if ($input->getOption("order")) {
                $option = $this->setOrderBy($input, $option);
            }
        }

        return $option;
    }

    /**
     * @param InputInterface $input
     * @param array $option
     * @return array
     */
    protected function setOrderBy(InputInterface $input, array $option): array
    {
        $order = strtolower($input->getOption("order"));

        if ($order === "asc") {
            $option["order"] = SORT_ASC;
        } else {
            $option["order"] = SORT_DESC;
        }
        return $option;
    }

    protected function getEncoder(string $fileType): Encoder
    {
        if ($fileType === "json") {
            return new JsonEncoder();
        }
        if ($fileType === "xml") {
            return new XmlEncoder();
        }

        // Throw an unsupported file format exception
        // throw new UnsupportedFileException();
    }

    protected function fileType(string $fileName): string
    {
        $type = pathinfo($fileName, PATHINFO_EXTENSION);

        return strtolower($type);
    }
}

<?php

namespace Test\Command;

use App\Helpers\Formatter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;

class CreateUserCommandTest extends KernelTestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;
    private $outputDir;

    protected function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $this->outputDir = $_ENV['OUTPUT_DIR'];

        $command = $application->find('trivago:convert');
        $this->commandTester = new CommandTester($command);
    }

    public function test_that_a_valid_hotel_file_in_json_format_is_converted()
    {
        $fileName = 'hotels.json';

        $this->commandTester->execute([
            // pass command arguments
            'file_name' => $fileName,
        ]);

        $expectedFile = Formatter::format($fileName, $this->outputDir);

        $this->assertEquals(Command::SUCCESS, $this->commandTester->getStatusCode());
        $this->assertTrue(file_exists($expectedFile));
    }

    public function test_that_a_valid_hotel_file_in_xml_format_is_converted()
    {
        $fileName = 'hotels.xml';

        $this->commandTester->execute([
            // pass command arguments
            'file_name' => $fileName,
        ]);

        // the output of the command in the console
        $output = $this->commandTester->getDisplay();

        $expectedFile = Formatter::format($fileName, $this->outputDir);

        $this->assertEquals(Command::SUCCESS, $this->commandTester->getStatusCode());
        $this->assertTrue(file_exists($expectedFile));
    }

    /**
     * @expectedException \DomainException
     */
    public function test_that_exception_is_thrown_in_strict_mode_for_hotel_file_containing_invalid_star()
    {
        $this->commandTester->execute([
            // pass command arguments
            'file_name' => 'hotelsWithStarError.json',
            // Pass command options
            '--strict' => true
        ]);
    }

    /**
     * @expectedException \DomainException
     */
    public function test_that_exception_is_thrown_in_strict_mode_for_hotel_file_containing_invalid_uri()
    {
        $this->commandTester->execute([
            // pass command arguments
            'file_name' => 'hotelsWithUriError.json',
            // Pass command options
            '--strict' => true
        ]);
    }

    public function tearDown() : void
    {
        $this->tidyUpOutputDirectory();
    }

    /**
     * Removes all generated files after each test run
     */
    protected function tidyUpOutputDirectory(): void
    {
        $finder = new Finder();

        // find all files in the current directory
        $finder->files()->in($this->outputDir);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                unlink($file);
            }
        }
    }
}

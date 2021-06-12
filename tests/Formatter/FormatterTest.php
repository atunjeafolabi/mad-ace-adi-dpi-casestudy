<?php declare(strict_types=1);

namespace Test\Formatter;

use App\Helpers\Formatter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormatterTest extends KernelTestCase
{
    public function test_that_output_file_name_is_properly_formatted()
    {
        $fileName = "hotels.json";
        $outputDir = $_ENV["OUTPUT_DIR"];
        $formattedFileName = Formatter::format($fileName, $outputDir);

        $this->assertSame(
            "$outputDir/hotels_json.csv",
            $formattedFileName
        );
    }
}

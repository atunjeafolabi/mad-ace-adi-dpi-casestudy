<?php declare(strict_types = 1);

namespace Test\Validator;

use App\Validator\OrdinaryRecordValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SimpleRecordValidatorTest extends KernelTestCase
{
    /**
     * @dataProvider recordSets
     *
     * @param array $record
     * @param bool $expected
     */
    public function test_that_ordinary_validator_works_well_with_both_valid_and_invalid_records(array $record, bool $expected)
    {
        $validator = new OrdinaryRecordValidator();

        $this->assertEquals($validator->isValid($record), $expected);
    }

    /**
     * @return array
     */
    public function recordSets(): array
    {
        return [
            'Invalid Url'  => [['name' => 'Sam Doe Hotel', 'uri' => 'www.invalid-uri', 'stars' => '3.5'], false],
            'Over Rating'  => [['name' => 'Segun Agunbiade', 'uri' => 'https://www.segun.com', 'stars' => '6'], false],
            'Negative Rating'  => [['name' => 'The Place', 'uri' => 'http://www.the-place.com', 'stars' => '-2'], false],
            'Valid Record'  => [['name' => 'Coolio', 'uri' => 'http://www.coolio.com', 'stars' => '5'], true],
            'Invalid Name(non-ASCII)'  => [['name' => 'The Sölzer', 'uri' => 'https://www.stumpf.com/post.php', 'stars' => '4.2'], false],

        ];
    }
}

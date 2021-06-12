<?php declare(strict_types = 1);

namespace Test\Validator;

use App\Validator\StrictRecordValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StrictRecordValidatorTest extends KernelTestCase
{
    public function test_that_a_valid_record_passes_validation()
    {
        $validRecord = [
            'name' => 'Berlin Hotel',
            'uri' => 'https://www.berlin.com',
            'stars' => '5',
            'address' => '23 Lange Avenue, Berlin',
            'phone' => '+229033948'
        ];

        $validator = new StrictRecordValidator();

        $this->assertTrue($validator->isValid($validRecord));
    }

    /**
     * @dataProvider invalidHotelRecords
     * @expectedException \DomainException
     * @param array $invalidHotelRecords
     */
    public function test_that_invalid_records_throw_exceptions(array $invalidHotelRecords)
    {
        $validator = new StrictRecordValidator();

        $validator->isValid($invalidHotelRecords);
    }

    /**
     * @return array
     */
    public function invalidHotelRecords(): array
    {
        return [
            'Invalid Url'  => [['name' => 'Sam Doe Hotel', 'uri' => 'www.invalid-uri', 'stars' => '3.5']],
            'Over Rating'  => [['name' => 'Segun Agunbiade', 'uri' => 'https://www.segun.com', 'stars' => '6']],
            'Negative Rating'  => [['name' => 'The Place', 'uri' => 'http://www.the-place.com', 'stars' => '-2']],
            'Invalid Name'  => [['name' => 'The Sölzer', 'uri' => 'https://www.stumpf.com/post.php', 'stars' => '4.2']],

        ];
    }
}

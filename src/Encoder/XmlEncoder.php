<?php

declare(strict_types=1);

namespace App\Encoder;

use App\Contracts\Encoder\Encoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder as BaseXmlEncoder;

final class XmlEncoder implements Encoder
{
    private const FILE_FORMAT = "xml";

    /**
     * @var BaseXmlEncoder
     */
    private $encoder;

    public function __construct()
    {
        $this->encoder = new BaseXmlEncoder();
    }

    /**
     * {@inheritdoc}
     */
    public function format(): string
    {
        return self::FILE_FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function decode(string $data): array
    {
        $decodedData = $this->encoder->decode($data, $this->format());

        return $this->properlyFormat($decodedData);
    }

    /**
     * A redundant "hotel" key is usually generated when a xml file is loaded into an array.
     * This function removes the key.
     *
     * @param $hotels
     * @return mixed
     */
    protected function properlyFormat($hotels): array
    {
        return array_shift($hotels);
    }
}

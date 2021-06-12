<?php

declare(strict_types=1);

namespace App\Encoder;

use App\Contracts\Encoder\Encoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder as BaseJsonEncoder;

final class JsonEncoder implements Encoder
{
    private const FILE_FORMAT = 'json';

    /**
     * @var BaseJsonEncoder
     */
    private $encoder;

    public function __construct()
    {
        $this->encoder = new BaseJsonEncoder();
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
        return $this->encoder->decode($data, $this->format());
    }
}

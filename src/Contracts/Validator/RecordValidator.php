<?php

declare(strict_types=1);

namespace App\Contracts\Validator;

interface RecordValidator
{
    const NAME_ENCODING_FORMAT = 'ASCII';
    const URI_REGEX = "/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/";

    /**
     * @param array $record
     * @return bool
     */
    public function isValid(array $record): bool;
}

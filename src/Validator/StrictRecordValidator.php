<?php

declare(strict_types=1);

namespace App\Validator;

final class StrictRecordValidator extends BaseRecordValidator
{
    /**
     * @param string $message
     *
     * @throws \DomainException
     */
    protected function handleError(string $message)
    {
        $this->terminate($message);
    }

    /**
     * @param string $message
     */
    private function terminate(string $message)
    {
        throw new \DomainException($message);
    }
}

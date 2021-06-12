<?php

declare(strict_types=1);

namespace App\Validator;

final class OrdinaryRecordValidator extends BaseRecordValidator
{
    /**
     * @var bool
     */
    private $displayErrors;

    /**
     * @param bool $displayErrors
     */
    public function __construct(bool $displayErrors = false)
    {
        $this->displayErrors = $displayErrors;
    }

    /**
     * @param $message
     */
    protected function handleError($message)
    {
        $this->printError($message);
    }

    /**
     * @param string $message
     */
    private function printError(string $message)
    {
        if (!$this->displayErrors) {
            return;
        }

        echo $message . PHP_EOL;
    }
}

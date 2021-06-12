<?php

declare(strict_types=1);

namespace App\Validator;

use App\Contracts\Validator\RecordValidator;

abstract class BaseRecordValidator implements RecordValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValid(array $record): bool
    {
        return $this->isValidHotelName($record['name'])
            && $this->isValidStars(floatval($record['stars']))
            && $this->isValidUri($record['uri']);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isValidHotelName(string $name): bool
    {
        if (!mb_check_encoding($name, self::NAME_ENCODING_FORMAT)) {
            $this->handleError(sprintf('Hotel name: "%s" is invalid (contains non-ASCII characters).', $name));

            return false;
        }

        return true;
    }

    /**
     * @param float $stars
     * @return bool
     */
    private function isValidStars(float $stars): bool
    {
        if ($stars < 0 || $stars > 5) {
            $this->handleError(sprintf('Review/Star: "%s" is invalid (cannot be greater than 5 or negative).', $stars));

            return false;
        }

        return true;
    }

    /**
     * @param string $uri
     * @return bool
     */
    private function isValidUri(string $uri): bool
    {
        if (!preg_match(self::URI_REGEX, $uri)) {
            $this->handleError(sprintf('URL: "%s" is invalid.', $uri));

            return false;
        }

        return true;
    }
}

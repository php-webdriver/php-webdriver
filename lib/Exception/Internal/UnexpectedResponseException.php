<?php declare(strict_types=1);

namespace Facebook\WebDriver\Exception\Internal;

use Facebook\WebDriver\Exception\PhpWebDriverExceptionInterface;

/**
 * Exception thrown on invalid or unexpected server response.
 */
class UnexpectedResponseException extends \RuntimeException implements PhpWebDriverExceptionInterface
{
    public static function forError(string $message): self
    {
        return new self($message);
    }

    public static function forJsonDecodingError(int $jsonLastError, string $rawResults): self
    {
        return new self(
            sprintf(
                "JSON decoding of remote response failed.\n" .
                "Error code: %d\n" .
                "The response: '%s'\n",
                $jsonLastError,
                $rawResults
            )
        );
    }
}

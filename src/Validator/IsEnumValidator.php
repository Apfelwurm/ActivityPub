<?php

namespace Rikudou\ActivityPub\Validator;

use BackedEnum;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Validates that the value is an instance of a specific enum class.
 * 
 * @template T of BackedEnum
 */
final class IsEnumValidator implements Validator
{
    /**
     * @param class-string<T> $enumClass
     */
    public function __construct(
        private readonly string $enumClass,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        // Allow Link objects as they can represent external/extended values
        if ($value instanceof Link) {
            return [];
        }

        if (!$value instanceof $this->enumClass) {
            return ["the value must be an instance of {$this->enumClass}"];
        }

        return [];
    }
}

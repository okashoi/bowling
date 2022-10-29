<?php

declare(strict_types=1);

namespace Okashoi\Bowling;

use UnexpectedValueException;

readonly class ThrowResult
{
    public function __construct(
        /** @var 0|1|2|3|4|5|6|7|8|9|10 */
        public int $pins,
        public ThrowResultType $type,
    ) {
        if ($type === ThrowResultType::None && $pins === 10) {
            throw new UnexpectedValueException();
        }

        if ($type === ThrowResultType::Spare && $pins === 0) {
            throw new UnexpectedValueException();
        }

        if ($type === ThrowResultType::Strike && $pins !== 10) {
            throw new UnexpectedValueException();
        }
    }
}

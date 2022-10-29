<?php

declare(strict_types=1);

namespace Okashoi\Bowling;

use BadMethodCallException;
use UnexpectedValueException;

class Frame
{
    protected ?ThrowResult $firstThrowResult = null;

    protected ?ThrowResult $secondThrowResult = null;

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|10 $pins
     */
    public function addThrowResultPins(int $pins): void
    {
        if ($this->isFinished()) {
            throw new BadMethodCallException();
        }

        // 1 投目
        if (is_null($this->firstThrowResult)) {
            if ($pins === 10) {
                $result = new ThrowResult($pins, ThrowResultType::Strike);
            } else {
                $result = new ThrowResult($pins, ThrowResultType::None);
            }
            $this->firstThrowResult = $result;

            return;
        }

        // 2 投目
        $pinsSum = $this->firstThrowResult->pins + $pins;
        if ($pinsSum > 10) {
            throw new UnexpectedValueException();
        } elseif ($pinsSum === 10) {
            $result = new ThrowResult($pins, ThrowResultType::Spare);
        } else {
            $result = new ThrowResult($pins, ThrowResultType::None);
        }
        $this->secondThrowResult = $result;
    }

    public function isFinished(): bool
    {
        if ($this->firstThrowResult?->type === ThrowResultType::Strike) {
            return true;
        }

        return !is_null($this->firstThrowResult) && !is_null($this->secondThrowResult);
    }
}

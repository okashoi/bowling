<?php

declare(strict_types=1);

namespace Okashoi\Bowling;

use BadMethodCallException;
use UnexpectedValueException;

class FinalFrame extends Frame
{
    private ?ThrowResult $thirdThrowResult = null;

    // NOTE: 都度算出するのは計算が複雑になるので状態として保持することにした
    private bool $isFinished = false;

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
        if (is_null($this->secondThrowResult)) {
            if ($this->firstThrowResult->type === ThrowResultType::Strike) {
                // 1 投目がストライクだったとき、1 投目と同様の処理になる
                if ($pins === 10) {
                    $result = new ThrowResult($pins, ThrowResultType::Strike);
                } else {
                    $result = new ThrowResult($pins, ThrowResultType::None);
                }
            } else {
                $pinsSum = $this->firstThrowResult->pins + $pins;
                if ($pinsSum > 10) {
                    throw new UnexpectedValueException();
                } elseif ($pinsSum === 10) {
                    $result = new ThrowResult($pins, ThrowResultType::Spare);
                } else {
                    $result = new ThrowResult($pins, ThrowResultType::None);
                    $this->isFinished = true;
                }
            }
            $this->secondThrowResult = $result;

            return;
        }

        // 3 投目
        if ($this->firstThrowResult->type === ThrowResultType::Strike) {
            // 1 投目がストライクだったとき、通常の 2 投目と同様の処理になる
            if ($this->secondThrowResult->type === ThrowResultType::Strike) {
                // 2 投目がストライクだったとき、1 投目と同様の処理になる
                if ($pins === 10) {
                    $result = new ThrowResult($pins, ThrowResultType::Strike);
                } else {
                    $result = new ThrowResult($pins, ThrowResultType::None);
                }
            } else {
                $pinsSum = $this->secondThrowResult->pins + $pins;
                if ($pinsSum > 10) {
                    throw new UnexpectedValueException();
                } elseif ($pinsSum === 10) {
                    $result = new ThrowResult($pins, ThrowResultType::Spare);
                } else {
                    $result = new ThrowResult($pins, ThrowResultType::None);
                }
            }
        } else {
            // 1 投目がストライクではなかった（= 2 投目でスペアになった）とき、1 投目と同様の処理になる
            if ($pins === 10) {
                $result = new ThrowResult($pins, ThrowResultType::Strike);
            } else {
                $result = new ThrowResult($pins, ThrowResultType::None);
            }
        }
        $this->isFinished = true;
        $this->thirdThrowResult = $result;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    /**
     * @return list<ThrowResult>
     */
    public function listThrowResults(): array
    {
        if (is_null($this->firstThrowResult)) {
            return [];
        }

        if (is_null($this->secondThrowResult)) {
            return [$this->firstThrowResult];
        }

        if (is_null($this->thirdThrowResult)) {
            return [$this->firstThrowResult, $this->secondThrowResult];
        }

        return [$this->firstThrowResult, $this->secondThrowResult, $this->thirdThrowResult];
    }
}

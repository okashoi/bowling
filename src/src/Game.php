<?php

declare(strict_types=1);

namespace Okashoi\Bowling;

use BadMethodCallException;

class Game
{
    /** @var list<Frame> */
    private array $frames = [];

    public function __construct()
    {
        foreach (range(1, 10) as $index) {
            if ($index < 10) {
                $this->frames[] = new Frame();
            } else {
                $this->frames[] = new FinalFrame();
            }
        }
    }

    /**
     * @return int|null ゲームが終了している場合に null
     */
    private function getCurrentFrameIndex(): ?int
    {
        foreach ($this->frames as $index => $frame) {
            assert($frame instanceof Frame);
            if (!$frame->isFinished()) {
                return $index;
            }
        }

        return null;
    }

    public function isFinished(): bool
    {
        return is_null($this->getCurrentFrameIndex());
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|10 $pins
     */
    public function addThrowResultPins(int $pins): void
    {
        if ($this->isFinished()) {
            throw new BadMethodCallException();
        }

        $currentFrameIndex = $this->getCurrentFrameIndex();
        assert(!is_null($currentFrameIndex));
        assert(isset($this->frames[$currentFrameIndex]));

        $this->frames[$currentFrameIndex]->addThrowResultPins($pins);
    }

    /**
     * @return list<ThrowResult>
     */
    public function listThrowResults(): array
    {
        /** @var list<ThrowResult> $results */
        $results = [];
        foreach ($this->frames as $frame) {
            assert($frame instanceof Frame);
            $results = array_merge($results, $frame->listThrowResults());
        }

        return $results;
    }
}

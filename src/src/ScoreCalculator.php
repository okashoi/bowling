<?php

declare(strict_types=1);

namespace Okashoi\Bowling;

class ScoreCalculator
{
    public function calculate(Game $game): int
    {
        $zeroResult = new ThrowResult(0, ThrowResultType::None);

        $throwResults = $game->listThrowResults();
        $score = 0;
        for ($i = 0; $i < count($throwResults); $i++) {
            $score += $this->calculateOneThrowScore(
                $throwResults[$i],
                $throwResults[$i + 1] ?? $zeroResult,
                $throwResults[$i + 2] ?? $zeroResult
            );

            if ($i === count($throwResults) - 3 && $throwResults[$i]->type === ThrowResultType::Strike) {
                break;
            }

            if ($i === count($throwResults) - 2 && $throwResults[$i]->type === ThrowResultType::Spare) {
                break;
            }
        }

        return $score;
    }

    /**
     * 連続する 3 投の結果から、最初の投球のスコアを算出する
     *
     * @param ThrowResult $n0 投球結果
     * @param ThrowResult $n1 1 つ後ろの投球結果
     * @param ThrowResult $n2 2 つ後ろの投球結果
     * @return int 新たに確定したスコア
     */
    private function calculateOneThrowScore(ThrowResult $n0, ThrowResult $n1, ThrowResult $n2): int
    {
        return match ($n0->type) {
            ThrowResultType::Strike => $n0->pins + $n1->pins + $n2->pins,
            ThrowResultType::Spare => $n0->pins + $n1->pins,
            ThrowResultType::None => $n0->pins,
        };
    }
}

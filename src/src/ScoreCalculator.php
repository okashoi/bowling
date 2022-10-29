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
            if ($i === 0) {
                $score += $this->calculateNewlyFixedScoreFromThreeThrowResults($zeroResult, $zeroResult, $throwResults[$i]);
            } elseif ($i === 1) {
                $score += $this->calculateNewlyFixedScoreFromThreeThrowResults($zeroResult, $throwResults[$i - 1], $throwResults[$i]);
            } else {
                $score += $this->calculateNewlyFixedScoreFromThreeThrowResults($throwResults[$i - 2], $throwResults[$i - 1], $throwResults[$i]);
            }
        }

        return $score;
    }

    /**
     * 連続する 3 投の結果から、「新たに確定したスコア」を算出する
     *
     * @param ThrowResult $n0 2 つ前の投球結果
     * @param ThrowResult $n1 1 つ前の投球結果
     * @param ThrowResult $n2 投球結果
     * @return int 新たに確定したスコア
     */
    private function calculateNewlyFixedScoreFromThreeThrowResults(ThrowResult $n0, ThrowResult $n1, ThrowResult $n2): int
    {
        $score = 0;
        $score += match ($n0->type) {
            ThrowResultType::Strike => $n0->pins + $n1->pins + $n2->pins,
            // ストライク以外で 2 つ前の投球結果は影響しないので 0
            default => 0,
        };

        $score += match ($n1->type) {
            ThrowResultType::Spare => $n1->pins + $n2->pins,
            // ストライクの場合は次の 1 投の結果がわかるまではスコアは確定できず、
            // ストライクでもスペアでもない場合は 1 つ前の投球結果は影響しないので 0
            default => 0,
        };

        $score += match ($n2->type) {
            ThrowResultType::None => $n2->pins,
            // ストライクにせよスペアにせよ次の 1～2 投の結果がわかるまではスコアは確定できないので 0
            default => 0,
        };

        return $score;
    }
}

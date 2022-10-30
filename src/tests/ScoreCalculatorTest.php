<?php

declare(strict_types=1);

namespace Okashoi\Bowling\Tests;

use Okashoi\Bowling\{Game, ScoreCalculator};
use PHPUnit\Framework\TestCase;

class ScoreCalculatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider calculateDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function calculate(int $expected, array $pinsList): void
    {
        $calculator = new ScoreCalculator();

        $game = new Game();
        foreach ($pinsList as $pins) {
            $game->addThrowResultPins($pins);
        }

        $this->assertSame($expected, $calculator->calculate($game));
    }

    /**
     * @return array<string, array{int, list<0|1|2|3|4|5|6|7|8|9|10>}>
     */
    public function calculateDataProvider(): array
    {
        return [
            'ストライクもスペアもない場合は倒したピンの数の総和がスコアになること' => [
                40,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4],
            ],
            'スペアを取った場合、次の投球で倒したピンの数がスコアに加算されること' => [
                51,
                [0, 10, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4],
            ],
            'ストライクを取った場合、次の2回の投球で倒したピンの数がスコアに加算されること' => [
                52,
                [10, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4],
            ],
            '最終フレームでスペアを取った場合、次の投球で倒したピンの数がスコアに加算されること' => [
                43,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 5, 5, 1],
            ],
            '最終フレーム1投目でストライクを取った場合、次の2回の投球で倒したピンの数がスコアに加算されること' => [
                50,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 4, 4],
            ],
            '最終フレームで1, 2投目ともにストライクを取った場合、3投目に倒したピンの数は1度しか加算されないこと' => [
                53,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 10, 1],
            ],
            'パーフェクトゲームのスコアは300点であること' => [
                300,
                [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
            ],
        ];
    }
}

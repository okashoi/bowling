<?php

declare(strict_types=1);

namespace Okashoi\Bowling\Tests;

use BadMethodCallException;
use Okashoi\Bowling\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @test
     * @dataProvider isFinishedDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function isFinished(bool $expected, array $pinsList): void
    {
        $game = new Game();
        foreach ($pinsList as $pins) {
            $game->addThrowResultPins($pins);
        }

        $this->assertSame($expected, $game->isFinished());
    }

    /**
     * @return array<string, array{bool, list<0|1|2|3|4|5|6|7|8|9|10>}>
     */
    public function isFinishedDataProvider(): array
    {
        return [
            'ストライクがなく、最終フレームでスペアも出さなかった場合は19投では終了していないこと' => [
                false,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4],
            ],
            'ストライクがなく、最終フレームでスペアも出さなかった場合は20投で終了すること' => [
                true,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4],
            ],
            '最終フレーム以外でストライクを1回出していても18投では終了していないこと' => [
                false,
                [10, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4],
            ],
            '最終フレーム以外でストライクを1回出した場合は19投で終了すること' => [
                true,
                [10, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4],
            ],
            '最終フレームまでにストライクがなく、最終フレームでスペアを出した場合は20投では終了していないこと' => [
                false,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 6],
            ],
            '最終フレームまでにストライクがなく、最終フレームでスペアを出した場合は21投で終了すること' => [
                true,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 6, 0],
            ],
            '最終フレームまでにストライクがなく、最終フレーム1投目でストライクを出した場合は20投では終了していないこと' => [
                false,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 0],
            ],
            '最終フレームまでにストライクがなく、最終フレーム1投目でストライクを出した場合は21投で終了すること' => [
                true,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 0, 0],
            ],
            '最終フレームまでにストライクがなく、最終フレーム1投目でストライクを出した場合、2投目でストライクを出しても21投で終了すること' => [
                true,
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 10, 0],
            ],
            'パーフェクトゲームは12投で終了すること' => [
                true,
                [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider addThrowResultPins_ExtraThrowDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function addThrowResultPins_ゲーム終了後に更に投げようとするとBadMethodCallExceptionを送出すること(array $pinsList): void
    {
        $this->expectException(BadMethodCallException::class);

        $game = new Game();
        foreach ($pinsList as $pins) {
            $game->addThrowResultPins($pins);
        }
    }

    /**
     * @return array<string, array{list<0|1|2|3|4|5|6|7|8|9|10>}>
     */
    public function addThrowResultPins_ExtraThrowDataProvider(): array
    {
        return [
            'ストライクがなく、最終フレームでスペアも出さなかった場合は20投で終了すること' => [
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0],
            ],
            '最終フレーム以外でストライクを1回出した場合は19投で終了すること' => [
                [10, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0],
            ],
            '最終フレームまでにストライクがなく、最終フレームでスペアを出した場合は21投で終了すること' => [
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 4, 6, 0, 0],
            ],
            '最終フレームまでにストライクがなく、最終フレーム1投目でストライクを出した場合は21投で終了すること' => [
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 0, 0, 0],
            ],
            '最終フレームまでにストライクがなく、最終フレーム1投目でストライクを出した場合、2投目でストライクを出しても21投で終了すること' => [
                [0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 0, 0, 1, 1, 2, 2, 3, 3, 10, 10, 0, 0],
            ],
            'パーフェクトゲームは12投で終了すること' => [
                [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 0],
            ],
        ];
    }
}

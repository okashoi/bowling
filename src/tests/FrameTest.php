<?php

declare(strict_types=1);

namespace Okashoi\Bowling\Tests;

use BadMethodCallException;
use Okashoi\Bowling\Frame;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class FrameTest extends TestCase
{
    /**
     * @test
     */
    public function addThrowResultPins_倒したピンの合計本数が10本を超えてしまう結果を追加しようとするとUnexpectedValueExceptionを送出すること(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $frame = new Frame();
        $frame->addThrowResultPins(5);
        $frame->addThrowResultPins(6);
    }

    /**
     * @test
     */
    public function addThrowResultPins_3回投げようとするとBadMethodCallExceptionを送出すること(): void
    {
        $this->expectException(BadMethodCallException::class);

        $frame = new Frame();
        $frame->addThrowResultPins(1);
        $frame->addThrowResultPins(1);
        $frame->addThrowResultPins(1);
    }

    /**
     * @test
     */
    public function addThrowResultPins_ストライクを取った後に更に投げようとするとBadMethodCallExceptionを送出すること(): void
    {
        $this->expectException(BadMethodCallException::class);

        $frame = new Frame();
        $frame->addThrowResultPins(10);
        $frame->addThrowResultPins(0);
    }

    /**
     * @test
     * @dataProvider isFinishedDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function isFinished(bool $expected, array $pinsList): void
    {
        $frame = new Frame();
        foreach ($pinsList as $pins) {
            $frame->addThrowResultPins($pins);
        }

        $this->assertSame($expected, $frame->isFinished());
    }

    /**
     * @return array<string, array{bool, list<0|1|2|3|4|5|6|7|8|9|10>}>
     */
    public function isFinishedDataProvider(): array
    {
        return [
            '1投もしていないならばフレームは終了していないこと' => [
                false,
                [],
            ],
            '1投したのみ（非ストライク）ならばフレームは終了していないこと' => [
                false,
                [5],
            ],
            '1投したのみ（ガーター）ならばフレームは終了していないこと' => [
                false,
                [0],
            ],
            '2投し終えたならばフレームは終了していること' => [
                true,
                [4, 5],
            ],
            'ストライクを取っていたらフレームは終了していること' => [
                true,
                [10],
            ],
            '2投し終えた（スペア）ならばフレームは終了していること' => [
                true,
                [5, 5],
            ],
            '2投し終えた（ガーターミス）ならばフレームは終了していること' => [
                true,
                [0, 0],
            ],
        ];
    }
}

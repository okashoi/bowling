<?php

declare(strict_types=1);

namespace Okashoi\Bowling\Tests;

use BadMethodCallException;
use Okashoi\Bowling\FinalFrame;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class FinalFrameTest extends TestCase
{
    /**
     * @test
     */
    public function addThrowResultPins_スペアを取っていたら3投目を投げられること(): void
    {
        try {
            $frame = new FinalFrame();
            $frame->addThrowResultPins(5);
            $frame->addThrowResultPins(5);
            $frame->addThrowResultPins(4);
        } catch (UnexpectedValueException | BadMethodCallException) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function addThrowResultPins_ストライクを取っていたら3投目まで投げられること(): void
    {
        try {
            $frame = new FinalFrame();
            $frame->addThrowResultPins(10);
            $frame->addThrowResultPins(5);
            $frame->addThrowResultPins(4);
        } catch (UnexpectedValueException | BadMethodCallException) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider addThrowResultPins_Exceed10PinsDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function addThrowResultPins_倒したピンの合計本数が10本を超えてしまう結果を追加しようとするとUnexpectedValueExceptionを送出すること(array $pinsList): void
    {
        $this->expectException(UnexpectedValueException::class);

        $frame = new FinalFrame();
        foreach ($pinsList as $pins) {
            $frame->addThrowResultPins($pins);
        }
    }

    /**
     * @return array<string, array{list<0|1|2|3|4|5|6|7|8|9|10>}>
     */
    public function addThrowResultPins_Exceed10PinsDataProvider(): array
    {
        return [
            '1, 2投目で10本を超えてしまう場合' => [[5, 6]],
            '1投目にストライクを取ったあと、2, 3投目で10本を超えてしまう場合' => [[10, 5, 6]],
        ];
    }

    /**
     * @test
     * @dataProvider addThrowResultPins_ExtraThrowDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function addThrowResultPins_フレーム終了後に更に投げようとするとBadMethodCallExceptionを送出すること(array $pinsList): void
    {
        $this->expectException(BadMethodCallException::class);

        $frame = new FinalFrame();
        foreach ($pinsList as $pins) {
            $frame->addThrowResultPins($pins);
        }
    }

    /**
     * @return array<string, array{list<0|1|2|3|4|5|6|7|8|9|10>}>
     */
    public function addThrowResultPins_ExtraThrowDataProvider(): array
    {
        return [
            'スペアでもストライクでもないのに3回投げようとした場合' => [[4, 5, 0]],
            '4回投げようとした場合' => [[5, 5, 5, 0]],
        ];
    }

    /**
     * @test
     * @dataProvider isFinishedDataProvider
     * @param list<0|1|2|3|4|5|6|7|8|9|10> $pinsList
     */
    public function isFinished(bool $expected, array $pinsList): void
    {
        $frame = new FinalFrame();
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
            '1投だけしてストライクを取っていたらフレームは終了していないこと' => [
                false,
                [10],
            ],
            '2投し終えてスペアでないならばフレームは終了していること' => [
                true,
                [4, 5],
            ],
            '2投してスペアを取っていたらフレームは終了していないこと' => [
                false,
                [5, 5]
            ],
            '1投目でストライクを取っていたら2投目終了段階でフレームは終了していないこと' => [
                false,
                [10, 5],
            ],
            '2投して両方ともストライクを取っていたらフレームは終了していないこと' => [
                false,
                [10, 10],
            ],
            '3投し終えたならばフレームは終了していること（スペア+3投目）' => [
                true,
                [5, 5, 5],
            ],
            '3投し終えたならばフレームは終了していること（スペア+3投目ストライク）' => [
                true,
                [5, 5, 10],
            ],
            '3投し終えたならばフレームは終了していること（1投目ストライク）' => [
                true,
                [10, 4, 5],
            ],
            '3投し終えたならばフレームは終了していること（1投目ストライク+スペア）' => [
                true,
                [10, 5, 5],
            ],
            '3投し終えたならばフレームは終了していること（1, 2投目ストライク）' => [
                true,
                [10, 10, 5],
            ],
            '3投し終えたならばフレームは終了していること（3投ともストライク）' => [
                true,
                [10, 10, 10],
            ],
        ];
    }
}

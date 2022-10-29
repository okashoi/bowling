<?php

declare(strict_types=1);

namespace Okashoi\Bowling\Tests;

use Okashoi\Bowling\{ThrowResult, ThrowResultType};
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class ThrowResultTest extends TestCase
{
    /**
     * @test
     */
    public function 倒したピンの本数が10本ならスペアかストライクどちらかであること(): void
    {
        $this->expectException(UnexpectedValueException::class);

        new ThrowResult(10, ThrowResultType::None);
    }

    /**
     * @test
     */
    public function 倒したピンの本数が0本ならスペアになり得ないこと(): void
    {
        $this->expectException(UnexpectedValueException::class);

        new ThrowResult(0, ThrowResultType::Spare);
    }

    /**
     * @test
     */
    public function 倒したピンの本数が10本以外ならストライクになり得ないこと(): void
    {
        $this->expectException(UnexpectedValueException::class);

        new ThrowResult(9, ThrowResultType::Strike);
    }
}

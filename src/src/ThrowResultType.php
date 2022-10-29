<?php

declare(strict_types=1);

namespace Okashoi\Bowling;

enum ThrowResultType
{
    case Strike;
    case Spare;
    case None;
}

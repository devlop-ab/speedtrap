<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

interface SpeedtrapServiceInterface
{
    public function getInputName() : string;

    public function getThreshold() : int;
}

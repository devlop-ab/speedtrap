<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

use Devlop\Speedtrap\Speedtrap;

final class SpeedtrapTriggeredEvent
{
    private Speedtrap $speedtrap;

    /**
     * Create a new event instance.
     *
     * @param  Speedtrap  $speedtrap
     * @return void
     */
    public function __construct(Speedtrap $speedtrap)
    {
        $this->speedtrap = $speedtrap;
    }

    /**
     * Get the speedtrap
     */
    public function getSpeedtrap() : Speedtrap
    {
        return $this->speedtrap;
    }
}

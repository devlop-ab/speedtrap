<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

final class SpeedtrapService implements SpeedtrapServiceInterface
{
    private string $inputName;

    /**
     * Threshold in seconds
     */
    private int $threshold;

    /**
     * Instantiate the service
     *
     * @param  string  $inputName
     * @param  int  $threshold
     * @return void
     */
    public function __construct(string $inputName, int $threshold)
    {
        $this->inputName = $inputName;

        $this->threshold = $threshold;
    }

    /**
     * Get the input name
     */
    public function getInputName() : string
    {
        return $this->inputName;
    }

    /**
     * Get the threshold
     */
    public function getThreshold() : int
    {
        return $this->threshold;
    }
}

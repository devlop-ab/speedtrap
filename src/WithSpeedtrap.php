<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

use Devlop\Speedtrap\Speedtrap;
use Devlop\Speedtrap\SpeedtrapNotTriggeredRule;
use Devlop\Speedtrap\SpeedtrapServiceInterface;
use Illuminate\Contracts\Validation\Rule;

trait WithSpeedtrap
{
    private ?SpeedtrapServiceInterface $speedtrapService = null;

    /**
     * Add the speedtrap rules with the existing ruleset
     *
     * @return array<string,string|array<string|Rule>>
     */
    private function withSpeedtrap(array $rules) : array
    {
        return $rules + [
            $this->getSpeedtrapInputName() => $this->speedtrapRules(),
        ];
    }

    /**
     * Get the speedtrap rules
     *
     * @return array<Rule>
     */
    private function speedtrapRules() : array
    {
        return [
            new SpeedtrapNotTriggeredRule($this),
        ];
    }

    /**
     * Get the input name
     */
    private function getSpeedtrapInputName() : string
    {
        return $this->getSpeedtrapService()->getInputName();
    }

    /**
     * Get the threshold
     */
    private function getSpeedtrapThreshold() : int
    {
        return $this->getSpeedtrapService()->getThreshold();
    }

    /**
     * Get the speedtrap service
     */
    private function getSpeedtrapService() : SpeedtrapServiceInterface
    {
        return $this->speedtrapService ??= $this->container->make(SpeedtrapServiceInterface::class);
    }

    /**
     * Get the speedtrap
     */
    public function speedtrap() : Speedtrap
    {
        return new Speedtrap($this, $this->getSpeedtrapInputName(), $this->getSpeedtrapThreshold(), $this->getValidatorInstance());
    }
}

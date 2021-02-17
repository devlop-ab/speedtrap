<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

use DateTimeImmutable;
use Devlop\Speedtrap\SpeedtrapServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Component;

final class SpeedtrapComponent extends Component
{
    private string $inputName;

    private bool $speedtrapWasTriggered;

    /**
     * Create a new component instance.
     *
     * @param  SpeedtrapServiceInterface  $service
     * @param  Request  $request
     * @return void
     */
    public function __construct(SpeedtrapServiceInterface $service, Request $request)
    {
        $this->inputName = $service->getInputName();

        $this->speedtrapWasTriggered = ($request->session()->get('errors') ?: new ViewErrorBag)->has($this->inputName);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render() : View
    {
        return view('speedtrap::components.speedtrap', [
            'inputName' => $this->inputName,
            'inputValue' => $this->getInputValue(),
            'speedtrapWasTriggered' => $this->speedtrapWasTriggered,
        ]);
    }

    /**
     * Get the input value
     */
    private function getInputValue() : string
    {
        return (string) (new DateTimeImmutable)->getTimestamp();
    }

    /**
     * Determine if the component should be rendered.
     */
    public function shouldRender() : bool
    {
        return true;
    }
}

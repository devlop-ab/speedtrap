<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

use DatetimeImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

final class Speedtrap
{
    /**
     * @var array<string,mixed>
     */
    private array $request;

    private string $inputName;

    private int $threshold;

    private ?int $timeTaken;

    /**
     * Create a new Speedtrap instance
     *
     * @param  FormRequest  $request
     * @param  string  $inputName
     * @param  int  $threshold
     * @return void
     */
    public function __construct(FormRequest $request, string $inputName, int $threshold)
    {
        $this->request = $request->input();

        $this->inputName = $inputName;

        $this->threshold = $threshold;

        $this->timeTaken = $this->calculateTimeTaken();
    }

    /**
     * Calculate the time taken
     */
    private function calculateTimeTaken() : ?int
    {
        $value = $this->value();

        if (! $value) {
            // no valid timestamp, input is either missing or being tampered with
            return null;
        }

        $timeTaken = (new DatetimeImmutable)->getTimestamp() - (int) $value;

        return max(0, $timeTaken);
    }

    /**
     * Get the speedtrap value, null if no valid value found
     */
    public function value() : ?string
    {
        $value = Arr::get($this->request, $this->inputName);

        return is_numeric($value)
            ? (string) $value
            : null;
    }

    /**
     * Number of seconds that it took the user to submit the form
     */
    public function timeTaken() : ?int
    {
        return $this->timeTaken;
    }

    /**
     * If the speedtrap is triggered, the speedtrap is
     * considered to be triggered if the value is either
     * an invalid timestamp or not older than threshold
     */
    public function triggered() : bool
    {
        $timeTaken = $this->timeTaken();

        if ($timeTaken === null) {
            // unknown duration, input is either missing or being
            // tampered with, treat this as being triggered
            return true;
        }

        return $timeTaken < $this->threshold
            ? true // the request was made quicker than allowed, the speedtrap was triggered
            : false;
    }
}

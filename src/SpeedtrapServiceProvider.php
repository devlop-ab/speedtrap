<?php

declare(strict_types=1);

namespace Devlop\Speedtrap;

use Devlop\Speedtrap\SpeedtrapComponent;
use Devlop\Speedtrap\SpeedtrapServiceInterface;
use Devlop\Speedtrap\SpeedtrapTriggeredEvent;
use Devlop\Speedtrap\WithSpeedtrap;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class SpeedtrapServiceProvider extends ServiceProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array<class-string>
     */
    public function provides() : array
    {
        return [
            SpeedtrapServiceInterface::class,
        ];
    }

    /**
     * Register the service provider.
     */
    public function register() : void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'speedtrap');

        $this->app->singleton(SpeedtrapServiceInterface::class, function (Application $app) : SpeedtrapServiceInterface {
            $config = $this->app['config']->get('speedtrap');

            $inputName = $config['input-name'] ?? $this->generateInputName($this->app['config']->get('app.name'));

            $threshold = $config['threshold'];

            return new SpeedtrapService($inputName, $threshold);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'speedtrap');

        $this->publishes(
            [
                $this->getConfigPath() => config_path('speedtrap.php'),
            ],
            'config',
        );

        $config = $this->app['config']->get('speedtrap');

        Blade::components([
            SpeedtrapComponent::class => $config['component-name'],
        ]);

        $this->app->resolving(FormRequest::class, function (FormRequest $request) : void {
            if (! in_array(WithSpeedtrap::class, class_uses($request), true)) {
                return;
            }

            $speedtrap = $request->speedtrap();

            if (! $speedtrap->triggered()) {
                return;
            }

            $this->app['events']->dispatch(
                new SpeedtrapTriggeredEvent($speedtrap),
            );
        });
    }

    /**
     * Get the speedtrap config path
     */
    private function getConfigPath() : string
    {
        return __DIR__ . '/../config/speedtrap.php';
    }

    /**
     * Generate the input name from the application name
     */
    private function generateInputName(string $applicationName) : string
    {
        return 'ts_' . substr(md5($applicationName), 10, 10); // timestamp_(generated part)
    }
}

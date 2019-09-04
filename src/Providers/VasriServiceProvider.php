<?php


namespace ExoUNX\Vasri\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Laravel
 * Class VasriServiceProvider
 *
 * @package ExoUNX\Vasri
 * @author  Gaige Lama <gaigelama@gmail.com>
 * @license MIT License
 * @link    https://github.com/ExoUNX/Vasri
 */
class VasriServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__.'/../config/vasri.php' => config_path('vasri.php'),
            ]
        );
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            base_path('/config/vasri.php'), 'vasri'
        );
        $this->commands([
            \ExoUNX\Vasri\Commands\VasriCommand::class,
        ]);
    }

}

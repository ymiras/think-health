<?php

declare(strict_types=1);
/**
 * This file is part of ymiras.
 *
 * @link     https://www.ymiras.com
 * @contact  support@ymiras.com
 * @license  https://github.com/ymiras/think-health/licenses
 */

namespace Ymiras\ThinkHealth;

use think\facade\Event;
use think\facade\Route;
use think\Response;
use think\Service;
use Throwable;
use Ymiras\ThinkHealth\Event\DiagnosingHealth;

class ServiceProvider extends Service
{
    /**
     * Register service.
     */
    public function register(): void
    {
        $this->registerEventListeners();
        $this->registerHealthCheckRoute();
    }

    /**
     * Register health check event listeners.
     */
    private function registerEventListeners(): void
    {
        $config = config('health');
        $listeners = $config['listeners'] ?? [];

        // Register health check event listeners
        $this->app->event->listenEvents([
            DiagnosingHealth::class => $listeners,
        ]);
    }

    /**
     * Register health check route.
     */
    private function registerHealthCheckRoute(): void
    {
        $this->registerRoutes(function () {
            $config = config('health');
            $uri = $config['uri'] ?? '/health';
            $timeout = $config['timeout'] ?? 5;

            Route::get($uri, function () use ($timeout) {
                return $this->handleHealthCheck($timeout);
            });
        });
    }

    /**
     * Handle health check request.
     *
     * @throws Throwable
     */
    private function handleHealthCheck(int $timeout): Response
    {
        // Set script timeout
        set_time_limit($timeout);

        $exception = null;
        try {
            Event::trigger(DiagnosingHealth::class);
        } catch (Throwable $e) {
            // Show detailed error in debug mode
            if (app()->isDebug()) {
                throw $e;
            }

            $exception = $e->getMessage();
        }

        return Response::create(
            $exception ?? 'ok',
            'html',
            $exception ? 500 : 200
        );
    }
}

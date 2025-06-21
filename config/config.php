<?php

declare(strict_types=1);
/**
 * This file is part of ymiras.
 *
 * @link     https://www.ymiras.com
 * @contact  support@ymiras.com
 * @license  https://github.com/ymiras/think-health/licenses
 */
use Ymiras\ThinkHealth\Listener\CheckDatabaseConnection;
use Ymiras\ThinkHealth\Listener\CheckEnvFile;

return [
    /*
     * Health check endpoint path
     *
     * Accessing this path will trigger the health check and return the application status.
     * Recommended to use a simple path, such as /health, /up, /ping, etc.
     *
     * @var string
     */
    'uri' => '/health',

    /*
     * Custom health check listeners
     *
     * You can add your own health check logic here.
     * Each listener class must implement a handle method and accept the DiagnosingHealth event.
     *
     * @var array
     * @example [
     *     \App\Listener\CheckRedisConnection::class,
     *     \App\Listener\CheckExternalApi::class,
     * ]
     */
    'listeners' => [
        // Default listeners (env file check, database connection check)
        // Comment out the following lines to disable default listeners
        CheckEnvFile::class,
        CheckDatabaseConnection::class,

        // Add your custom listeners here
        // \App\Listener\CheckRedisConnection::class,
        // \App\Listener\CheckExternalApi::class,
        // \App\Listener\CheckDiskSpace::class,
    ],

    /*
     * Timeout setting (seconds)
     *
     * @var int
     */
    'timeout' => 5,
];

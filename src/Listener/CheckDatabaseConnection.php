<?php

declare(strict_types=1);
/**
 * This file is part of ymiras.
 *
 * @link     https://www.ymiras.com
 * @contact  support@ymiras.com
 * @license  https://github.com/ymiras/think-health/licenses
 */

namespace Ymiras\ThinkHealth\Listener;

use Exception;
use think\facade\Db;
use Ymiras\ThinkHealth\Event\DiagnosingHealth;

class CheckDatabaseConnection
{
    /**
     * Health check for database connection.
     *
     * @throws Exception
     */
    public function handle(DiagnosingHealth $event)
    {
        try {
            // Get all configured database connections
            $connections = config('database.connections') ?? [];
            $default = config('database.default') ?? 'mysql';

            // Always check the default connection
            $toCheck = array_unique(array_merge([$default], array_keys($connections)));

            foreach ($toCheck as $connection) {
                // Get the connection instance
                $conn = Db::connect($connection);
                // Try to get a table list as a health check (no data read, just metadata)
                $conn->getTables();
                // Close the connection
                $conn->close();
            }
        } catch (Exception $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
}

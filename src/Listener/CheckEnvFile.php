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
use Ymiras\ThinkHealth\Event\DiagnosingHealth;

class CheckEnvFile
{
    /**
     * @throws Exception
     */
    public function handle(DiagnosingHealth $event)
    {
        $envFilePath = rtrim(root_path('.env'), DIRECTORY_SEPARATOR);

        if (! file_exists($envFilePath)) {
            throw new Exception('Env file not found');
        }
    }
}

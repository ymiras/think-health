# ThinkHealth - ThinkPHP 健康检查库

一个为 ThinkPHP 框架设计的健康检查库，支持自定义健康检查监听器和灵活的配置选项。

## 功能特性

- 🚀 简单易用的健康检查接口
- 🔧 可自定义的健康检查监听器
- ⚙️ 灵活的配置选项
- 📊 详细的检查结果和性能统计

## 安装

通过 Composer 安装：

```bash
composer require ymiras/think-health
```

## 基本使用

安装后，健康检查接口会自动注册到你的应用中。默认情况下，你可以通过访问 `/health` 路径来进行健康检查：

```bash
curl http://your-app.com/health
```

### 成功响应

```
HTTP/1.1 200 OK
Content-Type: text/html

ok
```

### 失败响应

```
HTTP/1.1 500 Internal Server Error
Content-Type: text/html

Database connection failed: SQLSTATE[HY000] [2002] Connection refused
```

## 配置选项

在 `config/health.php` 文件中配置健康检查行为：

```php
<?php

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
```

## 创建自定义监听器

你可以创建自定义的健康检查监听器来检查特定的服务或资源：

```php
<?php

namespace App\Listener;

use Ymiras\ThinkHealth\Event\DiagnosingHealth;

class CheckRedisConnection
{
    public function handle(DiagnosingHealth $event)
    {
        try {
            // 检查 Redis 连接
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379, 5);

            $result = $redis->ping();
            if ($result !== '+PONG' && $result !== true) {
                throw new \Exception('Redis ping failed');
            }

            $redis->close();

        } catch (\Exception $e) {
            throw new \Exception('Redis connection failed: ' . $e->getMessage());
        }
    }
}
```

然后在配置文件中添加这个监听器：

```php
'listeners' => [
    // ... 其他监听器
    \App\Listener\CheckRedisConnection::class,
],
```

## 内置监听器

### CheckEnvFile

检查项目根目录下是否存在 `.env` 文件。

### CheckDatabaseConnection

检查数据库连接是否正常，通过执行 `SELECT 1` 查询来验证。

## 超时控制

设置健康检查的超时时间：

```php
'timeout' => 5
```

## 最佳实践

1. **选择合适的路径**：使用简洁的路径如 `/health`、`/up`、`/ping`
2. **合理设置超时**：根据你的服务响应时间设置合适的超时时间
3. **监控关键服务**：添加对数据库、缓存、外部 API 等关键服务的检查
4. **避免过度检查**：只检查真正影响应用可用性的服务

## 许可证

MIT License

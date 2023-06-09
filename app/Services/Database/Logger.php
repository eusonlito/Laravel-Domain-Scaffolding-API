<?php declare(strict_types=1);

namespace App\Services\Database;

class Logger
{
    /**
     * @var array
     */
    protected static array $connections = [];

    /**
     * @return void
     */
    public static function listen(): void
    {
        foreach (config('database.connections') as $name => $config) {
            static::connection($name, $config);
        }
    }

    /**
     * @param string $name
     * @param array $config
     *
     * @return void
     */
    protected static function connection(string $name, array $config): void
    {
        static::$connections[$name] = LoggerConnection::new($name, $config)->listen();
    }
}

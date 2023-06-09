<?php declare(strict_types=1);

namespace App\Services\Database;

use DateTime;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class LoggerConnection
{
    /**
     * @var string
     */
    protected string $file;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param string $name
     * @param array $config
     *
     * @return self
     */
    public function __construct(protected string $name, protected array $config)
    {
        $this->setConfig();
    }

    /**
     * @return void
     */
    protected function setConfig(): void
    {
        $this->config += [
            'log' => false,
            'log_slow' => false,
            'log_backtrace' => false,
        ];

        $this->config['enabled'] = $this->getConfigEnabled();
    }

    /**
     * @return bool
     */
    protected function getConfigEnabled(): bool
    {
        if (config('logging.channels.database.enabled') !== true) {
            return false;
        }

        return $this->config['log']
            || $this->config['log_slow'];
    }

    /**
     * @return self
     */
    public function listen(): self
    {
        if ($this->config['enabled']) {
            $this->connection();
        }

        return $this;
    }

    /**
     * @return void
     */
    protected function connection(): void
    {
        DB::connection($this->name)->listen($this->connectionListen(...));
    }

    /**
     * @param \Illuminate\Database\Events\QueryExecuted $sql
     *
     * @return void
     */
    protected function connectionListen(QueryExecuted $sql): void
    {
        $slow = $this->queryIsSlow($sql);

        if (($slow === false) && ($this->config['log'] === false)) {
            return;
        }

        $name = $sql->connectionName;
        $query = $sql->sql;
        $bindings = $sql->bindings;

        foreach ($bindings as $i => $binding) {
            if ($binding instanceof DateTime) {
                $bindings[$i] = $binding->format('Y-m-d H:i:s');
            } elseif (is_string($binding)) {
                $bindings[$i] = "'{$binding}'";
            } elseif (is_bool($binding)) {
                $bindings[$i] = $binding ? 'true' : 'false';
            }

            if (is_string($i)) {
                $query = str_replace(':'.$i, (string)$bindings[$i], $query);
            }
        }

        $message = $this->backtrace()
            .'['.$sql->time.'] '
            .'['.($slow ? 'SLOW' : '').'] '
            .vsprintf(str_replace(['%', '?'], ['%%', '%s'], $query), $bindings);

        $this->log($message);
    }

    /**
     * @return string
     */
    protected function backtrace(): string
    {
        if (empty($this->config['log_backtrace'])) {
            return '';
        }

        $backtrace = current(array_filter(
            debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
            fn ($line) => $this->backtraceIsValid($line)
        ));

        if (empty($backtrace)) {
            return "\n".'#'."\n\n";
        }

        return "\n".'# '.str_replace($this->base, '', $backtrace['file']).'#'.$backtrace['line']."\n\n";
    }

    /**
     * @param array $line
     *
     * @return bool
     */
    protected function backtraceIsValid(array $line): bool
    {
        if (empty($line['file'])) {
            return false;
        }

        $file = $line['file'];

        if ($file === __FILE__) {
            return false;
        }

        return str_starts_with($file, $this->base.'/app/')
            || str_starts_with($file, $this->base.'/config/')
            || str_starts_with($file, $this->base.'/database/');
    }

    /**
     * @param \Illuminate\Database\Events\QueryExecuted $sql
     *
     * @return bool
     */
    protected function queryIsSlow(QueryExecuted $sql): bool
    {
        return $this->config['log_slow']
            && ($sql->time >= $this->config['log_slow']);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function log(string $message): void
    {
        $this->logHeader();
        $this->logContents($message);
    }

    /**
     * @return void
     */
    protected function logHeader(): void
    {
        if (empty($this->file)) {
            $this->write($this->logHeaderMessage());
        }
    }

    /**
     * @return string
     */
    protected function logHeaderMessage(): string
    {
        return "\n".'['.date('Y-m-d H:i:s').'] ['.Request::method().'] '.Request::fullUrl()."\n";
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function logContents(string $message): void
    {
        $this->write($this->logContentsMessage($message));
    }

    /**
     * @param string $message
     *
     * @return string
     */
    protected function logContentsMessage(string $message): string
    {
        return "\n".preg_replace(["/\n+/", "/\n\s+/"], ["\n", ' '], $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function write(string $message): void
    {
        file_put_contents($this->file(), $message, FILE_APPEND | LOCK_EX);
    }

    /**
     * @return string
     */
    protected function file(): string
    {
        if (isset($this->file)) {
            return $this->file;
        }

        $file = array_filter(explode('-', preg_replace('/[^a-z0-9]+/i', '-', Request::path())));
        $file = implode('-', array_map(fn ($value) => substr($value, 0, 20), $file)) ?: '-';
        $file = 'logs/query/'.$this->name.'/'.date('Y-m-d').'/'.substr($file, 0, 150).'.log';
        $file = storage_path($file);

        helper()->mkdir($file, true);

        return $this->file = $file;
    }
}

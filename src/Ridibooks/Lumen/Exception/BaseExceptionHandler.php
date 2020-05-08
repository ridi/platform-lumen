<?php
declare(strict_types=1);

namespace Ridibooks\Lumen\Exception;

use Laravel\Lumen\Exceptions\Handler;

class BaseExceptionHandler extends Handler
{
    public function report(\Throwable $e)
    {
        if (!$this->shouldntReport($e)) {
            if (method_exists($e, 'report')) {
                return $e->report();
            }

            $this->callAdditional($e);

            if (env('APP_DEBUG', false)) {
                parent::report($e);
            }
        }
    }

    protected function callAdditional(\Throwable $e): void
    {
    }
}

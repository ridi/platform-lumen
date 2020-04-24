<?php
declare(strict_types=1);

namespace Ridibooks\Exception;

use Laravel\Lumen\Exceptions\Handler;

class BaseExceptionHandler extends Handler
{
    protected function updateDontReport(array $dont_report_exceptions = [])
    {
        $this->dontReport = $dont_report_exceptions;
    }

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

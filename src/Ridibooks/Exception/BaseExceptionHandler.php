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
}

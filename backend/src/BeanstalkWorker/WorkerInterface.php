<?php

namespace App\BeanstalkWorker;

interface WorkerInterface
{
    public function runJob();
}

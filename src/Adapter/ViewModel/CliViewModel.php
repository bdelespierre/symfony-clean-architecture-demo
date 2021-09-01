<?php

namespace App\Adapter\ViewModel;

use Domain\Contract\ViewModel;
use Symfony\Component\Console\Output\OutputInterface;

class CliViewModel implements ViewModel
{
    public function __construct(
        private \Closure $handler
    ) {
    }

    public function handle(OutputInterface $output): mixed
    {
        return call_user_func($this->handler, $output);
    }
}

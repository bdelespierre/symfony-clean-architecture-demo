<?php

namespace App\Adapter\ViewModel;

use Domain\Contract\ViewModel;
use Symfony\Component\Console\Output\OutputInterface;

class CliViewModel implements ViewModel
{
    private \Closure $handler;

    public function __construct(\Closure $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return int
     */
    public function handle(OutputInterface $output)
    {
        return call_user_func($this->handler, $output);
    }
}

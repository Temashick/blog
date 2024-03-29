<?php

namespace MyProject\Cli;

use MyProject\Exceptions\CliException;

class Minusator extends AbstractCommand
{
    public function execute()
    {
        echo $this->getParam('a') - $this->getParam('b');
    }

    protected function checkParams()
    {
        $this->ensureParamExists('a');
        $this->ensureParamExists('b');
    }
}

<?php

namespace OutSource\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;

class Application extends SymfonyApplication
{

    public function __construct()
    {
        parent::__construct("outSource", "v1.0");
    }




}